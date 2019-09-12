<?php

namespace App\Model\Mobile;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mobile extends Authenticatable
{ 
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model', 'imei', 'purchase_price', 'sale_price', 'payment_type', 'amount_received', 'installment_starting_month', 'installment_ending_month', 'per_month_installment_amount',
    ];
    public function Mobile_Transaction(){ 
        return $this->hasMany('App\Model\Mobile\Mobile_Transaction','mobile_id');
  
      }
      public function Mobile_installment(){ 
        return $this->hasMany('App\Model\Mobile\Mobile_installment','mobile_id');
  
      }
}
