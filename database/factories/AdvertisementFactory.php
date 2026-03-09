<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\Cat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Advertisement>
 */
class AdvertisementFactory extends Factory
{
    protected $model = Advertisement::class;

    public function definition(): array
    {
        $return = [
            'user_id' => fn() => User::query()->inRandomOrder()->value('id')
                ?? User::factory()->create()->id,
            'catalog_id' => fn() => Cat::query()->inRandomOrder()->value('id')
                ?? Cat::query()->create(['name' => fake()->words(2, true)])->id,
            'title' => fake()->sentence(4),
            'description' => fake()->realTextBetween(120, 260),
            'type' => fake()->randomElement(['sell', 'buy']),
        ];

        if ($return['type'] == 'sell' || ($return['type'] == 'buy' && rand(0, 8) != 1))
            $return['price'] = fake()->randomFloat(2, 100, 500000);

        return $return;
    }
}
