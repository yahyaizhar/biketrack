<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Assign_bike extends Model
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    
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
