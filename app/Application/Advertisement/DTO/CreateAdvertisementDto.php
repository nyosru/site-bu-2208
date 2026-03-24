<?php

namespace App\Application\Advertisement\DTO;

class CreateAdvertisementDto
{
    /**
     * @param  string[]  $photos
     */
    public function __construct(
        public readonly int $userId,
        public readonly int $catalogId,
        public readonly string $title,
        public readonly string $description,
        public readonly array $photos,
    ) {}
}
