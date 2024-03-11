<?php


namespace App\Http\Controllers\Admin;

use Common\Utils\ApiJsonResponse;
use Illuminate\Http\Request;
use Validator;

class DictGroupController extends \Common\Controller\CURD
{
    protected $auth_except = ["getConfig"];

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
                "action" => "getConfig"
            ],
            "setConfig" => [
                "method" => "post",
                "uri" => "setConfig",
                "action" => "setConfig"
            ]
        ] + parent::routers();
    }

    public function filter(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query;
    }

    // get dict group as obj 
    public function getConfig(Request $request)
    {
        $valid = Validator::make($request->all(), [
            "key" => "required|string"
        ]);
        if ($valid->fails()) {
            return response()->json($valid->errors(), 400);
        }
        $key = $request->input("key");
        $result = $this->model->where("key", $key)->first();
        $config = [];
        foreach ($result->dicts()->get() as $dict) {
            $config[$dict->key] = $dict->value;
        }
        return ApiJsonResponse::success([
            "config" => $config,
            "group" => $result
        ]);
    }

    public function setConfig(Request $request)
    {
        $valid = Validator::make($request->all(), [
            "key" => "required|string",
            "config" => "required|array"
        ]);
        if ($valid->fails()) {
            return response()->json($valid->errors(), 400);
        }
        $key = $request->input("key");
        $config = $request->input("config");
        $result = $this->model->where("key", $key)->first();
        foreach ($config as $key => $value) {
            $dict = $result->dicts()->where("key", $key)->first();
            if ($dict) {
                $dict->value = $value;
                $dict->save();
            } else {
                // to nothing 
            }
        }
        return ApiJsonResponse::success(null);
    }
}