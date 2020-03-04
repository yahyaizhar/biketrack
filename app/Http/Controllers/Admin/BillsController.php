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
use App\Model\Accounts\Bill_change;
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
        $clients=Client::all();
        return view('admin.Bills.generated_bills',compact('clients'));
    }
    public function bills_loss_bike(){
        return view('admin.Bills.bills_loss_bike');
    }
    public function bills_loss_sim(){
        return view('admin.Bills.bills_loss_sim');
    }
    public function get_updatebills(Request $r)
    {
        #fetching bill details
        $source=$r->get('source');
        $rider_id=$r->get('rider_id');
        $month=$r->get('month');
        
        $onlyMonth=Carbon::parse($month)->format('m');
        $onlyYear=Carbon::parse($month)->format('Y');
        $month_start = Carbon::parse($month)->startOfMonth();
        $month_end = Carbon::parse($month)->endOfMonth();

        #fetcing records against source
        $company_accounts = Company_Account::whereMonth('month', $onlyMonth)
        ->whereYear('month',$onlyYear)
        ->where('rider_id', $rider_id)
        ->where('source', $source)
        ->get();
        $source_id='';
        $source_model='';
        $soure_billtype='';
        switch ($source) {
            case 'Sim Transaction':
                $source_id='sim_transaction_id';
                $source_model='App\Model\Sim\Sim_Transaction';
                $soure_billtype='sim';
                break;
            case 'Salik':
                $source_id='salik_id';
                break;
            case 'fuel_expense_vip':
                $source_id='fuel_expense_id';
                $source_model='App\Model\Accounts\Fuel_Expense';
                $soure_billtype='fuel_vip';
                break;
            case 'fuel_expense_cash':
                $source_id='fuel_expense_id';
                $source_model='App\Model\Accounts\Fuel_Expense';
                $soure_billtype='fuel_cash';
                break;
            case 'Bike Rent':
                $source_id='bike_rent_id';
                break;
            
            default:
                # No source found - throw exception
                break;
        }
        $response=[];
        $unique_source=[];
        foreach ($company_accounts as $company_account) {
            $source_value=$company_account[$source_id]; # source table id
            if($source_model!=''){ #if we find third table, we need to get bike_id or sim_id
                $source_rs = $source_model::find($source_value);
                if(isset($source_rs)){
                    #third table found
                    if(isset($source_rs->bike_id)){
                        #table contains bike id, means bill is bike related
                        $bike_id=$source_rs->bike_id;
                        #we need to distinct the array
                        if(!in_array($bike_id,$unique_source)){
                            $rs = $this->get_billsplits($bike_id,$month,'bike',$soure_billtype);
                            array_push($response,$rs);
                        }
                        array_push($unique_source,$bike_id);
                    }
                    elseif ($source_rs->sim_id) {
                        #table contains sim id, means bill is sim related
                        $sim_id=$source_rs->sim_id;
                    }
                }
            }
            

        }
        /**
         * ---------------WORK FOR TOMORROW------------------
         * 1) get bike id or sim id
         * 2) calculate days 
         * 3) get the already entered amount -if not found then ask from user
         * 4) update the bill in the folowwing tables (CA,RA,source table,export data,bill_changes)
         */

        return $response;
    }

    private function get_billsplits($source_id,$month,$according_to,$type)
    {
        $daysInMonth=Carbon::parse($month)->startOfMonth()->daysInMonth;
        $onlyMonth=Carbon::parse($month)->format('m');
        $onlyYear=Carbon::parse($month)->format('Y');
        $month_start = Carbon::parse($month)->startOfMonth();
        $month_end = Carbon::parse($month)->endOfMonth();
        $response=[];
        $response['source_id']=$source_id;
        if($according_to=='bike'){
            #we need to get details according to bike
            $bike_id=$source_id;
            $bike_histories = Assign_bike::with('Rider')->with('bike')->get()->toArray();
            $bikeh_f = Arr::where($bike_histories, function ($item, $key) use ($bike_id, $month) {
                $start_created_at =Carbon::parse($item['bike_assign_date'])->startOfMonth()->format('Y-m-d');
                $created_at =Carbon::parse($start_created_at);

                $start_updated_at =Carbon::parse($item['bike_unassign_date'])->endOfMonth()->format('Y-m-d');
                $updated_at =Carbon::parse($start_updated_at);
                $req_date =Carbon::parse($month);
                
                if($item['status']=='active'){
                    return $item['bike_id']==$bike_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
                }
                return $item['bike_id']==$bike_id &&
                    ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
            });
            if(isset($bikeh_f)){
                #sim found against this rider
                $previous_unassign_date = null;
                $response['split_data']=[];
                $response['temp']=$bikeh_f;
                foreach ($bikeh_f as $bike_history) {
                    # setting the assign and unassign date
                    $rider_id=$bike_history['rider_id'];
                    // $rider=$bike_history['Rider'];
                    $assign_date = Carbon::parse($bike_history['bike_assign_date']);
                    $unassign_date = Carbon::parse($bike_history['bike_unassign_date']);
                    if($assign_date->lessThan($month_start)){ #assign date will be start of month
                        $assign_date = $month_start;
                    }
                    if($unassign_date->greaterThan($month_end) || $bike_history['status']=='active'){ #unassign date will be end of month
                        $unassign_date = $month_end;
                    }

                    #storing previous unassign date, to check...
                    #if previous iteration unassign date is same as current iteration assign date, 
                    if ($previous_unassign_date!=null) {
                        # then we'll add 1 day to current iteration assign date
                        if ($previous_unassign_date->equalTo($assign_date)) {
                            $assign_date = $assign_date->addDay();
                        }
                    }
                    $previous_unassign_date = $unassign_date;

                    #now we just find total working days by subtracting assign_date and unassign_date +1 for adding first day
                    $working_days = $unassign_date->diffInDays($assign_date)+1;

                    #response array
                    $obj=[];
                    $obj['working_days']=$working_days;
                    $obj['assign_date']=$assign_date;
                    $obj['unassign_date']=$unassign_date;
                    $obj['month_days']=$daysInMonth;
                    array_push($response['split_data'],$obj);


                    #-------------------------------------------
                    # now we will get current added working days
                    #-------------------------------------------
                    $paid_bills = Bill_change::whereMonth('month', $onlyMonth)
                    ->whereYear('month', $onlyYear)
                    // ->where('type', 'sim')
                    ->get();

                    #now we need to fetch only sim bill from all paid bills
                    $bill_workdays=0;
                    $fuel_workdays=0;
                    $bill_msg='';
                    foreach ($paid_bills as $paid_bill) {
                        #filters only bike rents
                        if($paid_bill->type==$type){
                            $feed = json_decode($paid_bill->feed,true);
                            foreach ($feed as $feed_item) {
                                # match rider_id
                                if($feed_item['bike_id']==$bike_id ){
                                    #original bill found against this bike
                                    $response['original_bill']=$paid_bill;
                                    // array_push($response['temp'],'asdasd');
                                    break; # lets proceed to next bill. if any
                                }
                            }
                        }
                    }
                }
            }
        }
        return $response;
    }
}
