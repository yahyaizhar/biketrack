<?php

namespace App\Model\Accounts;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Maintenance extends Authenticatable
{
    protected $fillable = ['maintenance_type','accident_payment_status', 'workshop_id','bike_id', 'amount', 'status'];
}
