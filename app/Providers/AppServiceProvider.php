<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) 
        {
            if(Auth::check()){
                $user_id=Auth::user()->id;
                $notifications=[];
                $notification=\App\Notification::where("employee_id",$user_id)->where("status","unread")->get();
                $count_notification=$notification->count();
                foreach ($notification as $noti) {
                    $noti->action =json_decode($noti->action,true);
                    array_push($notifications,$noti);
                }
                $view->with('notifications', $notifications );  
                // View::share('notifications', $notifications);
            }
        });
    }
}
