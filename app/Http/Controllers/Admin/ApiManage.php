<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Common\Controller\CURD;
use Illuminate\Http\Request;

class ApiManage extends CURD
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

}
