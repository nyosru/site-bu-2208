<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimerController extends Controller
{

    public static $timerSt = [];

    static function start($name = 'x')
    {
        self::$timerSt[$name] = microtime(true);
    }

    static function finish($name = 'x')
    {
        return round(microtime(true) - self::$timerSt[$name], 4);
    }
}
