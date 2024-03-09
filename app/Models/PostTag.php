<?php

namespace App\Models;

use Common\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTag extends BaseModel
{
    use HasFactory;


    protected $rules = [
        'name' => 'required|string',
        "slug" => "required|string",
    ];

    protected $fillable = [
        'name',
        'slug',
    ];

    protected $messages = [
        'name.required' => 'name不能为空',
        'name.string' => 'name必须是字符串',
        'slug.required' => 'slug不能为空',
        'slug.string' => 'slug必须是字符串',
    ];

    protected $appends = [
        "post_count"
    ];

    public function posts()
    {
        return Post::where("tags_ids", "like", "%{$this->id}%");
    }

    // post count 
    public function getPostCountAttribute()
    {
        return $this->posts()->count();
    }
}
