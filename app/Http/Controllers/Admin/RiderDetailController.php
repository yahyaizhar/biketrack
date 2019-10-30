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
        // $time=[];
        //  $payout=Income_zomato::whereNotNull('rider_id')->whereMonth('date','09')->get();
        //  foreach ($payout as $hours) {
        //      $obj=[];
        //      $obj['log_in_hours_payable'] += $hours['log_in_hours_payable'];
        //      array_push($time,$obj);
        //  }
         $fuel=Company_Account::whereNotNull('fuel_expense_id')->where('month','09')->sum('amount');
        return response()->json([
        // 'payout'=>$time,
        'bike_fuel'=>$fuel,
        ]);
    }
}
