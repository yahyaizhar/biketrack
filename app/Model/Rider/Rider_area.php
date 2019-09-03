<?php

namespace App\Model\Rider;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Client\Client;
use Spatie\Activitylog\Traits\LogsActivity;



class Rider_area extends Authenticatable
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'name', 'path', 'status','setting',
    ];

    public function Rider(){
        return $this->hasMany('App\Model\Rider\Rider','area_id');
  
      }
}
