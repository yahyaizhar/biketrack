<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Client\Client;
use App\Model\Client\Client_History;
use Illuminate\Support\Facades\Hash;
use App\Model\Rider\Rider;
use App\Model\Client\Client_Rider;
use Illuminate\Support\Facades\Storage;
use App\Model\Rider\Rider_Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Model\Bikes\bike;
use App\Model\Bikes\bike_detail;
use App\Assign_bike;
use Carbon\Carbon;

class ClientController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients_count=Client::where("active_status","A")->get()->count();
        return view('admin.client.clients',compact('clients_count'));
    }
    public function get_active_clients()
    {
        $clients_count=Client::where("active_status","A")->get()->count();
        return view('admin.client.clients_active',compact('clients_count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // return $request;
        $this->validate($request, [
            'name' => 'required | string | max:255',
            'email' => 'required | email | unique:clients',
            'phone' => 'required | string | min:8 | max:255',
            'password' => 'required | string | confirmed',
            'address' => 'required | string',
            'restaurant_location' => 'required',
            'latitude' => 'required | numeric',
            'longitude' => 'required | numeric',
        ]);
        // return 'yse';
        $client = new Client();
        $client->name = $request->name;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->password = Hash::make($request->password);
        // $client->about = $request->about;
        $client->address = $request->address;
        $client->latitude = $request->latitude;
        $client->longitude = $request->longitude;
        if($request->status)
            $client->status = 1;
        else
            $client->status = 0;
        if($request->hasFile('logo'))
        {
            $filename = $request->logo->getClientOriginalName();
            $filesize = $request->logo->getClientSize();
            // $filepath = $request->logo->storeAs('public/uploads/clients/logos', $filename);
            $filepath = Storage::putfile('public/uploads/clients/logos', $request->file('logo'));
            $client->logo = $filepath;
        }
        $client->save();
        return redirect(route('admin.clients.index'))->with('message', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
        return view('admin.client.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
        $this->validate($request, [
            'name' => 'required | string | max:255',
            'email' => 'required | email',
            'phone' => 'required | string | max:255',
            'address' => 'required | string',
            'latitude' => 'required | numeric',
            'longitude' => 'required | numeric',
        ]);
        if($request->change_password)
        {
            $this->validate($request, [
                'password' => 'required | string | confirmed',
            ]);
            $client->password = Hash::make($request->password);
        }
        $client->name = $request->name;
        $client->email = $request->email;
        $client->phone = $request->phone;
        // $client->about = $request->about;
        $client->address = $request->address;
        $client->latitude = $request->latitude;
        $client->longitude = $request->longitude;
        if($request->status)
            $client->status = 1;
        else
            $client->status = 0;
        if($request->hasFile('logo'))
        {
            // return 'yes';
            if($client->logo)
            {
                Storage::delete($client->logo);
            }
            $filename = $request->logo->getClientOriginalName();
            $filesize = $request->logo->getClientSize();
            $filepath = Storage::putfile('public/uploads/clients/logos', $request->file('logo'));
            $client->logo = $filepath;
        }
        // return 'no';
        $client->update();
        return redirect(route('admin.clients.index'))->with('message', 'Record Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
        // if($client->logo)
        // {
        //     Storage::delete($client->logo);
        // }
        $client->active_status="D";
        $client->status=0;
        $client->update();
        $client_rider=Client_Rider::where("client_id",$client->id)->where("status",1)->get();
        foreach ($client_rider as  $delete_rider) {
            $delete_rider->delete();
            $client_history=Client_History::where('rider_id',  $delete_rider->rider_id)
            ->where('client_id',  $delete_rider->client_id)
            ->where("status","active")
            ->get()
            ->first();
            $client_history->status="deactive";
            $client_history->deassign_date=Carbon::now()->format("Y-m-d");
            
            $client_history->save();
        }
      }

    public function showRiders(Client $client) 
    {
        $riders = $client->riders;
        $client_history=Client_History::where("client_id",$client->id)->where("status","deactive")->get();
        $client_history_active=Client_History::where("client_id",$client->id)->where("status","active")->get();
        return view('admin.client.riders', compact('client', 'riders','client_history','client_history_active'));
    }

    public function assignRiders(Client $client)
    {
        // $riders = DB::table('riders')
        // ->select('riders.id', 'riders.name', 'riders.vehicle_number')
        // ->leftJoin('client_riders', 'riders.id', '=', 'client_riders.rider_id')
        // ->where('client_riders.status', '=', '0')
        // ->where('riders.status', '=', '1')
        // ->distinct('client_riders.rider_id')
        // ->orderByDesc('client_riders.created_at')
        // ->get();

        $riders = DB::table('riders')
        ->select('riders.id', 'riders.name')
        ->leftJoin('client_riders', 'riders.id', '=', 'client_riders.rider_id')
        ->where('riders.status', '=', '1')
        ->whereNull('client_riders.id')
        ->get();
        // return $riders;
        return view('admin.client.assignRider', compact('client', 'riders'));
    }
    public function updateAssignedRiders(Client $client, Request $request)
    {
        // $client->riders()->sync($request->riders);
        foreach($request->riders as $rider)
        {
            $new_record = new Client_Rider();
            $new_record->client_id = $client->id;
            $new_record->rider_id = $rider;
            $new_record->status = 1;
            $new_record->save(); 

            $client_history= new Client_History;
            $client_history->client_id = $client->id;
            $client_history->rider_id = $rider;
            $client_history->status = "active";
            $client_history->assign_date =Carbon::parse($request->assign_date)->format('Y-m-d');
            $client_history->deassign_date =Carbon::parse($request->assign_date)->format('Y-m-d');
            $client_history->save();
        }
        return redirect(route('admin.clients.riders', $client));
    }
    public function removeRiders($client, $rider)
    {
        $record = Client_Rider::where('rider_id', $rider)->where('client_id', $client)->orderBy('created_at', 'desc')->first();
        $record->delete();
        // return $record;
        // $record->status = 0;
        // $record->update();
        $client_history=Client_History::where('rider_id', $rider)
        ->where('client_id', $client)
        ->orderBy('created_at', 'desc')
        ->where("status","active")
        ->get()
        ->first();
        $client_history->status="deactive";
        $client_history->deassign_date=Carbon::now()->format("Y-m-d");
        $client_history->save();
        return response()->json([
            'status' => true
        ]);
    }
    public function showClientProfile(Client $client)
    {
        return view('admin.client.profile', compact('client'));
    }
    public function updateStatus(Client $client)
    {
        if($client->status == 1)
        {
            $client->status = 0;
        }
        else
        {
            $client->status = 1;
        }
        $client->update();
        return response()->json([
            'status' => true
        ]);
    }
    public function mutlipleDelete(Request $request)
    {
        $client_id_array = $request->id;
        // return $client_id_array;
        $clients = Client::whereIn('id', $client_id_array);
        if($clients->delete())
        {
            return response()->json([
                'status' => true
            ]);
        }
    }
// for bikeController

public function bike_assigned_show($id){
    $bike = bike::where("active_status","A")->get()->toArray();
    Rider::where("active_status","A")->get()->toArray();
    $rider=Rider::find($id);
    $assign_bike=count($rider->Assign_bike()->where('status', 'active')->get());
    $rider_show=$rider->Assign_bike()->get();
    
    $a=$rider->Assign_bike()->where('status', 'active')->get()->first();
    if(isset($a)){
    $bike_id=bike::find($a->bike_id);
}
    return view('admin.Bike.bike_assigned', compact( 'bike','rider','assign_bike','rider_show','bike_id'));
    //   return $bike_id;
    
  }
  public function bike_assigned_toRider(Request $request,$id){
    $rider=Rider::find($id); 
    $bikes=bike::where("active_status","A")->get();
    $assign_bike=count($rider->Assign_bike()->where('status', 'active')->get());
    return view('admin.Bike.bike_assigned_toRider',compact('rider','bikes','assign_bike'));
    // return $bikes;

  }
  public function updateStatusbike(bike $bike)
  {   
      if($bike->status == 1)
      {
          $bike->status = 0;
      }
      else
      {
          $bike->status = 1;
      }
      $bike->update();
      return response()->json([
          'status' => true
      ]);
  }
  public function updateAssignedBike(Request $request,$id)
  { 
    $month=Carbon::parse($request->bike_assign_date)->format('Y-m-d');
    $rider=Rider::find($id);
    $assign_bike = $rider->Assign_bike()->where('status', 'active')->get();
    $hasBike = $assign_bike->count();
    
   
    if($hasBike > 0){
        $assign_bike_id = $assign_bike->first()->id;
        $ab = Assign_bike::find($assign_bike_id);
        $ab->status='deactive';
        $ab->bike_unassign_date=Carbon::now()->format("Y-m-d");
        $ab->save();
        $bikes_availability=bike::find($assign_bike->first()->bike_id);
        $bikes_availability->availability='yes'; 
        $bikes_availability->save();
    }
    // return $hasBike;
// return $rider;
    $bikes=bike::find($request->get('bike_id'));
    $assign_bikes =$rider->Assign_bike()->create([
    'rider_id'=>$request->get('rider_id'),
    'bike_id' => $request->get('bike_id'),
    'status'=>'active'
    ]);
    $assign_bikes->bike_assign_date=$month;
    $assign_bikes->bike_unassign_date=$month;
    $assign_bikes->save();
    $bikes->availability='no';
    // return $assign_bikes;
    
    $bikes->save();
// return $assign_bikes; 
return redirect(route('Bike.assignedToRiders_History', $rider->id))->with('message', 'Bike assigned successfully.');;
}
  public function mutlipleDeleteBike(Request $request)
  {
    $bike_id_array =$request->bike_id;
    $bike = bike::find($bike_id_array);
    $bike->active_status='D';
    $bike->availability='no';
    $bike->update();

    $bike_detail=$bike->bike_detail;
    $bike_detail->active_status="D";
    $bike_detail->update();

    $assign_bike = $bike->Assign_bike()->where('status', 'active')->get()->first();
    if(isset($assign_bike)){
        $assign_bike->status='deactive';
        $assign_bike->bike_unassign_date=Carbon::now()->format("Y-m-d");
        $assign_bike->update();
    }
    
    return response()->json([
        'status' => true
    ]);
      
  }

public function Bike_assigned_to_riders_history(Request $request,$id){
  $rider=Rider::find($id);
  $assign_bikes=$rider->Assign_bike()->get();
  $assign_bike_count=$assign_bikes->count();
  return view('admin.Bike.Biking_history', compact( 'rider','assign_bikes','assign_bike_count'));
}
public function rider_history($bike_id){
    $bike=bike::find($bike_id);
    $bike_histories=$bike->Assign_bike()->get();
  return view('admin.Bike.rider_history',compact('bike_histories', 'bike'));
  // return $hasRider;
}
public function bike_profile($bike_id,$rider_id){

$rider=Rider::find($rider_id);
$bike=bike::find($bike_id);

$assign_bike=$rider->Assign_bike()->where('status','active')->get()->first();
if($assign_bike){
$bike_profile=bike::find($assign_bike->bike_id);
return view('admin.Bike.bike_profile',compact('bike_profile','rider','assign_bike','bike'));
}
return redirect('admin/riders');
}
public function deletebikeprofile($rider_id,$bike_id){
$assign_bike=Assign_bike::where('rider_id',$rider_id)->where('bike_id',$bike_id)->get()->first();
   if (isset($assign_bike)) {
       $assign_bike->status='deactive';
       $assign_bike->bike_unassign_date=Carbon::now()->format("Y-m-d");
       $assign_bike->save();
       $bike=bike::find($bike_id);
       $bike->availability='yes';
       $bike->save();
   }
}
// end bikeController
public function change_dates_history(Request $request,$rider_id,$assign_bike_id){
    $assign_bike=Assign_bike::find($assign_bike_id);
    if (isset($assign_bike)) {
        $assign_bike->bike_assign_date=Carbon::parse($request->bike_assign_date)->format("Y-m-d");
        $assign_bike->bike_unassign_date=Carbon::parse($request->bike_unassign_date)->format("Y-m-d"); 
        $assign_bike->update();
    }
    
    return response()->json([
        'status' =>  $assign_bike,
        'a'=>$assign_bike->bike_assign_date,
        'b'=>$assign_bike->bike_unassign_date,
    ]);
}
public function profit_client($id){
    $client=Client::find($id);
    return view('client_profit_sheet',compact('client'));
}

public function add_payout_method(Request $r){

    $method = $r->payout_method;
    $client = Client::find($r->client_id);
    $settings=[];
    $settings['payout_method']=$method;
    switch ($method) {
        case 'trip_based':
            $settings['tb__trip_amount']=$r->tb__trip_amount;
            $settings['tb__hour_amount']=$r->tb__hour_amount;
            break;
        case 'fixed_based':
            $settings['fb__amount']=$r->fb__amount;
            $settings['fb__workable_hours']=$r->fb__workable_hours;
            break;
        
        default:
            return response()->json([
                'status'=>0,
                'message'=>'No Payout Method is selected.'
            ]);
            break;
    }
    $client->setting=json_encode($settings);
    $client->save();
    return response()->json([
        'status'=>1,
        'client'=>$client 
    ]);
}
public function add_salary_method(Request $r){

    $method = $r->salary_method;
    $client = Client::find($r->client_id);
    $settings=[];
    $settings['salary_method']=$method;
    switch ($method) {
        case 'trip_based':
            $settings['tb_sm__trip_amount']=$r->tb_sm__trip_amount;
            $settings['tb_sm__hour_amount']=$r->tb_sm__hour_amount;
            $settings['tb_sm__bonus_trips']=$r->tb_sm__bonus_trips;
            $settings['tb_sm__bonus_amount']=$r->tb_sm__bonus_amount;
            $settings['tb_sm__trips_bonus_amount']=$r->tb_sm__trips_bonus_amount;
            break;
        case 'fixed_based':
            $settings['fb_sm__amount']=$r->fb_sm__amount;
            $settings['fb_sm__exrta_hours']=$r->fb_sm__exrta_hours;
            break;
        case 'commission_based':
            $settings['cb_sm__amount']=$r->cb_sm__amount;
            $settings['cb_sm__type']=$r->cb_sm__type;
            break;
        
        default:
            return response()->json([
                'status'=>0,
                'message'=>'No Payout Method is selected.'
            ]);
            break;
    }
    $client->salary_methods=json_encode($settings);
    $client->save();
    return response()->json([
        'status'=>1,
        'client'=>$client 
    ]);
}
public function client_history_dates(Request $request,$rider_id,$client_history_id){
    $CH=Client_History::where("rider_id",$rider_id)->where("id",$client_history_id)->get()->first();
    if (isset($CH)) {
        $CH->assign_date=Carbon::parse($request->assign_date)->format("Y-m-d");
        $CH->deassign_date=Carbon::parse($request->deassign_date)->format("Y-m-d");
        $CH->save();
    }
    return response()->json([
        'ch'=>$CH,
        'a'=>$rider_id,
        'b'=>$client_history_id,
    ]);
}


}