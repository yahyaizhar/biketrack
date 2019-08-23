<?php

namespace App\Model\Accounts;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Id_charge extends Authenticatable
{
    protected $fillable = ['type', 'amount', 'status'];
    public function Rider()
    {
        return $this->belongsTo('App\Model\Rider\Rider');
    }
}
