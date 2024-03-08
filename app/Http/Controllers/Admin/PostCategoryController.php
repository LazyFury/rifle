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

    public function filter(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query->with('parent')->where('parent_id', 0);
        return $query;
    }
}