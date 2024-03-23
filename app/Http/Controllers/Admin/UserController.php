<?php

namespace App\Http\Controllers\Admin;

use App\Service\UserService;
use Common\Controller\CURD;
use Common\Utils\ApiJsonResponse;

class UserController extends CURD
{
    public function __construct(\App\Models\UserModel $model, UserService $service)
    {
        parent::__construct($model);
        $this->service = $service;
    }

    public static function tag()
    {
        return '用户管理';
    }

    // 不应该使用CURD中的store方法，创建用户需要特定的逻辑
    public function store(\Illuminate\Http\Request $request)
    {
        return ApiJsonResponse::error('禁止操作');
    }
}
