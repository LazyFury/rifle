<?php
/*
 * @Author: robot suke971219@gmail.com
 * @Date: 2024-02-28 13:38:56
 * @LastEditors: robot suke971219@gmail.com
 * @LastEditTime: 2024-03-03 21:39:23
 * @FilePath: /rifle/app/Http/Controllers/Api/v1/UserController.php
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Common\Controller\CURD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends CURD
{

    protected $auth_except = [
        'test',
        'sse',
        'index'
    ];
    // constructor
    public function __construct(UserModel $model)
    {
        parent::__construct($model);
    }

    public static function route_alias()
    {
        return 'user';
    }

    // static tag
    public static function tag()
    {
        return '用户管理';
    }

    // routers
    public static function routers()
    {
        return [
            'test' => [
                'method' => 'get',
                'uri' => 'test',
                'action' => 'test',
                'meta' => [
                    'name' => 'test',
                    'desc' => 'test',
                    'tag' => self::tag()
                ]
            ],
            'sse' => [
                'method' => 'get',
                'uri' => 'sse',
                'action' => 'sse',
                'meta' => [
                    'name' => 'sse',
                    'desc' => 'sse',
                    'tag' => self::tag()
                ]
            ]
        ] + parent::routers();
    }

    // test
    public function test(Request $request)
    {
        return response()->json([
            'message' => 'Hello, World!1'
        ]);
    }

    // sse
    public function sse(Request $request)
    {
        $response = Response::stream(function () {
            while (true) {
                echo 'data: ' . json_encode([
                    'message' => 'Hello, World!2',
                    'time' => date('Y-m-d H:i:s')
                ]) . "\n\n";
                ob_flush();
                flush();
                if (connection_aborted()) {
                    break;
                }
                sleep(1);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
        return $response;
    }
}
