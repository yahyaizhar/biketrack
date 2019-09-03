<?php

namespace App\Model\Sim;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Rider\Rider;
use App\Model\Bikes\bike;
use App\Assign_bike;
use Spatie\Activitylog\Traits\LogsActivity;

class Sim_History extends Authenticatable
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'sim_id','return_date','allowed_balance','given_date','return_date','status',
       ];

       public function Rider(){
        return $this->belongsTo(Rider::class);
    }
    public function Sim(){
        return $this->belongsTo(Sim::class);
    }
}
