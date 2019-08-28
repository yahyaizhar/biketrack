<?php

namespace App\Model\Rider;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Client\Client;

class Rider extends Authenticatable
{
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
        return $this->belongsToMany(Client::class, 'client_riders', 'rider_id', 'client_id')->withTimestamps();
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
}
