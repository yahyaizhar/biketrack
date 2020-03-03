<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Client\Client;
use App\Model\Client\Client_Rider;
use App\Model\Client\Client_History;
use Yajra\DataTables\DataTables;
use App\Model\Rider\Rider;
use App\Http\Resources\RiderLocationResourceCollection;
use App\Http\Resources\RiderLocationResource;
use App\Http\Resources\ClientRidersLocationsResourceCollection;
use App\Http\Resources\ClientLocationResourceCollection;
use App\Model\Bikes\bike;
use App\Model\Bikes\bike_detail;
use App\Model\Accounts\Rider_salary;
use App\Model\Mobile\Mobile;
use carbon\carbon;
use App\Model\Rider\Rider_detail;
use App\Model\Rider\Rider_Location;
use App\New_comer;
use App\Model\Rider\Rider_Report;
use Illuminate\Support\Facades\Storage;
use App\Model\Sim\Sim;
use App\Model\Accounts\Bike_Fine;
use App\Model\Sim\Sim_Transaction;
use App\Model\Mobile\Mobile_installment;
use App\Model\Rider\Rider_Performance_Zomato;
use App\Assign_bike;
use App\Model\Rider\Trip_Detail;
use App\Model\Accounts\Company_Account;
use App\Model\Accounts\Bike_Accounts;
use App\Model\Accounts\Rider_Account;
use App\Model\Accounts\Id_charge;
use App\Model\Accounts\Workshop;
use App\Model\Accounts\Maintenance;
use App\Model\Accounts\Edirham;
use Arr;
use App\Model\Accounts\Fuel_Expense;
use App\Model\Accounts\Company_Expense;
use App\Model\Accounts\WPS;
use App\Model\Accounts\AdvanceReturn;
use App\Model\Accounts\Client_Income;
use App\Model\Accounts\Income_zomato;
use App\Model\Mobile\Mobile_Transaction;
use App\Log_activity;
use Auth;
use App\Model\Accounts\Bill_change;
use App\Model\Admin\Admin;
use App\Company_investment;
use App\Model\Invoice\Invoice;
use App\Tax_method;
use App\Bank_account;
use App\Model\Invoice\Invoice_item;
use App\Model\Invoice\Invoice_Payment;
use App\Model\Accounts\EmployeeAccounts;
use Str;
use App\Model\Mobile\Accessory;
use App\Model\Mobile\Seller;
use App\Model\Mobile\MobileHistory;
use DB;
use App\Export_data;
use App\Http\Controllers\Admin\AccountsController;
use App\Deleted_data;
use App\Model\Sim\Sim_History;


class AjaxNewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    } 
    public function getIdCharges()
    {
        $id_charges = Id_charge::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($id_charges)
        ->addColumn('status', function($id_charge){
            if($id_charge->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($id_charge){
            return '1000'.$id_charge->id;
        })
        ->addColumn('rider_name', function($id_charge){
            $rider = $id_charge->Rider;
            return $rider->name;
        })
        ->addColumn('actions', function($id_charge){
            $status_text = $id_charge->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.id_charges_edit_view', $id_charge).'"><i class="fa fa-edit"></i> View</a>
                    <button class="dropdown-item" onclick="updateStatus('.$id_charge->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteRow('.$id_charge->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['status','rider_name','type','amount','actions', 'status'])
        ->make(true);
    }
    public function income_zomato_ajax()
    {
        $income_zomatos = Income_zomato::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($income_zomatos)
        
        ->addColumn('rider_name', function($income_zomato){
            $client_rider=Client_Rider::where("client_rider_id",$income_zomato->feid)->get()->first();
            $rider=Rider::find($client_rider['rider_id']);
            
            if(isset($rider)){
                $p_id=$income_zomato->p_id;
                $feid=$income_zomato->feid;
                $date=$income_zomato->date;
                $ca=Company_Account::where("income_zomato_id",$p_id)->where("month",$date)->get();
                foreach ($ca as $value) {
                    if ($value->rider_id=="") {
                        return '<a onclick="feid_rider(\''.$p_id.'\',\''.$feid.'\','.$client_rider['rider_id'].')">'.$rider['name'].'*</a>';
                    }
                    return $rider['name'];
                }
            }
            else{
                $p_id=$income_zomato->p_id;
                $feid=$income_zomato->feid;
                return '<a href="'.url('/admin/clients').'" data-income-zomato-id="'.$p_id.'" data-feid="'.$feid.'">'.$feid.' is not Assigned.</a>'; 
            }
        })
        ->addColumn('total_to_be_paid_out', function($income_zomato){
            return $income_zomato->total_to_be_paid_out;
        })
        ->addColumn('amount_for_login_hours', function($income_zomato){
            return $income_zomato->amount_for_login_hours;
        })
        ->addColumn('amount_to_be_paid_against_orders_completed', function($income_zomato){
            return $income_zomato->amount_to_be_paid_against_orders_completed;
        })
        ->addColumn('ncw_incentives', function($income_zomato){
            return $income_zomato->ncw_incentives;
        })
        ->addColumn('tips_payouts', function($income_zomato){
            return $income_zomato->tips_payouts;
        })
        ->addColumn('dc_deductions', function($income_zomato){
            return $income_zomato->dc_deductions;
        })
        ->addColumn('mcdonalds_deductions', function($income_zomato){
            return $income_zomato->mcdonalds_deductions;
        })
        ->addColumn('feid', function($income_zomato){
            return $income_zomato->feid;
        })
        ->addColumn('date', function($income_zomato){
            return $income_zomato->date;
        })
        
        ->rawColumns(['feid','date','rider_name','total_to_be_paid_out','amount_for_login_hours', 'amount_to_be_paid_against_orders_completed', 'ncw_incentives', 'tips_payouts', 'dc_deductions', 'mcdonalds_deductions'])
        ->make(true);
    }
    public function getCompanyAccountsBills($ranges) 
    {
        $ranges = json_decode($ranges, true);
        $from = date($ranges['range']['start_date']);
        $to = date($ranges['range']['end_date']);
        $rider_id = $ranges['rider_id'];

        $bills = collect([]);

        $month = Carbon::parse($to)->format('m');
        //sim
        $modelArr = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->where("source","Sim Transaction")
        ->where("type","dr")
        ->whereNotNull('sim_transaction_id')
        ->get();
        $model = $modelArr->first();
        if(isset($model)){ 
        $sim_pend=0;
        $sim_paid=0;
        foreach ($modelArr as $mod) {
                if ($mod->payment_status=="pending") {
                    $sim_pend+=$mod->amount;
                }
                $sim_paid+=$mod->amount;
            }
            $model->amount=$sim_paid;
        $model->payment_status="paid";
        if ($sim_pend>0) {
            $model->payment_status="pending";
            $model->amount=$sim_pend.' is remaining out of '.$sim_paid;  
        }
        if ($model!="") {
            $bills->push($model);
        }
        }
        //Salik
        $modelArr = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->where("type","dr")
        ->whereNotNull('salik_id')
        ->get();
        $model = $modelArr->first();
        if(isset($model)){ 
        $salik_pend=0;
        $salik_paid=0;
        foreach ($modelArr as $mod) {
            if ($mod->payment_status=="pending") {
                $salik_pend+=$mod->amount;
            }
            $salik_paid+=$mod->amount;
        }
        if (isset($salik_paid)) {
            $model->amount=$salik_paid; 
            $model->payment_status="paid"; 
        }
        if ($salik_pend>0) {
            $model->payment_status="pending";
            $model->amount=$salik_pend.' is remaining out of '.$salik_paid;  
        }
        if ($model!="") {
            $bills->push($model);
        }
        }   
        // bike_fine
        $modelArr = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->where("type","dr")
        ->where("source",'Bike Fine')
        ->get();
        $model = $modelArr->first();
        if(isset($model)){ 
            $bike_fine_pend=0;
            $bike_fine_paid=0;
            
            foreach ($modelArr as $mod) {
                if ($mod->payment_status=="pending") {
                    $bike_fine_pend+=$mod->amount;
                }
                $bike_fine_paid+=$mod->amount;
            } 
            $model->payment_status="paid";
            $model->amount=$bike_fine_paid;
            if ($bike_fine_pend>0) {
                $model->amount=$bike_fine_pend.' is remaining out of '.$bike_fine_paid;
                $model->payment_status="pending";
            }
            if ($model!="") {
                $bills->push($model);
            }
        }
        //fuel_expense
        $modelArr = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->whereNotNull('fuel_expense_id')
        ->where('source','fuel_expense_vip')
        ->where('type','dr')
        ->get();
        $model = $modelArr->first();
        if(isset($model)){ 
        $fuel_pend=0;
        $fuel_paid=0;
        foreach ($modelArr as $mod) {
            if ($mod->payment_status=="pending") {
                $fuel_pend+=$mod->amount;
            }
            $fuel_paid+=$mod->amount;
            }
            if (isset($fuel_paid)) {
                $model->amount=$fuel_paid; 
                $model->payment_status="paid";  
            }
      
        if ($fuel_pend>0) {
            $model->payment_status="pending";
            $model->amount=$fuel_pend.' is remaining out of '.$fuel_paid;  
        }
        if ($model!="") {
            $bills->push($model);
        }
        }
        $modelArr = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->whereNotNull('fuel_expense_id')
        ->where('source','fuel_expense_cash')
        ->where('type','dr')
        ->get();
        $model = $modelArr->first();
        if(isset($model)){ 
        $fuel_cash_pend=0;
        $fuel_cash_paid=0;
        foreach ($modelArr as $mod) {
            if ($mod->payment_status=="pending") {
                $fuel_cash_pend+=$mod->amount;
            }
            $fuel_cash_paid+=$mod->amount;
            }   
            if (isset($fuel_cash_paid)) {
                $model->amount=$fuel_cash_paid; 
                $model->payment_status="paid";  
            } 
        
        if ($fuel_cash_pend>0) {
            $model->payment_status="pending";
            $model->amount=$fuel_cash_pend.' is remaining out of '.$fuel_cash_paid;  
        }
        if ($model!="") {
            $bills->push($model);
        }
        }
        //maintenance
        $model = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->whereNotNull('maintenance_id')
        ->get()
        ->first();
        if(isset($model)){
            $model->source = "Bike Maintenance";
            if ($model!="") {
                $bills->push($model);
            }
        }
        //bike_rent
        // $model = \App\Model\Accounts\Company_Account::
        // whereMonth('month', $month)
        // ->where("rider_id",$rider_id)
        // ->whereNotNull('bike_rent_id')
        // ->get()
        // ->first();
        // if(isset($model)){
        //     $model->source = "Bike Rent";
        //     if ($model!="") {
        //         $bills->push($model);
        //     }
        // }

        $modelArr = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->whereNotNull('bike_rent_id')
        ->where("type","dr")
        ->get();
        $model = $modelArr->first();
        if(isset($model)){ 
        $bike_rent_pend=0;
        $bike_rent_paid=0;
        foreach ($modelArr as $mod) {
            if ($mod->payment_status=="pending") {
                $bike_rent_pend+=$mod->amount;
            }
            $bike_rent_paid+=$mod->amount;
            }   
            if (isset($bike_rent_paid)) {
                $model->amount=$bike_rent_paid; 
                $model->payment_status="paid";  
            } 
        
        if ($bike_rent_pend>0) {
            $model->payment_status="pending";
            $model->amount=$bike_rent_pend.' is remaining out of '.$bike_rent_paid;  
        }
        if ($model!="") {
            $bills->push($model);
        }
        }


        return DataTables::of($bills)
        ->addColumn('date', function($bill){
            if (isset($bill->given_date)) {
                return Carbon::parse($bill->given_date)->format('M d, Y');
            }
        })
        ->addColumn('bill', function($bill){
            if (isset($bill->source)) {
                if($bill->source=="fuel_expense_cash"){
                    $_rowFuel='<a id="bill_detail" type="button" onclick="BillsDetails()">'.$bill->source.'</a>';
                    return $_rowFuel;
                }
                return $bill->source;
            }
            
        })
        ->addColumn('amount', function($bill){
            if (isset($bill->amount)) {
                return $bill->amount;
            }
            
        })
        ->addColumn('payment_status', function($bill){
            if (isset($bill->payment_status)) {
                if($bill->payment_status == 'pending'){
                    //enable pay
                    $month=$bill->month;
                    $rider_id=$bill->rider_id;
                    $type=$bill->source;
                    $month_given=$bill->given_date;
                    if ($type=="Sim Transaction") {
                        $sim_transaction_id=$bill->sim_transaction_id;
                        return '<div>Pending <button style="margin-right: 5px;" type="button" onclick="updateStatusBills('.$rider_id.',\''.$month.'\',\''.$type.'\',\''.$month_given.'\')" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button><button class="btn btn-sm btn-success" type="button" onclick="SimBillsImage('.$rider_id.',\''.$month.'\',\''.$type.'\')">View Sim Bill Image</button></div>';
                    }
                    return '<div>Pending <button type="button" onclick="updateStatusBills('.$rider_id.',\''.$month.'\',\''.$type.'\',\''.$month_given.'\')" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button></div>';
                    
                }
                if($bill->payment_status == 'paid'){
                    //enable pay
                    $month=$bill->month;
                    $rider_id=$bill->rider_id;
                    $type=$bill->source;
                    $month_given=$bill->given_date;
                    if ($type=="Sim Transaction") {
                        $sim_transaction_id=$bill->sim_transaction_id;
                        return ucfirst($bill->payment_status).' <i class="flaticon2-correct text-success h5"></i><button class="btn btn-sm btn-success" type="button" onclick="SimBillsImage('.$rider_id.',\''.$month.'\',\''.$type.'\')">View Sim Bill Image</button>';
                    }
                }
                return ucfirst($bill->payment_status).' <i class="flaticon2-correct text-success h5"></i>';
            
            }
          })
        ->addColumn('action', function($bill){
            $rb = '<i class="flaticon2-time tr-edit" onclick="regenerate_bill(this, \''.$bill->source.'\',\''.$bill->rider_id.'\',\''.$bill->month.'\')"></i>';
            return '';
        })
        // ->with([
        //     'closing_balance' => round($closing_balance,2)
        // ])
        ->rawColumns(['amount','bill','payment_status','date','action'])
        ->make(true);

        // return response()->json([
        //     'data'=>$bills
        // ]);

    }

    public function getRiderAccounts($ranges)
    {
        $ranges = json_decode($ranges, true);
        $from = date($ranges['range']['start_date']);
        $to = date($ranges['range']['end_date']);
        $rider_statements = \App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->get();

        // rider name
        $rider='';
        $date='';
        $hours=0;
        $salary_slip=0;
        $rider_name=Rider::find($ranges['rider_id']);
        $rider__detail=$rider_name->Rider_detail;
        if (isset($rider_name)) {
            $rider=$rider_name->name;
        }
        $date_of_joining=Rider_detail::where('rider_id',$ranges['rider_id'])->get()->first();
        if (isset($date_of_joining)) {
            $date=$date_of_joining->date_of_joining;
        }
        // salary
        $salary=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','salary')
        ->where('payment_status','pending')
        ->sum('amount');
        $salary_paid_status=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where(function($q) {
            $q->where('source', "salary_paid")
              ->orWhere('source', 'remaining_salary');
        })
        ->get()
        ->first();
        if (isset($salary_paid_status)) {
           $rider_salary_slip=Rider_salary::where("id",$salary_paid_status->salary_id)->get()->first();
           $salary_slip=asset(Storage::url($rider_salary_slip->salary_slip_image));
           if ($rider_salary_slip->salary_slip_image==null) {
            $salary_slip='';
           }
        }
        $hour=Income_zomato::where("rider_id",$ranges['rider_id'])
        ->whereDate('date', '>=',$from)
        ->whereDate('date', '<=',$to)
        ->first();
        if (isset($hour)) {
            $absent_days=$hour->absents_count;
            $absent_hours=$absent_days*11;
            
            $work_days=$hour->working_days;
            $workable_hours=$work_days*11;

            $calculate_hour=$hour->calculated_hours;
            
            $total_hours=$workable_hours -  $calculate_hour;

            $hours=286 - $absent_hours - $total_hours; 
        }

        $trips=Income_zomato::where("rider_id",$ranges['rider_id'])
        ->whereDate('date', '>=',$from)
        ->whereDate('date', '<=',$to)
        ->sum('trips_payable');
        if ( $trips > 400) $trips=400; 
        $extra_trips=Income_zomato::where("rider_id",$ranges['rider_id'])
        ->whereDate('date', '>=',$from)
        ->whereDate('date', '<=',$to)
        ->sum('trips_payable');
        if ( $extra_trips > 400){
            $extra_trips=$extra_trips-400; 
        }
        else{
            $extra_trips=0;
        }

        $ncw=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','NCW Incentives')
        ->sum('amount');
        $mics=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Visa Charges')
        ->where("payment_status","paid")
        ->sum('amount');
        $tip=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Tips Payouts')
        ->sum('amount');
        $bike_allowns=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where(function($q) {
            $q->where('source', "Bike Allowns")
            ->orWhere('source', 'Bike Rent');
        })
        ->sum('amount');
        $bike_fine=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Bike Fine Paid')
        ->sum('amount');
        $bones=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','400 Trips Acheivement Bonus')
        ->sum('amount');
        $advance=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','advance')
        ->sum('amount');
        $cash_paid_in_advance=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('type','dr')
        ->where("payment_status","paid")
        ->where("source","!=","advance")
        ->where("source","!=","salary_paid")
        ->where("source","!=","Visa Charges")
        ->where("source","!=","remaining_salary")
        ->where("source","!=","Mobile Installment")
        ->sum('amount');
        $salik=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where(function($q) {
            $q->where('source', "Salik")
            ->orWhere('source', 'Salik Extra');
        })
        ->sum('amount');
        $sim=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Sim extra usage')
        ->sum('amount');
        $dc=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','DC Deductions')
        ->sum('amount');
        $salary_paid=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where(function($q) {
            $q->where('source', "salary_paid")
              ->orWhere('source', 'remaining_salary');
        })
        ->where('payment_status','paid')
        ->sum('amount');
        $macdonald=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Mcdonalds Deductions')
        ->sum('amount');
        $dicipline=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where(function($q) {
            $q->where('source','Discipline Fine')
             ->orWhereNotNull('kingrider_fine_id');
        })
        ->sum('amount');
        $rta=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->whereNotNull('id_charge_id')
        ->sum('amount');
        $mobile=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Mobile Installment')
        ->sum('amount');
        $denial_penalty=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Denials Penalty')
        ->sum('amount');
      
        
        $rider_debits_cr_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->where(function($q) {
            $q->where('type', "cr");
        })
        ->sum('amount');
        
        $rider_debits_dr_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->where(function($q) {
            $q->where('type', "cr_payable")
              ->orWhere('type', 'dr');
        })
        ->sum('amount');

        $rider_debits_cr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->where(function($q) {
            $q->where('type', "cr");
        })
        ->whereDate('month', '<',$from)
        ->sum('amount');
        
        $rider_debits_dr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->where(function($q) {
            $q->where('type', "cr_payable")
              ->orWhere('type', 'dr');
        })
        ->whereDate('month', '<',$from)
        ->sum('amount');

        $closing_balance = $rider_debits_cr_payable - $rider_debits_dr_payable;
        $closing_balance_prev = round($rider_debits_cr_prev_payable - $rider_debits_dr_prev_payable,2);
        $running_balance =round($closing_balance_prev,2);
        $cash_paid =0;

        $flag = new \App\Model\Accounts\Rider_Account;
        $flag->month='';
        $flag->source='Opening Balance';
        $flag->type='skip';
        $flag->amount=0;
        $rider_statements->prepend($flag);

        $flag = new \App\Model\Accounts\Rider_Account;
        $flag->month='';
        $flag->source='Closing Balance';
        $flag->type='skip';
        $flag->amount=0;
        $rider_statements->push($flag);

        return DataTables::of($rider_statements)
        ->addColumn('date', function($rider_statement){
            if($rider_statement->type=='skip') return '';
            if($rider_statement->given_date==null || $rider_statement->given_date=='') return 'No given date';
            return Carbon::parse($rider_statement->given_date)->format('M d, Y');
        })
        ->addColumn('desc', function($rider_statement) use ($rider_statements){
            if($rider_statement->type=='skip') return '<strong >'.$rider_statement->source.'</strong>';

            if($rider_statement->source == 'salary'){
                $ras = $rider_statements->toArray();
                $ra_found = Arr::first($ras, function ($item, $key) use ($rider_statement) { 
                    if($item['type']=='skip') return false;
                    return $item['salary_id'] == $rider_statement->salary_id 
                    && $item['type'] == "dr"
                    && $item['rider_id'] == $rider_statement->rider_id
                    && $item['source'] == "salary_paid";
                });
                if(!isset($ra_found)){
                    $salary_paid=Rider_salary::find($rider_statement->salary_id);
                    $total=$salary_paid->total_salary;
                    $gross=$salary_paid->gross_salary;
                    $rider_id=$rider_statement->rider_id; 
                    //not found, can pay
                    return '<div>Salary Recieved from Kingriders <button style="display:none;" type="button" id="getting_val" onclick="remaining_pay('.$rider_id.', '.$rider_statement->id.')" data-toggle="modal" data-target="#remaining_pay_modal" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button></div>';
                }
                // updateStatus('.$rider_statement->id.')
                return '<div>Salary Recieved from Kingriders <button style="display:none;" type="button" id="getting_val" data-update onclick="remaining_pay('.$rider_statement->rider_id.','.$rider_statement->id.','.$ra_found['id'].')" data-toggle="modal" data-target="#remaining_pay_modal" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button></div>';
            }
            #salary received by commission based client
            if(strpos($rider_statement->source, 'Weekly Payout')!==false){
                $ras = $rider_statements->toArray();
                $ra_found = Arr::first($ras, function ($item, $key) use ($rider_statement) { 
                    if($item['type']=='skip') return false;
                    return $item['salary_id'] == $rider_statement->salary_id 
                    && $item['type'] == "dr"
                    && $item['rider_id'] == $rider_statement->rider_id
                    && $item['source'] == "salary_paid";
                });
                if(!isset($ra_found)){
                    $salary_paid=Rider_salary::where('settings',$rider_statement->client_income_id)->get()->first();
                    if($salary_paid){
                        $total=$salary_paid->total_salary;
                        $gross=$salary_paid->gross_salary;
                        $rider_id=$rider_statement->rider_id; 
                        //not found, can pay
                        return '<div>'.$rider_statement->source.' <button style="display:none;" type="button" id="getting_val" onclick="remaining_pay('.$rider_id.', '.$rider_statement->id.')" data-toggle="modal" data-target="#remaining_pay_modal" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button></div>';
                    }
                    else {
                        return 'Salary not found';
                    }
                    
                }
                // updateStatus('.$rider_statement->id.')
                return '<div>'.$rider_statement->source.' <button style="display:none;" type="button" id="getting_val" data-update onclick="remaining_pay('.$rider_statement->rider_id.','.$rider_statement->id.','.$ra_found['id'].')" data-toggle="modal" data-target="#remaining_pay_modal" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button></div>';

            }
            if($rider_statement->source == 'salary_paid'){
                return "Salary Paid";
            }
            if($rider_statement->source == 'remaining_salary'){
                return "Remaining Salary";
            }
            
            if ($rider_statement->source == 'advance') {
                $export_data=Export_data::where("source_id",$rider_statement->advance_return_id)->where("source","advance")->get()->first();
                if (isset($export_data)) {
                    return "A".$export_data->id."-".$rider_statement->source;
                }
                return $rider_statement->source;
            }
            if ($rider_statement->source == 'Discipline Fine') {
                if ($rider_statement->desc!="") {
                    $popoverHtml='<button type="button" 
                                    class="btn btn-outline-info btn-elevate btn-icon btn-sm btn-circle ml-3" 
                                    data-toggle="popover" 
                                    data-placement="top" 
                                    data-html="true" 
                                    data-content="'.$rider_statement->desc.'">
                                    <i class="fa fa-exclamation"></i>
                                    </button>';
                    return $rider_statement->source . $popoverHtml;
                }
                return $rider_statement->source;
            }
            return $rider_statement->source;
        })
        ->addColumn('cr', function($rider_statement){
            if($rider_statement->type=='skip') return '';
            if($rider_statement->payment_status=='paid') return 0;
            if ($rider_statement->type=='cr')
            {
                $class = $rider_statement->type=='cr_payable'?'kt-font-danger':'';
                if($rider_statement->payment_status=='paid') $class = 'kt-font-success';
                return '<span class="'.$class.'">'.$rider_statement->amount.'</span>';
            }
            return 0;
        })
        ->addColumn('dr', function($rider_statement){
            if($rider_statement->type=='skip') return '';
            if($rider_statement->payment_status=='paid') return 0;
            if($rider_statement->type=='dr' || $rider_statement->type=='dr_payable' || $rider_statement->type=='cr_payable'){
                $class = $rider_statement->type=='cr_payable'?'kt-font-danger':'';
                if($rider_statement->payment_status=='paid') $class = 'kt-font-success';
                return '<span class="'.$class.'">('.$rider_statement->amount.')</span>';
            }
            return 0;
        })
        ->addColumn('balance', function($rider_statement) use (&$running_balance){
            if($rider_statement->type=='dr' || $rider_statement->type=='dr_payable' || $rider_statement->type=='cr_payable'){
                $running_balance -= round($rider_statement->amount,2);
            }
            else{
                $running_balance += round($rider_statement->amount,2); 
            }
            if($rider_statement->type=='skip') return '<strong >'.round($running_balance,2).'</strong>';
            return round($running_balance,2);
        })
        ->addColumn('action', function($rider_statement) use (&$running_balance){
            if($rider_statement->type=='skip') return '';
            // if(Auth::user()->type!='su')return '';
            $model="";
            $model_id="";
            $rider_id="";
            $month="";
            $string="";
            // foreach ($rider_statement as $key => $value) {
            //     if(strpos($key, "_id") && $key!='rider_id'){
            //         //check if some columns ends with _id postfix
            //         if($value!=null && $value!=''){
            //             $model_id=$value;
            //             $rider_id=$rider_statement->rider_id;
            //             $string=$key;
            //             $month=Carbon::parse($rider_statement->month)->format('m');
            //             $sim_transaction=new Sim_Transaction();
            //             switch ($key) {
            //                 case 'bike_rent_id':
            //                     # code...
            //                     break;
                            
            //                 default:
            //                     # code...
            //                     break;
            //             }
            //             $model=get_class($sim_transaction);
            //         }
            //     }
            // }
            // if($rider_statement->bike_fine!=null){
            //     $model_id=$rider_statement->bike_fine;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="bike_fine";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $modelObj=new Bike_Fine();
            //     $model=get_class($modelObj);
            // }
            // if($rider_statement->bike_rent_id!=null){
            //     $model_id=$rider_statement->bike_rent_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="bike_rent_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $modelObj=null;
            //     $model=null;
            // }
            // if($rider_statement->salary_id!=null){
            //     $model_id=$rider_statement->salary_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="salary_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $modelObj=new Rider_salary();
            //     $model=get_class($modelObj);
            // }
            // if($rider_statement->client_income_id!=null){
            //     $model_id=$rider_statement->client_income_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="client_income_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $modelObj=new Client_Income();
            //     $model=get_class($modelObj);
            // }
            // if($rider_statement->investment_id!=null){
            //     $model_id=$rider_statement->investment_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="investment_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $modelObj=new Company_investment();
            //     $model=get_class($modelObj);
            // }
            // if($rider_statement->income_zomato_id!=null){
            //     $model_id=$rider_statement->source;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="source";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $modelObj=null;
            //     $model=null;
            // }
            // if($rider_statement->advance_return_id!=null){
            //     $model_id=$rider_statement->advance_return_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="advance_return_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $modelObj=new AdvanceReturn();
            //     $model=get_class($modelObj);
            // }
            // if($rider_statement->id_charge_id!=null){
            //     $model_id=$rider_statement->id_charge_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="id_charge_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $modelObj=new Id_charge();
            //     $model=get_class($modelObj);
            // }
            // if($rider_statement->fuel_expense_id!=null){
            //     $model_id=$rider_statement->fuel_expense_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="fuel_expense_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $fuel=new Fuel_Expense();
            //     $model=get_class($fuel);
            // }	
            // if($rider_statement->maintenance_id!=null){
            //     $model_id=$rider_statement->maintenance_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="maintenance_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $modelObj=new Maintenance();
            //     $model=get_class($modelObj);
            // }
            // if($rider_statement->mobile_installment_id!=null){
            //     $model_id=$rider_statement->mobile_installment_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="mobile_installment_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $mobile_installment=new Mobile_installment();
            //     $model=get_class($mobile_installment);
            // }
            // if($rider_statement->edirham_id!=null){
            //     $model_id=$rider_statement->edirham_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="edirham_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $model_obj=new Edirham();
            //     $model=get_class($model_obj);
            // }
            // if($rider_statement->company_expense_id!=null){
            //     $model_id=$rider_statement->company_expense_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="company_expense_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $model_obj=new Company_Expense();
            //     $model=get_class($model_obj);
            // }
            // if($rider_statement->salik_id!=null){
            //     $model_id=$rider_statement->salik_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="salik_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $model_obj=new Salik();
            //     $model=get_class($model_obj);
            // }
            // if($rider_statement->sim_transaction_id!=null){
            //     $model_id=$rider_statement->sim_transaction_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="sim_transaction_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $sim_transaction=new Sim_Transaction();
            //     $model=get_class($sim_transaction);
                
            // }
            // if($rider_statement->mobile_installment_id!=null){
            //     $model_id=$rider_statement->mobile_installment_id;
            //     $rider_id=$rider_statement->rider_id;
            //     $string="mobile_installment_id";
            //     $month=Carbon::parse($rider_statement->month)->format('m');
            //     $mobile_installment=new Mobile_installment();
            //     $model=get_class($mobile_installment);
            // }

            
            
            // if ($model_id!=null) {
            //     $model = addslashes($model);
            //     return '<i class="fa fa-trash-alt tr-remove" onclick="deleteRows('.$rider_statement->id.',\''.$model.'\',\''.$model_id.'\','.$rider_id.',\''.$string.'\',\''.$month.'\')"></i>';
            // }
            // else {
                // no id was found, lets just match the source, rider_id and month
            $model_id=$rider_statement->source;
            $rider_id=$rider_statement->rider_id;
            $string="source";
            $month=Carbon::parse($rider_statement->month)->format('m');
            $year=Carbon::parse($rider_statement->month)->format('Y');
            $modelObj=null;
            $model=null;
            $source_id="";
            $source_key="";
            $given_date=$rider_statement->given_date;
            #if source_id="" edit button will not be shown
            if ($rider_statement->salik_id!=null) {
                $source_id=$rider_statement->salik_id;
                $source_key="salik_id"; 
            }
            if ($rider_statement->bike_rent_id!=null) {
                $source_id=$rider_statement->bike_rent_id;   
                $source_key="bike_rent_id";
            }
            if ($rider_statement->bike_fine!=null) {
                $source_id=$rider_statement->bike_fine;  
                $source_key="bike_fine"; 
            }
            if ($rider_statement->advance_return_id!=null) {
                $source_id=$rider_statement->advance_return_id;  
                $source_key="advance_return_id"; 
            }
            if ($rider_statement->id_charge_id!=null) {
                $source_id=$rider_statement->id_charge_id;   
                $source_key="id_charge_id";
            }
            if ($rider_statement->client_income_id!=null) {
                $source_id=$rider_statement->client_income_id;   
                $source_key="client_income_id";
            }
            if ($rider_statement->employee_allownce_id!=null) {
                $source_id=$rider_statement->employee_allownce_id;  
                $source_key="employee_allownce_id"; 
            }
            if ($rider_statement->fuel_expense_id!=null) {
                $source_id=$rider_statement->fuel_expense_id;   
                $source_key="fuel_expense_id";
            }
            if ($rider_statement->sim_transaction_id!=null) {
                $source_id=$rider_statement->sim_transaction_id; 
                $source_key="sim_transaction_id";  
            }
            if ($rider_statement->mobile_installment_id!=null) {
                $source_id=$rider_statement->mobile_installment_id; 
                $source_key="mobile_installment_id";  
            }
            if ($rider_statement->kingrider_fine_id!=null) {
                $source_id=$rider_statement->kingrider_fine_id;  
                $source_key="kingrider_fine_id"; 
            }


            $editHTML = '<i class="fa fa-edit tr-edit" onclick="editRows(this,'.$rider_statement->id.',\''.$model.'\',\''.$model_id.'\','.$rider_id.',\''.$string.'\',\''.$month.'\')"></i>';
            $UpdateHTML='';
            $deleteHTML='';
            /**
             * Skip
             * -Salary row
             */
            if($rider_statement->salary_id!=null){
                //skip edit
                $editHTML='';
            }
            if($source_id!=''){
                $UpdateHTML = '<i class="fa fa-pencil-alt tr-edit" onclick="UpdateRows(this,'.$rider_statement->id.',\''.$model.'\',\''.$model_id.'\','.$rider_id.',\''.$string.'\',\''.$month.'\',\''.$year.'\',\''.$source_id.'\',\''.$source_key.'\',\''.$given_date.'\')"></i>';
                $deleteHTML='<i class="fa fa-trash-alt tr-remove" onclick="deleteRows('.$rider_statement->id.',\''.$model.'\',\''.$model_id.'\','.$rider_id.',\''.$string.'\',\''.$month.'\',\''.$year.'\',\''.$source_id.'\',\''.$source_key.'\',\''.$given_date.'\')"></i>';
            }else {
                # we don't want to trace it, so it will update current row only
                $UpdateHTML = '<i class="fa fa-pencil-alt tr-edit text-warning" onclick="UpdateRows(this,'.$rider_statement->id.',\''.$model.'\',\''.$model_id.'\','.$rider_id.',\''.$string.'\',\''.$month.'\',\''.$year.'\',\''.$source_id.'\',\''.$source_key.'\',\''.$given_date.'\')"></i>';
                $deleteHTML='<i class="fa fa-trash-alt tr-remove text-warning" onclick="deleteRows('.$rider_statement->id.',\''.$model.'\',\''.$model_id.'\','.$rider_id.',\''.$string.'\',\''.$month.'\',\''.$year.'\',\''.$source_id.'\',\''.$source_key.'\',\''.$given_date.'\')"></i>';
            }
            if ($model_id=="Sim extra usage" || $model_id=="Salik Extra") {
                $UpdateHTML='';
            }
            return $UpdateHTML.$deleteHTML;
            // }
        })
        ->addColumn('cash_paid', function($rider_statement) use (&$cash_paid){
            if($rider_statement->payment_status=='paid'){
                // if($rider_statement->type=='dr' || $rider_statement->type=='dr_payable' || $rider_statement->type=='cr_payable'){
                //     $cash_paid -= $rider_statement->amount;
                // }
                // else{
                    $cash_paid += $rider_statement->amount;
                //}
                return $rider_statement->amount;
            }
            // if($rider_statement->type=='skip') return '<strong >'.round($cash_paid,2).'</strong>';
            return  0;
        })
        ->with([
            'closing_balance_prev'=>round($closing_balance_prev,2),
            'closing_balance' => round($closing_balance,2),
            'rider_debits_cr_prev_payable'=>$rider_debits_cr_prev_payable,
            'rider_debits_dr_prev_payable'=>$rider_debits_dr_prev_payable,
            'rider'=>$rider,
            'rider_detail'=>$rider__detail,
            'month_year'=>Carbon::parse($from)->format('M, Y'),
            'today_date'=>Carbon::parse($date)->format('m/d/Y'),
            'employee_id'=>$ranges['rider_id'],
            'payment_date'=>Carbon::now()->format('M d, Y'),
            'salary'=>$salary,
            'trips'=>$trips,
            'extra_trips'=>$extra_trips,
            'hours'=>round($hours,2),
            'bike_allowns'=>$bike_allowns,
            'bike_fine'=>$bike_fine,
            'ncw'=>$ncw,
            'tip'=>$tip,
            'bones'=>$bones,
            'advance'=>$advance,
            'salik'=>$salik,
            'sim'=>round($sim,2),
            'dc'=>$dc,
            'macdonald'=>0,
            'rta'=>$rta,
            'mobile'=>$mobile,
            'dicipline'=>$dicipline,
            'denial_penalty'=>$denial_penalty,
            'mics'=>$mics,
            'cash_paid'=>$cash_paid_in_advance,
            'salary_paid'=>$salary_paid,
            'salary_slip'=>$salary_slip,
        ])
    
        ->rawColumns(['action','closing_balance','cash_paid','desc','date','cr','dr','balance'])
        ->make(true);
    }
    public function getCompanyAccounts($ranges)
    {
        $ranges = json_decode($ranges, true);
        $from = date($ranges['range']['start_date']);
        $to = date($ranges['range']['end_date']);
        $company_statements = collect([]);
        $company_statements_RAW = \App\Model\Accounts\Company_Account::where("rider_id",$ranges['rider_id'])
        // ->where(function($q) {
        //     $q->whereNotNull('fuel_expense_id')
        //       ->orWhere('maintenance_id', '!=', null)
        //       ->orWhere('sim_transaction_id', '!=', null)
        //       ->orWhere('salik_id', '!=', null)
        //       ->orWhere('salary_id', '!=', null);
        // })
        // ->where('payment_status','paid')
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->get();
        // ->whereDate('created_at', '>=',$from)
        // ->whereDate('created_at', '<=',$to)

        foreach ($company_statements_RAW as $company_statement) {
            $continue = false;
            if($company_statement->fuel_expense_id != null || 
            $company_statement->maintenance_id != null ||
            $company_statement->sim_transaction_id != null ||
            $company_statement->salik_id != null ||
            $company_statement->bike_fine != null ||
            $company_statement->bike_rent_id != null    ){
                if($company_statement->payment_status=="pending" && ($company_statement->source!='Sim extra usage')){
                    //skip this
                    $continue = true; 
                }
            }

            if(!$continue){
                $company_statements->push($company_statement);
            }
        }
        
        $Client_Rider = Client_Rider::where('rider_id', $ranges['rider_id'])->get()->first();
        $_feid=null;
        if(isset($Client_Rider)){
            $_feid=$Client_Rider->client_rider_id;
        } 

        
        $c_debits_cr_payable = \App\Model\Accounts\Company_Account::where("rider_id",$ranges['rider_id'])
        ->where(function($q) {
            $q->where('type', "dr_receivable")
              ->orWhere('type', 'cr');
        })
        ->sum('amount');
        
        $c_debits_dr_payable = \App\Model\Accounts\Company_Account::where("rider_id",$ranges['rider_id'])
        ->where('type', 'dr')
        ->sum('amount');
        
        //
        $c_debits_rn_cr_payable = \App\Model\Accounts\Company_Account::where("rider_id",$ranges['rider_id'])
        ->where(function($q) {
            $q->where('type', "dr_receivable")
              ->orWhere('type', 'cr');
        })
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->sum('amount');
        
        $c_debits_rn_dr_payable = \App\Model\Accounts\Company_Account::where("rider_id",$ranges['rider_id'])
        ->where('type', 'dr')
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->sum('amount');

        $c_debits_rn_pl_payable = \App\Model\Accounts\Company_Account::where("rider_id",$ranges['rider_id'])
        ->where('type', 'pl')
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->sum('amount');
        $running_static_balance = $c_debits_rn_cr_payable - $c_debits_rn_dr_payable - $c_debits_rn_pl_payable;
        //
        $c_debits_rn_pl_total = \App\Model\Accounts\Company_Account::where("rider_id",$ranges['rider_id'])
        ->where('type', 'pl')
        ->whereDate('month', '<',$from)
        ->sum('amount');
        // $profit = $c_debits_rn_pl_total;
        $profit = 0;

        $c_debits_cr_prev_payable = \App\Model\Accounts\Company_Account::where("rider_id",$ranges['rider_id'])
        ->where(function($q) {
            $q->where('type', "dr_receivable")
              ->orWhere('type', 'cr');
        })
        ->whereDate('month', '<',$from)
        ->sum('amount');
        
        $c_debits_dr_prev_payable = \App\Model\Accounts\Company_Account::where("rider_id",$ranges['rider_id'])
        ->where(function($q) {
            $q->where('type', "dr")
            ->orWhere('type', 'pl');
        })
        ->whereDate('month', '<',$from)
        ->sum('amount');

        $closing_balance = $c_debits_cr_payable - $c_debits_dr_payable;
        $first_month = Carbon::now()->format('Y-m-d');
        if(count($company_statements) > 0){
            $first_month = $company_statements->last()->month;
        }
        

        $closing_balance_prev = $c_debits_cr_prev_payable - $c_debits_dr_prev_payable;
        // $running_balance =$closing_balance_prev;
        $running_balance =0;

        $flag = new \App\Model\Accounts\Company_Account;
        $flag->month='';
        $flag->source='Opening Balance';
        $flag->type='skip';
        $flag->amount=0;
        $company_statements->prepend($flag);

        $flag = new \App\Model\Accounts\Company_Account;
        $flag->month='';
        $flag->source='Closing Balance';
        $flag->type='skip';
        $flag->amount=0;
        $company_statements->push($flag);
        return DataTables::of($company_statements)
        ->addColumn('date', function($company_statements){
            if($company_statements->type=='skip') return '';
            if($company_statements->given_date==null || $company_statements->given_date=='') return 'No given date';
            return Carbon::parse($company_statements->given_date)->format('M d, Y');
        })
        ->addColumn('description', function($company_statement)  use ($company_statements){
            if($company_statement->type=='skip') return '<strong >'.$company_statement->source.'</strong>';
            $ras = $company_statements->toArray();
            $income_zomato =Income_zomato::all()->toArray();
            $cdesc=$company_statement->desc!=null?$company_statement->desc:$company_statement->source;
            if($company_statement->source == 'Bike Fine'){
                $ca_found = Arr::first($ras, function ($item, $key) use ($company_statement) { 
                    if($item['type']=='skip') return false;
                    return $item['bike_fine'] == $company_statement->bike_fine 
                    && $item['type'] == "cr"
                    && $item['rider_id'] == $company_statement->rider_id
                    && $item['source'] == "Bike Fine Paid";
                });
                if(!isset($ca_found)){
                    $rider_id=$company_statement->rider_id;
                    $amount=$company_statement->amount;
                    $bike_fine_id=$company_statement->bike_fine;
                    $month=$company_statement->month;
                    $given_date=$company_statement->given_date;
                    return '<div>Fine Paid By Kingriders <button type="button" id="getting_val" onclick="FineBike('.$amount.','.$rider_id.','.$bike_fine_id.',\''.$month.'\',\''.$given_date.'\')" data-toggle="modal" data-target="#bike_fine" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button></div>';
                }
                return "Bike Fine Paid By King Riders";
            }
            if($company_statement->source == 'Zomato Payout' || $company_statement->source == 'Jeebly Payout'){
                $tips_found = Arr::first($ras, function ($item, $key) use ($company_statement) { 
                    if($item['type']=='skip') return false;
                    return $item['income_zomato_id'] == $company_statement->income_zomato_id
                    && $item['source'] == "Tips Payouts";
                });
                $DC_found = Arr::first($ras, function ($item, $key) use ($company_statement) { 
                    if($item['type']=='skip') return false;
                    return $item['income_zomato_id'] == $company_statement->income_zomato_id
                    && $item['source'] == "DC Deductions";
                });
                $penalty_found = Arr::first($ras, function ($item, $key) use ($company_statement) { 
                    if($item['type']=='skip') return false;
                    return $item['income_zomato_id'] == $company_statement->income_zomato_id
                    && $item['source'] == "Denials Penalty";
                });
                $ncw_found = Arr::first($ras, function ($item, $key) use ($company_statement) { 
                    if($item['type']=='skip') return false;
                    return $item['income_zomato_id'] == $company_statement->income_zomato_id
                    && $item['source'] == "NCW Incentives";
                });
                $tips_amount=0;
               if (isset($tips_found)) {
                    $tips_amount=$tips_found['amount'];
               }
               $dc_amount=0;
               if (isset($DC_found)) {
                    $dc_amount=$DC_found['amount'];
               }
               $penalty_amount=0;
               if (isset($penalty_found)) {
                    $penalty_amount=$penalty_found['amount'];
               }
               $ncw_amount=0;
               if (isset($ncw_found)) {
                    $ncw_amount=$ncw_found['amount'];
               }
               $trips_found = Arr::first($income_zomato, function ($item, $key) use ($company_statement) { 
                if($item['p_id']!=$company_statement->income_zomato_id) return false;
                return $item['rider_id'] == $company_statement->rider_id
                && $item['date'] == $company_statement->month
                && $item['p_id'] == $company_statement->income_zomato_id;
                });
                $hours_found = Arr::first($income_zomato, function ($item, $key) use ($company_statement) { 
                if($item['p_id']!=$company_statement->income_zomato_id) return false;
                return $item['rider_id'] == $company_statement->rider_id
                && $item['date'] == $company_statement->month
                && $item['p_id'] == $company_statement->income_zomato_id;
                });
                $trips=0;
                $settlements=0;
                if (isset($trips_found)) {
                    $trips=$trips_found['trips_payable'];
                    $settlements=$trips_found['settlements'];
                }
                $hours=0;
                if (isset($hours_found)) {
                        $hours=$hours_found['log_in_hours_payable'];
                }
                $AED_hours=$hours*6;
                $AED_trips=$trips*6.75;
                $total_zomato_payout=$AED_hours+$AED_trips;
                $zomato_payout=$cdesc.
                "<br>(<strong>Trips: </strong>".$trips. 
                "<br><strong>Hours: </strong>".$hours. 
                "<br><strong>AED-Trips: </strong>".$AED_trips.
                "<br><strong>AED-Hours: </strong>".$AED_hours.
                "<br><strong>Final Payout: </strong>".$total_zomato_payout.
                "<br><strong>Tips: </strong>".$tips_amount.
                "<br><strong>DC Deduction: </strong>".$dc_amount.
                "<br><strong>Denial Penalty: </strong>".$penalty_amount.
                "<br><strong>NCW Incentives: </strong>".$ncw_amount.")";
                return $zomato_payout; 

            }
            if ($company_statement->source=="fuel_expense_vip") {
                $ed=Export_data::where("source_id",$company_statement->fuel_expense_id)
                ->where("source","fuel_expense_vip")
                ->get()
                ->first();
                if (isset($ed)) {
                    return "F".$ed->id."-".$cdesc;
                }
                return $cdesc;
            }
            if ($company_statement->source=="fuel_expense_cash") {
                $ed=Export_data::where("source_id",$company_statement->fuel_expense_id)
                ->where("source","fuel_expense_cash")
                ->get()
                ->first();
                if (isset($ed)) {
                    return "F".$ed->id."-".$cdesc;
                }
                return $cdesc;
            }
            if ($company_statement->source=="Sim Transaction") {
                $ed=Export_data::where("source_id",$company_statement->sim_transaction_id)
                ->where("source","Sim Transaction")
                ->get()
                ->first();
                if (isset($ed)) {
                    return "S".$ed->id."-".$cdesc;
                }
                return $cdesc;
            }
            if ($company_statement->source=="Bike Rent") {
                $ed=Export_data::where("source_id",$company_statement->bike_rent_id)
                ->where("source","Bike Rent")
                ->get()
                ->first();
                if (isset($ed)) {
                    return "BR".$ed->id."-".$cdesc;
                }
                return $cdesc;
            }
            if ($company_statement->source=="Salik") {
                $ed=Export_data::where("source_id",$company_statement->salik_id)
                ->where("source","Salik")
                ->get()
                ->first();
                if (isset($ed)) {
                    return "S".$ed->id."-".$cdesc;
                }
                return $cdesc;
            }
            return $cdesc;
        })
        ->addColumn('cr', function($company_statements){
            if($company_statements->type=='pl') return 0;
            if($company_statements->type=='skip') return '';
            if ($company_statements->type=='cr' || $company_statements->type=='dr_receivable')
            {
                return '<span >'.$company_statements->amount.'</span>';
            }
            return 0;
        })
        ->addColumn('dr', function($company_statements){
            if($company_statements->type=='pl') return 0;
            if($company_statements->type=='skip') return '';
            if($company_statements->type=='dr'){
                return '<span>('.$company_statements->amount.')</span>';
            }
            return 0;
        })
        ->addColumn('company_profit', function($company_statements) use (&$profit){
            
            if($company_statements->type=='pl'){
                $profit +=$company_statements->amount;
                return round($company_statements->amount, 2);
            }
            if($company_statements->type=='skip') return '<strong>'.round($profit, 2).'</strong>';
            return 0;
        })
        ->addColumn('balance', function($company_statements) use (&$running_balance){
            
            if($company_statements->type=='dr' || $company_statements->type=='pl'){
                $running_balance -= $company_statements->amount;
            }
            else{
                $running_balance += $company_statements->amount;
            }
            $_id = $company_statements->source=="Closing Balance"? 'running_closing_balance':'running_opening_balance';
            // if($company_statements->type=='pl') return 0;
            if($company_statements->type=='skip') return '<strong id="'.$_id.'"> '.round($running_balance,2).'</strong>';
            return round($running_balance,2);
        })
        ->addColumn('action', function($company_statements) use (&$running_balance){
            if($company_statements->type=='skip') return '';
            // if(Auth::user()->type!='su')return '';
            

            
           
            // if ($model_id!=null) {
            //     $model = addslashes($model);
            //     return '<i class="fa fa-trash-alt"  onclick="deleteCompanyRows('.$company_statements->id.',\''.$model.'\','.$model_id.','.$rider_id.',\''.$string.'\',\''.$month.'\')"></i>';
            // } 
            $model_id=addslashes($company_statements->source);
            $rider_id=$company_statements->rider_id;
            $string="source";
            $month=Carbon::parse($company_statements->month)->format('m');
            $year=Carbon::parse($company_statements->month)->format('Y');
            $modelObj=null;
            $model=null;
            $source_id="";
            $source_key="";
            $given_date=$company_statements->given_date;
            #if source_id="" edit button will not be shown
            if ($company_statements->salik_id!=null) {
                $source_id=$company_statements->salik_id;
                $source_key="salik_id"; 
            }
            if ($company_statements->bike_rent_id!=null) {
                $source_id=$company_statements->bike_rent_id;   
                $source_key="bike_rent_id";
            }
            if ($company_statements->bike_fine!=null) {
                $source_id=$company_statements->bike_fine;  
                $source_key="bike_fine"; 
            }
            if ($company_statements->advance_return_id!=null) {
                $source_id=$company_statements->advance_return_id;  
                $source_key="advance_return_id"; 
            }
            if ($company_statements->id_charge_id!=null) {
                $source_id=$company_statements->id_charge_id;   
                $source_key="id_charge_id";
            }
            if ($company_statements->client_income_id!=null) {
                $source_id=$company_statements->client_income_id;   
                $source_key="client_income_id";
            }
            if ($company_statements->employee_allownce_id!=null) {
                $source_id=$company_statements->employee_allownce_id;  
                $source_key="employee_allownce_id"; 
            }
            if ($company_statements->fuel_expense_id!=null) {
                $source_id=$company_statements->fuel_expense_id;   
                $source_key="fuel_expense_id";
            }
            if ($company_statements->sim_transaction_id!=null) {
                $source_id=$company_statements->sim_transaction_id; 
                $source_key="sim_transaction_id";  
            }
            if ($company_statements->mobile_installment_id!=null) {
                $source_id=$company_statements->mobile_installment_id; 
                $source_key="mobile_installment_id";  
            }
            if ($company_statements->kingrider_fine_id!=null) {
                $source_id=$company_statements->kingrider_fine_id;  
                $source_key="kingrider_fine_id"; 
            }
            // if ($company_statements->income_zomato_id!=null) {
            //     $source_id=$company_statements->income_zomato_id;  
            //     $source_key="income_zomato_id"; 
            // }
            $editHTML = '<i class="fa fa-edit tr-edit" onclick="editRows(this,'.$company_statements->id.',\''.$model.'\',\''.$model_id.'\','.$rider_id.',\''.$string.'\',\''.$month.'\')"></i>';
            $UpdateHTML='';
            $deleteHTML='';

            if($company_statements->salary_id!=null || $company_statements->sim_transaction_id!=null || $company_statements->bike_rent_id!=null|| $company_statements->salik_id!=null){
                //skip edit
                $editHTML='';
            }
            if($source_id!=''){
                $UpdateHTML = '<i class="fa fa-pencil-alt tr-edit" onclick="UpdateRows(this,'.$company_statements->id.',\''.$model.'\',\''.$model_id.'\','.$rider_id.',\''.$string.'\',\''.$month.'\',\''.$year.'\',\''.$source_id.'\',\''.$source_key.'\',\''.$given_date.'\')"></i>';
                $deleteHTML='<i class="fa fa-trash-alt tr-remove" onclick="deleteRows('.$company_statements->id.',\''.$model.'\',\''.$model_id.'\','.$rider_id.',\''.$string.'\',\''.$month.'\',\''.$year.'\',\''.$source_id.'\',\''.$source_key.'\',\''.$given_date.'\')"></i>';
            }else {
                # we don't want to trace it, so it will update current row only
                $UpdateHTML = '<i class="fa fa-pencil-alt tr-edit text-warning" onclick="UpdateRows(this,'.$company_statements->id.',\''.$model.'\',\''.$model_id.'\','.$rider_id.',\''.$string.'\',\''.$month.'\',\''.$year.'\',\''.$source_id.'\',\''.$source_key.'\',\''.$given_date.'\')"></i>';
                $deleteHTML='<i class="fa fa-trash-alt tr-remove text-warning" onclick="deleteRows('.$company_statements->id.',\''.$model.'\',\''.$model_id.'\','.$rider_id.',\''.$string.'\',\''.$month.'\',\''.$year.'\',\''.$source_id.'\',\''.$source_key.'\',\''.$given_date.'\')"></i>';
            }
            if ($model_id=="Sim extra usage" || $model_id=="Salik Extra") {
                $UpdateHTML='';
                $deleteHTML='';
            }
            
            return $UpdateHTML.$deleteHTML;

        })
        
        ->with([
            'closing_balance' => round($closing_balance,2),
            'last_month' => $first_month,
            'running_static_balance' => $running_static_balance,
            'feid'=>$_feid
        ])
        ->rawColumns(['description','date','cr','dr','balance', 'company_profit','action'])
        ->make(true);
    }
    
    public function getCompanyOverallAccounts($ranges)
    {
        $ranges = json_decode($ranges, true);
        $from = date($ranges['range']['start_date']);
        $to = date($ranges['range']['end_date']);
        $company_statements = collect([]);
// total investment
        $total_investment=Company_Account::where("type","cr")
        ->where("source","Investment")
        ->where("payment_status","paid")
        ->sum("amount");
// end total investment

// total profit
        $total_profit=Company_Account::where('type','pl')
        ->sum("amount");
// end total profit

// overall_balnce
        $overall_balnce_cr=Company_Account::where('type','cr')
        ->orWhere('type','dr_receivable')
        ->sum("amount");

        $overall_balnce_dr=Company_Account::where('type','dr')
        ->sum("amount");
        
        $overall_balnce=$overall_balnce_cr-$overall_balnce_dr;
// end overall_balnce

// payable_to_riders
        $payable_to_riders_cr=Company_Account::whereNotNull('rider_id')
        ->where(function($q) {
            $q->where('type','cr')
            ->orWhere('type','dr_receivable');
        })
        ->sum('amount');

        $payable_to_riders_dr=Company_Account::whereNotNull('rider_id')
        ->where('type','dr')
        ->sum('amount');

        $payable_to_riders=$payable_to_riders_cr-$payable_to_riders_dr;
//  end payable_to_riders

// overall_balnce_monthly
        $overall_balnce_cr_monthly=Company_Account::whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where(function($q) {
            $q->where('type','cr')
            ->orWhere('type','dr_receivable')
            ->orWhere('type','pl');
        })
        ->sum("amount");

        $overall_balnce_dr_monthly=Company_Account::whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where("type","dr")
        ->sum("amount");

        $overall_balnce_monthly=$overall_balnce_cr_monthly-$overall_balnce_dr_monthly;
//  end overall_balnce_monthly


        
        $company_statements_RAW = \App\Model\Accounts\Company_Account::whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where(function($q) {
            $q->where('type', "cr")
              ->orWhere('type', 'pl');
        })
        ->where("payment_status","paid")
        ->get();

        return DataTables::of($company_statements_RAW)
        ->addColumn('date', function($company_statements_RAW){
         return "asd";
        })
        
       
        ->addColumn('description', function($company_statements_RAW){
          return $company_statements_RAW->source;
        })
        ->addColumn('cr', function($company_statements_RAW) {
            if ($company_statements_RAW->type=="pl") {
                return  $company_statements_RAW->amount;
            }
            return 0 ;
        })
        ->addColumn('dr', function($company_statements_RAW) {
            if ($company_statements_RAW->payment_status=="paid" && $company_statements_RAW->type=="cr") {
                return  $company_statements_RAW->amount;
            }
            return 0 ;
        })
        
        ->with([
            'overall_balnce_monthly' => round($overall_balnce_monthly,2),
            'total_profit' => round($total_profit,2),
            'overall_balnce' => round($overall_balnce,2),
            'payable_to_riders'=>round($payable_to_riders,2),
            'total_investment'=>round($total_investment,2),
        ])
        ->rawColumns(['description','date','cr','dr'])
        ->make(true);
    }

    public function getMaintenances()
    {
        $maintenances = Maintenance::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($maintenances)
        ->addColumn('status', function($maintenance){
            if($maintenance->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($maintenance){
            return '1000'.$maintenance->id;
        })
        ->addColumn('maintenance_type', function($maintenance){
            return $maintenance->maintenance_type;
        })
        ->addColumn('workshop', function($maintenance){
            $workshop = Workshop::find($maintenance->workshop_id);
            return $workshop->name;
        })
        ->addColumn('bike', function($maintenance){
            $bike = bike::find($maintenance->bike_id);
            return $bike->bike_number;
        })
        ->addColumn('amount', function($maintenance){
            return $maintenance->amount;
        })
        ->addColumn('created_at', function($maintenance){
            return Carbon::parse($maintenance->created_at)->diffForHumans();
        })
        ->addColumn('actions', function($maintenance){
            $status_text = $maintenance->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.maintenance_edit_view', $maintenance).'"><i class="fa fa-edit"></i> View</a>
                    <button class="dropdown-item" onclick="updateStatus('.$maintenance->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteRow('.$maintenance->id.');"><i class="fa fa-trash"></i> Delete</button>
                </div>
            </span>
        </span>';
        })
        ->rawColumns(['maintenance_type','workshop','bike','address','amount', 'status', 'actions'])
        ->make(true);
    }

    public function getWorkShops()
    {
        $workshops = Workshop::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($workshops)
        ->addColumn('status', function($workshop){
            if($workshop->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($workshop){
            return '1000'.$workshop->id;
        })
        ->addColumn('name', function($workshop){
            return $workshop->name;
        })
        ->addColumn('address', function($workshop){
            return $workshop->address;
        })
        ->addColumn('actions', function($workshop){
            $status_text = $workshop->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.workshop_edit_view', $workshop).'"><i class="fa fa-edit"></i> view</a>
                    <button class="dropdown-item" onclick="updateStatus('.$workshop->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteRow('.$workshop->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['name','address','actions', 'status'])
        ->make(true);
    }

    public function getEdirhams()
    {
        $edirhams = Edirham::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($edirhams)
        ->addColumn('status', function($edirham){
            if($edirham->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($edirham){
            return '1000'.$edirham->id;
        })
        ->addColumn('amount', function($edirham){
            return $edirham->amount;
        })
        ->addColumn('created_at', function($edirham){
            return Carbon::parse($edirham->created_at)->diffForHumans();
        })
        ->addColumn('actions', function($edirham){
            $status_text = $edirham->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.edirham_edit_view', $edirham).'"><i class="fa fa-edit"></i> View</a>
                    <button class="dropdown-item" onclick="updateStatus('.$edirham->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteRow('.$edirham->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['amount','actions', 'created_at','status'])
        ->make(true);
    }

    public function getFuelExpense()
    {
        $expense = Fuel_Expense::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($expense)
        ->addColumn('id', function($expense){
            return $expense->id;
        })
        ->addColumn('date', function($expense){
            return Carbon::parse($expense->month)->format("F");
        })
        ->addColumn('bike_id', function($expense){
            $bike = bike::find($expense->bike_id);
            if (isset($bike)) {
                return $bike->bike_number;
            }
            return 'No Bike Assigned';
        })
        ->addColumn('rider_id', function($expense){
            $rider = Rider::find($expense->rider_id);
            if (isset($rider)) {
                return $rider->name;
            }
            return 'No Rider Assigned';
        })
        ->addColumn('rider_id_id', function($expense){
            $rider = Rider::find($expense->rider_id);
            if (isset($rider)) {
                return "KR".$rider->id;
            }
            return 'No Rider Id Assigned';
        })
        ->addColumn('actions', function($expense){
            $status_text = $expense->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.edit_fuel_expense_view', $expense).'"><i class="fa fa-edit"></i> View</a>
                    <button class="dropdown-item" onclick="deleteRow('.$expense->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['date','rider_id_id','status','bike_id','type','amount','actions', 'rider_id'])
        ->make(true);
    }

    public function getCompanyExpense()
    {
        $expense = Company_Expense::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($expense)
        ->addColumn('id', function($expense){
            return '1000'.$expense->id;
        })
        ->addColumn('amount', function($expense){
            return $expense->amount;
        })
        ->addColumn('type', function($expense){
            return $expense->type;
        })
        ->addColumn('description', function($expense){
            return $expense->description;
        })
        ->addColumn('month', function($expense){
            $date=Carbon::parse($expense->month)->format('d M, Y');
            return $date;
        })
        
        ->addColumn('actions', function($expense){
            $status_text = $expense->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.CE_edit_view', $expense).'"><i class="fa fa-edit"></i> VIew</a>
                    <button class="dropdown-item" onclick="deleteRow('.$expense->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['status','description','type','amount','actions', 'month'])
        ->make(true);
    }
    public function getCompanyInvestment()
    {
        $investments = Company_investment::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($investments)
        ->addColumn('status', function($investment){
            if($investment->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($investment){
            return '1000'.$investment->id;
        })
        ->addColumn('amount', function($investment){
            return $investment->amount;
        })
        ->addColumn('date', function($investment){
            return Carbon::parse($investment->month)->format('d M, Y');
        })
        ->addColumn('description', function($investment){
            return $investment->notes;
        })
       
        ->addColumn('actions', function($investment){
            $status_text = $investment->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.kr_investment_edit_view', $investment).'"><i class="fa fa-edit"></i> VIew</a>
                    <button class="dropdown-item" onclick="updateStatus('.$investment->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteRow('.$investment->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['status','date','description','type','amount','actions', 'status'])
        ->make(true);
    }

    public function getWPS()
    {
        $wps = WPS::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($wps)
        ->addColumn('status', function($wps){
            if($wps->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($wps){
            return '1000'.$wps->id;
        })
        ->addColumn('amount', function($wps){
            return $wps->amount;
        })
        ->addColumn('bank_name', function($wps){
            return $wps->bank_name;
        })
        ->addColumn('payment_status', function($wps){
            return $wps->payment_status;
        })
        ->addColumn('rider_id', function($wps){
            $rider=Rider::find($wps->rider_id);
            if (isset($rider)) {
                return $rider->name ;
            }
            return 'No rider is assigned';
            
        })
       
        ->addColumn('actions', function($wps){
            $status_text = $wps->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.wps_edit_view', $wps).'"><i class="fa fa-edit"></i> View</a>
                    <button class="dropdown-item" onclick="updateStatus('.$wps->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteRow('.$wps->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['status','bank_name','rider_id','payment_status','amount','actions', 'status'])
        ->make(true);
    }
    public function getAR()
    {
        $ar = AdvanceReturn::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($ar)
        ->addColumn('status', function($ar){
            if($ar->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($ar){
            return $ar->id;
        })
        ->addColumn('amount', function($ar){
            return $ar->amount;
        })
        ->addColumn('month', function($ar){
            return Carbon::parse($ar->month)->format('d M, Y');
        })
        ->addColumn('type', function($ar){
            return $ar->type;
        })
        ->addColumn('payment_status', function($ar){
            return $ar->payment_status;
        })
        ->addColumn('rider_id', function($ar){
            $rider=Rider::find($ar->rider_id);
            if (isset($rider)) {
                return $rider->name ;
            }
            return 'No rider is assigned' ;
        })
       
        ->addColumn('actions', function($ar){
            $status_text = $ar->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.AR_edit_view', $ar).'"><i class="fa fa-edit"></i> View</a>
                    <button class="dropdown-item" onclick="updateStatus('.$ar->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteRow('.$ar->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['status','month','type','rider_id','payment_status','amount','actions', 'status'])
        ->make(true);
    }

    public function getclient_income()
    {
        $client_income = Client_Income::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($client_income)
        ->addColumn('status', function($client_income){
            if($client_income->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($client_income){
            return '1000'.$client_income->id;
        })
        ->addColumn('amount', function($client_income){
            return $client_income->total_payout;
        })
        ->addColumn('rider_id', function($client_income){
            $rider=Rider::find($client_income->rider_id);
            if (isset($rider)) {
                return $rider->name;
            }
            return 'No Rider is assigned';
        })
        ->addColumn('month', function($client_income){
            return $client_income->month;
        })
        ->addColumn('client_id', function($client_income){
            $client=Client::find($client_income->client_id);
            return $client->name ;
        })
       
        ->addColumn('actions', function($client_income){
            $status_text = $client_income->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.client_income_edit_view', $client_income).'"><i class="fa fa-edit"></i> View</a>
                    <button class="dropdown-item" onclick="updateStatus('.$client_income->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteRow('.$client_income->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['status','rider_id','month','client_id','amount','actions', 'status'])
        ->make(true);
    }
    public function getCE_REPORT($month)
    {  
        $only_month = Carbon::parse($month)->format('m');
        $only_year =  Carbon::parse($month)->format('Y');
        $CE=Company_Expense::where("active_status","A")->whereMonth('month', $only_month)->whereYear('month',$only_year)->get();
        $total_expense=Company_Expense::where("active_status","A")->whereMonth('month', $only_month)->whereYear('month',$only_year)->sum("amount");
        
        $flag = new Company_Expense;
        $flag->month='temp';
        $flag->description='<strong>Total Expense</strong>';
        $flag->amount='<strong>'.$total_expense.'</strong>';
        $CE->push($flag);
        
        return DataTables::of($CE)
        
        ->addColumn('date', function($CE){
            if($CE->month=='temp') return '';
            $month_year=Carbon::parse($CE->month)->format('M-Y');
           return $month_year;
        }) 
        ->addColumn('description', function($CE){
           return $CE->description;
        }) 
        ->addColumn('amount', function($CE){
            return $CE->amount;
        }) 
       
        ->with([
            'closing_balance' => $total_expense
        ])
               
        ->rawColumns(['closing_balance','date','description','amount'])
        ->make(true);
    }

    public function getKR_bikes($ranges) 
    {
        $ranges = json_decode($ranges, true);
        $from = date($ranges['range']['start_date']);
        $to = date($ranges['range']['end_date']);
        $rider_id = $ranges['rider_id'];

        $bills = collect([]);

        $month = Carbon::parse($to)->format('m');
        //sim
        $model = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->where("type","dr")
        ->whereNotNull('sim_transaction_id')
        ->get()
        ->first();
        if(isset($model)){
            $model->source = "Sim Usage";
            $bills->push($model);
        }
        //Salik
        $model = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->where("type","dr")
        ->whereNotNull('salik_id')
        ->get()
        ->first();
        if(isset($model)){
            $model->source = "Salik";
            $bills->push($model);
        }
        //fuel_expense
        $model = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->whereNotNull('fuel_expense_id')
        ->get()
        ->first();
        if(isset($model)){
            $model->source = "Fuel Expense";
            $bills->push($model);
        }
        //maintenance
        $model = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->whereNotNull('maintenance_id')
        ->get()
        ->first();
        if(isset($model)){
            $model->source = "Bike Maintenance";
            $bills->push($model);
        }
        //bike_rent
        $model = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->whereNotNull('bike_rent_id')
        ->get()
        ->first();
        if(isset($model)){
            $model->source = "Bike Rent";
            $bills->push($model);
        }


        return DataTables::of($bills)
        ->addColumn('date', function($bill){
            return Carbon::parse($bill->month)->format('M d, Y');
        })
        ->addColumn('bill', function($bill){
            return $bill->source;
        })
        ->addColumn('amount', function($bill){
            return $bill->amount;
        })
        ->addColumn('payment_status', function($bill){
            if($bill->payment_status == 'pending'){
                //enable pay
                return '<div>Pending <button type="button" onclick="updateStatus('.$bill->id.')" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button></div>';
            }
            
            return ucfirst($bill->payment_status).' <i class="flaticon2-correct text-success h5"></i>';
        })
        ->addColumn('action', function($bill){

            return '';
        })
        // ->with([
        //     'closing_balance' => round($closing_balance,2)
        // ])
        ->rawColumns(['amount','bill','payment_status','date','action'])
        ->make(true);

        // return response()->json([
        //     'data'=>$bills
        // ]);

    }
    public function getActivityLog()
    {
        $LA=Log_activity::where("active_status","A")->get();
        return DataTables::of($LA)
        ->addColumn('id', function($LA) {
                return '100'.$LA->id;
        }) 
        ->addColumn('month', function($LA) {
            return Carbon::parse($LA->created_at)->format('d M, Y');
         })
        ->addColumn('description', function($LA) {
            $ST='<strong>'.class_basename($LA->subject_type).'</strong>';
            $description=$LA->description;
            $auth=Admin::find($LA->causer_id);
            $causer= $auth->name;
            $subject_id='<strong>'.$LA->subject_id.'</strong>';
            return $causer.' '.$description.' '.$ST.' having id '.$subject_id ;
         }) 
        ->addColumn('actions', function($LA){
            $view_url = '';
            $subject = $LA->subject_type;
            $subject_id = $LA->subject_id;
            $subjectClassName = class_basename($subject);
            switch ($subjectClassName) {
                case 'Company_Expense':
                    $subObj = $subject::find($subject_id);
                    if(isset($subObj)) {
                        $view_url = '<a class="dropdown-item" href="'.route('admin.CE_edit_view', $subObj).'"><i class="fa fa-edit"></i> View</a>';
                    }
                    break;
                    case 'Rider':
                    $subObj = $subject::find($subject_id);
                    if (isset($subObj)) {
                        $view_url = '<a class="dropdown-item" href="'.route('admin.rider.profile', $subObj).'"><i class="fa fa-edit"></i> View</a>';
                    }
                    break;
                    case 'Sim':
                    $subObj = $subject::find($subject_id);
                    if (isset($subObj)) {
                        $view_url = '<a class="dropdown-item" href="'.route('Sim.edit_sim_view', $subObj).'"><i class="fa fa-edit"></i> View</a>';
                    }
                    break;
                
                default:
                   
                    break;
            }
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                '.$view_url.'
                     <button class="dropdown-item" onclick="deleteActivity('.$LA->id.');"><i class="fa fa-trash"></i> Delete</button>
                </div>
            </span>
        </span>';
        })
    
        ->rawColumns(['id','actions','description','month'])
        ->make(true);
    }
    public function zomato_salary_export($month, $client_id) 
    {
        // return \App\Http\Controllers\Admin\AccountsController::get_salary_deduction(new Request(),$month,5);
        $total_gross=0;
        $totlpaid=0; 
        $client_histories = collect([]);
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
            $client_histories->push($mdl);
        }

        $rider_ids=[];
        foreach ($client_histories as $client_history) {
            array_push($rider_ids, $client_history->rider_id); 
        }
        $client_riders= Rider::whereIn('id', $rider_ids)->get();
        return DataTables::of($client_riders)
        ->addColumn('rider_name', function($rider) {
            $riderFound = Rider::find($rider->id);
            return 'KR'.$riderFound->id.' - '.$riderFound->name;
        }) 
        ->addColumn('feid', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $riderFound = Rider::find($rider->id);
            $client_history = Client_History::all();
            $rider_id=$rider->id;
            $history_found = Arr::first($client_history, function ($item, $key) use ($rider_id, $month) {
                $start_created_at =Carbon::parse($item->assign_date)->startOfMonth()->format('Y-m-d');
                $created_at =Carbon::parse($start_created_at);
        
                $start_updated_at =Carbon::parse($item->deassign_date)->endOfMonth()->format('Y-m-d');
                $updated_at =Carbon::parse($start_updated_at);
                $req_date =Carbon::parse($month);
        
                return $item->rider_id==$rider_id &&
                    ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
            });
            $feid=null;
            if (isset($history_found)) {
                $feid=$history_found->client_rider_id;
            }
            return $feid;
        }) 
        ->addColumn('bike_number', function($rider) {
            $assign_bike=Assign_bike::where("rider_id",$rider->id)->where("status","active")->get()->first();             
            if (isset($assign_bike)) {
                $bike=bike::find($assign_bike->bike_id);
                return $bike->bike_number;
            }
            return 'No Bike is assigned';
        }) 
        ->addColumn('advance', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $advance_sum=Rider_Account::where('rider_id',$rider->id)
            ->whereNotNull('advance_return_id')
                        ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->get()
            ->sum('amount');
                return $advance_sum;
        }) 
        ->addColumn('poor_performance', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $poor_performance_sum=Rider_Account::where('source',"Denials Penalty")
            ->where('rider_id',$rider->id)
            ->whereNotNull('income_zomato_id')
                ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->get()
            ->sum('amount');
                return $poor_performance_sum;
        }) 
        ->addColumn('visa', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $visa_sum=Rider_Account::where('source',"Visa Charges")
            ->where('rider_id',$rider->id)
            ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->get()
            ->sum('amount');
                return $visa_sum;
        }) 
        ->addColumn('mobile_charges', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $mobile_charges=Rider_Account::where('source',"Mobile Charges")
            ->where('rider_id',$rider->id)
                ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->sum('amount');
                return $mobile_charges;
        }) 
        ->addColumn('mobile', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $mobile_sum=Rider_Account::where('rider_id',$rider->id)
            ->whereNotNull('mobile_installment_id')
                ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->get()
            ->sum('amount');
            return $mobile_sum;
        }) 
        ->addColumn('bike_allowns', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $bike_allowns=Rider_Account::where(function($q) {
                $q->where('source', "Bike Allowns")
                ->orWhere('source', 'Bike Rent');
            })
            ->where('rider_id',$rider->id)
            ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->get()
            ->sum('amount');
                return $bike_allowns;
        }) 
        ->addColumn('bonus', function($rider) use ($month){
                        $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $bonus=Rider_Account::where('source',"400 Trips Acheivement Bonus")
            ->where('rider_id',$rider->id)
                ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->get()
            ->sum('amount');
            return $bonus;
        }) 
        ->addColumn('number_of_hours', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $income_zomato=Income_zomato::where('rider_id',$rider->id)
                ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->first();
            if(isset($income_zomato)){
                $absent_count = $income_zomato->absents_count;
                $working_days = $income_zomato->working_days;
                $calculated_hours = $income_zomato->calculated_hours;
        
                $working_hours = $working_days*11;
                $absent_hours = $absent_count*11;
        
                $less_time = $working_hours - $calculated_hours;
                $payable_hours = round(286 - $absent_hours - $less_time,2);
                return round($payable_hours,2); 
            }
            return 0;
        })
        ->addColumn('number_of_trips', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $income_zomato=Income_zomato::where('rider_id',$rider->id)
                ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->first();
            if(isset($income_zomato)){
                $trips =$income_zomato->calculated_trips; 
                if ( $trips > 400) $trips=400;
                return round($trips,2);
            }
            return 0;
        }) 
        ->addColumn('aed_hours', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $income_zomato=Income_zomato::where('rider_id',$rider->id)
            ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->first();
            if(isset($income_zomato)){
                $absent_count = $income_zomato->absents_count;
                $working_days = $income_zomato->working_days;
                $calculated_hours = $income_zomato->calculated_hours;
        
                $working_hours = $working_days*11;
                $absent_hours = $absent_count*11;
        
                $less_time = $working_hours - $calculated_hours;
                $payable_hours = round(286 - $absent_hours - $less_time,2);
                return round($payable_hours*7.87,2); 
            }
            return 0;
        })
        ->addColumn('aed_trips', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $income_zomato=Income_zomato::where('rider_id',$rider->id)
                ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->first();
            if(isset($income_zomato)){
                $trips =$income_zomato->calculated_trips;
                if ( $trips > 400) $trips=400;
                return round($trips*2,2);
            }
            return 0; 
        }) 
        ->addColumn('extra_trips', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $income_zomato=Income_zomato::where('rider_id',$rider->id)
                ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->first();
            if(isset($income_zomato)){
                $trips =$income_zomato->calculated_trips; 
                if ( $trips > 400) $trips=$trips-400;
                else $trips=0;
                return round($trips,2);
            }
            return 0;
        }) 
        ->addColumn('aed_extra_trips', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $income_zomato=Income_zomato::where('rider_id',$rider->id)
                ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->first();
            if(isset($income_zomato)){
                $trips =$income_zomato->calculated_trips; 
                if ( $trips > 400) $trips=$trips-400;
                else $trips=0;
                return round($trips*4,2);
            }
            return 0;
        }) 
        ->addColumn('ncw', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $income_zomato=Income_zomato::where('rider_id',$rider->id)
                ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->first();
            if(isset($income_zomato)){
                $ncw = $income_zomato->ncw_incentives;
                return round($ncw,2);
            }
            return 0;
        }) 
        ->addColumn('tips', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $income_zomato=Income_zomato::where('rider_id',$rider->id)
                ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->first();
            if(isset($income_zomato)){
                $tips_payouts = $income_zomato->tips_payouts;
                return round($tips_payouts,2);
            }
            return 0;
        }) 
        ->addColumn('salik', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $salik_amount=Rider_Account::where('rider_id',$rider->id)
            ->whereNotNull('salik_id')
            ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->get()
            ->sum('amount');
            return $salik_amount;
        }) 
        ->addColumn('fuel', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $fuel_amount=Company_Account::where('rider_id',$rider->id)
            ->whereNotNull('fuel_expense_id')
            ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->get()
            ->sum('amount');
            return $fuel_amount;
        }) 
        ->addColumn('sim_charges', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $sim_charges=Company_Account::where('rider_id',$rider->id)
            ->whereNotNull('sim_transaction_id')
            ->where("source","Sim Transaction")
            ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->get()
            ->sum('amount');
            return $sim_charges;
        }) 
        ->addColumn('sim_extra_charges', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $sim_extra_charges=Rider_Account::where('rider_id',$rider->id)
            ->whereNotNull('sim_transaction_id')
            ->where("source","Sim extra usage")
            ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->get()
            ->sum('amount');
            return $sim_extra_charges;
        }) 
        ->addColumn('cod', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $cod=Rider_Account::where('rider_id',$rider->id)
            ->whereNotNull('income_zomato_id')
            ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->where('source',"Mcdonalds Deductions")
            ->get()
            ->sum('amount');
            return $cod;
        }) 
        ->addColumn('dc', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $dc=Rider_Account::where('rider_id',$rider->id)
            ->whereNotNull('income_zomato_id')
            ->where('source',"DC Deductions")
            ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->get()
            ->sum('amount');
            return $dc;
        }) 
        ->addColumn('rta_fine', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $rta_fine=Rider_Account::where('rider_id',$rider->id)
            ->whereNotNull('id_charge_id')
            ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->get()
            ->sum('amount');
            return $rta_fine;
        }) 
        ->addColumn('dicipline_fine', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $startMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
            $endMonth = Carbon::parse($month)->endOfMonth()->format('Y-m-d');
            $dicipline=\App\Model\Accounts\Rider_Account::where("rider_id",$rider->id)
            ->whereDate('month', '>=',$startMonth)
            ->whereDate('month', '<=',$endMonth)
            ->where(function($q) {
                $q->where('source','Discipline Fine')
                ->orWhereNotNull('kingrider_fine_id');
            })
            ->sum('amount');
            return $dicipline;
        }) 
        ->addColumn('total_deduction', function($rider) use ($month){
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $rider_id = $rider->id;
        
            $startMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
            $month = Carbon::parse($month)->format('Y-m-d');
            $onlyMonth = Carbon::parse($month)->format('m');
            //prev payables
            $rider_debits_cr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
            ->where(function($q) {
                $q->where('type', "cr");
            })
            ->whereDate('month', '<',$startMonth)
            ->sum('amount');
            
            $rider_debits_dr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
            ->where(function($q) {
                $q->where('type', "cr_payable")
                ->orWhere('type', 'dr');
            })
            ->whereDate('month', '<',$startMonth)
            ->sum('amount');
            $closing_balance_prev = round($rider_debits_cr_prev_payable - $rider_debits_dr_prev_payable,2);
            //ends prev payables
            $total_deduction=Rider_Account::where('rider_id',$rider_id)
            ->where(function($q) {
                $q->where('type', "cr_payable")
                ->orWhere('type', 'dr');
            })
            ->whereMonth('month', $onlyMonth)
            ->get()
            ->sum('amount');
            if($closing_balance_prev < 0){ //deduct
                $total_deduction += abs($closing_balance_prev);
            }
            return $total_deduction;
        })
        ->addColumn('total_salary', function($rider) use ($month){
            $salary_deductions = \App\Http\Controllers\Admin\AccountsController::get_salary_deduction(new Request(),$month,$rider->id);
            $salary_deductions=json_decode($salary_deductions->content(), true);
            if($salary_deductions['status']==1){
                return $salary_deductions['total_salary'];
            }
            return 0;
        })
        ->addColumn('net_salary', function($rider) use ($month){
            $salary_deductions = \App\Http\Controllers\Admin\AccountsController::get_salary_deduction(new Request(),$month,$rider->id);
            $salary_deductions=json_decode($salary_deductions->content(), true);
            if($salary_deductions['status']==1){
                return $salary_deductions['net_salary'];
            }
            return 0;
        })
        ->addColumn('gross_salary', function($rider) use ($month, &$total_gross, &$totlpaid){
            $salary_deductions = \App\Http\Controllers\Admin\AccountsController::get_salary_deduction(new Request(),$month,$rider->id);
            $salary_deductions=json_decode($salary_deductions->content(), true);
            $ra_recieved=0;
            if($salary_deductions['status']==1){
                $totlpaid++;
                $ra_recieved=$salary_deductions['net_salary'];
                $total_gross += $ra_recieved;
                return '<div totlgros="'.$total_gross.'" totl_paid="'.$totlpaid.'">'.round($ra_recieved,2).' <i class="flaticon2-correct" style="color: green;"></i></div>';
            }
            return '<div totlgros="'.$total_gross.'">'.round($ra_recieved,2).'</div>';
        })
        ->addColumn('get_paid_salaries', function($rider) use ($month) {
                $onlyMonth=Carbon::parse($month)->format('m');
                $onlyYear=Carbon::parse($month)->format('Y');
                $paid_salaries=Rider_Account::where('rider_id',$rider->id)
                ->whereMonth("month",$onlyMonth)
                ->where("source","salary_paid")
                ->where("payment_status","paid")
                ->get()
                ->sum('amount');
                return $paid_salaries;
        })
        ->rawColumns(['get_paid_salaries','sim_extra_charges','fuel','mobile_charges','bonus','bike_allowns','aed_extra_trips','extra_trips','net_salary','gross_salary','rider_name','bike_number','advance','poor_performance', 'salik', 'sim_charges', 'dc', 'cod', 'rta_fine', 'total_deduction', 'aed_hours', 'total_salary','visa','mobile','tips','aed_trips','ncw','number_of_trips','number_of_hours'])
        ->make(true);
    }
    public function client_salary_export($month)
    {
    $total_gross=0;
    $totlpaid=0;

    $riders=Rider::all();
    return DataTables::of($riders)
    ->addColumn('client_name', function($rider) use($month) {
        $rider_id=$rider->id;
        $client_history = Client_History::all();
        $startMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
        $history_found = Arr::first($client_history, function ($item, $key) use ($rider_id, $startMonth) {
            $start_created_at =Carbon::parse($item->assign_date)->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);
    
            $start_updated_at =Carbon::parse($item->deassign_date)->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($startMonth);
    
            return $item->rider_id==$rider_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });
        if(isset($history_found)){
            $client = Client::find($history_found->client_id);
            return $client->name;
        }
        return 'No client assigned';
    }) 
    ->addColumn('rider_name', function($rider) {
        $riderFound = Rider::find($rider->id);
        return $riderFound->name;
    }) 
    ->addColumn('feid', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $riderFound = Rider::find($rider->id);
        $client_history = Client_History::all();
        $rider_id=$rider->id;
        $history_found = Arr::first($client_history, function ($item, $key) use ($rider_id, $month) {
            $start_created_at =Carbon::parse($item->assign_date)->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);
    
            $start_updated_at =Carbon::parse($item->deassign_date)->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($month); 
    
            return $item->rider_id==$rider_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });
        $feid=null;
        if (isset($history_found)) {
            $feid=$history_found->client_rider_id;
        }
        return $feid;
    }) 
    ->addColumn('bike_number', function($rider) {
          $assign_bike=Assign_bike::where("rider_id",$rider->id)->where("status","active")->get()->first();             
         if (isset($assign_bike)) {
            $bike=bike::find($assign_bike->bike_id);
            return $bike->bike_number;
        }
          return 'No Bike is assigned';
    }) 
    ->addColumn('advance', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $advance_sum=Rider_Account::where('rider_id',$rider->id)
        ->whereNotNull('advance_return_id')
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->get()
        ->sum('amount');
        return $advance_sum;
    }) 
    ->addColumn('poor_performance', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $poor_performance_sum=Rider_Account::where('source',"Denials Penalty")
        ->where('rider_id',$rider->id)
        ->whereNotNull('income_zomato_id')
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->get()
        ->sum('amount');
        return $poor_performance_sum;
    }) 
    ->addColumn('visa', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $visa_sum=Rider_Account::where('source',"Visa Charges")
        ->where('rider_id',$rider->id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->get()
        ->sum('amount');
        return $visa_sum;
    }) 
    ->addColumn('mobile_charges', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $mobile_charges=Rider_Account::where('source',"Mobile Charges")
        ->where('rider_id',$rider->id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->sum('amount');
        return $mobile_charges;
    }) 
    ->addColumn('mobile', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $mobile_sum=Rider_Account::where('rider_id',$rider->id)
        ->whereNotNull('mobile_installment_id')
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->get()
        ->sum('amount');
        return $mobile_sum;
    }) 
    ->addColumn('bike_allowns', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $bike_allowns=Rider_Account::where(function($q) {
            $q->where('source', "Bike Allowns")
            ->orWhere('source', 'Bike Rent');
        })
        ->where('rider_id',$rider->id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->get()
        ->sum('amount');
        return $bike_allowns;
    }) 
    ->addColumn('bonus', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $bonus=Rider_Account::where('source',"400 Trips Acheivement Bonus")
        ->where('rider_id',$rider->id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->get()
        ->sum('amount');
        return $bonus;
    }) 
    ->addColumn('number_of_hours', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $income_zomato=Income_zomato::where('rider_id',$rider->id)
              ->whereMonth('date',$onlyMonth)
        ->whereYear('date',$onlyYear)
        ->get()
        ->first();
        if(isset($income_zomato)){
            $absent_count = $income_zomato->absents_count;
            $working_days = $income_zomato->working_days;
            $calculated_hours = $income_zomato->calculated_hours;
    
            $working_hours = $working_days*11;
            $absent_hours = $absent_count*11;
    
            $less_time = $working_hours - $calculated_hours;
            $payable_hours = round(286 - $absent_hours - $less_time,2);
            return round($payable_hours,2); 
        }
    }) 
    ->addColumn('number_of_trips', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $income_zomato=Income_zomato::where('rider_id',$rider->id)
              ->whereMonth('date',$onlyMonth)
        ->whereYear('date',$onlyYear)
        ->get()
        ->first();
        if(isset($income_zomato)){
            $trips =$income_zomato->calculated_trips; 
            if ( $trips > 400) $trips=400;
            return round($trips,2);
        }
    }) 
    ->addColumn('aed_hours', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $income_zomato=Income_zomato::where('rider_id',$rider->id)
        ->whereMonth('date',$onlyMonth)
        ->whereYear('date',$onlyYear)
        ->get()
        ->first();
        if(isset($income_zomato)){
            $absent_count = $income_zomato->absents_count;
            $working_days = $income_zomato->working_days;
            $calculated_hours = $income_zomato->calculated_hours;
    
            $working_hours = $working_days*11;
            $absent_hours = $absent_count*11;
    
            $less_time = $working_hours - $calculated_hours;
            $payable_hours = round(286 - $absent_hours - $less_time,2);
            return round($payable_hours*7.87,2); 
        }
        return 0;
    })
    ->addColumn('aed_trips', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $income_zomato=Income_zomato::where('rider_id',$rider->id)
              ->whereMonth('date',$onlyMonth)
        ->whereYear('date',$onlyYear)
        ->get()
        ->first();
        if(isset($income_zomato)){
            $trips =$income_zomato->calculated_trips;
            if ( $trips > 400) $trips=400;
            return round($trips*2,2);
        }
        return 0;
    
    }) 
    ->addColumn('extra_trips', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $income_zomato=Income_zomato::where('rider_id',$rider->id)
              ->whereMonth('date',$onlyMonth)
        ->whereYear('date',$onlyYear)
        ->get()
        ->first();
        if(isset($income_zomato)){
            $trips =$income_zomato->calculated_trips; 
            if ( $trips > 400) $trips=$trips-400;
            else $trips=0;
            return round($trips,2);
        }
    }) 
    ->addColumn('aed_extra_trips', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $income_zomato=Income_zomato::where('rider_id',$rider->id)
             ->whereMonth('date',$onlyMonth)
        ->whereYear('date',$onlyYear)
        ->get()
        ->first();
        if(isset($income_zomato)){
            $trips =$income_zomato->calculated_trips; 
            if ( $trips > 400) $trips=$trips-400;
            else $trips=0;
            return round($trips*4,2);
        }
    }) 
    ->addColumn('ncw', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $income_zomato=Income_zomato::where('rider_id',$rider->id)
              ->whereMonth('date',$onlyMonth)
        ->whereYear('date',$onlyYear)
        ->get()
        ->first();
        if(isset($income_zomato)){
            $ncw = $income_zomato->ncw_incentives;
            return round($ncw,2);
        }
    }) 
    ->addColumn('tips', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $income_zomato=Income_zomato::where('rider_id',$rider->id)
              ->whereMonth('date',$onlyMonth)
        ->whereYear('date',$onlyYear)
        ->get()
        ->first();
        if(isset($income_zomato)){
            $tips_payouts = $income_zomato->tips_payouts;
            return round($tips_payouts,2);
        }
    }) 
    ->addColumn('salik', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $salik_amount=Rider_Account::where('rider_id',$rider->id)
        ->whereNotNull('salik_id')
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->get()
        ->sum('amount');
        return $salik_amount;
    
    }) 
    ->addColumn('fuel', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $fuel_amount=Company_Account::where('rider_id',$rider->id)
        ->whereNotNull('fuel_expense_id')
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->get()
        ->sum('amount');
        return $fuel_amount;
    
    }) 
    ->addColumn('sim_charges', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $sim_charges=Company_Account::where('rider_id',$rider->id)
        ->whereNotNull('sim_transaction_id')
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->get()
        ->sum('amount');
        return $sim_charges;
    
    }) 
    ->addColumn('sim_extra_charges', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $sim_extra_charges=Rider_Account::where('rider_id',$rider->id)
        ->whereNotNull('sim_transaction_id')
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->get()
        ->sum('amount');
        return $sim_extra_charges;
    
    }) 
    ->addColumn('cod', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $cod=Rider_Account::where('rider_id',$rider->id)
        ->whereNotNull('income_zomato_id')
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->where('source',"Mcdonalds Deductions")
        ->get()
        ->sum('amount');
         return $cod;
    }) 
    ->addColumn('dc', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $dc=Rider_Account::where('rider_id',$rider->id)
        ->whereNotNull('income_zomato_id')
        ->where('source',"DC Deductions")
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->get()
        ->sum('amount');
         return $dc;
    }) 
    ->addColumn('rta_fine', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $rta_fine=Rider_Account::where('rider_id',$rider->id)
        ->whereNotNull('id_charge_id')
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->get()
        ->sum('amount');
        return $rta_fine;
    }) 
    ->addColumn('dicipline_fine', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        return '0';
    }) 
    ->addColumn('total_deduction', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $rider_id = $rider->id;
    
        $startMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
        $month = Carbon::parse($month)->format('Y-m-d');
        $onlyMonth = Carbon::parse($month)->format('m');
        //prev payables
        $rider_debits_cr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->where(function($q) {
            $q->where('type', "cr");
        })
        ->whereDate('month', '<',$startMonth)
        ->sum('amount');
        
        $rider_debits_dr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->where(function($q) {
            $q->where('type', "cr_payable")
            ->orWhere('type', 'dr');
        })
        ->whereDate('month', '<',$startMonth)
        ->sum('amount');
        $closing_balance_prev = round($rider_debits_cr_prev_payable - $rider_debits_dr_prev_payable,2);
        //ends prev payables
        $total_deduction=Rider_Account::where('rider_id',$rider_id)
        ->where(function($q) {
            $q->where('type', "cr_payable")
            ->orWhere('type', 'dr');
        })
        ->whereMonth('month', $onlyMonth)
        ->get()
        ->sum('amount');
        if($closing_balance_prev < 0){ //deduct
            $total_deduction += abs($closing_balance_prev);
        }
        return $total_deduction;
    }) 
    ->addColumn('total_salary', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $income_zomato=Income_zomato::where('rider_id',$rider->id)
              ->whereMonth('date',$onlyMonth)
        ->whereYear('date',$onlyYear)
        ->get()
        ->first();
        if(isset($income_zomato)){
            $absent_count = $income_zomato->absents_count;
            $working_days = $income_zomato->working_days;
            $calculated_hours = $income_zomato->calculated_hours;
            $calculated_trips = $income_zomato->calculated_trips;
    
            $working_hours = $working_days*11;
            $absent_hours = $absent_count*11;
    
            $less_time = $working_hours - $calculated_hours;
            $payable_hours = round(286 - $absent_hours - $less_time,2);
    
            $hours_payable=$payable_hours*7.87;
    
            $trips = $calculated_trips > 400?400:$calculated_trips;
            $trips_payable = $trips * 2;
    
            $trips_EXTRA = $calculated_trips > 400?$calculated_trips-400:0;
            $trips_EXTRA_payable = $trips_EXTRA * 4;
    
            $salary_hours=round($hours_payable,2);
            $salary_trips=$trips_payable+$trips_EXTRA_payable;
    
            $total_salary_amt = round($salary_hours+$salary_trips,2);
            // return 'salary_hours: '.$salary_hours.' salary_trips:'.$salary_trips.' payable_hours:'.$payable_hours.' absent_hours:'.$absent_count;
            return $total_salary_amt; 
        }
        return 0;
    })
    ->addColumn('net_salary', function($rider) use ($month){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $rider_id = $rider->id;
    
        $startMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
        $month = Carbon::parse($month)->format('Y-m-d');
        $onlyMonth = Carbon::parse($month)->format('m');
    
        //prev payables
        $rider_debits_cr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->where(function($q) {
            $q->where('type', "cr");
        })
        ->whereDate('month', '<',$startMonth)
        ->sum('amount');
        
        $rider_debits_dr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->where(function($q) {
            $q->where('type', "cr_payable")
            ->orWhere('type', 'dr');
        })
        ->whereDate('month', '<',$startMonth)
        ->sum('amount');
        $closing_balance_prev = $rider_debits_cr_prev_payable - $rider_debits_dr_prev_payable;
        //ends prev payables
    
        $ra_cr=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->where("payment_status","pending")
        ->where("type","cr")
        ->where("source",'!=',"salary")
        ->sum('amount');   
        if($closing_balance_prev > 0){
            // add
            $ra_cr += abs($closing_balance_prev);
        }
    
        //total salary
        $total_salary_amt = 0;
        $ra_salary=0;
        $income_zomato=Income_zomato::where('rider_id',$rider_id)
        ->whereMonth('date',$onlyMonth)
        ->get()
        ->first();
        if(isset($income_zomato)){
            $absent_count = $income_zomato->absents_count;
            $working_days = $income_zomato->working_days;
            $calculated_hours = $income_zomato->calculated_hours;
            $calculated_trips = $income_zomato->calculated_trips;
    
            $working_hours = $working_days*11;
            $absent_hours = $absent_count*11;
    
            $less_time = $working_hours - $calculated_hours;
            $payable_hours = round(286 - $absent_hours - $less_time,2);
    
            $hours_payable=$payable_hours*7.87;
    
            $trips = $calculated_trips > 400?400:$calculated_trips;
            $trips_payable = $trips * 2;
    
            $trips_EXTRA = $calculated_trips > 400?$calculated_trips-400:0;
            $trips_EXTRA_payable = $trips_EXTRA * 4;
    
            $salary_hours=round($hours_payable,2);
            $salary_trips=$trips_payable+$trips_EXTRA_payable;
    
            $total_salary_amt = round($salary_hours+$salary_trips,2);
            
            $salary_credits=round($ra_cr,2);
            $ra_salary=$salary_hours +$salary_trips  +$salary_credits ;
        }
        else {
            $fixed_salary = $rider->Rider_Detail->salary;
            $fixed_salary = isset($fixed_salary)?$fixed_salary:0;
            $ra_salary= $fixed_salary + $ra_cr;
        }
        return round($ra_salary,2);
    })
    ->addColumn('gross_salary', function($rider) use ($month, &$total_gross, &$totlpaid){
       $onlyMonth=Carbon::parse($month)->format('m');
       $onlyYear=Carbon::parse($month)->format('Y');
        $rider_id = $rider->id;
    
        $startMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
        $month = Carbon::parse($month)->format('Y-m-d');
        $onlyMonth = Carbon::parse($month)->format('m');
    
        //prev payables
        $rider_debits_cr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->where(function($q) {
            $q->where('type', "cr");
        })
        ->whereDate('month', '<',$startMonth)
        ->sum('amount');
        
        $rider_debits_dr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->where(function($q) {
            $q->where('type', "cr_payable")
            ->orWhere('type', 'dr');
        })
        ->whereDate('month', '<',$startMonth)
        ->sum('amount');
        $closing_balance_prev = $rider_debits_cr_prev_payable - $rider_debits_dr_prev_payable;
        //ends prev payables
    
        $ra_payable=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->where(function($q) {
            $q->where('type', "cr_payable")
            ->orWhere('type', 'dr');
        })
        ->sum('amount');
    
        $ra_cr=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->where("payment_status","pending")
        ->where("type","cr")
        ->where("source",'!=',"salary")
        ->sum('amount');   
        if($closing_balance_prev < 0){ //deduct
            $ra_payable += abs($closing_balance_prev);
        }
        else {
            // add
            $ra_cr += abs($closing_balance_prev);
        }
    
        //total salary
        $total_salary_amt = 0;
        $ra_recieved=0;
        $income_zomato=Income_zomato::where('rider_id',$rider_id)
        ->whereMonth('date',$onlyMonth)
        ->get()
        ->first();
        if(isset($income_zomato)){
            $absent_count = $income_zomato->absents_count;
            $working_days = $income_zomato->working_days;
            $calculated_hours = $income_zomato->calculated_hours;
            $calculated_trips = $income_zomato->calculated_trips;
    
            $working_hours = $working_days*11;
            $absent_hours = $absent_count*11;
    
            $less_time = $working_hours - $calculated_hours;
            $payable_hours = round(286 - $absent_hours - $less_time,2);
    
            $hours_payable=$payable_hours*7.87;
    
            $trips = $calculated_trips > 400?400:$calculated_trips;
            $trips_payable = $trips * 2;
    
            $trips_EXTRA = $calculated_trips > 400?$calculated_trips-400:0;
            $trips_EXTRA_payable = $trips_EXTRA * 4;
    
            $salary_hours=round($hours_payable,2);
            $salary_trips=$trips_payable+$trips_EXTRA_payable;
    
            $total_salary_amt = round($salary_hours+$salary_trips,2);
            
            $salary_credits=round($ra_cr,2);
            $ra_salary=$salary_hours +$salary_trips  +$salary_credits ;
            $ra_recieved=$ra_salary - $ra_payable;
        }
        else {
            $fixed_salary = $rider->Rider_Detail->salary;
            $fixed_salary = isset($fixed_salary)?$fixed_salary:0;
            $ra_salary= $fixed_salary + $ra_cr;
            $ra_recieved=$ra_salary - $ra_payable;
    
            $total_salary_amt = $fixed_salary;
        }
        $salary_paid=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->where("source","salary_paid")
        ->where("payment_status","paid")
        ->get()
        ->first();
        $total_gross +=$ra_recieved;
        if(isset($salary_paid)){
            $totlpaid++;
            return '<div totlgros="'.$total_gross.'" totl_paid="'.$totlpaid.'">'.round($ra_recieved,2).' <i class="flaticon2-correct" style="color: green;"></i></div>';
        }
        return '<div totlgros="'.$total_gross.'">'.round($ra_recieved,2).'</div>';
    })
    ->rawColumns(['client_name','sim_extra_charges','fuel','mobile_charges','bonus','bike_allowns','aed_extra_trips','extra_trips','net_salary','gross_salary','rider_name','bike_number','advance','poor_performance', 'salik', 'sim_charges', 'dc', 'cod', 'rta_fine', 'total_deduction', 'aed_hours', 'total_salary','visa','mobile','tips','aed_trips','ncw','number_of_trips','number_of_hours'])
    ->make(true);
    }

    public function zomato_profit_export($month,$client_id)
    {
        $client=Client::where("id",$client_id)->get()->first();
        // $client_riders=$zomato->riders();
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
        
        return DataTables::of($client_riders)
        ->addColumn('rider_name', function($rider) {
            $riderFound = Rider::find($rider->rider_id);
            if (isset($riderFound)) {
                return $riderFound->name;
            }
            return "No Rider is Assigned";
        }) 
        ->addColumn('bike_number', function($rider) {
              $assign_bike=Assign_bike::where("rider_id",$rider->rider_id)->where("status","active")->get()->first();             
            if (isset($assign_bike)) {
                $bike=bike::find($assign_bike->bike_id);    
                return $bike->bike_number;
            }
              return 'No Bike is assigned';
        }) 
        ->addColumn('aed_trips', function($rider) use ($month) {
            $salary_deductions = \App\Http\Controllers\Admin\AccountsController::get_salary_deduction(new Request(),$month,$rider->rider_id);
            $salary_deductions=json_decode($salary_deductions->content(), true);
            if($salary_deductions['status']==1){
                $_trips=$salary_deductions['trips_payable'];
                return round($_trips,2); 
            }
            return 0; 
        }) 
        ->addColumn('aed_hours', function($rider) use ($month) {
            
            $salary_deductions = \App\Http\Controllers\Admin\AccountsController::get_salary_deduction(new Request(),$month,$rider->rider_id);
            $salary_deductions=json_decode($salary_deductions->content(), true);
            if($salary_deductions['status']==1){
                $hours_payable=$salary_deductions['hours_payable'];
                return round($hours_payable,2); 
            }
            return 0; 
        })
        ->addColumn('aed_trips_zomato', function($rider) use ($month) {
            $salary_deductions = \App\Http\Controllers\Admin\AccountsController::get_salary_deduction(new Request(),$month,$rider->rider_id);
            $salary_deductions=json_decode($salary_deductions->content(), true);
            if($salary_deductions['status']==1){
                $zomato_trips=$salary_deductions['zomato_trips'];
                return round($zomato_trips,2); 
            }
            return 0;
        }) 
        ->addColumn('aed_hours_zomato', function($rider) use ($month) {
            $salary_deductions = \App\Http\Controllers\Admin\AccountsController::get_salary_deduction(new Request(),$month,$rider->rider_id);
            $salary_deductions=json_decode($salary_deductions->content(), true);
            if($salary_deductions['status']==1){
                $zomato_hours=$salary_deductions['zomato_hours'];
                return round($zomato_hours,2); 
            }
            return 0;
        })
        ->addColumn('number_of_hours', function($rider) use ($month) {
            $salary_deductions = \App\Http\Controllers\Admin\AccountsController::get_salary_deduction(new Request(),$month,$rider->rider_id);
            $salary_deductions=json_decode($salary_deductions->content(), true);
            if($salary_deductions['status']==1){
                $payable_hours=$salary_deductions['payable_hours'];
                return round($payable_hours,2); 
            }
            return 0; 
        }) 
        ->addColumn('number_of_trips', function($rider) use ($month) {
            $salary_deductions = \App\Http\Controllers\Admin\AccountsController::get_salary_deduction(new Request(),$month,$rider->rider_id);
            $salary_deductions=json_decode($salary_deductions->content(), true);
            if($salary_deductions['status']==1){
                $trips=$salary_deductions['trips'];
                return round($trips,2); 
            }
            return 0;  
        }) 
        ->addColumn('ncw', function($rider) use ($month) {
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $ncw_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->sum('ncw_incentives');
                return round($ncw_sum,2); 
        }) 
        ->addColumn('tips', function($rider) use ($month) {
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $tips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->sum('tips_payouts');
                return round($tips_sum,2); 
        }) 
        ->addColumn('penalty', function($rider) use ($month) {
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $penalty_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->sum('denials_penalty');
            return round($penalty_sum,2); 
        }) 
        ->addColumn('payout', function($rider) use ($month) {
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $hours=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->sum('log_in_hours_payable'); 
            $trips=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->sum('trips_payable');
            $AED_hours=$hours*6;
            $AED_trips=$trips*6.75;
            return round($AED_hours+$AED_trips,2); 
        }) 
        ->addColumn('payout_less', function($rider) use ($month) {
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $payout_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->sum('total_to_be_paid_out');
          
            return round($payout_sum,2); 
        }) 
        ->addColumn('cod', function($rider) use ($month) {
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $cod_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->sum('mcdonalds_deductions');
            return round($cod_sum,2); 
        }) 
        ->addColumn('dc', function($rider) use ($month) {
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $dc_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$onlyMonth)
            ->whereYear('date',$onlyYear)
            ->get()
            ->sum('dc_deductions');
            return round($dc_sum,2); 
        }) 
        ->addColumn('salik', function($rider) use ($month) {
            $bills = AccountsController::calculate_bills($month,$rider->rider_id);
            return round($bills['salik']+$bills['salik_extra'],2);
            
        }) 
        ->addColumn('sim_charges', function($rider) use ($month) {
            $bills = AccountsController::calculate_bills($month,$rider->rider_id);
            return round($bills['sim'],2).'('.round($bills['sim_extra'],2).')';
        }) 
        ->addColumn('fuel', function($rider) use ($month) {
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $fuel_amount=Company_Account::whereNotNull('fuel_expense_id')
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->sum('amount');
            return round($fuel_amount,2);
        }) 
        ->addColumn('bonus', function($rider) use ($month) {
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $bonus_amount=Rider_Account::where('source','400 Trips Acheivement Bonus')
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->sum('amount');
            return round($bonus_amount,2);
        }) 
        ->addColumn('bike_rent', function($rider) use ($month) {
            $onlyMonth=Carbon::parse($month)->format('m');
            $onlyYear=Carbon::parse($month)->format('Y');
            $bike_rent_amount=Company_Account::where('source','Bike Rent')
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$onlyMonth)
            ->whereYear('month',$onlyYear)
            ->sum('amount');
            return round($bike_rent_amount,2);
        }) 
        ->addColumn('net_salary', function($rider) use ($month) {
            $salary_deductions = \App\Http\Controllers\Admin\AccountsController::get_salary_deduction(new Request(),$month,$rider->rider_id);
            $salary_deductions=json_decode($salary_deductions->content(), true);
            if($salary_deductions['status']==1){
                return $salary_deductions['total_salary'];

            }
            return $salary_deductions['msg'];
        })
        ->addColumn('profit', function($rider) use ($month) {
            $profit = AccountsController::calculate_profit($month,$rider->rider_id);
            return $profit;
        })
        ->addColumn('expenses_bills', function($rider) use ($month) {
            $bills = AccountsController::calculate_bills($month,$rider->rider_id);
            return $bills['total'];
        }) 
        ->addColumn('decipline_fine', function($rider) use ($month) {
            $bills = AccountsController::calculate_bills($month,$rider->rider_id);
            return $bills['dicipline_fine'];
        }) 
        ->rawColumns(['decipline_fine','aed_trips_zomato','aed_hours_zomato','payout_less','expenses_bills','bonus','profit','bike_rent','fuel','payout','penalty','aed_extra_trips','net_salary','rider_name','bike_number', 'salik', 'sim_charges', 'dc', 'cod', 'aed_hours','tips','aed_trips','ncw','number_of_trips','number_of_hours'])
        ->make(true);
    }
    public function getBikeAccounts($ranges)
    {    
        $ranges = json_decode($ranges, true);
        $from = date($ranges['range']['start_date']);
        $to = date($ranges['range']['end_date']);
        $bike_statements = collect([]);
        $bike_statements_RAW = \App\Model\Accounts\Bike_Accounts::where("bike_id",$ranges['bike_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->get();

        foreach ($bike_statements_RAW as $bike_statement) {
            $continue = false;
            if(
            $bike_statement->maintenance_id != null){
                if($bike_statement->payment_status=="pending" && $bike_statement->type!="cr"){
                    $continue = true;
                }
            }

            if(!$continue){
                $bike_statements->push($bike_statement);
            }
        }
        

        
        $c_debits_cr_payable = \App\Model\Accounts\Bike_Accounts::where("bike_id",$ranges['bike_id'])
        ->where(function($q) {
            $q->where('type', "dr_receivable")
              ->orWhere('type', 'cr');
        })
        ->sum('amount');
        
        $c_debits_dr_payable = \App\Model\Accounts\Bike_Accounts::where("bike_id",$ranges['bike_id'])
        ->where('type', 'dr')
        ->sum('amount');
        
        //
        $c_debits_rn_cr_payable = \App\Model\Accounts\Bike_Accounts::where("bike_id",$ranges['bike_id'])
        ->where(function($q) {
            $q->where('type', "dr_receivable")
              ->orWhere('type', 'cr');
        })
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->sum('amount');
        
        $c_debits_rn_dr_payable = \App\Model\Accounts\Bike_Accounts::where("bike_id",$ranges['bike_id'])
        ->where('type', 'dr')
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->sum('amount');

        $c_debits_rn_pl_payable = \App\Model\Accounts\Bike_Accounts::where("bike_id",$ranges['bike_id'])
        ->where('type', 'pl')
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->sum('amount');
        $running_static_balance = $c_debits_rn_cr_payable - $c_debits_rn_dr_payable - $c_debits_rn_pl_payable;
        //
        $c_debits_rn_pl_total = \App\Model\Accounts\Bike_Accounts::where("bike_id",$ranges['bike_id'])
        ->where('type', 'pl')
        ->whereDate('month', '<',$from)
        ->sum('amount');
        $profit = $c_debits_rn_pl_total;

        $c_debits_cr_prev_payable = \App\Model\Accounts\Bike_Accounts::where("bike_id",$ranges['bike_id'])
        ->where(function($q) {
            $q->where('type', "dr_receivable")
              ->orWhere('type', 'cr');
        })
        ->whereDate('month', '<',$from)
        ->sum('amount');
        
        $c_debits_dr_prev_payable = \App\Model\Accounts\Bike_Accounts::where("bike_id",$ranges['bike_id'])
        ->where(function($q) {
            $q->where('type', "dr")
            ->orWhere('type', 'pl');
        })
        ->whereDate('month', '<',$from)
        ->sum('amount');

        $closing_balance = $c_debits_cr_payable - $c_debits_dr_payable;
        $first_month = Carbon::now()->format('Y-m-d');
        if(count($bike_statements) > 0){
            $first_month = $bike_statements->last()->month;
        }
        

        $closing_balance_prev = $c_debits_cr_prev_payable - $c_debits_dr_prev_payable;
        // $running_balance =$closing_balance_prev;
        $running_balance =0;

        $flag = new \App\Model\Accounts\Bike_Accounts;
        $flag->month='';
        $flag->source='Opening Balance';
        $flag->type='skip';
        $flag->amount=0;
        $bike_statements->prepend($flag);

        $flag = new \App\Model\Accounts\Bike_Accounts;
        $flag->month='';
        $flag->source='Closing Balance';
        $flag->type='skip';
        $flag->amount=0;
        $bike_statements->push($flag);
        return DataTables::of($bike_statements)
        ->addColumn('date', function($bike_statements){
            if($bike_statements->type=='skip') return '';
            return Carbon::parse($bike_statements->created_at)->format('M d, Y');
        })
        ->addColumn('desc', function($bike_statements){
            if($bike_statements->type=='skip') return '<strong >'.$bike_statements->source.'</strong>';
            return $bike_statements->source;
        })
        ->addColumn('cr', function($bike_statements){
            if($bike_statements->type=='pl') return 0;
            if($bike_statements->type=='skip') return '';
            if ($bike_statements->type=='cr' || $bike_statements->type=='dr_receivable')
            {
                return '<span >'.$bike_statements->amount.'</span>';
            }
            return 0;
        })
        ->addColumn('dr', function($bike_statements){
            if($bike_statements->type=='pl') return 0;
            if($bike_statements->type=='skip') return '';
            if($bike_statements->type=='dr'){
                return '<span>('.$bike_statements->amount.')</span>';
            }
            return 0;
        })
        ->addColumn('company_profit', function($bike_statements) use (&$profit){
            
            if($bike_statements->type=='pl'){
                $profit +=$bike_statements->amount;
                return round($bike_statements->amount, 2);
            }
            if($bike_statements->type=='skip') return '<strong>'.round($profit, 2).'</strong>';
            return 0;
        })
        ->addColumn('balance', function($bike_statements) use (&$running_balance){
            
            if($bike_statements->type=='dr' || $bike_statements->type=='pl'){
                $running_balance -= $bike_statements->amount;
            }
            else{
                $running_balance += $bike_statements->amount;
            }
            $_id = $bike_statements->source=="Closing Balance"? 'running_closing_balance':'running_opening_balance';
            // if($company_statements->type=='pl') return 0;
            if($bike_statements->type=='skip') return '<strong id="'.$_id.'"> '.round($running_balance,2).'</strong>';
            return round($running_balance,2);
        })
        
        ->with([
            'closing_balance' => round($closing_balance,2),
            'last_month' => $first_month,
            'running_static_balance' => $running_static_balance
        ])
        ->rawColumns(['desc','date','cr','dr','balance', 'company_profit'])
        ->make(true);
    }
    public function getBikeAccountsBills($ranges) 
    {
        $ranges = json_decode($ranges, true);
        $from = date($ranges['range']['start_date']);
        $to = date($ranges['range']['end_date']);
        $bike_id = $ranges['bike_id'];

        $bills = collect([]);

        $month = Carbon::parse($to)->format('m');
      
        //maintenance
        $model = \App\Model\Accounts\Bike_Accounts::
        whereMonth('month', $month)
        ->where("bike_id",$bike_id)
        ->whereNotNull('maintenance_id')
        ->get()
        ->first();
        if(isset($model)){
            $model->source = "Bike Maintenance";
            $bills->push($model);
        }
        //bike_rent
        $model = \App\Model\Accounts\Bike_Accounts::
        whereMonth('month', $month)
        ->where("bike_id",$bike_id)
        ->whereNotNull('bike_rent_id')
        ->get()
        ->first();
        if(isset($model)){
            $model->source = "Bike Rent";
            $bills->push($model);
        }


        return DataTables::of($bills)
        ->addColumn('date', function($bill){
            if (isset($bill->created_at)) {
                return Carbon::parse($bill->created_at)->format('M d, Y');
            }
        })
        ->addColumn('bill', function($bill){
            if (isset($bill->source)) {
                return $bill->source;
            }
            
        })
        ->addColumn('amount', function($bill){
            if (isset($bill->amount)) {
                return $bill->amount;
            }
            
        })
        ->addColumn('payment_status', function($bill){
            if (isset($bill->payment_status)) {
                if($bill->payment_status == 'pending'){
                    //enable pay
                    $month=$bill->month;
                    $bike_id=$bill->bike_id;
                    $type=$bill->source;
                    return '<div>Pending <button type="button" onclick="updateStatus('.$bike_id.',\''.$month.'\',\''.$type.'\')" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button></div>';
                }
                
                return ucfirst($bill->payment_status).' <i class="flaticon2-correct text-success h5"></i>';
            
            }
          })
        ->addColumn('action', function($bill){
            return '';
        })
        ->rawColumns(['amount','bill','payment_status','date','action'])
        ->make(true);
    }
    public function getBikeFine()
    {
        $bike_f = Bike_Fine::orderByDesc('created_at')->get();
        return DataTables::of($bike_f)
        ->addColumn('id', function($bike_f){
            return $bike_f->id;
        })
        ->addColumn('desc', function($bike_f){
            return $bike_f->description;
        })
        ->addColumn('amount', function($bike_f){
            return $bike_f->amount;
        })
        ->addColumn('date', function($bike_f){
            return Carbon::parse($bike_f->month)->format("F");
        })
        ->addColumn('bike_id', function($bike_f){
            $bike = bike::find($bike_f->bike_id);
            if (isset($bike)) {
                return $bike->bike_number;
            }
            return 'No Bike Assigned';
        })
        ->addColumn('rider_id', function($bike_f){
            $rider = Rider::find($bike_f->rider_id);
            if (isset($rider)) {
                return $rider->name;
            }
            return 'No Rider Assigned';
        })
        ->addColumn('rider_id_id', function($bike_f){
            $rider = Rider::find($bike_f->rider_id);
            if (isset($rider)) {
                return "KR".$rider->id;
            }
            return 'No Rider ID Assigned';
        })
        ->addColumn('actions', function($bike_f){
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.BF_edit_view', $bike_f).'"><i class="fa fa-edit"></i> View</a>
                    <button class="dropdown-item" onclick="deleteBikeFine('.$bike_f->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['rider_id_id','date','status','bike_id','desc','amount','actions', 'rider_id'])
        ->make(true);
    }
    public function getGeneratedBillStatus($month,$client_id) 
    {

        $bills = collect([]);
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
            $bills->push($mdl);
        }
        $sim_f=0;
        $bike_rent_f=0;
        $bike_fine_f=0;
        $fuel_f=0;
        $salik_f=0;
        $salary_f=0;
        $flag=new Rider_Account;
        $flag->rider_id='temp';
        $bills->push($flag);  

        // $bills=Client_History::where("client_id",$client_id)->get();
        return DataTables::of($bills)
      
        ->addColumn('rider_id', function($bills) use ($month){
            if ($bills->rider_id=="temp") {
                return "Total";
            }
            if(!isset($bills->rider_id))return 'asdasd';
            $rider_id=$bills->rider_id;
            $rider=Rider::find($rider_id);
            if(!isset($rider)) return 'no';
            $reqq = new Request();
            $reqq->setMethod('POST'); 
            $reqq->request->add(['month'=>$month]);
            $reqq->request->add(['rider_id'=>$rider_id]);
            
            $popoverHtml='';

            $bill_changes_detected=AccountsController::detect_bill_changes($reqq);
            // return $bill_changes_detected;
            if(isset($bill_changes_detected->original)){
                $bill_changes=$bill_changes_detected->original;
                if(isset($bill_changes['changes']) && count($bill_changes['changes'])>0){
                    $html = '<ul class=\'list-group\'>';
                    foreach ($bill_changes['changes'] as $bill_change) {
                        $html.='<li class=\'list-group-item\'>'.$bill_change.'</li>';
                    }
                    $html .= '</ul>';
                    $popoverHtml='<button type="button" 
                                    class="btn btn-outline-warning btn-elevate btn-icon btn-sm btn-circle" 
                                    data-toggle="popover" 
                                    data-placement="top" 
                                    data-html="true" 
                                    data-content="'.$html.'">
                                    <i class="fa fa-exclamation"></i>
                                    </button>';
                }
            }

            //
            
            return "KR".$rider->id ." - ". $rider->name.' '.$popoverHtml;
            
        })
        ->addColumn('sim_bill', function($bills) use ($month,&$sim_f){
            $rider_id=$bills->rider_id;
            $only_month = Carbon::parse($month)->format('m');
            $only_year = Carbon::parse($month)->format('Y');
            $sim_balance_allowed=Company_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$only_month)
            ->whereYear("month",$only_year)
            ->where("type","dr")
            ->where("source","Sim Transaction")
            ->get();
            $sim=0;
            $sim_extra=0;
            $status="";
            $temp="";
            foreach ($sim_balance_allowed as $value) {
                $sim+=$value->amount;
                $status=$value->payment_status;
                $temp.=$value->sim_transaction_id.",";
            }
            
            $tempextra="";
            $sim_extra_usage=Company_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$only_month)
            ->whereYear("month",$only_year)
            ->where("source","Sim extra usage")
            ->get();
            foreach ($sim_extra_usage as $value) {
                $sim_extra+=$value->amount;
                $tempextra.=$value->sim_transaction_id.",";
            }
            

            if (isset($sim)) {
                if ($status=="pending") {
                    $sim=$sim-$sim_extra;
                    $sim_rider_paid=$sim_extra;
                    $sim_f+=$sim+$sim_rider_paid;
                    return "<div style='color:red;'>".$sim."(". $sim_rider_paid.") <span class='flaticon2-delete'></span></div>";
                }
                if ($status=="paid") {
                    $sim=$sim-$sim_extra;
                    $sim_rider_paid=$sim_extra;
                    $sim_f+=$sim+$sim_rider_paid;
                    return "<div  style='color:green;' data-simtrans='".$temp."' data-simextratrans='".$tempextra."'>".$sim."(". $sim_rider_paid.") <span class='flaticon2-correct'></span></div>";
                }
            }
            if ($bills->rider_id=="temp") {
                return '<strong>'.$sim_f.'</strong>';
            }
            return "0";
        })
        ->addColumn('bike_rent', function($bills) use ($month,&$bike_rent_f){
            $rider_id=$bills->rider_id;
            $rent=0;
            $only_month = Carbon::parse($month)->format('m');
            $only_year = Carbon::parse($month)->format('Y');
            $bike_rent=Company_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$only_month)
            ->whereYear("month",$only_year)
            ->where("source","Bike Rent")
            ->get();
           foreach ($bike_rent as $item) {
               $rent+=$item->amount;
           }
           $bike_rent=$bike_rent->first();
            if (isset($bike_rent)) {
                if ($bike_rent->payment_status=="pending") {
                    $bike_rent_f+=$rent;
                    return "<div style='color:red;'>".$rent." <span class='flaticon2-delete'></span></div>";
                }
                if ($bike_rent->payment_status=="paid") {
                    $bike_rent_f+=$rent;
                    return "<div  style='color:green;'>".$rent." <span class='flaticon2-correct'></span></div>";
                }
            }
            if ($bills->rider_id=="temp") {
                return '<strong>'.$bike_rent_f.'</strong>';
            }
            return "0";
        })
        // ->addColumn('bike_bill', function($bills) use ($month){
        //     $rider_id=$bills->id;
        //     return 123;
        // })
        ->addColumn('bike_fine', function($bills) use ($month,&$bike_fine_f){
            $rider_id=$bills->rider_id;
            $only_month = Carbon::parse($month)->format('m');
            $only_year = Carbon::parse($month)->format('Y');
            $_bike_fine=Company_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$only_month)
            ->whereYear("month",$only_year)
            ->where("source","Bike Fine")
            ->get();
            $_bike_fine_paid=Company_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$only_month)
            ->whereYear("month",$only_year)
            ->where("source","Bike Fine Paid")
            ->get();
            $bike_fine=0;
            $bike_fine_paid=0;
            $status="";
            foreach ($_bike_fine as $value) {
                $bike_fine+=$value->amount;
                $status=$value->payment_status;
            }
            foreach ($_bike_fine_paid as $value) {
                $bike_fine_paid+=$value->amount;
                $status=$value->payment_status;
            }
            if (isset($bike_fine)) {
                if (isset($bike_fine_paid)) {
                    if ($status=="paid") {
                        $fine=($bike_fine_paid);
                        $bike_fine_f+=$fine;
                        return "<div  style='color:green;'>".$fine." <span class='flaticon2-correct'></span></div>";
                    }
                }
                if ($status=="pending") {
                    $fine=($bike_fine);
                    $bike_fine_f+=$fine;
                    return "<div style='color:red;'>".$fine." <span class='flaticon2-delete'></span></div>";
                }
            }
            if ($bills->rider_id=="temp") {
                return '<strong>'.$bike_fine_f.'</strong>';
            }
            return "0";
        })
        ->addColumn('fuel', function($bills) use ($month,&$fuel_f){
            $rider_id=$bills->rider_id;
            $only_month = Carbon::parse($month)->format('m');
            $only_year = Carbon::parse($month)->format('Y');
            $fuel_expense_val=Company_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$only_month)
            ->whereYear("month",$only_year)
            ->whereNotNull("fuel_expense_id")
            ->get();
            $fuel=0;
            $status='';
            foreach ($fuel_expense_val as $fuel_expense) {
                if (isset($fuel_expense)) {
                    if ($fuel_expense->payment_status=="pending") {
                        $fuel+=$fuel_expense->amount;
                        $status="pending";
                        
                    }
                    if ($fuel_expense->payment_status=="paid") {
                        $fuel+=$fuel_expense->amount;
                        $status="paid";
                      }
                }
            }
            if ($status=="pending") {
                $fuel_f+=$fuel;
                return "<div style='color:red;'>".$fuel." <span class='flaticon2-delete'></span></div>";
            }
            if ( $status=="paid") {
                $fuel_f+=$fuel;
                return "<div  style='color:green;'>".$fuel." <span class='flaticon2-correct'></span></div>";
                    
            }
            if ($bills->rider_id=="temp") {
                return '<strong>'.$fuel_f.'</strong>';
            }
            return $fuel;
        })
        ->addColumn('salik', function($bills) use ($month,&$salik_f){
            $rider_id=$bills->rider_id;
            $only_month = Carbon::parse($month)->format('m');
            $only_year = Carbon::parse($month)->format('Y');
            $_salik=Company_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$only_month)
            ->whereYear("month",$only_year)
            ->where("type","dr")
            ->where("source","Salik")
            ->get();
            $salik=0;
            $salik_extra=0;
            $status="";
            foreach ($_salik as $value) {
                $salik+=$value->amount;
                $status=$value->payment_status;
            }
            $_salik_extra=Company_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$only_month)
            ->whereYear("month",$only_year)
            ->where("source","Salik Extra")
            ->get();
            foreach ($_salik_extra as $value) {
                $salik_extra+=$value->amount;
            }
            if (isset($salik)) {
                if ($status=="pending") {
                    $_salik=$salik-$salik_extra;
                    $salik_rider_paid=$salik_extra;
                    $salik_f+=$_salik+$salik_rider_paid;
                    return "<div style='color:red;'>".$_salik."(". $salik_rider_paid.") <span class='flaticon2-delete'></span></div>";
                }
                if ($status=="paid") {
                    $_salik=$salik-$salik_extra;
                    $salik_rider_paid=$salik_extra;
                    $salik_f+=$_salik+$salik_rider_paid;
                    return "<div  style='color:green;'>".$_salik."(". $salik_rider_paid.") <span class='flaticon2-correct'></span></div>";
                }
            }
            if ($bills->rider_id=="temp") {
                return '<strong>'.$salik_f.'</strong>';
            }
            return "0";
        })
        ->addColumn('salary', function($bills) use ($month,&$salary_f){
            $rider_id=$bills->rider_id;
            $only_month = Carbon::parse($month)->format('m');
            $only_year = Carbon::parse($month)->format('Y');
            $salary=Rider_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$only_month)
            ->whereYear("month",$only_year)
            ->where("source","salary")
            ->get()
            ->first();
          if (isset($salary)) {
                if ($salary->payment_status=="pending") { 
                    $salary=$salary->amount;
                    $salary_f+=$salary;
                    return "<div  style='color:green;'>".$salary." <span class='flaticon2-correct'></span></div>";
               
              }
              
          }
          if ($bills->rider_id=="temp") {
            return '<strong>'.$salary_f.'</strong>';
        }
            return "0";
        })
        ->rawColumns(['rider_id','sim_bill','bike_rent','bike_bill','bike_fine','fuel','salik','salary',])
        ->make(true);
    }
    public function getInvoices()
    {
        $invoices = Invoice::with('Invoice_item')->orderByDesc('created_at')->get();
        return DataTables::of($invoices)
        ->addColumn('invoice', function($invoice){
            if($invoice->invoice_id != null) return $invoice->invoice_id;
            return 'No Invoice id';
        })
        ->addColumn('id', function($invoice){
            return $invoice->id;
        })
        ->addColumn('client_name', function($invoice){
            $client=Client::find($invoice->client_id);
            return $client->name;
        })
        ->addColumn('month', function($invoice){
            return Carbon::parse($invoice->month)->format('M Y');
        })
        ->addColumn('date', function($invoice){
            return Carbon::parse($invoice->invoice_date)->format('d-M-Y');
        })
        ->addColumn('due_date', function($invoice){
            return Carbon::parse($invoice->invoice_due)->format('d-M-Y');
        })
        ->addColumn('balance', function($invoice){
            return "AED ".$invoice->due_balance;
        })
        ->addColumn('total', function($invoice){
            return "AED ".$invoice->invoice_total;
        })
        ->addColumn('status', function($invoice){
            $step1='';
            $step2='';
            $step3='';
            $step4='';

            $invoice_status='';

            switch ($invoice->invoice_status) {
                case 'drafted':
                    $step1='is-active';
                    $invoice_status='Drafted';
                    break;
                case 'generated':
                    $step1='is-complete';
                    $step2='is-active';
                    $invoice_status='Generated';
                    break;
                case 'partially_paid':
                    $step1='is-complete';
                    $step2='is-complete';
                    $step3='is-active';
                    $invoice_status='Partially paid';
                    break;
                case 'paid':
                    $step1='is-complete';
                    $step2='is-complete';
                    $step3='is-complete';
                    $step4='is-active';
                    $invoice_status='Paid';
                    break;
            }
            $payments = $invoice->Invoice_Payment;
            if(count($payments)>0 && ($invoice->invoice_status=='generated' || $invoice->invoice_status=='drafted')){
                if(count($payments)>1) $invoice_status='Partially paid';
                else $invoice_status='Paid';
            }


            $statusBar='<div class="step__wrapper" style="display:none"> 
                            <ol class="steps">
                                <li class="step '.$step1.'" data-step="1">
                                    Drafted
                                </li>
                                <li class="step '.$step2.'" data-step="2">
                                    Generated
                                </li>
                                <li class="step '.$step3.'" data-step="3">
                                    Partially Paid
                                </li>
                                <li class="step '.$step4.'" data-step="4">
                                    Paid
                                </li>
                            </ol> 
                        </div>';
            $tz="Asia/Dubai";
            $current_date = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'), $tz);

            $remaining_days = $current_date->diffInDays(Carbon::parse($invoice->invoice_due), false);
            $abs_remainingDays = abs($remaining_days);
            $statusText='';
            if ($remaining_days < 0) {
                //overdue
                
                $daysText = $abs_remainingDays.' '.Str::plural('day', $abs_remainingDays);
                
                $statusText='<span class="kt-font-danger">Overdue '.$daysText.' ('.$invoice_status.')</span>';
            }
            else {
                //not due yet
                $daysText = 'in '.$abs_remainingDays.' '.Str::plural('day', $abs_remainingDays);
                if($abs_remainingDays==0) $daysText='today';
                if($abs_remainingDays==1) $daysText='tomorrow';
                $statusText='Due '.$daysText.' ('.$invoice_status.')';
            }
            
            if($invoice->payment_status=="paid"){
                //paid
                $statusText='<span class="kt-font-success"><i class="fa fa-check-circle" style="font-size: 130%;"></i> Paid</span>';
            }

            $html = '<a href="" class="statusText__container" onclick="handle_status(this);return false;">
                    <div class="statusText__wrapper">'.$statusText.' <i class="la la-angle-down"></i></div>
                    '.$statusBar.'
                    </a>';
            return $html;
        })
        ->addColumn('actions', function($invoice){
            $text='<a href="" class="reveive_payment" onclick=\'receive_payment_popup(this);return false;\'>Receive Payment</a>';
            if ($invoice->payment_status=="paid") {
                $text='<a href="" class="receive_payment col_editable" onclick=\';return false;\'>Print</a>';
            }
            return $text.'
            <noscript>'.$invoice->toJson().'</noscript>';
        })
        ->addColumn('details', function($invoice){
            $classes='bg-success text-white';


            

            $tz="Asia/Dubai";
            $current_date = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'), $tz);

            $remaining_days = $current_date->diffInDays(Carbon::parse($invoice->invoice_due), false);
            $abs_remainingDays = abs($remaining_days);
            $statusText='';
            if ($remaining_days < 0) {
                //overdue
                $classes='bg-warning text-white text-center';
                
                $daysText = $abs_remainingDays.' '.Str::plural('day', $abs_remainingDays);
                
                $statusText='Overdue '.$daysText.'. Due: '.Carbon::parse($invoice->invoice_due)->format('d-M-Y').'
                            <a href="" class="invoice__details-print col_editable_child" onclick="return false"><span>Print Invoice</span> <i class="flaticon2-next text-white"></i></a>';
            }
            else {
                //not due yet
                $classes='bg-success text-white text-center';
                $daysText = 'in '.$abs_remainingDays.' '.Str::plural('day', $abs_remainingDays);
                if($abs_remainingDays==0) $daysText='today';
                if($abs_remainingDays==1) $daysText='tomorrow';
                $statusText='Due '.$daysText.'. Due: '.Carbon::parse($invoice->invoice_due)->format('d-M-Y').' <a href="" class="invoice__details-print col_editable_child" onclick="return false"><span>Print Invoice</span> <i class="flaticon2-next text-white"></i></a>';
            }
            $payments = $invoice->Invoice_Payment;
            if(count($payments)>0){
                $classes='bg-success text-white';
                $payments_lis='';
                foreach ($payments as $payment) {
                    $payments_lis.='<li>
                            Payment on '.Carbon::parse($payment->payment_date)->format('d/m/Y').'. Payment: <strong>AED '.round($payment->payment, 2).'</strong>
                    </li>';
                }
                $statusText='<ul class="invoice__details-payments">'.$payments_lis.'</ul>';
            }
            // switch ($invoice->invoice_status) {
            //     case 'partially_paid':
            //     case 'paid':
            //         $classes='bg-success text-white';
            //         $payments = $invoice->Invoice_Payment;
            //         $payments_lis='';
            //         foreach ($payments as $payment) {
            //             $payments_lis.='<li>
            //                     Payment on '.Carbon::parse($payment->payment_date)->format('d/m/Y').'. Payment: <strong>AED '.round($payment->payment, 2).'</strong>
            //             </li>';
            //         }
            //         $statusText='<ul class="invoice__details-payments">'.$payments_lis.'</ul>';
            //         break;
            // }
            
            $html='<div class="invoice__details-wrapper">
                        <div class="'.$classes.' invoice__details-inner">'.$statusText.'</div>
                    </div>';
            return $html;
        })
        ->rawColumns(['id','invoice','client_name','month','date','due_date','balance','total','status','actions', 'details'])
        ->make(true);
    }


    public function getInvoicePayments()
    {
        $invoice_payments =Invoice_Payment::orderByDesc('created_at')->get();
        return DataTables::of($invoice_payments)
      
        ->addColumn('id', function($invoice_payments){
            return $invoice_payments->id;
        })
        
        ->addColumn('payment_date', function($invoice_payments){
            return $invoice_payments->payment_date;
        })
        ->addColumn('original_amount', function($invoice_payments){
            return $invoice_payments->original_amount;
        })
        ->addColumn('payment', function($invoice_payments){
            return $invoice_payments->payment;
        })
        ->addColumn('due_balance', function($invoice_payments){
            return $invoice_payments->due_balance;
        })
        ->addColumn('payment_method', function($invoice_payments){
            return $invoice_payments->payment_method;
        })
        ->addColumn('payment_received_by', function($invoice_payments){
            return $invoice_payments->payment_received_by;
        })
       
       
        ->rawColumns([ 'id','payment_date', 'original_amount', 'payment', 'due_balance','payment_method','payment_received_by'])
        ->make(true);
    }
    public function getSellers()
    {
        $sellers =Seller::orderByDesc('created_at')->where('active_status', 'A')->get();
        return DataTables::of($sellers)
        ->addColumn('id', function($sellers){
            return $sellers->id;
        })
        ->addColumn('name', function($sellers){
            return $sellers->name;
        })
        ->addColumn('address', function($sellers){
            return $sellers->address;
        })
        ->addColumn('phone_number', function($sellers){
            return $sellers->phone_number;
        })
        ->addColumn('actions', function($sellers){
            return '<a class="dropdown-item" href="'.route('mobile.sellers_edit', $sellers->id).'"><i class="fa fa-eye"></i></a>';
        })
        ->rawColumns([ 'name','address', 'phone_number', 'actions', 'id'])
        ->make(true);
    }
    public function getAccessory()
    {
        $accessory = Accessory::all();
        return DataTables::of($accessory)
        ->addColumn('id', function($accessory){
            return $accessory->id;
        })
        ->addColumn('seller_id', function($accessory){
            $seller=Seller::find($accessory->seller_id);
            if (isset($seller)) {
                $seller_name=$seller->name;
            }
            return $seller_name;
        })
        ->addColumn('description', function($accessory){
            return $accessory->description;
        })
        ->addColumn('date', function($accessory){
            return $accessory->purchasing_date;
        })
        ->addColumn('amount', function($accessory){
            return $accessory->amount;
        })
        ->addColumn('actions', function($accessory){
            return '<a class="dropdown-item" href="'.route('mobile.accessory_edit', $accessory->id).'"><i class="fa fa-eye"></i></a>';
        })
        ->rawColumns(['id', 'amount','date', 'description', 'actions', 'seller_id'])
        ->make(true);
    }

    public function getMobileProfitLoss()
    {
        $mobile = Mobile::all();
        return DataTables::of($mobile)
        ->addColumn('id', function($mobile){
            return $mobile->id;
        })
        ->addColumn('model', function($mobile){
            return $mobile->model." ".$mobile->brand;
        })
        ->addColumn('date', function($mobile){
            return carbon::parse($mobile->purchasing_date)->format('F Y');
        })
        ->addColumn('purchase_price', function($mobile){
            return $mobile->purchase_price;
        })
        ->addColumn('received', function($mobile){
            return $mobile->amount_received;
        })
        ->addColumn('profit_loss', function($mobile){
            $_pp=$mobile->purchase_price;
            $_ar=$mobile->amount_received;
            $mobile_history=MobileHistory::where("mobile_id",$mobile->id)->get()->first();
            if (isset($mobile_history)) {
            if ($_pp>=$_ar) {
                $remain=$_pp-$_ar;
                return '<div class="text-danger">'.$remain.' AED is in loss</div>';
            }
            if ($_pp<$_ar) {
                $remain=$_ar-$_pp;
                return '<div class="text-success">'.$remain.' AED is in profit</div>';
            }
            }
            return '<div class="text-warning">Mobile is not assigned to any rider</div>';
            
        })
        ->rawColumns(['id','date','purchase_price','profit_loss','model'])
        ->make(true);
    }

    public function getSalaryList($month)
    {
        $client_histories = collect([]);
        $client_history=Client_History::all()->toArray(); 
        $tmps = Arr::where($client_history, function ($item, $key) use ($month) {
            $start_created_at =Carbon::parse($item['assign_date'])->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);
    
            $start_updated_at =Carbon::parse($item['deassign_date'])->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($month);
    
            if($item['status']=='active'){    
                return ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
            }
    
            return ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });
        foreach ($tmps as $tmp) {
            $mdl = new Client_History;
            $mdl->rider_id=$tmp['rider_id'];
            $mdl->assign_date=$tmp['assign_date'];
            $mdl->deassign_date=$tmp['deassign_date'];
            $mdl->client_rider_id=$tmp['client_rider_id'];
            $client_histories->push($mdl);
        }

        $rider_ids=[];
        foreach ($client_histories as $client_history) {
            array_push($rider_ids, $client_history->rider_id); 
        }
        $rider= Rider::whereIn('id', $rider_ids)->get();

        // $rider = Rider::where("active_status","A")->get(); 
        return DataTables::of($rider)
        ->addColumn('id', function($rider){
            return "KR-".$rider->id;
        })
        ->addColumn('rider_id', function($rider){
            return '<a  href="'.route('admin.rider.profile', $rider->id).'">'.$rider->name.'</a>';
        })
        ->addColumn('client_name', function($rider) use ($month){
            $rider_id=$rider->id;
            $client_history = Client_History::all();
            $startMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
            $history_found = Arr::first($client_history, function ($item, $key) use ($rider_id, $startMonth) {
                $start_created_at =Carbon::parse($item->assign_date)->startOfMonth()->format('Y-m-d');
                $created_at =Carbon::parse($start_created_at);
        
                $start_updated_at =Carbon::parse($item->deassign_date)->endOfMonth()->format('Y-m-d');
                $updated_at =Carbon::parse($start_updated_at);
                $req_date =Carbon::parse($startMonth);
        
                return $item->rider_id==$rider_id &&
                    ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
            });
            if(isset($history_found)){
                $client = Client::find($history_found->client_id);
                return $client->name;
            }
            return 'No client assigned';
        })
        ->addColumn('salary', function($rider) use($month){
            $start_month=carbon::parse($month)->startOfMonth()->format("Y-m-d");
            $onlyMonth=carbon::parse($month)->format("m");
            $onlyYear=carbon::parse($month)->format("Y");
            
            //prev payables
            $rider_debits_cr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider->id)
            ->where(function($q) {
                $q->where('type', "cr");
            })
            ->whereDate('month', '<',$start_month)
            ->sum('amount');
            
            $rider_debits_dr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider->id)
            ->where(function($q) {
                $q->where('type', "cr_payable")
                ->orWhere('type', 'dr');
            })
            ->whereDate('month', '<',$start_month)
            ->sum('amount');
            $closing_balance_prev = round($rider_debits_cr_prev_payable - $rider_debits_dr_prev_payable,2);
            //ends prev payables

            $ra_payable=Rider_Account::where("rider_id",$rider->id)
            ->whereMonth("month",$onlyMonth)
            ->whereYear("month",$onlyYear)
            ->where(function($q) {
                $q->where('type', "cr_payable")
                ->orWhere('type', 'dr');
            })
            ->where("source",'!=',"salary_paid")
            ->sum('amount');
            $ra_cr=Rider_Account::where("rider_id",$rider->id)
            ->whereMonth("month",$onlyMonth)
            ->whereYear("month",$onlyYear)
            ->where("payment_status","pending")
            ->where("type","cr")
            ->sum('amount');  
            if($closing_balance_prev < 0){ //deduct
                $ra_payable += abs($closing_balance_prev);
            }
            else {
                // add
                $ra_cr += abs($closing_balance_prev);
            }
            // ->where("source",'!=',"salary_paid")
            $ra_recieved=$ra_cr - $ra_payable;
            return round($ra_recieved,2);
        })
        ->addColumn('remaining_salary', function($rider) use($month){
            $start_month=carbon::parse($month)->startOfMonth()->format("Y-m-d");
            $onlyMonth=carbon::parse($month)->format("m");
            $onlyYear=carbon::parse($month)->format("Y");
            
            //prev payables
            $rider_debits_cr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider->id)
            ->where(function($q) {
                $q->where('type', "cr");
            })
            ->whereDate('month', '<',$start_month)
            ->sum('amount');
            
            $rider_debits_dr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider->id)
            ->where(function($q) {
                $q->where('type', "cr_payable")
                ->orWhere('type', 'dr');
            })
            ->whereDate('month', '<',$start_month)
            ->sum('amount');
            $closing_balance_prev = round($rider_debits_cr_prev_payable - $rider_debits_dr_prev_payable,2);
            //ends prev payables

            $ra_payable=Rider_Account::where("rider_id",$rider->id)
            ->whereMonth("month",$onlyMonth)
            ->whereYear("month",$onlyYear)
            ->where(function($q) {
                $q->where('type', "cr_payable")
                ->orWhere('type', 'dr');
            })
            ->sum('amount');
            $ra_cr=Rider_Account::where("rider_id",$rider->id)
            ->whereMonth("month",$onlyMonth)
            ->whereYear("month",$onlyYear)
            ->where("payment_status","pending")
            ->where("type","cr")
            ->sum('amount');  
            if($closing_balance_prev < 0){ //deduct
                $ra_payable += abs($closing_balance_prev);
            }
            else {
                // add
                $ra_cr += abs($closing_balance_prev);
            }
            // ->where("source",'!=',"salary_paid")
            $ra_recieved=$ra_cr - $ra_payable;
            
            if ($ra_recieved>1) {
                return '<div class="text-success">'.round($ra_recieved,0).'</div>';
            }
            if ($ra_recieved<=0) {
                return '<div class="text-warning">'.round($ra_recieved,0).'</div>';
            }
            if ($ra_recieved>0 && $ra_recieved<1) {
                return '<div class="text-danger">'.round($ra_recieved,0).'</div>';
            }
            
        })
        ->addColumn('payment_status', function($rider) use($month){
            $start_month=carbon::parse($month)->startOfMonth()->format("Y-m-d");
            $onlyMonth=carbon::parse($month)->format("m");
            $onlyYear=carbon::parse($month)->format("Y");
            
            $salary_paid=Rider_Account::where("rider_id",$rider->id)
            ->whereMonth("month",$onlyMonth)
            ->whereYear("month",$onlyYear)
            ->where("source","salary_paid")
            ->get()
            ->first();
            if (isset($salary_paid)) {
                // return $salary_paid;
                return "<div class='text-success'>Paid</div>";
            }
            return "<div class='text-danger'>Pending</div>";
        })
        ->addColumn('image', function($rider) use($month){
            $_onlyMonth=carbon::parse($month)->format("m");
            $_onlyYear=carbon::parse($month)->format("Y");
            $slip=0;
            $paid=0;
            $salary_paid=Rider_Account::where("rider_id",$rider->id)
            ->whereMonth("month",$_onlyMonth)
            ->whereYear("month",$_onlyYear)
            ->where("source","salary_paid")
            ->get()
            ->first();
            if (isset($salary_paid)) {
                $paid=1;
            }

            $salary_slip=Rider_salary::where("rider_id",$rider->id)
            ->whereMonth("month",$_onlyMonth)
            ->whereYear("month",$_onlyYear)
            ->get()
            ->first();
            // return $salary_slip;
            if (isset($salary_slip)) {
                $slip=asset(Storage::url($salary_slip->salary_slip_image));
                if ($salary_slip->salary_slip_image==null) {
                    $slip='';
                    return '<a data-image="'.$slip.'" data-rider="'.$rider->id.'" data-paid="'.$paid.'" class="show_image text-warning"><strong>Upload Salary Slip</strong></a>';
                }
                return '<a data-image="'.$slip.'" data-rider="'.$rider->id.'" data-paid="'.$paid.'" class="show_image text-success"><i class="fa fa-eye"></i></a>';
            }
            return '<a data-image="'.$slip.'" data-rider="'.$rider->id.'" data-paid="'.$paid.'" class="show_image text-danger"><strong>Salary is not paid</strong></a>';
        })
        ->rawColumns(['id','rider_id','client_name','salary','remaining_salary','image','payment_status'])
        ->make(true);
    }

    public function getExpenseData($month)
    {
        $_onlyMonth=carbon::parse($month)->format('m');
        $_onlyYear=carbon::parse($month)->format('Y');
        $export_data = Export_data::where("active_status","A")
        ->whereMonth("month",$_onlyMonth)
        ->whereYear("month",$_onlyYear)
        ->get();
        return DataTables::of($export_data)
        ->addColumn('id', function($export_data){
            $src=$export_data->source;
            $kr='O-';
            if ( $src=="advance") {$kr="A-";}
            if ( $src=="Bonus") {$kr="B-";}
            if ( $src=="Mobile Installment") {$kr="MI-";}
            if ( $src=="Visa Charges") {$kr="VC-";}
            if ( $src=="fuel_expense_vip") {$kr="F-";}
            if ( $src=="fuel_expense_cash") {$kr="F-";} 
            if ( $src=="Bike Rent") {$kr="BR-";}
            if ( $src=="Discipline Fine") {$kr="KF-";}
            if ( $src=="Sim Transaction") {$kr="S-";}
            if ( $src=="Salik") {$kr="S-";}
            if ( $src=="pay_cash") {$kr="PC-";}
            if ( $src=="receive_cash") {$kr="RC-";}
            if ( $src=="Salik") {$kr="S-";}
            if ( strpos($src,'RC@')!==false) {$kr="RC-";} 
            if ( strpos($src,'PC@')!==false) {$kr="PC-";} 
            if ( $src=="salary") {$kr="S-";}
            return $kr.$export_data->id;
        })
        ->addColumn('source', function($export_data){
            $source=$export_data->source;
            if ( strpos($source,'RC@')!==false || strpos($source,'PC@')!==false) $source=explode('@',$source)[1];
            return $source;
        })
        ->addColumn('rider_id', function($export_data){
            $rider=Rider::find($export_data->rider_id);
            if (isset($rider)) {
                return '<a  href="'.route('admin.rider.profile', $rider->id).'">'.$rider->name.'</a>';
            }
            return 'no rider is found';
        })
        ->addColumn('month', function($export_data){
            return carbon::parse($export_data->month)->format('F Y');
        })
        ->addColumn('given_date', function($export_data){
            return carbon::parse($export_data->given_date)->format('F d,Y');
        })
        ->addColumn('amount', function($export_data){
            return $export_data->amount;
        })
        ->rawColumns(['id','source','rider_id','month','given_date','amount'])
        ->make(true);
    }

    public function getExpenseLoss($month,$source)
    {   
        if ($source=="sim") { 
            $bill= Sim::where("active_status","A")->get();
        }
        if ($source=="bike") {
            $bill = bike::where("active_status","A")->get();
        }
        return DataTables::of($bill)
        ->addColumn('bill_source', function($bill) use($month,$source){
            if ($source=="sim") { 
                return $bill->sim_number.'-'.$bill->sim_company;
            }
            if ($source=="bike") {
                return $bill->owner.'-'.$bill->brand.'-'.$bill->bike_number;
            }
        })
        ->addColumn('bill_amount', function($bill) use($month,$source){
            $_onlyMonth=carbon::parse($month)->format('m');
            $_onlyYear=carbon::parse($month)->format('Y');
            if ($source=="sim") { 
                return 123;
            }
            if ($source=="bike") {
                $ed=Export_data::whereMonth("month",$_onlyMonth)->whereYear("month",$_onlyYear)->where("bill_id",$bill->id)->where("source","Bike Rent")->get();
                $rider_id='';
                $bill_id='';
                $html='';
                $total_amount=0;
                $a=0;
                if (isset($ed->bill_id)) {
                    $bill_id=$ed->bill_id;
                }
                if (isset($ed)) {
                    foreach ($ed as $value) {
                        $bill_id=$value->bill_id;
                    }
                }
                    $paid_bills = Bill_change::whereMonth('month', $_onlyMonth)
                        ->whereYear('month', $_onlyYear)
                        ->where('type', 'bike_rent')
                        ->get();
                        foreach ($paid_bills as $key => $item) {
                            $feed = json_decode($item->feed,true);
                            foreach ($feed as $feed_item) {
                                if($feed_item['bike_id']==$bill->id){
                                    $total_amount=$item->amount;
                                }
                            }
                            $a=$total_amount;
                        }
                     }
            return $a;
        })
        ->addColumn('company_account', function($bill) use($month,$source){
            $_onlyMonth=carbon::parse($month)->format('m');
            $_onlyYear=carbon::parse($month)->format('Y');
            if ($source=="sim") { 
                return "Sim";
            }
            if ($source=="bike") {
                $ed=Export_data::whereMonth("month",$_onlyMonth)->whereYear("month",$_onlyYear)->where("bill_id",$bill->id)->get();
                $html='';
                $html_not_found='<span style="color: #fa8484;">No row is found against this bike.</span>';
                if (isset($ed)) {
                    foreach ($ed as $key => $value) {
                        $ca=Company_Account::whereMonth("month",$_onlyMonth)
                        ->whereYear("month",$_onlyYear)
                        ->where("source",$value->source)
                        ->where("bike_rent_id",$value->source_id)
                        ->get();
                        foreach ($ca as $item) {
                            if (isset($item->rider_id)) {
                                $rider=Rider::find($item->rider_id);
                                $rider_name=$rider->name;
                                $html.='<p>
                                        <strong>'.$rider_name.'</strong>: 
                                        '.$item->amount.' 
                                    </p>';
                            }
                        }
                    }
                }
            }
            if ($html=='') {
                return $html_not_found;
            }
            return $html;
        })
        ->addColumn('rider_account', function($bill) use($month,$source){
            $_onlyMonth=carbon::parse($month)->format('m');
            $_onlyYear=carbon::parse($month)->format('Y');
            if ($source=="sim") { 
                return "Sim";
            }
            if ($source=="bike") {
                $ed=Export_data::whereMonth("month",$_onlyMonth)->whereYear("month",$_onlyYear)->where("bill_id",$bill->id)->where("source","Bike Rent")->get();
                $html='';
                $html_not_found='<span style="color: #fa8484;">No row is found against this bike.</span>';
                if (isset($ed)) {
                    foreach ($ed as $key => $value) {
                        $ra=Rider_Account::whereMonth("month",$_onlyMonth)
                        ->whereYear("month",$_onlyYear)
                        ->where("source",$value->source)
                        ->where("bike_rent_id",$value->source_id)
                        ->get();
                        if (isset($ra)) {
                            foreach ($ra as $item) {
                                if (isset($item->rider_id)) {
                                    $rider=Rider::find($item->rider_id); 
                                    $rider_name=$rider->name;
                                    $html.='<p>
                                                <strong>'.$rider_name.'</strong>: 
                                                '.$item->amount.'
                                            </p>';  
                                }
                            }
                        }
                    }
                }
            }
            if ($html=='') {
                return $html_not_found;
            }
            return $html;
        })
        ->addColumn('loss', function($bill) use($month,$source){
            $_onlyMonth=carbon::parse($month)->format('m');
            $_onlyYear=carbon::parse($month)->format('Y');
            if ($source=="sim") { 
                return "Sim";
            }
            if ($source=="bike") {
                $ce=Company_Expense::whereMonth("month",$_onlyMonth)
                ->whereYear("month",$_onlyYear)
                ->where("bill_id",$bill->id)
                ->where("type","bike_rent") 
                ->sum('amount');
            }
            return round($ce,2);
        })
        ->rawColumns(['loss','company_account','bill_amount','bill_source','rider_account'])
        ->make(true);
    }

    public function getDeletedData()
    {
        $del_data = Deleted_data::all();
        return DataTables::of($del_data)
        // ->addColumn('id', function($del_data){
        //         return $del_data->id;
        // })
        ->addColumn('date', function($del_data){
            return carbon::parse($del_data->date)->format("F d, Y");
        })
        ->addColumn('deleted_by', function($del_data){
            $auth_name=Admin::find($del_data->deleted_by);
            return $auth_name->name;
        })
        ->addColumn('feed', function($del_data){
            $arr=json_decode($del_data->feed,true);
            $html='';
            
            foreach ($arr as $item) {
                $html_data='';
                $data=json_decode($item['data'],true);
                 foreach ($data as $key => $value) {
                     if ($value!=null ||$value!="") {
                        $html_data.='<p><strong>'.$key.': </strong>'.$value.'</p>';
                     }
                }
                $popoverHtml='<button type="button" 
                    class="btn btn-outline-warning btn-elevate btn-icon btn-sm btn-circle" 
                    data-toggle="popover" 
                    data-trigger="focus" 
                    data-placement="top" 
                    data-html="true" 
                    data-content="'.$html_data.'">
                    <i class="la la-question"></i>
                    </button>';
               
                $html.='<p>
                            <strong>Model: </strong>
                            '.$item['model'].' '.$popoverHtml.'
                    </p>';
               
            }
            return $html;
        })
        ->addColumn('status', function($del_data){
            return $del_data->status;
        })
        ->addColumn('actions', function($del_data){
            $id=$del_data->id;
            if($del_data->status!='request_send'){
                return '<button class="btn btn-danger" onclick="retreive_data('.$id.')">Request for Retreive Data</button>';
            }
            return '<label class="text-warning">Request Already sent</label>'; 
            
        })
        ->rawColumns(['id','date','deleted_by','feed','status','actions'])
        ->make(true);
    }
}
