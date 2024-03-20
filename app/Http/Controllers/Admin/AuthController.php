<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\AuthService;
use Common\Utils\ApiJsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected AuthService $service;
    public function __construct(AuthService $service)
    {
        $this->service = $service;
        $this->middleware('auth:sanctum')->except('login');
    }

    // static tag 
    public static function tag()
    {
        return 'Auth';
    }

    // static routes 
    public static function routers()
    {
        $tag = self::tag();
        return [
            "login" => [
                "method" => "post",
                "uri" => "login",
                "action" => "login",
                "meta" => [
                    "tag" => $tag,
                ]
            ],
            "logout" => [
                "method" => "post",
                "uri" => "logout",
                "action" => "logout",
                "meta" => [
                    "tag" => $tag,
                ]
            ],
            "profile" => [
                "method" => "get",
                "uri" => "profile",
                "action" => "profile",
                "meta" => [
                    "tag" => $tag,
                ]
            ]
        ];
    }

    public function login(Request $request)
    {
        $result = $this->service->adminLogin($request);
        if (is_string($result)) {
            return ApiJsonResponse::error($result);
        }
        return ApiJsonResponse::success($result);
    }

    public function logout(Request $request)
    {
        $this->service->logout();
        return ApiJsonResponse::success(null);
    }

    public function profile(Request $request)
    {
        return ApiJsonResponse::success($this->service->profile());
    }

    public function register(Request $request)
    {
        $result = $this->service->register($request);
        if (is_string($result)) {
            return ApiJsonResponse::error($result);
        }
        return ApiJsonResponse::success($result);
    }
}
