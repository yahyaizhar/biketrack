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
use carbon\carbon;
use App\Model\Rider\Rider_detail;
use App\New_comer;
use App\Model\Rider\Rider_Report;
use Illuminate\Support\Facades\Storage;
use App\Model\Sim\Sim;
use App\Model\Sim\Sim_Transaction;


class AjaxController extends Controller
{
    //
    public function getClients()
    {
        $clients = Client::orderByDesc('created_at')->get();
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
            return '1000'.$clients->id;
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
        ->addColumn('actions', function($clients){
            $status_text = $clients->status == 1 ? 'Inactive' : 'Active';
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
                    <button class="dropdown-item" onclick="deleteClient('.$clients->id.');"><i class="fa fa-trash"></i> Delete</button>
                </div>
            </span>
        </span>';
        })
        ->rawColumns(['new_name', 'new_email', 'new_phone', 'actions', 'status'])
        ->make(true);
    }
    public function getSims()
    {
        $sim = Sim::orderByDesc('created_at')->get();
        // return $clients;
        return DataTables::of($sim)
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
            return '1000'.$sim->id;
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
        ->addColumn('actions', function($sim){
            $status_text = $sim->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('Sim.edit_sim', $sim).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="updateStatus('.$sim->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteSim('.$sim->id.');"><i class="fa fa-trash"></i> Delete</button>
                </div>
            </span>
        </span>';
        })
        ->rawColumns([ 'sim_company', 'sim_number', 'actions', 'status'])
        ->make(true);
    }

    public function getSimTransaction()
    {
        $simTransaction = Sim_Transaction::orderByDesc('created_at')->get();
        // return $clients;
        return DataTables::of($simTransaction)
        ->addColumn('status', function($simTransaction){
            if($simTransaction->status == 1)
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Active</span>';
            }
            else
            {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Inactive</span>';
            }
        })
        ->addColumn('id', function($simTransaction){
            return '1000'.$simTransaction->id;
        })
        ->addColumn('checkbox', function($simTransaction){
            // return '<input type="checkbox" name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'">';
            // return '<label class="kt-checkbox kt-checkbox--brand">
            //             <input type="checkbox name="client_checkbox[]" class="client_checkbox" value="'.$clients->id.'"">
            //             <span></span>
            //         </label>';
        })
        ->addColumn('month_year', function($simTransaction){
            return $simTransaction->month_year;
        })
        ->addColumn('bill_amount', function($simTransaction){
            return $simTransaction->bill_amount;
        })
        ->addColumn('extra_usage_amount', function($simTransaction){
            return $simTransaction->extra_usage_amount;
        })
        ->addColumn('extra_usage_payment_status', function($simTransaction){
            return $simTransaction->extra_usage_payment_status;
        })
        ->addColumn('bill_status', function($simTransaction){
            return $simTransaction->bill_status;
        })
        
        ->addColumn('actions', function($simTransaction){
            $status_text = $simTransaction->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('SimTransaction.edit_sim', $simTransaction).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="updateStatus('.$simTransaction->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteSimTransaction('.$simTransaction->id.');"><i class="fa fa-trash"></i> Delete</button>
                </div>
            </span>
        </span>';
        })
        ->rawColumns([ 'month_year', 'bill_status','extra_usage_payment_status','extra_usage_amount','bill_amount', 'actions', 'status'])
        ->make(true);
    }


    public function getBikes()
    {
        $bike = bike::orderByDesc('created_at')->get();
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
            return '1000'.$bike->id;
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
        ->addColumn('Bike_number', function($bike){
            return '<a href="'.route('bike.bike_assigned', $bike).'">'.$bike->bike_number.'</a>';
        })
        
        ->addColumn('availability', function($bike){
            $status_text = $bike->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('Bike.edit_bike', $bike).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="updateStatus('.$bike->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteBike('.$bike->id.');"><i class="fa fa-trash"></i> Delete</button>
                    <a class="dropdown-item" href="'.route('bike.rider_history', $bike).'"><i class="fa fa-eye"></i> View Rider history</a>
                    </div>
            </span>
        </span>';
        })
        // <a class="dropdown-item" href="'.route('bike.bike_assigned', $bike).'"><i class="fa fa-eye"></i> View Bikes</a>
        // <a class="dropdown-item" href="'.route('bike.bike_assignRiders', $bike).'"><i class="fa fa-edit"></i> Assign Bikes</a>
                    
        ->rawColumns(['model','brand', 'Bike_number', 'detail', 'assigned_to_rider','availability', 'status'])
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
            return $rider_salary->salary;
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
                    
        ->rawColumns(['month', 'salary', 'status', 'created_at','paid_by','actions'])
        ->make(true);
    }

    public function getRiders()
    {
        $riders = Rider::orderByDesc('created_at')->get();
        return DataTables::of($riders)
        ->addColumn('new_id', function($riders){
            return '1000'.$riders->id;
        })
        ->addColumn('new_name', function($riders){
            return '<a href="'.route('admin.rider.profile', $riders->id).'">'.$riders->name.'</a>';
        })
        ->addColumn('new_email', function($riders){
            return $riders->email;
        })
        ->addColumn('client_name', function($riders){
            
            $client_rider=$riders->clients()->get()->first();
            if($client_rider){
            return '<a href="'.route('admin.clients.riders', $client_rider).'">' .$client_rider->name.'</a>';
        }
        else{
            return "Rider has no client";
        }
            
        })
        
        ->addColumn('new_phone', function($riders){
            return '<a href="'.route('admin.rider.profile', $riders->id).'">'.$riders->phone.'</a>';
        })
        ->addColumn('missing_fields', function($riders){
            $data='';
            $rider_detail =$riders->Rider_detail;
            $a=$riders->Assign_bike()->where('status', 'active')->get()->first();
            // 
            if ($a) {}
            else{
                $data.='*No bike assigned yet <br />';
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
            if(!isset($rider_detail->mulkiya_expiry)){
                $data.='*Mulkiya Expiry <br />';
            }
            if(!isset($rider_detail->official_sim_given_date)){
                $data.='*Official sim given date <br />';
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
            $status_text = $riders->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('admin.rider.profile', $riders->id).'"><i class="fa fa-eye"></i> View</a>
                    <a class="dropdown-item" href="'.route('admin.rider.ridesReport', $riders->id).'"><i class="fa fa-eye"></i> View Rides Report</a>
                    <button class="dropdown-item" onclick="updateStatus('.$riders->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <a class="dropdown-item" href="'.route('admin.rider.location', $riders->id).'"><i class="fa fa-map-marker-alt"></i> View Location</a>
                    
                    <a class="dropdown-item" href="'.route('admin.riders.edit', $riders).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="deleteRider('.$riders->id.')"><i class="fa fa-trash"></i> Delete</button>
                    <a class="dropdown-item" href="'.route('Bike.assignedToRiders_History', $riders).'"><i class="fa fa-eye"></i>View Bikes History</a>
                    <a class="dropdown-item" href="'.route('bike.bike_assignRiders', $riders).'"><i class="fa fa-eye"></i> Assign Bike</a> 
                    <a class="dropdown-item" href="'.route('admin.area_assign_to_rider', $riders).'"><i class="fa fa-eye"></i> Assign Area</a>
                    <a class="dropdown-item" href="'.route('SimHistory.addsim', $riders).'"><i class="fa fa-eye"></i> Assign Sim</a>
                      
                    </div>
                    
                    </div>
            </span>
        </span>';
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
            $rider_detail =$riders->Rider_detail()->get()->first();
           return $rider_detail->mulkiya_expiry;
        })
        ->addColumn('official_sim_given_date', function($riders){
            $rider_detail =$riders->Rider_detail()->get()->first();
           return $rider_detail->official_sim_given_date;
        })
        ->addColumn('bike_number', function($riders){
           $a=$riders->Assign_bike()->where('status', 'active')->get()->first();
            // 
            if ($a) {
                $bike=bike::find($a->bike_id);
                return '<a href="'.url('admin/bike/'.$bike->id.'/profile'.'/'.$riders->id) .'">'.$bike->bike_number.'</a>';
            }
            else{
               return 'Bike is not assigned to Rider';
            }
        
        })
        ->addColumn('emirate_id', function($riders){
            $rider_detail =$riders->Rider_detail()->get()->first();
           return $rider_detail->emirate_id;
        })
        
        // <a class="dropdown-item" href="'.route('Rider.salary', $riders).'"><i class="fa fa-money-bill-wave"></i> Salaries</a> 
        ->rawColumns(['new_name','missing_fields','adress','client_name','emirate_id','mulkiya_expiry','bike_number','official_sim_given_date','licence_expiry','visa_expiry','passport_expiry','official_given_number', 'new_email','date_of_joining', 'new_phone', 'actions', 'status'])
        ->make(true);
    }

    public function getRidesReport($id)
    {  $rider=Rider::find($id);
        $reports=$rider->Rider_Report()->get();
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
        
        
        $riderToMonth = Rider::find($rider_id)->Rider_salary()->orderByDesc('created_at')->get();
        //    foreach ($riderToMonth as $rider) {
        //     $month = Carbon::parse($rider->created_at)->format('M-Y');
        //     return $month;
        //    }

        
        return DataTables::of($riderToMonth)
        ->addColumn('status', function($riderToMonth){
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
        
        ->addColumn('created_at', function($riderToMonth){
            
            return  Carbon::parse($riderToMonth->created_at)->format('M Y');
        })
        ->addColumn('salary', function($riderToMonth){

            return $riderToMonth->salary;
        })
        ->addColumn('payment_date', function($riderToMonth){
            return $riderToMonth->updated_at;
        })
        ->addColumn('paid_by', function($riderToMonth){
            return $riderToMonth->paid_by;
        })
        
        ->addColumn('actions', function($riderToMonth){
            $status_text = $riderToMonth->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('account.edit_developer', $riderToMonth).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="updateStatus('.$riderToMonth->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteDeveloper('.$riderToMonth->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        // <a class="dropdown-item" href="'.route('bike.bike_assigned', $bike).'"><i class="fa fa-eye"></i> View Bikes</a>
        // <a class="dropdown-item" href="'.route('bike.bike_assignRiders', $bike).'"><i class="fa fa-edit"></i> Assign Bikes</a>
                    
        ->rawColumns(['created_at', 'salary','payment_date', 'paid_by',  'status','actions'])
        ->make(true);
    }
    public function getMonthToRider($month_id)
    {  
        $rider_salaries=Rider_salary::all();
        $arr=[];
        foreach ($rider_salaries as $rider_salary ) {
            $b=Carbon::parse($rider_salary->created_at)->format('m');
            if(intval($b)===intval($month_id)){
                array_push($arr, $rider_salary);
            }
        }
       
        $monthToRider=$arr;
        return DataTables::of($monthToRider)
        ->addColumn('status', function($monthToRider){
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
        ->addColumn('salary', function($monthToRider){
            return $monthToRider->salary;
        })
        ->addColumn('updated_at', function($monthToRider){
            return $monthToRider->updated_at;
        })
        ->addColumn('paid_by', function($monthToRider){
            return $monthToRider->paid_by;
        })
        
        ->addColumn('actions', function($monthToRider){
            $status_text = $monthToRider->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('account.edit_month', $monthToRider).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="updateStatus('.$monthToRider->id.')"><i class="fa fa-toggle-on"></i> '.$status_text.'</button>
                    <button class="dropdown-item" onclick="deleteMonth('.$monthToRider->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        // <a class="dropdown-item" href="'.route('bike.bike_assigned', $bike).'"><i class="fa fa-eye"></i> View Bikes</a>
        // <a class="dropdown-item" href="'.route('bike.bike_assignRiders', $bike).'"><i class="fa fa-edit"></i> Assign Bikes</a>
                    
        ->rawColumns(['name','status','salary','created_at','updated_at','paid_by','actions'])
        ->make(true);
    }

public function testing(Request $request){
    $a=Rider_salary::all();
    foreach ($a as $value) {
        // return $value->created_at;
        $month = Carbon::parse($value->created_at)->format('M-Y');
        
    }
   
    return $month;
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
        $newComer = New_comer::orderByDesc('created_at')->get();
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
                        <a class="dropdown-item" href="'.route('NewComer.edit', $newComer).'"><i class="fa fa-edit"></i> Edit</a>
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
                    
        ->rawColumns(['name','passport_status','overall_remarks','interview','kingriders_interview','passport_reason', 'nationality','experience_input', 'phone_number','experience', 'source_of_contact','actions', 'status','interview_status',])
        ->make(true);
    }
   
       public function testing1($id){
           $riders=Rider::find($id);
        $c =Rider_detail::all();
       foreach ($c as $a) {
        return $a->date_of_joining;
       }
       
       }
   public function client_name($id){
      
   }
}
