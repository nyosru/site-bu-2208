<?php

return [
    'enabled' => env('KAFKA_ENABLED', false),
    'brokers' => env('KAFKA_BROKERS', 'kafka_bu:9092'),
    'topic' => env('KAFKA_ADVERTISEMENTS_TOPIC', 'advertisements.created'),
    'tasks_topic' => env('KAFKA_TASKS_TOPIC', 'advertisements.create.tasks'),
    'consumer_group_id' => env('KAFKA_CONSUMER_GROUP_ID', 'ads-create-consumer'),
    'consumer_auto_offset_reset' => env('KAFKA_CONSUMER_AUTO_OFFSET_RESET', 'earliest'),
    'flush_timeout_ms' => (int) env('KAFKA_FLUSH_TIMEOUT_MS', 10000),
    'throw_on_failure' => env('KAFKA_THROW_ON_FAILURE', false),
    'security_protocol' => env('KAFKA_SECURITY_PROTOCOL', ''),
    'sasl_mechanism' => env('KAFKA_SASL_MECHANISM', ''),
    'username' => env('KAFKA_USERNAME', ''),
    'password' => env('KAFKA_PASSWORD', ''),
];
