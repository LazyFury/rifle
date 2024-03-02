<?php

namespace App\Http\Controllers\Api\v1\Cms;

use App\Models\Post;
use Common\Controller\CURD;

class PostCategoryController extends CURD
{

    public function __construct(Post $model)
    {
        parent::__construct($model);
    }

    public static function tag(){
        return "内容管理/文章分类";
    }
}
