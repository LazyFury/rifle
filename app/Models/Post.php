<?php

namespace App\Models;

use Common\Model\BaseModel;
use Common\Model\UsePersonalTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends BaseModel
{
    use HasFactory,UsePersonalTrait;

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

    public function searchable()
    {
        $arr = parent::searchable();
        // merge
        $arr = array_merge($arr, [
            'title',
            'content',
        ]);
        // dump($arr);
        return $arr;
    }

    // author
    public function author()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }
}
