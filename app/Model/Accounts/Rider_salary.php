<?php

namespace App\Model\Accounts;


use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;

class Rider_salary extends Authenticatable
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'rider_id','total_salary','gross_salary','remaining_salary','recieved_salary','paid_by','status','settings', 'month',
       ];
       public function Rider()
         {
             return $this->belongsTo('App\Model\Rider\Rider');
         }
}
