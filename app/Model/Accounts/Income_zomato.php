<?php

namespace App\Model\Accounts;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Income_zomato extends Authenticatable
{


    protected $fillable = [
        'feid','rider_id','log_in_hours_payable','total_to_be_paid_out',
        'amount_for_login_hours','amount_to_be_paid_against_orders_completed','ncw_incentives','tips_payouts','dc_deductions',
        'mcdonalds_deductions', 'date'
    ];
}
