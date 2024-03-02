<?php

namespace Common\Router;
use Common\Utils\OpenApiParam;


return [
    "v1"=>[
        "parameters"=>[
            new OpenApiParam("token","header","token",required:true,example:"123456")
        ]
    ]
];
