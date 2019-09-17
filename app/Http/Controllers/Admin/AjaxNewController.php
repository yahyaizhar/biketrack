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
use App\Model\Sim\Sim_Transaction;
use App\Model\Mobile\Mobile_installment;
use App\Model\Rider\Rider_Performance_Zomato;
use App\Assign_bike;
use App\Model\Rider\Trip_Detail;
use App\Model\Accounts\Company_Account;
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
                    <a class="dropdown-item" href="'.route('admin.id_charges_edit', $id_charge).'"><i class="fa fa-edit"></i> Edit</a>
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

    public function getRiderAccounts($ranges)
    {
        $ranges = json_decode($ranges, true);
        $from = date($ranges['range']['start_date']);
        $to = date($ranges['range']['end_date']);
        $rider_statements = \App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->get();
        
        $rider_debits_cr_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->where(function($q) {
            $q->where('type', "cr_payable")
              ->orWhere('type', 'cr');
        })
        ->sum('amount');
        
        $rider_debits_dr_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->where(function($q) {
            $q->where('type', "dr_payable")
              ->orWhere('type', 'dr');
        })
        ->sum('amount');

        $rider_debits_cr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->where(function($q) {
            $q->where('type', "cr_payable")
              ->orWhere('type', 'cr');
        })
        ->whereDate('month', '<',$from)
        ->sum('amount');
        
        $rider_debits_dr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->where(function($q) {
            $q->where('type', "dr_payable")
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
            return Carbon::parse($rider_statement->created_at)->format('d M, Y');
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
                    //not found, can pay
                    return '<div>Salary Recieved from Kingriders <button type="button" onclick="updateStatus('.$rider_statement->id.')" class="btn btn-sm btn-brand"><i class="fa fa-dollar-sign"></i> Pay</button></div>';
                }
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
            if($rider_statement->type=='skip') return '<strong >'.round($cash_paid,2).'</strong>';
            return  0;
        })
        ->with([
            'closing_balance' => round($closing_balance,2)
        ])
        ->rawColumns(['closing_balance','cash_paid','desc','date','cr','dr','balance'])
        ->make(true);
    }
    public function getCompanyAccounts($ranges)
    {
        $ranges = json_decode($ranges, true);
        $from = date($ranges['range']['start_date']);
        $to = date($ranges['range']['end_date']);
        $company_statements = \App\Model\Accounts\Company_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->get();
        // ->whereDate('created_at', '>=',$from)
        // ->whereDate('created_at', '<=',$to)

        

        
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
        $profit = $c_debits_rn_pl_total;

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
        $running_balance =$closing_balance_prev;

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
            return Carbon::parse($company_statements->month)->format('F Y');
        })
        ->addColumn('desc', function($company_statements){
            if($company_statements->type=='skip') return '<strong >'.$company_statements->source.'</strong>';
            return $company_statements->source;
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
            if($company_statements->type=='pl') return 0;
            if($company_statements->type=='skip') return '<strong id="'.$_id.'"> '.round($running_balance,2).'</strong>';
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
                    <a class="dropdown-item" href="'.route('admin.maintenance_edit', $maintenance).'"><i class="fa fa-edit"></i> Edit</a>
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
                    <a class="dropdown-item" href="'.route('admin.workshop_edit', $workshop).'"><i class="fa fa-edit"></i> Edit</a>
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
                    <a class="dropdown-item" href="'.route('admin.edirham_edit', $edirham).'"><i class="fa fa-edit"></i> Edit</a>
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
        ->addColumn('status', function($expense){
            if($expense->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($expense){
            return '1000'.$expense->id;
        })
        ->addColumn('bike_id', function($expense){
            $bike = bike::find($expense->bike_id);
            return $bike->bike_number;
        })
        ->addColumn('actions', function($expense){
            $status_text = $expense->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.edit_fuel_expense', $expense).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="updateStatus('.$expense->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteRow('.$expense->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['status','bike_id','type','amount','actions', 'status'])
        ->make(true);
    }

    public function getCompanyExpense()
    {
        $expense = Company_Expense::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($expense)
        ->addColumn('status', function($expense){
            if($expense->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($expense){
            return '1000'.$expense->id;
        })
        ->addColumn('amount', function($expense){
            return $expense->amount;
        })
        ->addColumn('description', function($expense){
            return $expense->description;
        })
       
        ->addColumn('actions', function($expense){
            $status_text = $expense->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.CE_edit', $expense).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="updateStatus('.$expense->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteRow('.$expense->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['status','description','type','amount','actions', 'status'])
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
            return $rider->name ;
        })
       
        ->addColumn('actions', function($wps){
            $status_text = $wps->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.wps_edit', $wps).'"><i class="fa fa-edit"></i> Edit</a>
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
            return $rider->name ;
        })
       
        ->addColumn('actions', function($ar){
            $status_text = $ar->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.AR_edit', $ar).'"><i class="fa fa-edit"></i> Edit</a>
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
                    <a class="dropdown-item" href="'.route('admin.client_income_edit', $client_income).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="updateStatus('.$client_income->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteRow('.$client_income->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['status','month','client_id','amount','actions', 'status'])
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
            $mob_tran =Mobile_Transaction::find($all_mobiles->id)->whereMonth('month', Carbon::parse($month)->format('m'))->get()->first();
            if(isset($mob_tran)){
                return Carbon::parse($mob_tran->month)->format('F Y');
            }
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
        
        ->rawColumns(['model','month','sale_price','amount_received','bill_status','remaining_amount','per_month_installment_amount', 'status'])
        ->make(true);
    }
    public function getCompany_overall_REPORT($month)
    {
        $CO=Company_Account::whereMonth('month',$month)->get();
        $overall_balnce_cr_monthly=Company_Account::where(function($q) {
            $q->where('type','cr')
            ->orWhere('type','dr_receivable')
            ->orWhere('type','pl');
        })
        ->whereMonth('month',$month)
        ->sum("amount");
        
        $overall_balnce_dr_monthly=Company_Account::whereMonth('month',$month)
        ->where("type","dr")
        ->sum("amount");

        $overall_balnce_monthly=$overall_balnce_cr_monthly-$overall_balnce_dr_monthly;

        $total_profit=Company_Account::where('type','pl')
        ->sum("amount");

        $overall_balnce_cr=Company_Account::where('type','cr')
        ->orWhere('type','dr_receivable')
        ->sum("amount");

        $overall_balnce_dr=Company_Account::where('type','dr')
        ->sum("amount");
        
        $overall_balnce=$overall_balnce_cr-$overall_balnce_dr;
       
        return DataTables::of($CO)
        
        ->addColumn('rider_id', function($CO){
                $rider=Rider::find($CO->rider_id);
                if (isset($rider)) {
                    return $rider->name;
                } else {
                    return 'No Rider';
                }
        }) 
        ->addColumn('description', function($CO){
                return $CO->source;

        }) 
        ->addColumn('cr', function($CO){
                $type=$CO->type;
                if ($type=='cr' || $type=='dr_receivable' || $type=='pl') {
                   return $CO->amount;
               }
                return 0 ;
        }) 
        ->addColumn('dr', function($CO){
                $type=$CO->type;
                if ($type=='dr') {
                   return $CO->amount;
               }
                return 0 ;
      
        }) 
       
        ->with([
            'overall_balnce_monthly' => round($overall_balnce_monthly,2),
            'total_profit' => round($total_profit,2),
            'overall_balnce' => round($overall_balnce,2),
        ])
               
        ->rawColumns(['profit','cr','description','amount','dr','rider_id'])
        ->make(true);
    }
}
