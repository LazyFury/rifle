<?php

namespace App\Http\Controllers\Admin;

use Common\Controller\CURD;

class UserController extends CURD
{

    public function __construct(\App\Models\UserModel $model)
    {
        parent::__construct($model);
    }
}