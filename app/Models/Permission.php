<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Permission extends BaseModel
{
    use HasFactory;

    protected $table = "permissions";

    protected $fillable = ['name', 'guard_name', 'parent_id'];

    protected $rules = [
        'name' => 'required',
        'guard_name' => 'required',
        "parent_id" => "nullable|exists:permissions,id|different:id"
    ];

    protected $messages = [
        'name.required' => '权限名称不能为空',
        'guard_name.required' => '守卫名称不能为空',
        "parent_id.exists" => "父级权限不存在",
        "parent_id.different" => "父级权限不能是自己",
        "parent_id.not_in" => "父级权限不能是自己的子权限",
    ];

    protected $appends = ['children', 'children_count'];

    public function rules(bool $isUpdate = false, array $data = []): array
    {
        if ($isUpdate) {
            $this->rules['parent_id'] = [
                "nullable",
                "exists:permissions,id",
                "different:id",
                Rule::notIn($this->get_all_children_id())
            ];
        }
        return $this->rules;
    }

    public function children()
    {
        return $this->hasMany(Permission::class, 'parent_id', 'id');
    }

    public function getChildrenAttribute()
    {
        return $this->children()->get();
    }

    // children count attr 
    public function getChildrenCountAttribute()
    {
        return $this->children()->count();
    }

    // get_all_children 
    public function get_all_children()
    {
        $children = $this->children;
        $all_children = [];
        foreach ($children as $child) {
            $all_children[] = $child;
            $all_children = array_merge($all_children, $child->get_all_children());
        }
        return $all_children;
    }

    public function get_all_children_id()
    {
        $all_children = $this->get_all_children();
        $all_children_id = [];
        foreach ($all_children as $child) {
            $all_children_id[] = $child->id;
        }
        return $all_children_id;
    }
}
