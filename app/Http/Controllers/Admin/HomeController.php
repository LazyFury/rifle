<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Post;
use App\Models\UserModel;
use Common\Controller\CURD;
use Common\Utils\ApiJsonResponse;

/**
 * Class HomeController
 */
class HomeController extends Controller
{
    public static function routers()
    {
        $tag = self::tag();
        return [
            'index' => [
                'method' => 'get',
                'uri' => 'index',
                'action' => 'index',
                'meta' => [
                    'tag' => $tag
                ]
            ]
        ];
    }

    public static function tag()
    {
        return '首页';
    }

    public function index()
    {
        $user_all_count = UserModel::count();
        $user_today_count = UserModel::where('created_at', '>=', date('Y-m-d 00:00:00'))->count();
        $post_all_count = Post::count();
        $post_today_count = Post::where('created_at', '>=', date('Y-m-d 00:00:00'))->count();
        $user_chart_by_day = UserModel::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        $user_chart_by_week = UserModel::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d 今年第%u周") as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        $post_chart_by_day = Post::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();
        return ApiJsonResponse::success([
            'user_all_count' => $user_all_count,
            'user_today_count' => $user_today_count,
            'post_all_count' => $post_all_count,
            'post_today_count' => $post_today_count,
            'user_chart_by_day' => $user_chart_by_day,
            'user_chart_by_week' => $user_chart_by_week,
            'post_chart_by_day' => $post_chart_by_day,
        ]);
    }
}