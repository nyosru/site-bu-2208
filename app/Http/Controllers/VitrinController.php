<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cat;
use App\Models\Good;
use App\Models\GoodImage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VitrinController extends Controller
{

    public static $bg = [
        'sell' => 'bg-orange-400',
        'outline-sell' => 'outline-orange-400',
        'outline-sell-bg' => 'bg-orange-200',
        'border-sell-bg' => 'bg-orange-200',
        'border-sell' => 'border-orange-400',
        'buy' => 'bg-green-400',
        'border-buy' => 'border-green-400',
        'border-buy-bg' => 'bg-green-200',
        'renta' => 'bg-yellow-400',
        'border-renta' => 'border-yellow-400',
        'border-renta-bg' => 'bg-yellow-200',
        'need_renta' => 'bg-blue-400',
        'border-need_renta' => 'border-blue-400',
        'border-need_renta-bg' => 'bg-blue-200',
    ];



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($good_id = null, $cat_id = null)
    {

        // $good = Good::with('images')->find(1)->images();
        $goods = Good::with('images')
            // ->where('cat_id', '=', 7)
            ->get()
            // ->find(24)
        ;
        // dd($good);
        // $goodImages = GoodImage::find(1)->good();



        // $ar = self::$all_ar;
        $ar = [];

        $ar['linkToAuth'] = self::getLinkToAuth();

        $ar['bgClass'] = self::$bg;
        $ar['cats'] = Cat::all()->toArray();
        // 'cats' => [],
        // 'goods' => Good::all()->toArray(),
        $ar['goods'] = [];
        // 'good' => $goodImages,
        $ar['goods'] = $goods->toArray();



        // $ar['cats2'] = $ar['cats'];
        $ar['types_now'] = $_REQUEST['types'] ?? ['sell' => 'da', 'buy' => 'da', 'renta' => 'da', 'need_renta' => 'da'];
        $ar['types_now_all'] = ( !empty($_REQUEST['types']) && $_REQUEST['types']['sell'] == 'da' && $_REQUEST['types']['buy'] == 'da' && $_REQUEST['types']['renta'] == 'da' && $_REQUEST['types']['need_renta'] == 'da') ? true : false;
        $ar['types_now_all_off'] = ( !empty($_REQUEST['types']) && $_REQUEST['types']['sell'] != 'da' && $_REQUEST['types']['buy'] != 'da' && $_REQUEST['types']['renta'] != 'da' && $_REQUEST['types']['need_renta'] != 'da') ? true : false;

        $ar['types_now_colvo'] = self::kolvoTypeDa();

        if (!empty($good_id)) {

            $ar['good_id'] = $good_id;
            $ar['good'] =
                $good =
                Good::with('images')
                ->where('id', $good_id)
                // ->get()
                ->first();

            // // $ar['good'] = $good->0;
            // foreach ($good as $k) {
            //     $ar['good'] = $k;
            //     break;
            // }
            // dd($good);
            // $ar['good'] = $good->toArray();
            // $ar['good'] = $good;
            // dd($ar['good']);
            // $ar['cat_now'] = $ar['good']['cat_id'];
            $ar['cat_now'] = $ar['good']->cat_id;

        }

        // if (!empty($cat_id)) {
        //     $cat = Cat::with('goods')
        //         ->where('id', $cat_id)
        //         ->get();
        //     // dd($good);
        //     $ar['cat'] = $cat->toArray();
        // }

        //         else if (!empty($cat_id)) {
        // dd(11);
        //             $good = Good::with('images')
        //                 ->where('id', $good_id)
        //                 ->get();
        //             // dd($good);
        //             $ar['goods'] = $good->toArray()[0];

        // } 
        else {
            $ar['goods'] = Good::with('images')
                // ->where('id', $good_id)
                ->get()
                ->take(30);
        }

        return view('layouts.app', $ar);
    }

    // id каталогов этого и вложенных каталогов
    public static final function idCats(?int $cat_id): array
    {
        $ar = [];

        // $in_cats3 = DB::table(DB::raw('cats as c'))
        $ssql = DB::table(DB::raw('cats as c'))
            ->join(DB::raw('cats as c1'), 'c.id', '=', 'c1.cat_up_id')
            ->join(DB::raw('cats as c2'), 'c1.id', '=', 'c2.cat_up_id')
            ->join(DB::raw('cats as c3'), 'c2.id', '=', 'c3.cat_up_id')
            ->where('c.id', '=', $cat_id)
            ->select('c3.id as id');

        $in_cats2 = DB::table(DB::raw('cats as c'))
            ->join(DB::raw('cats as c1'), 'c.id', '=', 'c1.cat_up_id')
            ->join(DB::raw('cats as c2'), 'c1.id', '=', 'c2.cat_up_id')
            ->where('c.id', '=', $cat_id)
            ->select('c2.id as id')
            // ->union($in_cats3)
        ;

        $ssql = $ssql->union($in_cats2);

        $in_cats1 = DB::table(DB::raw('cats as c'))
            ->join(DB::raw('cats as c1'), 'c.id', '=', 'c1.cat_up_id')
            // ->join('cats as c1', function ($join) use ($cat_id) {
            //     $join->on('c.id', '=', 'c1.cat_up_id')                
            //     //->orOn(...)
            //     ;
            // })
            ->where('c.id', '=', $cat_id)
            ->select('c1.id as id')
            // ->union($in_cats2)
            //->get()
        ;
        $ssql = $ssql->union($in_cats1);

        $in_cats = DB::table(DB::raw('cats as c'))
            ->where('c.id', $cat_id)
            ->select('c.id as id')
            // ->union($in_cats1)
            // ->get()
        ;
        $ssql = $ssql->union($in_cats);

        $in_cats = $ssql->get();

        // dd($in_cats);
        // dd($in_cats->toArray());

        // return $ar;
        // return $in_cats->toArray();

        $aa = $in_cats->toArray();

        $in_cats = [];
        foreach ($aa as $k => $v) {
            $in_cats[] = $v->id;
            // array_push($in_cats,$v->id);
        }

        // dd( $in_cats );
        return $in_cats;
    }

    // колво указанных типов для показа
    public static final function kolvoTypeDa(): int
    {

        $ar['types_now_colvo'] = 0;

        if (isset($_REQUEST['types'])) {

            if ($_REQUEST['types']['sell'] == 'da')
                $ar['types_now_colvo']++;

            if ($_REQUEST['types']['buy'] == 'da')
                $ar['types_now_colvo']++;

            if ($_REQUEST['types']['renta'] == 'da')
                $ar['types_now_colvo']++;

            if ($_REQUEST['types']['need_renta'] == 'da')
                $ar['types_now_colvo']++;
        }
        return $ar['types_now_colvo'];
    }

    /**
     * ссылка на регу
     */
    public static final function getLinkToAuth(): string
    {

        $client_id = 7991541; // ID приложения
        // $client_secret = '3XUInm5jv5lIR3cLVdqv'; // Защищённый ключ
        $client_secret = 'nRCGVm1iveaYLflpxlXN'; // Защищённый ключ
        // $redirect_uri = 'http://bu72.ru/aut/auth.php'; // Адрес сайта
        $redirect_uri = 'https://bu72.ru/aut/vk'; // Адрес сайта

        $url = 'http://oauth.vk.com/authorize'; // Ссылка для авторизации на стороне ВК

        $params = ['client_id' => $client_id, 'redirect_uri'  => $redirect_uri, 'response_type' => 'code']; // Массив данных, который нужно передать для ВК содержит ИД приложения код, ссылку для редиректа и запрос code для дальнейшей авторизации токеном

        return $url . '?' . urldecode(http_build_query($params));
    }

    // показ каталога с товарами
    public final function showCat(int $cat_id = null)
    {

        $ar = [

            'types_now' => $_REQUEST['types'] ?? ['sell' => 'da', 'buy' => 'da', 'renta' => 'da', 'need_renta' => 'da'],
            'bgClass' => self::$bg,

            // .order-type .sell {
            //     background-color: orange;
            // }

            // .order-type .buy {
            //     background-color: lightgreen;
            // }

            // .order-type .renta {
            //     background-color: yellow;
            // }

            // .order-type .need_renta {
            //     background-color: lightblue;
            // }

            'cat_now' => $cat_id,
            'cats' => Cat::all()->toArray(),
            // 'cats' => [],
            // 'goods' => Good::all()->toArray(),
            'goods' => [],
            // 'good' => $goodImages,
            // 'goods' => $goods->toArray(),
        ];













        $ar['linkToAuth'] = self::getLinkToAuth();


        $ar['types_now_colvo'] = self::kolvoTypeDa();


        $ar['types_now_all'] = (isset($_REQUEST['types']) && $_REQUEST['types']['sell'] == 'da' && $_REQUEST['types']['buy'] == 'da' && $_REQUEST['types']['renta'] == 'da' && $_REQUEST['types']['need_renta'] == 'da') ? true : false;
        $ar['types_now_all_off'] = (isset($_REQUEST['types']) && $_REQUEST['types']['sell'] != 'da' && $_REQUEST['types']['buy'] != 'da' && $_REQUEST['types']['renta'] != 'da' && $_REQUEST['types']['need_renta'] != 'da') ? true : false;
        // if      ($ar['types_now']['sell'] == 'da' && $ar['types_now']['buy'] != 'da' && $ar['types_now']['renta'] != 'da' && $ar['types_now']['need_renta'] != 'da') {     $ar['types_now'] = ['sell' => 'da', 'buy' => '1da', 'renta' => '1da', 'need_renta' => '1da']; }
        // else if ($ar['types_now']['sell'] != 'da' && $ar['types_now']['buy'] == 'da' && $ar['types_now']['renta'] != 'da' && $ar['types_now']['need_renta'] != 'da') {     $ar['types_now'] = ['sell' => '1da', 'buy' => 'da', 'renta' => '1da', 'need_renta' => '1da']; }
        // else if ($ar['types_now']['sell'] != 'da' && $ar['types_now']['buy'] != 'da' && $ar['types_now']['renta'] == 'da' && $ar['types_now']['need_renta'] != 'da') {     $ar['types_now'] = ['sell' => '1da', 'buy' => '1da', 'renta' => 'da', 'need_renta' => '1da']; }
        // else if ($ar['types_now']['sell'] != 'da' && $ar['types_now']['buy'] != 'da' && $ar['types_now']['renta'] != 'da' && $ar['types_now']['need_renta'] == 'da') {     $ar['types_now'] = ['sell' => '1da', 'buy' => '1da', 'renta' => '1da', 'need_renta' => 'da']; }


        // $in_cats = Cat::where( 'id', $cat_id )->with('children')->get();
        // $in_cats = Cat::whereId($cat_id)->with('children')->get();
        // dd( $in_cats );

        // if (!empty($cat_id)) {
        $in_cats0 = self::idCats($cat_id);
        // }
        // dd($in_cats0);
        // $in_cats = [];
        // foreach($in_cats0 as $k => $v ){
        //     $in_cats[] = $v['id'];
        // }


        DB::enableQueryLog();

        // if( empty($in_cats0) ){

        //     $ar['goods'] =
        //     Good::with('images')
        //     ->select('goods.*')
        //     ->orderBy('updated_at', 'desc')

        //     ->where(function ($query) use ($ar) {
        //         if ($ar['types_now_colvo'] < 4)
        //             $query
        //                 ->orWhere('type', (isset($ar['types_now']['buy']) && $ar['types_now']['buy'] == 'da' ? 'buy' : 'x'))
        //                 ->orWhere('type', (isset($ar['types_now']['sell']) && $ar['types_now']['sell'] == 'da' ? 'sell' : 'x'))
        //                 ->orWhere('type', (isset($ar['types_now']['renta']) && $ar['types_now']['renta'] == 'da' ? 'renta' : 'x'))
        //                 ->orWhere('type', (isset($ar['types_now']['need_renta']) && $ar['types_now']['need_renta'] == 'da' ? 'need_renta' : 'x'));
        //     })

        //     ->paginate(30)
        //     // ->limit(3-)
        //     ;


        // }else{

        $ar['goods'] =
            // $cat = 
            Good::with('images')
            ->select('goods.*')
            ->orderBy('updated_at', 'desc')
            // ->whereIn('goods.cat_id', $in_cats0)

            // ->join(DB::raw('`cats` as `c1`'), function ($join) use ( $cat_id ) {
            // // ->join(DB::raw('`cats` as `c1`'), function ($join) {
            //     // $join->on('c1.id', '=', $cat_id'contacts.user_id')->orOn(...);
            //     // $join->on('c1.id', $cat_id );
            //     $join->on('c1.id', 'goods.cat_id' )->where('c1.id','=',$cat_id);
            // })

            // // // ->join(DB::raw('`cats` as `c1`'), 'cats.id', '=', 'goods.cat_id')
            // // // ->join(DB::raw('`cats` as `c1`'), 'c1.id',  $cat_id)
            // ->leftJoin(DB::raw('`cats` as `c2`'), 'c2.cat_up_id', 'c1.id')
            // ->leftJoin(DB::raw('`cats` as `c3`'), 'c3.cat_up_id', 'c2.id')

            // ->where(function ($query) use ($cat_id) {
            // // ->where(function ($query) {
            // // //     // if (!empty($cat_id)) {
            //         $query
            //             // ->where('goods.cat_id', $cat_id)
            //             ->orWhere('goods.cat_id', '=', '`c1`.`id`')
            //             ->orWhere('goods.cat_id', '=', '`c2`.`id`')
            //             ->orWhere('goods.cat_id', '=', '`c3`.`id`')
            //             ;
            // // //     // }
            // })

            ->where(function ($query) use ($ar, $in_cats0) {
                // if ($ar['types_now_colvo'] < 4)
                //     $query
                //         ->orWhere('type', (isset($ar['types_now']['buy']) && $ar['types_now']['buy'] == 'da' ? 'buy' : 'x'))
                //         ->orWhere('type', (isset($ar['types_now']['sell']) && $ar['types_now']['sell'] == 'da' ? 'sell' : 'x'))
                //         ->orWhere('type', (isset($ar['types_now']['renta']) && $ar['types_now']['renta'] == 'da' ? 'renta' : 'x'))
                //         ->orWhere('type', (isset($ar['types_now']['need_renta']) && $ar['types_now']['need_renta'] == 'da' ? 'need_renta' : 'x'));

                foreach ($ar['types_now'] as $type => $v) {
                    if ($ar['types_now'][$type] == 'da')
                        $query->orWhere('type', $type);
                }

                if (!empty($in_cats0))
                    $query
                        ->whereIn('goods.cat_id', $in_cats0);
            })

            // ->get()
            // ->addSelect('goodImages.name')
            ->paginate(30);
        // }

        $ar['goods']->appends(['types' => $ar['types_now']]);
        // dd(DB::getQueryLog());
        // dd($ar['goods']);

        if (!empty($cat_id)) {
            $ar['catsDown'] = Cat::where('cat_up_id', $cat_id)->get();
        } else {
            $ar['catsDown'] = Cat::whereNull('cat_up_id')->get();
        }

        return view('layouts.app', $ar);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAdd(Request $request)
    {

        $validatorGood = Validator::make(
            $request->all(),
            [
                // $goodValidate = $request->validate([
                'cat_id' => 'required|integer',
                'name' => 'required|string',
                'price' => 'integer|nullable',
                'opis' => 'string|nullable',
                'type' => 'required|string',

                // файл должен быть картинкой (jpeg, png, bmp, gif, svg или webp)
                //    'photo1' => 'image',

                // поддерживаемые MIME файла (image/jpeg, image/png)
                //    'photo1' => 'mimetypes:image/jpeg,image/png',

            ],
            [
                'designation_type.required' => 'Designation is required',
                'designation_type.max' => 'Designation should not be greater than 255 characters.',
            ]
        );

        if ($validatorGood->fails()) {
            return redirect('add')
                ->withErrors($validatorGood)
                ->withInput();
        }

        $validated = $validatorGood->safe()->only(['name', 'opis', 'price', 'cat_id', 'type']);

        $newGood = new Good;
        $newGood->name = $validated['name'];
        $newGood->opis = $validated['opis'];
        $newGood->type = $validated['type'];
        $newGood->price = $validated['price'];
        $newGood->cat_id = $validated['cat_id'];
        $newGood->save();

        // dd($request);
        $e = [];
        // $e[] = $new;
        $e[] = $newGood->id ?? 'x';
        // $e[] = $in->id ?? 'x';

        foreach ($request->file('photo') as $file) {

            $p = $file->store('public/goods');

            // $e[] = asset($p);
            // $e[] = $p;
            // $e[] = Storage::url($p);

            $newImg = new GoodImage;
            $e[] =
                $newImg->name = $p;
            $newImg->good_id = $newGood->id;
            $newImg->save();
        }

        return redirect()->route('formAdd')->with('warn', 'Обьявление успешно отправлено, скоро будет добавлено');

        // dd($e);

        // $path = $request->file('photo1')
        // ->store('public/goods')
        // ;
        // echo '<img src="/'.$path.'" />';
        // echo '<img src="/public/storage/'.$path.'" />';
        // echo '<img src="/storage/'.$path.'" />';

        // $url = Storage::url($path);
        // echo '<img src="/'.$url.'" />';

        // $url7 = storage_path($path);
        // echo '7<img src="/'.$url7.'" />';

        // $url1 = asset($url);
        // echo '<img src="/'.$url1.'" />';

        // $url2 = asset($path);
        // echo '<img src="/'.$url2.'" />';

        // // $path1 = Storage::putFileAs(
        // //     'goods', $request->file('photo1'), 11
        // // );
        // // echo '<img src="/'.$path1.'" />';
        // dd( $path, $url , 7, $url7, $url1, $url2 );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAdd()
    {
        $ar = [
            'showForm' => 'add',
            'cats' => Cat::all(),
        ];
        $ar['linkToAuth'] = self::getLinkToAuth();
        return view('layouts.app', $ar);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
