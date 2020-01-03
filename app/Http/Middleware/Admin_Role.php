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
        // dd($route_name);
        $admin_gaurd = $request->user();
        // if($admin_gaurd->Active_status != "A"){
        //     Auth::guard('admin')->logout();
        //     return redirect('/admin/login');
        // }
        $user_type=$admin_gaurd->type;
        if ($user_type=="normal") {
            $users=$admin_gaurd->Role()->where("action_name",$route_name)->get()->first();
            if (isset($users)) {
                return $next($request);   
            }
            $web_route=WebRoute::where("route_name",$route_name)->get()->first();
            ///if route is not defined
            if(!isset($web_route)){
                return redirect(route('request.403'));
            }
            //end
            $web_route_name = $web_route->label;
            if($request->ajax()){
                return abort(403,$web_route_name); 
            }
        }
        return $next($request); 
     
    }
}
