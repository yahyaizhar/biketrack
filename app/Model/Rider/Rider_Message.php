<?php

namespace App\Model\Rider;

use Illuminate\Database\Eloquent\Model;
use App\Model\Admin\Admin;
use Spatie\Activitylog\Traits\LogsActivity;

class Rider_Message extends Model
{
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;

    protected $table = 'rider_messages';
    
    public function rider()
    {
        return $this->belongsTo(Rider::class, 'rider_id');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
