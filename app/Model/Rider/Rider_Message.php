<?php

namespace App\Model\Rider;

use Illuminate\Database\Eloquent\Model;
use App\Model\Admin\Admin;

class Rider_Message extends Model
{


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
