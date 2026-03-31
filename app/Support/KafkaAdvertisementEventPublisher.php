<?php

namespace App\Support;

use App\Models\Advertisement;
use Illuminate\Support\Facades\Log;
use JsonException;
use RuntimeException;
use Throwable;

class KafkaAdvertisementEventPublisher implements AdvertisementEventPublisherInterface
{
    public function publishCreated(Advertisement $advertisement): void
    {
        if (! (bool) config('kafka.enabled', false)) {
            return;
        }

        try {
            $this->publish($advertisement);
        } catch (Throwable $exception) {
            Log::error('Failed to publish advertisement.created event to Kafka.', [
                'advertisement_id' => $advertisement->id,
                'exception' => $exception->getMessage(),
            ]);

            if ((bool) config('kafka.throw_on_failure', false)) {
                throw $exception;
            }
        }
    }

    /**
     * @throws JsonException
     */
    private function publish(Advertisement $advertisement): void
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

        $producer = new \RdKafka\Producer($conf);

        $message = json_encode([
            'event' => 'advertisement.created',
            'advertisement_id' => (int) $advertisement->id,
            'user_id' => (int) $advertisement->user_id,
            'catalog_id' => (int) $advertisement->catalog_id,
            'title' => $advertisement->title,
            'description' => $advertisement->description,
            'price' => $advertisement->price !== null ? (float) $advertisement->price : null,
            'type' => $advertisement->type,
            'photo_paths' => $advertisement->photos()
                ->orderBy('sort_order')
                ->pluck('path')
                ->values()
                ->all(),
            'created_at' => $advertisement->created_at?->toAtomString(),
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        $topic = $producer->newTopic((string) config('kafka.topic', 'advertisements.created'));
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message, (string) $advertisement->id);

        $flushResult = $producer->flush((int) config('kafka.flush_timeout_ms', 10_000));
        if ($flushResult !== RD_KAFKA_RESP_ERR_NO_ERROR) {
            throw new RuntimeException('Kafka producer flush failed.');
        }
    }
}
