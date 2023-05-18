<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\GoodCollection;
use App\Models\Good;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class GoodController extends Controller
{

    /**
     * товары в каталоге и во всех вложенных каталогах
     */
    public function goodFromCat(Request $request, string $cat_id)
    {

        $type = [];
        
        $rf = json_decode($request->filter);

        if (!empty($rf))
            foreach ($rf as $k => $v) {
                if ($v === true)
                    $type[] = $k;
            }

        if (empty($cat_id)) {

            $products = Good::with('cat', 'image')
                ->where(function (Builder $query) use ($type) {
                    if (!empty($type))
                        $query->whereIn('type', $type);
                    //   ->orWhere('type', '=', 50);
                    // foreach ($request->filter as $k => $v) {
                    //     if ($v === true)
                    //         $query->orWhere('type', '=',  $k);
                    // }
                })
                ->paginate(30);

            // $request->filter

        } else {

            $cats = CatalogController::showCatsAndInnerCats($cat_id);


            $products = Good::with('cat', 'image')->whereHas('cat', function ($query) use ($cats) {
                $query->whereIn('id', $cats);
            })
                ->where(function (Builder $query) use ($type) {
                    if (!empty($type))
                        $query->whereIn('type', $type);
                    //   ->orWhere('type', '=', 50);
                    // foreach ($request->filter as $k => $v) {
                    //     if ($v === true)
                    //         $query->orWhere('type', '=',  $k);
                    // }
                })
                ->paginate(30);
        }
        return response()->json($products);
    }

    public function good(int $id)
    {
        // $cats = CatalogController::showCatsAndInnerCats($cat_id);

        $good = Good::with('cat', 'image')->find($id)
            // ->paginate(30)
        ;

        return response()->json($good);
    }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    public function store(Request $request)
    {

        // $request->search

        $s = explode(' ', $request->search);
        // $s = explode(' ', $request->search);


        return new GoodCollection(Good::with('analog')->where(function ($query) use ($s, $request) {
            foreach ($s as $v) {

                $v2 = preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '', $v);

                // $query->where('a', '=', 1)
                //       ->orWhere('b', '=', 1);
                $query->Where('head', 'LIKE', '%' . $v2 . '%');
            }
            $query->orWhere('catnumber_search', $v2);

            if (strlen($request->search) <= 20 && strlen($request->search) >= 5) {
                $searchString = strtolower(preg_replace('/[^a-zA-Zа-яА-Я0-9xA0x20]/ui', '', $request->search));
                // die($ee);
                $query->orWhere('catnumber_search', $searchString);
            }

            // if (!empty($s2))
            //     $query->orWhere('catnumber_search', $s2);

        })
            // ->orWhere(function ($query) use ($request) {
            //     if (sizeof($request->search) <= 20) {
            //         $s2 = preg_replace('/[^a-zA-Zа-яА-Я0-9 ]/ui', '', $request->search);
            //         $query->where('catnumber_search', $s2);
            //     }
            // })
            ->where('status', 'show')->
            // orderBy('a_price')->
            limit(150)->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new GoodCollection(Good::with('analog')->where('a_id', $id)->where('status', 'show')->get());
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
