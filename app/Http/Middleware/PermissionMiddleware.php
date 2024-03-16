<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Common\Utils\ApiJsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission_code): Response
    {
        // dump($permission_code);
        $user = auth()->user();

        //如果给没有设置 auth 中间件的路由设置了 permission 中间件，那么这里的 $user 就是 null
        if (!$user) {
            return ApiJsonResponse::unauthenticated("检查权限中间件失败，用户未登录。");
        }

        if ($user->is_superuser) {
            logger("passed superuser", [$user->is_superuser]);
            return $next($request);
        }

        // if (!$user->can($permission_code)) {
        //     return ApiJsonResponse::forbidden();
        // }
        logger("permission_code", [$permission_code]);
        logger("roles", [$user->roles->pluck('name')]);

        [$type] = explode('.', $permission_code);
        if ($type == "model") {
            // logger("model", [$type]);
            [$_, $model] = explode('.', $permission_code);
            $alias = "model.{$model}.*";
            // logger("user", [$user->permissions]);
            // logger($user->hasRole('admin'));

            $permissions = $user->getPermissionsViaRoles()->toArray();
            // logger("permissions", [$permissions]);
            // 检查模型权限
            if (in_array($permission_code, $permissions)) {
                logger("pass code", [$permission_code]);
                return $next($request);
            }

            // 检查模型别名权限
            if (in_array($alias, $permissions)) {
                logger("pass *", [$permission_code]);
                return $next($request);
            }
        }

        return ApiJsonResponse::forbidden();
    }
}
