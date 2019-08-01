<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Client\Client;
use App\Model\Rider\Rider;
use Yajra\DataTables\DataTables;
use App\Model\Client\Client_Rider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Model\Rider\Rider_area;
use App\Model\Rider\Rider_detail;
use App\Model\Mobile\Mobile;
use App\Model\Rider\Rider_Online_Time;
use App\Model\Bikes\bike;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all_riders = Rider::all();
        $riders = $all_riders->count();
        $clients = Client::all()->count();
        $online_riders = Rider::where('status', 1)->get()->count();
        $clients_online = Client::where('status', 1)->get()->count();
        // $clients_online = Client_Rider::select('client_id')->distinct()->get()->count();

        $latest_riders = Rider::orderBy('created_at', 'DESC')->take(5)->get();
        $latest_clients = Client::orderBy('created_at', 'DESC')->take(5)->get();

        
        $current_date = Carbon::parse(Carbon::now());
        $after2_dt=Carbon::now()->addMonths(2);
        $ve__riders=Rider_detail::whereDate('visa_expiry', '<=', $after2_dt->toDateString())
        ->whereDate('visa_expiry', '>', $current_date->toDateString())
        ->orderBy('visa_expiry', 'DESC')
        ->get();
        
        $pe__riders=Rider_detail::whereDate('passport_expiry', '<=', $after2_dt->toDateString())
        ->whereDate('passport_expiry', '>', $current_date->toDateString())
        ->orderBy('passport_expiry', 'DESC')
        ->get();
        // return $pe__riders;
        
        $me__bikes=bike::whereDate('mulkiya_expiry', '<=', $after2_dt->toDateString())
        ->whereDate('mulkiya_expiry', '>', $current_date->toDateString())
        ->orderBy('mulkiya_expiry', 'DESC')
        ->get();
        $le__riders=Rider_detail::whereDate('licence_expiry', '<=', $after2_dt->toDateString())
        ->whereDate('licence_expiry', '>', $current_date->toDateString())
        ->orderBy('licence_expiry', 'DESC')
        ->get();

        $logged_rider=[];
        $notlogged_rider=[];

        foreach ($all_riders as $rider) {
            $online_record = Rider_Online_Time::where('rider_id', $rider->id)->whereNull('offline_time')->first();
            $rider_startTime = $rider->start_time;

            if($online_record && $rider_startTime){ // logged in
                $rider_startTime = Carbon::createFromFormat('H:i',$rider_startTime);
                $online_time=Carbon::parse($online_record->online_time)->diffForHumans();
                array_push($logged_rider,array('rider'=>$rider,'online_time'=>$online_time));
            }
            else if($rider_startTime){ //not log in yet
                $rider_startTime = Carbon::createFromFormat('H:i',$rider_startTime)->addMinutes(15);
                $current_time=Carbon::now();
                if($current_time->greaterThanOrEqualTo($rider_startTime)){ // late
                    array_push($notlogged_rider,$rider);
                }
            }
            

        }
        // return $notlogged_rider;
        return view('admin.home', compact('logged_rider','notlogged_rider','ve__riders','pe__riders','me__bikes','le__riders','riders', 'clients', 'online_riders', 'clients_online', 'latest_riders', 'latest_clients'));
    }

    public function livemap()
    {
        return view('admin.map.livemap');
    }
    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }
    public function create_mobile_GET()
    {
        return view('admin.rider.mobile.create');
    }
    public function create_mobile_POST(Request $r)
    {
        $this->validate($r, [
            'model' => 'required | string | max:255',
            'imei' => 'required | numeric',
            'purchase_price' => 'required | numeric',
            'sale_price' => 'required | numeric',
            'payment_type' => 'required',
            'amount_received' => 'required | numeric',
            'per_month_installment_amount' => 'required | numeric',
        ]);
        $mobile = new Mobile;
        $mobile->model=$r->model;
        $mobile->imei=$r->imei;
        $mobile->purchase_price=$r->purchase_price;
        $mobile->sale_price=$r->sale_price;
        $mobile->payment_type=$r->payment_type;
        $mobile->amount_received=$r->amount_received;
        $mobile->installment_starting_month=$r->installment_starting_month;
        $mobile->installment_ending_month=$r->installment_ending_month;
        $mobile->per_month_installment_amount=$r->per_month_installment_amount;
        $mobile->save();
        return redirect(route('mobile.show'))->with('message', 'Record Added Successfully.');
        
    }
    public function mobiles(){
        $mobiles=Mobile::all();
        return view('admin.rider.mobile.mobiles', compact('mobiles'));
    }
   
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
        ]);
        if($request->change_password)
        {
            $this->validate($request, [
                'password' => 'required | string | confirmed',
            ]);
            $user->password = Hash::make($request->password);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->update();
        return redirect()->back()->with('message', 'Record Updated Successfully.');
    }
    public function assign_area()
    {
        $riders = Rider::all();
        return view('admin.map.assignArea', ['riders'=>$riders]);
    }
    public function assign_area_POST(Request $request)
    {
        $area_bounds = $request->area_bounds;
        $filename = uniqid().uniqid().'-'.time().'.json';
        $filepath = Storage::put('public/uploads/riders/areas/'.$filename,json_encode($area_bounds));
        //  $rider_area=$filepath->Rider_area()->create($request->all());
        $rider_area= new Rider_area();
        $rider_area->name=$request->name;
        $rider_area->path= $filename;
        $rider_area->save();
        return $rider_area;
    }
    public function assign_area_to_rider($id){
        $rider=Rider::find($id);
        $rider_area=Rider_area::all();
        // return $rider_area;
        return view('admin.map.assign_area_to_rider',compact('rider','rider_area'));
    }
}
