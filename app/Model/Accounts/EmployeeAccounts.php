<?php

namespace App\Model\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
class EmployeeAccounts extends Authenticatable
{
    protected $fillable = [
       'type',
       'amount',
       'month',
       'given_date',
       'source',
       'payment_status',
       'employee_id',
       'active_status',
       'sim_transaction_id',
       
    ];
}
