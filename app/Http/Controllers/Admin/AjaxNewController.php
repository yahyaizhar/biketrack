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

    public function getRiderAccounts($ranges)
    {
        $ranges = json_decode($ranges, true);
        $from = date($ranges['range']['start_date']);
        $to = date($ranges['range']['end_date']);
        $rider_statements = \App\Model\Accounts\Rider_Account::where("rider_id",$ranges['rider_id'])
        ->whereDate('created_at', '>=',$from)
        ->whereDate('created_at', '<=',$to)
        ->get();
        $running_balance = 0;
        return DataTables::of($rider_statements)
        ->addColumn('date', function($rider_statement){
            return Carbon::parse($rider_statement->created_at)->format('d/m/Y');
        })
        ->addColumn('desc', function($rider_statement){
            return $rider_statement->source;
        })
        ->addColumn('cr', function($rider_statement){
            if ($rider_statement->type=='cr' || $rider_statement->type=='cr_payable')
            {
                $class = $rider_statement->type=='cr_payable'?'kt-font-danger':'';
                return '<span class="'.$class.'">'.$rider_statement->amount.'</span>';
            }
            return 0;
        })
        ->addColumn('dr', function($rider_statement){
            if($rider_statement->type=='dr' || $rider_statement->type=='dr_payable'){
                $class = $rider_statement->type=='dr_payable'?'kt-font-danger':'';
                return '<span class="'.$class.'">('.$rider_statement->amount.')</span>';
            }
            return 0;
        })
        ->addColumn('balance', function($rider_statement) use (&$running_balance){
            if($rider_statement->type=='dr' || $rider_statement->type=='dr_payable'){
                $running_balance -= $rider_statement->amount;
            }
            else{
                $running_balance += $rider_statement->amount;
            }
            return $running_balance;
        })
        ->rawColumns(['desc','date','cr','dr','balance'])
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
 
}
