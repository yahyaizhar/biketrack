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
        $time=[];
        $start_month='01-09-'.Carbon::now()->format('Y');
        $end_month='31-09-'.Carbon::now()->format('Y');
        $payout=Income_zomato::whereNotNull('rider_id')->whereMonth('date','09')->get();
        $payout_total=0;
        foreach ($payout as $hours) {
        $obj=[];
        $obj['log_in_hours_payable'] = $hours['log_in_hours_payable']>286?$obj['log_in_hours_payable']=286 : $hours['log_in_hours_payable'];
        $obj['trips'] = $hours['trips_payable']>400?$obj['trips']=400 : $hours['trips_payable'];
        $obj['trips_extra'] = $hours['trips_payable']>400?$obj['trips_extra']=$hours['trips_payable']-400 :$obj['trips_extra']=0;
        $obj['total_payout']=$obj['log_in_hours_payable']*7.87 +  $obj['trips']*2 + $obj['trips_extra']*4;
        $payout_total+=$obj['total_payout'];

        }
         $salik=0;
         $fuel=0;
         $sim=0;
         $bike_rent=0;
        $clients=Client::where("name",'Zomato Food Delivery')->get()->first();
        $client_riders=Client_Rider::where("client_id",$clients->id)->get();
        foreach ($client_riders as $riders) {
            $assign_bike=Assign_bike::where('rider_id',$riders->rider_id)->where('status','active')->get()->first();
            if(isset($assign_bike)){
            $bike=bike::find($assign_bike->bike_id);
            $salik_amount=Trip_Detail::whereNotNull('rider_id')
            ->whereMonth('trip_date','09') 
            ->where('plate',$bike->bike_number)
            ->sum('amount_aed'); 
            $salik+=$salik_amount; 
            $performance=Rider_Performance_Zomato::all();
            
        }
        $fuel_amount=Company_Account::whereNotNull('fuel_expense_id')
        ->where('rider_id',$riders->rider_id)
        ->whereMonth('month','09')
        ->sum('amount');
         $fuel+=$fuel_amount;

         $sim_amount=Company_Account::where("source","Sim Transaction")
         ->where('rider_id',$riders->rider_id)
         ->whereMonth('month','09')
         ->where('type','dr')
         ->whereNotNull('sim_transaction_id')
         ->sum('amount');
         $sim+=$sim_amount;
    }
        return response()->json([
        'payout'=>round($payout_total,2),
        'bike_fuel'=>round($fuel,2),
        'salik'=>round($salik,2),
        'sim'=>round($sim,2),
        ]);
    }
}
