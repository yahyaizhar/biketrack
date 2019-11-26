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
    public function client_total_expense($id){
        $client=Client::find($id);
        return view('client_salary_sheet',compact('client'));
    }
    public function summary_month($month,$client_id){
        $client=Client::where("id",$client_id)->get()->first();
        $client_riders=Client_Rider::where('client_id', $client->id)->get();
        $total_hours_client=0;
        $total_trips_client=0;
        $trips=0;
        $hours=0;
        $aed_trips=0;
        $aed_hours=0;
        $bonus=0;
        $bike_rent=0;
        $fuel=0;
        $salik=0;
        $salik_extra=0;
        $sim=0;
        $sim_extra=0;
        $total_salik=0;
        $total_sim=0;
        

        foreach ($client_riders as $riders) {
            $total_hours_client+=Income_zomato::whereMonth('date',$month)
            ->where("rider_id",$riders->rider_id)
            ->sum('log_in_hours_payable');
            $hours_client=$total_hours_client*6;

            $total_trips_client+=Income_zomato::whereMonth('date',$month)
            ->where("rider_id",$riders->rider_id)
            ->sum('trips_payable');
            $trips_client=$total_trips_client*6.75;

            $trips+=Income_zomato::whereMonth('date',$month)
            ->where("rider_id",$riders->rider_id)
            ->sum('trips_payable');
            $hours+=Income_zomato::whereMonth('date',$month)
            ->where("rider_id",$riders->rider_id)
            ->sum('log_in_hours_payable');
            $_trips=Income_zomato::whereMonth('date',$month)
            ->where("rider_id",$riders->rider_id)
            ->sum('trips_payable');
            if ($_trips>400) {
                $extra_trips=($_trips-400)*4;
                $remain_trips=400*2;
                $aed_trips+=$extra_trips+$remain_trips;
            }
            else{
                $remain_trips=$_trips*2;
                $aed_trips+=$remain_trips;
            }
            $_hours=Income_zomato::whereMonth('date',$month)
            ->where("rider_id",$riders->rider_id)
            ->sum('log_in_hours_payable');
            if ($_hours>286) {
                $_hours=286;
            }
            $aed_hours+=$_hours*7.87;
            $bon=Company_Account::where('source',"400 Trips Acheivement Bonus")
            ->where('rider_id',$riders->rider_id)
            ->whereMonth('month',$month)
            ->sum('amount');
            $bonus+=$bon;

            $bike_rent+=Company_Account::where("source",'Bike Rent')
            ->where("rider_id",$riders->rider_id)
            ->whereMonth('month',$month)
            ->sum('amount');

            $fuel+=Company_Account::whereNotNull('fuel_expense_id')
            ->where('rider_id',$riders->rider_id)
            ->whereMonth('month',$month) 
            ->sum('amount');
            $sim_amount=Company_Account::where("source","Sim Transaction")
            ->where('rider_id',$riders->rider_id)
            ->whereMonth('month',$month)
            ->where('type','dr')
            ->whereNotNull('sim_transaction_id')
            ->sum('amount');
            $sim+=$sim_amount;
            $sim_extra_amount=Company_Account::where("source","Sim extra usage")
            ->where('rider_id',$riders->rider_id)
            ->whereMonth('month',$month)
            ->whereNotNull('sim_transaction_id')
            ->sum('amount');
            $sim_extra+=$sim_extra_amount;
           
            $salik_amount=Company_Account::where("source","Salik")
            ->where('rider_id',$riders->rider_id)
            ->whereMonth('month',$month)
            ->whereNotNull('salik_id')
            ->sum('amount');
            $salik+=$salik_amount;
            $salik_extra_amount=Company_Account::where("source","Salik Extra")
            ->where('rider_id',$riders->rider_id)
            ->whereMonth('month',$month)
            ->whereNotNull('salik_id')
            ->sum('amount');
            $salik_extra+=$salik_extra_amount;
            $total_salik=$salik-$salik_extra;
            $total_sim=$sim-$sim_extra;

        }
        return response()->json([
            'aed_hours_client'=>$hours_client,
            'aed_trips_client'=>$trips_client,
            'sum_1'=>round($hours_client+$trips_client,2),

            'trips'=>$trips,
            'hours'=>$hours,
            'aed_trips'=>$aed_trips,
            'aed_hours'=>$aed_hours,
            'bonus'=>$bonus,
            'sum_2'=>round($aed_trips+$aed_hours+$bonus,2),
           
            'bike_rent'=>$bike_rent,
            'fuel'=>$fuel,
            'salik'=>$salik,
            'salik_extra'=>$salik-$salik_extra,
            'sim'=>$sim,
            'sim_extra'=>$sim-$sim_extra,
            'sum_3'=>round($bike_rent+$fuel+$total_salik+$total_sim,2),
            
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