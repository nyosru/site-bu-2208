<?php

namespace App\Console\Commands;

use App\Support\AdvertisementCreator;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use JsonException;
use RuntimeException;
use Throwable;

class ConsumeAdvertisementTasksCommand extends Command
{
    protected $signature = 'kafka:consume-advertisement-tasks
        {--max-messages=0 : Stop after N processed messages (0 = infinite)}
        {--poll-timeout-ms=1000 : Poll timeout in milliseconds}
        {--stop-when-empty : Stop on timeout/EOF when no messages are available}';

    protected $description = 'Consume advertisement creation tasks from Kafka and write advertisements to DB';

    public function __construct(
        private readonly AdvertisementCreator $creator,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        if (! class_exists(\RdKafka\KafkaConsumer::class)) {
            $this->error('php-rdkafka extension is not installed.');

            return self::FAILURE;
        }

        if (! (bool) config('kafka.enabled', false)) {
            $this->error('Kafka is disabled. Set KAFKA_ENABLED=true.');

            return self::FAILURE;
        }

        $topic = (string) config('kafka.tasks_topic', 'advertisements.create.tasks');
        $consumer = $this->makeConsumer();
        $consumer->subscribe([$topic]);

        $maxMessages = max(0, (int) $this->option('max-messages'));
        $pollTimeoutMs = max(100, (int) $this->option('poll-timeout-ms'));
        $stopWhenEmpty = (bool) $this->option('stop-when-empty');
        $processed = 0;
        $idlePolls = 0;

        $this->info("Consuming Kafka topic [{$topic}]...");

        while (true) {
            $message = $consumer->consume($pollTimeoutMs);

            if ($message->err === RD_KAFKA_RESP_ERR_NO_ERROR) {
                $idlePolls = 0;
                $shouldCommit = $this->processMessage($message->payload);
                if ($shouldCommit) {
                    $consumer->commit($message);
                    $processed++;
                }

                if ($maxMessages > 0 && $processed >= $maxMessages) {
                    $this->info("Processed {$processed} message(s). Stopping.");

                    return self::SUCCESS;
                }

                continue;
            }

            if (
                $message->err === RD_KAFKA_RESP_ERR__TIMED_OUT
                || $message->err === RD_KAFKA_RESP_ERR__PARTITION_EOF
            ) {
                if ($stopWhenEmpty) {
                    $idlePolls++;
                    if ($idlePolls >= 3) {
                        $this->info("No new messages. Stopped after {$processed} processed message(s).");

                        return self::SUCCESS;
                    }
                }

                continue;
            }

            if ($this->isRetriableKafkaError($message->err)) {
                Log::warning('Kafka consume temporary error. Retrying...', [
                    'error' => $message->errstr(),
                    'code' => $message->err,
                ]);

                usleep(500_000);

                continue;
            }

            Log::error('Kafka consume error', [
                'error' => $message->errstr(),
                'code' => $message->err,
            ]);

            $this->error('Kafka consume error: '.$message->errstr());

            return self::FAILURE;
        }
    }

    private function makeConsumer(): \RdKafka\KafkaConsumer
    {
        $brokers = trim((string) config('kafka.brokers', ''));
        if ($brokers === '') {
            throw new RuntimeException('Kafka brokers are not configured.');
        }

        $groupId = trim((string) config('kafka.consumer_group_id', 'ads-create-consumer'));
        if ($groupId === '') {
            throw new RuntimeException('Kafka consumer group id is not configured.');
        }

        $conf = new \RdKafka\Conf;
        $conf->set('bootstrap.servers', $brokers);
        $conf->set('group.id', $groupId);
        $conf->set('enable.auto.commit', 'false');
        $conf->set('auto.offset.reset', (string) config('kafka.consumer_auto_offset_reset', 'earliest'));

        $this->applySecurity($conf);

        return new \RdKafka\KafkaConsumer($conf);
    }

    private function applySecurity(\RdKafka\Conf $conf): void
    {
        $securityProtocol = trim((string) config('kafka.security_protocol', ''));
        if ($securityProtocol !== '') {
            $conf->set('security.protocol', $securityProtocol);
        }

        $saslMechanism = trim((string) config('kafka.sasl_mechanism', ''));
        if ($saslMechanism !== '') {
            $conf->set('sasl.mechanisms', $saslMechanism);
        }

        $username = trim((string) config('kafka.username', ''));
        $password = trim((string) config('kafka.password', ''));
        if ($username !== '' && $password !== '') {
            $conf->set('sasl.username', $username);
            $conf->set('sasl.password', $password);
        }
    }

    private function processMessage(string $rawPayload): bool
    {
        try {
            /** @var array<string, mixed> $data */
            $data = json_decode($rawPayload, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            Log::error('Kafka advertisement task payload is not valid JSON.', ['error' => $e->getMessage()]);

            return true;
        }

        $validator = Validator::make($data, [
            'task_id' => ['required', 'string', 'max:255'],
            'payload' => ['required', 'array'],
            'payload.user_id' => ['required', 'integer', 'min:1'],
            'payload.catalog_id' => ['required', 'integer', 'min:1'],
            'payload.title' => ['required', 'string', 'max:255'],
            'payload.description' => ['required', 'string'],
            'payload.price' => ['required', 'numeric', 'min:0'],
            'payload.ad_type' => ['required', 'in:sell,buy'],
            'payload.photo_paths' => ['required', 'array', 'min:1'],
            'payload.photo_paths.*' => ['required', 'string', 'max:2048'],
        ]);

        if ($validator->fails()) {
            Log::error('Kafka advertisement task payload validation failed.', [
                'errors' => $validator->errors()->toArray(),
            ]);

            return true;
        }

        $taskId = (string) $data['task_id'];
        /** @var array<string, mixed> $payload */
        $payload = $data['payload'];

        try {
            $processed = $this->handleTask($taskId, $payload);
        } catch (Throwable $e) {
            Log::error('Kafka advertisement task handling failed.', [
                'task_id' => $taskId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }

        if ($processed === 'duplicate') {
            Log::info('Kafka advertisement task already processed.', ['task_id' => $taskId]);
        } else {
            Log::info('Kafka advertisement task processed.', [
                'task_id' => $taskId,
                'advertisement_id' => $processed,
            ]);
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return int|'duplicate'
     */
    private function handleTask(string $taskId, array $payload): int|string
    {
        $alreadyProcessed = DB::table('processed_kafka_tasks')
            ->where('task_id', $taskId)
            ->exists();

        if ($alreadyProcessed) {
            return 'duplicate';
        }

        try {
            return DB::transaction(function () use ($taskId, $payload): int {
                $advertisement = $this->creator->create((int) $payload['user_id'], [
                    'catalog_id' => (int) $payload['catalog_id'],
                    'title' => (string) $payload['title'],
                    'description' => (string) $payload['description'],
                    'price' => $payload['price'],
                    'ad_type' => (string) $payload['ad_type'],
                    'photo_paths' => array_values((array) $payload['photo_paths']),
                ]);

                DB::table('processed_kafka_tasks')->insert([
                    'task_id' => $taskId,
                    'advertisement_id' => (int) $advertisement->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return (int) $advertisement->id;
            });
        } catch (QueryException $e) {
            if ($this->isUniqueTaskViolation($e)) {
                return 'duplicate';
            }

            throw $e;
        }
    }

    private function isUniqueTaskViolation(QueryException $e): bool
    {
        $sqlState = (string) ($e->errorInfo[0] ?? '');

        return in_array($sqlState, ['23000', '23505'], true);
    }

    private function isRetriableKafkaError(int $errorCode): bool
    {
        return in_array($errorCode, [
            RD_KAFKA_RESP_ERR_UNKNOWN_TOPIC_OR_PART,
            RD_KAFKA_RESP_ERR__TRANSPORT,
            RD_KAFKA_RESP_ERR__ALL_BROKERS_DOWN,
        ], true);
    }
}
