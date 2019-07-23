<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assign_bike extends Model
{
    protected $fillable = [
        'rider_id', 'bike_id', 'status','settings',
    ];

}
