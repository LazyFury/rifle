<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
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
        $tag = self::tag();
        return [
            "fillable" => [
                'method' => 'get',
                'uri' => 'fillable',
                'action' => 'fillable',
                'meta' => [
                    'tag' => $tag,
                ]
            ],
            'columns' => [
                'method' => 'get',
                'uri' => 'columns',
                'action' => 'columns',
                'meta' => [
                    'tag' => $tag,
                ]
            ],
            'searchable' => [
                'method' => 'get',
                'uri' => 'searchable',
                'action' => 'searchable',
                'meta' => [
                    'tag' => $tag,
                    "name" => "可搜索字段",
                ]
            ],
        ] + parent::routers();
    }

    // fillable
    public function fillable()
    {
        return ApiJsonResponse::success($this->service->get_fillable());
    }

    // columns
    public function columns()
    {
        return ApiJsonResponse::success([
            'columns' => $this->service->get_columns(),
        ]);
    }

    // searchable
    public function searchable()
    {
        return ApiJsonResponse::success([
            'searchable' => $this->model->searchable(),
        ]);
    }
}
