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
}
