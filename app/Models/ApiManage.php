<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiManage extends BaseModel
{
    use HasFactory;

    protected $casts = [
        "columns" => "array",
        "add_form_fields" => "array",
        "search_form_fields" => "array"
    ];
    protected $fillable = [
        "title",
        "key",
        "api",
        "add_api",
        "update_api",
        "del_api",
        "columns",
        "add_form_fields",
        "search_form_fields",
        "desciption"
    ];

    protected $rules = [
        "title" => "required",
        "api" => "required",
        "add_api" => "required",
        "update_api" => "required",
        "del_api" => "required",
        "columns" => "required",
        "add_form_fields" => "required",
        "search_form_fields" => "required",
        "desctiption" => "",
        "key" => "",
    ];

    protected $messages = [
        "title.required" => "标题不能为空",
        "api.required" => "api不能为空",
        "add_api.required" => "add_api不能为空",
        "update_api.required" => "update_api不能为空",
        "del_api.required" => "del_api不能为空",
        "columns.required" => "columns不能为空",
        "add_form_fields.required" => "add_form_fields不能为空",
        "search_form_fields.required" => "search_form_fields不能为空",
    ];
}
