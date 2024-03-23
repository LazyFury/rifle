<?php

namespace App\Http\Controllers\Admin;

use Common\Controller\CURD;
use Common\Utils\ApiJsonResponse;

class ApiManageController extends CURD
{
    // //
    // protected $rules = [
    //     "title" => "required",
    //     "api" => "required",
    //     "add_api" => "required",
    //     "update_api" => "required",
    //     "del_api" => "required",
    //     "columns" => "required",
    //     "add_form_fields" => "required",
    //     "search_form_fields" => "required",
    //     "desctiption" => "",
    //     "key" => ""
    // ];

    public function __construct(\App\Models\ApiManage $model)
    {
        parent::__construct($model);
    }

    public static function tag()
    {
        return 'Api 管理';
    }

    public static function routers()
    {
        return [
            'get_grouped_keys' => [
                'method' => 'get',
                'uri' => 'get_grouped_keys',
                'action' => 'get_grouped_keys',
            ],
        ] + parent::routers();
    }

    public function get_grouped_keys()
    {
        $keys = $this->model->groupBy('group_key')->pluck('group_key')->filter(
            function ($group_key) {
                return ! empty($group_key);
            }
        )->map(function ($group_key) {
            return [
                'label' => $group_key,
                'value' => $group_key,
            ];
        })->values();

        $keys = array_merge([[
            'label' => '未分组',
            'value' => '',
        ]], $keys->toArray());

        return ApiJsonResponse::success($keys);
    }
}
