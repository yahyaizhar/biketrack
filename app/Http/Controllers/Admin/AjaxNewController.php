<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Client\Client;
use App\Model\Client\Client_Rider;
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
use App\Model\Admin\Admin;
use App\Company_investment;


class AjaxNewController extends Controller
{
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
                return $rider['name'];
            }
            else{
                return 'No Client Rider Id is Assigned';
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
        ->where("type","dr")
        ->whereNotNull('sim_transaction_id')
        ->get();
        $model = $modelArr->first();
        foreach ($modelArr as $mod) {
            if(isset($mod) && $mod->id !=$model->id){
                $model->amount+=$mod->amount;
            }
        }
        $bills->push($model);
        
        //Salik
        $modelArr = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->where("type","dr")
        ->whereNotNull('salik_id')
        ->get();
        $model = $modelArr->first();
        foreach ($modelArr as $mod) {
        if(isset($mod) && $mod->id !=$model->id){
            $model->amount+=$mod->amount;
            
        }
        }
        $bills->push($model);
        
        // bike_fine
        $modelArr = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->where("type","dr")
        ->whereNotNull('bike_fine')
        ->get();
        $model = $modelArr->first();
        foreach ($modelArr as $mod) {
        if(isset($mod) && $mod->id !=$model->id){
            $model->amount+=$mod->amount;
            
        }
        }
        $bills->push($model);
        //fuel_expense
        $modelArr = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->whereNotNull('fuel_expense_id')
        ->where('source','fuel_expense_vip')
        ->get();
        $model = $modelArr->first();
        foreach ($modelArr as $mod) {
        if(isset($mod) && $mod->id != $model->id){
                $model->amount+=$mod->amount;
            }
        }
        $bills->push($model);
        $modelArr = \App\Model\Accounts\Company_Account::
        whereMonth('month', $month)
        ->where("rider_id",$rider_id)
        ->whereNotNull('fuel_expense_id')
        ->where('source','fuel_expense_cash')
        ->get();
        $model = $modelArr->first();
        foreach ($modelArr as $mod) {
        if(isset($mod) && $mod->id != $model->id){
                $model->amount+=$mod->amount;
            }
        }
        $bills->push($model);
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
                    $rider_id=$bill->rider_id;
                    $type=$bill->source;
                    return '<div>Pending <button type="button" onclick="updateStatus('.$rider_id.',\''.$month.'\',\''.$type.'\')" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button></div>';
                }
                
                return ucfirst($bill->payment_status).' <i class="flaticon2-correct text-success h5"></i>';
            
            }
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
        $rider_name=Rider::find($ranges['rider_id']);
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
        $hours=Income_zomato::where("rider_id",$ranges['rider_id'])
        ->whereDate('date', '>=',$from)
        ->whereDate('date', '<=',$to)
        ->sum('log_in_hours_payable');
        if($hours > 286) $hours = 286;
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
        $tip=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Tips Payouts')
        ->sum('amount');
        $bike_allowns=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Bike Allowns')
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
        ->sum('amount');
        $salik=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Salik')
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
        $macdonald=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Mcdonalds Deductions')
        ->sum('amount');
        $rta=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->whereNotNull('id_charge_id')
        ->sum('amount');
        $mobile=\App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Mobile Charges')
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
        $closing_balance_prev = $rider_debits_cr_prev_payable - $rider_debits_dr_prev_payable;
        $running_balance =$closing_balance_prev;
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
            return Carbon::parse($rider_statement->created_at)->format('M d, Y');
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
                    return '<div>Salary Recieved from Kingriders <button type="button" id="getting_val" onclick="remaining_pay('.$rider_id.','.$rider_statement->id.')" data-toggle="modal" data-target="#remaining_pay_modal" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button></div>';
                }
                // updateStatus('.$rider_statement->id.')
                return "Salary Recieved from Kingriders";
            }
            if($rider_statement->source == 'salary_paid'){
                return "Salary Paid";
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
                $running_balance -= $rider_statement->amount;
            }
            else{
                $running_balance += $rider_statement->amount; 
            }
            if($rider_statement->type=='skip') return '<strong >'.round($running_balance,2).'</strong>';
            return round($running_balance,2);
        })
        ->addColumn('action', function($rider_statement) use (&$running_balance){
            $model="";
            $model_id="";
            if($rider_statement->sim_transaction_id!=null){
                $model_id=$rider_statement->sim_transaction_id;
                $sim_transaction=new Sim_Transaction();
                $model=get_class($sim_transaction);
                
            }
            if($rider_statement->fuel_expense_id!=null){
                $model_id=$rider_statement->fuel_expense_id;
                $fuel=new Fuel_Expense();
                $model=get_class($fuel);
            }	
            if($rider_statement->advance_return_id!=null){
                $model_id=$rider_statement->advance_return_id;
                $advance=new AdvanceReturn();
                $model=get_class($advance);
            }
            if($rider_statement->id_charge_id!=null){
                $model_id=$rider_statement->	id_charge_id;
                $id_charges=new Id_charge();
                $model=get_class($id_charges);
            }
            if($rider_statement->mobile_installment_id!=null){
                $model_id=$rider_statement->mobile_installment_id;
                $mobile_installment=new Mobile_installment();
                $model=get_class($mobile_installment);
            }
            // if($rider_statement->fuel_expense_id!=null){
            //     $model_id=$rider_statement->fuel_expense_id;
            //     $fuel=new Fuel_Expense();
            //     $model=get_class($fuel);
            // }
            // if($rider_statement->fuel_expense_id!=null){
            //     $model_id=$rider_statement->fuel_expense_id;
            //     $fuel=new Fuel_Expense();
            //     $model=get_class($fuel);
            // }
            if ($model_id!=null) {
                $model = addslashes($model);
                return '<i class="fa fa-trash-alt"  onclick="deleteCompanyRows('.$rider_statement->id.',\''.$model.'\','.$model_id.')"></i>';
            }
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
            'closing_balance' => round($closing_balance,2),
            'rider_debits_cr_prev_payable'=>$rider_debits_cr_prev_payable,
            'rider_debits_dr_prev_payable'=>$rider_debits_dr_prev_payable,
            'rider'=>$rider,
            'month_year'=>Carbon::parse($from)->format('M, Y'),
            'today_date'=>Carbon::parse($date)->format('m/d/Y'),
            'employee_id'=>$ranges['rider_id'],
            'payment_date'=>Carbon::now()->format('M d, Y'),
            'salary'=>$salary,
            'trips'=>$trips,
            'extra_trips'=>$extra_trips,
            'hours'=>$hours,
            'bike_allowns'=>$bike_allowns,
            'bike_fine'=>$bike_fine,
            'ncw'=>$ncw,
            'tip'=>$tip,
            'bones'=>$bones,
            'advance'=>$advance,
            'salik'=>$salik,
            'sim'=>round($sim,2),
            'dc'=>$dc,
            'macdonald'=>$macdonald,
            'rta'=>$rta,
            'mobile'=>$mobile,
            'dicipline'=>0,
            'denial_penalty'=>$denial_penalty,
            'mics'=>0,
            'cash_paid'=>$cash_paid_in_advance,
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
                if($company_statement->payment_status=="pending" && $company_statement->type!="cr"){
                    //skip this
                    $continue = true;
                }
            }

            if(!$continue){
                $company_statements->push($company_statement);
            }
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
            return Carbon::parse($company_statements->created_at)->format('M d, Y');
        })
        ->addColumn('desc', function($company_statement)  use ($company_statements){
            if($company_statement->type=='skip') return '<strong >'.$company_statement->source.'</strong>';
            $ras = $company_statements->toArray();
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
                    return '<div>Fine Paid By Kingriders <button type="button" id="getting_val" onclick="FineBike('.$amount.','.$rider_id.','.$bike_fine_id.',\''.$month.'\')" data-toggle="modal" data-target="#bike_fine" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button></div>';
                }
                return "Bike Fine Paid By King Riders";
            }
            if($company_statement->source == 'Bike Fine Paid'){
                return "Bike Fine Paid";
            }
            return $company_statement->source;
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
            $model="";
            $model_id="";
            if($company_statements->sim_transaction_id!=null){
                $model_id=$company_statements->sim_transaction_id;
                $sim_transaction=new Sim_Transaction();
                $model=get_class($sim_transaction);
                
            }
            if($company_statements->fuel_expense_id!=null){
                $model_id=$company_statements->fuel_expense_id;
                $fuel=new Fuel_Expense();
                $model=get_class($fuel);
            }	
            if($company_statements->advance_return_id!=null){
                $model_id=$company_statements->advance_return_id;
                $advance=new AdvanceReturn();
                $model=get_class($advance);
            }
            if($company_statements->id_charge_id!=null){
                $model_id=$company_statements->	id_charge_id;
                $id_charges=new Id_charge();
                $model=get_class($id_charges);
            }
            if($company_statements->mobile_installment_id!=null){
                $model_id=$company_statements->mobile_installment_id;
                $mobile_installment=new Mobile_installment();
                $model=get_class($mobile_installment);
            }
            // if($company_statements->fuel_expense_id!=null){
            //     $model_id=$company_statements->fuel_expense_id;
            //     $fuel=new Fuel_Expense();
            //     $model=get_class($fuel);
            // }
            // if($company_statements->fuel_expense_id!=null){
            //     $model_id=$company_statements->fuel_expense_id;
            //     $fuel=new Fuel_Expense();
            //     $model=get_class($fuel);
            // }
            if ($model_id!=null) {
                $model = addslashes($model);
                return '<i class="fa fa-trash-alt"  onclick="deleteCompanyRows('.$company_statements->id.',\''.$model.'\','.$model_id.')"></i>';
            }
            
         
            
        })
        
        ->with([
            'closing_balance' => round($closing_balance,2),
            'last_month' => $first_month,
            'running_static_balance' => $running_static_balance
        ])
        ->rawColumns(['desc','date','cr','dr','balance', 'company_profit','action'])
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
            return '1000'.$expense->id;
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
        ->rawColumns(['status','bike_id','type','amount','actions', 'rider_id'])
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
            return '1000'.$ar->id;
        })
        ->addColumn('amount', function($ar){
            return $ar->amount;
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
        ->rawColumns(['status','type','rider_id','payment_status','amount','actions', 'status'])
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
            return $client_income->amount;
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
        $CE=Company_Expense::where("active_status","A")->whereMonth('month', $month)->get();
        $total_expense=Company_Expense::where("active_status","A")->whereMonth('month', $month)->sum("amount");
        
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

    
 

    public function getMobileTransaction($month) 
    {
        $all_mobiles =Mobile::where("payment_type","installment")->orderByDesc('created_at')->get();
        $mob_trans=Mobile_Transaction::all();
        return DataTables::of($all_mobiles)
        ->addColumn('model', function($mobile) use ($month,$mob_trans){
            // $zp_found = Arr::first($mob_trans, function ($item_zp, $key) use ($month) {
            //     return $item_zp->month == Carbon::parse($month)->format('Y-m-d');
            // });
                   return $mobile->model;
               
              
        })
        ->addColumn('sale_price', function($all_mobiles) use ($month,$mob_trans){
            
                return $all_mobiles->sale_price;
               
        })
        ->addColumn('rider_id', function($all_mobiles) use ($month,$mob_trans){
            $riders=Rider::find($all_mobiles->rider_id);
            if (isset($riders)) {
                return $riders->name;
            }
            return 'No Assigned Rider';
           
        })
        ->addColumn('amount_received', function($all_mobiles) use ($month,$mob_trans){
                return $all_mobiles->amount_received;
              
        })
        ->addColumn('remaining_amount', function($all_mobiles) use ($month,$mob_trans){
          
                $RA=$all_mobiles->amount_received;
                $SP=$all_mobiles->sale_price;
                $remaining_amount=$SP-$RA;
                return $remaining_amount;
               
        })
        ->addColumn('per_month_installment_amount', function($all_mobiles) use ($month,$mob_trans){
            return '0';
        })
        ->addColumn('month', function($all_mobiles) use ($month,$mob_trans) {
            // $mob_tran =Mobile_Transaction::find($all_mobiles->id)->whereMonth('month', Carbon::parse($month)->format('m'))->get()->first();
            // if(isset($mob_tran)){
            //     return Carbon::parse($mob_tran->month)->format('F Y');
            // }
            return Carbon::now()->format('F Y');
        })
        ->addColumn('bill_status', function($all_mobiles) use ($month,$mob_trans){
            $RA=$all_mobiles->amount_received;
            $SP=$all_mobiles->sale_price;
            $remaining_amount=$SP-$RA;
          
            if ($remaining_amount<=0) {
                return 'paid' ;
            }
           
            return "pending" ;
        
        })
        
        ->rawColumns(['model','rider_id','month','sale_price','amount_received','bill_status','remaining_amount','per_month_installment_amount', 'status'])
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
                    $view_url = '<a class="dropdown-item" href="'.route('admin.CE_edit_view', $subObj).'"><i class="fa fa-edit"></i> View</a>';
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
    public function zomato_salary_export($month)
    {
        $zomato=Client::where("name","Zomato Food Delivery")->get()->first();
        // $client_riders=$zomato->riders();
        $client_riders=Client_Rider::where('client_id', $zomato->id)->get();
        return DataTables::of($client_riders)
        ->addColumn('rider_name', function($rider) {
            $riderFound = Rider::find($rider->rider_id);
            return $riderFound->name;
        }) 
        ->addColumn('bike_number', function($rider) {
              $assign_bike=Assign_bike::where("rider_id",$rider->rider_id)->where("status","active")->get()->first();             
            if (isset($assign_bike)) {
                $bike=bike::find($assign_bike->bike_id);
                return $bike->bike_number;
            }
              return 'No Bike is assigned';
        }) 
        ->addColumn('advance', function($rider) use ($month) {
            $advance_sum=Rider_Account::where('rider_id',$rider->rider_id)
            ->whereNotNull('advance_return_id')
            ->whereMonth('month',$month)
            ->get()
            ->sum('amount');
                return $advance_sum;
        }) 
        ->addColumn('poor_performance', function($rider) use ($month) {
            $poor_performance_sum=Rider_Account::where('source',"Denials Penalty")
            ->where('rider_id',$rider->rider_id)
            ->whereNotNull('income_zomato_id')
            ->whereMonth('month',$month)
            ->get()
            ->sum('amount');
                return $poor_performance_sum;
        }) 
        ->addColumn('visa', function($rider) use ($month) {
            $visa_sum=Rider_Account::where('source',"Visa Charges")
            ->where('rider_id',$rider->rider_id)
            ->whereNotNull('id_charge_id')
            ->whereMonth('month',$month)
            ->get()
            ->sum('amount');
                return $visa_sum;
        }) 
        ->addColumn('mobile_charges', function($rider) use ($month) {
            $mobile_charges=Rider_Account::where('source',"Mobile Charges")
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->sum('amount');
                return $mobile_charges;
        }) 
        ->addColumn('mobile', function($rider) use ($month) {
            $mobile_sum=Rider_Account::where('source',"Mobile Installment")
            ->where('rider_id',$rider->rider_id)
            ->whereNotNull('mobile_installment_id')
            ->whereMonth('month',$month)
            ->get()
            ->sum('amount');
                return $mobile_sum;
        }) 
        ->addColumn('bike_allowns', function($rider) use ($month) {
            $bike_allowns=Rider_Account::where('source',"Bike Allowns")
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->get()
            ->sum('amount');
                return $bike_allowns;
        }) 
        ->addColumn('bonus', function($rider) use ($month) {
            $bonus=Rider_Account::where('source',"400 Trips Acheivement Bonus")
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->get()
            ->sum('amount');
                return $bonus;
        }) 
        ->addColumn('number_of_hours', function($rider) use ($month) {
            $number_of_hours_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('log_in_hours_payable');
            if($number_of_hours_sum > 286) $number_of_hours_sum = 286;
                return $number_of_hours_sum; 
        }) 
        ->addColumn('number_of_trips', function($rider) use ($month) {
            $number_of_trips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('trips_payable');
            if ( $number_of_trips_sum > 400) $number_of_trips_sum=400; 
                return $number_of_trips_sum; 
        }) 
        ->addColumn('aed_trips', function($rider) use ($month) {
            $aed_trips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('trips_payable');
            if ( $aed_trips_sum > 400) $aed_trips_sum=400; 
                return $aed_trips_sum*2; 
        }) 
        ->addColumn('extra_trips', function($rider) use ($month) {
            $number_of__extra_trips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('trips_payable');
            if ( $number_of__extra_trips_sum > 400){; 
                return $number_of__extra_trips_sum-400; 
            }
        }) 
        ->addColumn('aed_extra_trips', function($rider) use ($month) {
            $aed_extra_trips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('trips_payable');
            if ( $aed_extra_trips_sum > 400){; 
                return ($aed_extra_trips_sum-400)*4; 
            }
        }) 
        ->addColumn('ncw', function($rider) use ($month) {
            $ncw_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('ncw_incentives');
                return $ncw_sum; 
        }) 
        ->addColumn('tips', function($rider) use ($month) {
            $tips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('tips_payouts');
                return $tips_sum; 
        }) 
        ->addColumn('salik', function($rider) use ($month) {
            $salik_amount=Rider_Account::where('rider_id',$rider->rider_id)
            ->whereNotNull('salik_id')
            ->whereMonth('month', $month)
            ->get()
            ->sum('amount');
            return $salik_amount;
        }) 
        ->addColumn('fuel', function($rider) use ($month) {
            $fuel_amount=Company_Account::where('rider_id',$rider->rider_id)
            ->whereNotNull('fuel_expense_id')
            ->whereMonth('month', $month)
            ->get()
            ->sum('amount');
            return $fuel_amount;
        }) 
        ->addColumn('sim_charges', function($rider) use ($month) {
            $sim_charges=Rider_Account::where('rider_id',$rider->rider_id)
            ->whereNotNull('sim_transaction_id')
            ->whereMonth('month', $month)
            ->get()
            ->sum('amount');
            return $sim_charges;
        }) 
        ->addColumn('cod', function($rider) use ($month) {
            $cod=Rider_Account::where('rider_id',$rider->rider_id)
            ->whereNotNull('income_zomato_id')
            ->whereMonth('month', $month)
            ->where('source',"Mcdonalds Deductions")
            ->get()
            ->sum('amount');
            return $cod;
        }) 
        ->addColumn('dc', function($rider) use ($month) {
            $dc=Rider_Account::where('rider_id',$rider->rider_id)
            ->whereNotNull('income_zomato_id')
            ->where('source',"DC Deductions")
            ->whereMonth('month', $month)
            ->get()
            ->sum('amount');
            return $dc;
        }) 
        ->addColumn('rta_fine', function($rider) use ($month) {
            $rta_fine=Rider_Account::where('rider_id',$rider->rider_id)
            ->whereNotNull('id_charge_id')
            ->whereMonth('month', $month)
            ->get()
            ->sum('amount');
            return $rta_fine;
        }) 
        ->addColumn('dicipline_fine', function($rider) use ($month) {
            return '0';
        }) 
        ->addColumn('total_deduction', function($rider) use ($month) {
            $total_deduction=Rider_Account::where('rider_id',$rider->rider_id)
            ->where(function($q) {
                $q->where('type', "cr_payable")
                  ->orWhere('type', 'dr');
            })
            ->where('payment_status','pending')
            ->whereMonth('month', $month)
            ->get()
            ->sum('amount');
            return $total_deduction;
        }) 
        ->addColumn('aed_hours', function($rider) use ($month) {
            $number_of_hours_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('log_in_hours_payable');
            if ($number_of_hours_sum > 286) {
                $number_of_hours_sum=286;
                return $number_of_hours_sum * 7.87;
            }

            return $number_of_hours_sum * 7.87;
        })
        ->addColumn('total_salary', function($rider) use ($month) {
            $number_of_hours_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->sum('log_in_hours_payable');
            if($number_of_hours_sum > 286) $number_of_hours_sum = 286;
            $number_of_hours_sum = $number_of_hours_sum * 7.87;
            
            $aed_trips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->sum('trips_payable');
            if ($aed_trips_sum > 400) {
                $aed_extra_trips=($aed_trips_sum - 400)*4;
                $aed_trips = 400 * 2;
                $aed_total=$aed_trips + $aed_extra_trips;
            }
            if($aed_trips_sum <= 400){
                $aed_extra_trips=0;
                $aed_trips_sum = $aed_trips_sum * 2;
                $aed_total=$aed_trips_sum + $aed_extra_trips;
            }
            
            $total_salary =$number_of_hours_sum + $aed_total;
            return $total_salary;
        })
        ->addColumn('net_salary', function($rider) use ($month) {
            $month = '01-'.$month.'-'.Carbon::now()->format('Y');
            $rider_id = $rider->rider_id;

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
            ->where("type","cr")
            ->where('source', '!=', 'salary')
            ->sum('amount');  
            if($closing_balance_prev > 0){
                // add
                $ra_cr += abs($closing_balance_prev);
            }

            //total salary
            $number_of_hours_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$onlyMonth)
            ->get()
            ->sum('log_in_hours_payable');
            if($number_of_hours_sum > 286) $number_of_hours_sum = 286;
            $number_of_hours_sum = $number_of_hours_sum * 7.87;
            
            $aed_trips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$onlyMonth)
            ->get()
            ->sum('trips_payable');
            if ($aed_trips_sum > 400) {
                $aed_extra_trips=($aed_trips_sum - 400)*4;
                $aed_trips = 400 * 2;
                $aed_total=$aed_trips + $aed_extra_trips;
            }
            if($aed_trips_sum <= 400){
                $aed_extra_trips=0;
                $aed_trips_sum = $aed_trips_sum * 2;
                $aed_total=$aed_trips_sum + $aed_extra_trips;
            }
            
            $total_salary =$number_of_hours_sum + $aed_total;

            $bonus=Rider_Account::where('source',"400 Trips Acheivement Bonus")
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->get()
            ->sum('amount');

            $bike_allowns=Rider_Account::where('source',"Bike Allowns")
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->get()
            ->sum('amount');

            $ra_salary=$total_salary + $ra_cr + $bonus +$bike_allowns;

            return round($ra_salary,2);


        })
        ->addColumn('gross_salary', function($rider) use ($month) {
            $month = '01-'.$month.'-'.Carbon::now()->format('Y');
            $rider_id = $rider->rider_id;

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
            ->where("payment_status","pending")
            ->where(function($q) {
                $q->where('type', "cr_payable")
                ->orWhere('type', 'dr');
            })
            ->sum('amount');

            $ra_cr=Rider_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$onlyMonth)
            ->where("type","cr")
            ->where('source', '!=', 'salary')
            ->sum('amount');  
            if($closing_balance_prev < 0){ //deduct
                $ra_payable += abs($closing_balance_prev);
            }
            else {
                // add
                $ra_cr += abs($closing_balance_prev);
            }

            //total salary
            $number_of_hours_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$onlyMonth)
            ->get()
            ->sum('log_in_hours_payable');
            if($number_of_hours_sum > 286) $number_of_hours_sum = 286;
            $number_of_hours_sum = $number_of_hours_sum * 7.87;
            
            $aed_trips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$onlyMonth)
            ->get()
            ->sum('trips_payable');
            if ($aed_trips_sum > 400) {
                $aed_extra_trips=($aed_trips_sum - 400)*4;
                $aed_trips = 400 * 2;
                $aed_total=$aed_trips + $aed_extra_trips;
            }
            if($aed_trips_sum <= 400){
                $aed_extra_trips=0;
                $aed_trips_sum = $aed_trips_sum * 2;
                $aed_total=$aed_trips_sum + $aed_extra_trips;
            }
            
            $total_salary =$number_of_hours_sum + $aed_total;

            $ra_salary=$total_salary + $ra_cr;
            $ra_recieved=$ra_salary - $ra_payable;

            return round($ra_recieved,2);
        })
        ->rawColumns(['fuel','mobile_charges','bonus','bike_allowns','aed_extra_trips','extra_trips','net_salary','gross_salary','rider_name','bike_number','advance','poor_performance', 'salik', 'sim_charges', 'dc', 'cod', 'rta_fine', 'total_deduction', 'aed_hours', 'total_salary','visa','mobile','tips','aed_trips','ncw','number_of_trips','number_of_hours'])
        ->make(true);
    }

    public function zomato_profit_export($month)
    {
        $zomato=Client::where("name","Zomato Food Delivery")->get()->first();
        // $client_riders=$zomato->riders();
        $client_riders=Client_Rider::where('client_id', $zomato->id)->get();
        return DataTables::of($client_riders)
        ->addColumn('rider_name', function($rider) {
            $riderFound = Rider::find($rider->rider_id);
            return $riderFound->name;
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
            $aed_trips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('trips_payable');
                return round($aed_trips_sum*6.75,2); 
        }) 
        ->addColumn('aed_hours', function($rider) use ($month) {
            $number_of_hours_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('log_in_hours_payable');
            return round($number_of_hours_sum *6,2);
        })
        ->addColumn('number_of_hours', function($rider) use ($month) {
            $number_of_hours_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('log_in_hours_payable');
            // if($number_of_hours_sum > 286) $number_of_hours_sum = 286;
                return round($number_of_hours_sum,2); 
        }) 
        ->addColumn('number_of_trips', function($rider) use ($month) {
            $number_of_trips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('trips_payable');
            // if ( $number_of_trips_sum > 400) $number_of_trips_sum=400; 
                return round($number_of_trips_sum,2); 
        }) 
        ->addColumn('ncw', function($rider) use ($month) {
            $ncw_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('ncw_incentives');
                return round($ncw_sum,2); 
        }) 
        ->addColumn('tips', function($rider) use ($month) {
            $tips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('tips_payouts');
                return round($tips_sum,2); 
        }) 
        ->addColumn('penalty', function($rider) use ($month) {
            $penalty_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('denials_penalty');
                return round($penalty_sum,2); 
        }) 
        ->addColumn('payout', function($rider) use ($month) {
            $payout_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('total_to_be_paid_out');
                return round($payout_sum,2); 
        }) 
        ->addColumn('cod', function($rider) use ($month) {
            $cod_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('mcdonalds_deductions');
                return round($cod_sum,2); 
        }) 
        ->addColumn('dc', function($rider) use ($month) {
            $dc_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('dc_deductions');
                return round($dc_sum,2); 
        }) 
        ->addColumn('salik', function($rider) use ($month) {
            // $assign_bike=Assign_bike::where('rider_id',$rider->rider_id)->where('status','active')->get()->first();
            // if (isset($assign_bike)) {
            //     $bike=bike::find($assign_bike->bike_id);
            //     if (isset($bike)) {
            //         
            //     } 
            // } 
            $salik_amount=Company_Account::whereMonth('month',$month) 
                    ->where("source","Salik")
                    ->where('rider_id',$rider->rider_id)
                    ->sum('amount');
                    return round($salik_amount,2);
            
        }) 
        ->addColumn('sim_charges', function($rider) use ($month) {
            $sim_charges=Company_Account::where("source","Sim Transaction")
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->where('type','dr')
            ->whereNotNull('sim_transaction_id')
            ->sum('amount');
            return round($sim_charges,2);
        }) 
        ->addColumn('fuel', function($rider) use ($month) {
            $fuel_amount=Company_Account::whereNotNull('fuel_expense_id')
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->sum('amount');
            return round($fuel_amount,2);
        }) 
        ->addColumn('bonus', function($rider) use ($month) {
            $bonus_amount=Company_Account::where('source','400 Trips Acheivement Bonus')
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->sum('amount');
            return round($bonus_amount,2);
        }) 
        ->addColumn('bike_rent', function($rider) use ($month) {
            $bike_rent_amount=Company_Account::where('source','Bike Rent')
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->sum('amount');
            return round($bike_rent_amount,2);
        }) 
        ->addColumn('net_salary', function($rider) use ($month) {
            $salary=Company_Account::where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->where('source','salary')
            ->where('type','dr')
            ->sum('amount');
            return round($salary,2);
        })
        ->addColumn('profit', function($rider) use ($month) {
            $payout_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('total_to_be_paid_out');
            $dc_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('dc_deductions');
            $cod_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('mcdonalds_deductions');
            $penalty_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('denials_penalty');

            $ncw_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('ncw_incentives');

            $tips_sum=Income_zomato::where('rider_id',$rider->rider_id)
            ->whereMonth('date',$month)
            ->get()
            ->sum('tips_payouts');

            $salary=Company_Account::where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->where('source','salary')
            ->where('type','dr')
            ->sum('amount');
            $fuel_amount=Company_Account::whereNotNull('fuel_expense_id')
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->sum('amount');
            $sim_charges=Company_Account::where("source","Sim Transaction")
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->where('type','dr')
            ->whereNotNull('sim_transaction_id')
            ->sum('amount');
            $sim_charges_EXTRA=Company_Account::where("source","Sim extra usage")
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->sum('amount');
            //  $salik_amount=0;
            // $assign_bike=Assign_bike::where('rider_id',$rider->rider_id)->where('status','active')->get()->first();
            // if (isset($assign_bike)) {
            //     $bike=bike::find($assign_bike->bike_id);
            //     if (isset($bike)) {
            //         $salik_amount=Trip_Detail::whereMonth('trip_date',$month) 
            //         ->where('plate',$bike->bike_number)
            //         ->sum('amount_aed');
            //     }
            // }
               $salik_amount=Company_Account::whereMonth('month',$month) 
                    ->where("source","Salik")
                    ->where('rider_id',$rider->rider_id)
                    ->sum('amount');
            $bike_rent_amount=Company_Account::where('source','Bike Rent')
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->sum('amount');
            $bonus_amount=Company_Account::where('source','400 Trips Acheivement Bonus')
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->sum('amount'); 
            $advance=Company_Account::where('source','advance')
            ->where('rider_id',$rider->rider_id)
            ->whereMonth('month',$month)
            ->sum('amount'); 

            $profit=($payout_sum+$dc_sum+$cod_sum+$penalty_sum+$sim_charges_EXTRA+$advance)-($bonus_amount+$salary+$ncw_sum+$tips_sum+$fuel_amount+$sim_charges+$salik_amount);
            $total_profit=$profit-$bike_rent_amount;
            return round($total_profit,2);
        })

        ->rawColumns(['bonus','profit','bike_rent','fuel','payout','penalty','aed_extra_trips','net_salary','rider_name','bike_number', 'salik', 'sim_charges', 'dc', 'cod', 'aed_hours','tips','aed_trips','ncw','number_of_trips','number_of_hours'])
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
        ->rawColumns(['status','bike_id','desc','amount','actions', 'rider_id'])
        ->make(true);
    }
}
