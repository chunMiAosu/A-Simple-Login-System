<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use App\Model\Users;
use App\Model\Role;
use App\Model\Permission;

class HasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //获取当前请求的路由对应的控制器方法名
        $route = Route::current() -> getActionName();
        // dd($route);

        $arr = $request -> session() -> get('permission');
        //判断当前请求的路由对应控制器的方法名是否在当前用户拥有的权限列表（$arr）中
        if(in_array($route,$arr))
        {
            return $next($request);
        }
        else
        {
            return redirect('noaccess');
        }

        


       
    }
}
