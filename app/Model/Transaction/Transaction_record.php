<?php

namespace App\Model\Transaction;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Rider\Rider;
use App\Model\Bikes\bike;
use App\Assign_bike;
use Auth;

class Transaction_record extends Authenticatable
{
    protected $fillable = [
        'type',
        'bank_id',
        'desc',
        'amount',
        'payment_type',
        'date',
        'month',
       ];
}
