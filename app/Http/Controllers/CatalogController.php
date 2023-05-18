<?php

namespace App\Http\Controllers;

use App\Http\Resources\CatalogCollection;

use App\Models\Cat;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    /*
     * список всех каталогов
     */
    public function index()
    {
        return new CatalogCollection(
            Cat::
                // remember(60)->
                // with('icon')->
                // with('children')->
                // with('cats')->
                // where('status', 'show')->orderBy('sort')->
                // whereNull('cat_up_id')->
                // orderBy('sort')->
                get()
        );
    }

    /**
     * показ одного каталога
     */
    public function show0(string $id)
    {
        return new CatalogCollection(Cat::where('id', $id)->
            // where('status', 'show')->
            get());
    }

    public function showIn(string $id)
    {
        if (empty($id)) {
            return new CatalogCollection(Cat::whereNull('cat_up_id')->
                // where('status', 'show')->
                get());
        } else {
            return new CatalogCollection(Cat::where('cat_up_id', $id)->
                // where('status', 'show')->
                get());
        }
    }

    /**
     * тащим массив каталогов, текущий и все вложенные 
     * id = номер текущего каталога
     */
    public static function show(string $id)
    {
        if (empty($id)) {
            $cat = DB::table('cats as c')
                ->whereNull('c.cat_up_id')
                ->select(
                    'c.id',
                    'c.name',
                )
                ->get();
            return response()->json(['data' => $cat]);
        } else {
            $cat = DB::table('cats as c')
                ->leftJoin('cats as c2', 'c.cat_up_id', '=', 'c2.id')
                ->leftJoin('cats as c3', 'c2.cat_up_id', '=', 'c3.id')
                ->leftJoin('cats as c4', 'c3.cat_up_id', '=', 'c4.id')
                ->leftJoin('cats as c5', 'c4.cat_up_id', '=', 'c5.id')
                ->where('c.id', '=', $id)
                ->select(
                    'c.id',
                    'c.name',
                    'c2.id as c2_id',
                    'c2.name as c2_name',
                    'c3.id as c3_id',
                    'c3.name as c3_name',
                    'c4.id as c4_id',
                    'c4.name as c4_name',
                    'c5.id as c5_id',
                    'c5.name as c5_name',
                )
                ->get();
            return response()->json(['data' => $cat[0]]);
        }
    }

    public static function showCatsAndInnerCats(int $cat_id)
    {

        $gg = DB::table('cats as c1')
            ->where('c1.id', '=', $cat_id)
            ->select('c1.id as cat_id')
            // ->get()
        ;

        $gg2 = DB::table('cats as c1')
            // ->leftJoin('cats as c', 'c.id' , '=', $cat_id )
            ->join('cats as c2', 'c2.cat_up_id', '=', 'c1.id')
            ->where('c1.id', '=', $cat_id)
            ->select('c2.id as cat_id')
            ->union($gg)
            // ->get()
        ;

        $gg3 = DB::table('cats as c1')
            ->join('cats as c2', 'c2.cat_up_id', '=', 'c1.id')
            ->join('cats as c3', 'c3.cat_up_id', '=', 'c2.id')
            ->where('c1.id', '=', $cat_id)
            ->select('c3.id as cat_id')
            ->union($gg2)
            // ->get()
        ;

        $gg4 = DB::table('cats as c1')
            // ->leftJoin('cats as c', 'c.id' , '=', $cat_id )
            ->join('cats as c2', 'c2.cat_up_id', '=', 'c1.id')
            ->join('cats as c3', 'c3.cat_up_id', '=', 'c2.id')
            ->join('cats as c4', 'c4.cat_up_id', '=', 'c3.id')
            ->where('c1.id', '=', $cat_id)
            ->select('c4.id as cat_id')
            ->union($gg3)
            // ->get()
        ;
        $gg5 = DB::table('cats as c1')
            // ->leftJoin('cats as c', 'c.id' , '=', $cat_id )
            ->join('cats as c2', 'c2.cat_up_id', '=', 'c1.id')
            ->join('cats as c3', 'c3.cat_up_id', '=', 'c2.id')
            ->join('cats as c4', 'c4.cat_up_id', '=', 'c3.id')
            ->join('cats as c5', 'c5.cat_up_id', '=', 'c4.id')
            ->where('c1.id', '=', $cat_id)
            ->select('c5.id as cat_id')
            ->union($gg4)
            ->get();

        // dd($gg5);
        // $catIds = collect($gg5)->pluck('cat_id')->toArray();
        return collect($gg5)->pluck('cat_id')->toArray();

        // dd($catIds);
        // return $catIds;
    }
}
