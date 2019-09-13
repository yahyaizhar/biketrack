<?php

namespace App\Model\Client;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Rider\Rider;

class Client extends Authenticatable
{
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
