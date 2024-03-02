<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Post;
use Common\Controller\CURD;
use Common\Utils\ApiJsonResponse;

class PostController extends CURD
{
    protected $rules = [
        'title' => 'required',
        'content' => 'required',
    ];

    protected $auth_except = [
        'index',
        'show',
        'create',
    ];

    public function __construct(Post $model)
    {
        parent::__construct($model);
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
            "fillable"=>[
                'method' => 'get',
                'uri' => 'fillable',
                'action' => 'fillable',
                'meta'=>[
                    'tag'=> $tag,
                ]
            ],
            'columns'=>[
                'method' => 'get',
                'uri' => 'columns',
                'action' => 'columns',
                'meta'=>[
                    'tag'=> $tag,
                ]
            ],
            'searchable'=>[
                'method' => 'get',
                'uri' => 'searchable',
                'action' => 'searchable',
                'meta'=>[
                    'tag'=> $tag,
                ]
            ],
        ] + parent::routers();
    }

    // fillable
    public function fillable()
    {
        return ApiJsonResponse::success($this->model->get_fillable());
    }

    // columns
    public function columns()
    {
        return ApiJsonResponse::success([
            'columns'=>$this->model->columns(),
        ]);
    }

    // searchable
    public function searchable()
    {
        return ApiJsonResponse::success([
            'searchable'=>$this->model->searchable(),
        ]);
    }
}
