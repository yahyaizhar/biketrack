<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Expence\Company_CD;
use carbon\carbon; 
use App\Model\Accounts\Company_Expense;
use App\Model\Accounts\CompanyExpenseType;
use App\Model\Accounts\Company_Account;
use App\Model\Accounts\Rider_Account;
use App\Model\Rider\Rider;
use App\Model\Bank\Bank_account;
use App\Model\Accounts\WPS;
use App\Model\Accounts\AdvanceReturn;
use App\Company_investment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Export_data;

class ExpenseController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    // Company Expense
    public function CE_index(){
        $banks = Bank_account::where("active_status","A")->get();
        $CompanyExpenseType = CompanyExpenseType::where("status","A")->get();
        $riders=Rider::where("active_status","A")->get();
        $available_balance=Company_Account::where("type","pl")->sum("amount");
        return view('admin.accounts.Company_Expense.CE_add', compact('riders','available_balance','banks','CompanyExpenseType'));
    }
    public function CE_store(Request $r){
        $ce=new Company_Expense();
        $t=new CompanyExpenseType();
        $ce->amount=$r->amount;
        $t->type_name=$r->type;
        $ce->type=$r->type;
        $ce->description=$r->description;
        $ce->paid_by=$r->paid_by;
        $ce->account_no=$r->account_no;
        $ce->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
        $ce->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        if($r->hasFile('bill_picture'))
        {
            $filename = $r->bill_picture->getClientOriginalName();
            $filesize = $r->bill_picture->getClientSize();
            $filepath = Storage::putfile('public/uploads/riders/bill_picture', $r->file('bill_picture'));
            $ce->bill_picture = $filepath;
        }
        $ce->save();
        if(CompanyExpenseType::where("type_name",$r->type)->count() <= 0){
            $t->save();
        }
        return redirect(route('admin.CE_view'));
    }
    public function CE_view(){
        return view('admin.accounts.Company_Expense.CE_view');
    }
    public function CE_update(Request $r,$id){
        // return $r;
        $ce=Company_Expense::find($id);
        $t=new CompanyExpenseType();
        $t->type_name=$r->type;
        $ce->type=$r->type;
        $ce->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
        $ce->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ce->description=$r->description;
        $ce->amount=$r->amount;
        $ce->paid_by=$r->paid_by;
        if(isset($r->account_no)){
        $ce->account_no=$r->account_no;
        }
        if($r->hasFile('bill_picture'))
        {
            $filepath = Storage::putfile('public/uploads/riders/bill_picture', $r->file('bill_picture'));
            $ce->bill_picture = $filepath;
        }
        $ce->update();
        if(CompanyExpenseType::where("type_name",$r->type)->count() <= 0){
            $t->save();
        }
        return redirect(route('admin.CE_edit_view',$ce->id));
    }
    public function CE_delete($id)
{ 
    $delete_expense=Company_Expense::find($id);
    // $delete_expense->status=0;
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
    $CompanyExpenseType = CompanyExpenseType::where("status","A")->get();
    $edit_expense=Company_Expense::find($id);
    $banks = Bank_account::where("active_status","A")->get();
    $available_balance=Company_Account::where("type","pl")->sum("amount");
    return view('admin.accounts.Company_Expense.CE_edit',compact('riders','readonly','edit_expense','available_balance','banks','CompanyExpenseType'));
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
    // $ca=Company_Account::get();
    // foreach ($ca as $c) {
    //     $c->given_date=Carbon::parse($c->created_at)->format('Y-m-d');
    //     $c->save();
    // }
    // $ra=Rider_Account::get();
    // foreach ($ra as $r) {
    //     $r->given_date=Carbon::parse($r->created_at)->format('Y-m-d');
    //     $r->save();
    // }
    // return 'updated';
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
        'payment_status'=>"paid",
        'type'=>$r->type,
        'rider_id'=>$r->rider_id,
        'month' => Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d'),
        'given_date' => Carbon::parse($r->get('given_date'))->format('Y-m-d'),
        'amount'=>$r->amount,
        'status'=>$r->status=='on'?1:0,
    ]); 

    if($ar->payment_status=="paid"){
    //     $ca =Company_Account::firstOrCreate([
    //         'advance_return_id'=>$ar->id
    //     ]);
    //     $ca->type='dr_receivable';
    //     $ca->amount=$r->amount;
    //     if($ar->type=="advance"){$ca->source='advance';}
    //     else if($ar->type=="return"){$ca->source='loan';}    
    //     $ca->advance_return_id=$ar->id;
    //     $ca->rider_id=$ar->rider_id;
    //     $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
    //     $ca->save();

        $ra =Rider_Account::firstOrCreate([
            'advance_return_id'=>$ar->id
        ]);
        $ra->type='cr_payable';
        $ra->rider_id=$ar->rider_id;
        $ra->amount=$r->amount;
        $ra->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
        $ra->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
        if($ar->type=="advance"){$ra->source='advance';}
        else if($ar->type=="return"){$ra->source='loan';}
        $ra->advance_return_id=$ar->id;
        $ra->payment_status="paid";
        $ra->save();

        $ed =Export_data::firstOrCreate([
            'source'=>"advance",
            'source_id'=>$ar->id,
        ]);
        $ed->type='cr_payable';
        $ed->rider_id=$ar->rider_id;
        $ed->amount=$r->amount;
        $ed->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
        $ed->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
        if($ar->type=="advance"){$ed->source='advance';}
        else if($ar->type=="return"){$ed->source='loan';}
        $ed->source_id=$ar->id;
        $ed->payment_status="paid";
        $ed->save();
    }


    return redirect(route('admin.AR_view'));
}
public function AR_update(Request $r,$id){
    $update_ar=AdvanceReturn::find($id);
    $update_ar->type=$r->type;
    $update_ar->amount=$r->amount;
    $update_ar->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
    $update_ar->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
    $update_ar->payment_status='paid';
    $update_ar->rider_id=$r->rider_id;
    if($r->status)
        $update_ar->status = 1;
    else
        $update_ar->status = 0;
    $update_ar->save();
    

    if($update_ar->payment_status == 'paid'){
        $ra_check =Rider_Account::where(['advance_return_id' => $update_ar->id, 'type'=>'dr_payable'])->get()->first();
        if(isset($ra_check)){
            $ra_check->delete();
        }

        $ca_check =Company_Account::where(['advance_return_id' => $update_ar->id, 'type'=>'cr'])->get()->first();
        if(isset($ca_check)){
            $ca_check->delete();
        }
        // $ca =Company_Account::firstOrCreate([
        //     'advance_return_id'=>$update_ar->id
        // ]);
        // $ca->advance_return_id =$update_ar->id;
        // $ca->type='dr_receivable';
        // $ca->rider_id=$update_ar->rider_id;
        // $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        // if($update_ar->type=="advance"){$ca->source='advance';}
        // else if($update_ar->type=="return"){$ca->source='loan';}
        // $ca->amount=$r->amount;
        // $ca->save();

        $ra =Rider_Account::firstOrCreate([
            'advance_return_id'=>$update_ar->id
        ]);
        $ra->advance_return_id =$update_ar->id;
        $ra->type='cr_payable';
        $ra->rider_id=$update_ar->rider_id;
        $ra->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
        $ra->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
        if($update_ar->type=="advance"){$ra->source='advance';}
        else if($update_ar->type=="return"){$ra->source='loan';}
        $ra->amount=$r->amount;
        $ra->payment_status="paid";
        $ra->save();
    }
    return redirect(route('admin.AR_edit_view',$update_ar->id));
}
// End ADVANCE & RETURN

public function CE_report(){
    return view('admin.accounts.Company_Expense.CE_report');
}
public function get_investment_detail($month){   
   $ca_credit=Company_Account::where("type","cr")->whereMonth('month',Carbon::parse($month)->format('m'))->sum('amount');
   $ca_debit=Company_Account::where("type","dr")->whereMonth('month',Carbon::parse($month)->format('m'))->sum('amount');
   $available_balance=$ca_credit-$ca_debit;
   
    return response()->json([
        'available_balance'=>$available_balance,
    ]);
}
}
