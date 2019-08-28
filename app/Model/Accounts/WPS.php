<?php

namespace App\Model\Accounts;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class WPS extends Authenticatable
{
    protected $fillable = ['bank_name','rider_id', 'amount', 'status','payment_status','active_status','setting'];
}
