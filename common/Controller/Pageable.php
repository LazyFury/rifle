<?php

namespace Common\Controller;
use Common\Utils\ApiJsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Grammar;

trait Pageable
{
    protected $defaultLimit = 10;
    protected $defaultPage = 1;


    /**
     * 基础查询 分页 排序
     * @param Builder $query
     * @return array
     */
    public function query(Builder $query)
    {
        $page = request()->input('page', $this->defaultPage);
        $page_int = (int)$page;
        $limit = request()->input('limit', $this->defaultLimit);
        $limit_int = (int)$limit;
        $offset = ($page_int - 1) * $limit_int;

        $no_page = request()->input('no_page', false);

        $query = $query->orderBy('id', 'desc')->orderBy('created_at', 'desc');
        // copy new
        $base_query = clone $query;

        if(!$no_page){
            $query->offset($offset)->limit($limit);
        }

        return [
            "query"=>$query,
            "base_query"=>$base_query,
            "page"=>$page_int,
            "limit"=>$limit_int,
            "offset"=>$offset,
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
    public function pageableResponse($query, $page, $limit, $offset)
    {
        $total = $query->count();
        $data = $query->get();
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
