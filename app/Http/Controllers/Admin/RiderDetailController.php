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
use App\Model\Client\Client_History;
use App\Model\Client\Client;
use Illuminate\Support\Arr;
use Batch;
use Carbon\Carbon;
use App\Assign_bike;
use App\Model\Accounts\Company_Account;
use App\Model\Accounts\Rider_Account;
use App\Model\Accounts\Income_zomato;
use App\Model\Rider\Trip_Detail;
use App\Model\Accounts\Rider_salary;
use App\Export_data;
use App\Model\Invoice\Invoice;
use App\Notification;

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
                    // if($item->status=="active"){ 
                    //     // mean its still active, we need to match only created at
                    //     if($to_match_bike=='bike_id'){
                    //         return $item->bike_id == $_id && $req_date->greaterThanOrEqualTo($created_at);
                    //     }
                    //     return $item->rider_id == $_id && $req_date->greaterThanOrEqualTo($created_at);
                        
                    // }
                    if($to_match_bike=='bike_id'){
                        return $item->bike_id == $_id && ($req_date->isSameMonth($created_at) || $req_date->isSameMonth($updated_at));
                    }
                    return $item->rider_id == $_id && ($req_date->isSameMonth($created_at) || $req_date->isSameMonth($updated_at));
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
                    // if($item->status=="active"){ 
                    //     // mean its still active, we need to match only created at
                    //     return $to_match_sim=='sim_id'?$item->sim_id == $_id && $req_date->greaterThanOrEqualTo($created_at):$item->rider_id == $_id && $req_date->greaterThanOrEqualTo($created_at);
                    // }
                    
                    return $to_match_sim=='sim_id'?$item->sim_id == $_id && ($req_date->isSameMonth($created_at) || $req_date->isSameMonth($updated_at)):$item->rider_id == $_id && ($req_date->isSameMonth($updated_at) || $req_date->isSameMonth($updated_at));
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

        $client_history = Client_History::all();
        $client_history_found = Arr::first($client_history, function ($item, $key) use ($rider_id, $month) {
            $created_at =Carbon::parse($item->assign_date)->format('Y-m-d');
            $created_at =Carbon::parse($created_at);

            $updated_at =Carbon::parse($item->deassign_date)->format('Y-m-d');
            $updated_at =Carbon::parse($updated_at);
            $req_date =Carbon::parse($month);

            // if($item->status=="active"){ 
            //     // mean its still active, we need to match only created at
            //     return $item->rider_id == $rider_id && $req_date->greaterThanOrEqualTo($created_at);
            // }
            
            return $item->rider_id == $rider_id && ($req_date->isSameMonth($created_at) || $req_date->isSameMonth($updated_at)) ;
        });
        $client=NULL;
        if(isset($client_history_found)){
            $client=Client::find($client_history_found->client_id);
        }
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

            'client'=>$client,
            'client_history_found'=>$client_history_found
        ]);
    }
    public function client_total_expense($id){
        $client=Client::find($id);
        return view('client_salary_sheet',compact('client'));
    }
    public function summary_month($month,$client_id){
        $client=Client::where("id",$client_id)->get()->first();
        $monthOnly=Carbon::parse($month)->format('m');
        $yearOnly=Carbon::parse($month)->format('Y');
        $client_id=$client->id;
        $client_riders = collect([]);
        $client_history=Client_History::all()->toArray(); 
        $tmps = Arr::where($client_history, function ($item, $key) use ($client_id, $month) {
            $start_created_at =Carbon::parse($item['assign_date'])->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);
    
            $start_updated_at =Carbon::parse($item['deassign_date'])->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($month);
    
            if($item['status']=='active'){    
                return $item['client_id']==$client_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
            }
    
            return $item['client_id']==$client_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });
        foreach ($tmps as $tmp) {
            $mdl = new Client_History;
            $mdl->rider_id=$tmp['rider_id'];
            $mdl->client_id=$tmp['client_id'];
            $mdl->assign_date=$tmp['assign_date'];
            $mdl->deassign_date=$tmp['deassign_date'];
            $mdl->client_rider_id=$tmp['client_rider_id'];
            $client_riders->push($mdl);
        }

        $hours_client=0;
        $trips_client=0;
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
        $gross_profit=0;
        $fine_amt=0;
        $gross_loss=0;
        $net_profit=0;
        $total_profit=0;
        $settlements=0;
        

        foreach ($client_riders as $riders) {
            $salary_deductions = \App\Http\Controllers\Admin\AccountsController::get_salary_deduction(new Request(),$month,$riders->rider_id);
            $salary_deductions=json_decode($salary_deductions->content(), true);
            $total_hours_client=0;
            $total_trips_client=0;
            $_trips=0;
            if($salary_deductions['status']==1){
                $total_hours_client=$salary_deductions['zomato_hours'];
                $total_trips_client=$salary_deductions['zomato_trips'];
                $_trips=$salary_deductions['trips'];
                $_hours=$salary_deductions['payable_hours'];

                $aed_hours+=$salary_deductions['hours_payable'];
                $aed_trips+=$salary_deductions['trips_payable'];
                $hours_client+=($total_hours_client*6);
                $trips_client+=($total_trips_client*6.75);

                $trips+=$_trips;
                $hours+=$_hours;
            }
            
            

            $bon=Rider_Account::where('source',"400 Trips Acheivement Bonus")
            ->where('rider_id',$riders->rider_id)
            ->whereMonth('month',$monthOnly)
            ->whereYear('month',$yearOnly)
            ->sum('amount');
            $bonus+=$bon;

            $bike_rent+=Company_Account::where("source",'Bike Rent')
            ->whereYear('month',$yearOnly)
            ->where("rider_id",$riders->rider_id)
            ->whereMonth('month',$monthOnly)
            ->sum('amount');

            $settlements+=Income_zomato::where('rider_id',$riders->rider_id)
            ->whereMonth('date',$monthOnly)
            ->whereYear('date',$yearOnly)
            ->sum('settlements');

            $fuel+=Company_Account::whereNotNull('fuel_expense_id')
            ->where('rider_id',$riders->rider_id)
            ->whereMonth('month',$monthOnly) 
            ->whereYear('month',$yearOnly)
            ->sum('amount');
            $sim_amount=Company_Account::where("source","Sim Transaction")
            ->where('rider_id',$riders->rider_id)
            ->whereMonth('month',$monthOnly)
            ->whereYear('month',$yearOnly)
            ->where('type','dr')
            ->whereNotNull('sim_transaction_id')
            ->sum('amount');
            $sim+=$sim_amount;
            $sim_extra_amount=Company_Account::where("source","Sim extra usage")
            ->where('rider_id',$riders->rider_id)
            ->whereMonth('month',$monthOnly)
            ->whereYear('month',$yearOnly)
            ->whereNotNull('sim_transaction_id')
            ->sum('amount');
            $sim_extra+=$sim_extra_amount;
           
            $salik_amount=Company_Account::where("source","Salik")
            ->where('rider_id',$riders->rider_id)
            ->whereMonth('month',$monthOnly)
            ->whereYear('month',$yearOnly)
            ->whereNotNull('salik_id')
            ->sum('amount');
            $salik+=$salik_amount;
            $salik_extra_amount=Company_Account::where("source","Salik Extra")
            ->where('rider_id',$riders->rider_id)
            ->whereMonth('month',$monthOnly)
            ->whereYear('month',$yearOnly)
            ->whereNotNull('salik_id')
            ->sum('amount');
            $salik_extra+=$salik_extra_amount;
            $total_salik=$salik-$salik_extra;
            $total_sim=$sim-$sim_extra;


            $profit = AccountsController::calculate_profit($month,$riders->rider_id);
            if($profit<0) $gross_loss+=abs($profit);
            else $gross_profit+=abs($profit);
        
            $total_profit+=$profit;

            $bills = AccountsController::calculate_bills($month,$riders->rider_id);
            $fine_amt+=$bills['dicipline_fine'];

        }
        $net_profit=($gross_profit-$gross_loss)+$fine_amt;

        $tax_collected=null;
        $invoice=Invoice::where('client_id',$client_id)
        ->whereMonth('month',$monthOnly)
        ->whereYear('month',$yearOnly)
        ->get()
        ->first();
        if(isset($invoice)){
            $tax_collected=round($invoice->taxable_amount,2);
        }

        return response()->json([
            'client'=>$client_riders,
            
            'total_profit'=>$total_profit,
            'net_profit'=>$net_profit,
            'gross_profit'=>$gross_profit,
            'gross_loss'=>$gross_loss,
            'fine_amt'=>$fine_amt,
            'settlements'=>$settlements,
            'tax_collected'=>$tax_collected,

            'aed_hours_client'=>$hours_client,
            'aed_trips_client'=>$trips_client,
            'sum_1'=>$hours_client+$trips_client+$settlements,

            'trips'=>$trips,
            'hours'=>$hours,
            'aed_trips'=>$aed_trips,
            'aed_hours'=>$aed_hours,
            'bonus'=>$bonus,
            'sum_2'=>$aed_trips+$aed_hours+$bonus,
           
            'bike_rent'=>$bike_rent,
            'fuel'=>$fuel,
            'salik'=>$salik,
            'salik_extra'=>$salik-$salik_extra,
            'sim'=>$sim,
            'sim_extra'=>round($sim-$sim_extra,2),
            'sum_3'=>$bike_rent+$fuel+$total_salik+$total_sim,
            
        ]);
    }
  
    public function rider_expense_bonus(Request $request)
    {
        $unique_id=\App\Http\Controllers\Admin\AccountsController::getUniqueId(15);

        $ca = new \App\Model\Accounts\Company_Account;
        $ca->type='dr';
        $ca->amount=$request->amount;
        $ca->month=Carbon::parse($request->get('month'))->startOfMonth()->format('Y-m-d');
        $ca->given_date=Carbon::parse($request->get('given_date'))->format('Y-m-d');
        $ca->rider_id = $request->rider_id;
        $ca->source='400 Trips Acheivement Bonus';
        $ca->kingrider_fine_id=$unique_id;
        $ca->desc='Bonus';
        $ca->save();

        $ra = new \App\Model\Accounts\Rider_Account;
        $ra->type='cr';
        $ra->amount=$request->amount;
        $ra->month=Carbon::parse($request->get('month'))->startOfMonth()->format('Y-m-d');
        $ra->given_date=Carbon::parse($request->get('given_date'))->format('Y-m-d');
        $ra->rider_id = $request->rider_id;
        $ra->source='400 Trips Acheivement Bonus';
        $ra->desc='Bonus';
        $ra->kingrider_fine_id=$unique_id;
        $ra->save();

        $ed =new Export_data;
        $ed->type='dr';
        $ed->rider_id=$request->rider_id;
        $ed->amount=$request->amount;
        $ed->month = Carbon::parse($request->get('month'))->startOfMonth()->format('Y-m-d');
        $ed->given_date = Carbon::parse($request->get('given_date'))->format('Y-m-d');
        $ed->source='Bonus';
        $ed->source_id=$unique_id;
        $ed->save();
        
    }
    public function rider_expense_discipline(Request $request)
    {
        $unique_id=\App\Http\Controllers\Admin\AccountsController::getUniqueId(15);
        
        $ca = new \App\Model\Accounts\Company_Account;
        $ca->type='cr';
        $ca->amount=$request->amount;
        $ca->month=Carbon::parse($request->get('month'))->startOfMonth()->format('Y-m-d');
        $ca->given_date=Carbon::parse($request->get('given_date'))->format('Y-m-d');
        $ca->rider_id = $request->rider_id;
        $ca->source='Discipline Fine';
        $ca->kingrider_fine_id=$unique_id;
        $ca->desc=$request->desc;
        $ca->save();

        $ra = new \App\Model\Accounts\Rider_Account;
        $ra->type='cr_payable';
        $ra->amount=$request->amount;
        $ra->month=Carbon::parse($request->get('month'))->startOfMonth()->format('Y-m-d');
        $ra->given_date=Carbon::parse($request->get('given_date'))->format('Y-m-d');
        $ra->rider_id = $request->rider_id;
        $ra->source='Discipline Fine';
        $ra->kingrider_fine_id=$unique_id;
        $ra->desc=$request->desc;
        $ra->save();

        $ed =new Export_data;
        $ed->type='cr';
        $ed->rider_id=$request->rider_id;
        $ed->amount=$request->amount;
        $ed->month = Carbon::parse($request->get('month'))->startOfMonth()->format('Y-m-d');
        $ed->given_date = Carbon::parse($request->get('given_date'))->format('Y-m-d');
        $ed->source='Discipline Fine';
        $ed->source_id=$unique_id;
        $ed->save();
        
    }
    public function cash_paid(Request $r){
        $unique_id=\App\Http\Controllers\Admin\AccountsController::getUniqueId(15);

        $ra = new \App\Model\Accounts\Rider_Account;
        $ra->type='dr';
        $ra->amount=$r->amount;
        $ra->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
        $ra->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
        $ra->rider_id = $r->cash_rider_id;
        $ra->source='pay_cash';
        $ra->desc=$r->desc;
        $ra->kingrider_fine_id=$unique_id;
        $ra->payment_status="paid";
        $ra->save();

        $ed =new Export_data;
        $ed->type='cr';
        $ed->rider_id=$r->cash_rider_id;
        $ed->amount=$r->amount;
        $ed->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
        $ed->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
        $ed->source="pay_cash";
        $ed->source_id=$unique_id;
        $ed->payment_status="paid";
        $ed->save();

        #we need to send the notification if it is coming from daily ledger page
        if(isset($r->send_noti)){
            #send the noti
            $data=[
                "export_id"=>$ed->id,
                'status'=>'accept'
            ];
            $data_accept=[
                "type"=>"update",
                "data"=>$data,
                "url"=> 'admin/account/dailyledger/noticallback',
            ];
            $data=[
                "export_id"=>$ed->id,
                'status'=>'reject'
            ];
            $data_reject=[
                "type"=>"update",
                "data"=>$data,
                "url"=> 'admin/account/dailyledger/noticallback',
            ];
            $button_html_accepted="<i style='font-size:20px' class='flaticon2-correct text-success btn_label--hover' onclick='CallBackNotification(this,".json_encode($data_accept).")'></i>";
            $button_html_rejected="<i style='font-size:20px' class='flaticon-circle text-danger btn_label--hover' onclick='CallBackNotification(this,".json_encode($data_reject).")'></i>";
            $button=$button_html_accepted.$button_html_rejected;
            $current_url=url('account.daily_ledger');
            $action_data = [
                [
                    'type'=>"url",
                    'value'=>$current_url,    
                ] ,
                [
                    'type'=>"button",
                    'value'=>$button,    
                ] ,
            ];
            $user = Auth::user();
            if (isset($user->s_emp_id) && $user->s_emp_id!="") {
                $seniour_emp=$user->s_emp_id;
                $emp_name=$user->name;

                $given_date=Carbon::parse($ed->given_date)->format("M d, Y");
    
                $notification=new Notification;
                $notification->date_time=Carbon::now()->format("Y-m-d");
                $notification->employee_id=$seniour_emp;
                $notification->desc='skip_this_notification';
                $notification->action=json_encode($action_data);
                $notification->source_id=$ed->id;
                $notification->source_type='kingrider_fine_id';
                $notification->action_status='pending_new';
                $notification->feed=json_encode($ed);
                $notification->save();
                return response()->json([
                    'status'=>$notification
                ]);
            }
            return response()->json([
                'status'=>1
            ]);
        }
     
    }
public function cash_debit_rider(Request $r){
    $unique_id=\App\Http\Controllers\Admin\AccountsController::getUniqueId(15);

    $ca = new \App\Model\Accounts\Company_Account;
    $ca->type='cr';
    $ca->amount=$r->amount;
    $ca->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
    $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
    $ca->rider_id = $r->cash_rider_id_debit;
    $ca->source='receive_cash';
    $ca->desc=$r->desc;
    $ca->kingrider_fine_id=$unique_id;
    $ca->save();
    
    $ra = new \App\Model\Accounts\Rider_Account;
    $ra->type='dr';
    $ra->amount=$r->amount;
    $ra->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
    $ra->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
    $ra->rider_id = $r->cash_rider_id_debit;
    $ra->source='receive_cash';
    $ra->desc=$r->desc;
    $ra->kingrider_fine_id=$unique_id;
    $ra->save();

    $ed =new Export_data;
    $ed->type='cr';
    $ed->rider_id=$r->cash_rider_id_debit;
    $ed->amount=$r->amount;
    $ed->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
    $ed->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
    $ed->source="receive_cash";
    $ed->source_id=$unique_id;
    $ed->save();
    
}
public function mics_charges(Request $r){
    $unique_id=\App\Http\Controllers\Admin\AccountsController::getUniqueId(15);

    $ra = new \App\Model\Accounts\Rider_Account;
    $ra->type='dr';
    $ra->amount=$r->amount;
    $ra->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
    $ra->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
    $ra->rider_id = $r->visa_rider_id;
    $ra->source="Visa Charges";
    $ra->kingrider_fine_id=$unique_id;
    $ra->payment_status="paid";
    $ra->save();

    $ed =new Export_data;
    $ed->type='dr';
    $ed->rider_id=$r->visa_rider_id;
    $ed->amount=$r->amount;
    $ed->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
    $ed->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
    $ed->source="Visa Charges";
    $ed->source_id=$unique_id;
    $ed->payment_status="paid";
    $ed->save();

}
public function cash_credit_rider(Request $r){ #not using
    $ca = new \App\Model\Accounts\Company_Account;
    $ca->type='dr';
    $ca->amount=$r->amount;
    $ca->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
    $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
    $ca->rider_id = $r->cash_rider_id;
    $ca->source=$r->desc;
    $ca->save();
    
    $ra = new \App\Model\Accounts\Rider_Account;
    $ra->type='cr';
    $ra->amount=$r->amount;
    $ra->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
    $ra->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
    $ra->rider_id = $r->cash_rider_id;
    $ra->source=$r->desc;
    $ra->save();
}
public function view_upload_salary_slip(Request $request,$month,$rider_id){
    $_month=Carbon::parse($month)->format('m');
    $_year=Carbon::parse($month)->format('Y');
    $slip_image=0;
    $salary_paid=Rider_Account::where("rider_id",$rider_id)
    ->whereMonth("month",$_month)
    ->whereYear("month",$_year)
    ->where("source","salary_paid")
    ->get()
    ->first();
    if (isset($salary_paid)) {
        $id=$salary_paid->salary_id;
        $slip_image=Rider_salary::find($id);
        if($request->hasFile('slip_image'))
       {
           $filename = $request->slip_image->getClientOriginalName();
           $filesize = $request->slip_image->getClientSize();
           $filepath = Storage::putfile('public/uploads/riders/salary_slip', $request->file('slip_image'));
           $slip_image->salary_slip_image = $filepath;
       }
       $slip_image->save();
    }
    
return response()->json([
    'image'=>$slip_image,
]);
}

}