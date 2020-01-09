<?php

namespace App\Http\Middleware;
use Closure;
use App\Model\Admin\Role;
use Auth;
use Arr;
use \App\WebRoute;
class Admin_Role
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
        $route_name = $request->route()->getName();
        $admin_gaurd = $request->user();
        $user_type=$admin_gaurd->type;
        if ($user_type=="normal") {
            $users=$admin_gaurd->Role()->where("action_name",$route_name)->get()->first();
            if (isset($users)) {
                return $next($request);   
            }
            //permission denied
            if($request->ajax()){
                $web_route=WebRoute::where("route_name",$route_name)->get()->first();
                $web_route_name = $web_route->label;
                return abort(403,$web_route_name); 
            }
            return redirect(route('request.403'));  
        }
        return $next($request);
    }
}
