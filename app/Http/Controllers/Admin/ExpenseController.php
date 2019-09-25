<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Expence\Company_CD;
use carbon\carbon;
use App\Model\Accounts\Company_Expense;
use App\Model\Accounts\Company_Account;
use App\Model\Accounts\Rider_Account;
use App\Model\Rider\Rider;
use App\Model\Accounts\WPS;
use App\Model\Accounts\AdvanceReturn;

class ExpenseController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    // Company Expense
    public function CE_index(){
        $riders=Rider::where("active_status","A")->get();
        return view('admin.accounts.Company_Expense.CE_add', compact('riders'));
    }
    public function CE_store(Request $r){
        $ce=new Company_Expense();
        $ce->amount=$r->amount;
        $ce->rider_id=$r->rider_id;
        $ce->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ce->description=$r->description;
        if($r->status)
                $ce->status = 1;
            else
                $ce->status = 0;
        $ce->save();
        
        $rider_id = null;
        if(isset($ce->rider_id) && $ce->rider_id!=""){
            $rider_id = $ce->rider_id;
        }
        $ca = new Company_Account();
        $ca->type='dr';
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->amount=$r->amount;
        $ca->rider_id=$rider_id;
        $ca->source=$ce->description;
        $ca->company_expense_id=$ce->id;
        $ca->save();
        return redirect(route('admin.CE_view'));
    }
    public function CE_view(){
        return view('admin.accounts.Company_Expense.CE_view');
    }
    public function CE_update(Request $r,$id){
        $ce=Company_Expense::find($id);
        $ce->amount=$r->amount;
        $ce->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ce->description=$r->description;
        if($r->status)
                $ce->status = 1;
            else
                $ce->status = 0;
        $ce->save();
        
        $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
            'company_expense_id'=>$ce->id
        ]);
        $ca->type='dr';
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->amount=$r->amount;
        $ca->source='company_expense';
        $ca->company_expense_id=$ce->id;
        $ca->save();
        return redirect(route('admin.CE_view'));
    }
    public function CE_delete($id)
{ 
    $delete_expense=Company_Expense::find($id);
    $delete_expense->status=0;
    $delete_expense->active_status="D";
    $delete_expense->update();

    $ca=Company_Account::where("company_expense_id",$delete_expense->id)->get();
        foreach ($ca as $item) {
            $item->active_status="D";
            $item->update();
        }
    }
    public function CE_updatestatus($id)
{
    $update_expense=Company_Expense::find($id);
    if($update_expense->status == 1)
    {
        $update_expense->status = 0;
    }
    else
    {
        $update_expense->status = 1;
    }
    $update_expense->update();
    return response()->json([
        'status' => true
    ]);
}
public function CE_edit($id){
    $readonly=false;
    $riders=Rider::where('active_status','A')->get();
    $edit_expense=Company_Expense::find($id);
    return view('admin.accounts.Company_Expense.CE_edit',compact('riders','readonly','edit_expense'));
}
public function CE_edit_view($id){
    $readonly=true;
    $riders=Rider::where('active_status','A')->get();
    $edit_expense=Company_Expense::find($id);
    return view('admin.accounts.Company_Expense.CE_edit',compact('riders','readonly','edit_expense'));
}
// End Company Expense
// WPS
public function wps_index(){
    $riders=Rider::where("active_status","A")->get();
    return view('admin.accounts.WPS.wps_add',compact('riders'));
}
public function wps_store(Request $r){
    $wps=new WPS();
    $wps->amount=$r->amount;
    $wps->bank_name=$r->bank_name;
    $wps->month = Carbon::parse($r->get('month'))->format('Y-m-d');
    $wps->payment_status=$r->payment_status;
    $wps->rider_id=$r->rider_id;
    if($r->status)
            $wps->status = 1;
        else
            $wps->status = 0;
    $wps->save();
    if ($wps->payment_status=="deposit") {
        $ca =Company_Account::firstOrCreate([
            'wps_id'=>$wps->id
        ]);
        $ca->type='dr';
        $ca->amount=$r->amount;
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->source='wps';
        $ca->wps_id=$wps->id;
        $ca->rider_id=$r->rider_id;
        $ca->save(); 
    }
    if ($wps->payment_status=="withdraw") {
        $ra =Rider_Account::firstOrCreate([
            'wps_id'=>$wps->id
        ]);
        $ra->type='cr';
        $ra->amount=$r->amount;
        $ra->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ra->source='wps';
        $ra->wps_id=$wps->id;
        $ra->rider_id=$r->rider_id;
        $ra->save();
    }
    if ($wps->payment_status=="kingrider") {
        $ca_dr =Company_Account::where("wps_id",$wps->id)->where("type","dr")->get()->first();
        if (isset($ca_dr)) {       
        $ca =Company_Account::firstOrCreate([
            'wps_id'=>$wps->id
        ]);
        $ca->type='cr';
        $ca->amount=$r->amount;
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->source='wps';
        $ca->wps_id=$wps->id;
        $ca->rider_id=$r->rider_id;
        $ca->save();
    }
        $ra_dr =Rider_Account::where("wps_id",$wps->id)->where("type","cr")->get()->first();
           
        
        if (isset($ra_dr)) {
            $ra=new Rider_Account();
            $ra->type='dr';
            $ra->amount=$r->amount;
            $ra->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ra->source='wps'; 
            $ra->rider_id=$r->rider_id; 
            $ra->save();
        }
       
    }
   
    return redirect(route('admin.wps_view'));
}
public function wps_view(){
 return view('admin.accounts.WPS.wps_view');
}
public function wps_edit($id){
    $readonly=false;
    $riders=Rider::where("active_status","A")->get();
    $edit_wps=WPS::find($id);
    return view('admin.accounts.WPS.wps_edit',compact('readonly','edit_wps','riders'));  
}
public function wps_edit_view($id){
    $readonly=true;
    $riders=Rider::where("active_status","A")->get();
    $edit_wps=WPS::find($id);
    return view('admin.accounts.WPS.wps_edit',compact('readonly','edit_wps','riders'));  
}
public function wps_update(Request $r,$id){
    $wps=WPS::find($id);
    $wps->amount=$r->amount;
    $wps->bank_name=$r->bank_name;
    $wps->month = Carbon::parse($r->get('month'))->format('Y-m-d');
    $wps->payment_status=$r->payment_status;
    $wps->rider_id=$r->rider_id;
    if($r->status)
            $wps->status = 1;
        else
            $wps->status = 0;
    $wps->save();
    $ca_delete=Company_Account::where("wps_id",$wps->id)->get();
    foreach ($ca_delete as $delete) {
        $delete->delete();
    }
    $ra_delete=Rider_Account::where("wps_id",$wps->id)->get();
    foreach ($ra_delete as $delete) {
        $delete->delete();
    }
    if ($wps->payment_status=="deposit") {
        $ca =Company_Account::firstOrCreate([
            'wps_id'=>$wps->id
        ]);
        $ca->type='dr';
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->amount=$r->amount;
        $ca->source='wps';
        $ca->wps_id=$wps->id;
        $ca->rider_id=$r->rider_id;
        $ca->save(); 
    }
    if ($wps->payment_status=="withdraw") {
        
        $ca =Company_Account::firstOrCreate([
            'wps_id'=>$wps->id
        ]);
        $ca->type='dr';
        $ca->amount=$r->amount;
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->source='wps';
        $ca->wps_id=$wps->id;
        $ca->rider_id=$r->rider_id;
        $ca->save();  

        $ra =Rider_Account::firstOrCreate([
            'wps_id'=>$wps->id
        ]);
        $ra->type='cr';
        $ra->amount=$r->amount;
        $ra->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ra->source='wps';
        $ra->wps_id=$wps->id;
        $ra->rider_id=$r->rider_id;
        $ra->save();
    }
    if ($wps->payment_status=="kingrider") {
        $ca_dr =Company_Account::where("wps_id",$wps->id)->where("type","dr")->get()->first();
        if (isset($ca_dr)) {       
        $ca =Company_Account::firstOrCreate([
            'wps_id'=>$wps->id
        ]);
        $ca->type='cr';
        $ca->amount=$r->amount;
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->source='wps';
        $ca->wps_id=$wps->id;
        $ca->rider_id=$r->rider_id;
        $ca->save();
    }
        $ra_dr =Rider_Account::where("wps_id",$wps->id)->where("type","cr")->get()->first();
           
        
        if (isset($ra_dr)) {
            $ra=new Rider_Account();
            $ra->type='dr';
            $ra->amount=$r->amount;
            $ra->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ra->source='wps';  
            $ra->rider_id=$r->rider_id;
            $ra->save();
        }
       
    }
    
        return redirect(route('admin.wps_view'));
        }
public function wps_delete($id){
    $delete_wps=WPS::find($id);
    $delete_wps->status=0;
    $delete_wps->active_status="D";
    $delete_wps->update();

    $ca=Company_Account::where("wps_id",$delete_wps->id)->get();
    foreach ($ca as $item) {
        $item->active_status="D";
        $item->update();
    }
}
public function wps_updatestatus($id){
    $update_wps=WPS::find($id);
    if($update_wps->status == 1)
    {
        $update_wps->status = 0;
    }
    else
    {
        $update_wps->status = 1;
    }
    $update_wps->update();
    return response()->json([
        'status' => true
    ]);
}
// End WPS
// ADVANCE & RETURN
public function AR_index(){
    $riders=Rider::where("active_status","A")->get();
return view('admin.accounts.AR.AR_add',compact("riders"));
}
public function AR_view(){
return view('admin.accounts.AR.AR_view');
}
public function AR_updatestatus($id){
    $update_ar=AdvanceReturn::find($id);
    if($update_ar->status == 1)
    {
        $update_ar->status = 0;
    }
    else
    {
        $update_ar->status = 1;
    }
    $update_ar->update();
    return response()->json([
        'status' => true
    ]);
}
public function AR_delete($id){
    $delete_ar=AdvanceReturn::find($id);
    $delete_ar->status=0;
    $delete_ar->active_status="D";
    $delete_ar->update();
}
public function AR_edit($id){
    $readonly=false;
    $riders=Rider::where("active_status","A")->get();
    $edit_ar=AdvanceReturn::find($id);
    return view('admin.accounts.AR.AR_edit',compact('readonly','edit_ar','riders'));
}
public function AR_edit_view($id){
    $readonly=true;
    $riders=Rider::where("active_status","A")->get();
    $edit_ar=AdvanceReturn::find($id);
    return view('admin.accounts.AR.AR_edit',compact('readonly','edit_ar','riders'));
}
public function AR_store(Request $r){
    $ar = AdvanceReturn::create([
        'payment_status'=>$r->payment_status,
        'type'=>$r->type,
        'rider_id'=>$r->rider_id,
        'month' => Carbon::parse($r->get('month'))->format('Y-m-d'),
        'amount'=>$r->amount,
        'status'=>$r->status=='on'?1:0,
    ]); 

    if($ar->payment_status=="pending"){
        $ca =Company_Account::firstOrCreate([
            'advance_return_id'=>$ar->id
        ]);
        $ca->type='dr_receivable';
        $ca->amount=$r->amount;
        if($ar->type=="advance"){$ca->source='advance';}
        else if($ar->type=="return"){$ca->source='loan';}    
        $ca->advance_return_id=$ar->id;
        $ca->rider_id=$ar->rider_id;
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->save();

        $ra =Rider_Account::firstOrCreate([
            'advance_return_id'=>$ar->id
        ]);
        $ra->type='cr_payable';
        $ra->rider_id=$ar->rider_id;
        $ra->amount=$r->amount;
        $ra->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        if($ar->type=="advance"){$ra->source='advance';}
        else if($ar->type=="return"){$ra->source='loan';}
        $ra->advance_return_id=$ar->id;
        $ra->save();

    }


    return redirect(route('admin.AR_view'));
}
public function AR_update(Request $r,$id){
    $update_ar=AdvanceReturn::find($id);
    $update_ar->type=$r->type;
    $update_ar->amount=$r->amount;
    $update_ar->month = Carbon::parse($r->get('month'))->format('Y-m-d');
    $update_ar->payment_status=$r->payment_status;
    $update_ar->rider_id=$r->rider_id;
    if($r->status)
        $update_ar->status = 1;
    else
        $update_ar->status = 0;
    $update_ar->save();
    

    if($update_ar->payment_status == 'pending'){
        $ra_check =Rider_Account::where(['advance_return_id' => $update_ar->id, 'type'=>'dr_payable'])->get()->first();
        if(isset($ra_check)){
            $ra_check->delete();
        }

        $ca_check =Company_Account::where(['advance_return_id' => $update_ar->id, 'type'=>'cr'])->get()->first();
        if(isset($ca_check)){
            $ca_check->delete();
        }
        $ca =Company_Account::firstOrCreate([
            'advance_return_id'=>$update_ar->id
        ]);
        $ca->advance_return_id =$update_ar->id;
        $ca->type='dr_receivable';
        $ca->rider_id=$update_ar->rider_id;
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        if($update_ar->type=="advance"){$ca->source='advance';}
        else if($update_ar->type=="return"){$ca->source='loan';}
        $ca->amount=$r->amount;
        $ca->save();

        $ra =Rider_Account::firstOrCreate([
            'advance_return_id'=>$update_ar->id
        ]);
        $ra->advance_return_id =$update_ar->id;
        $ra->type='cr_payable';
        $ra->rider_id=$update_ar->rider_id;
        $ra->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        if($update_ar->type=="advance"){$ra->source='advance';}
        else if($update_ar->type=="return"){$ra->source='loan';}
        $ra->amount=$r->amount;
        $ra->save();
    }
    return redirect(route('admin.AR_view'));
}
// End ADVANCE & RETURN

public function CE_report(){
    return view('admin.accounts.Company_Expense.CE_report');
}
}
