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
use App\Model\Accounts\Absent_detail;
use App\Model\Zomato\Riders_Payouts_By_Days;


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
        $bf->given_date=Carbon::parse($r->given_date)->format('Y-m-d');
        $bf->month=Carbon::parse($r->month)->format('Y-m-d');
        $bf->save();

        $ca = new Company_Account();
        $ca->type='dr';
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->given_date=Carbon::parse($r->given_date)->format('Y-m-d');
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
        $bf->given_date=Carbon::parse($r->given_date)->format('Y-m-d');
        $bf->update();

        $ca=Company_Account::where('bike_fine',$bf->id)->get();
        foreach ($ca as $value) {
            if ($value->source=='Bike Fine') {
                $value->type='dr';
                $value->source='Bike Fine';
            }if($value->source=='Bike Fine Paid'){
                $value->type='cr';
                $value->source='Bike Fine Paid';
            }
            $value->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
            $value->given_date=Carbon::parse($r->given_date)->format('Y-m-d');
            $value->amount=$r->amount;
            $value->bike_fine=$bf->id;
            $value->rider_id=$r->rider_id;
            $value->update();
        }
        $ra =Rider_Account::firstOrCreate([
            'bike_fine'=>$bf->id
        ]);
        $ra->type='dr';
        $ra->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
        $ra->given_date=Carbon::parse($r->given_date)->format('Y-m-d');
        $ra->amount=$r->amount;
        $ra->rider_id=$r->rider_id;
        $ra->source='Bike Fine Paid';
        $ra->bike_fine=$bf->id;
        $ra->payment_status='pending';
        $ra->save();
            
        return redirect(route('admin.BF_edit_view',$bf->id));
    }
    public function paid_fine_by_rider($amount,$rider_id,$bike_fine_id,$month,$given_date){
        $ca = new Company_Account();
        $ca->type='cr';
        $ca->month = Carbon::parse($month)->format('Y-m-d');
        $ca->given_date = Carbon::parse($given_date)->format('Y-m-d');
        $ca->amount=$amount;
        $ca->rider_id=$rider_id;
        $ca->source='Bike Fine Paid';
        $ca->bike_fine=$bike_fine_id;
        $ca->payment_status='paid';
        $ca->save();

        $ra = new Rider_Account();
        $ra->type='dr';
        $ra->month = Carbon::parse($month)->format('Y-m-d');
        $ra->given_date = Carbon::parse($given_date)->format('Y-m-d');
        $ra->amount=$amount;
        $ra->rider_id=$rider_id;
        $ra->source='Bike Fine Paid';
        $ra->bike_fine=$bike_fine_id;
        $ra->payment_status='pending';
        $ra->save();

        return response()->json([
            'status'=>$ca,
            'ra'=>$ra,

        ]);
    }

    public function absent_detail(){
        $riders=Rider::where("active_status","A")->get();
        return view('admin.accounts.Rider_Debit.absent_form',compact('riders'));
    }

    public function absent_detail_store(Request $request){
        $absent_detail=new Absent_detail;
        $already_absent_detail = Absent_detail::where(['rider_id'=>$request->rider_id, 'absent_date'=>Carbon::parse($request->absent_date)->format('Y-m-d')])
        ->get()
        ->first();
        if(isset($already_absent_detail)){
            $absent_detail=$already_absent_detail;
        }
        $absent_detail->rider_id=$request->rider_id;
        $absent_detail->absent_reason=$request->absent_reason;
        $absent_detail->absent_date=carbon::parse($request->absent_date)->format("Y-m-d");
        $absent_detail->email_sent=$request->email_sent;
        $absent_detail->approval_status=$request->approval_status;
        if($request->hasFile('document_image'))
        {
            $filename = $request->document_image->getClientOriginalName();
            $filesize = $request->document_image->getClientSize();
            $filepath = Storage::putfile('public/uploads/riders/absent_document_image', $request->file('document_image'));
            $absent_detail->document_image = $filepath;
        }
        $check_zomato_payout=Riders_Payouts_By_Days::where("rider_id",$request->rider_id)->where("date", $absent_detail->absent_date)->get()->first();
        if (isset($check_zomato_payout)) {
        if ($request->approval_status=="accepted") {
            $rpbd=Riders_Payouts_By_Days::where("rider_id",$request->rider_id)->where("date", $absent_detail->absent_date)->get()->first();
            if ($rpbd->absent_status!="Approved") {
                $absent_detail->save();
                $rpbd->absent_status="Approved";
                if(isset($rpbd->absent_fine_id)){
                    $ra =Rider_Account::where("kingrider_fine_id",$rpbd->absent_fine_id)->get()->first();
                    if(isset($ra)) $ra->delete();

                    $ca =Company_Account::where("kingrider_fine_id",$rpbd->absent_fine_id)->get()->first();
                    if (isset($ca)) $ca->delete();
                    
                }
                $rpbd->absent_fine_id=null;
                $rpbd->absent_detail_status="1";
                $income_id=$rpbd->zomato_income_id;
                $rpbd->save();
                $income_zomato=Income_zomato::find($income_id);
                if ($income_zomato->approve_absents!=0 || $income_zomato->approve_absents!=null) {
                    $income_zomato->approve_absents-=1;
                }
                $income_zomato->save();
            }
        }
        if ($request->approval_status=="rejected") {
            $rpbd=Riders_Payouts_By_Days::where("rider_id",$request->rider_id)->where("date", $absent_detail->absent_date)->get()->first();
            if ($rpbd->absent_status!="Rejected") {
                $absent_detail->save();
                $rpbd->absent_status="Rejected";
                $rpbd->absent_fine_id=$rpbd->id;
                $income_id=$rpbd->zomato_income_id;
                $rpbd->absent_detail_status="1";
                $rpbd->save();
                $income_zomato=Income_zomato::find($income_id);
                $income_zomato->approve_absents+=1;
                $income_zomato->save();

                $amt=100;
                $ra =new Rider_Account;
                $ra->type='dr';
                $ra->month = Carbon::parse($absent_detail->absent_date)->startOfMonth()->format('Y-m-d');
                $ra->given_date=Carbon::now()->format('Y-m-d');
                $ra->amount=round($amt,2);
                $ra->rider_id=$request->rider_id;
                $ra->source='Absent Fine (on '.Carbon::parse($absent_detail->absent_date)->format('Y-m-d').')';
                $ra->payment_status='pending';
                $ra->kingrider_fine_id=$rpbd->id; 
                $ra->save();

                $ca =new Company_Account;
                $ca->type='cr';
                $ca->month = Carbon::parse($absent_detail->absent_date)->startOfMonth()->format('Y-m-d');
                $ca->given_date=Carbon::now()->format('Y-m-d');
                $ca->amount=round($amt,2);
                $ca->rider_id=$request->rider_id;
                $ca->source='Absent Fine (on '.Carbon::parse($absent_detail->absent_date)->format('Y-m-d').')';
                $ca->payment_status='pending';
                $ca->kingrider_fine_id=$rpbd->id; 
                $ca->save();
            }
        }
    }
    else{
        $absent_detail->save();
    }
        return redirect(route('account.absent_detail'));
    }
    public function absent_detail_ajax($month,$rider_id,$_date){
        $rider_name="";
        $absnt_date="";
        $absnt_reason="";
        $absnt_email="";
        $absnt_app_status="";

        $absent_detail=Absent_detail::where("rider_id",$rider_id)
        ->where("absent_date",$_date)
        ->get()
        ->first();
        if (isset($absent_detail)) {
            $absnt_date=$absent_detail->absent_date;
            $absnt_reason=$absent_detail->absent_reason;
            $absnt_email=$absent_detail->email_sent;
            $absnt_app_status=$absent_detail->approval_status;
            $rider=Rider::find($absent_detail->rider_id);
            if (isset($rider)) {
                $rider_name=$rider->name;
            }
            $status="1";
        }
        if (!isset($absent_detail)) {
            $status="0";
        }
        return response()->json([
            'rider_name'=>$rider_name,
            'absnt_date'=>$absnt_date,
            'absnt_reason'=>$absnt_reason,
            'absnt_email'=>$absnt_email,
            'absnt_app_status'=>$absnt_app_status,
            'status'=>$status,
        ]);
    }
    public function check_payout($month,$rider_id,$date){
        $income_zomato=Income_zomato::where("rider_id",$rider_id)
        ->whereMonth("date",$month)
        ->get()
        ->first();
        $is_payout="0";
        if (isset($income_zomato)) {
            $is_payout="1";
        }
        $rpbd=Riders_Payouts_By_Days::where("rider_id",$rider_id)->where("date", $date)->get()->first();
        $day_status='';
        if (isset($rpbd)) {
            if($rpbd->off_days_status=="present" || $rpbd->off_days_status=="extraday" || $rpbd->off_days_status=="weeklyoff"){
                if ($rpbd->off_days_status=="present") {
                   $day_status="present"; 
                }
                if ($rpbd->off_days_status=="extraday") {
                    $day_status="extraday";
                }
                if ($rpbd->off_days_status=="weeklyoff") {
                    $day_status="weeklyoff";
                }
            }
        }
        return response()->json([
            'month'=>$month,
            'rider_id'=>$rider_id,
            'is_payout'=>$is_payout,
            'day_status'=>$day_status,
        ]);
    }
    public function change_payout_data(Request $request,$rider_id,$month){
        $data=$request->data;
        foreach ($data as $value) {
            $_date=carbon::parse($value['date'])->format('Y-m-d');
            $rpbd=Riders_Payouts_By_Days::where("rider_id",$rider_id)->where("date",$_date)->get()->first();
            if (isset($rpbd)) {
                $rpbd->login_hours=$value['hours'];
                $rpbd->trips=$value['trip'];
                $rpbd->update();
            }
        }
        return response()->json([
            'rider_id'=>$rider_id,
            'month'=>$month,
        ]);
    }
}
