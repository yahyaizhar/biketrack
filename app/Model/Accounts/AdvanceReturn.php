<?php

namespace App\Model\Accounts;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdvanceReturn extends Authenticatable
{
   

    protected $fillable = [
        'type','rider_id', 'month', 'amount', 'status','payment_status','active_status','setting'
    ];

    
   
}
