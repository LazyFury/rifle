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
    public function handle(Request $request, Closure $next,$permission_code): Response
    {
        // dump($permission_code);
        $user = auth()->user();

        //如果给没有设置 auth 中间件的路由设置了 permission 中间件，那么这里的 $user 就是 null
        if(!$user){
            return ApiJsonResponse::unauthenticated("检查权限中间件失败，用户未登录。");
        }

        // if(!$user->can($permission_code)){
        //     return ApiJsonResponse::forbidden();
        // }

        return $next($request);
    }
}
