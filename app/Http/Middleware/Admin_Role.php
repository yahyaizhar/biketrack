<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Admin\Role;
use Auth;
use Arr;
class Admin_Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$role)
    {
        $admin_gaurd = $request->user();
        if($admin_gaurd->Active_status != "A"){
            Auth::guard('admin')->logout();
            return redirect('/admin/login');
        }
        if($role!="Custom_Auth"){
            $user_type=$admin_gaurd->type;
            if ($user_type=="normal") {
                $users=$admin_gaurd->Role()->where("action_name",$role)->get()->first();
                if (isset($users)) {
                    return $next($request);   
                }
                return redirect(route('request.403'));
            }
        }
        return $next($request); 
     
    }
}
