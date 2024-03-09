<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostCategory;
use Common\Controller\CURD;
use Common\Utils\ApiJsonResponse;
use Illuminate\Http\Request;

class PostCategoryController extends CURD
{

    public function __construct(PostCategory $model)
    {
        parent::__construct($model);
    }

    public function filter(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query->with('parent')->where('parent_id', null);
        return $query;
    }

    public function update(Request $request)
    {
        $category_id = request()->input("parent_id");
        $id = request()->input("id");
        $currend = PostCategory::find($id);
        if ($currend->children()->count() > 0) {
            if ($category_id) {
                $category = PostCategory::find($category_id);
                if ($category->parent_id != null) {
                    return ApiJsonResponse::error("不能添加子分类到子分类中");
                }
            }
        }
        return parent::update($request);
    }

    // destory 
    public function destroy(Request $request, $destoryCheck = null)
    {
        return parent::destroy($request, destoryCheck: function ($model) {
            if ($model->children()->count() > 0) {
                return ApiJsonResponse::error("请先删除子分类");
            }
            if ($model->posts()->count() > 0) {
                return ApiJsonResponse::error("请先删除分类下的文章");
            }
        });
    }
}