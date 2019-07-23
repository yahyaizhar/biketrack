<?php

namespace App\Model\Rider;

use Illuminate\Database\Eloquent\Model;

class Rider_Online_Time extends Model
{
    //
    protected $table = 'rider_online_times';
    public function rider()
    {
        return $this->belongsTo(Rider::class, 'rider_id');
    }
}
