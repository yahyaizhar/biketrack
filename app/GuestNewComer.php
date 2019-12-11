<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Model\Rider\Rider_Message;
use Auth;

class GuestNewComer extends Authenticatable
{
    protected $fillable = [
        'newcommer_image','full_name','nationality','phone_number','national_id_card_number','whatsapp_number','education', 'license_check','license_number', 'licence_issue_date','license_image','experiance','passport_status','passport_number','passport_image','current_residence','current_residence_countries','source','overall_remarks'
    ];
}
