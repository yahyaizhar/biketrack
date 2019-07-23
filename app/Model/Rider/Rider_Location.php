<?php

namespace App\Model\Rider;

use Illuminate\Database\Eloquent\Model;

class Rider_Location extends Model
{
    //
    protected $table = 'rider_locations';
    public function rider()
    {
        return $this->belongsTo(Rider::class, 'rider_id');
    }
}
