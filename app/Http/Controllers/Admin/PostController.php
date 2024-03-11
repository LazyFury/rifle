<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\PostCategory;
use App\Service\PostService;
use Common\Controller\CURD;
use Common\Utils\ApiJsonResponse;
use Common\Utils\Cache;

class PostController extends CURD
{

    protected $auth_except = [
        "index",
        "archive",
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
        return [
            "archive" => [
                "method" => "get",
                "uri" => "archive",
                "action" => "archive",
            ]
        ] + parent::routers();
    }

    public function filter(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query = parent::filter($query);
        $category_id = request()->input("category_id");
        if ($category_id) {
            $category = PostCategory::find($category_id);
            $query->orWhereIn("category_id", $category->get_all_children_ids());
        }
        return $query;
    }


    // archive post month year group and count
    public function get_archive()
    {
        $year = $this->model->selectRaw("DATE_FORMAT(created_at, '%Y') as year, count(*) as count")
            ->groupBy("year")
            ->orderBy("year", "desc")->get("year");

        for ($i = 0; $i < count($year); $i++) {
            $year[$i]->month = $this->model->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, count(*) as count")
                ->whereRaw("DATE_FORMAT(created_at, '%Y') = ?", [$year[$i]->year])
                ->groupBy("month")
                ->orderBy("month", "desc")->get("month");

            // articles 
            for ($j = 0; $j < count($year[$i]->month); $j++) {
                $year[$i]->month[$j]->articles = $this->model->select()
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$year[$i]->month[$j]->month])
                    ->orderBy("created_at", "desc")->get();
            }
        }

        $result = $year->map(function ($item) {
            return [
                "year" => $item->year,
                "month" => $item->month->map(function ($item) {
                    return [
                        "month" => $item->month,
                        "count" => $item->count,
                        "articles" => $item->articles->map(function ($item) {
                            return $item;
                        }),
                    ];
                }),
            ];
        });

        return ApiJsonResponse::success($result);
    }

    public function archive()
    {
        // \Illuminate\Support\Facades\Cache::forget("post_archive");
        return \Illuminate\Support\Facades\Cache::remember("post_archive", 60, function () {
            return $this->get_archive();
        });
    }
}
