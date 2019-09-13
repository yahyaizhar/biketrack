<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class New_comer extends Authenticatable
{


    protected $fillable = [
        'name','kingriders_interview','whatsapp_number','education','licence_issue_date','passport_reason','experience_input', 'phone_number','priority', 'nationality','source_of_contact','experiance','passport_status','interview','interview_status','interview_date','interview_By','joining_date','why_rejected','overall_remarks',
    ];
}
