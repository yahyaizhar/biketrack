<?php

namespace App\Model\Rider;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Client\Client;

use Illuminate\Database\Eloquent\Model;

class Rider_Performance_Zomato extends Authenticatable
{
    protected $fillable = [
        'date', 'feid', 'trips','import_id','adt','average_pickup_time','average_drop_time','loged_in_during_shift_time','total_loged_in_hours','cod_orders','cod_amount',
    ];
    public function rider()
    {
        return $this->belongsTo(Rider::class, 'rider_id');
    }
}
