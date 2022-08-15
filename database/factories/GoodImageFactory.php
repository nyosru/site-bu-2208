<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GoodImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        $in = [
            'name' => 'http://placeimg.com/'.rand(640,960).'/'.rand(240,480).'/any',
            'good_id' => rand(1,10000),
        ];

        if (rand(1, 10) == 2)
            $in['start'] = 'y';

        return $in;
    }
}
