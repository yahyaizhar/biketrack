<?php

namespace App\Model\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
class CompanyExpenseType extends Model
{
    protected $fillable = [
        'type_name',
        'status',
     ];
}
