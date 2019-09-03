<?php

namespace App\Model\Accounts;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;

class WPS extends Authenticatable
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;

    protected $fillable = ['bank_name', 'month','rider_id', 'amount', 'status','payment_status','active_status','setting'];
    public function Rider()
    {
        return $this->belongsTo('App\Model\Rider\Rider');
    }
}
