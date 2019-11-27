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
        $ca_objects_updates=[];

        $ra_objects=[];
        $ra_objects_updates=[];

        $zp = Trip_Detail::all(); // r1
        $update_data = [];
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
            if(!isset($zp_found)){
                $obj = [];
                $obj['import_id']=$unique_id;
                $obj['rider_id']=$rider_id;
                $obj['transaction_id']=isset($item['transaction_id'])?$item['transaction_id']:null;
                $obj['toll_gate']=isset($item['toll_gate'])?$item['toll_gate']:null;
                $obj['direction']=isset($item['direction'])?$item['direction']:null;
                $obj['tag_number']=isset($item['tag_number'])?$item['tag_number']:null;
                $obj['plate']=isset($item['plate'])?$item['plate']:null; 
                $obj['amount_aed']=isset($item['amount_aed'])?$item['amount_aed']:null;
                $obj['trip_date']=isset($item['trip_date'])?$item['trip_date']:null;
                $obj['trip_time']=isset($item['trip_time'])?$item['trip_time']:null;
                $obj['transaction_post_date']=isset($item['transaction_post_date'])?$item['transaction_post_date']:null;
                array_push($trip_objects, $obj);
            }
            else{
                $objUpdate = [];
                $objUpdate['id']=$zp_found->id;
                $objUpdate['import_id']=$unique_id; 
                $objUpdate['transaction_id']=isset($item['transaction_id'])?$item['transaction_id']:null;
                $objUpdate['toll_gate']=isset($item['toll_gate'])?$item['toll_gate']:null;
                $objUpdate['direction']=isset($item['direction'])?$item['direction']:null;
                $objUpdate['tag_number']=isset($item['tag_number'])?$item['tag_number']:null;
                $objUpdate['plate']=isset($item['plate'])?$item['plate']:null; 
                $objUpdate['amount_aed']=isset($item['amount_aed'])?$item['amount_aed']:null;
                $objUpdate['trip_date']=isset($item['trip_date'])?$item['trip_date']:null;
                $objUpdate['trip_time']=isset($item['trip_time'])?$item['trip_time']:null;
                $objUpdate['transaction_post_date']=isset($item['transaction_post_date'])?$item['transaction_post_date']:null;
                array_push($update_data, $objUpdate);

            }
        }

        foreach ($data as $item) {
            if(trim($item['transaction_id']) == '') continue;
            $bike_plate = $item['plate'];
                $bike_found = Arr::first($bike, function ($item_zp, $key) use ($item) {
                    return $item_zp->bike_number == $item['plate'];
                }); 
                $rider_id = null;
                if(isset($bike_found)){
                    $bike_id = $bike_found['id'];
                    $date = $item['trip_date'];
                    $history_found = Arr::first($assign_bike, function ($item, $key) use ($bike_id, $date) {
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
                    if (isset($history_found)) {
                        $rider_id=$history_found->rider_id;
                    }
                }
                $obj = [];
                $obj['plate'] = $item['plate'];
                $obj['transaction_id'] = $item['transaction_id'];
                $obj['trip_date'] = $item['trip_date'];
                $obj['rider_id'] = $rider_id;
                $obj['amount_aed'] = $item['amount_aed'];
                array_push($distincts_data_more, $obj);
            }
 


        foreach ($distincts_data_more as $item) {
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

        foreach ($distinct_data as $distinct_item) {
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
            if($amount > $max_salik){
                $ca_obj = [];
                $ca_obj['salik_id']=$distinct_item['transaction_id'];
                $ca_obj['source']='Salik';
                $ca_obj['amount']=$amount;
                $ca_obj['rider_id']=$distinct_item['rider_id'];
                $ca_obj['type']='dr';
                $ca_obj['month']=Carbon::parse($distinct_item['trip_date'])->format("Y-m-d");
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects, $ca_obj);

                $ra_obj = [];
                $ra_obj['salik_id']=$distinct_item['transaction_id'];
                $ra_obj['source']='Salik';
                $ra_obj['amount']=$amount-$max_salik;
                $ra_obj['rider_id']=$distinct_item['rider_id'];
                $ra_obj['type']='cr_payable';
                $ra_obj['month']=Carbon::parse($distinct_item['trip_date'])->format("Y-m-d");
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects, $ra_obj);

                $ca_obj = [];
                $ca_obj['salik_id']=$distinct_item['transaction_id'];
                $ca_obj['source']='Salik Extra';
                $ca_obj['amount']=$amount-$max_salik;
                $ca_obj['rider_id']=$distinct_item['rider_id'];
                $ca_obj['type']='cr';
                $ca_obj['month']=Carbon::parse($distinct_item['trip_date'])->format("Y-m-d");
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects, $ca_obj);
                
            }
            else{
                $ca_obj = [];
                $ca_obj['salik_id']=$distinct_item['transaction_id'];
                $ca_obj['source']='Salik';
                $ca_obj['amount']=$amount;
                $ca_obj['rider_id']=$distinct_item['rider_id'];
                $ca_obj['type']='dr';
                $ca_obj['month']=Carbon::parse($distinct_item['trip_date'])->format("Y-m-d");
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects, $ca_obj);
                // $ca_obj = [];
                // $ca_obj['salik_id']=$distinct_item['transaction_id'];
                // $ca_obj['source']='Salik';
                // $ca_obj['amount']=$max_salik;
                // $ca_obj['rider_id']=$distinct_item['rider_id'];
                // $ca_obj['type']='dr';
                // $ca_obj['month']=Carbon::parse($distinct_item['trip_date'])->format("Y-m-d");
                // $ca_obj['created_at']=Carbon::now();
                // $ca_obj['updated_at']=Carbon::now();
                // array_push($ca_objects, $ca_obj);
                // if($amount<$max_salik){
                //     $ra_obj = [];
                //     $ra_obj['salik_id']=$distinct_item['transaction_id'];
                //     $ra_obj['source']='Salik';
                //     $ra_obj['amount']=$max_salik-$amount;
                //     $ra_obj['rider_id']=$distinct_item['rider_id'];
                //     $ra_obj['type']='cr';
                //     $ra_obj['month']=Carbon::parse($distinct_item['trip_date'])->format("Y-m-d");
                //     $ra_obj['created_at']=Carbon::now();
                //     $ra_obj['updated_at']=Carbon::now();
                //     array_push($ra_objects, $ra_obj);
                // }
            }
            
            
        }

       
        DB::table('trip__details')->insert($trip_objects); //r2
        $data=Batch::update(new Trip_Detail, $update_data, 'id'); //r3  

        DB::table('company__accounts')->insert($ca_objects); //r4
        $data_ca=Batch::update(new Company_Account, $ca_objects_updates, 'salik_id'); //r5  

        DB::table('rider__accounts')->insert($ra_objects); //r4
        $data_ra=Batch::update(new Rider_Account, $ra_objects_updates, 'salik_id'); //r5  

        return response()->json([
            'data'=>$distinct_data,
            'ra'=>$ra_objects,
            'ca'=>$ca_objects
        ]);

    }
    public function delete_lastImportSalik(){
        $import_id=Trip_Detail::all()->last()->import_id;
        $performances=Trip_Detail::where('import_id',$import_id)->get();
        $ca_deletes=[];
        $deletes = [];
        foreach($performances as $performance)
        {
            $ca_obj = [];
            $ca_obj['salik_id']=$performance->transaction_id;
            $ca_obj['active_status']='D';
            $ca_obj['source']='salik';
            $ca_obj['amount']=$performance->amount_aed;
            $ca_obj['type']='dr';
            array_push($ca_deletes, $ca_obj);
            $performance->active_status = 'D';
            $performance->update();
        }
        $data_ca=Batch::update(new Company_Account, $ca_deletes, 'salik_id'); //r
        return response()->json([
            'a'=>$performances,
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
    public function get_active_riders_ajax_salik($rider_id, $date){
        $bike_history = Assign_bike::all();
        $bike_histories = null;
        $history_found = Arr::first($bike_history, function ($item, $key) use ($rider_id, $date) {
            $created_at =Carbon::parse($item->created_at)->format('Y-m-d');
            $created_at =Carbon::parse($created_at);

            $updated_at =Carbon::parse($item->updated_at)->format('Y-m-d');
            $updated_at =Carbon::parse($updated_at);
            $req_date =Carbon::parse($date);
            if($item->status=="active"){ 
                // mean its still active, we need to match only created at
                return $item->rider_id == $rider_id && $req_date->greaterThanOrEqualTo($created_at);
            }
            
            return $item->rider_id == $rider_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
        });

        if(isset($history_found)){
            $bike_histories = $history_found;
        }else {
            $assign_bikeBK = Assign_bike::where('rider_id', $rider_id)
            ->where('status', 'active')->get()->first();
            if(isset($assign_bikeBK)){
               $bike_histories = $assign_bikeBK;
            }
        }

        
    
        $rider = Rider::find($rider_id);
        return response()->json([
            'bike_histories' => $bike_histories,
            'salik_amount' => $rider->Rider_Detail->salik_amount
        ]);
    }

    /* ===Get bike according to rider and rider according to bike=== */
    public function get_active_bikes_ajax_salik($_id, $date,$according_to){
        $bike_history = Assign_bike::all();
        $bike_histories = null;
        $history_found = Arr::first($bike_history, function ($item, $key) use ($_id, $date,$according_to) {
            $created_at =Carbon::parse($item->created_at)->format('Y-m-d');
            $created_at =Carbon::parse($created_at);

            $updated_at =Carbon::parse($item->updated_at)->format('Y-m-d');
            $updated_at =Carbon::parse($updated_at);
            $req_date =Carbon::parse($date);
            if($item->status=="active"){ 
                // mean its still active, we need to match only created at
                if($according_to=='bike'){
                    return $item->bike_id == $_id && $req_date->greaterThanOrEqualTo($created_at);
                }
                return $item->rider_id == $_id && $req_date->greaterThanOrEqualTo($created_at);
                
            }
            if($according_to=='bike'){
                return $item->bike_id == $_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
            }
            return $item->rider_id == $_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
        });

        if(isset($history_found)){
            $bike_histories = $history_found;
        }else {
            $to_find = $according_to=='bike'?'bike_id':'rider_id';
            $assign_bikeBK = Assign_bike::where($to_find , $_id)
            ->where('status', 'active')->get()->first();
            if(isset($assign_bikeBK)){
               $bike_histories = $assign_bikeBK;
            }
        }
        return response()->json([
            'bike_histories' => $bike_histories,
        ]);
    }

    /* ===Get sim according to rider and rider according to sim=== */
    public function get_active_sims_ajax_salik($_id, $date,$according_to){
        $sim_history = Sim_history::all();
        $sim_histories = null;
        $history_found = Arr::first($sim_history, function ($item, $key) use ($_id, $date,$according_to) {
            $created_at =Carbon::parse($item->created_at)->format('Y-m-d');
            $created_at =Carbon::parse($created_at);

            $updated_at =Carbon::parse($item->updated_at)->format('Y-m-d');
            $updated_at =Carbon::parse($updated_at);
            $req_date =Carbon::parse($date);
            if($item->status=="active"){ 
                // mean its still active, we need to match only created at
                return $according_to=='sim'?$item->sim_id == $_id && $req_date->greaterThanOrEqualTo($created_at):$item->rider_id == $_id && $req_date->greaterThanOrEqualTo($created_at);
            }
            
            return $according_to=='sim'?$item->sim_id == $_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at):$item->rider_id == $_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
        });

        if(isset($history_found)){
            $sim_histories = $history_found;
        }else {
            $to_find = $according_to=='sim'?'sim_id':'rider_id';
            $sim_history = Sim_history::where($to_find, $_id)
            ->where('status', 'active')->get()->first();
            if(isset($sim_history)){
               $sim_histories = $sim_history;
            }
        }
        return response()->json([
            'sim_histories' => $sim_histories,
        ]);
    }
    public function insert_salik(Request $request){
        $used_salik= $request->amount;
        
        $bike=bike::find($request->bike_id);

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
        $allow_salik=$rider_detail->salik_amount;
        if($used_salik>$allow_salik){
            $_greater_ca= new Company_Account;
            $_greater_ca->source="Salik";
            $_greater_ca->salik_id="0";
            $_greater_ca->amount=$used_salik;
            $_greater_ca->rider_id=$rider_id;
            $_greater_ca->month=Carbon::parse($request->month)->format("Y-m-d");
            $_greater_ca->type="dr";
            $_greater_ca->save();

            $_greater_ra= new Rider_Account;
            $_greater_ra->source="Salik";
            $_greater_ra->salik_id="0";
            $_greater_ra->amount=$used_salik-$allow_salik;
            $_greater_ra->rider_id=$rider_id;
            $_greater_ra->month=Carbon::parse($request->month)->format("Y-m-d");
            $_greater_ra->type="cr_payable";
            $_greater_ra->save();

            $_greater_ca= new Company_Account;
            $_greater_ca->source="Salik Extra";
            $_greater_ca->salik_id="0";
            $_greater_ca->amount=$used_salik-$allow_salik;
            $_greater_ca->rider_id=$rider_id;
            $_greater_ca->month=Carbon::parse($request->month)->format("Y-m-d");
            $_greater_ca->type="cr";
            $_greater_ca->save();
        }
        else{
            $ca= new Company_Account;
            $ca->source="Salik";
            $ca->amount=$used_salik;
            $ca->salik_id="0";
            $ca->rider_id=$rider_id;
            $ca->month=Carbon::parse($request->month)->format("Y-m-d");
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
