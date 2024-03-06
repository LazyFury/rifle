<?php

namespace Common\Controller;

use Common\Model\BaseModel;
use Common\Service\Service;
use Common\Utils\ApiJsonResponse;
use Common\Utils\OpenApiParam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Validator;

class CURD extends Controller
{
    use Pageable;
    // models
    protected BaseModel $model;
    protected Service $service;

    protected $auth_except = [
        'index',
        'show',
    ];

    // constructor
    public function __construct(BaseModel $model)
    {
        $this->model = $model;
        $this->service = new Service($model);

        $this->middleware('auth:sanctum')->except($this->auth_except);
        $this->define_middleware();
    }

    // middlewares
    public function define_middleware()
    {
        $table = $this->model->getTable();
        $permissions = $this->curd_permissions($table);
        foreach ($permissions as $key => $value) {
            // if $key in $this->auth_except:
            // auth 权限优先级高于 permission,如果是 auth_except 中的路由，那么不需要 permission 中间件
            if (in_array($key, $this->auth_except)) {
                continue;
            }
            $this->middleware('permission:' . $value, ['only' => $key]);
        }
    }

    // permission define
    public function curd_permissions(string $table)
    {
        return [
            'index' => 'model.' . $table . '.list',
            'store' => 'model.' . $table . '.create',
            'show' => 'model.' . $table . '.detail',
            'update' => 'model.' . $table . '.update',
            'destroy' => 'model.' . $table . '.delete'
        ];
    }

    // route_alias
    public static function route_alias()
    {
        return "";
    }

    // tag static
    public static function tag()
    {
        return null;
    }

    // meta static
    public static function meta()
    {
        $tag = static::tag();
        return [
            "list" => [
                "name" => "list",
                "desc" => "list all data",
                "tag" => $tag,
                "parameters" => [
                    new OpenApiParam(
                        "page",
                        "query",
                        "页码",
                        required: false,
                        schema: [
                            "type" => "integer",
                            "format" => "int32",
                            "default" => 1
                        ],
                        example: 1
                    ),
                    new OpenApiParam(
                        "limit",
                        "query",
                        "每页数量",
                        required: false,
                        schema: [
                            "type" => "integer",
                            "format" => "int32",
                            "default" => 10
                        ],
                        example: 10
                    ),
                ]
            ],
            "create" => [
                "name" => "create",
                "desc" => "create data",
                "auth" => true,
                "tag" => $tag,
                "parameters" => [
                    new OpenApiParam(
                        "name",
                        "query",
                        "name",
                        required: true,
                        schema: [
                            "type" => "string",
                            "description" => "name",
                            "example" => "test"
                        ],
                        example: "test"
                    ),
                ]
            ],
            "detail" => [
                "name" => "detail",
                "desc" => "detail data",
                "auth" => true,
                "tag" => $tag,
                "params" => [
                    new OpenApiParam(
                        "id",
                        "query",
                        "id",
                        required: true,
                        schema: [
                            "type" => "integer",
                            "format" => "int32",
                        ],
                        example: 1
                    ),
                ]
            ],
            "update" => [
                "name" => "update",
                "desc" => "update data",
                "auth" => true,
                "tag" => $tag,
            ],
            "delete" => [
                "name" => "delete",
                "desc" => "delete data",
                "auth" => true,
                "tag" => $tag,
                "params" => [
                    new OpenApiParam(
                        "id",
                        "query",
                        "id",
                        required: true,
                        schema: [
                            "type" => "integer",
                            "format" => "int32",
                        ],
                        example: 1
                    ),
                ]
            ],
            "export" => [
                "name" => "export",
                "desc" => "export data",
                "auth" => true,
                "tag" => $tag,
            ]
        ];
    }

    // routes
    public static function routers()
    {
        $metas = static::meta();
        return [
            'list' => [
                'method' => 'get',
                'uri' => 'list',
                'action' => 'index',
                'meta' => $metas['list'] ?? []
            ],
            'create' => [
                'method' => 'post',
                'uri' => 'create',
                'action' => 'store',
                'meta' => $metas['create'] ?? []
            ],
            'detail' => [
                'method' => 'get',
                'uri' => 'detail',
                'action' => 'show',
                'meta' => $metas['detail'] ?? []
            ],
            'update' => [
                'method' => 'put',
                'uri' => 'update',
                'action' => 'update',
                'meta' => $metas['update'] ?? []
            ],
            'delete' => [
                'method' => 'delete',
                'uri' => 'delete',
                'action' => 'destroy',
                'meta' => $metas['delete'] ?? []
            ],
            "export" => [
                "method" => "get",
                "uri" => "export",
                "action" => "export",
                "meta" => $metas['export'] ?? []
            ]
        ];
    }

    // index
    public function index(Request $request)
    {
        $query = $this->model->query();
        // extra filter
        // dump($query->toSql());
        $dict = $request->all();

        $query = $this->service->scopeSearch($query, $dict);
        $query = $this->filter($query);
        return $this->pagination($query, $request);
    }

    // filter
    public function filter(Builder $query)
    {
        return $query;
    }

    // store
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), $this->model->rules(), $this->model->messages());
        if ($valid->fails()) {
            return ApiJsonResponse::error($valid->errors()->first(), 400, data: $valid->errors());
        }
        $result = $this->model->create($valid->validated());
        return ApiJsonResponse::success($result);
    }

    // id valid
    // return array type
    public function getId(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'id' => 'required|integer'
        ], [
            'id.required' => 'id is required!',
            'id.integer' => 'id must be integer!'
        ]);
        if ($valid->fails()) {
            return [null, $valid->errors()];
        }
        return [$valid->validated()['id'], []];
    }

    // show
    public function show(Request $request)
    {
        [$id, $errors] = $this->getId($request);
        if ($errors) {
            return ApiJsonResponse::error($errors->first(), 400, data: $errors);
        }
        $result = $this->model->scopeGetOne($id, must_auth: false)->first();
        if ($result) {
            return ApiJsonResponse::success($result);
        }
        return ApiJsonResponse::error(trans('Not Found!'), 404, http_status: 404);
    }

    // update

    public function update(Request $request)
    {
        [$id, $errors] = $this->getId($request);
        if ($errors) {
            return ApiJsonResponse::error($errors->first(), 400, data: $errors);
        }

        $model = $this->service->scopeGetOne($id)->first();
        if (!$model) {
            return ApiJsonResponse::error("Not Found!", 404);//not able to update other's data
        }

        $valid = Validator::make($request->all(), $this->model->rules(), $this->model->messages());
        if ($valid->fails()) {
            return ApiJsonResponse::error($valid->errors()->first(), 400, data: $valid->errors());
        }
        $model->update($valid->validated());
        return ApiJsonResponse::success($model);
    }


    // destroy
    public function destroy(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'ids' => 'required|array'
        ], [
            'ids.required' => 'ids is required!',
            'ids.array' => 'ids must be array!'
        ]);
        if ($valid->fails()) {
            return ApiJsonResponse::error($valid->errors()->first(), 400, data: $valid->errors());
        }
        $ids = $valid->validated()['ids'];
        $this->model->destroy($ids);
        return ApiJsonResponse::success("删除成功!");
    }

    // export 
    public function export(Request $request)
    {
        $query = $this->model->query();
        $query = $this->service->scopeSearch($query, $request->all());
        $query = $this->filter($query);
        $data = $query->get();

        // loop data json_encode not string 
        for ($i = 0; $i < count($data); $i++) {
            $arr = $data[$i]->toArray();
            foreach ($arr as $key => $value) {
                if (!is_string($value)) {
                    $data[$i][$key] = json_encode($value);
                }
            }
        }

        return $this->toXlsx($data);
    }

    // toXlsx
    public function toXlsx($data)
    {
        $workbook = new Spreadsheet();
        // global font size 
        $workbook->getDefaultStyle()->getFont()->setSize(12);
        $sheet = $workbook->getActiveSheet();

        $columns = $this->service->get_columns();
        $column_names = [];
        foreach ($columns as $column) {
            $column_names[] = $column['name'];
        }

        $sheet->fromArray($column_names, null, 'A1');
        // a1 style gray background 
        $sheet->getStyle('A1:Z1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFDDDDDD');
        // a1 style with border
        $sheet->getStyle('A1:Z1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // lineheight = 20;
        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->fromArray($data->toArray(), null, 'A2');
        // a2 lineheight 
        $sheet->getRowDimension(2)->setRowHeight(20);

        $writer = new Xlsx($workbook);
        $filename = tempnam(sys_get_temp_dir(), 'export_') . '.xlsx';
        $writer->save($filename);
        return response()->download($filename);
    }

    // toCsv 
    public function toCsv($data)
    {
        $csv = "";
        $columns = $this->model->getFillable();
        $csv .= implode(",", $columns) . "\n";
        foreach ($data as $item) {
            foreach ($columns as $column) {
                if (!is_string($item->$column)) {
                    $item->$column = "Not Support";// json_encode($item->$column);
                }
                $csv .= $item->$column . ",";
            }
            $csv .= "\n";
        }
        $csv .= "";
        return $csv;
    }
}
