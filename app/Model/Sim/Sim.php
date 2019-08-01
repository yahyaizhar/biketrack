<?php

namespace App\Model\Sim;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Rider\Rider;
use App\Model\Bikes\bike;
use App\Assign_bike;

use Illuminate\Database\Eloquent\Model;

class Sim extends Authenticatable
{
    protected $fillable = [
    'sim_number','sim_company','status',
    ];
    public function Sim_History(){
        return $this->hasMany('App\Model\Sim\Sim_History');
    }
    public function Sim_Transaction(){
        return $this->hasMany('App\Model\Sim\Sim_Transaction');
    }
}
