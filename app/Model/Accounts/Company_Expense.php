<?php

namespace App\Model\Accounts;


use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;

class Company_Expense extends Authenticatable
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
       
    protected $fillable = [
        'amount', 'rider_id','month','description','status','active_status','setting'
       ];
}
