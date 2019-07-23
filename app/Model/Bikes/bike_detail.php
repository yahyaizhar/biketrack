<?php


namespace App\Model\Bikes;

use Illuminate\Database\Eloquent\Model;

class bike_detail extends Model
{
    protected $fillable = [
        'bikes_id', 'registration_number','settings',
        ];
    
}
