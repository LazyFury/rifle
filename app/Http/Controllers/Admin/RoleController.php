<?php

namespace App\Http\Controllers\Admin;

class RoleController extends \Common\Controller\CURD
{
    public function __construct(\App\Models\Role $model)
    {
        parent::__construct($model);
    }


    public function destroy(\Illuminate\Http\Request $request, $destoryCheck = null)
    {
        return response()->json([
            "message" => "禁止删除"
        ], 400);
    }
}