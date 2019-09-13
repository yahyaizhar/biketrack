<?php

namespace App\Model\Accounts;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Edirham extends Authenticatable
{


    protected $fillable = ['amount', 'month', 'status','active_status']; 
}
