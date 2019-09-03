<?php


namespace App\Model\Sim;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Rider\Rider;
use App\Model\Bikes\bike;
use App\Assign_bike;
use Spatie\Activitylog\Traits\LogsActivity;

class Sim_Transaction extends Authenticatable
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    
    protected $fillable = [
        'sim_id','rider_id','month_year','bill_amount','extra_usage_amount','extra_usage_payment_status','bill_status','status',
    ];
    public function Sim(){
        return $this->belongsTo(Sim::class);
    }
}
