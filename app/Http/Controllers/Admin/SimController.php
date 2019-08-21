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
use App\Model\Sim\Sim;
use App\Model\Sim\Sim_Transaction;
use App\Model\Sim\Sim_History;

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
        $this->validate($request, [
            'sim_company' => 'required | string | max:255',
            'sim_number'=> 'required | string |max:255'
        ]);
        $sim=new Sim();
        $sim->sim_number=$request->sim_number;
        $sim->sim_company=$request->sim_company;
        if ($request->status) 
            $sim->status=1;
        else
            $sim->status=0;
        $sim->save();
        return redirect(route('Sim.view_records'))->with('message', 'Sim created successfully.');
    }
    
    public function view_records_sim(){
        $sim_records=Sim::all();
        $sim_count=Sim::all()->count();
        return view('SIM.view_sim_records',compact('sim_records','sim_count'));
    }

    public function edit_sim($id){
        $sim=Sim::find($id);
       return view('SIM.edit_sim',compact('sim')); 
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
        return redirect(route('Sim.view_records'))->with('message', 'Sim updated successfully.');
    
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
    return view('SIM.create_simTransaction');
}

public function store_simTransaction(Request $request){
    $this->validate($request, [
     
        'month_year'=> 'required | string |max:255',
        'bill_amount'=> 'required | string |max:255',
        'extra_usage_amount'=> 'required | string |max:255',
        'extra_usage_payment_status'=> 'required | string |max:255',
        'bill_status'=> 'required | string |max:255',
    ]);
    $sim_transaction=new Sim_Transaction();
    $sim_transaction->month_year=$request->month_year;
    $sim_transaction->bill_amount=$request->bill_amount;
    $sim_transaction->extra_usage_amount=$request->extra_usage_amount;
    $sim_transaction->extra_usage_payment_status=$request->extra_usage_payment_status;
    $sim_transaction->bill_status=$request->bill_status;
    if ($request->status) 
        $sim_transaction->status=1;
    else
        $sim_transaction->status=0;
        
    $sim_transaction->save(); 

    return redirect(route('SimTransaction.view_records'))->with('message', 'Sim created successfully.');
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
                $sim_history = $sim->Sim_history()->where('status', 'active')->get()->first();
                $rider_id = null;
                if(isset($sim_history)){
                    $rider_id=$sim_history->rider_id;
                }
                if(strtolower($sim_trans->extra_usage_payment_status)=='paid'){
                    // dr to ca,
                    
                    $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                        'sim_transaction_id'=>$sim_trans->id
                    ]);
                    $ca->type='dr';
                    $ca->rider_id=$rider_id;
                    $ca->amount=$data['usage_limit'];
                    $ca->sim_transaction_id=$sim_trans->id;
                    $ca->save();
                    // dr to ra

                    $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
                        'sim_transaction_id'=>$sim_trans->id
                    ]);
                    $ra->type='dr';
                    $ra->amount=$extra;
                    $ra->rider_id=$rider_id;
                    $ra->sim_transaction_id=$sim_trans->id;
                    $ra->save();

                }
                else {
                    $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                        'sim_transaction_id'=>$sim_trans->id
                    ]);
                    $ca->type='dr';
                    $ca->rider_id=$rider_id;
                    $ca->amount=$sim_trans->bill_amount;
                    $ca->sim_transaction_id=$sim_trans->id;
                    $ca->save();
                }
               
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
        $old_history->save();
    }
    $sim_history=$rider->Sim_History()->create([
        'allowed_balance'=>$request->get('allowed_balance'),
        'given_date'=>$request->get('given_date'),
        'return_date'=>$request->get('return_date'),
        'rider_id'=>$request->get('rider_id'),
        'sim_id'=>$request->get('sim_id'),
        'status'=>'active',
    ]);
    
    $sim_history->save();
    return redirect(url('admin/view/Sim/'.$id))->with('message', 'Sim Assigned successfully.');
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
public function removeSim($sim_id,$rider_id){

    $delete_active_sim =Sim_History::where('sim_id', $sim_id)->where('rider_id', $rider_id)->orderBy('created_at', 'desc')->first();
    $delete_active_sim->status='deactive';
    $delete_active_sim->save();
    return response()->json([
        'status' => true
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

}
