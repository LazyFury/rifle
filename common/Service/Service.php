<?php

namespace Common\Service;

use Common\Repository\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Service
{
    protected Model $model;
    protected bool $use_default_query = true; //使用默认的查询

    protected $search_modes = [
        "__gt",
        "__lt",
        "__gte",
        "__lte",
        "__like",
        "__in",
        "__not_in",
        "__or",
        "__eq",
        "__fk",
        "__desc",
        "__asc"
    ];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // columns
    public function get_columns()
    {
        $cache_columns = Cache::remember(
            'columns_' . $this->model->getTable(),
            new \DateInterval("PT5M"),
            function () {
                return $this->model->getConnection()->getSchemaBuilder()->getColumns($this->model->getTable());
            }
        );
        return $cache_columns;
    }


    // fillable
    public function get_fillable()
    {
        $columns = $this->get_columns();
        $fillable = [];
        foreach ($columns as $column) {
            if (!in_array($column['name'], $this->model->fillable_exclude())) {
                $fillable[] = $column['name'];
            }
        }
        return $fillable;
    }

    // searchable
    public function get_searchable()
    {
        $columns = $this->get_columns();
        $searchable = [];
        foreach ($columns as $column) {
            $searchable[] = $column['name'];
        }
        return $searchable;
    }


    function addQuery(Builder $query, $key, $value, $type, $searchable)
    {
        // 为空不处理
        if ($value == null or $value == '') {
            return;
        }

        // eq 模式但是 key 没有后缀的情况
        if ($type == '__eq') {
            if (in_array($key, $searchable)) {
                $query->where($key, $value);
            }
        }
        if (strpos($key, $type)) {
            $key = str_replace($type, '', $key);

            if ($type == '__fk') {
                // sample key like : author__fk__name
                $key = str_replace($type, '', $key);
                $key = explode('__', $key);
                $fk = $key[0];
                $fk_key = $key[1];
                // dump("fk:",$fk,$fk_key,$key);
                $key = $fk;
            }

            if (in_array($key, $searchable)) {
                if ($type == '__gt') {
                    $query->where(str_replace($type, '', $key), '>', $value);
                }

                if ($type == '__lt') {
                    $query->where(str_replace($type, '', $key), '<', $value);
                }

                if ($type == '__gte') {
                    $query->where(str_replace($type, '', $key), '>=', $value);
                }
                if ($type == '__lte') {
                    $query->where(str_replace($type, '', $key), '<=', $value);
                }

                if ($type == '__like') {
                    $query->where(str_replace($type, '', $key), 'like', '%' . $value . '%');
                }

                if ($type == '__in') {
                    $value = explode(',', $value);
                    $query->whereIn(str_replace($type, '', $key), $value);
                }

                if ($type == '__not_in') {
                    $value = explode(',', $value);
                    $query->whereNotIn(str_replace($type, '', $key), $value);
                }

                if ($type == '__or') {
                    $query->orWhere(str_replace($type, '', $key), $value);
                }

                if ($type == '__eq') {
                    $query->where($key, $value);
                }

                // desc
                if ($type == '__desc' and ($value == "1")) {
                    $query->orderBy($key, 'desc');
                }


                // asc
                if ($type == '__asc' && ($value == 1)) {
                    $query->orderBy($key, 'asc');
                }

                if ($type == '__fk') {
                    // dump("fk:",$key);
                    $query->whereHas($fk, function ($query) use ($fk_key, $fk, $value) {
                        $model = $query->getModel();
                        // $table_name = $model->getTable();
                        $key = $fk_key;
                        // dump("fk:" . $key . "=" . $value);
                        // has methods searchable
                        if (method_exists($model, 'searchable') and is_callable([$model, 'searchable'])) {
                            $searchable = $model->searchable();
                            $this->addQuery($query, $key, $value, "__eq", $searchable);
                        }
                        // dump($query->toSql());
                    });
                }
            }
        }
    }

    public function defaultOrderBy(array $dict)
    {
        if (!isset($dict['id__desc']) and !isset($dict['id__asc'])) {
            $dict['id__desc'] = 1;
        }
        if (!isset($dict['created_at__desc']) and !isset($dict['created_at__asc'])) {
            $dict['created_at__desc'] = 1;
        }
        return $dict;
    }

    // base columns search
    public function scopeSearch(Builder $query, $search)
    {

        if (!$this->use_default_query)
            return $query;

        $search = $this->defaultOrderBy($search);

        $searchable = $this->get_searchable();

        if ($search && $this->use_default_query) {
            foreach ($search as $key => $value) {
                // $type is split with __ 
                $arr = preg_split("/__/", $key);

                // dump($arr);
                $len = count($arr);
                if ($len > 1) {
                    $type = "__" . $arr[1];
                    if (in_array($type, $this->search_modes)) {
                        $this->addQuery($query, $key, $value, $type, $searchable);
                    }
                } else {
                    $this->addQuery($query, $key, $value, "__eq", $searchable);
                }
            }
        }
        // dump($query->toSql());

        $query = $this->model->scopeUsePersonal($query, must_auth: false);

        return $query;
    }

    // get one query
    public function scopeGetOne($id, $column = 'id', $search = [], $must_auth = true): Builder
    {
        $query = $this->model->query();

        $query = $query->search([
            $column => $id,
        ])->where($search);

        $query = $this->model->scopeUsePersonal($query, must_auth: $must_auth);

        return $query;
    }

}
