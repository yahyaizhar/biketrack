<?php

namespace App\Model\Accounts;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class Income_zomato extends Authenticatable
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
         self::updating(function ($updated_model) {
             try {
                $subject_class=get_class($updated_model);
                $subject_id=$updated_model->id;
                $old_modal=$subject_class::find($subject_id)->toArray();

                $changed_array=[];
                $old_changed_array=[];
                foreach( $updated_model->toArray() as $key => $value )
                {
                    if($old_modal[$key]!=$value){
                        //data changed on this field
                        $changed_array[$key]=$value;
                        $old_changed_array[$key]=$old_modal[$key];
                    }
                }
                $activity_model = new \App\Log_activity;
                $desc = 'updated';
                if(isset($model->active_status)){
                    if($model->active_status=='D'){
                        $desc = 'soft_deleted';
                    }
                }
                $activity_model->description=$desc;
                $activity_model->subject_id=$updated_model->id;
                $activity_model->subject_type=get_class($updated_model);
                $activity_model->causer_id=Auth::user()->id;
                $activity_model->causer_type=get_class(Auth::user());
                
                $activity_model->updated_old=json_encode($old_changed_array);
                $activity_model->updated_new=json_encode($changed_array);
                $activity_model->save();
            } catch (\Exception $ex) { }
        });
    }
    // ends logging

    protected $fillable = [
        'feid','rider_id','log_in_hours_payable','total_to_be_paid_out',
        'amount_for_login_hours','amount_to_be_paid_against_orders_completed','ncw_incentives','tips_payouts','dc_deductions',
        'mcdonalds_deductions', 'date'
    ];
    public function Time_sheet(){
        return $this->hasMany('App\Model\Zomato\Riders_Payouts_By_Days','zomato_income_id');
  
      }
}
