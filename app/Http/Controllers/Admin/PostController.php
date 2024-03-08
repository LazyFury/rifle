<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\PostCategory;
use App\Service\PostService;
use Common\Controller\CURD;
use Common\Utils\ApiJsonResponse;

class PostController extends CURD
{

    protected $auth_except = [
        "export"
    ];
    protected $is_superuser = true;

    public function __construct(Post $model, PostService $service)
    {
        parent::__construct($model);
        $this->service = $service;
        $this->service->setIsSuperUser($this->is_superuser);
    }

    // static tag
    public static function tag()
    {
        return "内容管理/文章";
    }

    // routers
    public static function routers()
    {
        return [] + parent::routers();
    }


}
