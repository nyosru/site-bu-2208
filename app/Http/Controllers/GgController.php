<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\GgImageController;

use App\Http\Controllers\TimerController as Timer;
use Illuminate\Support\Facades\Storage;

class GgController extends Controller
{

    public static $grayArray = [];
    public static $imgWidth = 0;
    public static $imgHeigth = 0;

    public static $timerSt = [];

    public static $imageFile = '';
    public static $imageFileTmp = '';
    public static $im1 = '';
    // массив линий + серость линии
    public static $lineAll = [];
    // массив линий + длинная линии
    public static $lineLength = [];

    function __construct()
    {

        self::$imageFile = $_SERVER['DOCUMENT_ROOT'] . '/storage/photo_no.jpg';
        self::$imageFileTmp = $_SERVER['DOCUMENT_ROOT'] . '/tmp.png';
        // self::$grayArray = Cache::get('grayArray') ?? [];

        // dd(self::$grayArray);

        // if (!empty(self::$grayArray[1])) {
        //     self::$imgWidth = max(self::$grayArray[1]);
        //     self::$imgHeigth = max(self::$grayArray);
        // }

        // echo '== '.self::$imgWidth.' == '.self::$imgHeigth;

        GgImageController::$tempImgUri = Cache::get('tempImgUri');
        self::$lineAll = Cache::get('lineAll');
        self::$lineLength = Cache::get('lineLength');

        GgImageController::$gp = Cache::get('gp');
    }


    function __destruct()
    {

        // print "__destruct " . __CLASS__  . "\n";

        if (!empty(GgImageController::$tempImgUri))
            Cache::put('tempImgUri', GgImageController::$tempImgUri);

        if (!empty(self::$lineAll))
            Cache::put('lineAll', self::$lineAll);

        if (!empty(self::$lineLength))
            Cache::put('lineLength', self::$lineLength);

        if (!empty(GgImageController::$gp))
            Cache::put('gp', GgImageController::$gp);
    }

    static function calcLine($startX, $startY, $finX, $finY)
    {

        // dd(self::$imgWidth, self::$imgHeigth);

        // header("Content-type: image/png");
        $im2 = ImageCreate(self::$imgWidth, self::$imgHeigth) or die("Ошибка при создании изображения");
        $couleur_fond = ImageColorAllocate($im2, 0, 0, 0);
        $color = imagecolorallocatealpha($im2, 255, 0, 0, 0);
        imageline($im2, $startX, $startY, $finX, $finY, $color);
        // ImagePng($im);

        $red = [];

        for ($y = 1; $y < self::$imgHeigth; $y++) {
            for ($x = 1; $x < self::$imgWidth; $x++) {
                $red[$y][$x] = self::redTochka($im2, $x, $y);
            }
        }

        $sum = 0;

        foreach ($red as $k => $v) {
            // echo '<pre>';
            // echo '(' . $k . ')';
            foreach ($v as $k1 => $v1) {
                // echo $k1.'+'.$v1 . ' ';
                // echo $v1 . '';
                // echo '['.self::$grayArray[$k][$k1].'] ';
                if ($v1 > 100) {
                    $sum += self::$grayArray[$k][$k1];
                }
            }
            // echo '</pre>';
            // echo '<br/>';
        }

        return $sum;

        // dd($red[2]);
        // // // $im = imagecreate(self::$imgWidth, self::$imgHeigth) or die("Невозможно создать поток изображения");
        // // $im = imagecreatetruecolor(self::$imgWidth, self::$imgHeigth) or die("Невозможно создать поток изображения");
        // // // $color = imagecolorallocatealpha($im, 255, 0, 0, 0);
        // // // imageline($im, $startX, $startY, $finX, $finY, $color);
        // // // imagedestroy($im);
        // die();
    }


    static function calcLength($x1, $y1, $x2, $y2)
    {
        return ($y2 + $x2) - ($x1 + $y1);
    }

    // static function calcLength2($x1, $y1, $x2, $y2)
    // {
    //     // // Радиус земли 
    //     // define("EARTH_RADIUS", 6372795); 
    //     // /* 
    //     // * Расстояние между двумя точками 
    //     // * $φA, $λA - широта, долгота 1-й точки, 
    //     // * $φB, $λB - широта, долгота 2-й точки 
    //     // * Написано по мотивам http://gis-lab.info/qa/great-circles.html 
    //     // * Михаил Кобзарев 
    //     // **/ 
    //     // function calculateTheDistance ($φA, $λA, $φB, $λB) 
    //     // { 
    //     // перевести координаты в радианы 
    //     // $lat1 = $x1 * M_PI / 180; 
    //     $lat1 = $x1;
    //     // $lat2 = $x2 * M_PI / 180; 
    //     $lat2 = $x2;
    //     // $long1 = $y1 * M_PI / 180; 
    //     $long1 = $y1;
    //     // $long2 = $y2 * M_PI / 180; 
    //     $long2 = $y2;
    //     // косинусы и синусы широт и разницы долгот 
    //     $cl1 = cos($lat1);
    //     $cl2 = cos($lat2);
    //     $sl1 = sin($lat1);
    //     $sl2 = sin($lat2);
    //     $delta = $long2 - $long1;
    //     $cdelta = cos($delta);
    //     $sdelta = sin($delta);
    //     // вычисления длины большого круга 
    //     $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
    //     $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;
    //     // 
    //     // $ad = atan2($y, $x);
    //     return $ad = atan2($y, $x);
    //     // $dist = $ad * EARTH_RADIUS;
    //     $dist = $ad ;
    //     return $dist;
    //     // } 
    //     // Пример вызова функции: 
    //     // $lat1 = 77.1539; $long1 = -139.398; $lat2 = -77.1804; $long2 = -139.55; 
    //     // echo calculateTheDistance($lat1, $long1, $lat2, $long2) . " метров"; 
    //     // // Вернет "17166029 метров" 
    // }

    static function timerStart($name = 'x')
    {
        self::$timerSt[$name] = microtime(true);
    }


    static function timerFinish($name = 'x')
    {
        return round(microtime(true) - self::$timerSt[$name], 4);
    }

    static function start()
    {
        // $start = microtime(true);

        self::timerStart('s');

        $r = [
            'time' => 0,
            // 'colors' => []
        ];

        $r['grayArray'] = Cache::get('grayArray', function () {
            $r = self::creatGrayArray($_SERVER['DOCUMENT_ROOT'] . '/storage/photo_no.jpg');
            return $r['colors'];
        });

        $cc = [];

        $ccl = [];
        $ca = [];

        // for ($y = 1; $y < self::$imgHeigth; $y++) {
        // for ($x = 1; $x < self::$imgWidth; $x++) {
        for ($x = 1; $x < 10; $x++) {
            // $red[$y][$x] = self::redTochka($im2, $x, $y);
            $cc[$x] = self::calcLine(1, 1, $x, self::$imgHeigth);
            $ca[$x] = self::calcLength(1, 1, $x, self::$imgHeigth);
            // }
        }
        // echo '<div style="line-height: 1rem;" >' . implode(' ', $cc) . '</div>';

        // echo '<pre style="line-height: 1rem;"  >', print_r($cc), '</pre>';
        // echo '<pre style="line-height: 1rem;"  >', print_r($ccl), '</pre>';

        $cc2 = [];

        foreach ($cc as $k => $v) {
            $cc2[$k] = round($cc[$k] / $ca[$k], 2);
        }

        // echo max($cc2);
        // $q = self::searchMax($cc2);
        // echo '<pre>', print_r($q), '</pre>';

        $cc3 = asort($cc2);
        echo '<div style="border: 1px solid green; padding: 3px; margin: 3px;" >1212<pre style="line-height: 1rem;"  >', print_r($cc3), '</pre></div>';

        // echo '---------';
        // echo '<pre style="line-height: 1rem;"  >', print_r($cc2), '</pre>';

        // $t = 0;
        // foreach ( $cc31 as $k => $v ) {
        //     $t++;
        //     echo $k . ' ' . $v . '<br/>';
        //     if ($t > 3)
        //         break;
        // }

        // echo '<h2>cc</h2><pre>', print_r($cc), '</pre>';

        // $r['time0']['start'] = 'Время выполнения скрипта: ' . round(microtime(true) - $start, 4) . ' сек.';
        // $r['time0']['start'] = round(microtime(true) - $start, 4);
        $r['time0']['start'] = self::timerFinish('s');

        return view('welcome1', $r);
    }

    static function parse()
    {
        // echo(shell_exec('./storage ls 2>&1'));
        // echo '<Br/>';
        // echo '<Br/>';
        // echo '<Br/>';
        // echo(shell_exec('ls 2>&1'));
        echo(shell_exec('http://localhost/storage/phantomjs-2.1.1-macosx/bin/phantomjs -platform offscreen /storage/phantomjs-2.1.1-macosx/examples/hello.js 2>&1'));
        // echo(shell_exec('phantomjs -platform offscreen script.js 2>&1'));
        dd('12312');
        // return view('welcome1', $r);
    }

    static function searchMax($ar)
    {

        $mk = $mv = 0;

        foreach ($ar as $k => $v) {
            if ($v > $mv) {
                $mk = $k;
                $mv = $v;
            }
        }
        return [$mk, $mv];
    }

    static function creatGrayArray($filename)
    {

        self::$grayArray = [];
        $start = microtime(true);

        $r = [];
        $r['gg'] = 123;

        // Файл
        // $filename = $_SERVER['DOCUMENT_ROOT'] . '/storage/photo_no.jpg';

        // Создаем изображение
        self::$im1 = \imagecreatefromjpeg($filename);
        $imSize = getimagesize($filename);
        // echo '<pre>', print_R($imSize), '</pre>';

        self::$imgWidth = $imSize[0];
        Cache::put('w', $imSize[0]);
        self::$imgHeigth = $imSize[1];
        Cache::put('h', $imSize[1]);

        // $x = 10;
        // $y = 10;

        for ($y = 1; $y < self::$imgHeigth; $y++) {
            for ($x = 1; $x < self::$imgWidth; $x++) {

                // Получаем RGB точки
                // $rgb = imagecolorat($im, $x, $y);
                // Получаем массив значений RGB
                // $r['color'] = $colors = imagecolorsforindex($im, $rgb);
                // $r['color1'] = round(($r['color']['red'] + $r['color']['green'] + $r['color']['blue']) / 3, 1);
                // $r['color11'] = self::graySrednee($r);
                // $r['colors'][$y][$x] = self::grayTochka($im, $x, $y);
                // $r['colors'][$y][$x] = 
                self::$grayArray[$y][$x] = self::grayTochka(self::$im1, $x, $y);
                //$r['color11'] = array_sum($r['color']) / sizeof($r['color']);
            }
        }

        $r['colors'] = self::$grayArray;

        $r['time'] = 'Время выполнения скрипта: ' . round(microtime(true) - $start, 4) . ' сек.';


        return $r;
        // return view('welcome1', $r);
        // }
    }


    static function getBlackLine($img, $calcFn = [], $colvoLine = 10)
    {
        // echo __FUNCTION__ . '<br/><br/>';
        // self::timerStart('s2');
        // $r = [];
        // $r['colors'] = Cache::get('grayArray', function () {
        //     $r = self::creatGrayArray($_SERVER['DOCUMENT_ROOT'] . '/storage/photo_no.jpg');
        //     return $r['colors'];
        // });
        // echo '<pre style="max-height: 200px; overflow: auto;" >', print_r($r, true), '</pre>';
        // self::showNowPicture();
        // return view('step1', $r);
        $line = $cc = $ca = [];
        // for ($y = 1; $y < self::$imgHeigth; $y++) {
        for ($x = 1; $x < self::$imgWidth; $x++) {
            // for ($x = 1; $x < 10; $x++) {
            // $red[$y][$x] = self::redTochka($im2, $x, $y);
            $line[$x] = [1, 1, $x, self::$imgHeigth];
            $cc[$x] = self::calcLine(1, 1, $x, self::$imgHeigth);
            $ca[$x] = self::calcLength(1, 1, $x, self::$imgHeigth);
            // }
        }
        $cc2 = [];
        foreach ($cc as $k => $v) {
            $cc2[$k] = round($cc[$k] / $ca[$k], 2);
        }
        asort($cc2);
        // echo '<div style="border: 1px solid green; padding: 3px; margin: 3px;" >1212<pre style="line-height: 1rem;"  >', print_r($cc2), '</pre></div>';
        echo 'Самые тёмные строчки<br/>';
        echo '<div style="max-height: 200px; overflow: auto; border: 1px solid green; padding: 3px; margin: 3px;" >';
        $lineBlack = [];
        foreach ($cc2 as $k => $v) {
            echo $k . ' ' . $v . '| <br/>';
            $lineBlack[] = $line[$k] ?? [];
            if (sizeof($lineBlack) > 10)
                break;
        }
        echo '</div>';
        echo 'timer: ' . self::timerFinish('s2');

        // Cache::forget('2-lines');
        Cache::put('2lines', $lineBlack);

        echo ' $lineBlack<pre>', print_r($lineBlack), '</pre>';

        $ww = Cache::get('2lines');
        echo ' $ww<pre>', print_r($ww), '</pre>';

        // die('<br/>#' . __LINE__);
    }















    static public function generateLinaArrayArInStr($ar)
    {
        return implode('-', $ar);
    }

    static public function generateLinaArrayStrInAr($str)
    {
        return explode('-', $str);
    }

    static public function generateLinaArray($w, $h, $type = 'v')
    {
        $r = [];
        if ($type == 'v') {
            // for ($y = 1; $y < self::$imgHeigth; $y++) {
            for ($x = 1; $x <= $w; $x++) {
                $str = self::generateLinaArrayArInStr([$x, 1, $x, $h]);
                $r[$str] = 0;
                self::$lineLength[$str] = self::lengthLine($x, 1, $x, $h, 1200);
            }
        }
        // (вертикаль) проходимся по всем нижним точкам, старт со всех верхних точек, линии наискосок
        else if ($type == 'v-7') {
            // for ($y = 1; $y < self::$imgHeigth; $y++) {
            for ($x1 = 1; $x1 <= $w; $x1++) {
                for ($x = 1; $x <= $w; $x++) {
                    if ($x1 % 5 == 0 && $x % 5 == 0) {
                        $str = self::generateLinaArrayArInStr([$x, 1, $x, $h]);
                        $r[$str] = 0;
                        self::$lineLength[$str] = self::lengthLine($x, 1, $x, $h, 1200);
                    }
                }
            }
        }
        // (горизонт) проходимся по всем нижним точкам, старт со всех верхних точек, линии наискосок
        else if ($type == 'g-7') {
            // for ($y = 1; $y < self::$imgHeigth; $y++) {
            for ($x1 = 1; $x1 <= $h; $x1++) {
                for ($x = 1; $x <= $h; $x++) {
                    if ($x1 % 5 == 0 && $x % 5 == 0) {
                        // $r[] = 0;
                        $str = self::generateLinaArrayArInStr([1, $x1, $w, $x]);
                        $r[$str] = 0;
                        self::$lineLength[$str] = self::lengthLine(1, $x1, $w, $x, 1200);
                    }
                }
            }
        }
        //
        else if ($type == 'g') {
            // for ($y = 1; $y < self::$imgHeigth; $y++) {
            for ($x = 1; $x <= $h; $x++) {
                $str = self::generateLinaArrayArInStr([1, $x, $w, $x]);
                $r[$str] = 0;
                self::$lineLength[$str] = self::lengthLine(1, $x, $w, $x, 1200);
            }
        }

        return $r;
    }





    static function step($step = 0, $dop = 'x', $dop2 = '', Request $request)
    {

        if ($step > 0) {
            $s = 'step' . $step;
            return self::$s($dop, $dop2, $request);
        }

        // dd('step', $step);
    }

    /** 
     * показ картинки
     * */
    static function step1()
    {

        echo __FUNCTION__ . '<br/><br/>';

        $r = [];

        $r['colors'] = Cache::get('grayArray', function () {
            $r = self::creatGrayArray($_SERVER['DOCUMENT_ROOT'] . '/storage/photo_no.jpg');
            return $r['colors'];
        });

        // echo '<pre style="max-height: 200px; overflow: auto;" >', print_r($r, true), '</pre>';

        self::showNowPicture();

        // return view('step1', $r);

        die(777);
    }

    /**
     * считаем индексы темноты строк вертикальные
     */
    static function step2()
    {
        echo __FUNCTION__ . '<br/><br/>';
        self::timerStart('s2');
        $r = [];
        $r['colors'] = Cache::get('grayArray', function () {
            $r = self::creatGrayArray($_SERVER['DOCUMENT_ROOT'] . '/storage/photo_no.jpg');
            return $r['colors'];
        });
        // echo '<pre style="max-height: 200px; overflow: auto;" >', print_r($r, true), '</pre>';
        // self::showNowPicture();
        // return view('step1', $r);
        $line = $cc = $ca = [];
        // for ($y = 1; $y < self::$imgHeigth; $y++) {
        for ($x = 1; $x < self::$imgWidth; $x++) {
            // for ($x = 1; $x < 10; $x++) {
            // $red[$y][$x] = self::redTochka($im2, $x, $y);
            $line[$x] = [1, 1, $x, self::$imgHeigth];
            $cc[$x] = self::calcLine(1, 1, $x, self::$imgHeigth);
            $ca[$x] = self::calcLength(1, 1, $x, self::$imgHeigth);
            // }
        }
        $cc2 = [];
        foreach ($cc as $k => $v) {
            $cc2[$k] = round($cc[$k] / $ca[$k], 2);
        }
        asort($cc2);
        // echo '<div style="border: 1px solid green; padding: 3px; margin: 3px;" >1212<pre style="line-height: 1rem;"  >', print_r($cc2), '</pre></div>';
        echo 'Самые тёмные строчки<br/>';
        echo '<div style="max-height: 200px; overflow: auto; border: 1px solid green; padding: 3px; margin: 3px;" >';
        $lineBlack = [];
        foreach ($cc2 as $k => $v) {
            echo $k . ' ' . $v . '| <br/>';
            $lineBlack[] = $line[$k] ?? [];
            if (sizeof($lineBlack) > 10)
                break;
        }
        echo '</div>';
        echo 'timer: ' . self::timerFinish('s2');

        // Cache::forget('2-lines');
        Cache::put('2lines', $lineBlack);

        echo ' $lineBlack<pre>', print_r($lineBlack), '</pre>';

        $ww = Cache::get('2lines');
        echo ' $ww<pre>', print_r($ww), '</pre>';

        // die('<br/>#' . __LINE__);
    }



    /**
     * вставляем строчки из шага 2 в изображение
     */
    static function step3()
    {
        // echo __FUNCTION__ . '<br/><br/>';
        self::timerStart('s2');

        $ar = Cache::get('grayArray', function () {
            $r = self::creatGrayArray($_SERVER['DOCUMENT_ROOT'] . '/storage/photo_no.jpg');
            // echo '<pre>',print_r($r),'</pre>';
            return $r['colors'];
        });




        // if (Cache::has('grayArray')) {
        // } else {
        //     die('нет картинки' . Cache::has('grayArray'));
        // }

        $ee = Cache::get('grayArray');
        // echo '<pre style="max-height: 200px; overflow: auto;" >', print_r($ee), '</pre>';

        // if( !Cache::has('2lines') ){
        //     die('нет выделенных самых тёмных линий');
        // }

        // $lines = Cache::get('2lines');
        $lines = Cache::get('lineAll');
        //         echo '<pre style="max-height: 200px; overflow: auto;" >', print_r($lines), '</pre>';
        // die();


        self::$im1 = imagecreatefrompng(self::$imageFileTmp) or die("Ошибка при создании изображения");
        // // $im2 = imagecreatefromjpeg( self::$imageFile ) or die("Ошибка при создании изображения");
        // // $im2 = ImageCreate(self::$imgWidth, self::$imgHeigth) or die("Ошибка при создании изображения");
        // // $couleur_fond = ImageColorAllocate($im2, 0, 0, 0);


        header("Content-type: image/png");
        // $color = imagecolorallocatealpha(self::$im1, 255, 255, 255, 120);
        $color = imagecolorallocatealpha(self::$im1, 255, 255, 255, 100);

        foreach ($lines as $v1 => $k1) {
            // if ( !empty($dop2) && $k1 > 0) {
            if ($k1 > 0) {
                // if ($k1 > 4000) {
                // echo '<pre>';
                // print_r( $v );
                // echo '<pre>';
                $v = self::generateLinaArrayStrInAr($v1);
                imageline(self::$im1, $v[0], $v[1], $v[2], $v[3], $color);
            }
        }

        // // ImagePng(self::$im1, self::$imageFileTmp);
        ImagePng(self::$im1);
        die();
        // Cache::put('imgTmp', self::$im1);

        echo '<pre>', print_r(\scandir($_SERVER['DOCUMENT_ROOT'])), '</pre>';

        // var_dump(self::$im1);
        // exit;


        // Cache::flush();
        // Cache::put('2-lines', [] , 2);
        // sleep(5);
        // $ee = Cache::get('2-line');
        // echo '<pre>', print_r($ee), '</pre>';

        echo 'timer: ' . self::timerFinish('s2');
    }



    static function step4()
    {
        // echo __FUNCTION__ . '<br/><br/>';
        self::timerStart('s2');

        // self::$im1 = Cache::get('imgTmp');
        header("Content-type: image/png");
        ImagePng(self::$im1);
        exit;

        // echo 'timer: ' . self::timerFinish('s2');
    }



    /**
     * получаем картинку темповую и считаем линии
     */
    static function step5()
    {

        // ob_start();
        // echo __FUNCTION__ . '<br/><br/>';
        Timer::start('s2');

        // берём временную картинку
        $img = GgImageController::readImage(self::$imageFileTmp);
        $imgSize = GgImageController::getSize(self::$imageFileTmp);
        // echo '<pre>', print_r($imgSize), '</pre>';

        $list = self::generateLinaArray($imgSize[0], $imgSize[1]);
        // echo '$list<pre style="max-height: 150px; overflow: auto; border: 1px solid green;" >', print_r($list), '</pre>';

        GgImageController::lineCalcGrayAverage($img);

        // header("Content-type: image/png");
        // ImagePng($img);

        // $lines = Cache::get('2lines');
        // echo '<pre>', print_r($lines), '</pre>';

        // $lines = Cache::put('2lines', [ 1 => 2 ] );

        // рисуем и выбираем самые тёмные линии



        die();

        // сохраняем линии и рисунок с ними

        echo 'timer: ' . Timer::finish('s2');

        // $out1 = ob_get_contents();
        die();
        // die( $out );
    }




    static function step6($dop = '', $dop2 = '')
    {
        ob_start();
        echo __FUNCTION__ . '<br/>';
        echo 'Собираем линии для расчёта и записываем в кеш с нулевыми значениями<br/><br/>';
        Timer::start('s2');

        // if (empty(GgImageController::$tempImgUri)) {

        // берём временную картинку
        // $img = GgImageController::readImage(self::$imageFileTmp);
        // $imgSize = GgImageController::getSize(self::$imageFileTmp);

        $uri = Storage::path('public/' . GgImageController::$tempImgUri);

        $img = GgImageController::readImage($uri);
        $imgSize = GgImageController::getSize($uri);

        // echo '<pre>', print_r($imgSize), '</pre>';

        // $list = self::generateLinaArray($imgSize[0], $imgSize[1]);        
        // echo '$list<pre style="max-height: 150px; overflow: auto; border: 1px solid green;" >', print_r($list), '</pre>';
        // echo 'timer: ' . Timer::finish('s2');

        // $list = self::generateLinaArray($imgSize[0], $imgSize[1], 'g');
        // echo '$list<pre style="max-height: 150px; overflow: auto; border: 1px solid green;" >', print_r($list), '</pre>';

        // echo 'timer: ' . Timer::finish('s2');

        $list = self::generateLinaArray($imgSize[0], $imgSize[1], 'v-7');
        echo '$list<pre style="max-height: 150px; overflow: auto; border: 1px solid green;" >', print_r($list), '</pre>';

        echo 'timer: ' . Timer::finish('s2');

        $list2 = self::generateLinaArray($imgSize[0], $imgSize[1], 'g-7');
        echo '$list<pre style="max-height: 150px; overflow: auto; border: 1px solid green;" >', print_r($list), '</pre>';

        echo 'timer: ' . Timer::finish('s2');

        $rr['lines'] =
            self::$lineAll = array_merge($list, $list2);
        print_r(self::$lineAll);
        $rr['linesCount'] = sizeof($rr['lines']);
        // Cache::put('lineAll', self::$lineAll);
        $rr['linesLength'] = self::$lineLength;
        $rr['linesLength_max'] = max(self::$lineLength);
        $rr['linesLength_min'] = min(self::$lineLength);

        $rr['timer'] = Timer::finish('s2');
        echo 'timer: ' . Timer::finish('s2');
        // die('<br/>111');

        $out = ob_get_contents();

        ob_end_clean();

        if ($dop == 'json') {
            $rr['text'] = $out;
            // return json_encode($rr);
            // return response()->json($rr);
            return response()->json($rr);
        } else {
            echo $out;
        }
    }






    static function step7($dop = '', $dop2 = '')
    {

        ob_start();

        $rr = [
            'function' => __FUNCTION__,
            'file' => __FILE__,
            'line' => __LINE__
        ];

        echo $rr['function'] . '<br/>';
        echo 'Получаем адреса линий с кеша с параметрами оценки серости<br/><br/>';
        Timer::start('s2');

        // $img
        // берём временную картинку
        $img = GgImageController::readImage(self::$imageFileTmp);
        $imgSize = GgImageController::getSize(self::$imageFileTmp);
        // echo '<pre>', print_r($imgSize), '</pre>';

        // $lines = Cache::get('lineAll');
        //         echo '$list<pre style="max-height: 150px; overflow: auto; border: 1px solid green;" >', print_r($lines, true), '</pre>';
        // die();

        $new = [];

        $count = 0;
        $count7 = 0;

        Timer::start('s21');

        echo '<div style="padding: 2px; border: 1px solid orange;" >';
        GgImageController::grayTochkaGet();
        $gpCount = count(GgImageController::$gp, COUNT_RECURSIVE);
        echo 'count:' . $gpCount;
        echo ' / timer1: ' . Timer::finish('s21');
        echo '</div>';

        echo '<div style="max-height: 250px; overflow: auto; border: 1px solid green;" >';

        $rr['dop'] = $dop;
        $rr['dop2'] = $dop2;
        $rr['line'] = [];
        $rr['lines_id'] = [];

        $w = 0;

        foreach (self::$lineAll as $k => $v) {

            if ($v != 0)
                continue;

            // пропускаем по 4 линии
            // if ($w <= 30) {
            //     $w++;
            //     continue;
            // } else {
            //     $w = 0;
            // }

            if ($dop > 0) {
                if ($count7 >= $dop) {
                    // $count7=0;
                } else {
                    $count7++;
                    continue;
                }
            }

            $count++;

            // echo $k . ' ' . $v . '<br/>';
            echo $k;
            $a = self::generateLinaArrayStrInAr($k);
            $rr['lines_id'][] = $k;
            // echo '<pre>', print_r($a), '</pre>';
            // echo '<br/>';
            // echo 'ss='.GgImageController::lineCalcGrayAverage( $img , $a[0] , $a[1], $a[2], $a[3] );
            $gray = (int) GgImageController::lineCalcGrayAverage($img, $a[0], $a[1], $a[2], $a[3]);
            echo ' = ' . $gray . ' // ';

            // $rr['line'][$k] = self::generateLinaArrayStrInAr($k);
            self::$lineAll[self::generateLinaArrayStrInAr($k)] = $gray;
            // $rr['line'][$k]['gray'] = $gray;

            // echo '<br/>';
            // echo '<br/>';

            // $new[self::generateLinaArrayArInStr($a)] = $gray;
            // flush();
            // sleep(1);

            // if (Timer::finish('s2') > 5)
            // if (Timer::finish('s2') > 10){
            if (Timer::finish('s2') > 25) {
                break;
            }
        }

        echo '</div>';

        imagedestroy($img);

        Timer::start('s22');
        $newCount = count(GgImageController::$gp, COUNT_RECURSIVE);
        if ($gpCount != $newCount) {
            echo 'save ' . $newCount;
            GgImageController::grayTochkaSave();
        } else {
            echo 'not save';
        }
        echo 'timer2: ' . Timer::finish('s22');

        echo '<br/>';
        echo '<br/>';
        $rr['jobed'] = $count;
        echo 'обработано: ' . $rr['jobed'] . '<Br/>';

        echo 'timer: ' . Timer::finish('s2');

        $lineAll = array_merge($lines, $new);
        Cache::put('lineAll', $lineAll);
        echo '<br/>';
        $a0 = sizeof(array_filter($lineAll, function ($v, $k) {
            // return $k == 'b' || $v == 0;
            return $v == 0;
        }, ARRAY_FILTER_USE_BOTH));

        // $summa = ( sizeof($lineAll) - ( sizeof($lineAll) - $a0 ) );
        echo sizeof($lineAll) . ' ( ещё копать ' . $a0 . ' )';

        if ($a0 > 100) {
            echo '<script> 
        location.href=location.href;
        </script>';
        }

        echo '<br/>';

        $rr['timer'] = Timer::finish('s2');
        echo 'timer: ' . $rr['timer'];
        // die('<br/>111');

        $out = ob_get_contents();

        ob_end_clean();

        if ($dop2 == 'json') {
            $rr['text'] = $out;
            // return json_encode($rr);
            // return response()->json($rr);
            return response()->json($rr);
        } else {
            echo $out;
        }
    }



    // считаем линии ... на 500 дальше чем нужно (второй поток к 7 шагу )
    static function step8($dop = 0)
    {
        echo __FUNCTION__ . ' ' . date('H:i:s') . ' ' . ($dop ?? 'x') . '<br/>';
        echo 'Получаем адреса линий с кеша с параметрами оценки серости'
            // .'<br/><br/>'
        ;
        Timer::start('s2');

        // берём временную картинку
        $img = GgImageController::readImage(self::$imageFileTmp);
        $imgSize = GgImageController::getSize(self::$imageFileTmp);
        // echo '<pre>', print_r($imgSize), '</pre>';

        $lines = Cache::get('lineAll');
        // echo '$list<pre style="max-height: 150px; overflow: auto; border: 1px solid green;" >', print_r($lines, true), '</pre>';

        $new = [];

        $count0 = 0;
        $count = 0;

        foreach ($lines as $k => $v) {

            if ($v != 0)
                continue;

            $count0++;

            if ($count0 < $dop)
                continue;

            $count++;

            $a = self::generateLinaArrayStrInAr($k);
            // echo '<br/>';
            $gray = GgImageController::lineCalcGrayAverage($img, $a[0], $a[1], $a[2], $a[3]);
            // echo ' = ' . $gray . ' // ';

            $new[self::generateLinaArrayArInStr($a)] = $gray;
            // flush();
            // sleep(1);
            // if (Timer::finish('s2') > 25)
            if (Timer::finish('s2') > 10)
                break;
        }
        // echo '<br/>';
        echo '<br/>';
        echo 'обработано: ' . $count . '<Br/>';

        echo 'timer: ' . Timer::finish('s2');

        $lineAll = array_merge($lines, $new);
        Cache::put('lineAll', $lineAll);
        echo '<br/>';
        $a0 = sizeof(array_filter($lineAll, function ($v, $k) {
            // return $k == 'b' || $v == 0;
            return $v == 0;
        }, ARRAY_FILTER_USE_BOTH));

        // $summa = ( sizeof($lineAll) - ( sizeof($lineAll) - $a0 ) );
        echo sizeof($lineAll) . ' ( ещё копать ' . $a0 . ' )';

        if ($a0 > 100) {
            echo '<script> 
        location.href=location.href;
        </script>';
        }

        echo '<br/>';
        echo 'timer: ' . Timer::finish('s2');
        die('<br/>111');
    }






    static function step9($dop = '', $dop2 = '')
    {

        // // request->all()
        // echo '<pre>',print_r( [ $dop , $dop2 ] ), '</pre>';
        // // echo '<pre>',print_r( $r ), '</pre>';
        // die();

        // echo __FUNCTION__ . ' ( '.( $dop ?? 'x' ) .' )<br/>';
        // echo 'Получаем посчитанные строки<br/><br/>';
        Timer::start('s2');

        // берём временную картинку
        $img = GgImageController::readImage(self::$imageFileTmp);
        $imgSize = GgImageController::getSize(self::$imageFileTmp);
        // echo '<pre>', print_r($imgSize), '</pre>';



        $lines = Cache::get('lineAll');

        if ($dop == 'desc') {
            arsort($lines);
        } elseif ($dop == 'asc') {

            asort($lines);

            $l = $lines;
            $lines = [];
            $count = 0;
            $count1 = 0;

            foreach ($l as $k => $v) {

                if ($v > 1) {

                    $count1++;

                    if (!empty($dop2) && $dop2 > $count1) {
                        continue;
                    }

                    $lines[$k] = $v;
                    if ($count < 100) {
                        $count++;
                        $p = self::generateLinaArrayStrInAr($k);
                        GgImageController::paintLine($img, $p[0], $p[1], $p[2], $p[3]);
                    }
                }
            }
        }

        // echo '$list<pre style="max-height: 450px; overflow: auto; border: 1px solid green;" >', print_r($lines, true), '</pre>';


        header("Content-type: image/png");
        ImagePng($img);


        // $new = [];

        // $count = 0;

        // foreach ($lines as $k => $v) {

        //     if ($v != 0)
        //         continue;

        //     $count++;

        //     // echo $k . ' ' . $v . '<br/>';
        //     echo $k;
        //     $a = self::generateLinaArrayStrInAr($k);
        //     // echo '<pre>', print_r($a), '</pre>';
        //     // echo '<br/>';
        //     // echo 'ss='.GgImageController::lineCalcGrayAverage( $img , $a[0] , $a[1], $a[2], $a[3] );
        //     $gray = GgImageController::lineCalcGrayAverage($img, $a[0], $a[1], $a[2], $a[3]);
        //     echo ' = ' . $gray . ' // ';
        //     // echo '<br/>';
        //     // echo '<br/>';

        //     $new[self::generateLinaArrayArInStr($a)] = $gray;
        //     // flush();
        //     // sleep(1);
        //     if (Timer::finish('s2') > 25)
        //         break;
        // }
        // echo '<br/>';
        // echo '<br/>';
        // echo 'обработано: ' . $count . '<Br/>';

        // echo 'timer: ' . Timer::finish('s2');

        // $lineAll = array_merge($lines, $new);
        // Cache::put('lineAll', $lineAll);
        // echo '<br/>';
        // $a0 = sizeof(array_filter($lineAll, function ($v, $k) {
        //     // return $k == 'b' || $v == 0;
        //     return $v == 0;
        // }, ARRAY_FILTER_USE_BOTH));

        // // $summa = ( sizeof($lineAll) - ( sizeof($lineAll) - $a0 ) );
        // echo sizeof($lineAll) . ' ( ещё копать ' . $a0 . ' )';

        // if ($a0 > 100) {
        //     echo '<script> 
        // location.href=location.href;
        // </script>';
        // }

        echo '<br/>';
        echo 'timer: ' . Timer::finish('s2');
        die('<br/>111');
    }







    static function step10()
    {
        echo __FUNCTION__ . '<br/>';
        echo 'запуск 7 шага 10 раз<br/><br/>';
        Timer::start('s2');

        // берём временную картинку
        // $img = GgImageController::readImage(self::$imageFileTmp);
        // $imgSize = GgImageController::getSize(self::$imageFileTmp);
        // echo '<pre>', print_r($imgSize), '</pre>';

        echo '<style>
        iframe{
            width: 200px;
            heigth: 100px;
            display: inline-block;
            margin: 5px;
        }
        </style>';
        for ($t = 1; $t <= 15; $t++) {
            // echo '<Br/>';
            // echo '<Br/>';
            // echo '<Br/>';
            // echo '<Br/>';
            // echo '<Br/>';
            // echo '<Br/>';
            // echo '<Br/>';
            // echo $t;
            echo '<iframe src="http://localhost/api/gg/8/' . (500 * $t) . '" ></iframe> ';
        }


        // $lines = Cache::get('lineAll');
        // echo '$list<pre style="max-height: 450px; overflow: auto; border: 1px solid green;" >', print_r($lines, true), '</pre>';

        // $new = [];

        // $count = 0;

        // foreach ($lines as $k => $v) {

        //     if ($v != 0)
        //         continue;

        //     $count++;

        //     // echo $k . ' ' . $v . '<br/>';
        //     echo $k;
        //     $a = self::generateLinaArrayStrInAr($k);
        //     // echo '<pre>', print_r($a), '</pre>';
        //     // echo '<br/>';
        //     // echo 'ss='.GgImageController::lineCalcGrayAverage( $img , $a[0] , $a[1], $a[2], $a[3] );
        //     $gray = GgImageController::lineCalcGrayAverage($img, $a[0], $a[1], $a[2], $a[3]);
        //     echo ' = ' . $gray . ' // ';
        //     // echo '<br/>';
        //     // echo '<br/>';

        //     $new[self::generateLinaArrayArInStr($a)] = $gray;
        //     // flush();
        //     // sleep(1);
        //     if (Timer::finish('s2') > 25)
        //         break;
        // }
        // echo '<br/>';
        // echo '<br/>';
        // echo 'обработано: ' . $count . '<Br/>';

        // echo 'timer: ' . Timer::finish('s2');

        // $lineAll = array_merge($lines, $new);
        // Cache::put('lineAll', $lineAll);
        // echo '<br/>';
        // $a0 = sizeof(array_filter($lineAll, function ($v, $k) {
        //     // return $k == 'b' || $v == 0;
        //     return $v == 0;
        // }, ARRAY_FILTER_USE_BOTH));

        // // $summa = ( sizeof($lineAll) - ( sizeof($lineAll) - $a0 ) );
        // echo sizeof($lineAll) . ' ( ещё копать ' . $a0 . ' )';

        // if ($a0 > 100) {
        //     echo '<script> 
        // location.href=location.href;
        // </script>';
        // }

        echo '<br/>';
        echo 'timer: ' . Timer::finish('s2');
        die('<br/>111');
    }
















    static function step11($dop = 0)
    {
        echo __FUNCTION__ . '<br/>';
        echo 'прогреваем кеш индекса серого в изображении<br/><br/>';
        Timer::start('s2');

        // берём временную картинку
        $img = GgImageController::readImage(self::$imageFileTmp);
        $s = $imgSize = GgImageController::getSize(self::$imageFileTmp);
        // echo '<pre>', print_r($imgSize), '</pre>';

        GgImageController::grayTochkaGet();

        for ($x = 1; $x <= $s[0]; $x++) {
            for ($y = 1; $y <= $s[1]; $y++) {

                GgImageController::$gp[$x][$y] = GgImageController::grayTochka($img, $x, $y);
            }
        }

        GgImageController::grayTochkaSave();

        echo '<br/>';
        echo 'timer: ' . Timer::finish('s2');
        // die('<br/>111');
    }









    static function step12()
    {

        // echo __FUNCTION__ . '<br/>';
        // echo 'список ссылок сервиса<br/><br/>';
        Timer::start('s2');

        $links = [
            '01' => [
                'name' => 'vue расчёт линий',
                'link' => '/lines'
            ],
            1 => [
                'name' => 'показ картинки блоками',
                'link' => '/api/gg/1'
            ],
            2 => [
                'name' => 'функ 2',
                'link' => '/api/gg/2'
            ],
            3 => [
                'name' => 'показ картинки со всеми просчитанными линиями',
                'link' => '/api/gg/3'
            ],
            4 => [
                'name' => 'функ 4',
                'link' => '/api/gg/4'
            ],
            5 => [
                'name' => 'функ 5',
                'link' => '/api/gg/5'
            ],
            6 => [
                'name' => '++ расчёт линий и запись с 0 в кеш',
                'link' => '/api/gg/6'
            ],
            7 => [
                'name' => 'запуск 7 шага 10 раз',
                'link' => '/api/gg/7'
            ],
            8 => [
                'name' => 'функ 8',
                'link' => '/api/gg/8'
            ],
            9 => [
                'name' => 'функ 9',
                'link' => '/api/gg/9'
            ],
            10 => [
                'name' => 'функ 10',
                'link' => '/api/gg/10'
            ],
            11 => [
                'name' => 'прогреваем кеш индекса серого в изображении',
                'link' => '/api/gg/11'
            ],
            '13' => [
                'name' => '++ загружаем картинку для следующих расчётов',
                'link' => '/api/gg/13'
            ],
            '14' => [
                'name' => '++ режем оригинал в норм квадрат для линий',
                'link' => '/api/gg/14'
            ],
            '15' => [
                'name' => '++ показ временной картинки',
                'link' => '/api/gg/15'
            ],
            '16' => [
                'name' => '++ показ массива с линиями',
                'link' => '/api/gg/16'
            ],
            '18' => [
                'name' => '++ показ временной картинки с линиями зафиксенными',
                'link' => '/api/gg/18'
            ],
            '19' => [
                'name' => '++ показ сохранённых линий',
                'link' => '/api/gg/19'
            ],
            '21' => [
                'name' => '++ показ сохранённых длинн линий',
                'link' => '/api/gg/21'
            ]
        ];

        echo '
        <style>
        .a .a{
            display: inline-block;
            width: 150px;
            float: left;
            font-size: 12px;
        }
        .a iframe{ width: 100%; height: 600px; }
        .a .b{ 
            display: inline-block;
            width: calc( 100% - 150px );
         }
        </style>
        <div class="a" >
        <div class="a" >';

        foreach ($links as $k => $v) {
            echo '<a href="//' . $_SERVER['HTTP_HOST'] . $v['link'] . '" target="iframe_a" >' . $k . ' ' . $v['name'] . '</a>'
                // .'<br/>'
                . '<br/>';
        }

        echo '</div>
            <div class="b" >
                <iframe src="" name="iframe_a"></iframe>
            </div>
        </div>';

        echo '<br/>';
        echo 'timer: ' . Timer::finish('s2');
        // die('<br/>111');


    }



    static function step13($dop = '', $dop2 = '', Request $request)
    {

        echo __FUNCTION__ . '<br/>';
        echo 'список ссылок сервиса<br/><br/>';
        Timer::start('s2');

        // $request = Request();
        // if ($request->hasFile('image')) {
        //     echo '654654654';
        // }

        if ($request->isMethod('post')) {
            if ($request->hasFile('image')) {

                //     $path = $request->file('avatar')->store('avatars');
                // //      return $path;
                //      echo '<pre>'; print_r( $path ); echo '</pre>';

                $file = $request->file('image');
                //     // $e = $file->move(public_path() . '/path','filename.img');
                $e = $file->move(public_path('storage') . '/lines', 'origin.jpg');
                //     // $e = $file->move( '/storage/app/public/path','filename.img');
                //     // echo 11;
                echo '11+' . $e . '+22<br/>';
                echo '11+' . filesize($e) . '+22<br/>';
                //     // echo 22;
                //     // echo '987987';

                //     echo '<pre>';
                //     // print_r($_FILES, true);
                //     // print_r($file, true);
                //     // print_r(Request::allFiles(), true);
                //     echo '</pre>';

            }
        }

        echo '<form action="" method="post" enctype="multipart/form-data" >';
        echo '<input type="file" name="image" />';
        echo '<br />';
        echo '<input type="submit" value="отправить" />';
        echo '<br />';
        echo '</form>';

        // $ar = Cache::get('grayArray', function () {
        //     $r = self::creatGrayArray($_SERVER['DOCUMENT_ROOT'] . '/storage/photo_no.jpg');
        //     // echo '<pre>',print_r($r),'</pre>';
        //     return $r['colors'];
        // });

        // // if (Cache::has('grayArray')) {
        // // } else {
        // //     die('нет картинки' . Cache::has('grayArray'));
        // // }

        // $ee = Cache::get('grayArray');
        // // echo '<pre style="max-height: 200px; overflow: auto;" >', print_r($ee), '</pre>';

        // // if( !Cache::has('2lines') ){
        // //     die('нет выделенных самых тёмных линий');
        // // }

        // // $lines = Cache::get('2lines');
        // $lines = Cache::get('lineAll');
        // //         echo '<pre style="max-height: 200px; overflow: auto;" >', print_r($lines), '</pre>';
        // // die();

        // self::$im1 = imagecreatefrompng(self::$imageFileTmp) or die("Ошибка при создании изображения");
        // // // $im2 = imagecreatefromjpeg( self::$imageFile ) or die("Ошибка при создании изображения");
        // // // $im2 = ImageCreate(self::$imgWidth, self::$imgHeigth) or die("Ошибка при создании изображения");
        // // // $couleur_fond = ImageColorAllocate($im2, 0, 0, 0);

        // header("Content-type: image/png");
        // // $color = imagecolorallocatealpha(self::$im1, 255, 255, 255, 120);
        // $color = imagecolorallocatealpha(self::$im1, 255, 255, 255, 100);

        // foreach ($lines as $v1 => $k1) {
        //     // if ( !empty($dop2) && $k1 > 0) {
        //     if ($k1 > 0) {
        //         // if ($k1 > 4000) {
        //         // echo '<pre>';
        //         // print_r( $v );
        //         // echo '<pre>';
        //         $v = self::generateLinaArrayStrInAr($v1);
        //         imageline(self::$im1, $v[0], $v[1], $v[2], $v[3], $color);
        //     }
        // }

        // // // ImagePng(self::$im1, self::$imageFileTmp);
        // ImagePng(self::$im1);
        // die();
        // // Cache::put('imgTmp', self::$im1);

        // echo '<pre>', print_r(\scandir($_SERVER['DOCUMENT_ROOT'])), '</pre>';

        echo '<br/>';
        echo 'timer: ' . Timer::finish('s2');
        // die('<br/>111');

    }


    static function step14($dop = '', $dop2 = '', Request $request)
    {

        // echo __FUNCTION__ . '<br/>';
        // echo 'режем изображение в норм для расшифровки<br/><br/>';
        Timer::start('s2');

        // use Illuminate\Support\Facades\Storage;
        // $path = Storage::path(public_path('storage') .'/lines/origin.jpg');

        $e = Storage::path('public/lines/origin.jpg');

        GgImageController::$tempImgUri = // 'lines/origin.jpg';
            $e2 = 'lines/origin.tmp.png';
        // echo $e;
        // echo '11+' . $e . '+22<br/>';
        // echo '11+' . filesize($e) . '+22<br/>';

        GgImageController::imageResize($e, 200, $e2);
        // die();

        // echo '<br/>';
        // echo 'timer: ' . Timer::finish('s2');
        // die('<br/>111');

    }


    static function step15($dop = '', $dop2 = '', Request $request)
    {

        if (empty(GgImageController::$tempImgUri)) {
            echo 'пустой GgImageController::$tempImgUri ' . __LINE__;
            GgImageController::$tempImgUri = 'lines/origin.tmp.png';
        }

        $e2 = Storage::path('public/' . GgImageController::$tempImgUri);

        // GgImageController::imageTmpShow();
        echo (GgImageController::$tempImgUri ?? 'x') . '<br/>';
        // echo '"/storage/' . GgImageController::$tempImgUri . '"<Br/>';
        echo '<img src="/storage/' . GgImageController::$tempImgUri . '" />';
        // echo '"/storage/lines/origin.tmp.png"<br/>';
        // echo '<img src="/storage/lines/origin.tmp.png" />';
    }



    static function step16($dop = '', $dop2 = '', Request $request)
    {

        echo '<pre>';
        print_r(self::$lineAll);
        echo '</pre>';
    }












    static function step17($dop = 0, $dop2 = '', Request $request)
    {

        ob_start();

        $rr = [
            'function' => __FUNCTION__,
            'file' => __FILE__,
            'line' => __LINE__,
            'dop' => $dop,
            'dop2' => $dop2,
            'req' => $request->all(),
        ];

        echo $rr['function'] . '<br/>';
        echo 'Считаем серость линий из списка линий с 0-ём<br/><br/>';
        Timer::start('s2');

        //         // берём временную картинку
        // $img = GgImageController::readImage(self::$imageFileTmp);
        $img = GgImageController::readImage(Storage::path('public/' . GgImageController::$tempImgUri));



        $save_line = Cache::get('line_saved');
        $color = imagecolorallocatealpha($img, 255, 255, 255, 50);
        // $save_line
        foreach ($save_line as $k => $v) {
            $q = self::generateLinaArrayStrInAr($k);
            imageline($img, $q[0], $q[1], $q[2], $q[3], $color);
        }


        $imgSize = GgImageController::getSize(Storage::path('public/' . GgImageController::$tempImgUri));
        //         // echo '<pre>', print_r($imgSize), '</pre>';

        GgImageController::$imgWidth = $imgSize[0];
        GgImageController::$imgHeigth = $imgSize[1];

        //         // $lines = Cache::get('lineAll');
        // $rr['lines'] = self::$lineAll;
        // echo '$list<pre style="max-height: 150px; overflow: auto; border: 1px solid green;" >', print_r(self::$lineAll, true), '</pre>';
        // // die();

        //         $new = [];

        $count = 0;
        //         $count7 = 0;

        // Timer::start('s21');

        //         echo '<div style="padding: 2px; border: 1px solid orange;" >';
        //         GgImageController::grayTochkaGet();
        //         $gpCount = count(GgImageController::$gp, COUNT_RECURSIVE);
        //         echo 'count:' . $gpCount;
        //         echo ' / timer1: ' . Timer::finish('s21');
        //         echo '</div>';

        //         echo '<div style="max-height: 250px; overflow: auto; border: 1px solid green;" >';

        //         $rr['dop'] = $dop;
        //         $rr['dop2'] = $dop2;
        //         $rr['line'] = [];
        $rr['lines_id'] = [];

        //         $w = 0;

        $rr['jobed'] = 0;
        $rr['gray'] = [];

        foreach (self::$lineAll as $k => $v) {

            if ($v >= 3)
                continue;

            //             // пропускаем по 4 линии
            //             // if ($w <= 30) {
            //             //     $w++;
            //             //     continue;
            //             // } else {
            //             //     $w = 0;
            //             // }

            $count++;

            if ($dop > 0) {
                if ($count >= $dop) {
                    // $count7=0;
                } else {
                    // $count7++;
                    continue;
                }
            }


            // if( $count > 20 )
            // break;

            $rr['jobed']++;

            //             // echo $k . ' ' . $v . '<br/>';
            //             echo $k;

            $a = self::generateLinaArrayStrInAr($k);
            $rr['lines_id'][] = $k;

            //             // echo '<pre>', print_r($a), '</pre>';
            //             // echo '<br/>';
            //             // echo 'ss='.GgImageController::lineCalcGrayAverage( $img , $a[0] , $a[1], $a[2], $a[3] );

            $rr['gray'][$k] = $gray = GgImageController::lineCalcGrayAverage($img, $a[0], $a[1], $a[2], $a[3]);
            self::$lineAll[$k] = $gray > 2 ? $gray : 2;
            // echo ' = ' . $gray . ' // ';

            //             // $rr['line'][$k] = self::generateLinaArrayStrInAr($k);
            //             self::$lineAll[self::generateLinaArrayStrInAr($k)] = $gray;
            //             // $rr['line'][$k]['gray'] = $gray;

            //             // echo '<br/>';
            //             // echo '<br/>';

            //             // $new[self::generateLinaArrayArInStr($a)] = $gray;
            //             // flush();
            // sleep(1);

            if (Timer::finish('s2') > 2) {
                // if (Timer::finish('s2') > 20) {
                //             // if (Timer::finish('s2') > 10){
                // if (Timer::finish('s2') > 25) {
                break;
            }
        }

        $rr['lines_id_colvo'] = sizeof($rr['lines_id']);
        echo '<br/>колво:' . sizeof($rr['lines_id']);
        echo '<br/>колво:' . sizeof($rr['gray']);
        echo '<br/>колво2:' . $count;

        //         echo '</div>';

        //         imagedestroy($img);

        //         Timer::start('s22');
        //         $newCount = count(GgImageController::$gp, COUNT_RECURSIVE);
        //         if ($gpCount != $newCount) {
        //             echo 'save ' . $newCount;
        //             GgImageController::grayTochkaSave();
        //         } else {
        //             echo 'not save';
        //         }
        //         echo 'timer2: ' . Timer::finish('s22');

        //         echo '<br/>';
        //         echo '<br/>';
        //         $rr['jobed'] = $count;
        //         echo 'обработано: ' . $rr['jobed'] . '<Br/>';

        //         echo 'timer: ' . Timer::finish('s2');

        //         $lineAll = array_merge($lines, $new);
        //         Cache::put('lineAll', $lineAll);
        //         echo '<br/>';
        $a0 = sizeof(array_filter(self::$lineAll, function ($v, $k) {
            // return $k == 'b' || $v == 0;
            // echo '<Br/>++ '.$k.' '.$v;
            return $v != 0;
        }, ARRAY_FILTER_USE_BOTH));

        echo '<br/>kolvoA:' . $a0;
        echo '<br/>kolvoA9:' . sizeof(self::$lineAll);

        if (sizeof(self::$lineAll) > 0 && $a0 > 0) {
            echo '<br/>обработкано % :' . round($a0 / (sizeof(self::$lineAll)  / 100), 1);
            //         // $summa = ( sizeof($lineAll) - ( sizeof($lineAll) - $a0 ) );
            //         echo sizeof($lineAll) . ' ( ещё копать ' . $a0 . ' )';
            $rr['pr_obr'] = round($a0 / (sizeof(self::$lineAll)  / 100), 1);
        }

        if ($count > 30) {
            echo '<script> 
                location.href=location.href;
                </script>';
        }

        echo '<br/>';

        // $rr['lines_id'] = [];
        // $rr['lines'] = self::$lineAll;

        $rr['timer'] = Timer::finish('s2');
        echo 'timer: ' . $rr['timer'];
        // die('<br/>111');

        $out = ob_get_contents();

        ob_end_clean();

        if ($dop2 == 'json') {
            $rr['text'] = $out;
            // return json_encode($rr);
            // return response()->json($rr);
            return response()->json($rr);
        } else {
            echo $out;
        }
    }









    static function step18($dop = '', $dop2 = '', Request $request)
    {

        // dd( [ $request->input('dop2') , $dop2 ] );

        if (empty(GgImageController::$tempImgUri)) {
            echo 'пустой GgImageController::$tempImgUri ' . __LINE__;
            GgImageController::$tempImgUri = 'lines/origin.tmp.png';
        }

        $e2 = Storage::path('public/' . GgImageController::$tempImgUri);

        // echo $e2.'<br/>';

        if ($dop != 'save')
            header("Content-type: image/png");

        $im = imagecreatefrompng($e2) or die("Ошибка при создании изображения");

        // $im2 = ImageCreate(self::$imgWidth, self::$imgHeigth) or die("Ошибка при создании изображения");
        // $couleur_fond = ImageColorAllocate($im2, 0, 0, 0);
        // $color = imagecolorallocatealpha($im, 0, 0, 0, 110);

        // $color = imagecolorallocatealpha($im, 255, 255, 255, 110);
        $color = imagecolorallocatealpha($im, 255, 255, 255, 50);

        $ee = 0;

        $we = self::$lineAll;
        arsort($we);

        $skip0 = [];
        $skip1 = [];
        $skip2 = [];
        $skip3 = [];

        if ($dop == 'save') {
            $save_line = Cache::get('line_saved');
            if ($dop2 == 1) {
                $save_line = [];
            }
        }

        $save_line = Cache::get('line_saved');

        // $save_line
        foreach ($save_line as $k => $v) {
            $q = self::generateLinaArrayStrInAr($k);
            imageline($im, $q[0], $q[1], $q[2], $q[3], $color);
        }

        if (1 == 2) {

            // foreach (self::$lineAll as $k => $v) {
            foreach ($we as $k => $v) {

                $q = self::generateLinaArrayStrInAr($k);

                // dd($q);
                // if (isset($skip0[$q[0]]) && $skip0[$q[0]] > 3)                continue;

                if (isset($skip0[$q[0] . '.' . $q[1]]))
                    continue;

                // if ( isset($skip2[$q[2]]) )                continue;
                // if (!empty($skip1[$q[1]]))                continue;
                // if (!empty($skip2[$q[2]]))                continue;
                // if (!empty($skip3[$q[3]]))                continue;

                if (!isset($skip0[$q[0] . '.' . $q[1]])) {
                    // if (!isset($skip0[$q[0]])) {
                    $skip0[$q[0] . '.' . $q[1]] = 1;
                    // $skip0[$q[0]] = 1;
                } else {
                    $skip0[$q[0] . '.' . $q[1]]++;
                    // $skip0[$q[0]]++;
                }
                // if (!isset($skip2[$q[2]])) {
                //     $skip2[$q[2]] = 1;
                // } else {
                //     $skip2[$q[2]]++;
                // }

                // $skip1[$q[1]] = 1;
                // $skip2[$q[2]] = 
                // $skip3[$q[3]] = 1;

                // if( rand(1,2) == 2 )
                // continue;

                imageline($im, $q[0], $q[1], $q[2], $q[3], $color);


                if ($dop == 'save') {
                    if (isset($save_line[$k])) {
                        $save_line[$k]++;
                    } else {
                        $save_line[$k] = 1;
                    }
                }

                if ($ee >= 100)
                    break;

                $ee++;
            }
        }
        if ($dop != 'save')
            ImagePng($im);

        if ($dop == 'save') {
            Cache::put('line_saved', $save_line);
            // echo 'asdasd';
            self::$lineAll = [];
            Cache::put('lineAll', []);
            echo 'сохранили линии, удалили расчитанные линии';
        }

        // // GgImageController::imageTmpShow();
        // echo (GgImageController::$tempImgUri ?? 'x') . '<br/>';
        // // echo '"/storage/' . GgImageController::$tempImgUri . '"<Br/>';
        // echo '<img src="/storage/' . GgImageController::$tempImgUri . '" />';
        // // echo '"/storage/lines/origin.tmp.png"<br/>';
        // // echo '<img src="/storage/lines/origin.tmp.png" />';
    }







    static function step19($dop = '', $dop2 = '', Request $request)
    {
        $ee = Cache::get('line_saved');
        dD($ee);
    }


    static function step21($dop = '', $dop2 = '', Request $request)
    {
        $ee = Cache::get('lineLength');
        dD($ee);
    }


    /**
     * сохраняем линии входящие в процентом соотношении по заполненности (тёмности)
     * $dop - сколько процентов отбираем от максимального значения
     */
    static function step20($dop = '', $dop2 = '', Request $request)
    {

        // $procentOtbobora = 30;
        $procentOtbobora = $dop;

        $ee = Cache::get('lineAll');
        $srednee = round(array_sum($ee) / sizeof($ee), 1);
        $maxx = round(max($ee), 1);

        $granica = $maxx / 100 * (100 - $procentOtbobora);

        $save_new_line = array_filter($ee, function ($v, $k) use ($granica) {
            // return $k == 'b' || $v == 0;
            // echo '<Br/>++ '.$k.' '.$v;
            return $v >= $granica;
        }, ARRAY_FILTER_USE_BOTH);

        // сохраняем только эти расчитанные линии, старое стираем
        if (strpos($dop2, 'new') !== false) {
            // if ($dop2 == 'new') {
            Cache::put('line_saved', $save_new_line);
        }
        // добавляем новые линии к существующим
        else {
            $save_line = array_merge($save_new_line, $save_new_line);
            Cache::put('line_saved', $save_line);
        }

        if (strpos($dop2, 'json') !== false) {
            return response()->json(
                [
                    'save_lines' => $save_line ?? [],
                    'new_lines' => $save_new_line ?? []
                ]
            );
        } else {
            dD([$srednee, $maxx, $save_new_line]);
            // dD($ee);
        }
    }















    static function showNowPicture()
    {
        $ar = Cache::get('grayArray', function () {
            $r = self::creatGrayArray($_SERVER['DOCUMENT_ROOT'] . '/storage/photo_no.jpg');
            return $r['colors'];
        });

        // echo '<pre>', print_r($ar), '</pre>';

        echo '<style>.tt div{          
       display: inline-block;
            height: 2px;
            width: 2px;
            overflow: hidden;
        }</style>';
        echo '<pre><div class="tt" >';

        foreach ($ar as $k => $v) {
            foreach ($v as $k1 => $v1) {
                // echo $v1 . ' ';
                echo '<div style="background-color: rgba(0,0,0,' . (1 - round($v1 / 255, 1)) . ')"
                        title="' . (1 - round($v1 / 255, 1)) . '">&nbsp;</div>';
            }
            echo '<br/>';
            flush();
        }

        echo '</div></pre>';
    }

    static function grayTochka($img, $x, $y)
    {
        $rgb = imagecolorat($img, $x, $y);
        $c = imagecolorsforindex($img, $rgb);
        return self::graySrednee($c);
    }

    static function redTochka($img, $x, $y)
    {
        $rgb = imagecolorat($img, $x, $y);
        $c = imagecolorsforindex($img, $rgb);
        // dd($c);
        return $c['red'];
    }

    static function graySrednee($r)
    {
        // echo '<pre>', print_r($r), '</pre>';
        return round(($r['red'] + $r['green'] + $r['blue']) / 3, 1);
    }

    /**
     * вычисляем длинну строки
     */
    static public function lengthLine($x1, $y1, $x2,  $y2, $x = '')
    {
        $dist = sqrt(
            pow($x2 - $x1, 2)  +
                pow($y2 - $y1, 2)
        );
        return round($dist, 1);
    }
}
