<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Client\Client;
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
        //
        // return Client::all();
        return view('admin.client.clients');
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
        if($client->logo)
        {
            Storage::delete($client->logo);
        }
        $client->delete();
    }

    public function showRiders(Client $client)
    {
        // $riders = $client->getRiders;
        $riders = $client->riders;
        return view('admin.client.riders', compact('client', 'riders'));
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
    $bike = bike::all()->toArray();
    Rider::all()->toArray();
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
    $bikes=bike::all();
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
    $rider=Rider::find($id);
    $assign_bike = $rider->Assign_bike()->where('status', 'active')->get();
    $hasBike = $assign_bike->count();
    
   
    if($hasBike > 0){
        $assign_bike_id = $assign_bike->first()->id;
        $ab = Assign_bike::find($assign_bike_id);
        $ab->status='deactive';
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
    $bikes->availability='no';
    // return $assign_bikes;
    
    $bikes->save();

return redirect()->route('bike.bike_assigned', ['id'=>$rider->id]) ;
}
  public function removeBikes($rider_id,$bike_id){
    
    $record = Assign_bike::where('rider_id', $rider_id)->where('bike_id', $bike_id)->orderBy('created_at', 'desc')->first();
   
    if($record->bike_id){
    $bikes_availability=bike::find($record->bike_id);    
    $bikes_availability->availability='yes'; 
    $bikes_availability->save();
    
}
$record->status='deactive';
$record->save();


    return response()->json([
        'status' => true
    ]);
   
  }
  public function mutlipleDeleteBike(Request $request)
  {
    
       $bike_id_array =$request->bike_id;
      $bike = bike::find($bike_id_array);
  $bike->delete();
      
          return response()->json([
              'status' => true
          ]);
      
  }

public function Bike_assigned_to_riders_history(Request $request,$id){
  $rider=Rider::find($id);
  $bike=bike::all()->toArray();
  $bike_id=bike::find($id);
  $assign_bike_id=$rider->Assign_bike()->get();
  $assign_bike_count=$assign_bike_id->count();
  $hasBike=bike::find($assign_bike_id->pluck('bike_id'));
  return view('admin.Bike.Biking_history', compact( 'rider','assign_bike_id','assign_bike_count','hasBike'));
}
public function rider_history(Request $request,$id){
$bike_id=bike::find($id);
 $riders=Rider::all()->toArray();
 $rider_id=Rider::find($id);
 $assign_rider=$bike_id->Assign_bike()->get();
 $assign_rider_id_count=$assign_rider->count();
 $hasRider=Rider::find($assign_rider->pluck('rider_id'));
  return view('admin.Bike.rider_history',compact('bike_id','riders','assign_rider','assign_rider_id_count','hasRider','rider_id'));
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


// end bikeController


}