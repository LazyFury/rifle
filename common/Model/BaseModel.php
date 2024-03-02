<?php

namespace Common\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use UseTimeFormatTairt,UsePersonalFakeTrait;

    protected bool $use_default_query = true;

    public function searchable()
    {
        $columns = $this->columns();
        $searchable = [];
        foreach ($columns as $column) {
            $searchable[] = $column['name'];
        }
        // dump($searchable);
        return $searchable;
    }

    // base columns search
    public function scopeSearch($query, $search)
    {
        if(!$this->use_default_query)return $query;
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
                    if ($type == '__desc' and ($value == 1)) {
                        $query->orderBy($key, 'desc');
                    }

                    // asc
                    if ($type == '__asc' and ($value == 1)) {
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
                                addQuery($query, $key, $value, "__eq", $searchable);
                            }
                            // dump($query->toSql());
                        });
                    }
                }
            }
        }
        $searchable = $this->searchable();
        if ($search && $this->use_default_query) {
            $query->where(function ($query) use ($search, $searchable) {
                foreach ($search as $key => $value) {
                    $arr = ["__gt", "__lt", "__gte", "__lte", "__like", "__in", "__not_in", "__or", "__eq", "__fk",
                        "__desc", "__asc"];
                    foreach ($arr as $type) {
                        addQuery($query, $key, $value, $type, $searchable);
                    }
                }
            });
        }
        // dump($query->toSql());

        $query = $this->scopeUsePersonal($query,must_auth:false);

        return $query;
    }

    // get one query
    public function scopeGetOne($id,$column='id',$search=[],$must_auth=true):Builder
    {
        $query = $this->query();
        $query = $query->search([
            $column => $id,
        ])->where($search);

        $query = $this->scopeUsePersonal($query,must_auth:$must_auth);

        return $query;
    }


    // base columns order
    public function columns()
    {
        // exec file line
        // $_FILES = debug_backtrace();
        // dump($_FILES[0]['file'] . ":" . $_FILES[0]['line']);
        // dump("columns", $this->getTable());
        $cache_key = 'columns_' . $this->getTable();
        if (cache()->has($cache_key)) {
            return cache()->get($cache_key);
        }
        $columns = $this->getConnection()->getSchemaBuilder()->getColumns($this->getTable());
        cache()->put($cache_key, $columns, 5 * 60);
        return $columns;
    }

    // filleble exclude
    public function fillable_exclude()
    {
        return [
            'id',
            'created_at',
            'updated_at',
        ];
    }

    public function get_fillable()
    {
        $columns = $this->columns();
        $fillable = [];
        foreach ($columns as $column) {
            if (!in_array($column['name'], $this->fillable_exclude())) {
                $fillable[] = $column['name'];
            }
        }
        return $fillable;
    }

}
