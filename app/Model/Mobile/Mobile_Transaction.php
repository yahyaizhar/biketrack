<?php

namespace App\Model\Mobile;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mobile_Transaction extends Authenticatable
{
    

    protected $fillable = [
       'mobile_id','month','sale_price','amount_recieved','bill_status','remaining_amount','per_month_installment_amount','status'
    ];
}
