<?php

namespace App\Model\Rider;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Client\Client;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class Rider_Performance_Zomato extends Authenticatable
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
        'date', 'feid', 'trips','import_id','adt','average_pickup_time','average_drop_time','loged_in_during_shift_time','total_loged_in_hours','cod_orders','cod_amount',
    ];
    public function rider()
    {
        return $this->belongsTo(Rider::class, 'rider_id');
    }
}
