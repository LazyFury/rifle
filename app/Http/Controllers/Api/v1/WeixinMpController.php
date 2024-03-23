<?php

namespace App\Http\Controllers\Api\v1;

use Common\Utils\ApiJsonResponse;
use Illuminate\Support\Facades\Http;

class WeixinMpController
{
    public static function routers()
    {
        return [
            'jsapi' => [
                'method' => 'get',
                'uri' => 'jsapi',
                'action' => 'jsapi',
            ],
        ];
    }

    public function jsapi(\Illuminate\Http\Request $request)
    {
        $appId = 'wx312c64478e116813';
        $appSecret = 'e94cb44d5b30c1daee25f7f59e7ac8a7';
        $result = Http::get('https://api.weixin.qq.com/cgi-bin/token', [
            'grant_type' => 'client_credential',
            'appid' => $appId,
            'secret' => $appSecret,
        ])->json();
        logger($result);
        if (isset($result['errcode'])) {
            return ApiJsonResponse::error($result['errmsg']);
        }

        $accessToken = $result['access_token'];

        $jsapi_ticket = Http::get('https://api.weixin.qq.com/cgi-bin/ticket/getticket', [
            'access_token' => $accessToken,
            'type' => 'jsapi',
        ])->json()['ticket'];

        $noncestr = 'Wm3WZYTPz0wzccnW';
        $timestamp = time();
        $url = $request->input('url');
        $url = base64_decode($url);
        $str = "jsapi_ticket=$jsapi_ticket&noncestr=$noncestr&timestamp=$timestamp&url=$url";
        $signature = sha1($str);

        return response()->json([
            'appId' => $appId,
            'timeStamp' => $timestamp,
            'nonceStr' => $noncestr,
            'signature' => $signature,
            'url' => $url,
            'code' => 200,
        ]);
    }
}
