<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $appends = [
        'post_count',
        'children',
        'self_post_count'
    ];

    public function rules()
    {
        $this->rules['slug'] = 'required|unique:post_categories,slug,' . $this->id . '|regex:/^[a-zA-Z0-9-_]+$/';
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
        $count = 0;
        // loop three level children , plus count 
        foreach ($this->children as $child) {
            $count += $child->getSelfPostCountAttribute();
            foreach ($child->children as $child2) {
                $count += $child2->getSelfPostCountAttribute();
                foreach ($child2->children as $child3) {
                    $count += $child3->getSelfPostCountAttribute();
                }
            }
        }
        return $this->posts()->count() + $count;
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

    // childrenIds 
    public function getChildrenIdsAttribute()
    {
        $ids = [];
        for ($i = 0; $i < count($this->children); $i++) {
            $ids[] = $this->children[$i]->id;
            for ($j = 0; $j < count($this->children[$i]->children); $j++) {
                $ids[] = $this->children[$i]->children[$j]->id;
                for ($k = 0; $k < count($this->children[$i]->children[$j]->children); $k++) {
                    $ids[] = $this->children[$i]->children[$j]->children[$k]->id;
                }
            }
        }
        return $ids;
    }
}
