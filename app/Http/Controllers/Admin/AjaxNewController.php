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
use Arr;


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
}
