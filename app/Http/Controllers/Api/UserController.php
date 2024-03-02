<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Common\Controller\CURD;
use Illuminate\Http\Request;
use Response;

class UserController extends CURD
{

    protected $auth_except = [
        'test',
        'sse',
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
    // routers
    public static function routers()
    {
        return [
            'test' => [
                'method' => 'get',
                'uri' => 'test',
                'action' => 'test'
            ],
            'sse' => [
                'method' => 'get',
                'uri' => 'sse',
                'action' => 'sse'
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
