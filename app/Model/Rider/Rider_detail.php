<?php

namespace App\Model\Rider;

use Illuminate\Database\Eloquent\Model;
use Auth;
class Rider_detail extends Model
{
    /**
     * Start logging.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // auto-sets values on creation
        self::created(function($model){
            $activity_model = new \App\Log_activity;
            $activity_model->description="created";
            $activity_model->subject_id=$model->id;
            $activity_model->subject_type=get_class($model);
            $activity_model->causer_id=Auth::user()->id;
            $activity_model->causer_type=get_class(Auth::user());
            $activity_model->save();
        });

         // auto-sets values on creation
         self::updated(function($model){
            $activity_model = new \App\Log_activity;
            $desc = 'updated';
            if(isset($model->active_status)){
                if($model->active_status=='D'){
                    $desc = 'soft_deleted';
                }
            }
            $activity_model->description=$desc;
            $activity_model->subject_id=$model->id;
            $activity_model->subject_type=get_class($model);
            $activity_model->causer_id=Auth::user()->id;
            $activity_model->causer_type=get_class(Auth::user());
            $activity_model->save();
        });
    }
    // ends logging

    protected $fillable = [
        'rider_id','salary','salik_amount','passport_document_image','agreement_image','is_guarantee','other_details','date_of_joining','passport_collected','empoloyee_reference','other_passport_given','not_given','official_given_number','official_sim_given_date','passport_image','passport_image_back','passport_expiry','emirate_image','emirate_image_back','emirate_id','visa_image','visa_image_back','visa_expiry','licence_image','licence_image_back','licence_expiry',
    ];

    public function Rider_detail() 
    {
        return $this->belongsTo('App\Model\Rider\Rider_detail','rider_id');
    }

}
