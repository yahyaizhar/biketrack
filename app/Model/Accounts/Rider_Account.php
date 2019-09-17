<?php

namespace App\Model\Accounts;


use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;


class Rider_Account extends Authenticatable
{

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // auto-sets values on creation
        static::creating(function ($query) {
            $query->created_by = Auth::user()->id;
        });
    }
    protected $fillable = [
        'type', 'month',
        'amount',
        'p_id',
        'rider_id',
        'payment_status',
        'advance_return_id',
        'id_charge_id',
        'wps_id',
        'fuel_expense_id',
        'maintenance_id',
        'edirham_id',
        'company_expense_id',
        'salik_id',
        'sim_transaction_id',
        'status',
    ];
}
