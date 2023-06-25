<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $k = [
            [
                // 'site' => '' 
                'module' => 'about',
                'name' => 'О проекте',
                // 'opis' => '',
                'html' => '<p>Страница</p>'
            ],
            [
                // 'site' => '' 
                'module' => 'help',
                'name' => 'Помощь',
                // 'opis' => '',
                'html' => '<p>Страница</p>'
            ],
            [
                // 'site' => '' 
                'module' => 'konf',
                'name' => 'Конфеденциальность данных',
                // 'opis' => '',
                'html' => '<p>Страница</p>'
            ],
            [
                // 'site' => '' 
                'module' => 'oferta',
                'name' => 'Оферта',
                // 'opis' => '',
                'html' => '<p>Страница</p>'
            ],
        ];

        foreach ($k as $p) {
            Page::create($p);
        }

        // // Cat::factory(100)->create();

        // foreach (self::$CatalogList as $k => $v) {
        //     $new = Cat::create(['name' => $k]);

        //     if (!empty($v)) {
        //         foreach ($v as $k1) {
        //             //         if( !empty($k1) ){
        //             $new1 = Cat::create(['name' => $k1, 'cat_up_id' => $new->id]);

        //             //         // if (!empty(self::$CatalogList[$k][$k1])) {
        //             //         //     foreach (self::$CatalogList[$k][$k1] as $k2) {
        //             //         //         $new1 = Cat::create(['name' => $k2, 'cat_up_id' => $new1->id ]);
        //             //         //     }
        //             //         // }
        //             //         }
        //         }
        //     }
        // }
    }
}
