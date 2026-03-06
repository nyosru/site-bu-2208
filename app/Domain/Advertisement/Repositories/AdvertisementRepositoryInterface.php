<?php

namespace App\Domain\Advertisement\Repositories;

use App\Domain\Advertisement\Entities\Advertisement;

interface AdvertisementRepositoryInterface
{
    public function save(Advertisement $advertisement): Advertisement;

    public function findById(int $id): ?Advertisement;
}
