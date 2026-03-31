<?php

namespace App\Support;

use Illuminate\Support\Str;
use JsonException;
use RuntimeException;
use Throwable;

class KafkaAdvertisementTaskProducer implements AdvertisementTaskProducerInterface
{
    /**
     * @param  array{catalog_id:int,title:string,description:string,price:numeric-string|int|float,ad_type:string,photo_paths:array<int,string>}  $payload
     */
    public function dispatchCreateTask(int $userId, array $payload, ?string $taskId = null): string
    {
        if (! (bool) config('kafka.enabled', false)) {
            throw new RuntimeException('Kafka is disabled. Unable to dispatch advertisement task.');
        }

        $taskId ??= (string) Str::uuid();

        try {
            $this->publish($taskId, $userId, $payload);
        } catch (Throwable $exception) {
            if ((bool) config('kafka.throw_on_failure', false)) {
                throw $exception;
            }

            throw new RuntimeException(
                'Failed to dispatch advertisement task to Kafka: '.$exception->getMessage(),
                previous: $exception
            );
        }

        return $taskId;
    }

    /**
     * @param  array{catalog_id:int,title:string,description:string,price:numeric-string|int|float,ad_type:string,photo_paths:array<int,string>}  $payload
     *
     * @throws JsonException
     */
    private function publish(string $taskId, int $userId, array $payload): void
    {
        if (! class_exists(\RdKafka\Producer::class)) {
            throw new RuntimeException('php-rdkafka extension is not installed.');
        }

        $brokers = (string) config('kafka.brokers', '');
        if (trim($brokers) === '') {
            throw new RuntimeException('Kafka brokers are not configured.');
        }

        $conf = new \RdKafka\Conf;
        $conf->set('bootstrap.servers', $brokers);
        $this->applySecurity($conf);

        $producer = new \RdKafka\Producer($conf);

        $message = json_encode([
            'task_id' => $taskId,
            'event' => 'advertisement.create.requested',
            'payload' => [
                'user_id' => $userId,
                'catalog_id' => (int) $payload['catalog_id'],
                'title' => (string) $payload['title'],
                'description' => (string) $payload['description'],
                'price' => (float) $payload['price'],
                'ad_type' => (string) $payload['ad_type'],
                'photo_paths' => array_values($payload['photo_paths']),
            ],
            'requested_at' => now()->toAtomString(),
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        $topic = $producer->newTopic((string) config('kafka.tasks_topic', 'advertisements.create.tasks'));
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message, $taskId);

        $flushResult = $producer->flush((int) config('kafka.flush_timeout_ms', 10_000));
        if ($flushResult !== RD_KAFKA_RESP_ERR_NO_ERROR) {
            throw new RuntimeException('Kafka producer flush failed.');
        }
    }

    private function applySecurity(\RdKafka\Conf $conf): void
    {
        $securityProtocol = (string) config('kafka.security_protocol', '');
        if ($securityProtocol !== '') {
            $conf->set('security.protocol', $securityProtocol);
        }

        $saslMechanism = (string) config('kafka.sasl_mechanism', '');
        if ($saslMechanism !== '') {
            $conf->set('sasl.mechanisms', $saslMechanism);
        }

        $username = (string) config('kafka.username', '');
        $password = (string) config('kafka.password', '');
        if ($username !== '' && $password !== '') {
            $conf->set('sasl.username', $username);
            $conf->set('sasl.password', $password);
        }
    }
}
