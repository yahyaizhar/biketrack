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
use App\Model\Accounts\Bike_Fine;
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

    public function BF_index(){
        $riders=Rider::where('active_status','A')->get();
        $bikes=bike::where('active_status', 'A')->get();
        return view('admin.accounts.Bike_Fine.BF_add',compact('bikes','riders'));
    }
    
    public function BF_store(Request $r){
        $bf=new Bike_Fine();
        $bf->rider_id=$r->rider_id;
        $bf->bike_id=$r->bike_id;
        $bf->description='Bike Fine';
        $bf->amount=$r->amount;
        $bf->month=Carbon::parse($r->month)->format('Y-m-d');
        $bf->save();

        $ca = new Company_Account();
        $ca->type='dr';
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->amount=$r->amount;
        $ca->rider_id=$r->rider_id;
        $ca->source='Bike Fine';
        $ca->bike_fine=$bf->id;
        $ca->save();

        return redirect(route('admin.BF_view'));
    }
    public function BF_view()
    { 
        return view('admin.accounts.Bike_Fine.BF_view');
    }
    public function BF_delete($id)
    { 
        $delete_bf=Bike_Fine::find($id);
        $delete_bf->delete();
    }
    public function BF_edit($id){
        $is_readonly=false;
        $bf=Bike_Fine::find($id);
        $bikes=bike::all();
        $riders=Rider::all();
        return view('admin.accounts.Bike_Fine.BF_edit',compact('is_readonly','bf','bikes','riders'));
    }
    public function BF_edit_view($id){
        $is_readonly=true;
        $bf=Bike_Fine::find($id);
        $bikes=bike::all();
        $riders=Rider::all();
        return view('admin.accounts.Bike_Fine.BF_edit',compact('is_readonly','bf','bikes','riders'));
    }
    public function BF_update(Request $r,$id){
        $bf=Bike_Fine::find($id);
        $bf->rider_id=$r->rider_id;
        $bf->bike_id=$r->bike_id;
        $bf->description='Bike Fine';
        $bf->amount=$r->amount;
        $bf->month=Carbon::parse($r->month)->format('Y-m-d');
        $bf->update();

        $ca =Company_Account::firstOrCreate([
                'bike_fine'=>$bf->id
            ]);
            $ca->type='dr';
            $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ca->amount=$r->amount;
            $ca->source='Bike Fine';
            $ca->bike_fine=$bf->id;
            $ca->update();
        return redirect(route('admin.BF_view'));
    }
    public function paid_fine_by_rider($amount,$rider_id,$bike_fine_id,$month){
        $ca = new Company_Account();
        $ca->type='cr';
        $ca->month = Carbon::parse($month)->format('Y-m-d');
        $ca->amount=$amount;
        $ca->rider_id=$rider_id;
        $ca->source='Bike Fine Paid';
        $ca->bike_fine=$bike_fine_id;
        $ca->payment_status='paid';
        $ca->save();

        $ra = new Rider_Account();
        $ra->type='dr';
        $ra->month = Carbon::parse($month)->format('Y-m-d');
        $ra->amount=$amount;
        $ra->rider_id=$rider_id;
        $ra->source='Bike Fine Paid';
        $ra->bike_fine=$bike_fine_id;
        $ra->payment_status='paid';
        $ra->save();

return response()->json([
    'status'=>$ca,
    'ra'=>$ra,

]);
    }
}
