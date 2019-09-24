<?php

namespace App\Model\Rider;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Client\Client; 


use Illuminate\Database\Eloquent\Model;

class Trip_Detail extends Authenticatable
{

    
    protected $fillable = [
        'transaction_id','rider_id','active_status','trip_date','trip_time','transaction_post_date','toll_gate','direction','tag_number','plate','amount_AED',
    ];
}
 