<?php

namespace App\Model\Rider;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Rider_Report extends Model
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;

    protected $table = 'rider_reports';
    public function rider()
    {
        return $this->belongsTo(Rider::class, 'rider_id');
    }
}
