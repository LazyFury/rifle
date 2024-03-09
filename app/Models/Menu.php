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
        "meta_id",
        "desciption",
        "parent_id",
    ];

    protected $appends = [
        "meta",
        "children",
    ];

    protected $rules = [
        "title" => "required",
        "key" => "required",
        "path" => "required",
        "icon" => "required",
        "component" => "required",
        "meta_id" => "",
        "desciption" => "",
        "parent_id" => "nullable|exists:menus,id",
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

    // get meta 
    public function meta()
    {
        return $this->belongsTo(ApiManage::class, "meta_id", "id");
    }

    public function getMetaAttribute()
    {
        return $this->meta()->first();
    }

    // children 
    public function children()
    {
        return $this->hasMany(Menu::class, "parent_id", "id");
    }

    public function getChildrenAttribute()
    {
        return $this->children()->get();
    }
}
