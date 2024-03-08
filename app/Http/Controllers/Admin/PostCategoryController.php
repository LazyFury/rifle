<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostCategory;
use Common\Controller\CURD;

class PostCategoryController extends CURD
{

    public function __construct(PostCategory $model)
    {
        parent::__construct($model);
    }
}