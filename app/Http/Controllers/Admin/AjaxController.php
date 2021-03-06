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
use Illuminate\Support\Facades\Auth;
use App\Model\Accounts\Rider_salary;
use App\Model\Mobile\Mobile;
use carbon\carbon;
use App\Model\Rider\Rider_detail;
use App\Model\Rider\Rider_Location;
use App\New_comer;
use App\GuestNewComer;
use App\Model\Rider\Rider_Report;
use Illuminate\Support\Facades\Storage;
use App\Model\Sim\Sim;
use App\Model\Sim\Sim_Transaction;
use App\Model\Sim\Sim_History;
use App\Model\Mobile\Mobile_installment;
use App\Model\Rider\Rider_Performance_Zomato;
use App\Assign_bike;
use App\WebRoute;
use App\Model\Bank\Bank_account;
use App\Model\Rider\Trip_Detail;
use App\Model\Accounts\Company_Account;
use App\Model\Accounts\Rider_Account;
use App\Model\Admin\Admin;
use Arr;
use App\Model\Zomato\Riders_Payouts_By_Days;
use App\Model\Mobile\MobileHistory;



class AjaxController extends Controller
{
    //
    public function getClients()
    {
        $clients = Client::orderByDesc('created_at')->where("active_status","A")->get();
        // return $clients;
        return DataTables::of($clients)
        ->addColumn('status', function($clients){
            if($clients->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('new_id', function($clients){
            return 'KR-C-0'.$clients->id;
        })
        ->addColumn('checkbox', function($clients){
            // return '<input type="checkbox" name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'">';
            // return '<label class="kt-checkbox kt-checkbox--brand">
            //             <input type="checkbox name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'"">
            //             <span></span>
            //         </label>';
        })
        ->addColumn('new_name', function($clients){
            return '<a href="'.route('admin.clients.riders', $clients).'">'.$clients->name.'</a>';
        })
        ->addColumn('new_email', function($clients){
            return '<a href="'.route('admin.clients.riders', $clients).'">'.$clients->email.'</a>';
        })
        ->addColumn('new_phone', function($clients){
            return '<a href="'.route('admin.clients.riders', $clients).'">'.$clients->phone.'</a>';
        })
        ->addColumn('trn_no', function($clients){
            return $clients->trn_no;
        })
        ->addColumn('payout_method', function($clients){
            if($clients->setting!=null){
                $settings = json_decode($clients->setting, true);
                $pm = $settings['payout_method'];
                $to_return ='';
                switch ($pm) {
                    case 'trip_based': 
                        $to_return .='<p><strong>Payout Method:</strong> Based on Trips and Hours</p>';
                        $temp_var = isset($settings['tb__trip_amount'])?$settings['tb__trip_amount']:'Unspecified';
                        $to_return .='<p><strong>Per trip amount:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['tb__hour_amount'])?$settings['tb__hour_amount']:'Unspecified';
                        $to_return .='<p><strong>Per hour amount:</strong> '.$temp_var.'</p>';
                        break;
                    case 'fixed_based':
                        $to_return .='<p><strong>Payout Method:</strong> Based on Fixed Amount</p>';
                        $temp_var = isset($settings['fb__amount'])?$settings['fb__amount']:'Unspecified';
                        $to_return .='<p><strong>Amount:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['fb__perdayHours'])?$settings['fb__perdayHours']:'Unspecified';
                        $to_return .='<p><strong>Estimated perday hours:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['fb__working_days'])?$settings['fb__working_days']:'Unspecified';
                        $to_return .='<p><strong>Estimated Working Days:</strong> '.$temp_var.'</p>';
                        break;
                    case 'commission_based':
                        $to_return .='<p><strong>Payout Method:</strong> Based on Commission</p>';
                        break;
                    
                    default:
                        
                        break;
                }
                return $to_return;
            }
            return 'No Payout Method is set.';
        })
        ->addColumn('salary_method', function($clients){
            if($clients->salary_methods!=null){
                $settings = json_decode($clients->salary_methods, true);
                $pm = $settings['salary_method'];
                $to_return ='';
                switch ($pm) {
                    case 'trip_based':
                        $to_return .='<p><strong>Salary Method:</strong> Based on Trips and Hours</p>';
                        $temp_var = isset($settings['tb_sm__trip_amount'])?$settings['tb_sm__trip_amount']:'Unspecified';
                        $to_return .='<p><strong>Per trip amount:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['tb_sm__hour_amount'])?$settings['tb_sm__hour_amount']:'Unspecified';
                        $to_return .='<p><strong>Per hour amount:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['tb_sm__bonus_trips'])?$settings['tb_sm__bonus_trips']:'Unspecified';
                        $to_return .='<p><strong>Bonus trips:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['tb_sm__bonus_amount'])?$settings['tb_sm__bonus_amount']:'Unspecified';
                        $to_return .='<p><strong>Bonus amount:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['tb_sm__trips_bonus_amount'])?$settings['tb_sm__trips_bonus_amount']:'Unspecified';
                        $to_return .='<p><strong>Bonus trips amount:</strong> '.$temp_var.'</p>';
                        break;
                    case 'fixed_based':
                        $to_return .='<p><strong>Salary Method:</strong> Based on Fixed Amount</p>';
                        $temp_var = isset($settings['fb_sm__amount'])?$settings['fb_sm__amount']:'Unspecified';
                        $to_return .='<p><strong>Amount:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['fb_sm__exrta_hours'])?$settings['fb_sm__exrta_hours']:'Unspecified';
                        $to_return .='<p><strong>Extra Hours Rate:</strong> '.$temp_var.'</p>';
                        break;
                    case 'commission_based':
                        $to_return .='<p><strong>Salary Method:</strong> Based on Commission</p>';
                        $temp_var = isset($settings['cb_sm__amount'])?$settings['cb_sm__amount']:'Unspecified';
                        $to_return .='<p><strong>Commission:</strong> '.$temp_var;
                        if(isset($settings['cb_sm__type'])&&$settings['cb_sm__type']=='percentage'){
                            $to_return .='%</p>' ;
                        }
                        else {
                            $to_return .=' AED</p>' ;
                        }
                        break;
                    
                    default:
                        
                        break;
                }
                return $to_return;
            }
            return 'No Salary Method is set.';
        })
        ->addColumn('actions', function($clients){
            $status_text = $clients->status == 1 ? 'Inactive' : 'Active';
            $payout_method_HTML_suffix = '<i class="fa fa-plus"></i>Set Payout Method';
            $salary_method_HTML_suffix = '<i class="fa fa-plus"></i>Set Salary Method';
            if($clients->setting!=null){
                $payout_method_HTML_suffix = '<i class="fa fa-edit"></i>Update Payout Method';
            }
            if($clients->salary_methods!=null){
                $salary_method_HTML_suffix = '<i class="fa fa-edit"></i>Update Salary Method';
            }
            //<button class="dropdown-item" onclick="deleteClient('.$clients->id.');"><i class="fa fa-trash"></i> Delete</button>
            $client_settings = $clients->setting==""?null:$clients->setting;
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.clients.edit', $clients).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="updateStatus('.$clients->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <a class="dropdown-item" href="'.route('admin.clients.riders', $clients).'"><i class="fa fa-eye"></i> View Riders</a>
                    <a class="dropdown-item" href="'.route('admin.clients.assignRiders', $clients).'"><i class="fa fa-edit"></i> Assign Riders</a>
                    
                    <a class="dropdown-item" href="'.route('client.profit_sheet_view', $clients).'"><i class="fa fa-edit"></i>View Record Sheet</a>
                    <a class="dropdown-item" href="'.route('client.client_total_expense', $clients).'"><i class="fa fa-edit"></i>View Record Summary</a>
                    <a class="dropdown-item" href="'.route('admin.zomato_salary_sheet_export', $clients).'"><i class="fa fa-edit"></i>View Salary Sheet</a> 
                    <a class="dropdown-item" href="" onclick=\'show_payout_modal('.json_encode($client_settings).', '.$clients->id.');return false;\'>'.$payout_method_HTML_suffix.'</a> 
                    <a class="dropdown-item" href="" onclick=\'show_salary_modal('.json_encode($clients->salary_methods).', '.$clients->id.');return false;\'>'.$salary_method_HTML_suffix.'</a> 
                </div>
            </span>
        </span>';
        })
        ->rawColumns(['new_name', 'new_email', 'new_phone', 'trn_no', 'actions', 'status', 'payout_method', 'salary_method'])
        ->make(true);
    }
    public function getActiveClients()
    {
        $clients = Client::orderByDesc('created_at')->where("active_status","A")->where("status","1")->get();
        // return $clients;
        return DataTables::of($clients)
        ->addColumn('status', function($clients){
            if($clients->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('new_id', function($clients){
            return 'KR-C-0'.$clients->id;
        })
        ->addColumn('checkbox', function($clients){
            // return '<input type="checkbox" name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'">';
            // return '<label class="kt-checkbox kt-checkbox--brand">
            //             <input type="checkbox name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'"">
            //             <span></span>
            //         </label>';
        })
        ->addColumn('new_name', function($clients){
            return '<a href="'.route('admin.clients.riders', $clients).'">'.$clients->name.'</a>';
        })
        ->addColumn('new_email', function($clients){
            return '<a href="'.route('admin.clients.riders', $clients).'">'.$clients->email.'</a>';
        })
        ->addColumn('new_phone', function($clients){
            return '<a href="'.route('admin.clients.riders', $clients).'">'.$clients->phone.'</a>';
        })
        ->addColumn('payout_method', function($clients){
            if($clients->setting!=null){
                $settings = json_decode($clients->setting, true);
                $pm = $settings['payout_method'];
                $to_return ='';
                switch ($pm) {
                    case 'trip_based': 
                        $to_return .='<p><strong>Payout Method:</strong> Based on Trips and Hours</p>';
                        $temp_var = isset($settings['tb__trip_amount'])?$settings['tb__trip_amount']:'Unspecified';
                        $to_return .='<p><strong>Per trip amount:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['tb__hour_amount'])?$settings['tb__hour_amount']:'Unspecified';
                        $to_return .='<p><strong>Per hour amount:</strong> '.$temp_var.'</p>';
                        break;
                    case 'fixed_based':
                        $to_return .='<p><strong>Payout Method:</strong> Based on Fixed Amount</p>';
                        $temp_var = isset($settings['fb__amount'])?$settings['fb__amount']:'Unspecified';
                        $to_return .='<p><strong>Amount:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['fb__perdayHours'])?$settings['fb__perdayHours']:'Unspecified';
                        $to_return .='<p><strong>Estimated perday hours:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['fb__working_days'])?$settings['fb__working_days']:'Unspecified';
                        $to_return .='<p><strong>Estimated Working Days:</strong> '.$temp_var.'</p>';
                        break;
                    case 'commission_based':
                        $to_return .='<p><strong>Payout Method:</strong> Based on Commission</p>';
                        break;
                    
                    default:
                        
                        break;
                }
                return $to_return;
            }
            return 'No Payout Method is set.';
        })
        ->addColumn('salary_method', function($clients){
            if($clients->salary_methods!=null){
                $settings = json_decode($clients->salary_methods, true);
                $pm = $settings['salary_method'];
                $to_return ='';
                switch ($pm) {
                    case 'trip_based':
                        $to_return .='<p><strong>Salary Method:</strong> Based on Trips and Hours</p>';
                        $temp_var = isset($settings['tb_sm__trip_amount'])?$settings['tb_sm__trip_amount']:'Unspecified';
                        $to_return .='<p><strong>Per trip amount:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['tb_sm__hour_amount'])?$settings['tb_sm__hour_amount']:'Unspecified';
                        $to_return .='<p><strong>Per hour amount:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['tb_sm__bonus_trips'])?$settings['tb_sm__bonus_trips']:'Unspecified';
                        $to_return .='<p><strong>Bonus trips:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['tb_sm__bonus_amount'])?$settings['tb_sm__bonus_amount']:'Unspecified';
                        $to_return .='<p><strong>Bonus amount:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['tb_sm__trips_bonus_amount'])?$settings['tb_sm__trips_bonus_amount']:'Unspecified';
                        $to_return .='<p><strong>Bonus trips amount:</strong> '.$temp_var.'</p>';
                        break;
                    case 'fixed_based':
                        $to_return .='<p><strong>Salary Method:</strong> Based on Fixed Amount</p>';
                        $temp_var = isset($settings['fb_sm__amount'])?$settings['fb_sm__amount']:'Unspecified';
                        $to_return .='<p><strong>Amount:</strong> '.$temp_var.'</p>';
                        $temp_var = isset($settings['fb_sm__exrta_hours'])?$settings['fb_sm__exrta_hours']:'Unspecified';
                        $to_return .='<p><strong>Extra Hours Rate:</strong> '.$temp_var.'</p>';
                        break;
                    case 'commission_based':
                        $to_return .='<p><strong>Salary Method:</strong> Based on Commission</p>';
                        $temp_var = isset($settings['cb_sm__amount'])?$settings['cb_sm__amount']:'Unspecified';
                        $to_return .='<p><strong>Commission:</strong> '.$temp_var;
                        if(isset($settings['cb_sm__type'])&&$settings['cb_sm__type']=='percentage'){
                            $to_return .='%</p>' ;
                        }
                        else {
                            $to_return .=' AED</p>' ;
                        }
                        break;
                    
                    default:
                        
                        break;
                }
                return $to_return;
            }
            return 'No Salary Method is set.';
        })
        ->addColumn('actions', function($clients){
            $status_text = $clients->status == 1 ? 'Inactive' : 'Active';
            $payout_method_HTML_suffix = '<i class="fa fa-plus"></i>Set Payout Method';
            $salary_method_HTML_suffix = '<i class="fa fa-plus"></i>Set Salary Method';
            if($clients->setting!=null){
                $payout_method_HTML_suffix = '<i class="fa fa-edit"></i>Update Payout Method';
            }
            if($clients->salary_methods!=null){
                $salary_method_HTML_suffix = '<i class="fa fa-edit"></i>Update Salary Method';
            }
            //<button class="dropdown-item" onclick="deleteClient('.$clients->id.');"><i class="fa fa-trash"></i> Delete</button>
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.clients.edit', $clients).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="updateStatus('.$clients->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <a class="dropdown-item" href="'.route('admin.clients.riders', $clients).'"><i class="fa fa-eye"></i> View Riders</a>
                    <a class="dropdown-item" href="'.route('admin.clients.assignRiders', $clients).'"><i class="fa fa-edit"></i> Assign Riders</a>
                    
                    <a class="dropdown-item" href="'.route('client.profit_sheet_view', $clients).'"><i class="fa fa-edit"></i>View Record Sheet</a>
                    <a class="dropdown-item" href="'.route('client.client_total_expense', $clients).'"><i class="fa fa-edit"></i>View Record Summary</a>
                    <a class="dropdown-item" href="'.route('admin.zomato_salary_sheet_export', $clients).'"><i class="fa fa-edit"></i>View Salary Sheet</a> 
                    <a class="dropdown-item" href="" onclick=\'show_payout_modal('.json_encode($clients->setting).', '.$clients->id.');return false;\'>'.$payout_method_HTML_suffix.'</a> 
                    <a class="dropdown-item" href="" onclick=\'show_salary_modal('.json_encode($clients->salary_methods).', '.$clients->id.');return false;\'>'.$salary_method_HTML_suffix.'</a> 
                </div>
            </span>
        </span>';
        })
        ->rawColumns(['new_name', 'new_email', 'new_phone', 'actions', 'status', 'payout_method', 'salary_method'])
        ->make(true);
    }
    public function getSims()
    {
        $sims = Sim::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($sims)
        ->addColumn('status', function($sim){
            if($sim->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($sim){
            return $sim->id;
        })
        ->addColumn('checkbox', function($sim){
            // return '<input type="checkbox" name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'">';
            // return '<label class="kt-checkbox kt-checkbox--brand">
            //             <input type="checkbox name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'"">
            //             <span></span>
            //         </label>';
        })
        ->addColumn('sim_number', function($sim){
            return $sim->sim_number;
        })
        ->addColumn('sim_company', function($sim){
            return $sim->sim_company;
        })
        ->addColumn('assigned_to', function($sim){
            $sim_history = $sim->Sim_history()->where('status', 'active')->get()->first();
            if(isset($sim_history)){
                $rider = $sim_history->Rider;
                return '<a href="'.route('admin.rider.profile', $rider->id).'">'.$rider->name.'</a>';
            }
            return 'This SIM is not assigned to any rider';
        })
        ->addColumn('actions', function($sim){
            $status_text = $sim->status == 1 ? 'Inactive' : 'Active';
            //<button class="dropdown-item" onclick="deleteSim('.$sim->id.');"><i class="fa fa-trash"></i> Delete</button>
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('Sim.edit_sim_view', $sim).'"><i class="fa fa-edit"></i> View</a>
                    <button class="dropdown-item" onclick="updateStatus('.$sim->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    
                    <a class="dropdown-item" href="'.route('Sim.rider.history', $sim->id).'"><i class="fa fa-eye"></i> View Rider history</a>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns([ 'sim_company','assigned_to', 'sim_number', 'actions', 'status'])
        ->make(true);
    }
    public function getActiveSims()
    {
        $sims = Sim::orderByDesc('created_at')->where('active_status', 'A')->where("status","1")->get();
        // return $clients;
        return DataTables::of($sims)
        ->addColumn('status', function($sim){
            if($sim->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($sim){
            return $sim->id;
        })
        ->addColumn('checkbox', function($sim){
            // return '<input type="checkbox" name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'">';
            // return '<label class="kt-checkbox kt-checkbox--brand">
            //             <input type="checkbox name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'"">
            //             <span></span>
            //         </label>';
        })
        ->addColumn('sim_number', function($sim){
            return $sim->sim_number;
        })
        ->addColumn('sim_company', function($sim){
            return $sim->sim_company;
        })
        ->addColumn('assigned_to', function($sim){
            $sim_history = $sim->Sim_history()->where('status', 'active')->get()->first();
            if(isset($sim_history)){
                $rider = $sim_history->Rider;
                return '<a href="'.route('admin.rider.profile', $rider->id).'">'.$rider->name.'</a>';
            }
            return 'This SIM is not assigned to any rider';
        })
        ->addColumn('actions', function($sim){
            $status_text = $sim->status == 1 ? 'Inactive' : 'Active';
            //<button class="dropdown-item" onclick="deleteSim('.$sim->id.');"><i class="fa fa-trash"></i> Delete</button>
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('Sim.edit_sim_view', $sim).'"><i class="fa fa-edit"></i> View</a>
                    <button class="dropdown-item" onclick="updateStatus('.$sim->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    
                    <a class="dropdown-item" href="'.route('Sim.rider.history', $sim->id).'"><i class="fa fa-eye"></i> View Rider history</a>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns([ 'sim_company','assigned_to', 'sim_number', 'actions', 'status'])
        ->make(true);
    }

    public function getSimTransaction($month) 
    {
        $sims = [];
        $all_sims = Sim::with('Sim_history')->orderByDesc('created_at')->where('active_status', 'A')->get();

        foreach ($all_sims as $sim) {
            $sim_history = Arr::first($sim->Sim_history, function ($history, $key) {
                return $history->status=='active';
            });
            if(isset($sim_history)){
                array_push($sims, $sim);
            }
        }
        
        // return $clients;
        //array_push($sims,array('month'=>$month));
        return DataTables::of($sims)
        ->addColumn('status', function($sims) use ($month){
            $sim_tran = $sims->Sim_Transaction()->whereMonth('month_year', Carbon::parse($month)->format('m'))->get()->first();
            $status = 0;
            if(isset($sim_tran)){
                $status = 1;
            }
            if($status === 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('sim_id', function($sims){
            return $sims->id;
        })
        ->addColumn('rider_id', function($sims) use ($month){
            $sim_history = Sim_history::all();
            $sim_id=$sims->id;
            $history = Arr::first($sim_history, function ($item, $key) use ($sim_id, $month) {
                $created_at =Carbon::parse($item->created_at)->format('Y-m-d');
                $created_at =Carbon::parse($created_at);
    
                $updated_at =Carbon::parse($item->updated_at)->format('Y-m-d');
                $updated_at =Carbon::parse($updated_at);
                $req_date =Carbon::parse($month);
                if($item->status=="active"){ 
                    // mean its still active, we need to match only created at
                    return $item->sim_id == $sim_id && $req_date->greaterThanOrEqualTo($created_at);
                }
                
                return $item->sim_id == $sim_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
            });
           if(isset($history)){
               $rider=Rider::find($history->rider_id);
               if (isset($rider)) {
                return $rider->name ;
               }
               return 'No Rider Assigned' ;
           }
           
           return 'No Sim Assigned' ;
        })
        ->addColumn('id', function($sims) use ($month){
            $sim_tran = $sims->Sim_Transaction()->whereMonth('month_year', Carbon::parse($month)->format('m'))->get()->first();
            if(isset($sim_tran)){
                return $sim_tran->id;
            }
            return null;
        })
        ->addColumn('sim_number', function($sims){
            return $sims->sim_number;
        })
        ->addColumn('month', function($sims) use ($month){
            $sim_tran = $sims->Sim_Transaction()->whereMonth('month_year', Carbon::parse($month)->format('m'))->get()->first();
            if(isset($sim_tran)){
                return Carbon::parse($sim_tran->month_year)->format('F Y');
            }
            return Carbon::now()->format('F Y');
        })
        ->addColumn('usage_limit', function($sims){
            $sim_history = $sims->Sim_history()->where('status', 'active')->get()->first();
            if(isset($sim_history)){
                if (isset($sim_history->allowed_balance)==null) {
                    return 105;
                }
                return $sim_history->allowed_balance;
            }
            return 105;
        })
        ->addColumn('bill_amount', function($sims) use ($month){
            $sim_tran = $sims->Sim_Transaction()->whereMonth('month_year', Carbon::parse($month)->format('m'))->get()->first();
            $sim_history = $sims->Sim_history()->where('status', 'active')->get()->first();
            if(isset($sim_tran)){
                return $sim_tran->bill_amount;
            }
            if(isset($sim_history)){
                if (isset($sim_history->allowed_balance)==null) {
                    return 105;
                }
                return $sim_history->allowed_balance;
            }
            return 105;
        })
        ->addColumn('extra_usage_amount', function($sims) use ($month){
            $sim_tran = $sims->Sim_Transaction()->whereMonth('month_year', Carbon::parse($month)->format('m'))->get()->first();
            $sim_history = $sims->Sim_history()->where('status', 'active')->get()->first();
            $bill_amt=105;
            $usage_limit=105;
            if(isset($sim_history)){
                $bill_amt = $sim_history->allowed_balance;
                $usage_limit=$sim_history->allowed_balance;
            }
            if(isset($sim_tran)){
                $bill_amt = $sim_tran->bill_amount;
            }
            $extra = $bill_amt - $usage_limit ;
            if($extra<0){
                $extra=0;
            }
            
            return $extra;
        })
        ->addColumn('extra_usage_payment_status', function($sims) use ($month){
            $sim_tran = $sims->Sim_Transaction()->whereMonth('month_year', Carbon::parse($month)->format('m'))->get()->first();
            if(!isset($sim_tran)){
                return 'Pending';
            }
            return $sim_tran->extra_usage_payment_status;
        })
        ->addColumn('bill_status', function($sims) use ($month){
            // $sim_tran = $sims->Sim_Transaction()->whereMonth('month_year', Carbon::parse($month)->format('m'))->get()->first();
            // if(!isset($sim_tran)){
            //     return 'Pending';
            // }
            // return $sim_tran->bill_status;
           
            $sim_history = Sim_history::all();
            $sim_id=$sims->id;
            $history_found = Arr::first($sim_history, function ($item, $key) use ($sim_id, $month) {
                $created_at =Carbon::parse($item->created_at)->format('Y-m-d');
                $created_at =Carbon::parse($created_at);
    
                $updated_at =Carbon::parse($item->updated_at)->format('Y-m-d');
                $updated_at =Carbon::parse($updated_at);
                $req_date =Carbon::parse($month);
                if($item->status=="active"){ 
                    // mean its still active, we need to match only created at
                    return $item->sim_id == $sim_id && $req_date->greaterThanOrEqualTo($created_at);
                }
                
                return $item->sim_id == $sim_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
            });

            $startMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
            if (isset($history_found)) {
                $rider_id=$history_found->rider_id;
                $CA=Company_Account::where("source","Sim Transaction")
                ->where("month",$startMonth)
                ->where("payment_status","paid")
                ->where("rider_id",$rider_id)
                ->get()
                ->first();
                if (isset($CA)) {
                    return "Paid";
                }
            }
            return "Pending";
        })
        
        // ->addColumn('actions', function($simTransaction){
        //     $status_text = $simTransaction->status == 1 ? 'Inactive' : 'Active';
        //     return '<span class="dtr-data">
        //     <span class="dropdown">
        //         <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
        //         <i class="la la-ellipsis-h"></i>
        //         </a>
        //         <div class="dropdown-menu dropdown-menu-right">
        //             <a class="dropdown-item" href="'.route('SimTransaction.edit_sim', $simTransaction).'"><i class="fa fa-edit"></i> Edit</a>
        //             <button class="dropdown-item" onclick="updateStatus('.$simTransaction->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
        //             <button class="dropdown-item" onclick="deleteSimTransaction('.$simTransaction->id.');"><i class="fa fa-trash"></i> Delete</button>
        //         </div>
        //     </span>
        // </span>';
        // })
        ->rawColumns(['usage_limit','rider_id','sim_number','bill_amount', 'status'])
        ->make(true);
    }

    public function getBanks()
    {
        $bank = Bank_account::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($bank)
        ->addColumn('id', function($bank){
            return $bank->id;
        })
        ->addColumn('name', function($bank){
           return $bank->name;
        })
        ->addColumn('account_no', function($bike){
            return $bike->account_number;
        })          
        ->rawColumns(['id','name','account_no'])
        ->make(true);
    }
    public function getBikes()
    {
        $bike = bike::orderByDesc('created_at')->where('active_status', 'A')->get();
        // return $clients;
        return DataTables::of($bike)
        ->addColumn('status', function($bike){
            if($bike->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($bike){
            return $bike->id;
        })
        ->addColumn('owner_type', function($bike){
           return '<strong>'.$bike->owner.'</strong>';
        })
        ->addColumn('assigned_to', function($bike){
            // $bike_id = $bike->id;
            $assign_bike=\App\Assign_bike::where('bike_id', $bike->id)->where('status','active')->get()->first();
       
            if($assign_bike){
                
                $rider=Rider::find($assign_bike->rider_id);
            $rider_profile='<a href="'.route('admin.rider.profile', $rider->id).'">'.$rider->name.'</a>';
                return $rider_profile;
            }
            else{
                return "Bike is free to assign";
            }
       
      
        })
        ->addColumn('checkbox', function($bike){
            // return '<input type="checkbox" name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'">';
            // return '<label class="kt-checkbox kt-checkbox--brand">
            //             <input type="checkbox name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'"">
            //             <span></span>
            //         </label>';
        }) 
        ->addColumn('models', function($bike){
            return $bike->model;
        })
        ->addColumn('brand', function($bike){
            return $bike->brand;
        })
        ->addColumn('rent', function($bike){
            if ($bike->rider_id==null) {
                return $bike->rent_amount;
            } else {
                return $bike->bike_allowns;
            }
        })
        ->addColumn('bike_no', function($bike){
            return '<a href="'.route('bike.bike_assigned', $bike).'">'.$bike->bike_number.'</a>';
        })
        
        ->addColumn('availability', function($bike){
            $status_text = $bike->status == 1 ? 'Inactive' : 'Active';
            $assign_bike_to_kingriders='';
            if ($bike->is_given=='1') {
                $assign_bike_to_kingriders='<a class="dropdown-item" href="'.route('bike.give_bike_to_company', $bike->id).'"><i class="fa fa-eye"></i>Give Bike To Comapany</a>';
            }
            //<button class="dropdown-item" onclick="deleteBike('.$bike->id.');"><i class="fa fa-trash"></i> Delete</button>
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('Bike.bike_edit_view', $bike).'"><i class="fa fa-edit"></i>View</a>
                    <button class="dropdown-item" onclick="updateStatus('.$bike->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    
                    <a class="dropdown-item" href="'.route('bike.rider_history', $bike).'"><i class="fa fa-eye"></i> View Rider history</a>
                    <a class="dropdown-item" href="'.route('bike.bike_salik', $bike->id).'"><i class="fa fa-eye"></i> View Salik</a>
                    '.$assign_bike_to_kingriders.'
                    </div>
            </span>
        </span>';
        })
        // <a class="dropdown-item" href="'.route('bike.bike_assigned', $bike).'"><i class="fa fa-eye"></i> View Bikes</a>
        // <a class="dropdown-item" href="'.route('bike.bike_assignRiders', $bike).'"><i class="fa fa-edit"></i> Assign Bikes</a>
                    
        ->rawColumns(['models','rent','owner_type','brand','chassis_number', 'bike_no', 'detail', 'assigned_to','availability', 'status'])
        ->make(true);
    }
    public function getActiveBikes()
    {
        $bike = bike::orderByDesc('created_at')->where('active_status', 'A')->where("status","1")->get();
        // return $clients;
        return DataTables::of($bike)
        ->addColumn('status', function($bike){
            if($bike->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($bike){
            return $bike->id;
        })
        ->addColumn('owner', function($bike){
           return '<strong>'.$bike->owner.'</strong>';
        })
        ->addColumn('assigned_to', function($bike){
            // $bike_id = $bike->id;
            $assign_bike=\App\Assign_bike::where('bike_id', $bike->id)->where('status','active')->get()->first();
       
            if($assign_bike){
                
                $rider=Rider::find($assign_bike->rider_id);
            $rider_profile='<a href="'.route('admin.rider.profile', $rider->id).'">'.$rider->name.'</a>';
                return $rider_profile;
            }
            else{
                return "Bike is free to assign";
            }
       
      
        })
        ->addColumn('checkbox', function($bike){
            // return '<input type="checkbox" name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'">';
            // return '<label class="kt-checkbox kt-checkbox--brand">
            //             <input type="checkbox name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'"">
            //             <span></span>
            //         </label>';
        }) 
        ->addColumn('model', function($bike){
            return $bike->model;
        })
        ->addColumn('brand', function($bike){
            return $bike->brand;
        })
        ->addColumn('rent', function($bike){
            if ($bike->rider_id==null) {
                return $bike->rent_amount;
            } else {
                return $bike->bike_allowns;
            }
        })
        ->addColumn('Bike_number', function($bike){
            return '<a href="'.route('bike.bike_assigned', $bike).'">'.$bike->bike_number.'</a>';
        })
        
        ->addColumn('availability', function($bike){
            $status_text = $bike->status == 1 ? 'Inactive' : 'Active';
            $assign_bike_to_kingriders='';
            if ($bike->is_given=='1') {
                $assign_bike_to_kingriders='<a class="dropdown-item" href="'.route('bike.give_bike_to_company', $bike->id).'"><i class="fa fa-eye"></i>Give Bike To Comapany</a>';
            }
            //<button class="dropdown-item" onclick="deleteBike('.$bike->id.');"><i class="fa fa-trash"></i> Delete</button>
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('Bike.bike_edit_view', $bike).'"><i class="fa fa-edit"></i>View</a>
                    <button class="dropdown-item" onclick="updateStatus('.$bike->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    
                    <a class="dropdown-item" href="'.route('bike.rider_history', $bike).'"><i class="fa fa-eye"></i> View Rider history</a>
                    <a class="dropdown-item" href="'.route('bike.bike_salik', $bike->id).'"><i class="fa fa-eye"></i> View Salik</a>
                    '.$assign_bike_to_kingriders.'
                    </div>
            </span>
        </span>';
        })
        // <a class="dropdown-item" href="'.route('bike.bike_assigned', $bike).'"><i class="fa fa-eye"></i> View Bikes</a>
        // <a class="dropdown-item" href="'.route('bike.bike_assignRiders', $bike).'"><i class="fa fa-edit"></i> Assign Bikes</a>
                    
        ->rawColumns(['model','rent','owner','brand','chassis_number', 'Bike_number', 'detail', 'assigned_to','availability', 'status'])
        ->make(true);
    }
    public function getSalary_by_developer()
    {
        $rider_salary = Rider_salary::orderByDesc('created_at')->get();
        // return $clients;
        return DataTables::of($rider_salary)
        ->addColumn('status', function($rider_salary){
            if($rider_salary->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('month', function($rider_salary){
            return $rider_salary->month;
        })
        ->addColumn('checkbox', function($rider_salary){
            // return '<input type="checkbox" name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'">';
            // return '<label class="kt-checkbox kt-checkbox--brand">
            //             <input type="checkbox name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'"">
            //             <span></span>
            //         </label>';
        }) 
        ->addColumn('salary', function($rider_salary){
            return $rider_salary->total_salary;
        })
        ->addColumn('created_at', function($rider_salary){
            return $rider_salary->created_at;
        })
        ->addColumn('paid_by', function($rider_salary){
            return $rider_salary->paid_by;
        })
        ->addColumn('actions', function($rider_salary){
            $status_text = $rider_salary->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('Bike.edit_bike', $rider_salary).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="updateStatus('.$rider_salary->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteBike('.$rider_salary->id.');"><i class="fa fa-trash"></i> Delete</button>
                    <a class="dropdown-item" href="'.route('bike.rider_history', $rider_salary).'"><i class="fa fa-eye"></i> View Rider history</a>
                    </div>
            </span>
        </span>';
        })
      
        // <a class="dropdown-item" href="'.route('bike.bike_assigned', $bike).'"><i class="fa fa-eye"></i> View Bikes</a>
        // <a class="dropdown-item" href="'.route('bike.bike_assignRiders', $bike).'"><i class="fa fa-edit"></i> Assign Bikes</a>
                    
        ->rawColumns(['month', 'salary','assigned_to', 'status', 'created_at','paid_by','actions'])
        ->make(true);
    }

    public function getRiders()
    {   
        $riders = Rider::orderByDesc('created_at')->where("active_status","A")->get();
        return DataTables::of($riders)
        ->addColumn('new_id', function($riders){
            return "KR".$riders->id;
        })
        ->addColumn('new_name', function($riders){
            return '<a href="'.route('admin.rider.profile', $riders->id).'">'.$riders->name.'</a>';
        })
        ->addColumn('new_email', function($riders){
            return $riders->email;
        })
        ->addColumn('kingriders_id', function($riders){
            if(!isset($riders->kingriders_id))
            {
                $kr_HTML='<a href="" class="dropdown-item" data-toggle="modal" data-target="#kingrider_model" data-rider-id="'.$riders->id.'" ><i class="fa fa-user-plus"></i> Kingrider ID</a>';
                return $kr_HTML;
            }
            else{
                $kr_HTML='<a href="" class="dropdown-item" data-toggle="modal" current-target="'.$riders->kingriders_id.'" data-target="#kingrider_model" data-rider-id="'.$riders->id.'" >'.'<strong>'.$riders->kingriders_id.'</strong>'.'</a>';
                return $kr_HTML;
            }
        })
        ->addColumn('client_name', function($riders){
            
            $client_rider=$riders->clients()->get()->first();
            if($client_rider){
            return '<a href="'.route('Client.client_history', $riders->id).'">' .$client_rider->name.'</a>';
        }
        else{
            return "Rider has no client";
        }
            
        })
        
        ->addColumn('phone', function($riders){
            return '<a href="tel:'.$riders->phone.'">'.$riders->phone.'</a>';
        })
        ->addColumn('sim_number', function($rider){
            $sim_history = $rider->Sim_history()->where('status', 'active')->get()->first();
            if(isset($sim_history)){
                $sim = $sim_history->Sim;
                return '<a class="text-success" href="'.route('Sim.simHistory', $rider->id).'">'.$sim->sim_number.'</a>';
            }
            return '<a class="text-danger" href="'.route('SimHistory.addsim', $rider->id).'">Assign Sim</a>';
        })
     
        ->addColumn('missing_fields', function($riders){
            $data='';
            $rider_detail =$riders->Rider_detail;
            $assign_bike=$riders->Assign_bike()->where('status', 'active')->get()->first();
            $sim_history = $riders->Sim_history()->where('status', 'active')->get()->first();
            
            // 
            if ($assign_bike) {}
            else{
                $data.='*No bike assigned <br />';
            }
            if(!isset($riders->name)){
                $data.='*Name <br />';
            }
            if(!isset($rider_detail->emirate_id)){
                $data.='*Emirates ID <br />';
            }
            if(!isset($rider_detail->licence_expiry)){
                $data.='*Licence Expiry <br />';
            }
            if(!isset($rider_detail->visa_expiry)){
                $data.='*Visa Expiry <br />';
            }
            if(!isset($rider_detail->passport_expiry)){
                $data.='*Passport Expiry <br />';
            }
            if(!isset($rider_detail->date_of_joining)){
                $data.='*Date of joining <br />';
            }
            if(!isset($rider_detail->passport_image)){
                $data.='*Passport front image <br />';
            }
            // if(!isset($rider_detail->passport_image_back)){
            //     $data.='*Passport back image <br />';
            // }
            if(!isset($rider_detail->visa_image)){
                $data.='*Visa front image <br />';
            }
            // if(!isset($rider_detail->visa_image_back)){
            //     $data.='*Visa back image <br />';
            // }
            if(!isset($rider_detail->emirate_image)){
                $data.='*Emirate front image <br />';
            }
            if(!isset($rider_detail->emirate_image_back)){
                $data.='*Emirate back image <br />';
            }
            if(!isset($rider_detail->licence_image)){
                $data.='*Licence front image <br />';
            }
            if(!isset($rider_detail->licence_image_back)){
                $data.='*Licence back image <br />';
            }
            if(isset($assign_bike)){
                $bike = $assign_bike->Bike;
                if(!isset($bike->mulkiya_picture)){
                     $data.='*Mulkiya front image <br />';
                }
            } 
            if(isset($assign_bike)){
                $bike = $assign_bike->Bike;
                if(!isset($bike->mulkiya_picture_back)){
                     $data.='*Mulkiya back image <br />';
                }
            }
            if(isset($assign_bike)){
                $bike = $assign_bike->Bike;
                if(!isset($bike->mulkiya_expiry)){
                     $data.='*Mulkiya Expiry <br />';
                }
            }
            if(!isset($sim_history)){
                $data.='*No SIM assigned <br />';
            }
            return '<a style="color:red;" href="'.url('admin/riders/'.$riders->id.'/edit').'">'.$data.'</a>';
        })        
    ->addColumn('adress', function($riders){
            if($riders->address){
            return '<a href="'.route('admin.rider.profile', $riders->id).'">'.$riders->address.'</a>';
        }
        else{
            $rider_detail =$riders->Rider_detail()->get()->first();
           $emerate=$rider_detail->emirate_id.'';
           $phone   =$riders->phone;
           $_hasimage=asset(Storage::url($rider_detail->visa_image));
        //    <img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="'+data.visa_image+'" width:100px,height:100px>
           $_notimage=asset('dashboard/assets/media/users/default.jpg');
           if($rider_detail->visa_image){
            return $emerate.$phone ;
        }else{
            return $emerate.$phone ; 
        }
                       
        }
        })
        ->addColumn('status', function($riders){
            if($riders->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('actions', function($riders){
            return '<a class="dropdown-item" href="'.route('admin.rider.profile', $riders->id).'"><i class="fa fa-eye"></i></a>';
        })
        // <a class="dropdown-item" href="'.route('admin.rider.location', $riders->id).'"><i class="fa fa-map-marker-alt"></i> View Location</a>
        // <a class="dropdown-item" href="'.route('admin.rider.ridesReport', $riders->id).'"><i class="fa fa-eye"></i> View Rides Report</a>
        ->addColumn('date_of_joining', function($riders){
            $rider_detail =$riders->Rider_detail()->get()->first();
           return $rider_detail->date_of_joining;
        })
        ->addColumn('official_given_number', function($riders){
            $rider_detail =$riders->Rider_detail()->get()->first();
           return $rider_detail->official_given_number;
        })
        ->addColumn('passport_expiry', function($riders){
            $rider_detail =$riders->Rider_detail()->get()->first();
           return $rider_detail->passport_expiry;
        })
        ->addColumn('visa_expiry', function($riders){
            $rider_detail =$riders->Rider_detail()->get()->first();
           return $rider_detail->visa_expiry;
        })
        ->addColumn('licence_expiry', function($riders){
            $rider_detail =$riders->Rider_detail()->get()->first();
           return $rider_detail->licence_expiry;
        })
        ->addColumn('passport_number', function($riders){
            $rider_detail =$riders->Rider_detail()->get()->first();
           return $rider_detail->passport_number;
        })
        ->addColumn('mulkiya_expiry', function($riders){
            $assign_bike=$riders->Assign_bike()->where('status', 'active')->get()->first();
            if(isset($assign_bike)){
                $bike = $assign_bike->Bike;
                if(isset($bike->mulkiya_expiry)){
                    return $bike->mulkiya_expiry;
                }
            }
           return "No mulkiya expiry";
        })
        ->addColumn('official_sim_given_date', function($riders){
            $rider_detail =$riders->Rider_detail()->get()->first();
           return $rider_detail->official_sim_given_date;
        })
        ->addColumn('bike_number', function($riders){
           $a=$riders->Assign_bike()->where('status', 'active')->get()->first();
            // 
            if ($a) {
                $bike=bike::where("id",$a->bike_id)->get()->first();
                
                return '<a class="text-success" href="'.url('admin/riders/'.$riders->id.'/history') .'">'.$bike['bike_number'].'</a>';
            }
            else{
               return '<a class="text-danger" href="'.route('bike.bike_assignRiders', $riders->id).'">Assign Bike</a>';
            }
        
        })
        ->addColumn('emirate_id', function($riders){
            $rider_detail =$riders->Rider_detail()->get()->first();
           return $rider_detail->emirate_id;
        })
        ->addColumn('passport_collected', function($riders){
            $rider_detail =$riders->Rider_detail()->get()->first();
            $a=$rider_detail->passport_collected."<br>";
            $b="";
            if (isset($rider_detail->empoloyee_reference)) {
                $rider=Rider::find($rider_detail->empoloyee_reference);
                $b="Referred By: <a href='".route('admin.rider.profile', $rider_detail->empoloyee_reference)."'> ".$rider['name']."</a>";
            }
            if($rider_detail->passport_collected=="no"){
                if ($rider_detail->is_guarantee=="employee") {
                    return $a.$b;
                }
            }
            return $a;
           
        })
        
        // <a class="dropdown-item" href="'.route('Rider.salary', $riders).'"><i class="fa fa-money-bill-wave"></i> Salaries</a> 
        ->rawColumns(['new_name','kingriders_id','sim_number','passport_number','passport_collected','missing_fields','adress','client_name','emirate_id','mulkiya_expiry','bike_number','official_sim_given_date','licence_expiry','visa_expiry','passport_expiry','official_given_number', 'new_email','date_of_joining', 'phone', 'actions', 'status'])
        ->make(true);
    }
    public function getMobiles(){
        $mobile=Mobile::orderByDesc('created_at')->get();
        
        return DataTables::of($mobile)
        ->addColumn('id', function($mobile){
            return $mobile->id;
        })
        ->addColumn('rider_id', function($mobile){
            $mobile_history=MobileHistory::where("mobile_id",$mobile->id)->get()->first();
            if (isset($mobile_history)) {
                $rider_id=$mobile_history->rider_id;
                $rider=Rider::find($rider_id);
                return $rider->name;
            }
            return "No Rider is Assigned";
        })
        ->addColumn('invoice_id', function($mobile){
            return $mobile->purchased_invoice_id;
        })
        ->addColumn('model', function($mobile){
            return $mobile->brand.'-'.$mobile->model;
        })
        ->addColumn('imei_1', function($mobile){
            return  $mobile->imei_1;
        })
        ->addColumn('imei_2', function($mobile){
            return $mobile->imei_2;
        })
        ->addColumn('month', function($mobile){
            return carbon::parse($mobile->purchasing_date)->format('M d, Y');
        })
        ->addColumn('status', function($mobile){
            $mobile_history=MobileHistory::where("active_status","A")->where("mobile_id",$mobile->id)->get()->first();
            if (isset($mobile_history)) {
                if ($mobile_history->payment_type=="cash") {
                    $status_text='<div class="text-success">Invoice cleared</div>';
                }
                if ($mobile_history->payment_type=="installment") {
                    $mobile=Mobile::find($mobile_history->mobile_id);
                    if (isset($mobile)) {
                        $status=$mobile->payment_status;
                        if ($status=="pending") {
                            $status_text='<div class="text-danger">Invoice not cleared with pending balance</div>';
                        }
                        if ($status=="paid") {
                            $status_text='<div class="text-success">Invoice paid</div>';
                        }
                    }
                    
                }
            }
            else{
                $status_text='<div class="text-warning">Available</div>';
            }
            
            $html = '<a href="" class="statusText__container" onclick="show_installments(this);return false;">
            '.$status_text.'
            </a>';
            return $html;
        })
        ->addColumn('actions', function($mobile){
            $mobile_history=MobileHistory::where("mobile_id",$mobile->id)->get()->first();
            if (isset($mobile_history)) {
                if ($mobile_history->payment_type=="installment") {
                    $mobile=Mobile::find($mobile_history->mobile_id);
                    if (isset($mobile)) {
                        $status=$mobile->payment_status;
                        if ($status=="pending") {
                            $html='<a class="text-warning" href="" data-ajax2="'.route("MobileInstallment.create").'">Add Installment</a>';
                        }
                        if ($status=="paid") {
                            $html='<div class="text-success">All Installments are paid</div>';
                        }
                    }
                }
                if ($mobile_history->payment_type=="cash") {
                    $html='<div class="text-success">Sold</div>';
                }
            }
            else{
                $html='<div><a  class="text-danger" href="'.url("/admin/riders").'">Not assigned to rider</a></div>'; 
            }
            return $html;
        })
        ->addColumn('installments', function($mobile){
            $pur_price='<div class="mobile_sale-purchase"><span style="font-weight: bolder;">Purchase Price: </span>'.$mobile->purchase_price.' AED</div>';
            $vat_paid='<div class="mobile_sale-purchase"><span style="font-weight: bolder;">VAT Paid: </span>'.$mobile->vat_paid.' AED</div>';
            $sale_price='<div class="mobile_sale-purchase"><span style="font-weight: bolder;">Sale Price: </span>'.$mobile->sale_price.' AED</div>';  
            $mobile_history=MobileHistory::where("active_status","A")->where("mobile_id",$mobile->id)->get()->first();
            if (isset($mobile_history)) {
                if ($mobile_history->payment_type=="cash") {
                    $status_text='<div class="text-white" style="text-align: center;">Invoice is cleared. Payment is all paid through Cash</div>';
                    $bg='bg-success';
                }
                if ($mobile_history->payment_type=="installment") {
                    $payments_list=Mobile_installment::where("mobile_id",$mobile->id)->get();
                    if (isset($payments_list)) {
                        $classes='text-white';
                        $payments_lis='';
                        foreach ($payments_list as $list) {
                            $payments_lis.='<li>
                                Payment on '.Carbon::parse($list->month_year)->format('d/m/Y').'. Payment: <strong>AED '.round($list->per_month_installment_amount, 2).'</strong>
                            </li>';
                        }
                    }
                    $status_text='<ul class="invoice__details-payments '.$classes.'">'.$payments_lis.'</ul>';
                    $bg='bg-success';
                }
            }
            else{
                $status_text='<div class="text-white" style="text-align: center;">Mobile is available to assigned.</div>';
                $bg='bg-warning';
            }
            $html='<div class="invoice__details-wrapper">
            <div class="invoice__details-inner">'.$pur_price.''.$vat_paid.''.$sale_price.'</div>
            <div class="invoice__details-inner-lower '.$bg.'">'.$status_text.'</div>
            </div>';
        return $html;
        })
        ->rawColumns(['model','rider_id','installments','imei_1','imei_2','id','invoice_id','actions', 'status','month'])
        ->make(true);
    }

    public function getMobileInstallment(){
        $installment=Mobile_installment::orderByDesc('created_at')->where("active_status","A")->get();
        return DataTables::of($installment)
        ->addColumn('installment_month', function($installment){
            return $installment->installment_month;
        })
        ->addColumn('mobile', function($installment){
            $mobile=Mobile::find($installment->mobile_id);
            if (isset($mobile)) {
                return $mobile->model;
            }
           
        })
        ->addColumn('rider_name', function($installment){
            $mobile=Mobile::find($installment->mobile_id);
            $rider=Rider::find( $mobile->rider_id);
            if (isset($rider)) {
                return $rider->name;
            }
            return 'No rider is assigned';
        })
        ->addColumn('installment_amount', function($installment){
            return $installment->installment_amount;
        })
        // ->addColumn('status', function($installment){
        //     if($installment->status == 1)
        //     {
        //         return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
        //     }
        //     else
        //     {
        //         return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
        //     }
        // })
        ->addColumn('actions', function($installment){
            $status_text = $installment->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    
                    <a class="dropdown-item" href="'.route('MobileInstallment.edit', $installment).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="deletemobileInstallment('.$installment->id.')"><i class="fa fa-trash"></i> Delete</button>
                    
                    </div>
            </span>
        </span>';
        })
        // <button class="dropdown-item" onclick="updateStatus('.$installment->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    
        ->rawColumns(['mobile','rider_name','installment_month','installment_amount','status','actions',])
        ->make(true);
    }

    public function getRidesReport($id)
    {  $rider=Rider::find($id);
        $reports=$rider->Rider_Report()->where("active_status","A")->get();
        // $reports = $rider->reports;
        
        return DataTables::of($reports)
        ->addColumn('rider_name', function($reports){
            return $reports->rider->name;
        })
        ->editColumn('created_at', function($reports){
            return $reports->created_at->diffForHumans();
        })
        ->editColumn('start_time', function($reports){
            return '<span data-local-format="yyyy-mm-dd HH:MM:ss" data-utc-to-local="' .$reports->start_time.'"></span>';
        })
        ->editColumn('way_points', function($report){
            $start_time = Carbon::parse($report->start_time)->format('Y-m-d H:i:s');
            $end_time = Carbon::parse($report->end_time)->format('Y-m-d H:i:s'); 
            $rider = $report->Rider;
            $locations = $rider->locations()->where('created_at', '>=', $start_time)->where('created_at', '<', $end_time)->get()->toArray();
            return array('points' => $locations);
        })
        ->editColumn('online_hours', function($reports){
            $time = Carbon::now(); 
            $time =$time->diffForHumans($time->copy()->addSeconds($reports->online_hours), true);
            return $time;
            // return $reports->online_hours;
        })
        ->editColumn('no_of_trips', function($reports){
            return $reports->no_of_trips;
        })
        ->editColumn('no_of_hours', function($reports){ 
            return $reports->no_of_hours;
        })
        ->editColumn('mileage', function($reports){
            return $reports->mileage;
        })
        ->editColumn('start/end-location', function($reports){
          $start=$reports->started_location;
          $end=$reports->ended_location;
          $start=str_replace('"',"'", $start);
          $end=str_replace('"',"'", $end);
            return '<span>
            <a href="" style="width:55%;" data-toggle="modal" data_start="'.$start.'" data_end="'.$end.'" data-target="#myModal">Click to see</a>
</span>';
        })
        ->editColumn('end_time', function($reports){
            return '<span data-local-format="yyyy-mm-dd HH:MM:ss" data-utc-to-local="' .$reports->end_time.'"></span>';
        })
        ->editColumn('id', function($reports){
            return $reports->id;
        })
        ->addColumn('actions', function($reports){
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" onclick="deleteRecord('.$reports->id.')"><i class="fa fa-trash"></i> Delete</button>
                </div>
            </span>
        </span>';
        })
        ->rawColumns(['actions','id','start/end-location','mileage','start_time','end_time','no_of_hours','no_of_trips','online_hours','created_at','rider_name'])
        ->make(true);
    }

    // public function getMessages(Rider $rider)
    // {
    //     return 'yes';
    // }
    
    public function loadLocations() 
    {
        // $riders = Rider::where('status', 1)->where('online', '!=', 3)->get();
        $riders = Rider::where('status', 1)->get();
        if($riders)
        {
            return RiderLocationResourceCollection::collection($riders);
        }
    }
    public function loadClientLocations()
    {
        $clients = Client::where('status', 1)->get();
        if($clients)
        {
            return ClientLocationResourceCollection::collection($clients);
        }
    }
    public function loadSingleRiderLocation(Rider $rider)
    {
        $location = $rider->getLatestLocation($rider->id);
        if($location){
            return new RiderLocationResource($location);
        }
    }

    public function loadClientRidersLocations(Client $client)
    {
        $riders = $client->riders;
        if($riders)
        {
            return ClientRidersLocationsResourceCollection::collection($riders);
        }
    }

    public function getRiderToMonth($rider_id)
    {
        
        
        $riderToMonth = Rider::find($rider_id)->Rider_salary()->where("active_status","A")->orderByDesc('created_at')->get();
        //    foreach ($riderToMonth as $rider) {
        //     $month = Carbon::parse($rider->created_at)->format('M-Y');
        //     return $month;
        //    }

        
        return DataTables::of($riderToMonth)
        ->addColumn('status', function($riderToMonth){
            $ra=Rider_Account::where('type','cr_payable')
            ->where("rider_id",$riderToMonth->rider_id)
            ->whereMonth("month",Carbon::parse($riderToMonth->month)->format('m'))
            ->get()->toArray();
            $total_salaary=$riderToMonth->total_salary;
            $gross_salary=$riderToMonth->gross_salary;
            return '<a href=""  data-gross_salary='.$gross_salary.'   data-salary='.$total_salaary.' data-view="'.htmlspecialchars(json_encode($ra), ENT_QUOTES, 'UTF-8').'"  data-toggle="modal" data-target="#invoice_model" class="btn btn-bold btn-sm btn-font-sm btn-label-success">View</a>';
            if($riderToMonth->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        // ->addColumn('id', function($riderToMonth){
        //     return '1000'.$riderToMonth->id;
        // })
        ->addColumn('checkbox', function($riderToMonth){
            // return '<input type="checkbox" name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'">';
            // return '<label class="kt-checkbox kt-checkbox--brand">
            //             <input type="checkbox name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'"">
            //             <span></span>
            //         </label>';
        }) 
        
        ->addColumn('month', function($riderToMonth){
            
            return  Carbon::parse($riderToMonth->month)->format('d M, Y');
        })
        ->addColumn('balance', function($riderToMonth){
            
            return  $riderToMonth->gross_salary;
        })
        ->addColumn('salary', function($riderToMonth){
               $total_salary='<strong>Total Salary</strong>: '.$riderToMonth->total_salary.'<br>';
               $gross_salary='<strong>Gross Salary</strong>: '.$riderToMonth->gross_salary.'<br>';
               $recieved_salary='<strong>Received Salary</strong>: '.$riderToMonth->recieved_salary.'<br>';
               $remaining_salary='<strong>Remaining Salary</strong>: '.$riderToMonth->remaining_salary.'<br>';
            return $riderToMonth->total_salary;
        })
        ->addColumn('payment_date', function($riderToMonth){
            return $riderToMonth->updated_at;
        })
        ->addColumn('payment_status', function($monthToRider){
            return $monthToRider->payment_status;
        })
        ->addColumn('paid_by', function($riderToMonth){
            $admin=Admin::find($riderToMonth->paid_by);
            return $admin->name;
        })
        
        ->addColumn('actions', function($riderToMonth){
            $status_text = $riderToMonth->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('account.edit_developer_view', $riderToMonth).'"><i class="fa fa-edit"></i> view</a>
                    <button class="dropdown-item" onclick="updateStatus('.$riderToMonth->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteDeveloper('.$riderToMonth->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        // <a class="dropdown-item" href="'.route('bike.bike_assigned', $bike).'"><i class="fa fa-eye"></i> View Bikes</a>
        // <a class="dropdown-item" href="'.route('bike.bike_assignRiders', $bike).'"><i class="fa fa-edit"></i> Assign Bikes</a>
                    
        ->rawColumns(['created_at','balance','payment_status', 'salary','payment_date', 'paid_by',  'status','actions'])
        ->make(true);
    }
    public function getMonthToRider($month)
    {  
        $only_month = Carbon::parse($month)->format('m');
        $only_year =  Carbon::parse($month)->format('Y');
        $rider_salaries=Rider_salary::where("active_status","A")->whereMonth('month', $only_month)->whereYear('month',$only_year)->get();
        
        return DataTables::of($rider_salaries)
        ->addColumn('status', function($monthToRider){
            
            $only_month2 = Carbon::parse($monthToRider->month)->format('m');
            $only_year2 =  Carbon::parse($monthToRider->month)->format('Y');
             $ra=Rider_Account::where('type','cr_payable')
            ->where("rider_id",$monthToRider->rider_id)
            ->whereMonth("month",$only_month2)->whereYear("month",$only_year2)
            ->get()->toArray();
            $total_salaary=$monthToRider->total_salary;
            $gross_salary=$monthToRider->gross_salary;
            return '<a href=""  data-gross_salary='.$gross_salary.'   data-salary='.$total_salaary.' data-view="'.htmlspecialchars(json_encode($ra), ENT_QUOTES, 'UTF-8').'"  data-toggle="modal" data-target="#invoice_model" class="btn btn-bold btn-sm btn-font-sm btn-label-success">View</a>';
            
            if($monthToRider->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        // ->addColumn('id', function($riderToMonth){
        //     return '1000'.$riderToMonth->id;
        // })
        ->addColumn('checkbox', function($monthToRider){
            // return '<input type="checkbox" name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'">';
            // return '<label class="kt-checkbox kt-checkbox--brand">
            //             <input type="checkbox name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'"">
            //             <span></span>
            //         </label>';
        }) 
        ->addColumn('name', function($monthToRider){
            $c = Rider::find($monthToRider->rider_id);
            return $c->name;
        })
        ->addColumn('balance', function($monthToRider){
            return $monthToRider->gross_salary;
        })
        ->addColumn('salary', function($riderToMonth){
            $total_salary='<strong>Total Salary</strong>: '.$riderToMonth->total_salary.'<br>';
            $gross_salary='<strong>Gross Salary</strong>: '.$riderToMonth->gross_salary.'<br>';
            $recieved_salary='<strong>Received Salary</strong>: '.$riderToMonth->recieved_salary.'<br>';
            $remaining_salary='<strong>Remaining Salary</strong>: '.$riderToMonth->remaining_salary.'<br>';
         return $riderToMonth->total_salary;
     })
        ->addColumn('updated_at', function($monthToRider){
            return $monthToRider->updated_at;
        })
        ->addColumn('payment_status', function($monthToRider){
            return $monthToRider->payment_status;
        })
        ->addColumn('paid_by', function($monthToRider){
            $admin=Admin::find($monthToRider->paid_by);
            if(isset($admin)){
            return $admin->name;
        } 
        })
        
        ->addColumn('actions', function($monthToRider){
            $status_text = $monthToRider->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('account.edit_month_view', $monthToRider).'"><i class="fa fa-edit"></i> View</a>
                    <button class="dropdown-item" onclick="updateStatus('.$monthToRider->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteMonth('.$monthToRider->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        // <a class="dropdown-item" href="'.route('bike.bike_assigned', $bike).'"><i class="fa fa-eye"></i> View Bikes</a>
        // <a class="dropdown-item" href="'.route('bike.bike_assignRiders', $bike).'"><i class="fa fa-edit"></i> Assign Bikes</a>
                    
        ->rawColumns(['name','balance','payment_status','status','salary','created_at','updated_at','paid_by','actions'])
        ->make(true);
    }
    public function getRidersDetails()
    {
        $riders_detail = Rider_detail::orderByDesc('created_at')->get();
        // return $riders_detail;
        return DataTables::of($riders_detail)
        ->addColumn('name', function($riders_detail){
            $rider_name=Rider::find($riders_detail->rider_id);
            return $rider_name->name;
        })
        ->addColumn('date_of_joining', function($riders_detail){
            return $riders_detail->date_of_joining;
        })
        ->addColumn('official_given_number', function($riders_detail){
            return $riders_detail->official_given_number;
        })
        ->addColumn('passport_expiry', function($riders_detail){
            return $riders_detail->passport_expiry;
        })
        ->addColumn('visa_expiry', function($riders_detail){
            return $riders_detail->visa_expiry;
        })
        ->addColumn('licence_expiry', function($riders_detail){
            return $riders_detail->licence_expiry;
        })
        ->addColumn('mulkiya_expiry', function($riders_detail){
            return $riders_detail->mulkiya_expiry;
        })
        
        // ->addColumn('status', function($riders_detail){
        //     if($riders_detail->status == 1)
        //     {
        //         return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
        //     }
        //     else
        //     {
        //         return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
        //     }
        // })
        ->addColumn('actions', function($riders_detail){
            $status_text = $riders_detail->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.riders.edit', $riders_detail->rider_id).'"><i class="fa fa-edit"></i> Edit</a>
                     <button class="dropdown-item" onclick="deleteRider('.$riders_detail->rider_id.')"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['name','date_of_joining', 'official_given_number', 'actions','visa_expiry','licence_expiry','mulkiya_expiry', 'passport_expiry','status'])
        ->make(true);
    }
    public function getNewComer()
    {
        $newComer = New_comer::orderByDesc('created_at')->where("active_status","A")->get();
        // return $clients;
        return DataTables::of($newComer)
        ->addColumn('status', function($newComer){
            if($newComer->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('name', function($newComer){
            return $newComer->name;
        })
        ->addColumn('checkbox', function($newComer){
            // return '<input type="checkbox" name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'">';
            // return '<label class="kt-checkbox kt-checkbox--brand">
            //             <input type="checkbox name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'"">
            //             <span></span>
            //         </label>';
        }) 
        ->addColumn('phone_number', function($newComer){
            return $newComer->phone_number;
        })
        
        ->addColumn('nationality', function($newComer){
            return $newComer->nationality;
        })
        ->addColumn('experience', function($newComer){
            return $newComer->experiance;
        })
        ->addColumn('interview_status', function($newComer){
           $a=$newComer->interview_status=='rejected';
           if($newComer->interview_status=='rejected'){
            return 'Rejected Reason:&nbsp&nbsp'. $newComer->why_rejected;
           }
          else if($newComer->interview_status=='accepted'){
              $a='Interview Date:&nbsp&nbsp&nbsp'.$newComer->interview_date.'<br>';
              $b='<div class="row" style="margin-top: 15px;margin-left: 1px;">Interview By:&nbsp'.'<p style="font-weight:bold;">'.$newComer->interview_By.'</p></div>'; 
              $c='Joining Date:&nbsp&nbsp&nbsp&nbsp'.$newComer->joining_date;
            $ifaccepted=$a.$b.$c;
            return $ifaccepted ;
           }else{
                  return 'Pending'; 
           }
            
        })
        ->addColumn('actions', function($newComer){
            $status_text = $newComer->status == 1 ? 'Inactive' : 'Active';
            if( $newComer->priority == "top_priority" ){
                return '<span class="dtr-data">
                <span class="dropdown">
                    <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                    <i class="la la-ellipsis-h"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="'.route('NewComer.edit_view', $newComer).'"><i class="fa fa-edit"></i> View</a>
                        <button class="dropdown-item" onclick="deleteNewComer('.$newComer->id.');"><i class="fa fa-trash"></i> Delete</button>
                        </div>
                        <div style="left:32px;transform: translateY(-25px);position:absolute;">
                        <span style=" height: 20px;width: 20px;background-color: black;border-radius: 50%;display: inline-block;"></span>
                        </div>
                       </span>
            </span>';
                
                 }
                 else if($newComer->priority == "priority"){
                    return '<span class="dtr-data">
                    <span class="dropdown">
                        <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                        <i class="la la-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="'.route('NewComer.edit', $newComer).'"><i class="fa fa-edit"></i> Edit</a>
                            <button class="dropdown-item" onclick="deleteNewComer('.$newComer->id.');"><i class="fa fa-trash"></i> Delete</button>
                            </div>
                            <div style="left:32px;transform: translateY(-25px);position:absolute;">
                            <span style=" height: 20px;width: 20px;background-color:red;border-radius: 50%;display: inline-block;"></span>
                            </div>
                            </span>
                </span>';
                 }
                 else if($newComer->priority == "normal"){
                    return '<span class="dtr-data">
                    <span class="dropdown">
                        <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                        <i class="la la-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="'.route('NewComer.edit', $newComer).'"><i class="fa fa-edit"></i> Edit</a>
                            <button class="dropdown-item" onclick="deleteNewComer('.$newComer->id.');"><i class="fa fa-trash"></i> Delete</button>
                            </div>
                            <div style="left:32px;transform: translateY(-25px);position:absolute;">
                            <span style=" height: 20px;width: 20px;border:3px solid red;black;border-radius: 50%;display: inline-block;"></span>
                            </div>
                           </span>
                </span>';
                 }
                 
                 return '<span class="dtr-data">
                 <span class="dropdown">
                     <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                     <i class="la la-ellipsis-h"></i>
                     </a>
                     <div class="dropdown-menu dropdown-menu-right">
                         <a class="dropdown-item" href="'.route('NewComer.edit', $newComer).'"><i class="fa fa-edit"></i> Edit</a>
                         <button class="dropdown-item" onclick="deleteNewComer('.$newComer->id.');"><i class="fa fa-trash"></i> Delete</button>
                         </div>    
                       </span>';
           
        })
        ->addColumn('source_of_contact',function($newComer){
            return $newComer->source_of_contact;
        })
        ->addColumn('experience_input',function($newComer){
            return $newComer->experience_input;
        })
        ->addColumn('passport_status',function($newComer){
            return $newComer->passport_status;
        })
        ->addColumn('passport_reason',function($newComer){
            return $newComer->passport_reason;
        })
        ->addColumn('kingriders_interview',function($newComer){
            return $newComer->kingriders_interview;
        })
        ->addColumn('whatsapp_number',function($newComer){
            return $newComer->whatsapp_number;
        })
        ->addColumn('licence_issue_date',function($newComer){
            return $newComer->licence_issue_date;
        })
        ->addColumn('education',function($newComer){
            return $newComer->education;
        })
        ->addColumn('interview',function($newComer){
            return $newComer->interview;
        })
        // ->addColumn('interview_status',function($newComer){
        //     return $newComer->interview_status;
        // })
        ->addColumn('overall_remarks',function($newComer){
            return $newComer->overall_remarks;
        })
        // <a class="dropdown-item" href="'.route('bike.bike_assigned', $bike).'"><i class="fa fa-eye"></i> View Bikes</a>
        // <a class="dropdown-item" href="'.route('bike.bike_assignRiders', $bike).'"><i class="fa fa-edit"></i> Assign Bikes</a>
                    
        ->rawColumns(['name','passport_status','education','whatsapp_number','licence_issue_date','overall_remarks','interview','kingriders_interview','passport_reason', 'nationality','experience_input', 'phone_number','experience', 'source_of_contact','actions', 'status','interview_status',])
        ->make(true);
    }
    public function getApprovalComer($id)
    {
        if($id =='all'){
            $newComer = GuestNewComer::orderByDesc('created_at')->get();
        }
        elseif($id =='interview'){
            $newComer = GuestNewComer::orderByDesc('created_at')->where("interview_status","approve")->get();
        }
        else{
        $newComer = GuestNewComer::orderByDesc('created_at')->where("approval_status",$id)->get();
        }
        // return $clients;
        return DataTables::of($newComer)
        ->addColumn('full_name', function($newComer){
            return $newComer->full_name;
        })
        ->addColumn('newcommer_image', function($newComer){
            return asset(Storage::url($newComer->newcommer_image));
        })
        ->addColumn('nationality', function($newComer){
            return $newComer->nationality;
        })
        ->addColumn('phone_number', function($newComer){
            return $newComer->phone_number;
        })
        ->addColumn('national_id_card_number', function($newComer){
            return $newComer->national_id_card_number;
        })
        ->addColumn('whatsapp_number', function($newComer){
            return $newComer->whatsapp_number;
        })
        ->addColumn('education', function($newComer){
            return $newComer->education;
        })
        ->addColumn('license_check', function($newComer){
            return $newComer->license_check;
        })
        ->addColumn('license_number', function($newComer){
            return $newComer->license_number;
        })
        ->addColumn('licence_issue_date', function($newComer){
            return $newComer->licence_issue_date;
        })
        ->addColumn('license_image', function($newComer){
            return asset(Storage::url($newComer->license_image));
        })
        ->addColumn('experiance', function($newComer){
            return $newComer->experiance;
        })
        ->addColumn('passport_status', function($newComer){
            return $newComer->passport_status;
        })
        ->addColumn('passport_number', function($newComer){
            return $newComer->passport_number;
        })
        ->addColumn('current_residence', function($newComer){
            return $newComer->current_residence;
        })
        ->addColumn('current_residence_countries', function($newComer){
            return $newComer->current_residence_countries;
        })
        ->addColumn('source', function($newComer){
            return $newComer->source;
        })
        ->addColumn('passport_image', function($newComer){
            return asset(Storage::url($newComer->passport_image));
            return $newComer->passport_image;
        })
        ->addColumn('overall_remarks', function($newComer){
            return $newComer->overall_remarks;
        })
        ->addColumn('overall_remarks', function($newComer){
            return $newComer->overall_remarks;
        })
        ->addColumn('noc_status', function($newComer){
            return $newComer->noc_status;
        })
        ->addColumn('have_bike', function($newComer){
            return $newComer->have_bike;
        })
        ->addColumn('actions', function($newComer){
            $status_text = $newComer->status == 0 ? 'Inactive' : 'Active';
                 return '<span class="show_waiting_comer" onclick="show_waiting_comer('.$newComer->id.',this);">
                     <i class="flaticon-eye"></i>    
                       </span>';
           
        })

        ->rawColumns(['noc_status','have_bike','applying_for','newcommer_image','full_name','nationality','phone_number','national_id_card_number','whatsapp_number','education','license_check','license_number','licence_issue_date','license_image', 'experiance','passport_status', 'passport_number','passport_image', 'current_residence','current_residence_countries', 'status','source','overall_remarks','actions'])
        ->make(true);
    }
    public function getWebRoutes()
    {
        $webroutes = WebRoute::orderByDesc('created_at')->get();
        // return $clients;
        return DataTables::of($webroutes)
        ->addColumn('id', function($webroute){
            return $webroute->id;
        })
        ->addColumn('category', function($webroute){
            return $webroute->category;
        })
        ->addColumn('label', function($webroute){
            return $webroute->label;
        })
        ->addColumn('type', function($webroute){
            return $webroute->type;
        })
        ->addColumn('route_name', function($webroute){
            return $webroute->route_name;
        })
        ->addColumn('description', function($webroute){
            return $webroute->route_description;
        })
        ->addColumn('actions', function($webroute){
                 return '<a href="'.route('admin.edit_route', $webroute->id).'"><span class="show_waiting_comer">
                     <i class="flaticon-eye"></i>    
                       </span></a>';
           
        })

        ->rawColumns(['category','label','type','route_name','description','actions'])
        ->make(true);
    }
       public function getRiderPerformance()
       {
           $performance =Rider_Performance_Zomato::orderByDesc('created_at')->get();
           return DataTables::of($performance)
           ->addColumn('status', function($performance){
               if($performance->status == 1)
               {
                   return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
               }
               else
               {
                   return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
               }
           })
         ->addColumn('feid', function($performance){
               return $performance->feid;
           })
           ->addColumn('area', function($performance){
            return $performance->area;
        })
           ->addColumn('date', function($performance){
            return $performance->date;
        })
        ->addColumn('cod_orders', function($performance){
            return $performance->cod_orders;
        })
        ->addColumn('cod_amount', function($performance){
            return $performance->cod_amount;
        })
        ->addColumn('trips', function($performance){
            return $performance->trips;
        })
        ->addColumn('loged_in_during_shift_time', function($performance){
            return $performance->loged_in_during_shift_time;
        })
        ->addColumn('total_loged_in_hours', function($performance){
            return $performance->total_loged_in_hours;
        })
        ->addColumn('average_drop_time', function($performance){
            return $performance->average_drop_time;
        })
        ->addColumn('average_pickup_time', function($performance){
            return $performance->average_pickup_time;
        })
        ->addColumn('adt', function($performance){
            return $performance->adt;
        })
        ->addColumn('rider_name', function($performance){
        
        $client_rider=Client_Rider::where("client_rider_id",$performance->feid)->get()->first();
         $rider=Rider::find($client_rider['rider_id']);
        
        if(isset($rider)){
            return $rider['name'];
        }
        else{
            return 'No Client Rider Id is Assigned';
        }
        })
        
           ->addColumn('actions', function($performance){
               $status_text = $performance->status == 1 ? 'Inactive' : 'Active';
               return '<span class="dtr-data">
               <span class="dropdown">
                   <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                   <i class="la la-ellipsis-h"></i>
                   </a>
                   <div class="dropdown-menu dropdown-menu-right">
                       <a class="dropdown-item" href="'.route('Sim.edit_sim', $performance).'"><i class="fa fa-edit"></i> Edit</a>
                       <button class="dropdown-item" onclick="updateStatus('.$performance->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                       <button class="dropdown-item" onclick="deleteSim('.$performance->id.');"><i class="fa fa-trash"></i> Delete</button>
                   </div>
               </span>
           </span>';
           })
           ->rawColumns([ 'area','feid','adt','average_pickup_time','average_drop_time','loged_in_during_shift_time','total_loged_in_hours','date','cod_orders','cod_amount','trips', 'actions', 'status'])
           ->make(true);
       }

       public function getSalikTrip_Details()
       {
           $detail =Trip_Detail::where("active_status","A")->orderByDesc('created_at')->get();
           return DataTables::of($detail)
           ->addColumn('status', function($detail){
               if($detail->status == 1)
               {
                   return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
               }
               else
               {
                   return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
               }
           })
        ->addColumn('transaction_id', function($detail){
            return $detail->transaction_id;
        })
        ->addColumn('rider_id', function($detail){
        $plate=$detail->plate;
        $bike=bike::where('bike_number',$plate)->get()->first();
        if (isset($bike)) {
            $rider_id=Assign_bike::where('bike_id',$bike->id)->where('status','active')->get()->first();
            if (isset($rider_id)) {
                $rider=Rider::find($rider_id->rider_id);
                if(isset($rider)){
                    return $rider->name;
                }
            }
        }
        return "No Rider is Assigned";
        })
        ->addColumn('toll_gate', function($detail){
            return $detail->toll_gate;
        })
        ->addColumn('direction', function($detail){
            return $detail->direction;
        })
        ->addColumn('tag_number', function($detail){
            return $detail->tag_number;
        })
        ->addColumn('plate', function($detail){
            return $detail->plate;
        })
        ->addColumn('amount_aed', function($detail){
            return $detail->amount_aed;
        })
        ->addColumn('transaction_post_date', function($detail){
            return $detail->transaction_post_date;
        })
        ->addColumn('trip_date', function($detail){
            return $detail->trip_date;
        })
        ->addColumn('trip_time', function($detail){
            return $detail->trip_time;
        })
    
        
        
           ->addColumn('actions', function($detail){
               $status_text = $detail->status == 1 ? 'Inactive' : 'Active';
               return '<span class="dtr-data">
               <span class="dropdown">
                   <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                   <i class="la la-ellipsis-h"></i>
                   </a>
                   <div class="dropdown-menu dropdown-menu-right">
                       <a class="dropdown-item" href="'.route('Sim.edit_sim', $detail).'"><i class="fa fa-edit"></i> Edit</a>
                       <button class="dropdown-item" onclick="updateStatus('.$detail->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                       <button class="dropdown-item" onclick="deleteSim('.$detail->id.');"><i class="fa fa-trash"></i> Delete</button>
                   </div>
               </span>
           </span>';
           })
           ->rawColumns([ 'transaction_id','toll_gate','direction','tag_number','plate','amount_aed','trip_date','trip_time','transaction_post_date', 'actions', 'status'])
           ->make(true);
       }
       public function getActiveRiders()
       {   
           $riders = Rider::orderByDesc('created_at')->where("active_status","A")->where("status","1")->get();
           return DataTables::of($riders)
           ->addColumn('new_id', function($riders){
               return "KR".$riders->id;
           })
           ->addColumn('new_name', function($riders){
               return '<a href="'.route('admin.rider.profile', $riders->id).'">'.$riders->name.'</a>';
           })
           ->addColumn('kingriders_id', function($riders){
            if(!isset($riders->kingriders_id))
            {
                $kr_HTML='<a href="" class="dropdown-item" data-toggle="modal" data-target="#kingrider_model" data-rider-id="'.$riders->id.'" ><i class="fa fa-user-plus"></i> Kingrider ID</a>';
                return $kr_HTML;
            }
            else{
                $kr_HTML='<a href="" class="dropdown-item" data-toggle="modal" current-target="'.$riders->kingriders_id.'" data-target="#kingrider_model" data-rider-id="'.$riders->id.'" >'.'<strong>'.$riders->kingriders_id.'</strong>'.'</a>';
                return $kr_HTML;
            }
        })
           ->addColumn('new_email', function($riders){
               return $riders->email;
           })
           ->addColumn('client_name', function($riders){
               
               $client_rider=$riders->clients()->get()->first();
               if($client_rider){
               return '<a href="'.route('Client.client_history', $riders->id).'">' .$client_rider->name.'</a>';
           }
           else{
               return "Rider has no client";
           }
               
           })
           
           ->addColumn('phone', function($riders){
               return '<a href=tel:"'.$riders->phone.'">'.$riders->phone.'</a>';
           })
           ->addColumn('sim_number', function($rider){
               $sim_history = $rider->Sim_history()->where('status', 'active')->get()->first();
               if(isset($sim_history)){
                   $sim = $sim_history->Sim;
                   return '<a class="text-success" href="'.route('Sim.simHistory', $rider->id).'">'.$sim->sim_number.'</a>';
               }
               return '<a class="text-danger" href="'.route('SimHistory.addsim', $rider->id).'">Assign Sim</a>';
           })
           
           
           ->addColumn('missing_fields', function($riders){
               $data='';
               $rider_detail =$riders->Rider_detail;
               $assign_bike=$riders->Assign_bike()->where('status', 'active')->get()->first();
               $sim_history = $riders->Sim_history()->where('status', 'active')->get()->first();
               
               // 
               if ($assign_bike) {}
               else{
                   $data.='*No bike assigned <br />';
               }
               if(!isset($riders->name)){
                   $data.='*Name <br />';
               }
               if(!isset($rider_detail->emirate_id)){
                   $data.='*Emirates ID <br />';
               }
               if(!isset($rider_detail->licence_expiry)){
                   $data.='*Licence Expiry <br />';
               }
               if(!isset($rider_detail->visa_expiry)){
                   $data.='*Visa Expiry <br />';
               }
               if(!isset($rider_detail->passport_expiry)){
                   $data.='*Passport Expiry <br />';
               }
               if(!isset($rider_detail->date_of_joining)){
                   $data.='*Date of joining <br />';
               }
               if(!isset($rider_detail->passport_image)){
                   $data.='*Passport front image <br />';
               }
               // if(!isset($rider_detail->passport_image_back)){
               //     $data.='*Passport back image <br />';
               // }
               if(!isset($rider_detail->visa_image)){
                   $data.='*Visa front image <br />';
               }
               // if(!isset($rider_detail->visa_image_back)){
               //     $data.='*Visa back image <br />';
               // }
               if(!isset($rider_detail->emirate_image)){
                   $data.='*Emirate front image <br />';
               }
               if(!isset($rider_detail->emirate_image_back)){
                   $data.='*Emirate back image <br />';
               }
               if(!isset($rider_detail->licence_image)){
                   $data.='*Licence front image <br />';
               }
               if(!isset($rider_detail->licence_image_back)){
                   $data.='*Licence back image <br />';
               }
               if(isset($assign_bike)){
                   $bike = $assign_bike->Bike;
                   if(!isset($bike->mulkiya_picture)){
                        $data.='*Mulkiya front image <br />';
                   }
               } 
               if(isset($assign_bike)){
                   $bike = $assign_bike->Bike;
                   if(!isset($bike->mulkiya_picture_back)){
                        $data.='*Mulkiya back image <br />';
                   }
               }
               if(isset($assign_bike)){
                   $bike = $assign_bike->Bike;
                   if(!isset($bike->mulkiya_expiry)){
                        $data.='*Mulkiya Expiry <br />';
                   }
               }
               if(!isset($sim_history)){
                   $data.='*No SIM assigned <br />';
               }
               return '<a style="color:red;" href="'.url('admin/riders/'.$riders->id.'/edit').'">'.$data.'</a>';
           })        
       ->addColumn('adress', function($riders){
               if($riders->address){
               return '<a href="'.route('admin.rider.profile', $riders->id).'">'.$riders->address.'</a>';
           }
           else{
               $rider_detail =$riders->Rider_detail()->get()->first();
              $emerate=$rider_detail->emirate_id.'';
              $phone   =$riders->phone;
              $_hasimage=asset(Storage::url($rider_detail->visa_image));
           //    <img style="width:150px;height:150px;" class="profile-logo img img-thumbnail" src="'+data.visa_image+'" width:100px,height:100px>
              $_notimage=asset('dashboard/assets/media/users/default.jpg');
              if($rider_detail->visa_image){
               return $emerate.$phone ;
           }else{
               return $emerate.$phone ; 
           }
                          
           }
           })
           ->addColumn('status', function($riders){
               if($riders->status == 1)
               {
                   return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
               }
               else
               {
                   return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
               }
           })
           ->addColumn('actions', function($riders){
       
               return '<a class="dropdown-item" href="'.route('admin.rider.profile', $riders->id).'"><i class="fa fa-eye"></i></a>';
           })
           ->addColumn('date_of_joining', function($riders){
               $rider_detail =$riders->Rider_detail()->get()->first();
              return $rider_detail->date_of_joining;
           })
           ->addColumn('official_given_number', function($riders){
               $rider_detail =$riders->Rider_detail()->get()->first();
              return $rider_detail->official_given_number;
           })
           ->addColumn('passport_expiry', function($riders){
               $rider_detail =$riders->Rider_detail()->get()->first();
              return $rider_detail->passport_expiry;
           })
           ->addColumn('visa_expiry', function($riders){
               $rider_detail =$riders->Rider_detail()->get()->first();
              return $rider_detail->visa_expiry;
           })
           ->addColumn('licence_expiry', function($riders){
               $rider_detail =$riders->Rider_detail()->get()->first();
              return $rider_detail->licence_expiry;
           })
           ->addColumn('mulkiya_expiry', function($riders){
               $assign_bike=$riders->Assign_bike()->where('status', 'active')->get()->first();
               if(isset($assign_bike)){
                   $bike = $assign_bike->Bike;
                   if(isset($bike->mulkiya_expiry)){
                       return $bike->mulkiya_expiry;
                   }
               }
              return "No mulkiya expiry";
           })
           ->addColumn('official_sim_given_date', function($riders){
               $rider_detail =$riders->Rider_detail()->get()->first();
              return $rider_detail->official_sim_given_date;
           })
           ->addColumn('bike_number', function($riders){
              $a=$riders->Assign_bike()->where('status', 'active')->get()->first();
               // 
               if ($a) {
                   $bike=bike::where("id",$a->bike_id)->get()->first();
                   
                   return '<a class="text-success" href="'.url('admin/riders/'.$riders->id.'/history') .'">'.$bike['bike_number'].'</a>';
               }
               else{
                return '<a class="text-danger" href="'.route('bike.bike_assignRiders', $riders->id).'">Assign Bike</a>';
               }
           })
           ->addColumn('emirate_id', function($riders){
               $rider_detail =$riders->Rider_detail()->get()->first();
              return $rider_detail->emirate_id;
           })
           ->addColumn('passport_collected', function($riders){
            $rider_detail =$riders->Rider_detail()->get()->first();
            $a=$rider_detail->passport_collected."<br>";
            $b="";
            if (isset($rider_detail->empoloyee_reference)) {
                $rider=Rider::find($rider_detail->empoloyee_reference);
                $b="Referred By: <a href='".route('admin.rider.profile', $rider_detail->empoloyee_reference)."'> ".$rider['name']."</a>";
            }
            if($rider_detail->passport_collected=="no"){
                if ($rider_detail->is_guarantee=="employee") {
                    return $a.$b;
                }
            }
            return $a;
           
        })
           
           // <a class="dropdown-item" href="'.route('Rider.salary', $riders).'"><i class="fa fa-money-bill-wave"></i> Salaries</a> 
           ->rawColumns(['new_name','kingriders_id','sim_number','passport_collected','missing_fields','adress','client_name','emirate_id','mulkiya_expiry','bike_number','official_sim_given_date','licence_expiry','visa_expiry','passport_expiry','official_given_number', 'new_email','date_of_joining', 'phone', 'actions', 'status'])
           ->make(true);
       }
       public function getSalik_Bike($id)
       {
           $bike=bike::find($id);
           $detail =Trip_Detail::orderByDesc('created_at')->where("plate",$bike->bike_number)->get();
           return DataTables::of($detail)
           ->addColumn('status', function($detail){
               if($detail->status == 1)
               {
                   return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
               }
               else
               {
                   return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
               }
           })
         ->addColumn('transaction_id', function($detail){
               return $detail->transaction_id;
           })
           ->addColumn('toll_gate', function($detail){
            return $detail->toll_gate;
        })
        ->addColumn('direction', function($detail){
            return $detail->direction;
        })
        ->addColumn('tag_number', function($detail){
            return $detail->tag_number;
        })
        ->addColumn('plate', function($detail){
            return $detail->plate;
        })
        ->addColumn('amount_aed', function($detail){
            return $detail->amount_aed;
        })
        ->addColumn('transaction_post_date', function($detail){
            return $detail->transaction_post_date;
        })
        ->addColumn('trip_date', function($detail){
            return $detail->trip_date;
        })
        ->addColumn('trip_time', function($detail){
            return $detail->trip_time;
        })
    
        
        
           ->addColumn('actions', function($detail){
               $status_text = $detail->status == 1 ? 'Inactive' : 'Active';
               return '<span class="dtr-data">
               <span class="dropdown">
                   <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                   <i class="la la-ellipsis-h"></i>
                   </a>
                   <div class="dropdown-menu dropdown-menu-right">
                       <a class="dropdown-item" href="'.route('Sim.edit_sim', $detail).'"><i class="fa fa-edit"></i> Edit</a>
                       <button class="dropdown-item" onclick="updateStatus('.$detail->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                       <button class="dropdown-item" onclick="deleteSim('.$detail->id.');"><i class="fa fa-trash"></i> Delete</button>
                   </div>
               </span>
           </span>';
           })
           ->rawColumns([ 'transaction_id','toll_gate','direction','tag_number','plate','amount_aed','trip_date','trip_time','transaction_post_date', 'actions', 'status'])
           ->make(true);
       }
       public function getCompanyAccounts()
       {
           $C_A = Company_Account::orderByDesc('created_at')->where('active_status', 'A')->get();
           // return $clients;
           return DataTables::of($C_A)
           ->addColumn('status', function($C_A){
               if($C_A->status == 1)
               {
                   return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
               }
               else
               {
                   return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
               }
           })
           ->addColumn('id', function($C_A){
               return '1000'.$C_A->id;
           })
          
           ->addColumn('type', function($C_A){
               return $C_A->type=='dr'?'Debit':($C_A->type=='cr'?'Credit':'');
           })
           ->addColumn('amount', function($C_A){
               return $C_A->amount;
           })
           ->addColumn('source', function($C_A){
                $rider=isset($C_A->rider_id)?'Rider_id: <a href="'.route('admin.rider.profile', $C_A->rider_id).'">'.$C_A->rider_id.'</a><br/>':'';
                $client=isset($C_A->client_id)?'client_id: <a href="">'.$C_A->client_id.'</a><br/>':'';
                $bike_expense=isset($C_A->bike_expense_id)?'bike_expense_id: <a href="">'.$C_A->bike_expense_id.'</a><br/>':'';
                $fine=isset($C_A->find_id)?'Fine_id: <a href="">'.$C_A->find_id.'</a><br/>':'';
                $salik=isset($C_A->salik_id)?'Salik_id: <a href="">'.$C_A->salik_id.'</a><br/>':'';
                $salary=isset($C_A->salary_id)?'salary_id: <a href="">'.$C_A->salary_id.'</a><br/>':'';
                $sim_transaction=isset($C_A->sim_transaction_id)?'sim_transaction_id: <a href="'.route('SimTransaction.view_records').'">'.$C_A->sim_transaction_id.'</a><br/>':'';
                $income=isset($C_A->income_id)?'income_id: <a href="">'.$C_A->income_id.'</a><br/>':'';
                $investment=isset($C_A->investment_id)?'investment_id: <a href="">'.$C_A->investment_id.'</a><br/>':'';
                return $rider.$client.$bike_expense.$fine.$salik.$salary.$sim_transaction.$income.$income.$investment;
            
            })
        //    ->addColumn('action', function($C_A){
        //        $status_text = $C_A->status == 1 ? 'Inactive' : 'Active';
        //        return '<span class="dtr-data">
        //        <span class="dropdown">
        //            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
        //            <i class="la la-ellipsis-h"></i>
        //            </a>
        //            <div class="dropdown-menu dropdown-menu-right">
        //                <a class="dropdown-item" href="'.route('Bike.edit_bike', $C_A).'"><i class="fa fa-edit"></i> Edit</a>
        //                <button class="dropdown-item" onclick="updateStatus('.$C_A->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
        //                <button class="dropdown-item" onclick="deleteBike('.$C_A->id.');"><i class="fa fa-trash"></i> Delete</button>
        //                </div>
        //        </span>
        //    </span>';
        //    })
                 
           ->rawColumns(['type','amount','action', 'status','source'])
           ->make(true);
       }

       public function getRiderAccounts()
       {
           $R_A = Rider_Account::orderByDesc('created_at')->where('active_status', 'A')->get();
           // return $clients;
           return DataTables::of($R_A)
           ->addColumn('status', function($R_A){
               if($R_A->status == 1)
               {
                   return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
               }
               else
               {
                   return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
               }
           })
           ->addColumn('id', function($R_A){
               return '1000'.$R_A->id;
           })
          
           ->addColumn('type', function($R_A){
            return $R_A->type=='dr'?'Debit':($R_A->type=='cr'?'Credit':'');
           })
           ->addColumn('amount', function($R_A){
               return $R_A->amount;
           })
           ->addColumn('source', function($C_A){
                $rider=isset($C_A->rider_id)?'Rider_id: <a href="'.route('admin.rider.profile', $C_A->rider_id).'">'.$C_A->rider_id.'</a><br/>':'';
                $client=isset($C_A->client_id)?'client_id: <a href="">'.$C_A->client_id.'</a><br/>':'';
                $bike_expense=isset($C_A->bike_expense_id)?'bike_expense_id: <a href="">'.$C_A->bike_expense_id.'</a><br/>':'';
                $fine=isset($C_A->find_id)?'Fine_id: <a href="">'.$C_A->find_id.'</a><br/>':'';
                $salik=isset($C_A->salik_id)?'Salik_id: <a href="">'.$C_A->salik_id.'</a><br/>':'';
                $salary=isset($C_A->salary_id)?'salary_id: <a href="">'.$C_A->salary_id.'</a><br/>':'';
                $sim_transaction=isset($C_A->sim_transaction_id)?'sim_transaction_id: <a href="'.route('SimTransaction.view_records').'">'.$C_A->sim_transaction_id.'</a><br/>':'';
                $income=isset($C_A->income_id)?'income_id: <a href="">'.$C_A->income_id.'</a><br/>':'';
                $investment=isset($C_A->investment_id)?'investment_id: <a href="">'.$C_A->investment_id.'</a><br/>':'';
                return $rider.$client.$bike_expense.$fine.$salik.$salary.$sim_transaction.$income.$income.$investment;
            
            })
                 
           ->rawColumns(['type','amount','source', 'status'])
           ->make(true);
       }

       public function getRiderRangesADT($ranges)
       {
           $ranges = json_decode($ranges, true);
           //return $ranges['range1'];
           $data = [];

           $riders = Rider::orderByDesc('created_at')->where("active_status","A")->get();
           array_push($data, $riders);
           array_push($data, $ranges);
           return DataTables::of($riders) 
           
           ->addColumn('rider_id', function($rider){
            return $rider->name;
           })
           ->addColumn('called_over', function($rider) use ($ranges){
            $feid=Client_Rider::where("client_id","2")->where('rider_id',$rider->id)->get()->first();
            if (isset($feid)) {
                $start_date=$ranges['range1']['start_date'];
                $end_date=$ranges['range1']['end_date'];
                if($start_date=="" || $end_date == ""){
                    $start_date=$ranges['range2']['start_date'];
                    $end_date=$ranges['range2']['end_date'];    
                }
                else{
                    $end_date=$ranges['range2']['end_date'];
                }
                $from = date($start_date);
                $to = date($end_date);
                $zomato=Rider_Performance_Zomato::where("feid",$feid->client_rider_id)
                ->whereBetween('date',[$from,$to])
                ->get()
                ->first();
                if (isset($zomato)) {
                    return $zomato->called_over;
                }
            }
           })
           ->addColumn('status', function($rider) use ($ranges){
            $feid=Client_Rider::where("client_id","2")->where('rider_id',$rider->id)->get()->first();
            if (isset($feid)) {
                $start_date=$ranges['range1']['start_date'];
                $end_date=$ranges['range1']['end_date'];
                if($start_date=="" || $end_date == ""){
                    $start_date=$ranges['range2']['start_date'];
                    $end_date=$ranges['range2']['end_date'];    
                }
                else{
                    $end_date=$ranges['range2']['end_date'];
                }
                $from = date($start_date);
                $to = date($end_date);
                $zomato=Rider_Performance_Zomato::where("feid",$feid->client_rider_id)
                ->whereBetween('date',[$from,$to])
                ->get()
                ->first();
                if (isset($zomato)) {
                    return $zomato->status;
                }
            }
           })
           ->addColumn('comments', function($rider) use ($ranges){
            $feid=Client_Rider::where("client_id","2")->where('rider_id',$rider->id)->get()->first();
            if (isset($feid)) {
                $start_date=$ranges['range1']['start_date'];
                $end_date=$ranges['range1']['end_date'];
                $from = date($start_date);
                $to = date($end_date);
                if($start_date=="" || $end_date == ""){
                    $start_date=$ranges['range2']['start_date'];
                    $end_date=$ranges['range2']['end_date'];    
                }
                else{
                    $end_date=$ranges['range2']['end_date'];
                }
                $zomato=Rider_Performance_Zomato::where("feid",$feid->client_rider_id)
                ->whereBetween('date',[$from,$to])
                ->get()
                ->first();
                if (isset($zomato)) {
                    return $zomato->comments;
                }
            }
           })
           ->addColumn('area', function($rider) use ($ranges){
            $start_date=$ranges['range1']['start_date'];
            $end_date=$ranges['range1']['end_date'];

            if($start_date=="" || $end_date == ""){
                $start_date=$ranges['range2']['start_date'];
                $end_date=$ranges['range2']['end_date'];    
            }
            else{
                $end_date=$ranges['range2']['end_date'];
            }

            $from = date($start_date);
            $to = date($end_date);
            
            $zomato=Rider_performance_zomato::where('rider_id',$rider->id)
            ->whereBetween('date',[$from,$to])
            ->get()
            ->first();
            if(isset($zomato)){
            return $zomato->area;
            }
            return 'no area';
           })
           ->addColumn('feid', function($rider){
            //    $zomato=Rider_performance_zomato::where('rider_id',$rider->id)->get()->first();
            $feid=Client_Rider::where("client_id","2")->where('rider_id',$rider->id)->get()->first();
               if(isset($feid)){
            return $feid->client_rider_id;
            }
            return 'no feid';
           })
           ->addColumn('action', function($rider) use ($ranges){
               $data='';
            $feid=Client_Rider::where("client_id","2")->where('rider_id',$rider->id)->get()->first();
            if(isset($feid)){
             $id=$feid->client_rider_id;
             $start_date=$ranges['range1']['start_date'];
             $end_date=$ranges['range1']['end_date'];
             $from = date($start_date);
             $to = date($end_date);
             $zomato=Rider_Performance_Zomato::where("feid",$id)->get()->first();
             if (isset($zomato)) {
                $status=$zomato->status;
                $called_over=$zomato->called_over;
                $comments=$zomato->comments;
                if(!isset($status)){
                    $data.='<a class="dropdown-item"><span>status is missing</span></a>';
                }
                if(!isset($called_over)){
                    $data.='<a class="dropdown-item"><span>called_over is missing</span></a>';
                }
                if(!isset($comments)){
                    $data.='<a class="dropdown-item"><span>comments are missing</span></a>';
                }
               
             }
             return '<a class="dropdown-item" onclick="extraFields(\''.$id.'\')"><i class="fa fa-eye"></i></a>'.$data.'';
             
            }
           
           })
           ->addColumn('adt1', function($rider) use ($ranges){
            $client=$rider->clients()->get()->first();
            if(isset($client)){
                $client_rider=Client_Rider::where("rider_id",$rider->id)->where("client_id",$client->id)->get()->first();
                $client_rider_id=$client_rider->client_rider_id;
                $start_date=$ranges['range1']['start_date'];
                $end_date=$ranges['range1']['end_date'];
                $from = date($start_date);
                $to = date($end_date);
                $zomato_adt1=Rider_Performance_Zomato::whereBetween('date',[$from,$to])
                ->where("feid",$client_rider_id)
                ->avg("adt"); 
                return round($zomato_adt1,2);
            }
            return "0";
            })
           ->addColumn('adt2', function($rider) use ($ranges){
            $client=$rider->clients()->get()->first();
            if(isset($client)){
                $client_rider=Client_Rider::where("rider_id",$rider->id)->where("client_id",$client->id)->get()->first();
                $client_rider_id=$client_rider->client_rider_id;
                $start_date=$ranges['range2']['start_date'];
                $end_date=$ranges['range2']['end_date'];
                $from = date($start_date);
                $to = date($end_date);
                $zomato_adt2=Rider_Performance_Zomato::whereBetween('date',[$from,$to])
                ->where("feid",$client_rider_id)
                ->avg("adt"); 
                return round($zomato_adt2,2);
            }
                return "0";
           })
           ->addColumn('improvements', function($rider) use ($ranges){
            $client=$rider->clients()->get()->first();
            if(isset($client)){
                $client_rider=Client_Rider::where("rider_id",$rider->id)
                ->where("client_id",$client->id)
                ->get()
                ->first();
                $client_rider_id=$client_rider->client_rider_id;
                $start_date1=$ranges['range1']['start_date'];
                $end_date1=$ranges['range1']['end_date'];
                $from1 = date($start_date1);
                $to1 = date($end_date1);   
                $start_date2=$ranges['range2']['start_date'];
                $end_date2=$ranges['range2']['end_date'];
                $from2 = date($start_date2);
                $to2 = date($end_date2);
                $zomato_adt1=floatval(Rider_Performance_Zomato::whereBetween('date',[$from1,$to1])
                ->where("feid",$client_rider_id)
                ->avg("adt")); 
                $zomato_adt2=floatval(Rider_Performance_Zomato::whereBetween('date',[$from2,$to2])
                ->where("feid",$client_rider_id)
                ->avg("adt"));
                if( $zomato_adt1!=0 && $zomato_adt2!=0){
                        $imp = ($zomato_adt1/$zomato_adt2)*100;
                        $better=$imp-100;
                        return round($better,2).'%';
                }
                if( $zomato_adt2==0  && $zomato_adt1!=0){
                    return "0%";
                }
            }
                return '0%';
            })
           ->rawColumns(['action','comments','status','feid','area','rider_id','adt1','adt2','improvements','called_over'])
           ->make(true);
       }
       public function getRiderPayoutsByDays()
       {
           $days_payouts =Riders_Payouts_By_Days::orderByDesc('created_at')->get();
           return DataTables::of($days_payouts)
            ->addColumn('feid', function($days_payouts){
                return $days_payouts->feid;
            })
            ->addColumn('rider_name', function($days_payouts){
                $rider=Rider::find($days_payouts->rider_id);
                if (isset($rider)) {
                    return '<a href="'.route('admin.rider.profile', $rider->id).'">'.$rider->name.'</a>';
                }
                return '<a class="text-danger" href="'.route('admin.riders.index').'">No Rider is assigned</a>';
            })
            ->addColumn('date', function($days_payouts){
                return carbon::parse($days_payouts->date)->format('d M Y');
            })
            ->addColumn('login_hours', function($days_payouts){
                return round($days_payouts->login_hours,2);
            })
            ->addColumn('trips', function($days_payouts){
                return round($days_payouts->trips,2);
            })
            ->addColumn('payout_for_login_hours', function($days_payouts){
                return round($days_payouts->payout_for_login_hours,2);
            })
            ->addColumn('payout_for_trips', function($days_payouts){
                return round($days_payouts->payout_for_trips,2);
            })
            ->addColumn('grand_total', function($days_payouts){
                return round($days_payouts->grand_total,2);
            })
           ->rawColumns([ 'rider_name','login_hours','feid','grand_total','payout_for_trips','payout_for_login_hours','date','trips',])
           ->make(true);
       }

}
