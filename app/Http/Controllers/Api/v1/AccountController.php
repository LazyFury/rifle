<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Common\Utils\ApiJsonResponse;
use Common\Utils\OpenApiParam;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // static tag
    public static function tag()
    {
        return '账户登录注册';
    }

    // routers
    public static function routers()
    {
        $tag = self::tag();

        return [
            'login' => [
                'method' => 'post',
                'uri' => 'login',
                'action' => 'login',
                'meta' => [
                    'name' => 'login',
                    'desc' => 'login',
                    'tag' => $tag,
                    'params' => [
                        new OpenApiParam(
                            'body',
                            'body',
                            '',
                            required: true,
                            schema: [
                                '$ref' => '#/definitions/login',
                            ],
                            example: 'admin'
                        ),
                    ],
                    'schemas' => [
                        'login' => [
                            'type' => 'object',
                            'properties' => [
                                'username' => [
                                    'type' => 'string',
                                    'description' => '用户名或者邮箱',
                                    'example' => 'admin',
                                ],
                                'password' => [
                                    'type' => 'string',
                                    'description' => '密码',
                                    'example' => '123456',
                                ],
                            ],
                        ],
                    ],
                ],

            ],
            'logout' => [
                'method' => 'post',
                'uri' => 'logout',
                'action' => 'logout',
                'meta' => [
                    'name' => 'logout',
                    'desc' => 'logout',
                    'tag' => $tag,
                ],
            ],
            'register' => [
                'method' => 'post',
                'uri' => 'register',
                'action' => 'register',
                'meta' => [
                    'tag' => $tag,
                ],
            ],
        ];
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        if (empty($username) || empty($password)) {
            return ApiJsonResponse::error('username or password is empty!');
        }

        $pass = auth()->attempt(['name' => $username, 'password' => $password]);
        if (!$pass) {
            return ApiJsonResponse::error('username or password is wrong!');
        }

        $user = auth()->user();
        $token = $user->createToken('token-name')->plainTextToken;

        return ApiJsonResponse::success([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
