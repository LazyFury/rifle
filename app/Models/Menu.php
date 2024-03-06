<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        "title",
        "key",
        "path",
        "icon",
        "component",
        "meta",
        "desciption"
    ];

    protected $rules = [
        "title" => "required",
        "key" => "required",
        "path" => "required",
        "icon" => "required",
        "component" => "required",
        "meta_id" => "",
        "desciption" => "",
    ];

    protected $messages = [
        "title.required" => "标题不能为空",
        "key.required" => "key不能为空",
        "path.required" => "path不能为空",
        "icon.required" => "icon不能为空",
        "component.required" => "component不能为空",
    ];

    public function get_deleteable()
    {
        return $this->deleteable;
    }
}
