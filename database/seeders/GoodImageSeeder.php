<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GoodImage;

class GoodImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GoodImage::factory(5000)->create();
    }
}
