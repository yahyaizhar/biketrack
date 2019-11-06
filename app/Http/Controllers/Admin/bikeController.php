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
use \App\Model\Accounts\Bike_Accounts;
use App\Assign_bike;
use Arr;

class bikeController extends Controller 
{
  public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function bike_login(){
      $riders=Rider::where('active_status','A')->get(); 
      return view('admin.Bike.bike_login',compact('riders'));
    

    }
    public function bike_view(){

      $bike_count=bike::all()->count();
      return view('admin.Bike.bike_view',compact('bike_count'));
    }
    public function create_bike(Request $r){
      $bike_object=new bike;
      $bike_object->owner = $r->owner;
      $bike_object->model = $r->model;
      $bike_object->bike_number = $r->bike_number;
      $bike_object->mulkiya_number = $r->mulkiya_number;
      $bike_object->brand = $r->brand;
      $bike_object->chassis_number = $r->chassis_number;
      $bike_object->mulkiya_expiry = $r->mulkiya_expiry;
      $bike_object->rental_company =$r->rental_company;
      $bike_object->bike_allowns =$r->bike_allowns;
      $bike_object->contract_start=null; 
      $bike_object->contract_end=null;
      if ($bike_object->owner == 'rent') {
        $bike_object->contract_start =Carbon::parse($r->contract_start)->format('Y-m-d');
        $bike_object->contract_end =Carbon::parse($r->contract_end)->format('Y-m-d');
      }
      $bike_object->rent_amount =$r->rent_amount;
      $bike_object->amount =$r->amount;
      $bike_object->rider_id=null;
      if ($bike_object->owner == 'self') {
        $bike_object->rider_id = $r->rider_id;
      }
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
            // return $bike_object;
            $bike_object->save();
            if ($bike_object->owner == 'kr_bike') {
              $ba= new Bike_Accounts();
              $ba->type='dr';
              $ba->amount=$bike_object->amount;
              $ba->month=Carbon::parse($bike_object->created_at)->format('Y-m-d');
              $ba->bike_id =$bike_object->id;
              $ba->source='Purchase Bike';
              $ba->payment_status='paid';
              $ba->save();
            }
      $bike_detail = $bike_object->Bike_detail()->create([
      'registration_number'=> $r->get('registration_number'),
]);
      return view('admin.Bike.bike_view');
    

    }
    public function bike_assigned_show(Client $client){
      $bike = $client->bike;
      return view('Bike.bike_assigned', compact('client', 'bike'));
      return $bike;
      
    }
    public function bike_edit(Request $request,$id){
      $bike_id_array =$request->id;
      $bike = bike::find($bike_id_array);
      $riders=Rider::where('active_status','A')->get();
      return view('admin.Bike.Edit_bike',compact('bike','riders'));
      // return $bike;
    }
    public function bike_update(Request $request,bike $bike,$id){

      $this->validate($request, [
        'model' => 'required | string | max:255',
        'bike_number' => 'required | string |max:255',
        
    ]);
    $bike_id_array =$request->id;
    $bike = bike::find($bike_id_array);
    $bike->owner = $request->owner;
    $bike->model = $request->model;
    $bike->bike_number = $request->bike_number;
    // $bike->availability = $request->availability;
    $bike->brand = $request->brand;
    $bike->chassis_number = $request->chassis_number;
    $bike->mulkiya_number = $request->mulkiya_number;
    $bike->mulkiya_expiry = $request->mulkiya_expiry;
    $bike->rental_company = $request->rental_company;
    $bike->contract_start=null; 
    $bike->contract_end=null;
    if ($bike->owner == 'rent') {
      $bike->contract_start =Carbon::parse($request->contract_start)->format('Y-m-d');
      $bike->contract_end =Carbon::parse($request->contract_end)->format('Y-m-d');
    }
    $bike->rent_amount = $request->rent_amount;
    $bike->amount = $request->amount;
    $bike->rider_id=null;
    if ($bike->owner == 'self') {
      $bike->rider_id = $request->rider_id;
    }
    $bike->bike_allowns =$request->bike_allowns;
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
        
       $date=$bike_rent->month;
       $bike_id=$r->bike_id;
      $bike_history = Assign_bike::all();
      $bike_histories = null;
      $history_found = Arr::first($bike_history, function ($item, $key) use ($bike_id, $date) {
          $created_at =Carbon::parse($item->created_at)->format('Y-m-d');
          $created_at =Carbon::parse($created_at);

          $updated_at =Carbon::parse($item->updated_at)->format('Y-m-d');
          $updated_at =Carbon::parse($updated_at);
          $req_date =Carbon::parse($date);
          if($item->status=="active"){ 
            // mean its still active, we need to match only created at
              return $item->bike_id == $bike_id && $req_date->greaterThanOrEqualTo($created_at);
          }
          
          return $item->bike_id == $bike_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
      });

  
      $ca = new Company_Account;
      $ca->type='dr';
      if (isset($history_found)) {
        $rider_id=$history_found->rider_id;
        $ca->rider_id=$rider_id;
      }
      else{
        $ca->rider_id=NULL;
      }
      $ca->amount=$r->amount;
      $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
      $ca->bike_rent_id ='0';
      $ca->source='Bike Rent';
      $ca->save();
    
      return redirect(route('bike.bike_view'));
      
    }
    // end Bike Rent
    public function deactive_date(Request $request,$rider_id,$assign_bike_id){ 
      $assign_bike=Assign_bike::where("rider_id",$rider_id)->where("id",$assign_bike_id)->get()->first();
    if (isset($assign_bike)) {
        $assign_bike->updated_at=Carbon::parse($request->updated_at)->format("Y-m-d");  
        $assign_bike->status='deactive';
    }
    $assign_bike->update();
    if (isset($assign_bike->bike_id)) {
      $bikes_availability=bike::find($assign_bike->bike_id);    
      $bikes_availability->availability='yes'; 
    }
    $bikes_availability->update();
      return response()->json([
        'updated_at' => $assign_bike->updated_at
    ]);
    }
    public function give_bike_to_company($bike_id){
      $bike=bike::find($bike_id);
      return view('admin.Bike.assign_bike_to_kingriders_company',compact('bike'));
    }
    public function is_given_bike_status(Request $request,$id){
      // $bike=bike::find($id);
      // $bike->is_given='0';
      // if ($bike->rider_id==null) {
      //   $bike->rent_amount=$request->monthly_rent;
      // }
      // else{
      //   $bike->bike_allowns=$request->bike_allowns;
      // }
          $bikes=bike::all();
    foreach ($bikes as $bike) {
      if ($bike->availability=='yes') {
        $bike->is_given='1';
      }
      if ($bike->availability=='no') {
        $bike->is_given='0';
      }
        $bike->save();
    }
   
      // return redirect(route('bike.bike_view'));
      return 'han ho gaya';
    } 
}
