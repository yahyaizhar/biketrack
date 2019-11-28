<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Bikes\bike;
use App\Model\Bikes\bike_detail;
use App\Model\Client\Client;
use Illuminate\Support\Facades\Hash;
use App\Model\Rider\Rider;
use App\Model\Client\Client_Rider;
use Illuminate\Support\Facades\Storage;
use App\Model\Rider\Rider_Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use carbon\carbon;
use \App\Model\Accounts\Company_Account;
use \App\Model\Accounts\Bike_Accounts;
use \App\Model\Accounts\Rider_Account;
use App\Assign_bike;
use App\insurance_company;
use Arr;

class BillsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function rider_generated_bills(){
        return view('admin.Bills.generated_bills');
    }
}
