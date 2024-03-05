<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Common\Utils\ApiJsonResponse;
use Illuminate\Http\Request;

class Auth extends Controller
{

    // static routes 
    public static function routers()
    {
        return [
            "login" => [
                "method" => "post",
                "uri" => "login",
                "action" => "login"
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
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout success'
        ]);
    }
}
