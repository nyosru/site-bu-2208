<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
// use Illuminate\Filesystem\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GgImageController extends Controller
{

    /**
     * временная картинка
     */
    static public  $tempImg;
    static public  $tempImg1;
    static public  $tempImgUri = '';
    static public $imgWidth = 0;
    static public $imgHeigth = 0;



    static public function getSize($filename)
    {
        $imSize = getimagesize($filename);
        // echo '<pre>', print_R($imSize), '</pre>';

        self::$imgWidth = $imSize[0];
        // Cache::put('w', $imSize[0]);
        self::$imgHeigth = $imSize[1];
        // Cache::put('h', $imSize[1]);
        return $imSize;
    }

    static public function readImage($uri)
    {

        $info = new \SplFileInfo($uri);
        // var_dump($info->getExtension());

        // Создаем изображение
        if ($info->getExtension() == 'jpg') {
            $im = \imagecreatefromjpeg($uri);
        } else if ($info->getExtension() == 'png') {
            $im = \imagecreatefrompng($uri);
        }

        return $im ?? false;
    }

    static function redTochka($img, $x, $y)
    {
        $rgb = imagecolorat($img, $x, $y);
        $c = imagecolorsforindex($img, $rgb);
        // dd($c);
        return $c['red'];
    }

    static function paintLine($img, $startX, $startY, $finX, $finY)
    {
        $color = imagecolorallocatealpha($img, 255, 255, 255, 0.3);
        imageline($img, $startX, $startY, $finX, $finY, $color);
    }

    static function calcLine($startX, $startY, $finX, $finY)
    {
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
            foreach ($v as $k1 => $v1) {
                if ($v1 > 100) {
                    $sum += self::$grayArray[$k][$k1];
                }
            }
        }

        return $sum;
    }

    static function calcLength($x1, $y1, $x2, $y2)
    {
        if ($y2 >= $y1) {
            $s1 = $y2 - $y1;
        } else {
            $s1 = $y1 - $y2;
        }

        if ($x2 >= $x1) {
            $s2 = $x2 - $x1;
        } else {
            $s2 = $x1 - $x2;
        }

        return $s1 + $s2;
    }




    static function imageResize($imgOriginPath, $newWH, $saveTo)
    {
        self::$imgWidth =
            self::$imgHeigth = $newWH;

        // self::$tempImg = ImageCreate(self::$imgWidth, self::$imgHeigth) or die("Ошибка при создании изображения");
        self::$tempImg = imagecreatetruecolor(self::$imgWidth, self::$imgHeigth) or die("Ошибка при создании изображения");
        $colorWhite = ImageColorAllocate(self::$tempImg, 255, 255, 255);
        imagefill(
            self::$tempImg,
            1,
            1,
            $colorWhite
        );

        $jpgNow = imagecreatefromjpeg($imgOriginPath);
        $size = getimagesize($imgOriginPath);
        $newWHOrigin = $size[0] <= $size[1] ? $size[0] : $size[1];

        // imagecopyresampled(
        imagecopyresized(
            self::$tempImg, // GdImage $dst_image,
            $jpgNow, // GdImage $src_image,
            ceil($newWH / 100 * 3), // int $dst_x,
            ceil($newWH / 100 * 3), // int $dst_y,
            0, // int $src_x,
            0, // int $src_y,
            ceil($newWH / 100 * 94), // int $dst_width,
            ceil($newWH / 100 * 94), // int $dst_height,
            $newWHOrigin, // int $src_width,
            $newWHOrigin, // int $src_height
        );

        ImagePng(self::$tempImg, Storage::path('public/' . $saveTo));
        // self::$tempImgUri = 'lines/origin.jpg';
        self::$tempImgUri = $saveTo;

        header("Content-type: image/png");
        ImagePng(self::$tempImg);
    }


    static function imageTmpShow()
    {
        // $jpgNow = imagecreatefrompng(self::$tempImgUri);
        // header("Content-type: image/png");
        // ImagePng($jpgNow);

        header("Content-type: image/png");
        ImagePng(self::$tempImg);
    }




    static function lineCalcGrayAverage($imgGdOrigin, $startX, $startY, $finX, $finY)
    {

        // return self::calcLength($startX, $startY, $finX, $finY);
        // dd(self::$imgWidth, self::$imgHeigth);

        // header("Content-type: image/png");
        // if (empty(self::$tempImg)) {
        //     self::$tempImg = ImageCreate(self::$imgWidth, self::$imgHeigth) or die("Ошибка при создании изображения");
        if (empty(self::$tempImg1)) {
            self::$tempImg1 = ImageCreate(self::$imgWidth * 10, self::$imgHeigth * 10) or die("Ошибка при создании изображения");
        } else {
        }

        // $im2 = ImageCreate(self::$imgWidth, self::$imgHeigth) or die("Ошибка при создании изображения");
        $couleur_fond = ImageColorAllocate(self::$tempImg1, 0, 0, 0);
        $color = imagecolorallocatealpha(self::$tempImg1, 255, 0, 0, 0);

        // imageline(self::$tempImg, $startX, $startY, $finX, $finY, $color);

        // for ($q = 9; $q <= 10; $q++) {
            $q = 10;
            imageline(self::$tempImg1, $startX * $q, $startY * $q, $finX * $q, $finY * $q, $color);
            // $q = 9;
            // imageline(self::$tempImg1, $startX * $q, $startY * $q, $finX * $q, $finY * $q, $color);
            // $q = 11;
            // imageline(self::$tempImg1, $startX * $q, $startY * $q, $finX * $q, $finY * $q, $color);
        // }

        self::$tempImg = imagecreatetruecolor(self::$imgWidth, self::$imgHeigth) or die("Ошибка при создании изображения");

        imagecopyresized(
            self::$tempImg, // GdImage $dst_image,
            self::$tempImg1, // GdImage $src_image,
            0, // int $dst_x,
            0, // int $dst_y,
            0, // int $src_x,
            0, // int $src_y,
            $finX, // int $dst_width,
            $finY, // int $dst_height,
            $finX * 10, // int $src_width,
            $finY * 10, // int $src_height
        );

        // ImagePng($im);

        $red = [];

        for ($y = 1; $y < self::$imgHeigth; $y++) {
            for ($x = 1; $x < self::$imgWidth; $x++) {
                // $sum += 
                $red[$y][$x] = self::redTochka(self::$tempImg, $x, $y);
            }
        }

        $sum = 0;

        foreach ($red as $gx => $v) {
            foreach ($v as $gy => $v1) {
                if ($v1 == 255) {
                    $sum += self::grayTochka($imgGdOrigin, $gx, $gy);
                    // $sum += self::$grayArray[$k][$k1];
                }
            }
        }

        // return $sum;

        // return ceil($sum) .' / '. self::calcLength($startX, $startY, $finX, $finY) .' // '. $startX .' + '.$startY.' , '.$finX.' + '. $finY;
        $ss = round(ceil($sum) / self::calcLength($startX, $startY, $finX, $finY), 1);
        return $ss < 3 ? 3 : $ss;

        // dd($red[2]);
        // // // $im = imagecreate(self::$imgWidth, self::$imgHeigth) or die("Невозможно создать поток изображения");
        // // $im = imagecreatetruecolor(self::$imgWidth, self::$imgHeigth) or die("Невозможно создать поток изображения");
        // // // $color = imagecolorallocatealpha($im, 255, 0, 0, 0);
        // // // imageline($im, $startX, $startY, $finX, $finY, $color);
        // // // imagedestroy($im);
        // die();
    }

    // Gray Point
    static public $gp = [];

    static function grayTochkaSave()
    {
        Cache::put('gp', self::$gp);
    }

    static function grayTochkaGet()
    {
        self::$gp = Cache::get('gp');
    }

    static function grayTochka($img, $x, $y)
    {
        try {

            if (!empty(self::$gp[$x][$y]))
                return self::$gp[$x][$y];

            //code...
            $rgb = imagecolorat($img, $x, $y);
            // if ($rgb === false)
            //     return 1;
            $c = imagecolorsforindex($img, $rgb);
            return
                self::$gp[$x][$y] = self::graySrednee($c);
            // return self::graySrednee($c);

        } catch (\Throwable $th) {
            //throw $th;
            return 1;
        }
    }
    static function graySrednee($r)
    {
        // echo '<pre>', print_r($r), '</pre>';
        return round(($r['red'] + $r['green'] + $r['blue']) / 3, 1);
    }
}
