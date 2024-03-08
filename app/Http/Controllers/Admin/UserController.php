<?php

namespace App\Http\Controllers\Admin;

use App\Service\UserService;
use Common\Controller\CURD;

class UserController extends CURD
{
    public function __construct(\App\Models\UserModel $model, UserService $service)
    {
        parent::__construct($model);
        $this->service = $service;
    }
}