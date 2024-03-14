<?php

namespace Common\Router;

use Common\Utils\ApiJsonResponse;
use Illuminate\Support\Facades\Route;

class RegisterController
{
    // listAllFiles
    public static function list_all_files($dir)
    {
        $result = [];
        $c = scandir($dir);
        foreach ($c as $value) {
            if ($value == '.' || $value == '..') {
                continue;
            }
            if (is_dir($dir . '/' . $value)) {
                $result = array_merge($result, self::list_all_files($dir . '/' . $value));
            } else {
                $result[] = $dir . '/' . $value;
            }
        }
        return $result;
    }


    /**
     * register
     * @param $api_dir api dir
     * @param $namespace_prefix namespace prefix
     * @param string $split_symbol split symbol
     * @param bool $dir_keep_slash keep slash in dir
     * @param string $route_prefix "v1" path will be /api/v1/xxx,register route and openapi doc will use it
     * @param string $route_group "api" path will be /api/xxx ,only for openapi doc
     */
    public static function register($api_dir, $namespace_prefix, $split_symbol = '.', $dir_keep_slash = true, $route_prefix = "", $route_group = "api")
    {
        $files = self::list_all_files($api_dir);
        foreach ($files as $key => $value) {
            $files[$key] = str_replace($api_dir . '/', '', $value);
        }

        // dump($files);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $file = $api_dir . '/' . $file;
            if (is_file($file)) {
                // controller_name with dir relate
                $controller_name = str_replace($api_dir . '/', '', $file);
                // remove .php
                $controller_name = str_replace('.php', '', $controller_name);
                $controller = $namespace_prefix . str_replace('/', '\\', $controller_name);
                // dump($controller);

                $controller_arr = explode('\\', $controller_name);
                $controller_class_name = end($controller_arr);
                $controller_class_name = preg_replace('/(?<=[a-z])(?=[A-Z])/', '_', $controller_class_name);
                // lower
                $controller_class_name = strtolower($controller_class_name);
                $controller_class_name = str_replace("_controller", "", $controller_class_name);
                $controller_arr[count($controller_arr) - 1] = $controller_class_name;
                $controller_name = implode('/', $controller_arr);

                // replace / to $split_symbol
                if ($dir_keep_slash == false) {
                    $controller_name = str_replace('/', $split_symbol, $controller_name);
                }

                $route_alias = $controller_name;
                if (method_exists($controller, 'route_alias') and is_callable([$controller, 'route_alias'])) {
                    $route_alias = $controller::route_alias();
                    if ($route_alias == "" or $route_alias == null) {
                        $route_alias = $controller_name;
                    }
                }
                // dump($controller);
                if (method_exists($controller, 'routers') and is_callable([$controller, 'routers'])) {
                    $extra_methods = $controller::routers();
                    // dump($extra_methods);
                }

                Route::group([
                    "prefix" => $route_prefix,
                ], function () use ($controller, $extra_methods, $route_alias, $split_symbol) {
                    // extra methods
                    foreach ($extra_methods as $method => $config) {
                        Route::match(
                            [$config['method']],
                            $route_alias . $split_symbol . $config['uri'],
                            $controller . '@' . $config['action']
                        )->meta($config['meta'] ?? []);
                    }
                });

            }
        }

        Route::get($route_prefix . '/routes', function () use ($route_prefix, $route_group) {
            $doc_default = require_once __DIR__ . "/OpenApiDocDefault.php";

            // print all routers
            $routes = Route::getRoutes();
            $routes = $routes->getRoutes();
            $result = [];
            $prefix = $route_group . "/" . $route_prefix;


            foreach ($routes as $route) {
                // prefix with api/$route_prefix
                // dump($route->uri);
                // dump("api" . "/" . $route_prefix);
                if ($route->action['prefix'] != $prefix) {
                    continue;
                }
                $result[] = [
                    "uri" => $route->uri,
                    "method" => $route->methods[0],
                    "action" => $route->action['uses'],
                    "meta" => $route->action['meta'] ?? [],
                    "middleware" => $route->action['middleware'] ?? []
                ];
            }
            $paths = [];
            $tags = [];
            $schemas = [];


            foreach ($result as $route) {
                $uri = ($route_prefix ? "/" : "") . $route['uri'];
                // $tag = uri without last
                $arr = explode("/", $uri);
                array_pop($arr);
                $tag = implode("/", $arr);

                $tag_name = $tag;
                if ($route['meta']['tag'] ?? false) {
                    $tag_name = $prefix . ($route_prefix ? "/" : "") . $route['meta']['tag'];
                }
                $tag = [
                    "name" => $tag_name,
                    "description" => ""
                ];
                $tags[] = [
                    "name" => $tag['name'] ?? $uri,
                    "description" => $tag['description'] ?? ""
                ];

                $parameters = $route['meta']['params'] ?? $route['meta']['parameters'] ?? [];

                $schemas = array_merge($schemas, $route['meta']['schemas'] ?? []);

                $method = strtolower($route['method']);

                $config = [
                    "tags" => [
                        $tag['name']
                    ],
                    "summary" => $route['meta']['name'] ?? $uri,
                    "description" => $route['meta']['desc'] ?? "",
                    "operationId" => $uri,
                    "parameters" => $parameters,
                    "responses" => [
                        "200" => [
                            "description" => "successful operation"
                        ]
                    ],
                    // "requestBody" => $route['meta']['requestBody'] ?? "",//openapi 3.0 不太方便
                ];

                $default_params = $doc_default['v1']['parameters'] ?? [];
                $config['parameters'] = array_merge($config['parameters'], $default_params);

                $paths[$uri] = [
                    $method => $config,
                ];
            }


            // tags remove duplicate to arrat
            $tags = array_values(array_unique($tags, SORT_REGULAR));
            $doc = [

                "paths" => $paths,
                "tags" => $tags,
                "definitions" => $schemas,

            ];

            $doc = array_merge($doc_default['v1'], $doc);

            return $doc;
        });
    }

}
