<?php

namespace App\Http\Controllers\Api\v1\Set;

use App\Http\Controllers\Controller;
use Common\Utils\ApiJsonResponse;
use Illuminate\Http\Request;

class LogController extends Controller
{
    //

    // routes
    public static function routers()
    {
        $tag = "日志管理";
        return [
            'test' => [
                'method' => 'get',
                'uri' => 'test',
                'action' => 'test',
                'meta' => [
                    'tag' => $tag,
                    "name" => "测试日志",
                ]
            ],
            'hello' => [
                'method' => 'get',
                'uri' => 'hello',
                'action' => 'hello',
                'meta' => [
                    'tag' => $tag,
                ]
            ]
        ];
    }

    // test
    public function test(Request $request)
    {
        return ApiJsonResponse::success('sub dir controller test success!');
    }

    // hello
    public function hello(Request $request)
    {
        return ApiJsonResponse::success([
            "req"=>$request->all(),
            "try-real-ip"=>$request->header('X-Real-IP') ?? $request->ip() ?? 'unknown',
            "message"=>'Hello World!',
            "from"=>"sub dir controller hello!"
        ],message:'Hello World!');
    }
}
