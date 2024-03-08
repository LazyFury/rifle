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
    // static routes 
    public static function routers()
    {
        return [
            "login" => [
                "method" => "post",
                "uri" => "login",
                "action" => "login"
            ],
            "logout" => [
                "method" => "post",
                "uri" => "logout",
                "action" => "logout"
            ],
            "profile" => [
                "method" => "get",
                "uri" => "profile",
                "action" => "profile"
            ]
        ];
    }

    public function login(Request $request)
    {
        $result = $this->service->login($request);
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
