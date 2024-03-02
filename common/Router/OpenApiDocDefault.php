<?php

namespace Common\Router;

use Common\Utils\OpenApiParam;


return [
    "v1" => [
        "swagger" => "2.0",
        "security" => [
            [
                "token" => []
            ]
        ],
        "components" => [
            "securitySchemes" => [
                "token" => [
                    "type" => "apiKey",
                    "in" => "header",
                    "name" => "auth",
                    "description" => "token",
                    "example" => "123456"
                ]
            ]
        ],
        "info" => [
            "version" => "1.0.0",
            "title" => "API",
            "description" => "使用 Apifox 支持按照目录的方式查看 API 文档",
            "termsOfService" => "http://swagger.io/terms/",
            "contact" => [
                "email" => " [email protected]"
            ],
            "license" => [
                "name" => "Apache 2.0",
                "url" => "http://www.apache.org/licenses/LICENSE-2.0.html"
            ]
        ],
        "parameters" => [
            new OpenApiParam("token", "header", "token", required: false, example: "123456", schema: [
                "type" => "string",
                "description" => "token",
                "example" => "123456"
            ])
        ],
    ]
];
