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
        'slug' => 'required|unique:post_categories',
        'description' => '',
        'parent_id' => '',
        'order' => '',
        'image' => '',
        'status' => '',
    ];

    protected $messages = [
        'name.required' => 'Tên danh mục không được để trống',
        'slug.required' => 'Slug không được để trống',
        'slug.unique' => 'Slug đã tồn tại',
    ];

    protected $appends = [
        'post_count',
        'children',
    ];

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
        return $this->posts()->count();
    }

    // children 
    public function getChildrenAttribute()
    {
        return $this->children()->get();
    }
}
