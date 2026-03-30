<?php

namespace App\Support;

use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;

class AdvertisementCreator
{
    public function __construct(
        private readonly AdvertisementEventPublisherInterface $eventPublisher,
    ) {}

    /**
     * @param  array{catalog_id:int,title:string,description:string,price:numeric-string|int|float,ad_type:string,photo_paths:array<int,string>}  $payload
     */
    public function create(int $userId, array $payload): Advertisement
    {
        $advertisement = DB::transaction(function () use ($userId, $payload): Advertisement {
            $advertisement = Advertisement::query()->create([
                'user_id' => $userId,
                'catalog_id' => $payload['catalog_id'],
                'title' => trim($payload['title']),
                'description' => trim($payload['description']),
                'price' => $payload['price'],
                'type' => $payload['ad_type'],
            ]);

            foreach ($payload['photo_paths'] as $index => $path) {
                $advertisement->photos()->create([
                    'path' => $path,
                    'sort_order' => $index,
                ]);
            }

            return $advertisement;
        });

        $advertisement->loadMissing('photos');
        $this->eventPublisher->publishCreated($advertisement);

        return $advertisement;
    }
}
