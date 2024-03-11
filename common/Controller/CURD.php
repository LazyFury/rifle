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

    protected $auth_except = [];

    protected $is_superuser = false; // 是否是超级管理员

    // constructor
    public function __construct(BaseModel $model)
    {
        $this->model = $model;
        $this->service = new Service($model, $this->is_superuser);

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
        $valid = Validator::make($request->all(), $this->model->rules(false, $request->all()), $this->model->messages());
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

        $valid = Validator::make($request->all(), $model->rules(isUpdate: true, data: $request->all()), $model->messages());
        if ($valid->fails()) {
            return ApiJsonResponse::error($valid->errors()->first(), 400, data: $valid->errors());
        }
        $model->update($valid->validated());
        return ApiJsonResponse::success($model);
    }


    // destroy
    public function destroy(Request $request, $destoryCheck = null)
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
        for ($i = 0; $i < count($ids); $i++) {
            $model = $this->service->scopeGetOne($ids[$i])->first();
            if (!$model) {
                return ApiJsonResponse::error("Not Found!", 404);//not able to delete other's data
            }
            if (!$model->get_deleteable()) {
                return ApiJsonResponse::error("禁止删除!", 403);//not able to delete other's data
            }

            if ($destoryCheck && is_callable($destoryCheck)) {
                $result = $destoryCheck($model);
                if ($result) {
                    return $result;
                }
            }
        }
        $this->model->destroy($ids);
        return ApiJsonResponse::success("删除成功!");
    }


    // export 
    public function export(Request $request)
    {

        $type = $request->get('type', 'xlsx');
        // support xlsx csv
        if (!in_array($type, ['csv', 'xlsx'])) {
            return ApiJsonResponse::error("不支持的导出类型", 400);
        }

        $query = $this->model->query();
        $query = $this->service->scopeSearch($query, $request->all());
        // $query = $this->filter($query);
        $data = $query->get();
        $service = clone $this->service;
        $columns = $service->export_column_names();

        $result = [];
        foreach ($data as $key => $item) {
            $service->setModel($item);
            $result[] = $service->export_serialize();
        }
        // loop data json_encode not string 

        if ($type == 'csv') {
            return $this->service->toCsv($columns, $result);
        }
        return $this->service->toXlsx($columns, $result);
    }

}
