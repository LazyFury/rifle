<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\UserModel;
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
                    'tag' => $tag,
                ],
            ],
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

        $user_this_week_count = UserModel::where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('this week')))->count();
        $user_last_week_count = UserModel::where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('last week')))->count();
        $user_compare_week = $user_this_week_count - $user_last_week_count;

        $user_this_month_count = UserModel::where('created_at', '>=', date('Y-m-01 00:00:00'))->count();
        $user_last_month_count = UserModel::where('created_at', '>=', date('Y-m-01 00:00:00', strtotime('last month')))->count();
        $user_compare_month = $user_this_month_count - $user_last_month_count;

        $user_three_month_ago_count = UserModel::where('created_at', '>=', date('Y-m-01 00:00:00', strtotime('-3 month')))->count();
        $user_last_three_month_count = UserModel::where('created_at', '>=', date('Y-m-01 00:00:00', strtotime('-6 month')))->count();
        $user_compare_three_month = $user_three_month_ago_count - $user_last_three_month_count;

        $user_this_year_count = UserModel::where('created_at', '>=', date('Y-01-01 00:00:00'))->count();
        $user_last_year_count = UserModel::where('created_at', '>=', date('Y-01-01 00:00:00', strtotime('last year')))->count();
        $user_compare_year = $user_this_year_count - $user_last_year_count;

        return ApiJsonResponse::success([
            'user_all_count' => $user_all_count,
            'user_today_count' => $user_today_count,
            'post_all_count' => $post_all_count,
            'post_today_count' => $post_today_count,
            'user_chart_by_day' => $user_chart_by_day,
            'user_chart_by_week' => $user_chart_by_week,
            'post_chart_by_day' => $post_chart_by_day,
            'user_this_week_count' => $user_this_week_count,
            'user_last_week_count' => $user_last_week_count,
            'user_compare_week' => $user_compare_week,
            'user_compare_week_text' => $user_compare_week > 0 ? '增加' : '减少',
            'user_this_month_count' => $user_this_month_count,
            'user_last_month_count' => $user_last_month_count,
            'user_compare_month' => $user_compare_month,
            'user_compare_month_text' => $user_compare_month > 0 ? '增加' : '减少',
            'user_this_year_count' => $user_this_year_count,
            'user_last_year_count' => $user_last_year_count,
            'user_compare_year' => $user_compare_year,
            'user_compare_year_text' => $user_compare_year > 0 ? '增加' : '减少',
            'user_three_month_ago_count' => $user_three_month_ago_count,
            'user_last_three_month_count' => $user_last_three_month_count,
            'user_compare_three_month' => $user_compare_three_month,
            'user_compare_three_month_text' => $user_compare_three_month > 0 ? '增加' : '减少',
        ]);
    }
}
