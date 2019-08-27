<?php

namespace App\Model\Rider;

use Illuminate\Database\Eloquent\Model;

class Rider_detail extends Model
{
    protected $fillable = [
        'rider_id','passport_document_image','agreement_image','is_guarantee','other_details','date_of_joining','passport_collected','empoloyee_reference','other_passport_given','not_given','official_given_number','official_sim_given_date','passport_image','passport_image_back','passport_expiry','emirate_image','emirate_image_back','emirate_id','visa_image','visa_image_back','visa_expiry','licence_image','licence_image_back','licence_expiry',
    ];

    public function Rider_detail() 
    {
        return $this->belongsTo('App\Model\Rider\Rider_detail','rider_id');
    }

}
