<?php

namespace App\Model\Accounts;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;



class Client_Income extends Authenticatable
{

    
    protected $fillable = [
        'client_id', 'month', 'amount', 'status','active_status','rider_id'
    ];
}
