<?php

namespace App\Model\Client;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Rider\Rider;
use Auth;
class Client extends Authenticatable
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
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    public function riders()
    {
        return $this->belongsToMany(Rider::class, 'client_riders', 'client_id', 'rider_id')->withPivot('client_id', 'rider_id', 'created_at', 'updated_at', 'client_rider_id');
    }
    public function getRiders()
    {
        return $this->belongsToMany(Rider::class, 'client_riders', 'client_id', 'rider_id')->where('client_riders.status', 1)->orderBy('created_at','DESC');
    }
}
