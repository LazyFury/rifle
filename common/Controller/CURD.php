<?php

namespace Common\Controller;

use Common\Model\BaseModel;
use Common\Utils\ApiJsonResponse;
use Common\Utils\OpenApiParam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CURD extends Controller
{
    use Pageable;
    // models
    protected BaseModel $model;

    protected $rules = [];
    protected $messages = [];

    protected $auth_except = [
        'index',
        'show'
    ];

    // constructor
    public function __construct(BaseModel $model)
    {
        $this->model = $model;
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
            if(in_array($key,$this->auth_except)){
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
                "parameters"=>[
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
                "body" => []
            ],
            "detail" => [
                "name" => "detail",
                "desc" => "detail data",
                "auth" => true,
                "tag" => $tag,
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
            ]
        ];
    }

    // index
    public function index()
    {
        $query = $this->model->query();
        $data = $this->query($query);

        // extra filter
        if (method_exists($this, 'filter')) {
            $data["query"] = $this->filter($data["query"]);
        }

        $query = $data["query"];
        $query = $this->model->scopeSearch($query, request()->all());
        return $this->pageableResponse($query, $data['page'], $data['limit'], $data['offset']);
    }

    // filter
    public function filter($query)
    {
        return $query;
    }

    // store
    public function store(Request $request)
    {
        $valid = \Validator::make($request->all(), $this->rules, $this->messages);
        if ($valid->fails()) {
            return ApiJsonResponse::error($valid->errors()->first(), 400,data: $valid->errors());
        }
        return $this->model->create($valid->validated());
    }

    // id valid
    // return array type
    public function getId(Request $request)
    {
        $valid = \Validator::make($request->all(), [
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
        $result = $this->model->scopeGetOne($id,must_auth:false)->first();
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

        $model = $this->model->scopeGetOne($id)->first();
        if(!$model){
            return ApiJsonResponse::error("Not Found!", 404);//not able to update other's data
        }
        $valid = \Validator::make($request->all(), $this->rules, $this->messages);
        if ($valid->fails()) {
            return ApiJsonResponse::error($valid->errors()->first(), 400,data: $valid->errors());
        }
        $model->update($valid->validated());
        return $model;
    }


    // destroy
    public function destroy(Request $request)
    {
        [$id, $errors] = $this->getId($request);
        if ($errors) {
            return ApiJsonResponse::error($errors->first(), 400, data: $errors);
        }
        $model = $this->model->scopeGetOne($id)->first();
        if(!$model){
            return ApiJsonResponse::success("删除成功!");//trick
        }
        $model->delete();
        return ApiJsonResponse::success("删除成功!");
    }
}
