<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VkController extends Controller
{
    public static $client_id = '';
    public static $client_secret = '';
    //  $code,
    public static $redirect_uri = '';

    // получаем инфу о посетителе
    public static function getDataFromCode($code)
    {

        $params = [
            'client_id' => self::$client_id,
            'client_secret' => self::$client_secret,
            // 'code' => $_GET['code'],
            'code' => $code,
            'redirect_uri' => self::$redirect_uri
        ];

        $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

        // return json_encode([1 => 2]);

        if (!empty($token['access_token'])) {

            $params = [
                'uids' => $token['user_id'],
                'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
                'access_token' => $token['access_token'],
                'v' => '5.101'
            ];

            $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
            return !empty($userInfo['response'][0]['id']) ? $userInfo['response'][0] : false;
        }
    }
}
