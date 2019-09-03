<?php


namespace App\Model\Bikes;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class bike_detail extends Model
{
    
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    
    protected $fillable = [
        'bikes_id', 'registration_number','settings',
        ];
    
}
