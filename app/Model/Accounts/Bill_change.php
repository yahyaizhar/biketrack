<?php

namespace App\Model\Accounts;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class Bill_change extends Authenticatable
{
    protected $fillable = [
        'type','feed','amount','month','given_date','rider_id','status','settings',
    ];
}
