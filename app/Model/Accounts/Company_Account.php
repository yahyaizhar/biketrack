<?php

namespace App\Model\Accounts;


use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company_Account extends Authenticatable
{
    protected $fillable = [
        'type','amount','rider_id','rider_advance_id','client_id','bike_expense_id','fine_id','salik_id','salary_id','sim_transaction_id','income_id','investment_id','mobile_installment_id','status','active_status','setting'
       ];
}
