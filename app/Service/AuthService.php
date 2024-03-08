<?php

namespace App\Service;

use App\Models\User;
use Common\Service\Service;

/**
 * User Service
 * 负责用户的登录、注册、退出、个人信息等操作
 */
class AuthService
{

    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function login($request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('authToken')->plainTextToken;

            return [
                "user" => $user,
                "token" => $token
            ];
        }

        return "账号密码错误";
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return "退出成功";
    }

    public function profile()
    {
        return auth()->user();
    }

    // register 
    public function register($request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = $this->model->create($request->all());

        return $user;
    }
}