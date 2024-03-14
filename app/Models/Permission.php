<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends BaseModel
{
    use HasFactory;

    protected $table = "permissions";

    protected $fillable = ['name', 'guard_name'];

    protected $rules = [
        'name' => 'required',
        'guard_name' => 'required',
    ];

    protected $messages = [
        'name.required' => '权限名称不能为空',
        'guard_name.required' => '守卫名称不能为空',
    ];
}
