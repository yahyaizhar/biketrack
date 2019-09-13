<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Assign_bike extends Model
{

    
    protected $fillable = [
        'rider_id', 'bike_id', 'status','settings',
    ];
    public function Rider(){
        return $this->belongsTo(Rider::class);
    }
    public function bike(){
        return $this->belongsTo('App\Model\Bikes\bike');
    }
}
