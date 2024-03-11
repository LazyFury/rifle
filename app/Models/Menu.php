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
    public function allChildrenId()
    {
        $ids = [];
        $children = $this->children()->get();
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->allChildrenId());
        }
        return $ids;
    }

    // get all children ids attr 
    public function getAllChildrenIdAttribute()
    {
        return $this->allChildrenId();
    }

    // perent_id is avaliable
    public function isParentIdAvaliable($parent_id)
    {
        if ($parent_id == null) {
            return true;
        }
        $ids = $this->allChildrenId();
        return !in_array($parent_id, $ids);
    }
}
