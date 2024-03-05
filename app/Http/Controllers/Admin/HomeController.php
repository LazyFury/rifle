<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Post;
use Common\Controller\CURD;

class HomeController extends CURD
{

    public function __construct(Menu $model)
    {
        parent::__construct($model);
    }
}