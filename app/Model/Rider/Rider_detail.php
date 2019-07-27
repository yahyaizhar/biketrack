<?php

namespace App\Model\Rider;

use Illuminate\Database\Eloquent\Model;

class Rider_detail extends Model
{
    protected $fillable = [
        'rider_id','other_details','date_of_joining','passport_collected','official_given_number','official_sim_given_date','passport_image','passport_expiry','emirate_image','emirate_id','visa_image','visa_expiry','licence_image','licence_expiry',
    ];

    public function Rider_detail()
    {
        return $this->belongsTo('App\Model\Rider\Rider_detail','rider_id');
    }

}
