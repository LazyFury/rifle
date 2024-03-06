<?php

namespace Common\Controller;

use Common\Utils\ApiJsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Grammar;
use Illuminate\Http\Request;

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


        $validator = \Validator::make($data, [
            'page' => 'required|integer|min:1',
            'limit' => 'required|integer|min:1',
            'offset' => 'required|integer|min:0',
            'no_page' => 'boolean',
        ]);

        if ($validator->fails()) {
            return ApiJsonResponse::error($validator->errors()->first());
        }

        $valid = $validator->validated();

        $limit = $valid["limit"];
        $offset = $valid["offset"];
        $page = $valid["page"];
        $no_page = $valid['no_page'] ?? false;

        $total = \Common\Utils\Cache::rememberSql(
            $query,
            [
                'page' => $page,
                'limit' => $limit,
                'offset' => $offset,
                'no_page' => $no_page,
            ],
            new \DateInterval("PT5M"),
            "count_",
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
            "sql" => $query->toSql(),
            'pageable' => [
                'page' => $page,
                'size' => $limit,
                'offset' => $offset,
                'total' => (int) ($total),
                'total_page' => ceil($total / $limit)
            ],
            'data' => $data,
        ]);
    }
}
