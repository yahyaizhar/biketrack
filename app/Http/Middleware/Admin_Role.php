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
        $user_type=$request->user()->type;
        if ($user_type=="normal") {
        $users=$request->user()->Role()->where("action_name",$role)->get()->first();
        if (isset($users)) {
            return $next($request);   
        }
         return redirect(route('request.403'));
        }
        return $next($request); 
     
    }
}
