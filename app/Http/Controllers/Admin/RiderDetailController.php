<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Rider\Rider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\RiderLocationResourceCollection;
use App\Model\Rider\Rider_Message;
use App\Model\Rider\Rider_Performance_Zomato;
use Illuminate\Support\Facades\Auth;
use App\Model\Rider\Rider_Report;
use App\Model\Rider\Rider_detail;
use App\Model\Bikes\bike;
use App\Model\Sim\Sim;
use App\Model\Sim\Sim_Transaction;
use App\Model\Sim\Sim_History;
use App\Model\Client\Client_Rider;
use App\Model\Client\Client;
use Illuminate\Support\Arr;
use Batch;
use Carbon\Carbon;
use App\Assign_bike;
use App\Model\Accounts\Company_Account;
use App\Model\Accounts\Rider_Account;
use App\Model\Accounts\Income_zomato;
use App\Model\Rider\Trip_Detail;

class RiderDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function view_detail(){
        $riders=Rider::where("active_status","A")->get();
        return view('admin.rider.rider_details',compact('riders'));
    }
    public function get_data_ajax_detail($rider_id,$month){
        //  bike
        $assign_bike_date='';
        $brand='';
        $model='';
        $bike_number='';
      $assign_bike=Assign_bike::where("rider_id",$rider_id)->whereMonth('created_at',$month)->get()->last();
      if (isset( $assign_bike)) {
        $assign_bike_date=Carbon::parse($assign_bike->created_at)->format('d M, Y');
        $bike=bike::where('id',$assign_bike->bike_id)->get()->first();
        if (isset($bike)) {
            $brand=$bike->brand;
            $model=$bike->model;
            $bike_number=$bike->bike_number;
        }
      }
      $salik=Company_Account::where("rider_id",$rider_id)->where("source","Salik")->whereMonth("month",$month)->sum('amount');
      $fuel_expense=Company_Account::where("rider_id",$rider_id)->whereNotNull("fuel_expense_id")->whereMonth("month",$month)->sum('amount');
        // end bike
        // sim
        $allowed_balance='';
        $assign_sim_date='';
        $sim='';
        $sim_company='';
        $sim_number ='';
        $assign_sim=Sim_History::where("rider_id",$rider_id)->whereMonth('created_at',$month)->get()->last();
        if (isset($assign_sim)) {
            $allowed_balance=$assign_sim->allowed_balance;
            $assign_sim_date=Carbon::parse($assign_sim->created_at)->format('d M, Y');
            $sim=Sim::where("id",$assign_sim->sim_id)->get()->first();
            if (isset($sim)) {
                $sim_company=$sim->sim_company;
                $sim_number=$sim->sim_number;
            }
        }
        $sim_useage=Company_Account::where("rider_id",$rider_id)->where("source","Sim Transaction")->whereMonth("month",$month)->sum('amount');
        $sim_Extra_useage=Company_Account::where("rider_id",$rider_id)->where("source","Sim extra usage")->whereMonth("month",$month)->sum('amount');
        // end sim
        return response()->json([
            'assign_bike_date'=>$assign_bike_date,
            'brand'=>$brand,
            'model'=>$model,
            'bike_number'=>$bike_number,
            'salik'=>$salik,
            'fuel_expense'=>$fuel_expense,

            'allowed_balance'=>$allowed_balance,
            'assign_sim_date'=>$assign_sim_date,
            'sim_company'=>$sim_company,
            'sim_number'=>$sim_number,
            'sim_useage'=>$sim_useage,
            'sim_Extra_useage'=>$sim_Extra_useage,
        ]);
    }
    public function Zomato_salary_sheet_view(){
        return view('Zomato_salary_sheet');
    }
    public function zomato_faisla(){
        // $a = Trip_Detail::all();
        // foreach ($a as $b) {
        //     $c = Carbon::parse($b->trip_date)->format('Y-m-d');
        //     $b->trip_date=$c;
        //     $b->save();
        // }

        // return; 
        $month='10';
        $month_date='2019-10-01';
        $time=[];
        $total_hours=Income_zomato::whereMonth('date',$month)->sum('log_in_hours_payable');
        $total_trips=Income_zomato::whereMonth('date',$month)->sum('trips_payable');
        $ncw=Income_zomato::whereMonth('date',$month)->sum('ncw_incentives');
        $tips=Income_zomato::whereMonth('date',$month)->sum('tips_payouts');
        $denials_penalty=Income_zomato::whereMonth('date',$month)->sum('denials_penalty');
        $payout=Income_zomato::whereMonth('date',$month)->sum('total_to_be_paid_out');
        $cod=Income_zomato::whereMonth('date',$month)->sum('mcdonalds_deductions');

        $DC_deduction=Income_zomato::whereMonth('date',$month)->sum('dc_deductions');
        // $payout_total=0;
        // foreach ($payout as $hours) {
        // $obj=[];
        // $obj['log_in_hours_payable'] = $hours['log_in_hours_payable']>286?$obj['log_in_hours_payable']=286 : $hours['log_in_hours_payable'];
        // $obj['trips'] = $hours['trips_payable']>400?$obj['trips']=400 : $hours['trips_payable'];
        // $obj['trips_extra'] = $hours['trips_payable']>400?$obj['trips_extra']=$hours['trips_payable']-400 :$obj['trips_extra']=0;
        // $obj['total_payout']=$obj['log_in_hours_payable']*7.87 +  $obj['trips']*2 + $obj['trips_extra']*4;
        // $payout_total+=$obj['total_payout'];

        // }
         $salik=0;
         $fuel=0;
         $sim=0;
         $bike_rent=0;
        $clients=Client::where("name",'Zomato Food Delivery')->get()->first();
        $client_riders=Client_Rider::where("client_id",$clients->id)->get();
        foreach ($client_riders as $riders) {
            $bike_history = Assign_bike::all();
            $rider_id = $riders->id;
            $history_found = Arr::first($bike_history, function ($item, $key) use ($rider_id, $month_date) {
                $created_at =Carbon::parse($item->created_at)->format('Y-m-d');
                $created_at =Carbon::parse($created_at);
    
                $updated_at =Carbon::parse($item->updated_at)->format('Y-m-d');
                $updated_at =Carbon::parse($updated_at);
                $req_date =Carbon::parse($month_date);
                if($item->status=="active"){ 
                    // mean its still active, we need to match only created at
                    return $item->rider_id == $rider_id && $req_date->greaterThanOrEqualTo($created_at);
                }
                
                return $item->rider_id == $rider_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
            });
            if(isset($history_found)){
                $bike=bike::find($assign_bike->bike_id);  
                $salik_amount=Trip_Detail::whereMonth('trip_date',$month)
                ->where("plate",$bike->id)
                ->sum('amount_aed'); 
                $salik+=$salik_amount; 
            }
        
        $fuel_amount=Company_Account::whereNotNull('fuel_expense_id')
        ->where('rider_id',$riders->rider_id)
        ->whereMonth('month',$month)
        ->sum('amount');
         $fuel+=$fuel_amount;

         $sim_amount=Company_Account::where("source","Sim Transaction")
         ->where('rider_id',$riders->rider_id)
         ->whereMonth('month',$month)
         ->where('type','dr')
         ->whereNotNull('sim_transaction_id')
         ->sum('amount');
         $sim+=$sim_amount;

         $salary=Company_Account::whereNotNull('rider_id')
         ->whereMonth('month',$month)
         ->where('source','salary')
         ->where('type','dr')
         ->sum('amount');
    }
        return response()->json([
        'total_trips'=>round($total_trips,2),
        'total_hours'=>round($total_hours,2), 
        'ncw'=>round($ncw,2), 
        'tips'=>round($tips,2),  
        'cod'=>round($cod,2),  
        'denials_penalty'=>round($denials_penalty,2),   
        'DC_deduction'=>round($DC_deduction,2),   
        'payout'=>round($payout,2),
        'bike_fuel'=>round($fuel,2),
        'salik'=>round($salik,2),
        'sim'=>round($sim,2),
        'salary'=>round($salary,2),
        ]);
    }
    public function profit_zomato(){
        return view('zomato_profit_sheet');
    }
    public function rider_expense_bonus(Request $request)
    {
        $ca = new \App\Model\Accounts\Company_Account;
        $ca->type='dr';
        $ca->amount=$request->amount;
        $ca->month=Carbon::parse($request->get('month'))->format('Y-m-d');
        $ca->rider_id = $request->rider_id;
        $ca->source='400 Trips Acheivement Bonus';
        $ca->save();

        $ra = new \App\Model\Accounts\Rider_Account;
        $ra->type='cr';
        $ra->amount=$request->amount;
        $ra->month=Carbon::parse($request->get('month'))->format('Y-m-d');
        $ra->rider_id = $request->rider_id;
        $ra->source='400 Trips Acheivement Bonus';
        $ra->save();
        
    }
    public function rider_expense_discipline(Request $request)
    {
        $ca = new \App\Model\Accounts\Company_Account;
        $ca->type='dr';
        $ca->amount=$request->amount;
        $ca->month=Carbon::parse($request->get('month'))->format('Y-m-d');
        $ca->rider_id = $request->rider_id;
        $ca->source='Discipline Fine';
        $ca->save();

        $ra = new \App\Model\Accounts\Rider_Account;
        $ra->type='cr_payable';
        $ra->amount=$request->amount;
        $ra->month=Carbon::parse($request->get('month'))->format('Y-m-d');
        $ra->rider_id = $request->rider_id;
        $ra->source='Discipline Fine';
        $ra->save();
        
    }
    public function cash_paid(Request $r){
            $ra = new \App\Model\Accounts\Rider_Account;
            $ra->type='dr';
            $ra->amount=$r->amount;
            $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
            $ra->rider_id = $r->cash_rider_id;
            $ra->source=$r->desc;
            $ra->payment_status="paid";
            $ra->save();
     
}
public function cash_debit_rider(Request $r){

        $ca = new \App\Model\Accounts\Company_Account;
        $ca->type='cr';
        $ca->amount=$r->amount;
        $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->rider_id = $r->cash_rider_id;
        $ca->source=$r->desc;
        $ca->save();
        
        $ra = new \App\Model\Accounts\Rider_Account;
        $ra->type='dr';
        $ra->amount=$r->amount;
        $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ra->rider_id = $r->cash_rider_id;
        $ra->source=$r->desc;
        $ra->save();
    
}
public function cash_credit_rider(Request $r){
        $ca = new \App\Model\Accounts\Company_Account;
        $ca->type='dr';
        $ca->amount=$r->amount;
        $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->rider_id = $r->cash_rider_id;
        $ca->source=$r->desc;
        $ca->save();
        
        $ra = new \App\Model\Accounts\Rider_Account;
        $ra->type='cr';
        $ra->amount=$r->amount;
        $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ra->rider_id = $r->cash_rider_id;
        $ra->source=$r->desc;
        $ra->save();
}
}