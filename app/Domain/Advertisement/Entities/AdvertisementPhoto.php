<?php

namespace App\Domain\Advertisement\Entities;

class AdvertisementPhoto
{
    public function __construct(
        public readonly string $path,
        public readonly int $sortOrder
    ) {
    }
}
