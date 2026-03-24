<?php

namespace App\Application\Advertisement\Services;

use App\Application\Advertisement\DTO\CreateAdvertisementDto;
use App\Domain\Advertisement\Entities\Advertisement;
use App\Domain\Advertisement\Entities\AdvertisementPhoto;
use App\Domain\Advertisement\Repositories\AdvertisementRepositoryInterface;
use App\Domain\Catalog\Repositories\CatalogReadRepositoryInterface;
use InvalidArgumentException;

class AdvertisementService
{
    public function __construct(
        private readonly AdvertisementRepositoryInterface $advertisementRepository,
        private readonly CatalogReadRepositoryInterface $catalogRepository,
    ) {}

    public function create(CreateAdvertisementDto $dto): Advertisement
    {
        $title = trim($dto->title);
        $description = trim($dto->description);

        if ($dto->userId <= 0) {
            throw new InvalidArgumentException('User id must be a positive integer.');
        }

        if ($dto->catalogId <= 0) {
            throw new InvalidArgumentException('Catalog id must be a positive integer.');
        }

        if ($this->catalogRepository->findById($dto->catalogId) === null) {
            throw new InvalidArgumentException('Catalog does not exist.');
        }

        if ($title === '') {
            throw new InvalidArgumentException('Title is required.');
        }

        if ($description === '') {
            throw new InvalidArgumentException('Description is required.');
        }

        if (count($dto->photos) === 0) {
            throw new InvalidArgumentException('At least one photo is required.');
        }

        $photos = [];
        foreach ($dto->photos as $index => $path) {
            $normalizedPath = trim($path);
            if ($normalizedPath === '') {
                throw new InvalidArgumentException('Photo path must not be empty.');
            }

            $photos[] = new AdvertisementPhoto(
                path: $normalizedPath,
                sortOrder: $index,
            );
        }

        $advertisement = new Advertisement(
            id: null,
            userId: $dto->userId,
            catalogId: $dto->catalogId,
            title: $title,
            description: $description,
            photos: $photos,
        );

        return $this->advertisementRepository->save($advertisement);
    }
}
