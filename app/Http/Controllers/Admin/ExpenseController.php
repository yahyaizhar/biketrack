<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Expence\Company_CD;
use carbon\carbon;
use App\Model\Accounts\Company_Expense;
use App\Model\Accounts\Company_Account;
use App\Model\Rider\Rider;
use App\Model\Accounts\WPS;

class ExpenseController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function CE_index(){
        return view('admin.accounts.Company_Expense.CE_add');
    }
    public function CE_store(Request $r){
        $ce=new Company_Expense();
        $ce->amount=$r->amount;
        $ce->description=$r->description;
        if($r->status)
                $ce->status = 1;
            else
                $ce->status = 0;
        $ce->save();
        
        $ca = new Company_Account();
        $ca->type='dr';
        $ca->amount=$r->amount;
        $ca->source='company_expense';
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
    $edit_expense=Company_Expense::find($id);
    return view('admin.accounts.Company_Expense.CE_edit',compact('edit_expense'));
}

public function wps_index(){
    $riders=Rider::where("active_status","A")->get();
    return view('admin.accounts.WPS.wps_add',compact('riders'));
}
public function wps_store(Request $r){
    $wps=new WPS();
    $wps->amount=$r->amount;
    $wps->bank_name=$r->bank_name;
    $wps->payment_status=$r->payment_status;
    $wps->rider_id=$r->rider_id;
    if($r->status)
            $wps->status = 1;
        else
            $wps->status = 0;
    $wps->save();
    
    $ca = new Company_Account();
    $ca->type='dr';
    $ca->amount=$r->amount;
    $ca->source='wps';
    $ca->wps_id=$wps->id;
    $ca->save();
    return redirect(route('admin.wps_view'));
}
public function wps_view(){
 return view('admin.accounts.WPS.wps_view');
}
public function wps_edit($id){
    $riders=Rider::where("active_status","A")->get();
    $edit_wps=WPS::find($id);
    return view('admin.accounts.WPS.wps_edit',compact('edit_wps','riders'));  
}
public function wps_update(Request $r,$id){
    $wps=WPS::find($id);
    $wps->amount=$r->amount;
    $wps->bank_name=$r->bank_name;
    $wps->payment_status=$r->payment_status;
    $wps->rider_id=$r->rider_id;
    if($r->status)
            $wps->status = 1;
        else
            $wps->status = 0;
    $wps->save();
    
    $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
        'wps_id'=>$wps->id
    ]);
    $ca->type='dr';
    $ca->amount=$r->amount;
    $ca->source='wps';
    $ca->save();
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


}
