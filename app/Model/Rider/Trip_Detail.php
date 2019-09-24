<?php

namespace App\Model\Rider;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Client\Client; 
use Auth;

use Illuminate\Database\Eloquent\Model;

class Trip_Detail extends Authenticatable
{
    /**
     * Start logging.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // auto-sets values on creation
        self::created(function($model){
            $activity_model = new \App\Log_activity;
            $activity_model->description="created";
            $activity_model->subject_id=$model->id;
            $activity_model->subject_type=get_class($model);
            $activity_model->causer_id=Auth::user()->id;
            $activity_model->causer_type=get_class(Auth::user());
            $activity_model->save();
        });

         // auto-sets values on creation
         self::updated(function($model){
            $activity_model = new \App\Log_activity;
            $desc = 'updated';
            if(isset($model->active_status)){
                if($model->active_status=='D'){
                    $desc = 'soft_deleted';
                }
            }
            $activity_model->description=$desc;
            $activity_model->subject_id=$model->id;
            $activity_model->subject_type=get_class($model);
            $activity_model->causer_id=Auth::user()->id;
            $activity_model->causer_type=get_class(Auth::user());
            $activity_model->save();
        });
    }
    // ends logging
    
    protected $fillable = [
        'transaction_id','rider_id','active_status','trip_date','trip_time','transaction_post_date','toll_gate','direction','tag_number','plate','amount_AED',
    ];
}
 