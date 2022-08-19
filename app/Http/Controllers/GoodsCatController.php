<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\GoodCollection;
use App\Models\Good;

use Illuminate\Support\Facades\DB;

class GoodsCatController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(request $r, $id)
    {
        // dd($r->type);
        $ids = self::getCatsInner($id);
        // dd($ids);
        // $ids = [1, 2, 3, '00000071', 'ЦБ002029'];
        // return new GoodCollection(Good::whereIn('a_categoryid', $ids)->where('status', 'show')->get());
        // return new GoodCollection(Good::whereIn('a_categoryid', $ids)->where('status', 'show')->simplePaginate(10));
        // return new GoodCollection(Good::whereIn('a_categoryid', $ids)->where('status', 'show')->paginate(50));
        return new GoodCollection(Good::with('image')->
            //
            // whereIn('cat_id', $ids)->
            //
            where(function ($query) use ($ids, $r) {
                $query
                    //
                    ->whereIn('cat_id', $ids)
                    //
                    // ->where('name', 'Abigail')
                    //
                    // ->where('votes', '>', 50);
                ;
                if (!empty($r->type)) {
                    $query->whereIn('type', $r->type);
                }
            })->
            // where('status', 'show')->
            paginate(50));
    }

    /**
     * возвращает массив id текущий каталог и все вложенные
     */
    public static function getCatsInner($id_cat)
    {
        // $ids = [ 1 , 2 , 3 , '00000071', 'ЦБ002029' ];
        // return new GoodCollection(Good::whereIn('a_categoryid',$ids)->where('status', 'show')->get());
        $a = DB::table('cats as m')
            ->addSelect('m.id')
            ->where('m.cat_up_id', $id_cat)
            // ->whereIn('id', [1, 2, 3])
            // ->get()
        ;
        $a1 = DB::table('cats as m')
            ->join('cats as m2', 'm2.cat_up_id', '=', 'm.id')
            ->addSelect('m2.id')
            ->where('m.cat_up_id', $id_cat)
            // ->whereIn('id', [1, 2, 3])
            // ->union($a)
            // ->get()
        ;

        $a = $a->union($a1);

        $a2 = DB::table('cats as m')
            ->join('cats as m2', 'm2.cat_up_id', '=', 'm.id')
            ->join('cats as m3', 'm3.cat_up_id', '=', 'm2.id')
            ->addSelect('m3.id')
            ->where('m.cat_up_id', $id_cat)
            // ->whereIn('id', [1, 2, 3])
            // ->union($a1)
            // ->get()
        ;
        $a = $a->union($a2);
        $a3 = DB::table('cats as m')
            ->join('cats as m2', 'm2.cat_up_id', '=', 'm.id')
            ->join('cats as m3', 'm3.cat_up_id', '=', 'm2.id')
            ->join('cats as m4', 'm4.cat_up_id', '=', 'm3.id')
            ->addSelect('m4.id')
            ->where('m.cat_up_id', $id_cat)
            // ->whereIn('id', [1, 2, 3])
            // ->union($a2)
            // ->get()
        ;
        $a = $a->union($a3);
        $a4 = DB::table('cats as m')
            ->join('cats as m2', 'm2.cat_up_id', '=', 'm.id')
            ->join('cats as m3', 'm3.cat_up_id', '=', 'm2.id')
            ->join('cats as m4', 'm4.cat_up_id', '=', 'm3.id')
            ->join('cats as m5', 'm5.cat_up_id', '=', 'm4.id')
            ->addSelect('m5.id')
            ->where('m.cat_up_id', $id_cat)
            // ->whereIn('id', [1, 2, 3])
            // ->union($a3)
            // ->get()
        ;
        $a = $a->union($a4);
        $aaa = $a->get();
        // dd($a1);
        $r = [];
        $r[] = $id_cat;
        // foreach( $a4 as $k ){
        foreach ($aaa as $k) {
            // dd($k);
            $r[] = $k->id;
        }
        return $r;
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     //
    // }
}
