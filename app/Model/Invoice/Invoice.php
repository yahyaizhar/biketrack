<?php

namespace App\Model\Invoice;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Rider\Rider;
use Auth;
class Invoice extends Authenticatable
{
    /**
     * Start logging.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // $old_modal=null;

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
        // self::updating(function ($model) {
        //     foreach( $model->toArray() as $key => $value )
        //     {
        //         $old_modal[$key]=$value;
        //         // echo $key;
        //     }
        //     dd($model->invoice_status);
        // });
    }

    // ends logging
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'invoice_amount',
        'month',
        'invoice_date',
        'invoice_due',
        'payment_status',
        'generated_by',
        'tax_method_id',
        'taxable_amount',
        'bank_id',
        'amount_paid',
        'due_balance',
        'received_date',
        'invoice_status',
        'discount_type',
        'discount_amount',
        'attachment',
        'message_on_invoice',
        'billing_address',
        'status'

    ];

    public function Invoice_item()
    {
        return $this->hasMany('App\Model\Invoice\Invoice_item', 'invoice_id');
    }
    public function Invoice_Payment()
    {
        return $this->hasMany('App\Model\Invoice\Invoice_Payment', 'invoice_id');
    }
}
