<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $in = [
            'name' => $this->faker->name(),
            'opis' => $this->faker->text(),
            'price' => rand(100, 9900000),
            // 'author_id' => 1,
        ];

        // if (rand(1, 2) == 2)
        $in['cat_id'] = rand(1, 100);

        $r = rand(1, 4);
        if ($r == 2) {
            $in['type'] = 'buy';
        } elseif ($r == 3) {
            $in['type'] = 'renta';
        } elseif ($r == 4) {
            $in['type'] = 'need_renta';
        }

        return $in;
    }
}
