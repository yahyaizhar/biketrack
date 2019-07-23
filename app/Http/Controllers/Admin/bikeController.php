<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Bikes\bike;
use App\Model\Bikes\bike_detail;
use App\Model\Client\Client;
use Illuminate\Support\Facades\Hash;
use App\Model\Rider\Rider;
use App\Model\Client\Client_Rider;
use Illuminate\Support\Facades\Storage;
use App\Model\Rider\Rider_Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class bikeController extends Controller 
{
  public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function bike_login(){
      return view('admin.Bike.bike_login');
    

    }
    public function bike_view(){

      
      return view('admin.Bike.bike_view');
    }
    public function create_bike(Request $r){
      $bike_object=bike::create($r->all());
      $bike_detail = $bike_object->Bike_detail()->create([
      'registration_number'=> $r->get('registration_number'),
        
]);
      return view('admin.Bike.bike_view');
    

    }
    public function bike_assigned_show(Client $client){
      $bike = $client->bike;
      // return view('Bike.bike_assigned', compact('client', 'bike'));
      return $bike;
      
    }
    public function bike_edit(Request $request,$id){
      $bike_id_array =$request->id;
      $bike = bike::find($bike_id_array);
      return view('admin.Bike.Edit_bike',compact('bike'));
      // return $bike;
    }
    public function bike_update(Request $request,bike $bike,$id){

      $this->validate($request, [
        'model' => 'required | string | max:255',
        'bike_number' => 'required | string |max:255',
        'availability' => 'required | string | max:255',
    ]);
    $bike_id_array =$request->id;
    $bike = bike::find($bike_id_array);
    $bike->model = $request->model;
    $bike->bike_number = $request->bike_number;
    $bike->availability = $request->availability;
    
    
    if($request->status)
        $bike->status = 1;
    else
        $bike->status = 0;
   
    $bike->update();
   
    return redirect(route('bike.bike_view'))->with('message', 'Record Updated Successfully.');

      
      
    }
    
}
