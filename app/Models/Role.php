<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends BaseModel
{
    use HasFactory;

    protected $table = "roles";

    protected $fillable = ['name', 'guard_name', 'remark'];

    protected $rules = [
        'name' => 'required',
        'guard_name' => 'required',
        "remark" => "nullable"
    ];

    protected $messages = [
        'name.required' => '角色名称不能为空',
        'guard_name.required' => '守卫名称不能为空',
    ];
}
