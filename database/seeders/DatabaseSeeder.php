<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\CatSeeder;
use Database\Seeders\GoodSeeder;
use Database\Seeders\GoodImageSeeder;
use Database\Seeders\PageSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // \App\Models\User::factory(10)->create();
        $this->call([
            CatSeeder::class,
            GoodSeeder::class,
            GoodImageSeeder::class,
            PageSeeder::class,
        ]);
    }
}
