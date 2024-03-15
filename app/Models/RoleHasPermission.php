<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    use HasFactory;


    protected $table = 'role_has_permissions';

    protected $fillable = [
        'role_id',
        'permission_id',
        'enabled'
    ];

    protected $rules = [
        'role_id' => 'required|integer',
        'permission_id' => 'required|integer',
        "enabled" => "required|boolean"
    ];

    protected $messages = [
        'role_id.required' => '角色ID不能为空',
        'role_id.integer' => '角色ID必须是整数',
        'permission_id.required' => '权限ID不能为空',
        'permission_id.integer' => '权限ID必须是整数',
        'enabled.required' => '启用状态不能为空',
        'enabled.boolean' => '启用状态必须是布尔值'
    ];

    // 更新是排除 created_at 和 updated_at 字段
    public $timestamps = false;


    //  组合 primary key
    protected $primaryKey = "permission_id";

    public function isSameWith($role_id, $permission_id, $enabled)
    {
        return $this->role_id == $role_id && $this->permission_id == $permission_id && $this->enabled == $enabled;
    }

    // permission 
    public function permission()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Permission::class, 'permission_id', 'id');
    }

}
