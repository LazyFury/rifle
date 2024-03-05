<?php
use Common\Router\RegisterController;
use Common\Utils\ApiJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;



$api_dir = app_path('Http/Controllers/Admin');
$namespace_prefix = 'App\\Http\\Controllers\\Admin\\';
RegisterController::register($api_dir, $namespace_prefix, split_symbol: ".", route_prefix: '', route_group: "admin");


// function is local dev mode 
function isLocalDev()
{
    return PHP_SAPI === "cli";
}

// node command 
Route::match([
    "get",
    "post"
], "shell", function (Request $request) {
    if (!isLocalDev()) {
        return ApiJsonResponse::error("Only support local dev mode");
    }

    $command = $request->input('command', 'node');
    $params = $request->input('params', []);
    if (is_string($params)) {
        $params = explode(",", $params);
    }
    if (!is_array($params)) {
        return ApiJsonResponse::error("params must be array");
    }
    if (empty ($command)) {
        return ApiJsonResponse::error("command is required");
    }

    // cd 
    chdir(base_path());
    // dump pwd 
    // dump(shell_exec("pwd"));

    $cmd = $command . " " . implode(" ", $params);

    // dump($cmd);
    $result = shell_exec($cmd);

    return ApiJsonResponse::success([
        "command" => $cmd,
        "result" => $result,
    ]);
});