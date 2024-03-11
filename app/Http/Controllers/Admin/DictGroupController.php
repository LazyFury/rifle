<?php


namespace App\Http\Controllers\Admin;

use Common\Utils\ApiJsonResponse;
use Illuminate\Http\Request;
use Validator;

class DictGroupController extends \Common\Controller\CURD
{
    protected $auth_except = ["getDictGroupAsObj"];

    public function __construct(\App\Models\DictGroup $model)
    {
        parent::__construct($model);
    }

    public static function routers()
    {
        return [
            "getConfig" => [
                "method" => "get",
                "uri" => "getConfig",
                "action" => "getDictGroupAsObj"
            ]
        ] + parent::routers();
    }

    public function filter(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query;
    }

    // get dict group as obj 
    public function getDictGroupAsObj(Request $request)
    {
        $valid = Validator::make($request->all(), [
            "id" => "required|integer|exists:dict_groups,id"
        ]);
        if ($valid->fails()) {
            return response()->json($valid->errors(), 400);
        }
        $id = $request->input("id");
        $result = $this->model->find($id);
        $config = [];
        foreach ($result->dicts()->get() as $dict) {
            $config[$dict->key] = $dict->value;
        }
        return ApiJsonResponse::success([
            "config" => $config,
            "group" => $result
        ]);
    }
}