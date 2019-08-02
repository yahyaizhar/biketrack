<?php


namespace App\Model\Bikes;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Rider\Rider;
use App\Model\Bikes\bike;
use App\Assign_bike;



class bike extends Authenticatable
{
    protected $fillable = [
        'model','brand','mulkiya_number','mulkiya_expiry','mulkiya_picture','mulkiya_picture_back','bike_number','rider_id','availability','other','status',
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
