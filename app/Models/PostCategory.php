<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class PostCategory extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'order',
        'image',
        'status',
    ];

    protected $rules = [
        'name' => 'required',
        'slug' => 'required|unique:post_categories,slug|regex:/^[a-zA-Z0-9-_]+$/', // 只能包含字母、数字、破折号（ - ）以及下划线（ _ ）
        'description' => '',
        'parent_id' => '',
        'order' => '',
        'image' => '',
        'status' => '',
    ];

    protected $messages = [
        'name.required' => '名称不得为空',
        'slug.required' => 'Slug 不得为空',
        'slug.unique' => 'Slug 已存在',
        "slug.regex" => "Slug 只能包含字母、数字、破折号（ - ）以及下划线（ _ ）",
        "parent_id.exists" => "父级分类不存在",
        "parent_id.different" => "父级分类不能是自己",
        "parent_id.not_in" => "父级分类不能是自己的子分类",
    ];

    protected $appends = [
        'post_count',
        'children',
        'self_post_count'
    ];

    public function rules($isUpdate = false, $data = [])
    {
        $this->rules['slug'] = [
            'required',
            'regex:/^[a-zA-Z0-9-_]+$/',
            Rule::unique('post_categories', 'slug')->ignore($this->id),
        ];

        $this->rules['parent_id'] = [
            'nullable',
            'exists:post_categories,id',
            "different:id", // 父级分类不能是自己
            Rule::notIn($this->get_all_children_ids()),
        ];

        return $this->rules;
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(PostCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(PostCategory::class, 'parent_id');
    }

    // post count 
    public function getPostCountAttribute()
    {
        return $this->get_all_post_count();
    }

    // self post count 
    public function getSelfPostCountAttribute()
    {
        return $this->posts()->count();
    }

    // children 
    public function getChildrenAttribute()
    {
        return $this->children()->get();
    }


    // danger:get all children 
    public function get_all_children()
    {
        $children = $this->children()->get();
        $result = [];
        foreach ($children as $child) {
            $result[] = $child;
            $result = array_merge($result, $child->get_all_children());
        }
        return $result;
    }

    // get_childrenIds
    public function get_all_children_ids()
    {
        $arr = $this->get_all_children();
        $ids = [];
        foreach ($arr as $item) {
            $ids[] = $item->id;
        }
        return $ids;
    }

    // get all posts 
    public function get_all_post_count()
    {
        $all_children = $this->get_all_children();
        $post_count = 0;
        foreach ($all_children as $child) {
            $post_count += $child->posts()->count();
        }
        return $post_count + $this->posts()->count();
    }
}
