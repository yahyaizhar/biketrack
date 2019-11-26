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
        $sims=Sim::where("active_status","A")->get();
        $bikes=bike::where("active_status","A")->get();
        return view('admin.rider.rider_details',compact('riders', 'sims', 'bikes'));
    }
    public function get_data_ajax_detail($_id,$month, $according_to){


       
        //bike details
        function get_bike_data($_id,$month, $according_to){
            $only_month = Carbon::parse($month)->format('m');
            $rider_id = null;
            $assign_bike_date__start=null;
            $assign_bike_date__end=null;
            $brand='';
            $model='';
            $bike=NULL;
            $bike_number='';
            $salik=0;
            $fuel_expense=0;
            

            $is_bike_found = null;
            $bike_histories = null;
            $bike_history = Assign_bike::all();
            

            $to_match_bike = $according_to=='rider'?'rider_id':($according_to=='bike'?'bike_id':'');
            if($to_match_bike != ''){
                $bike_history_found = Arr::first($bike_history, function ($item, $key) use ($_id, $month,$to_match_bike) {
                    $created_at =Carbon::parse($item->created_at)->format('Y-m-d');
                    $created_at =Carbon::parse($created_at);
        
                    $updated_at =Carbon::parse($item->updated_at)->format('Y-m-d');
                    $updated_at =Carbon::parse($updated_at);
                    $req_date =Carbon::parse($month);
                    if($item->status=="active"){ 
                        // mean its still active, we need to match only created at
                        if($to_match_bike=='bike_id'){
                            return $item->bike_id == $_id && $req_date->greaterThanOrEqualTo($created_at);
                        }
                        return $item->rider_id == $_id && $req_date->greaterThanOrEqualTo($created_at);
                        
                    }
                    if($to_match_bike=='bike_id'){
                        return $item->bike_id == $_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
                    }
                    return $item->rider_id == $_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
                });

                if(isset($bike_history_found)){
                    $is_bike_found = true;
                    $bike_histories = $bike_history_found;
                }else {
                    $is_bike_found = false;
                    $to_find = $to_match_bike=='bike_id'?'bike_id':'rider_id';
                    $assign_bikeBK = Assign_bike::where($to_find , $_id)
                    ->where('status', 'active')->get()->first();
                    if(isset($assign_bikeBK)){
                    $bike_histories = $assign_bikeBK;
                    }
                }
                
                //fetching data
                if (isset( $bike_histories )) {
                    $rider_id = $bike_histories->rider_id;
                    $bike=bike::find($bike_histories->bike_id);
                    $assign_bike_date__start=Carbon::parse($bike_histories->created_at)->format('Y-m-d');
                    $assign_bike_date__end=Carbon::parse($bike_histories->updated_at)->format('Y-m-d');
                    $salik=Company_Account::where("rider_id",$bike_histories->rider_id)->whereNotNull("salik_id")->whereMonth("month",$only_month)->sum('amount');
                    $fuel_expense=Company_Account::where("rider_id",$bike_histories->rider_id)->whereNotNull("fuel_expense_id")->whereMonth("month",$only_month)->sum('amount');
                    if (isset($bike)) {
                        $brand=$bike->brand;
                        $model=$bike->model;
                        $bike_number=$bike->bike_number;
                    }
                }
                //fetching data
            }

            return array(
                'rider_id'=>$rider_id,
                'bike'=>$bike,
                'bike_histories'=>$bike_histories,
                'assign_bike_date__start'=>$assign_bike_date__start,
                'assign_bike_date__end'=>$assign_bike_date__end,
                'is_bike_found'=>$is_bike_found,
                'brand'=>$brand,
                'model'=>$model,
                'bike_number'=>$bike_number,
                'salik'=>$salik,
                'fuel_expense'=>$fuel_expense,
            );  
        }
        //bike_details end

        // sim details
        function get_sim_data($_id,$month, $according_to){
            $only_month = Carbon::parse($month)->format('m');
            $rider_id = null;
            $assign_sim_date__start=null;
            $assign_sim_date__end=null;
            $allowed_balance='';
            $assign_sim_date='';
            $sim=null;
            $sim_company='';
            $sim_number ='';

            $is_sim_found = null;
            $sim_history = Sim_history::all();
            $sim_histories = null;
            $sim_useage=0;
            $sim_Extra_useage=0;

            //function get_sim_data(){}
            $to_match_sim = $according_to=='rider'?'rider_id':($according_to=='sim'?'sim_id':'');
            if($to_match_sim != ''){
                
                $sim_history_found = Arr::first($sim_history, function ($item, $key) use ($_id, $month,$to_match_sim) {
                    $created_at =Carbon::parse($item->created_at)->format('Y-m-d');
                    $created_at =Carbon::parse($created_at);

                    $updated_at =Carbon::parse($item->updated_at)->format('Y-m-d');
                    $updated_at =Carbon::parse($updated_at);
                    $req_date =Carbon::parse($month);
                    if($item->status=="active"){ 
                        // mean its still active, we need to match only created at
                        return $to_match_sim=='sim_id'?$item->sim_id == $_id && $req_date->greaterThanOrEqualTo($created_at):$item->rider_id == $_id && $req_date->greaterThanOrEqualTo($created_at);
                    }
                    
                    return $to_match_sim=='sim_id'?$item->sim_id == $_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at):$item->rider_id == $_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
                });

                if(isset($history_found)){
                    $sim_histories = $history_found;
                    $is_sim_found = true;
                }else {
                    $is_sim_found = false;
                    $to_find = $to_match_sim=='sim_id'?'sim_id':'rider_id';
                    $sim_history = Sim_history::where($to_find, $_id)
                    ->where('status', 'active')->get()->first();
                    if(isset($sim_history)){
                        $sim_histories = $sim_history;
                    }
                }
                
                //fetching data
                if (isset( $sim_histories )) {
                    $rider_id = $sim_histories->rider_id;
                    $allowed_balance=$sim_histories->allowed_balance;
                    $assign_sim_date__start=Carbon::parse($sim_histories->created_at)->format('Y-m-d');
                    $assign_sim_date__end=Carbon::parse($sim_histories->updated_at)->format('Y-m-d');
                    $sim_useage=Company_Account::where("rider_id",$sim_histories->rider_id)->where("source","Sim Transaction")->whereMonth("month",$only_month)->sum('amount');
                    $sim_Extra_useage=Company_Account::where("rider_id",$sim_histories->rider_id)->where("source","Sim extra usage")->whereMonth("month",$only_month)->sum('amount');
                    $sim=Sim::find($sim_histories->sim_id);
                    if (isset($sim)) {
                        $sim_company=$sim->sim_company;
                        $sim_number=$sim->sim_number;
                    }
                }
            }

            return array(
                'rider_id'=>$rider_id,
                'sim_histories'=>$sim_histories,
                'assign_sim_date__start'=>$assign_sim_date__start,
                'assign_sim_date__end'=>$assign_sim_date__end,
                'is_sim_found'=>$is_sim_found,
                'allowed_balance'=>$allowed_balance,
                'sim_company'=>$sim_company,
                'sim_number'=>$sim_number,
                'sim_useage'=>$sim_useage,
                'sim_Extra_useage'=>$sim_Extra_useage,
                'sim'=>$sim
            );  
        }
        // end sim details

        $rider_id=null;
        if($according_to=='rider'){
            $rider_id = $_id;
        }
        else if($according_to=='sim'){
            $sim_id = $_id;
            $sim_data = get_sim_data($sim_id,$month, 'sim');
            $rider_id = $sim_data['rider_id'];
        }
        else if($according_to=='bike'){
            $bike_id = $_id;
            $bike_data = get_bike_data($bike_id,$month, 'bike');
            $rider_id = $bike_data['rider_id'];
        }
        else {
            return response()->json([
                'status'=>0,
                'message'=>'Parameter "$according_to" is wrong.'
            ]);
        }
        $bike_data = get_bike_data($rider_id,$month, 'rider');
        $assign_bike_date__start=$bike_data['assign_bike_date__start'];
        $assign_bike_date__end=$bike_data['assign_bike_date__end'];
        $brand=$bike_data['brand'];
        $model=$bike_data['model'];
        $bike = $bike_data['bike'];
        $bike_number=$bike_data['bike_number'];
        $salik=$bike_data['salik'];;
        $fuel_expense=$bike_data['fuel_expense'];
        $is_bike_found = $bike_data['is_bike_found'];
        $bike_histories = $bike_data['bike_histories'];


        $sim_data = get_sim_data($rider_id,$month, 'rider');
        $allowed_balance=$sim_data['allowed_balance'];
        $assign_sim_date__start=$sim_data['assign_sim_date__start'];
        $assign_sim_date__end=$sim_data['assign_sim_date__end'];
        $sim_company=$sim_data['sim_company'];
        $sim = $sim_data['sim'];
        $sim_number =$sim_data['sim_number'];
        $sim_useage=$sim_data['sim_useage'];
        $sim_Extra_useage=$sim_data['sim_Extra_useage'];
        $is_sim_found = $sim_data['is_sim_found'];
        $sim_histories = $sim_data['sim_histories'];
        
        if($according_to=='rider'){
            $rider= Rider::find($_id);
        }
        else if($according_to=='sim'){
            $sim = Sim::find($_id);
        }
        else if($according_to=='bike'){
            $bike = bike::find($_id);
        }
        
        $rider= Rider::find($rider_id);
        return response()->json([
            'status'=>1,
            'rider'=>$rider,
            'assign_bike_date__start'=>$assign_bike_date__start,
            'assign_bike_date__end'=>$assign_bike_date__end,
            'brand'=>$brand,
            'model'=>$model,
            'bike_number'=>$bike_number,
            'salik'=>$salik,
            'fuel_expense'=>$fuel_expense,
            'bike'=>$bike,

            'allowed_balance'=>$allowed_balance,
            'assign_sim_date__start'=>$assign_sim_date__start,
            'assign_sim_date__end'=>$assign_sim_date__end,
            'sim'=>$sim,
            'sim_company'=>$sim_company,
            'sim_number'=>$sim_number,
            'sim_usage'=>$sim_useage,
            'sim_Extra_usage'=>$sim_Extra_useage,
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
        $month_date='2019-10-31';
        $time=[];
        $total_hours=Income_zomato::whereMonth('date',$month)->sum('log_in_hours_payable');
        $total_trips=Income_zomato::whereMonth('date',$month)->sum('trips_payable');
        $ncw=Income_zomato::whereMonth('date',$month)->sum('ncw_incentives');
        $tips=Income_zomato::whereMonth('date',$month)->sum('tips_payouts');
        $denials_penalty=Income_zomato::whereMonth('date',$month)->sum('denials_penalty');
        $payout=Income_zomato::whereMonth('date',$month)->sum('total_to_be_paid_out');
        $cod=Income_zomato::whereMonth('date',$month)->sum('mcdonalds_deductions');
        

        $DC_deduction=Income_zomato::whereMonth('date',$month)->sum('dc_deductions');
        $salik=Trip_Detail::whereMonth('trip_date',$month)->sum('amount_aed'); 
        $bike_rent=Company_Account::where("source",'Bike Rent')
        ->whereNotNull('rider_id')
        ->whereMonth('month',$month)
        ->sum('amount');
        // $payout_total=0;
        // foreach ($payout as $hours) {
        // $obj=[];
        // $obj['log_in_hours_payable'] = $hours['log_in_hours_payable']>286?$obj['log_in_hours_payable']=286 : $hours['log_in_hours_payable'];
        // $obj['trips'] = $hours['trips_payable']>400?$obj['trips']=400 : $hours['trips_payable'];
        // $obj['trips_extra'] = $hours['trips_payable']>400?$obj['trips_extra']=$hours['trips_payable']-400 :$obj['trips_extra']=0;
        // $obj['total_payout']=$obj['log_in_hours_payable']*7.87 +  $obj['trips']*2 + $obj['trips_extra']*4;
        // $payout_total+=$obj['total_payout'];

        // }
        //  $salik=0;
         $fuel=0;
         $sim=0;
         $bonus=0;
        //  $bike_rent=0;
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
                $bike=bike::find($history_found->bike_id);  
                // $salik_amount=Trip_Detail::whereMonth('trip_date',$month)
                // ->where("plate",$bike->bike_number)
                // ->sum('amount_aed'); 
                // $salik+=$salik_amount; 
            }
        
        $fuel_amount=Company_Account::whereNotNull('fuel_expense_id')
        ->where('rider_id',$riders->rider_id)
        ->whereMonth('month',$month) 
        ->sum('amount');
         $fuel+=$fuel_amount;
         $bon=Company_Account::where('source',"400 Trips Acheivement Bonus")
         ->where('rider_id',$riders->rider_id)
         ->whereMonth('month',$month)
         ->sum('amount');
          $bonus+=$bon;

        //  $_rent=Company_Account::where("source",'Bike Rent')
        // ->where('rider_id','6')
        // ->whereMonth('month',$month)
        // ->sum('amount');
        //  $bike_rent+=$_rent;

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
        'bike_rent'=>round($bike_rent,2),
        'bonus'=>round($bonus,2),

        ]);
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