<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Advertisement\Entities\Advertisement as AdvertisementEntity;
use App\Domain\Advertisement\Entities\AdvertisementPhoto as AdvertisementPhotoEntity;
use App\Domain\Advertisement\Repositories\AdvertisementRepositoryInterface;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;

class EloquentAdvertisementRepository implements AdvertisementRepositoryInterface
{
    public function save(AdvertisementEntity $advertisement): AdvertisementEntity
    {
        $model = DB::transaction(function () use ($advertisement) {
            $model = Advertisement::query()->create([
                'user_id' => $advertisement->userId,
                'catalog_id' => $advertisement->catalogId,
                'title' => $advertisement->title,
                'description' => $advertisement->description,
            ]);

            foreach ($advertisement->photos as $photo) {
                $model->photos()->create([
                    'path' => $photo->path,
                    'sort_order' => $photo->sortOrder,
                ]);
            }

            return $model->load('photos');
        });

        return $this->toDomain($model);
    }

    public function findById(int $id): ?AdvertisementEntity
    {
        $model = Advertisement::query()->with('photos')->find($id);

        if ($model === null) {
            return null;
        }

        return $this->toDomain($model);
    }

    private function toDomain(Advertisement $model): AdvertisementEntity
    {
        $photos = $model->photos
            ->sortBy('sort_order')
            ->values()
            ->map(fn ($photo) => new AdvertisementPhotoEntity(
                path: $photo->path,
                sortOrder: (int) $photo->sort_order,
            ))
            ->all();

        return new AdvertisementEntity(
            id: $model->id,
            userId: (int) $model->user_id,
            catalogId: (int) $model->catalog_id,
            title: $model->title,
            description: $model->description,
            photos: $photos,
            createdAt: $model->created_at?->toDateTimeImmutable(),
            updatedAt: $model->updated_at?->toDateTimeImmutable(),
        );
    }
}
