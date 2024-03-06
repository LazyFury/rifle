<?php

namespace Common\Service;

use Common\Model\BaseModel;
use Common\Repository\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Service
{
    protected BaseModel $model;
    protected bool $use_default_query = true; //使用默认的查询

    protected bool $is_superuser = false;

    protected $search_modes = [
        "__gt",
        "__lt",
        "__gte",
        "__lte",
        "__like",
        "__in",
        "__not_in",
        "__or",
        "__or_like",//简单实现多字段模糊查询
        "__eq",
        "__fk", //外键查询, 例如 author__fk__name 仅支持 eq 模式
        "__desc", //排序
        "__asc", //排序
    ];

    public function __construct(BaseModel $model, bool $is_superuser = false)
    {
        $this->model = $model;
        $this->is_superuser = $is_superuser;
    }

    // setIsSuperUser 
    public function setIsSuperUser(bool $is_superuser)
    {
        $this->is_superuser = $is_superuser;
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

        $searchable = array_merge($searchable, $this->model->get_searchable());
        // reindex 
        $searchable = array_values(array_unique($searchable));

        for ($i = 0; $i < count($searchable); $i++) {
            if (in_array($searchable[$i], $this->model->get_searchable_exclude())) {
                unset($searchable[$i]);
            }
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
                // 处理外键查询，例如 author__fk__name，移除__fk 后按照 __ 分割，arr[0] 为外键名，arr[1] 为字段名
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

                // __or_like 
                if ($type == '__or_like') {
                    $query->orWhere(str_replace($type, '', $key), 'like', '%' . $value . '%');
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
                        $server = new Service($model);
                        // $table_name = $model->getTable();
                        $key = $fk_key;
                        // dump("fk:" . $key . "=" . $value);
                        // dump($key, $value);
                        // if still has fk 
                        if (strpos($key, '__fk')) {
                            throw new \Exception("yyy__fk__xxx__fk is not supported");
                        }

                        $searchable = $server->get_searchable();
                        if (in_array($key, $searchable)) {
                            $query->where($key, $value);
                        }

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
                    // dump($type);
                    if (in_array($type, $this->search_modes)) {
                        $this->addQuery($query, $key, $value, $type, $searchable);
                    }
                } else {
                    $this->addQuery($query, $key, $value, "__eq", $searchable);
                }
            }
        }
        // dump($query->toSql());

        $query = $this->model->scopeUsePersonal($query, must_auth: false, is_superuser: $this->is_superuser);

        return $query;
    }

    // get one query
    public function scopeGetOne($id, $column = 'id', $search = [], $must_auth = true): Builder
    {
        $query = $this->model->query();

        $query = $query->where([
            $column => $id,
        ])->where($search);

        $query = $this->model->scopeUsePersonal($query, must_auth: $must_auth, is_superuser: $this->is_superuser);

        return $query;
    }

}
