<?php

namespace App\Support;

use App\Models\Advertisement;

interface AdvertisementEventPublisherInterface
{
    public function publishCreated(Advertisement $advertisement): void;
}
