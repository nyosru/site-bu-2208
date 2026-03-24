<?php

namespace App\Domain\Advertisement\Entities;

class Advertisement
{
    /**
     * @param  AdvertisementPhoto[]  $photos
     */
    public function __construct(
        public readonly ?int $id,
        public readonly int $userId,
        public readonly int $catalogId,
        public readonly string $title,
        public readonly string $description,
        public readonly array $photos,
        public readonly ?\DateTimeImmutable $createdAt = null,
        public readonly ?\DateTimeImmutable $updatedAt = null,
    ) {}
}
