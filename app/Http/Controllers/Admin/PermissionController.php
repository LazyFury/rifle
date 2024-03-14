<?php


namespace App\Http\Controllers\Admin;

use Common\Controller\CURD;

class PermissionController extends CURD
{
    public function __construct(\App\Models\Permission $model)
    {
        parent::__construct($model);
    }

    public static function tag()
    {
        return '权限管理/权限';
    }

    public function destroy(\Illuminate\Http\Request $request, $destoryCheck = null)
    {
        return response()->json([
            "message" => "禁止删除"
        ], 400);
    }
}