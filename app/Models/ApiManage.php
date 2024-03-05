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
}
