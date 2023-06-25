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
            // 'uri' => 'http://placeimg.com/'.rand(640,960).'/'.rand(240,480).'/any',
            // 'uri' => 'http://placeimg.com/'.rand(64,96).'/'.rand(24,48).'/any',
            // 'uri' => 'http://placeimg.com/'.rand(164,196).'/'.rand(124,148).'/any',
            // 'uri' => 'http://placeimg.com/196/124/any',
            'uri' => 'http://via.placeholder.com/150x100',
            'good_id' => rand(1,5000),
        ];

        if (rand(1, 10) == 2)
            $in['start'] = 'y';

        return $in;
    }
}
