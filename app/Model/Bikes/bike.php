<?php


namespace App\Model\Bikes;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Rider\Rider;
use App\Model\Bikes\bike;
use App\Assign_bike;
use Auth;



class bike extends Authenticatable
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
        'model','insurance_co_name','issue_date','expiry_date','owner','is_given','amount','rider_id','bike_allowns','brand','rental_company','contract_end','contract_start','rent_amount','chassis_number','mulkiya_number','mulkiya_expiry','mulkiya_picture','mulkiya_picture_back','bike_number','rider_id','availability','other','status',
       ];
   
       
       protected $hidden = [
           'password', 'remember_token',
       ];
   
      
       protected $casts = [
           'email_verified_at' => 'datetime',
       ];
    //    public function riders()
    //    {
    //        return $this->belongsTo(Rider::class,'riders' ,'rider_id')->withTimestamps();
    //    }
    // public function Rider(){
    //     return $this->belongsTo(Rider::class);
    // }
       public function bike_detail(){
           return $this->hasOne('App\Model\Bikes\bike_detail');
       }
       public function Assign_bike(){ 
        return $this->hasMany('App\Assign_bike','bike_id');
  
      }
      public function Rider(){
        return $this->belongsTo(Rider::class);
    }
    //    public function Bike_detail(){
    //        return $this->hasOne('App\Model\Bike\Bike_detail');
    //    }
}
