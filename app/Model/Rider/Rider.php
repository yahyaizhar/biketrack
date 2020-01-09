<?php

namespace App\Model\Rider;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Client\Client;
use Auth;

class Rider extends Authenticatable
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
    use Notifiable;
    protected $table = 'riders';
    protected $guard = 'api-riders';
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','area_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_riders', 'rider_id', 'client_id')->withPivot('client_id', 'rider_id', 'created_at', 'updated_at', 'client_rider_id');
    }
    public function client_history()
    {
        return $this->belongsToMany(Client_History::class,  'rider_id', 'client_id')->withPivot('client_id', 'rider_id', 'created_at', 'updated_at', 'client_rider_id');
    }
    public function messages()
    {
        return $this->hasMany(Rider_Message::class, 'rider_id');
    }
    public function WPS()
    {
        return $this->hasMany('App\Model\Accounts\WPS', 'rider_id');
    }
    public function id_charges()
    {
        return $this->hasMany('App\Model\Accounts\Id_charge', 'rider_id');
    }
    public function Rider_Performance_Zomato()
    {
        return $this->hasMany(Rider_Performance_Zomato::class, 'rider_id');
    }
    public function locations()
    {
        return $this->hasMany(Rider_Location::class, 'rider_id');
    }
    public function Rider_Report()
    {
        return $this->hasMany(Rider_Report::class, 'rider_id');
    }
    public function onlineTimes()
    {
        return $this->hasMany(Rider_Online_Time::class, 'rider_id');
    }
    public function getLatestLocation($rider_id)
    {
    	return $locations = Rider_Location::select('rider_id', 'latitude', 'longitude', 'created_at', 'updated_at')->where('rider_id', $rider_id)->orderBy('created_at','DESC')->first();
    }
    public function Rider_detail(){
        return $this->hasOne('App\Model\Rider\Rider_detail');
  
      }
    //   public function bike()
    //   {
    //       return $this->belongsTo('App\Model\Bikes\bike','bike_id');
    //   }
      public function Assign_bike(){
        return $this->hasMany('App\Assign_bike','rider_id');
  
      }
      public function Rider_salary(){
        return $this->hasMany('App\Model\Accounts\Rider_salary','rider_id');
  
      }
      public function Sim_History(){
        return $this->hasMany('App\Model\Sim\Sim_History');
    }
    public function MobileHistory(){
        return $this->hasMany('App\Model\Mobile\MobileHistory');
    }
}
