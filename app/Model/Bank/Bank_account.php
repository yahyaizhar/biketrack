<?php

namespace App\Model\Bank;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class Bank_account extends Authenticatable
{
    protected $fillable = [
        'name','account_number',
    ];
}
