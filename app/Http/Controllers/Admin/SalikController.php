<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Model\Rider\Trip_Detail; 
use Batch;
use App\Assign_bike;
use Illuminate\Support\Arr;
use App\Model\Bikes\bike;
use App\Model\Accounts\Company_Account;
use App\Model\Accounts\Rider_Account;
use App\Model\Rider\Rider;
use App\Model\Rider\Rider_detail;
use App\Model\Sim\Sim_History;
use Carbon\Carbon;
use App\Model\Accounts\Income_zomato;
use App\Export_data;

class SalikController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    } 
    public function import_salik_data(){
        return view('admin.rider.view_salik');
    }
    public function import_Salik(Request $r)
    {
        $bike=bike::all();
        $assign_bike=Assign_bike::all();
        $rider_details = Rider_detail::all();
        $data = $r->data;
        $distinct_data = [];
        $distincts_data_more = [];
        
        $trip_objects=[];
        $ca_objects=[];
        $ra_objects=[];

        $delete_data=[];
        $ca_delete_data=[];
        $ra_delete_data=[];

        $zp = Trip_Detail::all(); // r1
        $company_accounts=[];
        $i=0;
        $unique_id=uniqid().'-'.time();

        foreach ($data as $item) {
            $i++;
            $zp_found = Arr::first($zp, function ($item_zp, $key) use ($item) {
                return $item_zp->transaction_id == $item['transaction_id'];
            });
            $bike_found = Arr::first($bike, function ($item_zp, $key) use ($item) {
                return $item_zp->bike_number == $item['plate'];
            });
            if(isset($bike_found)){
                $assign_bike_found = Arr::first($assign_bike, function ($item_zp, $key) use ($bike_found) {
                    return $item_zp->bike_id =="29" && $item_zp->status =="active";
                });
            }
            
            $rider_id=null;
            if(isset($assign_bike_found)){
                $rider_id=$assign_bike_found->rider_id;
            }
            if(isset($zp_found)){ 
                //delete this
                //salik table
                $objDelete = [];
                $objDelete['id']=$zp_found->id; 
                array_push($delete_data, $objDelete);
                //ca
                $objDelete = [];
                $objDelete['salik_id']=$zp_found->transaction_id; 
                array_push($ca_delete_data, $objDelete);
                //ra
                $objDelete = [];
                $objDelete['salik_id']=$zp_found->transaction_id; 
                array_push($ra_delete_data, $objDelete);
            }
            $obj = [];
            $obj['import_id']=$unique_id;
            $obj['rider_id']=$rider_id;
            $obj['transaction_id']=isset($item['transaction_id'])?$item['transaction_id']:null;
            $obj['toll_gate']=isset($item['toll_gate'])?$item['toll_gate']:null;
            $obj['direction']=isset($item['direction'])?$item['direction']:null;
            $obj['tag_number']=isset($item['tag_number'])?$item['tag_number']:null;
            $obj['plate']=isset($item['plate'])?$item['plate']:null; 
            $obj['amount_aed']=isset($item['amount_aed'])?$item['amount_aed']:null;
            $obj['trip_date']=isset($item['trip_date'])?Carbon::parse($item['trip_date'])->format('Y-m-d'):null;
            $obj['trip_time']=isset($item['trip_time'])?$item['trip_time']:null;
            $obj['transaction_post_date']=isset($item['transaction_post_date'])?Carbon::parse($item['transaction_post_date'])->format('Y-m-d'):null;
            array_push($trip_objects, $obj);
        }

        //fetching rider id against each plate
        $j=0;
        foreach ($data as $item) {
            $j++;
            if(trim($item['transaction_id']) == '') continue;
            $bike_plate = $item['plate'];
            $bike_found = Arr::first($bike, function ($item_zp, $key) use ($item) {
                return $item_zp->bike_number == $item['plate'] && $item_zp->active_status=='A';
            }); 
            $rider_id = null;
            if(isset($bike_found)){
                $bike_id = $bike_found['id'];
                $date = $item['trip_date'];
                $history_found = Arr::first($assign_bike, function ($item, $key) use ($bike_id, $date) { 
                    $created_at =Carbon::parse($item->bike_assign_date)->format('Y-m-d');
                    $created_at =Carbon::parse($created_at);
        
                    $updated_at =Carbon::parse($item->bike_unassign_date)->format('Y-m-d');
                    $updated_at =Carbon::parse($updated_at);
                    $req_date =Carbon::parse($date);
                    if($item->status=="active"){ 
                        // mean its still active, we need to match only created at
                        return $item->bike_id == $bike_id && $req_date->greaterThanOrEqualTo($created_at);
                    }
                    
                    return $item->bike_id == $bike_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
                });
                if (isset($history_found)) {
                    $rider_id=$history_found->rider_id;
                }
            }
            $obj = [];
            $obj['plate'] = $item['plate'];
            $obj['transaction_id'] = $item['transaction_id'];
            $obj['trip_date'] = isset($item['trip_date'])?Carbon::parse($item['trip_date'])->format('Y-m-d'):null;
            $obj['rider_id'] = $rider_id;
            $obj['amount_aed'] = $item['amount_aed'];
            array_push($distincts_data_more, $obj);
        }
        
        //adding amount to same plate and rider id (sum amount)
        $k=0;
        foreach ($distincts_data_more as $item) {
            $k++;
            if(trim($item['transaction_id']) == '') continue;
            $key_found = '';
            $zp_found = Arr::first($distinct_data, function ($item_zp, $key) use ($item, &$key_found) {
                $key_found = $key;
                return $item_zp['plate'] == $item['plate'] && $item_zp['rider_id'] == $item['rider_id'];
            });

            if(isset($zp_found)){
                $distinct_data[$key_found]['amount_aed'] += $item['amount_aed'];
            }
            else {
                
                $obj = [];
                $obj['plate'] = $item['plate'];
                $obj['transaction_id'] = $item['transaction_id'];
                $obj['trip_date'] = $item['trip_date'];
                $obj['rider_id'] = $item['rider_id'];
                $obj['amount_aed'] = $item['amount_aed'];
                array_push($distinct_data, $obj);
            }
 
        }

        //adding data to CR and Rider account
        $m=0;
        foreach ($distinct_data as $distinct_item) {
            $m++;
            if ($distinct_item['rider_id']==null) {
                continue;
            }
            $rider_detail_found = Arr::first($rider_details, function ($item_zp, $key) use ($distinct_item) {
                return $item_zp->rider_id == $distinct_item['rider_id'];
            });
            $max_salik = 50;
            if(isset($rider_detail_found)){
                $max_salik = $rider_detail_found['salik_amount']; 
            }
            $amount = $distinct_item['amount_aed'];
            $add_amount = 0;
            $ca_obj = [];
            $ca_obj['salik_id']=$distinct_item['transaction_id'];
            $ca_obj['source']='Salik';
            $ca_obj['amount']=$amount;
            $ca_obj['rider_id']=$distinct_item['rider_id'];
            $ca_obj['type']='cr';
            $ca_obj['month']=Carbon::parse($distinct_item['trip_date'])->startOfMonth()->format("Y-m-d");
            $ca_obj['given_date']=Carbon::now()->format("Y-m-d");
            $ca_obj['created_at']=Carbon::now();
            $ca_obj['updated_at']=Carbon::now();
            array_push($ca_objects, $ca_obj);

            $ca_obj = [];
            $ca_obj['salik_id']=$distinct_item['transaction_id'];
            $ca_obj['source']='Salik';
            $ca_obj['amount']=$amount;
            $ca_obj['rider_id']=$distinct_item['rider_id'];
            $ca_obj['type']='dr';
            $ca_obj['month']=Carbon::parse($distinct_item['trip_date'])->startOfMonth()->format("Y-m-d");
            $ca_obj['given_date']=Carbon::now()->format("Y-m-d");
            $ca_obj['created_at']=Carbon::now();
            $ca_obj['updated_at']=Carbon::now();
            array_push($ca_objects, $ca_obj);

            $ra_obj = [];
            $ra_obj['salik_id']=$distinct_item['transaction_id'];
            $ra_obj['source']='Salik';
            $ra_obj['amount']=$amount;
            $ra_obj['rider_id']=$distinct_item['rider_id'];
            $ra_obj['type']='cr_payable';
            $ra_obj['month']=Carbon::parse($distinct_item['trip_date'])->startOfMonth()->format("Y-m-d");
            $ra_obj['given_date']=Carbon::now()->format("Y-m-d");
            $ra_obj['created_at']=Carbon::now();
            $ra_obj['updated_at']=Carbon::now();
            array_push($ra_objects, $ra_obj);
            
        }


        $iz_deletes = DB::table('trip__details')
                    ->whereIn('id', $delete_data)
                    ->delete();

        $ca_deletes = DB::table('company__accounts')
                        ->whereIn('salik_id', $ca_delete_data)
                        ->delete();
        $ra_deletes = DB::table('rider__accounts')
                        ->whereIn('salik_id', $ra_delete_data)
                        ->delete();

       
        DB::table('trip__details')->insert($trip_objects); //r2
        DB::table('company__accounts')->insert($ca_objects); //r4
        DB::table('rider__accounts')->insert($ra_objects); //r4  

        return response()->json([
            'data'=>$distinct_data,
            'ra'=>$ra_objects,
            'ca'=>$ca_objects,

            'data_d'=>$delete_data,
            'ra_d'=>$ca_delete_data,
            'ca_d'=>$ra_delete_data,

            'i'=>$i,
            'j'=>$j,
            'k'=>$k,
            'm'=>$m,

            'test'=>$distincts_data_more
        ]);

    }
    public function fuel_emarat_excel(Request $r)
    {
        $bike=bike::all();
        $assign_bike=Assign_bike::all();
        $rider_details = Rider_detail::all();
        $data = $r->data;
        $distinct_data = [];
        $distincts_data_more = [];
        
        $trip_objects=[];
        $ca_objects=[];
        $ca_objects_updates=[];

        $ra_objects=[];
        $ra_objects_updates=[];

        $zp = Trip_Detail::all(); // r1
        $update_data = [];
        $company_accounts=[];
        $i=0;
        $unique_id=uniqid().'-'.time();

        //fetching rider id against each plate
        foreach ($data as $item) {
            // if(trim($item['transaction_id']) == '') continue;
            $bike_plate = $item['token'];
            $bike_found = Arr::first($bike, function ($item_zp, $key) use ($item) {
                return $item_zp->bike_number == $item['token'] && $item_zp->active_status=='A';
            }); 
            $rider_id = null;
            if(isset($bike_found)){
                $bike_id = $bike_found['id'];
                $date = $item['transaction_date'];
                $history_found = Arr::first($assign_bike, function ($item, $key) use ($bike_id, $date) { 
                    $created_at =Carbon::parse($item->bike_assign_date)->format('Y-m-d');
                    $created_at =Carbon::parse($created_at);
        
                    $updated_at =Carbon::parse($item->bike_unassign_date)->format('Y-m-d');
                    $updated_at =Carbon::parse($updated_at);
                    $req_date =Carbon::createFromFormat('d/m/Y H:i',$date);
                    if($item->status=="active"){ 
                        // mean its still active, we need to match only created at
                        return $item->bike_id == $bike_id && $req_date->greaterThanOrEqualTo($created_at);
                    }
                    
                    return $item->bike_id == $bike_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
                });
                if (isset($history_found)) {
                    $rider_id=$history_found->rider_id;
                }
            }
            $obj = [];
            $obj['plate'] = $item['token'];
            // $obj['transaction_id'] = $item['transaction_id'];
            $obj['transaction_date'] = isset($item['transaction_date'])?Carbon::createFromFormat('d/m/Y H:i',$item['transaction_date'])->format('Y-m-d'):null;
            // $obj['rider_id'] = $rider_id;
            $obj['total_amount__aed'] = $item['total_amount__aed'];
            array_push($distincts_data_more, $obj);
        }
        
        //adding amount to same plate and rider id (sum amount)
        foreach ($distincts_data_more as $item) {
            // if(trim($item['transaction_id']) == '') continue;
            $key_found = '';
            $zp_found = Arr::first($distinct_data, function ($item_zp, $key) use ($item, &$key_found) {
                $key_found = $key;
                return $item_zp['plate'] == $item['plate'];
            });

            if(isset($zp_found)){
                $distinct_data[$key_found]['total_amount__aed'] += round($item['total_amount__aed'],2);
            }
            else {
                
                $obj = [];
                $obj['plate'] = $item['plate'];
                // $obj['transaction_id'] = $item['transaction_id'];
                $obj['transaction_date'] = $item['transaction_date'];
                // $obj['rider_id'] = $item['rider_id'];
                $obj['total_amount__aed'] = round($item['total_amount__aed'],2);
                array_push($distinct_data, $obj);
            }
 
        }
        return response()->json([
            'data'=>$distinct_data,
        ]);

    }
    public function delete_lastImportSalik(){
        $import_id=Trip_Detail::all()->last()->import_id;
        $performances=Trip_Detail::where('import_id',$import_id)->get();
        $ca_deletes=[];
        $ra_deletes=[];
        $deletes = [];
        foreach($performances as $performance)
        {
            //salik
            $objDelete = [];
            $objDelete['id']=$performance->id;
            array_push($deletes, $objDelete);
            //ca
            $objDelete = [];
            $objDelete['salik_id']=$performance->transaction_id;
            array_push($ca_deletes, $objDelete);
            //ra
            $objDelete = [];
            $objDelete['salik_id']=$performance->transaction_id; 
            array_push($ra_deletes, $objDelete);
        }
        $salik_deletes = DB::table('trip__details')
                    ->whereIn('id', $deletes)
                    ->delete();

        $ca_delete_data = DB::table('company__accounts')
                        ->whereIn('salik_id', $ca_deletes)
                        ->delete();
        $ra_delete_data = DB::table('rider__accounts')
                        ->whereIn('salik_id', $ra_deletes)
                        ->delete();
        return response()->json([
            'salik'=>$deletes,
            'ca'=>$ca_deletes,
            'ra'=>$ra_deletes,
            'import_id'=>$import_id,
            'salik_count'=>$salik_deletes,
            'ca_count'=>$ca_delete_data,
            'ra_count'=>$ra_delete_data
        ]);

    }

    public function bike_salik($id){
        $bike=bike::find($id);
        return view('admin.Bike.salik_bike',compact('bike')); 
    }
    public function rider_salik($id){
        $rider=Rider::find($id);
        $assign_bike=$rider->Assign_bike()->get()->first();
        $bike=bike::find($assign_bike->bike_id);
        return view('admin.rider.salik_rider',compact('bike'));
    }
    public function add_salik(){
        $bikes=bike::where("active_status","A")->get();
        return view('admin.rider.add_salik',compact('bikes'));
    }
    public function store_salik(Request $request,$id){

        $bike=bike::find($id);
        
        $bike_history = Assign_bike::all();
        $bike_id = $bike->id;
        $date = $request->month;
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
        $rider_id=null;

         if (isset($history_found)) {
            $rider_id=$history_found->rider_id;
         }else {
             $assign_bikeBK = Assign_bike::where('bike_id', $bike_id)
             ->where('status', 'active')->get()->first();
             if(isset($assign_bikeBK)){
                $rider_id=$assign_bikeBK->rider_id;
             }
         }

         $rider=Rider::find($rider_id);
      $rider_detail=$rider->Rider_detail;
      $salik_amount=$rider_detail->salik_amount;


        return response()->json([
            'salik_amount'=>$salik_amount,
            'rider_id'=>$rider,
        ]);
    }
    public function get_active_riders_ajax_salik($_id, $date, $according_to=null){
        $bike_history = Assign_bike::all();
        $bike_histories = null;
        $history_found = Arr::first($bike_history, function ($item, $key) use ($_id, $date, $according_to) {
            $start_created_at =Carbon::parse($item->bike_assign_date)->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);

            $start_updated_at =Carbon::parse($item->bike_unassign_date)->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($date);
            
            if($item->status=='active'){
                if($according_to=='bike'){
                    return $item->bike_id==$_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
                }
                return $item->rider_id==$_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
            }
            if($according_to=='bike'){
                return $item->bike_id==$_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
            }
            return $item->rider_id==$_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });

        $salik_amount=0;
        if(isset($history_found)){
            $bike_histories = $history_found;
            $rider = Rider::find($bike_histories->rider_id);
            $salik_amount=$rider->Rider_Detail->salik_amount;
        }
        
        return response()->json([
            'bike_histories' => $bike_histories,
            'salik_amount' => $salik_amount
        ]);
    }

    /* ===Get bike according to rider and rider according to bike=== */
    public function get_active_bikes_ajax_salik($_id, $date,$according_to){
       
        $bike_history = Assign_bike::with('Rider')->with('bike')->get()->toArray();
        
        $bike_histories = null;
        $history_found = Arr::where($bike_history, function ($item, $key) use ($_id, $date,$according_to) {
            $start_created_at =Carbon::parse($item['bike_assign_date'])->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);

            $start_updated_at =Carbon::parse($item['bike_unassign_date'])->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($date);
            
            if($item['status']=='active'){
                if($according_to=='bike'){
                    return $item['bike_id']==$_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
                }
                return $item['rider_id']==$_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
            }
            if($according_to=='bike'){
                return $item['bike_id']==$_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
            }
            return $item['rider_id']==$_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });
        if(isset($history_found)){
            

            ### check if self bike, we need to deduct absent days
            foreach ($history_found as $key=>$bike_history) {
                $assign_date = $bike_history['bike_assign_date'];
                $unassign_date = $bike_history['bike_unassign_date'];
                if($bike_history['bike']['owner']=='self'){
                    $timesheet_data=Income_zomato::where('rider_id', $bike_history['rider_id'])
                    ->whereDate('date', $date)
                    ->get()
                    ->first();
                    if(isset($timesheet_data)){
                        $timesheet_data=$timesheet_data->Time_sheet()
                        ->where('off_days_status', 'absent')
                        ->get();
                        $absent_days=[];
                        foreach ($timesheet_data as $timesheet) {
                            $obj=[];
                            $obj['date']=$timesheet->date;
                            array_push($absent_days, $obj);
                        }
                        $history_found[$key]['absent_days']=$absent_days;
                    }
                    
                }
            } 
            // $bike_history['c']=123;
            $bike_histories = $history_found;
        }
        // else {
        //     $to_find = $according_to=='bike'?'bike_id':'rider_id';
        //     $assign_bikeBK = Assign_bike::where($to_find , $_id)
        //     ->where('status', 'active')->get()->first();
        //     if(isset($assign_bikeBK)){
        //        $bike_histories = $assign_bikeBK;
        //     }
        // }
        // $bike_id=$bike_histories->bike_id;
        // if (isset($bike_id)) {
        //     $bike=bike::find($bike_id);
        //     if(isset($bike)){
        //         $owner=$bike->owner;
        //     }
        //     $absent_days=0;
        //     $weekly_off=0;
        //     $extra_day=0;
        //     $working_days=0;
        //     $total_month_days=0;
        //     $income_zomato=Income_zomato::where("rider_id",$bike_histories->rider_id)
        //     ->whereMonth("date",Carbon::parse($date)->format("m"))
        //     ->get()
        //     ->first();
        //     if (isset($income_zomato)) {
        //         $absent_days=$income_zomato->absents_count;
        //         $weekly_off=$income_zomato->weekly_off;
        //         $extra_day=$income_zomato->extra_day;
        //         $working_days=$income_zomato->working_days;
        //         $total_month_days=$date;
        //     }
        // }
        return response()->json([
            'bike_histories' => $bike_histories,
            // 'owner'=>$owner,
            // 'absent_days'=>$absent_days,
            // 'weekly_off'=>$weekly_off,
            // 'extra_day'=>$extra_day,
            // 'working_days'=>$working_days,
            // 'total_month_days'=>$total_month_days,
        ]);
    }

    /* ===Get sim according to rider and rider according to sim=== */
    public function get_active_sims_ajax_salik($_id, $date,$according_to){
        $sim_history = Sim_History::with('Rider')->with('Sim')->get()->toArray();;
        $sim_histories = null;
        $history_found = Arr::where($sim_history, function ($item, $key) use ($_id, $date,$according_to) {
            $start_created_at =Carbon::parse($item['given_date'])->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);

            $start_updated_at =Carbon::parse($item['return_date'])->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($date);
            
            if($item['status']=='active'){
                if($according_to=='sim'){
                    return $item['sim_id']==$_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
                }
                return $item['rider_id']==$_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
            }
            if($according_to=='sim'){
                return $item['sim_id']==$_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
            }
            return $item['rider_id']==$_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });

        if(isset($history_found)){
            $sim_histories = $history_found;
        }
        return response()->json([
            'sim_histories' => $sim_histories,
        ]);
    }
    public function insert_salik(Request $request){
        $used_salik= $request->amount;
        
        $bike=bike::find($request->bike_id);

        $_id = $bike->id;
        $date = $request->month;
        $bike_history = Assign_bike::all();
        $bike_histories = null;
        $history_found = Arr::first($bike_history, function ($item, $key) use ($_id, $date ) {
            $start_created_at =Carbon::parse($item->bike_assign_date)->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);

            $start_updated_at =Carbon::parse($item->bike_unassign_date)->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($date);
            
            if($item->status=='active'){
                return $item->bike_id==$_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
            }
           return $item->bike_id==$_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });

        $allow_salik=0;
        if(isset($history_found)){
            $bike_histories = $history_found;
            $rider = Rider::find($bike_histories->rider_id);
            $rider_id=$rider->id;
            $allow_salik=$rider->Rider_Detail->salik_amount;

            $ed =new Export_data;
            $ed->type='dr';
            $ed->rider_id=$rider_id;
            $ed->amount=$used_salik;
            $ed->month = Carbon::parse($request->month)->startOfMonth()->format('Y-m-d');
            $ed->given_date = Carbon::parse($request->given_date)->format('Y-m-d');
            $ed->source="Salik";
            $ed->source_id='0';
            $ed->save();  
            
        if($used_salik>$allow_salik){
            $_greater_ca= new Company_Account;
            $_greater_ca->source="Salik";
            $_greater_ca->salik_id="0";
            $_greater_ca->amount=$used_salik;
            $_greater_ca->rider_id=$rider_id;
            $_greater_ca->month=Carbon::parse($request->month)->startOfMonth()->format("Y-m-d");
            $_greater_ca->given_date=Carbon::parse($request->given_date)->format("Y-m-d");
            $_greater_ca->type="dr";
            $_greater_ca->save();

            $_greater_ra= new Rider_Account;
            $_greater_ra->source="Salik";
            $_greater_ra->salik_id="0";
            $_greater_ra->amount=$used_salik-$allow_salik;
            $_greater_ra->rider_id=$rider_id;
            $_greater_ra->month=Carbon::parse($request->month)->startOfMonth()->format("Y-m-d");
            $_greater_ra->given_date=Carbon::parse($request->given_date)->format("Y-m-d");
            $_greater_ra->type="cr_payable";
            $_greater_ra->save();

            $_greater_ca= new Company_Account;
            $_greater_ca->source="Salik Extra";
            $_greater_ca->salik_id="0";
            $_greater_ca->amount=$used_salik-$allow_salik;
            $_greater_ca->rider_id=$rider_id;
            $_greater_ca->month=Carbon::parse($request->month)->startOfMonth()->format("Y-m-d");
            $_greater_ca->given_date=Carbon::parse($request->given_date)->format("Y-m-d");
            $_greater_ca->type="cr";
            $_greater_ca->save();
        }
        else{
            $ca= new Company_Account;
            $ca->source="Salik";
            $ca->amount=$used_salik;
            $ca->salik_id="0";
            $ca->rider_id=$rider_id;
            $ca->month=Carbon::parse($request->month)->startOfMonth()->format("Y-m-d");
            $ca->given_date=Carbon::parse($request->given_date)->format("Y-m-d");
            $ca->type="dr";
            $ca->save();
            // $ca= new Company_Account;
            // $ca->source="Salik";
            // $ca->amount=$allow_salik;
            // $ca->salik_id="0";
            // $ca->rider_id=$rider_id;
            // $ca->month=Carbon::parse($request->month)->format("Y-m-d");
            // $ca->type="dr";
            // $ca->save();
            // if($used_salik<$allow_salik){
            //     $_less_ra= new Rider_Account;
            //     $_less_ra->source="Salik";
            //     $_less_ra->salik_id="0";
            //     $_less_ra->amount=$allow_salik-$used_salik;
            //     $_less_ra->rider_id=$rider_id;
            //     $_less_ra->month=Carbon::parse($request->month)->format("Y-m-d");
            //     $_less_ra->type="cr";
            //     $_less_ra->save();
            // }
        }
          
            
        return redirect(route('admin.salik'));
    }

}
}