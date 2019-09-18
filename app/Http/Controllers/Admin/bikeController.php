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
use carbon\carbon;
use \App\Model\Accounts\Company_Account;

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

      $bike_count=bike::all()->count();
      return view('admin.Bike.bike_view',compact('bike_count'));
    }
    public function create_bike(Request $r){
      $bike_object=new bike;
      $bike_object->model = $r->model;
      $bike_object->bike_number = $r->bike_number;
      $bike_object->mulkiya_number = $r->mulkiya_number;
      $bike_object->brand = $r->brand;
      $bike_object->chassis_number = $r->chassis_number;
      $bike_object->mulkiya_expiry = $r->mulkiya_expiry;
      
      if($r->status)
            $bike_object->status = 1;
        else
            $bike_object->status = 0;
            if($r->hasFile('mulkiya_picture'))
            {
                // return 'yes';
                $filename = $r->mulkiya_picture->getClientOriginalName();
                $filesize = $r->mulkiya_picture->getClientSize();
                // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
                $filepath = Storage::putfile('public/uploads/riders/mulkiya_pictures', $r->file('mulkiya_picture'));
                $bike_object->mulkiya_picture = $filepath;
            }
            if($r->hasFile('mulkiya_picture_back'))
            {
                // return 'yes';
                $filename = $r->mulkiya_picture_back->getClientOriginalName();
                $filesize = $r->mulkiya_picture_back->getClientSize();
                // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
                $filepath = Storage::putfile('public/uploads/riders/mulkiya_picture_back', $r->file('mulkiya_picture_back'));
                $bike_object->mulkiya_picture_back = $filepath;
            }
            $bike_object->save();
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
        
    ]);
    $bike_id_array =$request->id;
    $bike = bike::find($bike_id_array);
    $bike->model = $request->model;
    $bike->bike_number = $request->bike_number;
    // $bike->availability = $request->availability;
    $bike->brand = $request->brand;
    $bike->chassis_number = $request->chassis_number;
    $bike->mulkiya_number = $request->mulkiya_number;
    $bike->mulkiya_expiry = $request->mulkiya_expiry;
    if($request->status)
        $bike->status = 1;
    else
        $bike->status = 0;
        if($request->hasFile('mulkiya_picture'))
        {
            // return 'yes';
            if($bike->mulkiya_picture)
            {
                Storage::delete($bike->mulkiya_picture);
            }
            $filename = $request->mulkiya_picture->getClientOriginalName();
            $filesize = $request->mulkiya_picture->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/mulkiya_pictures', $request->file('mulkiya_picture'));
            $bike->mulkiya_picture = $filepath;
        }
        if($request->hasFile('mulkiya_picture_back'))
        {
            // return 'yes';
            if($bike->mulkiya_picture_back)
            {
                Storage::delete($bike->mulkiya_picture_back);
            }
            $filename = $request->mulkiya_picture_back->getClientOriginalName();
            $filesize = $request->mulkiya_picture_back->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/mulkiya_picture_back', $request->file('mulkiya_picture_back'));
            $bike->mulkiya_picture_back = $filepath;
        }
    $bike->update();
   
    return redirect(route('bike.bike_view'))->with('message', 'Record Updated Successfully.');

      
      
    }
    // Bike Rent
    public function create_bike_rent(){
      $bikes=bike::where('active_status','A')->get();
      return view('admin.Bike.bike_rent',compact('bikes'));
    }
    public function post_bike_rent(Request $r){
      $bike_rent=new bike;
      $bike_rent->bike_id=$r->bike_id;
      $bike_rent->month=carbon::parse($r->month)->format('Y-m-d');
      $bike_rent->amount=$r->amount;
      
      $ca = new Company_Account;
      $ca->type='dr';
      $ca->amount=$r->amount;
      $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
      $ca->bike_rent_id ='0';
      $ca->source='Bike Rent';
      $ca->save();
      return redirect(route('bike.bike_view'));
    }
    // end Bike Rent
}
