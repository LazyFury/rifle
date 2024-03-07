<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Common\Utils\ApiJsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct()
    {
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

        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('name', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('authToken')->plainTextToken;

            return ApiJsonResponse::success([
                "user" => $user,
                "token" => $token
            ]);
        }

        return ApiJsonResponse::error("账号密码错误", 400);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout success'
        ]);
    }

    public function profile(Request $request)
    {
        $user = auth()->user();
        return ApiJsonResponse::success($user);
    }
}
