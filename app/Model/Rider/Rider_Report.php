<?php

namespace App\Model\Rider;

use Illuminate\Database\Eloquent\Model;

class Rider_Report extends Model
{
    //
    protected $table = 'rider_reports';
    public function rider()
    {
        return $this->belongsTo(Rider::class, 'rider_id');
    }
}
