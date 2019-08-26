<?php

namespace App\Model\Accounts;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Fuel_Expense extends Authenticatable
{
    protected $fillable = ['type', 'amount', 'status','active_status'];
}
