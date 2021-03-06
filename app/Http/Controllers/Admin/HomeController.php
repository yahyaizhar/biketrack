<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Client\Client;
use App\Model\Rider\Rider;
use Yajra\DataTables\DataTables;
use App\Model\Client\Client_Rider;
use App\Model\Client\Client_History;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Model\Rider\Rider_area;
use Illuminate\Support\Facades\Hash;
use App\Model\Rider\Rider_detail;
use App\Model\Mobile\Mobile;
use App\Model\Rider\Rider_Online_Time;
use App\Model\Bikes\bike;
use Carbon\Carbon;
use App\WebRoute;
use App\Model\Accounts\Company_Account;
use App\Model\Accounts\Rider_Account;

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
        $all_riders = Rider::where("active_status","A")->get();
        $riders = $all_riders->count();
        $clients = Client::where("active_status","A")->get()->count();
        $online_riders = Rider::where('status', 1)->where("active_status","A")->get()->count();
        $clients_online = Client::where('status', 1)->where("active_status","A")->get()->count();
        // $clients_online = Client_Rider::select('client_id')->distinct()->get()->count();

        $latest_riders = Rider::orderBy('created_at', 'DESC')->take(5)->get();
        $latest_clients = Client::orderBy('created_at', 'DESC')->take(5)->get();

        
        $current_date = Carbon::parse(Carbon::now());
        $after2_dt=Carbon::now()->addMonths(2);
        $ve__riders=Rider_detail::where("active_status","A")->whereDate('visa_expiry', '<=', $after2_dt->toDateString())
        ->whereDate('visa_expiry', '>', $current_date->toDateString())
        ->orderBy('visa_expiry', 'DESC')
        ->get();
        
        $pe__riders=Rider_detail::where("active_status","A")->whereDate('passport_expiry', '<=', $after2_dt->toDateString())
        ->whereDate('passport_expiry', '>', $current_date->toDateString())
        ->orderBy('passport_expiry', 'DESC')
        ->get();
        // return $pe__riders;
        
        $me__bikes=bike::where("active_status","A")->whereDate('mulkiya_expiry', '<=', $after2_dt->toDateString())
        ->whereDate('mulkiya_expiry', '>', $current_date->toDateString())
        ->orderBy('mulkiya_expiry', 'DESC')
        ->get();
        $le__riders=Rider_detail::where("active_status","A")->whereDate('licence_expiry', '<=', $after2_dt->toDateString())
        ->whereDate('licence_expiry', '>', $current_date->toDateString())
        ->orderBy('licence_expiry', 'DESC')
        ->get();

        $logged_rider=[];
        $notlogged_rider=[];

        foreach ($all_riders as $rider) {
            $online_record_all = Rider_Online_Time::where('rider_id', $rider->id)->whereDate('online_time', Carbon::now()->toDateString());
            $online_record_null = Rider_Online_Time::where('rider_id', $rider->id)->whereDate('online_time', Carbon::now()->toDateString())->whereNull('offline_time')->get()->first();
            $online_record = Rider_Online_Time::where('rider_id', $rider->id)->whereDate('online_time', Carbon::now()->toDateString())->get()->first();
            $rider_startTime = $rider->start_time;

            if($online_record_null && $rider_startTime){ // logged in
                $rider_startTime = Carbon::createFromFormat('H:i',$rider_startTime);
                $online_time=Carbon::parse($online_record_null->online_time)->diffForHumans();
                array_push($logged_rider,array('rider'=>$rider,'online_time'=>$online_time));
            }
            else if($rider_startTime){ //not log in yet
                if(!isset($online_record)){
                    $rider_startTime = Carbon::createFromFormat('H:i',$rider_startTime)->addMinutes(15);
                    $current_time=Carbon::now();
                    if($current_time->greaterThanOrEqualTo($rider_startTime)){ // late
                        array_push($notlogged_rider,$rider);
                    }
                }
            }
            

        }
        // return $logged_rider;
        return view('admin.home', compact('all_riders','logged_rider','notlogged_rider','ve__riders','pe__riders','me__bikes','le__riders','riders', 'clients', 'online_riders', 'clients_online', 'latest_riders', 'latest_clients'));
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

    public function accounts_testing_v1(){
        $sum_dr = Company_Account::where("active_status","A")->where("type","dr")->sum('amount');
        $sum_cr = Company_Account::where("active_status","A")->where("type","cr")->sum('amount');
        $closing_balance_CA=$sum_cr-$sum_dr;

        $sum_dr = Rider_Account::where("active_status","A")->where("type","dr")->sum('amount');
        $sum_cr = Rider_Account::where("active_status","A")->where("type","cr")->sum('amount');
        $closing_balance_RA=$sum_cr-$sum_dr;

        return view('admin.accounts.testing',compact('closing_balance_CA','closing_balance_RA'));
    
    }
    public function request403(){
        return view("403");
    }
    public function add_manual_client_history(){
        $clients = Client::all();
        $riders = Rider::all();
        return view("admin.add_client_history",compact('clients','riders'));
    }
    public function submit_manual_client_history(Request $r){
        // return Carbon::parse($r->assign_date)->format('Y-m-d');
        $rec = new Client_History;
        $rec->client_id = $r->client_id;
        $rec->rider_id = $r->rider_id;
        $rec->assign_date = Carbon::parse($r->assign_date)->format('Y-m-d');
        $rec->deassign_date = Carbon::parse($r->deassign_date)->format('Y-m-d');
        $rec->client_rider_id = $r->client_rider_id;
        $rec->status = 'deactive';
        $rec->save();
        return redirect(route('request.add_manual_client_history'))->with('message', 'Record added successfully.');

    }
    public function show_add_routes(){
        return view('admin.addroutes');
    }
    public function edit_routes($id){
        $webroute = WebRoute::find($id);
        return view('admin.editroute',compact('webroute'));
    }
    public function view_add_routes(){
        return view('admin.show_routes');
    }
    public function insert_add_routes(Request $request){ 
        $webroutes= new WebRoute();
        $webroutes->category=$request->category;
        $webroutes->label=$request->label;
        $webroutes->type=$request->type;
        $webroutes->route_name=$request->route_name;
        $webroutes->route_description=$request->route_description;
        $webroutes->save();
        return redirect(route('admin.add_routes'))->with('message', 'Route added successfully.');
    }
    public function update_add_routes(Request $request){ 
        // return $request; 
        $webroutes= WebRoute::find($request->id);
        $webroutes->category=$request->category;
        $webroutes->label=$request->label;
        $webroutes->type=$request->type;
        $webroutes->route_name=$request->route_name;
        $webroutes->route_description=$request->route_description;
        $webroutes->save();
        return redirect(route('admin.view_routes'))->with('message', 'Route updated successfully.'); 
    }
}
