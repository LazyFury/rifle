<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\Rule;

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
        "type"
    ];

    protected $appends = [
        "meta",
        "children",
        "all_children_id"
    ];

    protected $rules = [
        "title" => "required",
        "key" => "required",
        "path" => "required",
        "icon" => "required",
        "component" => "required",
        "meta_id" => "",
        "desciption" => "",
        "parent_id" => "nullable|exists:menus,id|different:id",
        "type" => "nullable|in:menu,sub_menu,group"
    ];

    protected $messages = [
        "title.required" => "标题不能为空",
        "key.required" => "key不能为空",
        "path.required" => "path不能为空",
        "icon.required" => "icon不能为空",
        "component.required" => "component不能为空",
        "meta_id.required" => "meta_id不能为空",
        "parent_id.exists" => "父级菜单不存在",
        "parent_id.different" => "父级菜单不能是自己",
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

    public function rules(bool $isUpdate = false, array $data = [])
    {
        $rules = $this->rules;
        if ($isUpdate) {
            $rules["parent_id"] = [
                "nullable",
                "exists:menus,id",
                "different:id",
                Rule::notIn($this->get_all_children_ids())
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return $this->messages + [
            "parent_id.not_in" => "父级菜单不能是自己的子菜单"
        ];
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

    // all children id 
    public function get_all_children_ids()
    {
        $ids = [];
        $children = $this->children()->get();
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->get_all_children_ids());
        }
        return $ids;
    }

    // get all children ids attr 
    public function getAllChildrenIdAttribute()
    {
        return $this->get_all_children_ids();
    }

}
