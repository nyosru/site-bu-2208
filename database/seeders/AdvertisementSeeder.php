<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Cat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    private const ADS_PER_CATALOG = 30;

    public function run(): void
    {
        if (!User::query()->exists()) {
            User::factory(10)->create();
        }

        $userIds = User::query()->pluck('id')->all();
        Cat::query()
            ->select(['id', 'name'])
            ->chunkById(100, function ($catalogs) use ($userIds): void {
                foreach ($catalogs as $catalog) {
                    Advertisement::factory()
                        ->count(self::ADS_PER_CATALOG)
                        ->sequence(
                            fn (Sequence $sequence) => [
                                'user_id' => fake()->randomElement($userIds),
                                'catalog_id' => $catalog->id,
                                'title' => sprintf('%s #%d', $catalog->name, $sequence->index + 1),
                            ]
                        )
                        ->create();
                }
            });
    }
}
