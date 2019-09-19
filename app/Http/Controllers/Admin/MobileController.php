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
use App\Model\Mobile\Mobile;
use App\Model\Mobile\Mobile_installment;
use App\Model\Mobile\Mobile_Transaction;
use carbon\carbon;

class MobileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    } 
    // start mobile section
    public function update_mobile_GET($id){
        $mobile_edit=Mobile::find($id);
        return view('admin.rider.mobile.edit', compact('mobile_edit'));
    }

    public function update_mobile(Request $request,$id){
        $mobile_update=Mobile::find($id); 
        $this->validate($request, [
            'model' => 'required | string | max:255',
            'imei' => 'required | numeric',
            'purchase_price' => 'required | numeric',
            'sale_price' => 'required | numeric',
            'payment_type' => 'required',
            'amount_received' => 'required | numeric',
            'per_month_installment_amount' => 'required | numeric',
        ]);
        $mobile_update->model=$request->brand.' '.$request->model;
        $mobile_update->imei=$request->imei;
        $mobile_update->purchase_price=$request->purchase_price;
        $mobile_update->sale_price=$request->sale_price;
        $mobile_update->payment_type=$request->payment_type;
        $mobile_update->amount_received=$request->amount_received;
        $mobile_update->installment_starting_month=$request->installment_starting_month;
        $mobile_update->installment_ending_month=$request->installment_ending_month;
        $mobile_update->per_month_installment_amount=$request->per_month_installment_amount;
        $mobile_update->update();
        return redirect(route('mobile.show'))->with('message', 'Record Updated Successfully.');

    }

    public function updateStatusMobile(Request $request,$id)
    {   
        
        $mobile=Mobile::find($id);
        if($mobile->status == 1)
        {
            $mobile->status = 0;
        }
        else
        {
            $mobile->status = 1;
        }
        
        $mobile->update();
        return response()->json([
            'status' => true
        ]);
    }
    public function create_mobile_GET()
    {
        $riders=Rider::where("active_status","A")->get();
        return view('admin.rider.mobile.create',compact("riders"));
    }
    public function create_mobile_POST(Request $r)
    {
        $this->validate($r, [
            'model' => 'required | string | max:255',
            'imei' => 'required | numeric',
            'purchase_price' => 'required | numeric',
            'sale_price' => 'required | numeric',
            'payment_type' => 'required',
        ]);
        $mobile = new Mobile;
        $mobile->model=$r->brand.' '.$r->model;
        $mobile->imei=$r->imei;
        $mobile->rider_id=$r->rider_id;
        $mobile->purchase_price=$r->purchase_price;
        $mobile->sale_price=$r->sale_price;
        $mobile->payment_type=$r->payment_type;
        $mobile->amount_received=$r->amount_received;
        $mobile->installment_starting_month=$r->installment_starting_month;
        $mobile->installment_ending_month=$r->installment_ending_month;
        $mobile->per_month_installment_amount=$r->per_month_installment_amount;
        $mobile->save();

        

        $mobile_installment=new Mobile_installment;
        $mobile_installment->installment_amount=$r->amount_received;
        $mobile_installment->mobile_id=$mobile->id;
        $mobile_installment->installment_month=Carbon::parse($r->filterMonth)->format('Y-m-d');
        $mobile_installment->save();

        $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
            'mobile_installment_id'=>$mobile_installment->id
        ]);
        $ca->mobile_installment_id =$mobile_installment->id;
        $ca->type='dr';
        $ca->rider_id=$r->rider_id;
        $ca->month = $mobile_installment->installment_month;
        $ca->source="Mobile Installment";
        $ca->amount=$r->per_month_installment_amount;
        $ca->save();

        $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
            'mobile_installment_id'=>$mobile_installment->id
        ]);
        $ra->mobile_installment_id =$mobile_installment->id;
        $ra->type='cr_payable';
        $ra->rider_id=$r->rider_id;
        $ra->month = $mobile_installment->installment_month;
        $ra->source="Mobile Installment";
        $ra->amount=$r->per_month_installment_amount;
        $ra->save();

        return redirect(route('mobile.show'))->with('message', 'Record Added Successfully.');
        
    }
    public function mobiles(){
        $mobiles=Mobile::all();
        $mobile_count=$mobiles->count();
        return view('admin.rider.mobile.mobiles', compact('mobiles','mobile_count'));
    }
    public function delete_mobile(Request $request,$id){
        $mobile_delete=Mobile::find($id);
        $mobile_delete->active_status="D";
        $mobile_delete->update();
        
            return response()->json([
                'status' => true
            ]);

    }
    // end mobile section
    // mobile installment
    public function create_mobileInstallment(){
        return view('admin.rider.mobile.create_installment');
    }
    public function store_mobileInstallment(Request $request){
        $installment=new Mobile_installment();
        $installment->installment_month=$request->installment_month;
        $installment->installment_amount=$request->installment_amount;
        $installment->save();
        return redirect(route('MobileInstallment.show'))->with('message', 'Record created Successfully.');
    }

    public function show_mobileInstallmenet(){
        $installment_count=Mobile_installment::all()->count();
        return view('admin.rider.mobile.view_installment',compact('installment_count'));
    }
    public function edit_mobileInstallment($id){
        $installment=Mobile_installment::find($id);
        return view('admin.rider.mobile.edit_installment',compact('installment'));

    }
    public function update_mobileInstallment(Request $request,$id){
        $installment_update=Mobile_installment::find($id); 
        $installment_update->installment_month=$request->installment_month;
        $installment_update->installment_amount=$request->installment_amount;
        $installment_update->update();
        return redirect(route('MobileInstallment.show'))->with('message', 'Record Updated Successfully.');
   
    }
    public function delete_mobileInstallment($id){
        $installment_delete=Mobile_installment::find($id);
        $installment_delete->active_status="D";
        $installment_delete->update();
        
            return response()->json([
                'status' => true
            ]);
    }


    // End mobile installment
    // transaction Records
    public function transaction_view(){
        return view('admin.rider.mobile.transaction_records.transaction_view');
    }
    public function edit_inline_MobileTransaction(Request $r){
        $action = $r->action;
        $id = $r->data['id'];
        if($action=="edit"){
            $mobile=Mobile::find($id);
            $sp=$mobile->sale_price;

            $mob_installment=$mobile->Mobile_installment()->sum("installment_amount");
            $RA=$sp-$mob_installment;

            if($RA!==0){
                $data=$r->data;
                $mobile_trans = Mobile::firstOrCreate([
                    'id'=>$id
                ]);
                $mobile_trans->model=$data['model'];
                $mobile_trans->sale_price=$data['sale_price'];
                $cash_recieved = $data['amount_received'] + $data['per_month_installment_amount'] ;
            
                $mobile_trans->amount_received=$cash_recieved;
                $mobile_trans->save();
                
                // $mobile_transaction=new Mobile_Transaction;
                // $mobile_transaction->mobile_id=$data['id'];
                // $mobile_transaction->sale_price=$data['sale_price'];
                // $mobile_transaction->amount_received=$cash_recieved;
                // $mobile_transaction->remaining_amount=$data['sale_price']-$cash_recieved;
                // $mobile_transaction->per_month_installment_amount=$data['per_month_installment_amount'];
                
                // $a=$data['sale_price']-$cash_recieved;
                // if($a<=0){
                // $mobile_transaction->bill_status='paid';
                // }
                // // $mobile_transaction->bill_status=$a;
                // $mobile_transaction->month=Carbon::parse($data['filterMonth'])->format('Y-m-d');
                // $mobile_transaction->save();



                $mobile_installment=new Mobile_installment;
                $mobile_installment->installment_amount=$data['per_month_installment_amount'];
                $mobile_installment->mobile_id=$data['id'];
                $mobile_installment->installment_month=Carbon::parse($data['filterMonth'])->format('Y-m-d');
                $mobile_installment->save();

                $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                    'mobile_installment_id'=>$mobile_installment->id
                ]);
                $ca->mobile_installment_id =$mobile_installment->id;
                $ca->type='dr';
                $ca->rider_id=$mobile_trans->rider_id;
                $ca->month = $mobile_installment->installment_month;
                $ca->source="Mobile Installment";
                $ca->amount=$data['per_month_installment_amount'];
                $ca->save();

                $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
                    'mobile_installment_id'=>$mobile_installment->id
                ]);
                $ra->mobile_installment_id =$mobile_installment->id;
                $ra->type='cr_payable';
                $ra->rider_id=$mobile_trans->rider_id;
                $ra->month = $mobile_installment->installment_month;
                $ra->source="Mobile Installment";
                $ra->amount=$data['per_month_installment_amount'];
                $ra->save();
                        
            }
        }
       

        return response()->json([
            'status' => true,
            'ss'=>$RA
        ]);
    }
    // end transaction Records
}
