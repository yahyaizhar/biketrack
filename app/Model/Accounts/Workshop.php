<?php

namespace App\Model\Accounts;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;


class Workshop extends Authenticatable
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;

    protected $fillable = ['name', 'address', 'status'];
    /**
     * The roles that belong to the user.
     */
    public function Bike()
    {
        return $this->belongsToMany('App\Model\Bikes\bike', 'workshops_bikes', 'workshop_id', 'bike_id');
    }
}
