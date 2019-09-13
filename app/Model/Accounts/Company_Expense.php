<?php

namespace App\Model\Accounts;


use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Company_Expense extends Authenticatable
{
    
       
    protected $fillable = [
        'amount', 'rider_id','month','description','status','active_status','setting'
       ];
}
