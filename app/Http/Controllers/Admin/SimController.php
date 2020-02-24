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
use App\Model\Client\Client_History;
use Illuminate\Support\Facades\Storage;
use App\Model\Rider\Rider_Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Model\Sim\Sim;
use App\Model\Sim\Sim_Transaction;
use App\Model\Sim\Sim_History;
use Carbon\Carbon;
use App\Model\Accounts\Company_Expense;
use Arr;
use \App\Model\Accounts\Company_Account;
use App\Model\Accounts\Bill_change;
use App\Export_data;

class SimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    } 

//    Start Sim Section 
    public function add_sim(){
        return view('SIM.create_sim');
    }
    public function store_sim(Request $request){
        $sim_number=$request->sim_number;
        $sim=Sim::where("sim_number",$sim_number)->get()->first();
       if (isset($sim)) {
               if($sim->status==="1"){
                return redirect(url('admin/view/records/Sim?search='.$sim->sim_number))->with('error', 'Sim is already created with id= '.$sim->id.'. And status is Active.');
               }
               return redirect(url('admin/view/records/Sim?search='.$sim->sim_number))->with('error', 'Sim is already created with id= '.$sim->id.'.But status is Inactive.');
        }
        $this->validate($request, [
            'sim_company' => 'required | string | max:255',
            'sim_number'=> 'required | unique:sims'
        ]);
        $sim=new Sim();
        $sim->sim_number=$sim_number;
        $sim->sim_company=$request->sim_company; 
        $sim->status=1;
        $sim->save();
        return redirect(route('Sim.view_records'))->with('message', 'Sim created successfully.');
    }
    
    public function view_records_sim(){
        $sim_records=Sim::all();
        $sim_count=Sim::all()->count();
        return view('SIM.view_sim_records',compact('sim_records','sim_count'));
    }
    public function view_records_sim_active(){
        $sim_records=Sim::all();
        $sim_count=Sim::all()->count();
        return view('SIM.view_sim_records_active',compact('sim_records','sim_count'));
    }

    public function edit_sim($id){
        $readonly=false;
        $sim=Sim::find($id);
       return view('SIM.edit_sim',compact('readonly','sim')); 
    }
    public function edit_sim_view($id){
        $readonly=true;
        $sim=Sim::find($id);
       return view('SIM.edit_sim',compact('readonly','sim')); 
    }
    public function update_sim(Request $request, $id){
      $sim=Sim::find($id);  
        $this->validate($request, [
            'sim_company' => 'required | string | max:255',
            'sim_number'=> 'required | string |max:255'
        ]);
        $sim->sim_number=$request->sim_number;
        $sim->sim_company=$request->sim_company;
        if ($request->status) 
            $sim->status=1;
        else
            $sim->status=0;
        $sim->update();
        return redirect(route('Sim.edit_sim_view',$sim->id))->with('message', 'Sim updated successfully.');
    
    }
    public function updateStatusSim(Request $request,$id)
    {   
        
        $sim=Sim::find($id);
        if($sim->status == 1)
        {
            $sim->status = 0;
        }
        else
        {
            $sim->status = 1;
        }
        
        $sim->update();
        return response()->json([
            'status' => true
        ]);
    }
    public function getRiderHistory($sim_id){
        $sim = Sim::find($sim_id);
        $sim_history=$sim->Sim_History()->get();

        return view('SIM.rider_history',compact('sim_history', 'sim'));

    }
    public function DeleteSim(Request $request,$id){
        $sim_delete=Sim::find($id);
        $sim_delete->active_status='D';
        $sim_delete->status=0;
        $sim_delete->update();
        $sim_history=$sim_delete->Sim_History()->where("status","active")->get()->first();
        if(isset($sim_history)){
        $sim_history->status="deactive";
        $sim_history->update();
        }
        return response()->json([
            'status' => true
        ]);
    }
    // End Sim Section

    
    // Start Sim Transaction Section
public function add_simTransaction(){
    $sims = Sim::where('active_status', 'A')->get();
    $riders=Rider::where('active_status','A')->get();
    return view('SIM.create_simTransaction', compact('sims','riders'));
}
public function get_active_riders_ajax($rider_id, $date){
    // $sims_RAW = Sim::all();
    $sim_history = Sim_history::all();
    $sim_histories = null;
    // foreach ($sims_RAW as $sim) {
    $history_found = Arr::first($sim_history, function ($item, $key) use ($rider_id, $startMonth) {
        $start_created_at =Carbon::parse($item->given_date)->startOfMonth()->format('Y-m-d');
        $created_at =Carbon::parse($start_created_at);

        $start_updated_at =Carbon::parse($item->return_date)->endOfMonth()->format('Y-m-d');
        $updated_at =Carbon::parse($start_updated_at);
        $req_date =Carbon::parse($startMonth);

        return $item->rider_id==$rider_id &&
            ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
    });

        if(isset($history_found)){
            $sim_histories = $history_found;
        }
    // }


    


    return response()->json([
        'sim_histories' => $sim_histories,
    ]);
}
public function get_sim_ajax($sim_id, $month,$rider_id){
    $rider_data=[];
    $sim = Sim::find($sim_id);
    $usage_limit = 105;
    $sim_history = Sim_history::all()->toArray();
    $history_found = Arr::first($sim_history, function ($item, $key) use ($sim_id, $month, $rider_id) {
        $created_at =Carbon::parse($item['given_date'])->format('m');
        $updated_at =Carbon::parse($item['return_date'])->format('m');
        $req_date =Carbon::parse($month)->format('m');
        if ($item['status']=='active') {
            return $item['sim_id'] == $sim_id && $item['rider_id'] == $rider_id;
        }
        return $item['sim_id'] == $sim_id && $item['rider_id'] == $rider_id && ($created_at == $req_date || $updated_at == $req_date);
    });

    $day_created = null;
    $day_updated = null;
    //usage_limit
    if(isset($history_found)){
        if ($history_found['allowed_balance']!=null) {
            $usage_limit =$history_found['allowed_balance'];
        }
        
        $day_created = $history_found['created_at'];
        $day_updated = $history_found['updated_at'];

        $day_created_t=Carbon::parse($day_created);
        $day_updated_t=Carbon::parse($day_updated);

        $req_date_start =Carbon::parse($month)->firstOfMonth();
        $req_date_end =Carbon::parse($month)->lastOfMonth();
        if ($day_updated_t->greaterThan($req_date_end)) {
            $day_updated_t=$req_date_end;
        }
        if ($day_created_t->lessThan($req_date_start)) {
            $day_created_t=$req_date_start; 
        }
        $days_sim_used=$day_created_t->diffInDays(Carbon::parse($day_updated_t));
        $total_days_of_month=Carbon::parse($month)->daysInMonth;
    }
    //usage_limit
        // $totaldays=Arr::where($sim_history, function ($item, $key) use ($sim_id, $month) {
        //     $created_at =Carbon::parse($item['created_at'])->format('m');
        //     $updated_at =Carbon::parse($item['updated_at'])->format('m');
        //     $req_date =Carbon::parse($month)->format('m');
        //     if ($item['status']=='active') {
        //         return $item['sim_id'] == $sim_id;
        //     }
        //     return $item['sim_id'] == $sim_id && ($created_at == $req_date || $updated_at == $req_date);
        // });
        // $total_month_days= 0;
        // foreach ($totaldays as $days) {
        //     $created_total =Carbon::parse($days['created_at']);
        //     $updated_total =Carbon::parse($days['updated_at']);
        //     $days_in_month=$created_total->diffInDays(Carbon::parse($updated_total));

        //     $total_month_days += $days_in_month;
        // }
        // foreach ($totaldays as $days) {
        //     $created_total =Carbon::parse($days['created_at']);
        //     $updated_total =Carbon::parse($days['updated_at']);
        //     $days_in_month=$created_total->diffInDays(Carbon::parse($updated_total)); 
        //     $obj=[]; 
        //     $obj['rider_id']=$days['rider_id']; 
        //     $obj['allowed_balance']=$days['allowed_balance']; 
        //     $obj['created_at']=$days['created_at']; 
        //     $obj['updated_at']=$days['updated_at']; 
        //     array_push($rider_data,$obj);
        // }
        
        
    
    return response()->json([
        // 'sim' => $sim,
        // 'usage_limit' => $usage_limit,
        // 'bill_amount' => $usage_limit,
        // 'days_sim_used'=>$days_sim_used ,
        // 'total_month_days'=>$total_month_days,
        // 'rider_data'=>$rider_data,
        'sim_history'=>$history_found,
    ]);
}

public function store_simTransaction(Request $request){
    $data=$request->data;
    $splitted_amount=0;
    $total_amount=$request->amount;

    #storing any bill details so we can detect changes
    $bill_changes = new Bill_change;
    $bill_changes->month=Carbon::parse($request->month_year)->startOfMonth()->format('Y-m-d');
    $bill_changes->type='sim';
    $bill_changes->amount=$total_amount;
    $bill_changes->given_date=Carbon::parse($request->given_date)->format('Y-m-d');
    $billchanges_feed=[];

    foreach ($data as $value) {
        $sim_trans=new Sim_Transaction();
        $sim_trans->month_year=Carbon::parse($request->month_year)->startOfMonth()->format('Y-m-d');
        $sim_trans->bill_amount=$value['bill_amount_given_by_days'];
        $sim_trans->sim_id=$request->sim_id;
        if(isset($value['sim_id'])){
            $sim_trans->sim_id=$value['sim_id'];
        }
        $extra = $value['bill_amount_given_by_days'] - $value['work_days_allowed_balance'];
        if($extra<0){
            $extra=0;
        }
        $sim_trans->extra_usage_amount=round($extra,2);
        $sim_trans->extra_usage_payment_status="pending";
        $sim_trans->bill_status="pending";
        $sim_trans->status=1;
        if($request->hasFile('sim_bill_image'))
        {
            // return 'yes';
            $filename = $request->sim_bill_image->getClientOriginalName();
            $filesize = $request->sim_bill_image->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/sim_bill_image', $request->file('sim_bill_image'));
            $sim_trans->sim_bill_image = $filepath;
        }
        $sim_trans->save();

        $sim=$sim_trans->Sim;
    
        $rider_id=$request->rider_id;
        if(isset($value['rider_id'])){
            $rider_id=$value['rider_id'];
        }
        $date_to_match=Carbon::parse($request->month_year)->format('Y-m-d');
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
        if($pm==""){
            return response()->json([
                'status'=>0,
                'pm'=>$pm,
            ]);
        }
        if ($pm=="commission_based") {
            $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                'sim_transaction_id'=>$sim_trans->id,
                'type'=>'cr',
            ]);
            $ca->sim_transaction_id =$sim_trans->id;
            $ca->type='cr';
            $ca->rider_id=$request->rider_id;
            if(isset($value['rider_id'])){
                $ca->rider_id=$value['rider_id'];
            }
            $ca->month = Carbon::parse($sim_trans->month_year)->startOfMonth()->format('Y-m-d');
            $ca->given_date = Carbon::parse($request->given_date)->format('Y-m-d');
            $ca->source="Sim Transaction"; 
            $ca->amount=$value['bill_amount_given_by_days'];
            $ca->save();

            $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                'sim_transaction_id'=>$sim_trans->id,
                'type'=>'dr',
            ]);
            $ca->sim_transaction_id =$sim_trans->id;
            $ca->type='dr';
            $ca->rider_id=$request->rider_id;
            if(isset($value['rider_id'])){
                $ca->rider_id=$value['rider_id'];
            }
            $ca->month = Carbon::parse($sim_trans->month_year)->startOfMonth()->format('Y-m-d');
            $ca->given_date = Carbon::parse($request->given_date)->format('Y-m-d');
            $ca->source="Sim Transaction"; 
            $ca->amount=$value['bill_amount_given_by_days'];
            $ca->save();
            
            $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
                'sim_transaction_id'=>$sim_trans->id,
            ]);
            $ra->sim_transaction_id =$sim_trans->id;
            $ra->type='dr';
            $ra->rider_id=$request->rider_id;
            if(isset($value['rider_id'])){
                $ra->rider_id=$value['rider_id'];
            }
            $ra->month = Carbon::parse($sim_trans->month_year)->startOfMonth()->format('Y-m-d');
            $ra->given_date = Carbon::parse($request->given_date)->format('Y-m-d');
            $ra->source="Sim Transaction"; 
            $ra->amount=$value['bill_amount_given_by_days'];
            $ra->save();
            // return response()->json([
            //     'status'=>1,
            //     'ca'=>$ca,
            //     'ra'=>$ra,
            //     'sim_trans'=>$sim_trans->id,
            //     'pm'=>$pm,
            // ]);
        }
        else{
            $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                'sim_transaction_id'=>$sim_trans->id,
                'type' => 'dr'
            ]);
            $ca->sim_transaction_id =$sim_trans->id;
            $ca->type='dr';
            $ca->rider_id=$request->rider_id;
            if(isset($value['rider_id'])){
                $ca->rider_id=$value['rider_id'];
            }
            $ca->month = Carbon::parse($sim_trans->month_year)->startOfMonth()->format('Y-m-d');
            $ca->given_date = Carbon::parse($request->given_date)->format('Y-m-d');
            $ca->source="Sim Transaction"; 
            $ca->amount=$value['bill_amount_given_by_days'];
            $ca->save();
        }
        $ed =new Export_data;
        $ed->type='dr';
        $ed->rider_id=$request->rider_id;
        if(isset($value['rider_id'])){
            $ed->rider_id=$value['rider_id'];
        }
        $ed->amount=$value['bill_amount_given_by_days'];
        $ed->month = Carbon::parse($sim_trans->month_year)->startOfMonth()->format('Y-m-d');
        $ed->given_date = Carbon::parse($request->given_date)->format('Y-m-d');
        $ed->source="Sim Transaction"; 
        $ed->source_id=$sim_trans->id;
        $ed->save();
        if ($pm!="commission_based") {
            if($extra>0){
                $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
                    'sim_transaction_id'=>$sim_trans->id
                ]);
                $ra->sim_transaction_id =$sim_trans->id;
                $ra->type='cr_payable';
                $ra->rider_id=$request->rider_id;
                if(isset($value['rider_id'])){
                    $ra->rider_id=$value['rider_id'];
                }
                $ra->month = Carbon::parse($sim_trans->month_year)->startOfMonth()->format('Y-m-d');
                $ra->given_date = Carbon::parse($request->given_date)->format('Y-m-d');
                $ra->source="Sim extra usage"; 
                $ra->amount=round($extra,2);
                $ra->save();
                
                $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                    'sim_transaction_id'=>$sim_trans->id,
                    'type' => 'cr'
                ]);
                $ca->sim_transaction_id =$sim_trans->id;
                $ca->type='cr';
                $ca->rider_id=$request->rider_id;
                if(isset($value['rider_id'])){
                    $ca->rider_id=$value['rider_id'];
                }
                $ca->month = Carbon::parse($sim_trans->month_year)->startOfMonth()->format('Y-m-d');
                $ca->given_date = Carbon::parse($request->given_date)->format('Y-m-d');
                $ca->source="Sim extra usage";
                $ca->amount=round($extra,2);
                $ca->save();
            }
        }
        $splitted_amount+=$value['bill_amount_given_by_days'];

        #saving bill data to detect changes
        $obj_feed=[];
        $obj_feed['sim_id']=$request->sim_id;
        if(isset($value['sim_id'])){
            $obj_feed['sim_id']=$value['sim_id'];
        }
        $obj_feed['rider_id']=$request->rider_id;
        if(isset($value['rider_id'])){
            $obj_feed['rider_id']=$value['rider_id'];
        }
        $obj_feed['allowed_balance']=$value['allowed_balance'];
        $obj_feed['work_days']=$value['work_days_count'];
        $obj_feed['working_allowed']=$value['work_days_allowed_balance'];
        $obj_feed['month_days']=$value['total_days'];
        $obj_feed['bill_amount']=$value['bill_amount_given_by_days'];
        $obj_feed['extra']=$value['extra_useage_amount_given_by_days'];
        array_push($billchanges_feed,$obj_feed);
    }
    $bill_changes->feed=json_encode($billchanges_feed);
    $bill_changes->save();
    $remaining_amount=$total_amount-$splitted_amount;

    if($remaining_amount>0){
        //add this as company expense
        $ce=new Company_Expense();
        $ce->amount=round($remaining_amount,2);
        // $ce->rider_id=$request->rider_id;
        $ce->month = Carbon::parse($request->get('month'))->format('Y-m-d');
        $ce->description="Sim Bill remaining amount";
        $ce->type="sim_bill";
        $ce->save();
    }
    return response()->json([
        'status' => 1,
        'data'=>$bill_changes,
        'pm'=>$pm,
    ]);
    return redirect(route('SimTransaction.view_records'))->with('message', 'Sim Transaction is completed successfully.');
    
}

public function view_sim_transaction_records(){
    $transaction_count=Sim_Transaction::all()->count();
    return view('SIM.view_simTransaction',compact('transaction_count'));
}
public function edit_simTransaction($id){
    $sim_transaction=Sim_Transaction::find($id);
    return view('SIM.edit_simTransaction',compact('sim_transaction'));
}
public function update_simTransaction(Request $request, $id){
   
    $sim_transaction=Sim_Transaction::find($id);  
      $this->validate($request, [
        'bill_amount'=> 'required | string |max:255',
        'extra_usage_amount'=> 'required | string |max:255',
        'extra_usage_payment_status'=> 'required | string |max:255',
        'bill_status'=> 'required | string |max:255',
      ]);
      $sim_transaction->month_year=$request->month_year;
      $sim_transaction->bill_amount=$request->bill_amount;
      $sim_transaction->extra_usage_amount=$request->extra_usage_amount;
      $sim_transaction->extra_usage_payment_status=$request->extra_usage_payment_status;
      $sim_transaction->bill_status=$request->bill_status;
      if ($request->status) 
          $sim_transaction->status=1;
      else
          $sim_transaction->status=0;
      $sim_transaction->update();
      return redirect(route('SimTransaction.view_records'))->with('message', 'Sim updated successfully.');
  
  }
  public function updateStatusSimTransaction(Request $request,$id)
  {   
      
      $sim_transaction=Sim_Transaction::find($id);
      if($sim_transaction->status == 1)
      {
          $sim_transaction->status = 0;
      }
      else
      {
          $sim_transaction->status = 1;
      }
      
      $sim_transaction->update();
      return response()->json([
          'status' => true
      ]);
  }
  public function DeleteSimTransaction(Request $request,$id){
    $simTransaction_delete=Sim_Transaction::find($id);
    
    $simTransaction_delete->delete();
    
        return response()->json([
            'status' => true
        ]);
}
    public function edit_inline_simTransaction(Request $r){
        $action = $r->action;
        $id = $r->data['id'];

        if($action=="edit"){
            $data=$r->data;
            $sim_trans = Sim_transaction::firstOrCreate([
                'id'=>$id
            ]);
            $sim_trans->sim_id=$data['sim_id'];
            $sim_trans->bill_amount=$data['bill_amount'];
            $extra = $data['bill_amount'] - $data['usage_limit'] ;
            if($extra<0){
                $extra=0;
            }
            $sim_trans->extra_usage_amount=$extra;
            $sim_trans->extra_usage_payment_status=$data['extra_usage_payment_status'];
            $sim_trans->bill_status=$data['bill_status'];
            $sim_trans->status=1;
            $sim_trans->month_year=$data['filterMonth'];
            $sim_trans->save();

            // ca-ra

            $status = $data['status'];
            //if($status=='inactive'){
                // created
                
            //}
           // else{
                //updated
                $sim=$sim_trans->Sim;
                $sim_history = Sim_history::all();		 
                $sim_id = $sim->id;
                $date = $sim_trans->month_year;
                $history_found = Arr::first($sim_history, function ($item, $key) use ($sim_id, $date) {
                    $created_at =Carbon::parse($item->given_date)->format('Y-m-d');
                    $created_at =Carbon::parse($created_at);

                    $updated_at =Carbon::parse($item->return_date)->format('Y-m-d');
                    $updated_at =Carbon::parse($updated_at);
                    $req_date =Carbon::parse($date);
                    if($item->status=="active"){ 
                        // mean its still active, we need to match only created at
                        return $item->sim_id == $sim_id && $req_date->greaterThanOrEqualTo($created_at);
                    }
                    
                    return $item->sim_id == $sim_id && $req_date->greaterThanOrEqualTo($created_at) && $req_date->lessThanOrEqualTo($updated_at);
                });

                $rider_id=null;
                if (isset($history_found)) {
                    $rider_id=$history_found->rider_id;
                }else {
                    $sim_history = Sim_history::where('sim_id', $sim_id)
                    ->where('status', 'active')->get()->first();
                    if(isset($sim_history)){
                        $rider_id=$sim_history->rider_id;
                    }
                }
                // if(strtolower($sim_trans->extra_usage_payment_status)=='paid'){
                //     // dr to ca,
                    
                //     $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                //         'sim_transaction_id'=>$sim_trans->id
                //     ]);
                //     $ca->type='dr';
                //     $ca->rider_id=$rider_id;
                //     $ca->amount=$data['usage_limit'];
                //     $ca->sim_transaction_id=$sim_trans->id;
                //     $ca->save();
                //     // dr to ra

                //     $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
                //         'sim_transaction_id'=>$sim_trans->id
                //     ]);
                //     $ra->type='dr';
                //     $ra->amount=$extra;
                //     $ra->rider_id=$rider_id;
                //     $ra->sim_transaction_id=$sim_trans->id;
                //     $ra->save();

                // }
                // else {
                    $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                        'sim_transaction_id'=>$sim_trans->id,
                        'type' => 'dr'
                    ]);
                    $ca->sim_transaction_id =$sim_trans->id;
                    $ca->type='dr';
                    $ca->rider_id=$rider_id;
                    $ca->month = Carbon::parse($data['filterMonth'])->format('Y-m-d');
                    $ca->source="Sim Transaction"; 
                    $ca->amount=$data['bill_amount'];
                    $ca->save();

                    if($extra>0){
                        $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
                            'sim_transaction_id'=>$sim_trans->id
                        ]);
                        $ra->sim_transaction_id =$sim_trans->id;
                        $ra->type='cr_payable';
                        $ra->rider_id=$rider_id;
                        $ra->month = Carbon::parse($data['filterMonth'])->format('Y-m-d');
                        $ra->source="Sim extra usage"; 
                        $ra->amount=$extra;
                        $ra->save();
                        
                        $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                            'sim_transaction_id'=>$sim_trans->id,
                            'type' => 'cr'
                        ]);
                        $ca->sim_transaction_id =$sim_trans->id;
                        $ca->type='cr';
                        $ca->rider_id=$rider_id;
                        $ca->month = Carbon::parse($data['filterMonth'])->format('Y-m-d');
                        $ca->source="Sim extra usage";
                        $ca->amount=$extra;
                        $ca->save();
                    }
                //}
                
              

            
          //  }
            
            // ends ca-ra
        }

        return response()->json([
            'status' => true
        ]);
    }



    // End Sim Transaction Section
// Start Sim history Section
public function add_simHistory($id){
    $rider_id=Rider::find($id);
    $sim=Sim::all();
     $sim_history=$rider_id->Sim_History()->where('status','active')->get()->first();
     $sim_val=0;
     if (isset($sim_history)) {
         $sim_val=1;
         $sim_history->status='active'; 
     }
    return view("SIM.create_simHistory",compact('rider_id','sim','sim_history','sim_val'));
}
public function store_simHistory(Request $request,$id){
    $rider=Rider::find($id);
    $sim=Sim::find($request->get('sim_id'));
    $old_histories = $rider->Sim_History()->where('status', 'active')->get();
    foreach($old_histories as $old_history)
    {
        $old_history->status='deactive';
        $old_history->return_date=Carbon::now()->format("Y-m-d");
        $old_history->save();
    }
    $sim_history=$rider->Sim_History()->create([
        'allowed_balance'=>$request->get('allowed_balance'),
        'given_date'=>Carbon::parse($request->get('given_date'))->format('Y-m-d'),
        'return_date'=>Carbon::parse($request->get('given_date'))->format('Y-m-d'),
        'rider_id'=>$request->get('rider_id'),
        'sim_id'=>$request->get('sim_id'),
        'status'=>'active',
    ]);
    
    $sim_history->save();
    return redirect(route('Sim.simHistory', $rider->id))->with('message', 'Sim Assigned successfully.');
}
public function update_simHistory(Request $request,$id){
    $rider=Rider::find($id);
    $sim_history=$rider->Sim_history()->where('status','active')->get()->first();  
      
      $sim_history->given_date=$request->given_date;
      $sim_history->return_date=$request->return_date;
      $sim_history->allowed_balance=$request->allowed_balance;
      $sim_history->update();
    // return $sim_history;
}



// end Sim history Section


public function view_assigned_sim($id){
    $rider=Rider::find($id);
    $sim_history=$rider->Sim_History()->where('status','active')->get()->first();
    $sim_count=0;
    $sim=null;
    if (isset($sim_history)) {
        $sim_count=1;
    $sim=Sim::find($sim_history->sim_id);
    }
    
    return view('SIM.view_assigned_sim',compact('rider','sim','sim_history','sim_count')); 
}
public function removeSim($rider_id,$sim_id){
    $delete_active_sim =Sim_History::where('sim_id', $sim_id)
    ->where('rider_id', $rider_id)
    ->where('status', 'active')
    ->get()
    ->first();
    $delete_active_sim->status='deactive';
    $delete_active_sim->return_date=Carbon::now()->format("Y-m-d");
    $delete_active_sim->save();
    return response()->json([
        'status' => $rider_id,
        's' => $sim_id,
    ]);
   
   
  }
  public function sim_deactive_date(Request $request,$rider_id,$sim_id){
    $deactive_sim =Sim_History::where('sim_id', $sim_id)->where('rider_id', $rider_id)->where('status','active')->get()->first();
    if (isset( $deactive_sim)) {
        $deactive_sim->return_date=Carbon::parse($request->updated_at)->format("Y-m-d");  
        $deactive_sim->status='deactive';
    }
    $deactive_sim->update();
    return response()->json([
        'status' => $deactive_sim,
    ]);
  }
  public function update_allowed_abalance(Request $request,$rider_id,$sim_history_id){
    $allowed_balance=Sim_History::find($sim_history_id);
    $allowed_balance->allowed_balance=$request->allowed_balance;
    $allowed_balance->update();
        return response()->json([
            'status'=>$allowed_balance,
        ]);
  }
  public function sim_History($id){
    $rider=Rider::find($id);
    $sim=Sim::find($id);
    $sim_history=$rider->Sim_History()->get();
    $simHistory_count=$sim_history->count();
    // $hasRider=Rider::find($assign_rider->pluck('rider_id'));
    // $hasSim=Sim::find($sim_history->pluck('sim_id'));
    // return $simHistory_count; 
    return view('SIM.view_sim_histroy',compact('rider','sim_history','simHistory_count','sim'));
  }
  public function DeleteSimHistory($id)
  {
      $sim_history = Sim_History::find($id);
      $sim_history->delete();
  }
  public function sim_dates_History(Request $request,$rider_id,$assign_sim_id,$status){
    // return response()->json([
    //     'rider_id' =>$rider_id,
    //     'assign_sim_id' =>$assign_sim_id,
    //     'status' =>$status,
    // ]);
      $assign_sim=Sim_History::where("rider_id",$rider_id)->where("id",$assign_sim_id)->get()->first();
      if (isset($assign_sim)) {
          if ($status=="active") {
            $assign_sim->given_date=Carbon::parse($request->created_at)->format('Y-m-d');
            $assign_sim->return_date=Carbon::parse($request->created_at)->format('Y-m-d');
          }
          else{
            $assign_sim->given_date=Carbon::parse($request->created_at)->format('Y-m-d');
            $assign_sim->return_date=Carbon::parse($request->updated_at)->format('Y-m-d');
          }
      }
      $assign_sim->update();
    return response()->json([
        'status' =>$assign_sim,
    ]);
  }
    public function SimBIllImage($rider_id,$month,$type){
        $_onlyMonth=Carbon::parse($month)->format("m");
        $ca=Company_Account::where("rider_id",$rider_id)
        ->where("source","Sim Transaction")
        ->whereMonth("month",$_onlyMonth)
        ->get();
        if (isset($ca)) {
        $image_id=[];
            foreach ($ca as $value) {
                $sim_trans_id=$value->sim_transaction_id;
                $image_show=Sim_Transaction::find( $sim_trans_id);
                $_image_link=url(asset(Storage::url($image_show->sim_bill_image)));
                if ($image_show->sim_bill_image=="") {
                    $_image_link="";
                }
                array_push($image_id,$_image_link);
            }
        }
        return response()->json([
            '_onlyMonth'=>$_onlyMonth,
            'rider_id'=>$rider_id,
            'type'=>$type,
            'ca'=>$ca,
            'sim_trans_id'=>$image_id,
        ]);
    }
    public function History_Sim_matching_dates($rider_id,$sim_id,$date){
        $sim_history = Sim_History::all();
        $history_found = Arr::first($sim_history, function ($item, $key) use ($rider_id, $date) {
            $start_created_at =Carbon::parse($item->given_date)->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);
  
            $start_updated_at =Carbon::parse($item->return_date)->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($date);
            if ($item->status=="active") {
              return $item->rider_id==$rider_id && ( $req_date->greaterThanOrEqualTo($created_at));
            }
            return $item->rider_id==$rider_id && ( $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->lessThan($updated_at));
        });
        $history_found2 = Arr::first($sim_history, function ($item, $key) use ($sim_id, $date) {
          $start_created_at =Carbon::parse($item->given_date)->format('Y-m-d');
          $created_at =Carbon::parse($start_created_at);
  
          $start_updated_at =Carbon::parse($item->return_date)->format('Y-m-d');
          $updated_at =Carbon::parse($start_updated_at);
          $req_date =Carbon::parse($date);
          if ($item->status=="active") {
            return $item->sim_id==$sim_id &&  ( $req_date->greaterThanOrEqualTo($created_at));
          }
          return $item->sim_id==$sim_id && ( $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->lessThan($updated_at));
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
