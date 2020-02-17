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
use App\Model\Accounts\Client_Income;
use App\Model\Accounts\Rider_salary;
use Arr;
use Batch;

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
        $clients_count=Client::where("active_status","A")->where("status","1")->get()->count();
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
        $client->trn_no = $request->trn_no;
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
        $client->trn_no = $request->trn_no;
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
        $client_active_count=$client_history_active->count();
        $client_deactive_count=$client_history->count();
        return view('admin.client.riders', compact('client', 'riders','client_history','client_history_active','client_active_count','client_deactive_count'));
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
    public function import_income(Request $r)
    {
        $client_histories=Client_History::all();
        $data = $r->data;
        
        $income_objs=[];
        $ca_objects=[];
        $ra_objects=[];
        $salaries=[];

        $delete_data=[];
        $ca_delete_data=[];
        $ra_delete_data=[];

        $salaries_updates=[];

        $client_incomes = Client_Income::all(); // r1
        $clients = Client::all(); // r2
        $rider_salaries = Rider_salary::all(); // r3
        $warnings=[];
        $i=0;
        $commission_type='percentage';
        $commission_value=30;
        $commission=0;
        $salary_amount=0;
        

        foreach ($data as $item) {
            $i++;
            if(!isset($item['captain_id']) || $item['captain_id']==null){
                continue; # just skip the iteration..because we don't find any data
            }
            $captain_id=$item['captain_id'];
            $unique_id=uniqid().time().$captain_id;
            $date_to_match=$item['week_start'];
            $month = Carbon::parse($date_to_match)->startOfMonth()->format('Y-m-d');

            # loop to find client history againts this captain id (so we can fetch rider id and client id)
            $history_found = Arr::first($client_histories, function ($iteration, $key) use ($captain_id, $date_to_match) {
                $start_created_at =Carbon::parse($iteration->assign_date)->startOfMonth()->format('Y-m-d');
                $created_at =Carbon::parse($start_created_at);
    
                $start_updated_at =Carbon::parse($iteration->deassign_date)->endOfMonth()->format('Y-m-d');
                $updated_at =Carbon::parse($start_updated_at);
                $req_date =Carbon::parse($date_to_match);
    
                if($iteration->status=='active'){    
                    return $iteration->client_rider_id==$captain_id && 
                    ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
                }
    
                return $iteration->client_rider_id==$captain_id &&
                    ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
            });
            $rider_id=null;
            $client_id=null;
            
            /**
             * =======================================================================
             *              Start validation of the data we received
             * =======================================================================
            */

            if(isset($history_found)){
                # history found - get rider and client id
                $rider_id=$history_found->rider_id;
                $client_id=$history_found->client_id;

                # validate and store the commission
                if($history_found->comission!=null){
                    $commission_data = json_decode($history_found->comission,true);
                    if(isset($commission_data['com_type']) && isset($commission_data['com_amount'])){
                        $commission_type = $commission_data['com_type'];
                        $commission_value = $commission_data['com_amount'];

                        if($commission_type!='percentage' && $commission_type!='fixed'){
                           # Commission type isn't valid (check [com_type] and [com_amount] on client__histories table)
                            $obj = [];
                            $obj['msg']='Invalid commission type against captain id '.$captain_id; 
                            array_push($warnings, $obj);
                            continue; # we no longer need to persue the process 
                        }
                    }
                    else {
                        # Commission isn't valid (check [com_type] and [com_amount] on client__histories table)
                        $obj = [];
                        $obj['msg']='Invalid commission against captain id '.$captain_id; 
                        array_push($warnings, $obj);
                        continue; # we no longer need to persue the process
                    }
                }
                else {
                    # Commission not found
                    $obj = [];
                    $obj['msg']='Commission not found against captain id '.$captain_id; 
                    array_push($warnings, $obj);
                    continue; # we no longer need to persue the process
                }
            }
            else {
                # history not found, we need to show error against this rider
                $obj = [];
                $obj['msg']='No rider found against '.$captain_id.'. Please assign the captain id first'; 
                array_push($warnings, $obj);
                continue; # we no longer need to persue the process
            }
            # now we need to find the client and check if it is commission based
            $client = Arr::first($clients, function ($iteration, $key) use ($client_id) {
                return $iteration->id == $client_id;
            });
            if(isset($client)){
                if($client->setting!=null){
                    $payout_method = json_decode($client->setting, true);
                    $pm = $payout_method['payout_method'];
                    # checking if client is commission based
                    if($pm!='commission_based'){
                        # client isn't commission based
                        $obj = [];
                        $obj['msg']=$client->name.' is not Commission Based, Please set the payout method to commission base.';
                        array_push($warnings, $obj);
                        continue; # we no longer need to persue the process
                    }
                }
                else {
                    # no payout method is defined
                    $obj = [];
                    $obj['msg']='Payout method is undefined against client '.$client->name.'.'; 
                    array_push($warnings, $obj);
                    continue; # we no longer need to persue the process
                }
            }
            else {
                # no client found
                $obj = [];
                $obj['msg']='No client found against client id '.$client_id.'.'; 
                array_push($warnings, $obj);
                continue; # we no longer need to persue the process
            }

            $cash = isset($item['cash_payment'])?abs($item['cash_payment']):null;
            $bank = isset($item['cash_balance'])?$item['cash_balance']:null;
            $total_payout = round($cash+$bank,2);
            ## total payout must be greater than zero
            if($total_payout<=0){
                continue; # we no longer need to persue the process
            }

            /**
             * =======================================================================
             * Adding data started from here - no validations will be apply after this
             * =======================================================================
            */

            # now we need to check if data is already exist - if exist than we delete it
            $already_income = Arr::first($client_incomes, function ($iteration, $key) use ($item) {
                $ws_i = Carbon::parse($iteration->week_start);
                $we_i = Carbon::parse($iteration->week_end);
                $ws_item = Carbon::parse($item['week_start']); 
                $we_item = Carbon::parse($item['week_end']);
                return $iteration->captain_id == $item['captain_id'] &&
                $ws_i->equalTo($ws_item) &&
                $we_i->equalTo($we_item);
            });
            

            if(isset($already_income)){ 
                # data found - deleting it from 3 tables
                //client icnome table
                $objDelete = [];
                $objDelete['id']=$already_income->id; 
                array_push($delete_data, $objDelete);
                //ca (company_account table)
                $objDelete = [];
                $objDelete['client_income_id']=$already_income->acc_id; 
                array_push($ca_delete_data, $objDelete);
                //ra (rider_account table)
                $objDelete = [];
                $objDelete['client_income_id']=$already_income->acc_id; 
                array_push($ra_delete_data, $objDelete);
            }
            #############################################################
            ##      storing the data into client_income table          ##
            #############################################################
        
            $obj = [];
            $obj['client_id']=$client_id;
            $obj['rider_id']=$rider_id;
            $obj['acc_id']=$unique_id;
            $obj['month']=$month;
            $obj['given_date']=Carbon::now()->format('Y-m-d');
            $obj['captain_id']=$captain_id;
            $obj['trips']=isset($item['trips'])?$item['trips']:null;
            $obj['total_payout']=$total_payout;
            $obj['cash']=$cash;
            $obj['bank']=$bank;
            $obj['week_start']=isset($item['week_start'])?Carbon::parse($item['week_start'])->format('Y-m-d'):null;
            $obj['week_end']=isset($item['week_end'])?Carbon::parse($item['week_end'])->format('Y-m-d'):null;
            $obj['income_type']='commission_based';
            $obj['status']=1;
            $obj['created_at']=Carbon::now();
            $obj['updated_at']=Carbon::now();
            array_push($income_objs, $obj);

            #############################################################
            ##  storing data into company account and rider acc table  ##
            #############################################################

            $week_start = Carbon::parse($obj['week_start'])->format('d M');
            $week_end = Carbon::parse($obj['week_end'])->format('d M');
            $client_name = $client->name;
            
            # calculating commission based on commission type and value
            if($commission_type=='percentage'){
                # calculate the percentage of payout
                $commission = ($total_payout/100)*$commission_value;
            }
            else {
                # just duduct the commission value from percentage
                $commission = $commission_value;
            }
            $salary_amount = $total_payout - $commission;

            # =======================Flow====================
            # 1) Payout - RA -cr
            # 2) cash pay from payout - RA -dr
            # 3) transfer company commission to CA - RA - dr
            # 4) receive company commission from RA - CA - cr 
            # =====================End Flow==================

            # adding payout - to RA
            $ra_amt = $total_payout;
            $ra_obj = [];
            $ra_obj['client_income_id']=$unique_id;
            $ra_obj['source']=$client_name.' Weekly Payout<br>('.$week_start.' - '.$week_end.') <br>Cash: '.$cash.'<br>Bank: '.$bank;
            $ra_obj['rider_id']=$rider_id;
            $ra_obj['amount']=$ra_amt;
            $ra_obj['month']=$month;
            $ra_obj['type']='cr';
            $ra_obj['payment_status']='pending';
            $ra_obj['salary_id']=null;
            $ra_obj['given_date']=Carbon::now();
            $ra_obj['created_at']=Carbon::now();
            $ra_obj['updated_at']=Carbon::now();
            array_push($ra_objects, $ra_obj);

            # auto pay cash amount - To RA
            $ra_amt = round($cash,2);
            if($ra_amt>0){
                $ra_obj = [];
                $ra_obj['client_income_id']=$unique_id;
                $ra_obj['source']= $client_name.' Cash';
                $ra_obj['rider_id']=$rider_id;
                $ra_obj['amount']=$ra_amt;
                $ra_obj['month']=$month;
                $ra_obj['type']='dr';
                $ra_obj['payment_status']='paid';
                $ra_obj['salary_id']=null;
                $ra_obj['given_date']=Carbon::now();
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects, $ra_obj);
            }

            # adding commission - to RA
            $ra_amt = round($commission,2);
            if($ra_amt>0){
                $ra_obj = [];
                $ra_obj['client_income_id']=$unique_id;
                $ra_obj['source']= 'Kingrider Weekly Commission <br>('.$week_start.' - '.$week_end.')';
                $ra_obj['rider_id']=$rider_id;
                $ra_obj['amount']=$ra_amt;
                $ra_obj['month']=$month;
                $ra_obj['type']='dr';
                $ra_obj['payment_status']='pending';
                $ra_obj['salary_id']=null;
                $ra_obj['given_date']=Carbon::now();
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects, $ra_obj);
            }

            # adding commission - to CA profit
            $ca_amt = round($commission,2);
            if($ca_amt>0){
                $ca_obj = [];
                $ca_obj['client_income_id']=$unique_id;
                $ca_obj['source']= 'Weekly Commission ('.$week_start.' - '.$week_end.') <br>Cash: '.$cash.'<br>Bank: '.$bank;
                $ca_obj['rider_id']=$rider_id;
                $ca_obj['amount']=$ca_amt;
                $ca_obj['month']=$month;
                $ca_obj['type']='cr';
                $ca_obj['salary_id']=null;
                $ca_obj['given_date']=Carbon::now();
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects, $ca_obj);
            }

            if(isset($already_income)){ 

                $rs= Arr::first($rider_salaries, function ($iteration, $key) use ($already_income) {
                    $rs_i = Carbon::parse($iteration->month);
                    $rs_item = Carbon::parse($already_income->month);
                    return $iteration->rider_id == $already_income->rider_id &&
                    $rs_i->equalTo($rs_item) &&
                    $iteration->settings==$already_income->acc_id;
                });
                if (isset($rs)) {
                    #salary found - update salary to rider salaries table
                    $rs_amt = round($salary_amount,2);
                    if($rs_amt>0){
                        $rs_obj = [];
                        $rs_obj['id']=$rs->id;
                        $rs_obj['total_salary'] = $rs_amt;
                        $rs_obj['gross_salary']=$rs_amt;
                        $rs_obj['rider_id']=$rider_id;
                        $rs_obj['month']=$month;
                        $rs_obj['paid_by']=Auth::user()->id;
                        $rs_obj['settings'] = $unique_id;
                        $rs_obj['created_at']=Carbon::now();
                        $rs_obj['updated_at']=Carbon::now();
                        array_push($salaries_updates, $rs_obj);
                    }

                    
                }
                else {
                    #adding salary to rider salaries table
                    $rs_amt = round($salary_amount,2);
                    if($rs_amt>0){
                        $rs_obj = [];
                        $rs_obj['total_salary'] = $rs_amt;
                        $rs_obj['gross_salary']=$rs_amt;
                        $rs_obj['rider_id']=$rider_id;
                        $rs_obj['month']=$month;
                        $rs_obj['paid_by']=Auth::user()->id;
                        $rs_obj['settings'] = $unique_id;
                        $rs_obj['created_at']=Carbon::now();
                        $rs_obj['updated_at']=Carbon::now();
                        array_push($salaries, $rs_obj);
                    }
                }
            }
            else {
                #adding salary to rider salaries table
                $rs_amt = round($salary_amount,2);
                if($rs_amt>0){
                    $rs_obj = [];
                    $rs_obj['total_salary'] = $rs_amt;
                    $rs_obj['gross_salary']=$rs_amt;
                    $rs_obj['rider_id']=$rider_id;
                    $rs_obj['month']=$month;
                    $rs_obj['paid_by']=Auth::user()->id;
                    $rs_obj['settings'] = $unique_id;
                    $rs_obj['created_at']=Carbon::now();
                    $rs_obj['updated_at']=Carbon::now();
                    array_push($salaries, $rs_obj);
                }
            }
        }

        


        $ci_deletes = DB::table('client__incomes')
                    ->whereIn('id', $delete_data)
                    ->delete();

        $ca_deletes = DB::table('company__accounts')
                        ->whereIn('client_income_id', $ca_delete_data)
                        ->delete();
        $ra_deletes = DB::table('rider__accounts')
                        ->whereIn('client_income_id', $ra_delete_data)
                        ->delete();

        // $salaries_deletes = DB::table('rider_salaries')
        // ->whereNotNull('settings')
        // ->whereIn('settings', $delete_salaries)
        // ->delete();
        

       
        DB::table('client__incomes')->insert($income_objs); //r2

        DB::table('rider_salaries')->insert($salaries); //r2
        $su = Batch::update(new Rider_salary, $salaries_updates, 'id'); //r5 

        $last_insertedids=[];
        foreach ($salaries as $salary) {
            # code...
            $obj=[];
            $obj['settings']=$salary['settings'];
            array_push($last_insertedids,$obj);
        }
        foreach ($salaries_updates as $salary) {
            # code...
            $obj=[];
            $obj['settings']=$salary['settings'];
            array_push($last_insertedids,$obj);
        }
        # we need to update salary id in ca and ra
        $salaries_justCreated = DB::table('rider_salaries')
        ->whereNotNull('settings')
        ->whereIn('settings', $last_insertedids)
        ->get();

        
        foreach ($salaries_justCreated as $salary) {
            # storing the salary_id one by one to company accounts
            foreach ($ca_objects as $ca_key=>$ca_value) {
                # code...
                if($ca_value['client_income_id']==$salary->settings && strpos($ca_value['source'], 'Weekly Payout')!==false){
                    $ca_objects[$ca_key]['salary_id'] = $salary->id;
                }
            }
            # storing the salary_id one by one to rider accounts
            foreach ($ra_objects as $ra_key=>$ra_value) {
                # code...
                if(strpos($ra_value['source'], 'Weekly Payout')!==false){
                    if($ra_value['client_income_id']==$salary->settings){
                        $ra_objects[$ra_key]['salary_id'] = $salary->id;
                    }
                }
            }
        } 
        
        DB::table('company__accounts')->insert($ca_objects); //r4
        DB::table('rider__accounts')->insert($ra_objects); //r4
        

        return response()->json([
            'income_objs'=>$income_objs,
            'ra'=>$ra_objects,
            'ca'=>$ca_objects,
            'salaries'=>$salaries,

            'data_d'=>$delete_data,
            'ra_d'=>$ca_delete_data,
            'ca_d'=>$ra_delete_data,
            'salaries_u'=>$salaries_updates,

            'salary_updates'=>$su,

            'i'=>$last_insertedids,
            'warnings'=>$warnings
        ]);

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
            $settings['fb__working_days']=$r->fb__working_days;
            $settings['fb__perdayHours']=$r->fb__perdayHours;
            break;
        case 'commission_based':
            ### No settings yet
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
public function assignMultipleClients($rider_id){
    $rider=Rider::find($rider_id);
    $clients=Client::where("status","1")->get();
    return view('admin.client.assignMultipleClients',compact('rider','clients'));
}
public function insert_multiple_clients(Request $request,$rider_id){
    $data=$request->client_data;
    foreach ($data as $value) {
        $client_histories=new Client_History;
        $client_histories->client_id=$value['client_id'];
        $client_histories->rider_id=$rider_id;
        $client_histories->assign_date=Carbon::parse($value['assign_date'])->format('Y-m-d');
        $client_histories->deassign_date=Carbon::parse($value['assign_date'])->format('Y-m-d');
        $client_histories->status="active";
        $client_histories->active_status="A";
        $client_histories->save();
    }
    return redirect(route('admin.clients.index'));
}
}