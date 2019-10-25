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
      $assign_bike=Assign_bike::where("rider_id",$rider_id)->get();
        // end bike
        return response()->json([
            'assign_bike'=>$assign_bike,
        ]);
    }
}
