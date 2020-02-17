<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Accounts\Company_Expense;
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
use \App\Model\Accounts\Rider_Account;
use App\Assign_bike;
use App\insurance_company;
use Arr;
use App\Export_data;
use App\Model\Client\Client_History;

class bikeController extends Controller 
{
  public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function bike_login(){
      $riders=Rider::where('active_status','A')->get(); 
      $insurance_co_name=insurance_company::all();
      return view('admin.Bike.bike_login',compact('riders','insurance_co_name'));
    

    }
    public function bike_view(){

      $bike_count=bike::all()->count();
      return view('admin.Bike.bike_view',compact('bike_count'));
    }
    public function bike_view_active(){

      $bike_count=bike::all()->count();
      return view('admin.Bike.bike_view_active',compact('bike_count'));
    }
    public function create_bike(Request $r){
      $bike_number=$r->bike_number;
      $bike=bike::where("bike_number",$bike_number)->get()->first();
     if (isset($bike)) {
             if($bike->active_status==="A"){
              return redirect(url('/admin/bike_view?search='.$bike->bike_number))->with('error', 'Bike is already created with id= '.$bike->id.'. And status is Active.');
             }
             return redirect(url('/admin/bike_view?search='.$bike->bike_number))->with('error', 'Bike is already created with id= '.$bike->id.'.But status is deactive now.');
      }
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
      $bike_object->insurance_co_name = $r->insurance_co;
      $bike_object->issue_date =Carbon::parse($r->issue_date)->format('Y-m-d');
      $bike_object->expiry_date =Carbon::parse($r->expiry_date)->format('Y-m-d');

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
      $is_readonly=false;
      $bike_id_array =$request->id;
      $bike = bike::find($bike_id_array);
      $riders=Rider::where('active_status','A')->get();
      $insurance_co_name=insurance_company::all();
      return view('admin.Bike.Edit_bike',compact('is_readonly','bike','riders','insurance_co_name'));
      // return $bike;
    }
    public function bike_edit_view(Request $request,$id){
      $is_readonly=true;
      $bike_id_array =$request->id;
      $bike = bike::find($bike_id_array);
      $riders=Rider::where('active_status','A')->get();
      $insurance_co_name=insurance_company::all();
      return view('admin.Bike.Edit_bike',compact('is_readonly','bike','riders','insurance_co_name'));
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
    $bike->insurance_co_name = $request->insurance_co;
    $bike->issue_date = Carbon::parse($request->issue_date)->format('Y-m-d');
    $bike->expiry_date =Carbon::parse($request->expiry_date)->format('Y-m-d');
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
   
    return redirect(route('Bike.bike_edit_view',$bike->id))->with('message', 'Record Updated Successfully.');

      
      
    }
    // Bike Rent
    public function create_bike_rent(){
      $bikes=bike::where('active_status','A')->get();
      $riders=Rider::where('active_status','A')->get();
      return view('admin.Bike.bike_rent',compact('bikes','riders'));
    }
    public function post_bike_rent(Request $r){
      $data=$r->data;
      $total_amount=$r->amount;
      $splitted_amount=0;
      $bike_own='';
      $pm='';
      // return $r->all();
      foreach ($data as $item) {
        $rider_id=$r->rider_id;
        if(isset($item['rider_id'])){
            $rider_id=$item['rider_id'];
        }
        $date_to_match=$r->get('month');
        $client_histories=Client_History::all();
        $history_found = Arr::first($client_histories, function ($iteration, $key) use ($rider_id, $date_to_match) {
            $start_created_at =Carbon::parse($iteration->assign_date)->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);

            $start_updated_at =Carbon::parse($iteration->deassign_date)->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($date_to_match);

            if($iteration->status=='active'){    
                return $iteration->rider_id==$rider_id && 
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
            }

            return $iteration->rider_id==$rider_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });
        if (isset($history_found)) {
            $client_id=$history_found->client_id;
            $client=Client::find($client_id);
            $c_setting=json_decode($client->setting,true);
            $pm=$c_setting['payout_method'];
        }
        $type=$item['type'];
        if(isset($item['bike_id'])){
          $bike_id=$item['bike_id'];
        }
        else{
          $bike_id = $r->bike_id;
        }
        $work_days_count=$item['work_days_count'];
        $total_days=$item['total_days'];
        $amount_given_by_days=$item['amount_given_by_days'];
        if($amount_given_by_days==0) continue;
        $bike_own=$item['owner'];
        if($type=='bike'){
          $rider_id=$r->rider_id;
          if(isset($item['bike_id'])){
            $bike_id=$item['bike_id'];
          }
          else{
            $bike_id = $r->bike_id;
          }
        }
        else {
          $rider_id=$item['rider_id'];
          $bike_id=$r->bike_id;
        }
        if ($pm=="commission_based") {
          if ($bike_own!="self") {
            $ca=new Company_Account();
            $ca->type='cr';
            $ca->amount=$amount_given_by_days;
            $ca->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
            $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ca->bike_rent_id =$bike_id;
            $ca->rider_id=$rider_id;
            $ca->source='Bike Rent';
            $ca->save();

            $ca=new Company_Account();
            $ca->type='dr';
            $ca->amount=$amount_given_by_days;
            $ca->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
            $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ca->bike_rent_id =$bike_id;
            $ca->rider_id=$rider_id;
            $ca->source='Bike Rent';
            $ca->save();

            $ra=new Rider_Account();
            $ra->type='dr';
            $ra->amount=$amount_given_by_days;
            $ra->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
            $ra->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ra->bike_rent_id =$bike_id;
            $ra->rider_id=$rider_id;
            $ra->source='Bike Rent';
            $ra->save();
          }
        }
        else{
          $ca=new Company_Account();
          $ca->type='dr';
          $ca->amount=$amount_given_by_days;
          $ca->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
          $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
          $ca->bike_rent_id =$bike_id;
          $ca->rider_id=$rider_id;
          $ca->source='Bike Rent';
          $ca->save();
        }
        $ed =new Export_data;
        $ed->type='dr';
        $ed->rider_id=$rider_id;
        $ed->amount=$amount_given_by_days;
        $ed->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
        $ed->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
        $ed->source='Bike Rent';
        $ed->source_id='0';
        $ed->payment_status="paid";
        $ed->save();
          
        if ($bike_own=="kr_bike") {
          $ba=new Bike_Accounts();
          $ba->type='cr';
          $ba->amount=$amount_given_by_days;
          $ba->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
          $ba->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
          $ba->bike_id =$bike_id;
          $ba->rider_id=$rider_id;
          $ba->source='Bike Rent';
          $ba->save();
        }
        if ($bike_own=="rent") {
          $ba=new Bike_Accounts();
          $ba->type='cr';
          $ba->amount=$amount_given_by_days;
          $ba->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
          $ba->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
          $ba->bike_id =$bike_id;
          $ba->rider_id=$rider_id;
          $ba->source='Bike Rent';
          $ba->save();
  
          $ba=new Bike_Accounts();
          $ba->type='dr';
          $ba->amount=$amount_given_by_days;
          $ba->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
          $ba->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
          $ba->bike_id =$bike_id;
          $ba->rider_id=$rider_id;
          $ba->source='Bike Rent paid to rental comapny';
          $ba->save();
        }
        if ($bike_own=="self") {
          if ($pm!="commission_based") {
            $ra=new Rider_Account();
            $ra->type='cr';
            $ra->amount=$amount_given_by_days;
            $ra->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
            $ra->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ra->bike_rent_id =$bike_id;
            $ra->rider_id=$rider_id;
            $ra->source='Bike Allowns';
            $ra->save();
          }
        }

        $splitted_amount+=$amount_given_by_days;
      }
      $remaining_amount=$total_amount-$splitted_amount;

      if($remaining_amount>0){
        //add this as company expense
        $bike = bike::find($r->bike_id);
        $ce=new Company_Expense();
        $ce->amount=$remaining_amount;
        // $ce->rider_id=$r->rider_id;
        $ce->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ce->description="Bike rent remaining amount against ".$bike->bike_number;
        $ce->save();
      }
      return response()->json([
        'pm'=>$pm,
        'bike_own'=>$bike_own,
      ]);
      // return redirect(route('bike.bike_view'));
      
    }
    // end Bike Rent
    public function deactive_date(Request $request,$rider_id,$assign_bike_id){ 
      $assign_bike=Assign_bike::where("rider_id",$rider_id)->where("id",$assign_bike_id)->get()->first();
    if (isset($assign_bike)) {
        $assign_bike->bike_unassign_date=Carbon::parse($request->bike_unassign_date)->format("Y-m-d");  
        $assign_bike->status='deactive';
        $assign_bike->bike_unassign_date=Carbon::parse($request->bike_unassign_date)->format("Y-m-d");
    }
    $assign_bike->update();
    if (isset($assign_bike->bike_id)) {
      $bikes_availability=bike::find($assign_bike->bike_id);    
      $bikes_availability->availability='yes'; 
    }
    $bikes_availability->update();
      return response()->json([
        'bike_unassign_date' => $assign_bike->bike_unassign_date,
    ]);
    }
    public function give_bike_to_company($bike_id){
      $bike=bike::find($bike_id);
      return view('admin.Bike.assign_bike_to_kingriders_company',compact('bike'));
    }
    public function is_given_bike_status(Request $request,$id){
      $bike=bike::find($id);
      $bike->is_given='0';
      if ($bike->rider_id==null) {
        $bike->rent_amount=$request->monthly_rent;
      }
      else{
        $bike->bike_allowns=$request->bike_allowns;
      }
      return redirect(route('bike.bike_view'));
    } 
    public function insurance_co_name(Request $request){
      $insurance_co_name=new insurance_company();
      $insurance_co_name->insurance_co_name=$request->data;
      $insurance_co_name->save();
      return response()->json([
        'insurance_co_name'=>$insurance_co_name,
      ]);
    }
    public function History_matching_dates($rider_id,$bike_id,$date){

      $bike_history = Assign_bike::all();
      $history_found = Arr::first($bike_history, function ($item, $key) use ($rider_id, $date) {
          $start_created_at =Carbon::parse($item->bike_assign_date)->format('Y-m-d');
          $created_at =Carbon::parse($start_created_at);

          $start_updated_at =Carbon::parse($item->bike_unassign_date)->format('Y-m-d');
          $updated_at =Carbon::parse($start_updated_at);
          $req_date =Carbon::parse($date);
          if ($item->status=="active") {
            return $item->rider_id==$rider_id && ( $req_date->greaterThanOrEqualTo($created_at));
          }
          return $item->rider_id==$rider_id && ( $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->lessThanOrEqualTo($updated_at));
      });
      $history_found2 = Arr::first($bike_history, function ($item, $key) use ($bike_id, $date) {
        $start_created_at =Carbon::parse($item->bike_assign_date)->format('Y-m-d');
        $created_at =Carbon::parse($start_created_at);

        $start_updated_at =Carbon::parse($item->bike_unassign_date)->format('Y-m-d');
        $updated_at =Carbon::parse($start_updated_at);
        $req_date =Carbon::parse($date);
        if ($item->status=="active") {
          return $item->bike_id==$bike_id &&  ( $req_date->greaterThanOrEqualTo($created_at));
        }
        return $item->bike_id==$bike_id && ( $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->lessThanOrEqualTo($updated_at));
    });
      $error=0;
      if (isset($history_found) || isset($history_found2)) {
        $error=1;
      }
      return response()->json([
        'history_found'=>$history_found,
        'history_found2'=>$history_found2,
        'error'=>$error,
      ]);
    }
}
