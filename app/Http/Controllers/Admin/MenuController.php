<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiManage;
use Common\Controller\CURD;
use Common\Utils\ApiJsonResponse;
use Illuminate\Http\Request;
use Validator;

class MenuController extends CURD
{

    public function __construct(\App\Models\Menu $model)
    {
        parent::__construct($model);
    }

    protected $auth_except = [
        // "all"
    ];

    //
    // static routers 
    public static function routers()
    {
        $arr = [
            "all" => [
                "method" => "get",
                "uri" => "all",
                "action" => "all"
            ]
        ];

        return array_merge($arr, parent::routers());
    }

    public function filter(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where("parent_id", null);
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $data["parent_id"] = $data["parent_id"] ?? null;
        return parent::create($request);
    }

    public function update(Request $request)
    {
        return parent::update($request);
    }

    public function all(Request $request)
    {
        $menus = \App\Models\Menu::where("parent_id", null)->get();
        return ApiJsonResponse::success([
            "menus" => [
                [
                    "title" => "控制台",
                    "key" => "overview",
                    "path" => "/overview",
                    "icon" => "ant-design:home-outlined",
                    "component" => "HomeView"
                ],
                ...$menus->toArray(),
                // [
                //     "title" => "文章管理",
                //     "key" => "article",
                //     "path" => "/article",
                //     "icon" => "ant-design:file-outlined",
                //     "component" => "TableView",
                //     "meta" => ApiManage::where("key", "article")->first()
                // ],
                [
                    // dev mode 
                    "title" => "开发人员系统维护",
                    "icon" => "ant-design:sliders-outlined",
                    "key" => "dev",
                    "children" => [
                        [
                            "title" => "菜单管理",
                            "key" => "menu",
                            "path" => "/dev/menu",
                            "component" => "TableView",
                            "meta" => ApiManage::where("key", "menu-manager")->first()
                        ],
                        [
                            "title" => "Api 表格管理",
                            "key" => "api",
                            "path" => "/dev/api",
                            "component" => "dev/SystemTableView",
                            "meta" => [
                                "list_api" => "/api_manage.list",
                                "create_api" => "/api_manage.create",
                                "update_api" => "/api_manage.update",
                                "delete_api" => "/api_manage.delete",
                                "export_api" => "/api_manage.export",
                                "columns" => [
                                    [
                                        "title" => "名称",
                                        "dataIndex" => "title",
                                        "key" => "title"
                                    ],
                                    [
                                        "title" => "key",
                                        "dataIndex" => "key",
                                        "key" => "key"
                                    ],
                                    [
                                        "title" => "API",
                                        "dataIndex" => "api",
                                        "key" => "list_api"
                                    ],
                                    [
                                        "title" => "删除API",
                                        "dataIndex" => "del_api",
                                        "key" => "delete_api"
                                    ],
                                    [
                                        "title" => "更新API",
                                        "dataIndex" => "update_api",
                                        "key" => "update_api"
                                    ],
                                    [
                                        "title" => "创建API",
                                        "dataIndex" => "add_api",
                                        "key" => "create_api"
                                    ]
                                ],
                                "add_form_fields" => [
                                    [
                                        [
                                            "label" => "名称",
                                            "name" => "title",
                                            "placeholder" => "请输入名称"
                                        ],
                                        // key
                                        [
                                            "label" => "key",
                                            "name" => "key",
                                            "placeholder" => "请输入key"
                                        ],
                                    ],
                                    [
                                        // apis 
                                        [
                                            "label" => "API",
                                            "name" => "list_api",
                                            "placeholder" => "请输入API"
                                        ],
                                        // del_api 
                                        [
                                            "label" => "删除API",
                                            "name" => "delete_api",
                                            "placeholder" => "请输入删除API"
                                        ],
                                        // update_api 
                                        [
                                            "label" => "更新API",
                                            "name" => "update_api",
                                            "placeholder" => "请输入更新API"
                                        ],
                                        // create_api
                                        [
                                            "label" => "创建API",
                                            "name" => "create_api",
                                            "placeholder" => "请输入创建API"
                                        ],
                                        // export_api
                                        [
                                            "label" => "导出API",
                                            "name" => "export_api",
                                            "placeholder" => "请输入导出API"
                                        ]
                                    ],
                                    [
                                        [
                                            "label" => "表格列",
                                            "name" => "columns",
                                            "placeholder" => "请输入表格列",
                                            "width" => "100%"
                                        ],
                                    ],
                                    [
                                        [
                                            "label" => "搜索表单",
                                            "name" => "search_form_fields",
                                            "placeholder" => "请输入搜索表单",
                                            "width" => "100%"
                                        ]
                                    ],
                                    [
                                        // add_form_fields
                                        [
                                            "label" => "添加表单",
                                            "name" => "add_form_fields",
                                            "placeholder" => "请输入添加表单",
                                            "width" => "100%"
                                        ]
                                    ],
                                    [
                                        [
                                            "label" => "描述",
                                            "name" => "description",
                                            "placeholder" => "请输入描述",
                                            "width" => "100%"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}
