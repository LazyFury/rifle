<?php

namespace App\Http\Controllers\Admin;

use App\Models\RoleHasPermission;
use Common\Utils\ApiJsonResponse;
use Validator;

class RoleController extends \Common\Controller\CURD
{
    public function __construct(\App\Models\Role $model)
    {
        parent::__construct($model);
    }

    public static function tag()
    {
        return '权限管理/角色';
    }

    public static function routers()
    {
        $tag = static::tag();
        return [
            "get_permissions" => [
                "method" => "get",
                "uri" => "get_permissions",
                "action" => "get_permissions",
                "meta" => [
                    "tag" => $tag
                ]
            ],
            "set_permissions" => [
                "method" => "post",
                "uri" => "set_permissions",
                "action" => "set_permissions",
                "meta" => [
                    "tag" => $tag
                ]
            ]
        ] + parent::routers();
    }

    public function destroy(\Illuminate\Http\Request $request, $destoryCheck = null)
    {
        return response()->json([
            "message" => "禁止删除"
        ], 400);
    }

    public function get_permissions(\Illuminate\Http\Request $request)
    {
        $valid = Validator::make($request->all(), [
            'role_id' => 'required|integer'
        ]);
        if ($valid->fails()) {
            return ApiJsonResponse::error($valid->errors()->first());
        }
        $role_id = $request->role_id;
        $permissions = RoleHasPermission::where('role_id', $role_id)->get();
        return ApiJsonResponse::success($permissions);
    }

    // set permissions for role 
    public function set_permissions(\Illuminate\Http\Request $request)
    {
        $valid = Validator::make($request->all(), [
            'role_id' => 'required|integer',
            'permissions' => 'required|array'
        ]);
        if ($valid->fails()) {
            return ApiJsonResponse::error($valid->errors()->first());
        }
        $role_id = $request->role_id;
        $permissions = $request->permissions;
        // RoleHasPermission::where('role_id', $role_id)->delete();
        foreach ($permissions as $permission) {
            $permission_id = $permission['permission_id'];
            $enabled = (int) $permission['enabled'] ?? 0;
            $perm = RoleHasPermission::where('role_id', $role_id)->where('permission_id', $permission_id)->first();
            if ($perm) {
                if ($perm->isSameWith($role_id, $permission_id, $enabled)) {
                    continue;
                }
                $perm->where('role_id', $role_id)->where('permission_id', $permission_id)->update([
                    'enabled' => $enabled
                ]);
            } else {
                if ($enabled == 1) {
                    RoleHasPermission::create([
                        'role_id' => $role_id,
                        'permission_id' => $permission_id,
                        'enabled' => $enabled
                    ]);
                    continue;
                }

                //do nothing
            }

        }
        return ApiJsonResponse::success(null);
    }
}