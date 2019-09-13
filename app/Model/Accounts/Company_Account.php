<?php

namespace App\Model\Accounts;


use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Company_Account extends Authenticatable
{


    protected $fillable = [
        'type', 
        'month',
        'amount',
        'rider_id',
        'payment_status',
        'advance_return_id',
        'id_charge_id',
        'wps_id',
        'fuel_expense_id',
        'maintenance_id',
        'edirham_id',
        'company_expense_id',
        'salik_id',
        'sim_transaction_id',
        'status',
    ];
}
