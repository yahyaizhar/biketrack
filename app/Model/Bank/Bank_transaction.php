<?php

namespace App\Model\Bank;

use Illuminate\Database\Eloquent\Model;

class Bank_transaction extends Model
{
    protected $fillable = [
        'bank_id',
        'type',
        'amount',
        'source',
        'source_id',
        'created_by'
    ];
}
