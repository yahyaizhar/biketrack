<?php

namespace App\Model\Mobile;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mobile_installment extends Authenticatable
{
    protected $fillable = [
        'mobile_id', 'installment_month', 'installment_amount', 
    ];
}
