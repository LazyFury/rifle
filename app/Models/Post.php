<?php

namespace App\Models;

use Common\Model\BaseModel;
use Common\Model\UsePersonalTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Post extends BaseModel
{
    use HasFactory, UsePersonalTrait;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->user_id_column = 'author_id';
        $this->must_auth = false;
    }

    protected $fillable = [
        'author_id',
        'title',
        'content',
        "category_id"
    ];

    // add to json 
    protected $appends = [
        'author',
        'author_name',
        'author_avatar',
        'content_cut',
        'category_name'
    ];

    protected $rules = [
        'author_id' => '',
        'title' => 'required|string',
        'content' => 'required|string',
        'category_id' => 'integer|nullable'
    ];

    protected $casts = [
        'author_id' => 'integer',
        'title' => 'string',
        'content' => 'string',
        'author' => 'array',
    ];

    protected $messages = [
        'author_id.required' => 'author_id不能为空',
        'author_id.integer' => 'author_id必须是整数',
        'title.required' => 'title不能为空',
        'title.string' => 'title必须是字符串',
        'content.required' => 'content不能为空',
        'content.string' => 'content必须是字符串',
        'category_id.integer' => 'category_id必须是整数',
    ];


    public function get_searchable()
    {
        return [
            'author'
        ];
    }

    public function get_searchable_exclude()
    {
        return [];
    }

    // author
    public function author()
    {
        return $this->belongsTo(UserModel::class, 'author_id', 'id');
    }

    public function getAuthorAttribute()
    {
        return $this->author()->first() ?: [
            'name' => '-',
            'avatar' => ''
        ];
    }

    // author_name 
    public function getAuthorNameAttribute()
    {
        return $this->getAuthorAttribute()['name'];
    }

    // author_avatar 
    public function getAuthorAvatarAttribute()
    {
        return $this->getAuthorAttribute()['avatar'] ?? '';
    }

    // content cut 16
    public function getContentCutAttribute()
    {
        return mb_substr($this->content, 0, 16) . '...';
    }

    // category_name
    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id', 'id');
    }

    public function getCategoryAttribute()
    {
        return $this->category()->first() ?: [
            'name' => '-'
        ];
    }

    public function getCategoryNameAttribute()
    {
        return $this->getCategoryAttribute()['name'];
    }

}
