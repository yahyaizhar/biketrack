<?php

namespace App\Model\Mobile;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mobile extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model', 'imei', 'purchase_price', 'sale_price', 'payment_type', 'amount_received', 'installment_starting_month', 'installment_ending_month', 'per_month_installment_amount',
    ];

}
