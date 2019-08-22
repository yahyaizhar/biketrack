<?php

namespace App\Model\Expense;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Client\Client;

class Company_CD extends Authenticatable
{
    protected $fillable = [
        'type','type_db','type_cr','amount','month','advance_deducted_by','advance_notes','advance_description','salary_total','salary_remaining','salary_recieved','salary_gross',
       ];
}
