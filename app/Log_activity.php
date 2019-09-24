<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log_activity extends Model
{
    protected $fillable = [
        'description', 'subject_id', 'subject_type', 'causer_id', 'causer_type','settings',
    ];
}
