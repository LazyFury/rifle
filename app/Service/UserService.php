<?php

namespace App\Service;

use App\Models\UserModel;
use Common\Service\Service;

/**
 * User Service
 * 负责后台用户的CURD操作，不敏感信息查询，不可修改密码邮箱
 */
class UserService extends Service
{
    public function __construct(UserModel $model)
    {
        parent::__construct($model);
    }


    public function export_serialize()
    {
        $arr = parent::export_serialize();
        return $arr;
    }
}