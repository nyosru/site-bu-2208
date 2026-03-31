<?php

namespace App\Support;

interface AdvertisementTaskProducerInterface
{
    /**
     * @param  array{catalog_id:int,title:string,description:string,price:numeric-string|int|float,ad_type:string,photo_paths:array<int,string>}  $payload
     */
    public function dispatchCreateTask(int $userId, array $payload, ?string $taskId = null): string;
}
