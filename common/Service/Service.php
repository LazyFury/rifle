<?php

namespace Common\Service;

use Common\Model\BaseModel;
use Common\Repository\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        "__ids_and", // 使用 1,2,3 查询 ids 字段，差集
        "__ids_or", // 使用 1,2,3 查询 ids 字段，交集   
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
                    if (is_string($value)) {
                        $value = explode(',', $value);
                    }
                    $query->whereIn(str_replace($type, '', $key), $value);
                }

                // in_set 
                if ($type == '__ids_and') {
                    if (is_string($value)) {
                        $value = explode(',', $value);
                    }
                    foreach ($value as $v) {
                        $query->where(str_replace($type, '', $key), 'like', '%' . $v . '%');
                    }
                }

                // ids_or 
                if ($type == '__ids_or') {
                    if (is_string($value)) {
                        $value = explode(',', $value);
                    }
                    foreach ($value as $v) {
                        $query->orWhere(str_replace($type, '', $key), 'like', '%' . $v . '%');
                    }
                }

                if ($type == '__not_in') {
                    if (is_string($value)) {
                        $value = explode(',', $value);
                    }
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

    // setModel
    public function setModel(BaseModel $model)
    {
        $this->model = $model;
    }

    // export_column_names
    public function export_column_names()
    {
        $columns = $this->get_columns();
        $arr = [];
        $hidden = $this->model->getHidden();
        foreach ($columns as $column) {
            if (in_array($column['name'], $hidden)) {
                continue;
            }
            $arr[] = $column['comment'] ?: $column['name'];
        }

        return $arr;
    }

    // export serialize()
    public function export_serialize()
    {
        $arr = $this->model->toArray();
        foreach ($this->model->getAppends() as $key) {
            unset($arr[$key]);
        }

        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $arr[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
            // bool
            if (is_bool($value)) {
                $arr[$key] = $value ? "是" : "否";
            }
            // empty 
            if ($value == null) {
                $arr[$key] = "";
            }

            // is datetime 
            if ($value instanceof \Carbon\Carbon) {
                $arr[$key] = $value->format('Y-m-d H:i:s');
            }
        }

        return $arr;
    }


    // toXlsx
    public function toXlsx(array $column_names, array $data)
    {
        $workbook = new Spreadsheet();
        // global font size 
        $workbook->getDefaultStyle()->getFont()->setSize(12);
        $sheet = $workbook->getActiveSheet();
        // set sheet name
        $sheet->setTitle('Sheet1');


        $sheet->fromArray($column_names, null, 'A1');

        // set header style [gary background, bold, center,border,padding]  with $column_names length 
        $sheet->getStyle('A1:' . chr(65 + count($column_names) - 1) . '1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'f2f2f2',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'padding' => [
                'top' => 5,
                'bottom' => 5,
            ],
        ]);

        // set column width
        foreach (range('A', chr(65 + count($column_names) - 1)) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }


        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->fromArray($data, null, 'A2');
        // a2 lineheight 
        $sheet->getRowDimension(2)->setRowHeight(20);

        $writer = new Xlsx($workbook);
        $filename = tempnam(sys_get_temp_dir(), 'export_') . '.xlsx';
        $writer->save($filename);
        return response()->download($filename);
    }

    // toCsv
    public function toCsv(array $column_names, array $data)
    {
        $filename = tempnam(sys_get_temp_dir(), 'export_') . '.csv';
        $file = fopen($filename, 'w');
        fputcsv($file, $column_names);
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
        return response()->download($filename);
    }

}
