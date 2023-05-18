<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Good;

class GoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Good::factory(5000)->create();
    }
}
