<?php

namespace Common\Controller;

use Common\Utils\ApiJsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Grammar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

trait Pageable
{
    protected $defaultLimit = 10;
    protected $defaultPage = 1;


    /**
     * 基础查询 分页 排序
     * @param Request $request
     * @return array
     */
    public function params(Request $request)
    {
        $page = request()->input('page', $this->defaultPage);
        $page_int = (int) $page;
        $limit = request()->input('limit', $this->defaultLimit);
        $limit_int = (int) $limit;
        $offset = ($page_int - 1) * $limit_int;
        $no_page = request()->input('no_page', false);
        return [
            "page" => $page_int,
            "limit" => $limit_int,
            "offset" => $offset,
            "no_page" => $no_page,
        ];
    }

    /**
     * 分页返回
     * @param Builder $query
     * @param $page
     * @param $limit
     * @param $offset
     * @return \Illuminate\Http\JsonResponse
     */
    public function pagination($query, Request $request)
    {
        $data = $this->params($request);
        $limit = $data["limit"];
        $offset = $data["offset"];
        $page = $data["page"];
        $no_page = $data['no_page'] ?? false;

        $total = Cache::remember(
            'total_' . $query->toSql(),
            new \DateInterval("PT5M"),
            function () use ($query) {
                return (clone $query)->count();
            }
        );

        if (!$no_page) {
            $query->offset($offset)->limit($limit);
        } else {
            //do nothing
        }

        // $data = $query->get();
        $data = $query->get();

        // dump($query->toSql());
        return ApiJsonResponse::success([
            'pageable' => [
                'page' => $page,
                'limit' => $limit,
                'offset' => $offset,
                'total' => $total,
                'total_page' => ceil($total / $limit)
            ],
            'data' => $data,
        ]);
    }
}
