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
    ];

    // add to json 
    protected $appends = [
        'author',
        'author_name',
        'author_avatar',
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
            'name' => 'unknown',
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
}
