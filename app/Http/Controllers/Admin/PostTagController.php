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

    public static function tag()
    {
        return '内容管理/标签';
    }

    public function destroy(\Illuminate\Http\Request $request, $destoryCheck = null)
    {
        return parent::destroy($request, destoryCheck: function ($model) {
            if ($model->posts()->count() > 0) {
                return \Common\Utils\ApiJsonResponse::error("请先删除标签下的文章");
            }
        });
    }
}