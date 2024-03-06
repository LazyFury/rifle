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
        "list_api",
        "create_api",
        "update_api",
        "delete_api",
        "columns",
        "add_form_fields",
        "search_form_fields",
        "desciption",
    ];

    protected $rules = [
        "title" => "required",
        "list_api" => "required",
        "create_api" => "required",
        "update_api" => "required",
        "delete_api" => "required",
        "columns" => "required",
        "add_form_fields" => "required",
        "search_form_fields" => "required",
        "desctiption" => "",
        "key" => "",
    ];

    protected $messages = [
        "title.required" => "标题不能为空",
        "columns.required" => "columns不能为空",
        "add_form_fields.required" => "add_form_fields不能为空",
        "search_form_fields.required" => "search_form_fields不能为空",
        "list_api.required" => "list_api不能为空",
        "create_api.required" => "create_api不能为空",
        "update_api.required" => "update_api不能为空",
        "delete_api.required" => "delete_api不能为空",
    ];

    public function get_deleteable()
    {
        return $this->deleteable;
    }
}
