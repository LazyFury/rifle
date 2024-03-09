<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostTag;
use Common\Controller\CURD;

class PostTagController extends CURD
{
    public function __construct(PostTag $model)
    {
        parent::__construct($model);
    }
}