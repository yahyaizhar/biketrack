<?php

namespace App\Model\Mobile;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
class Mobile extends Authenticatable
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
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model', 'imei', 'purchase_price', 'sale_price', 'payment_type', 'amount_received', 'installment_starting_month', 'installment_ending_month', 'per_month_installment_amount',
    ];
    public function Mobile_Transaction(){ 
        return $this->hasMany('App\Model\Mobile\Mobile_Transaction','mobile_id');
  
      }
      public function Mobile_installment(){ 
        return $this->hasMany('App\Model\Mobile\Mobile_installment','mobile_id');
  
      }
}
