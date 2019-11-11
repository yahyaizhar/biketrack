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
use App\Model\Accounts\Rider_salary;
use App\Model\Accounts\Id_charge;
use App\Model\Accounts\Workshop;
use App\Model\Accounts\Maintenance;
use App\Model\Accounts\Rider_Account;
use App\Model\Accounts\Edirham;
use Carbon\Carbon;
use App\Model\Rider\Rider_detail;
use App\Model\Accounts\Fuel_Expense;
use App\Model\Accounts\Client_Income;
use App\Model\Accounts\Income_zomato;
use Arr;
use Batch;
use App\Model\Accounts\Company_Account;
use App\Assign_bike;
use App\Log_activity;


class KRController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function account_view(){
        $riders = Rider::where('active_status', 'A')->get();
        return view('admin.KR_Bikes.accounts',compact('riders')); 
    }
     
    public function activity_view(){
        return view('activity_log_view');
    }
    public function delete_activity_log($id){
    $la=Log_activity::find($id);
    $la->active_status="D";
    $la->save();
    return response()->json([
        'status' => true
    ]);
    }
    public function gov_tax(){
        return view('tax');
    }
}
