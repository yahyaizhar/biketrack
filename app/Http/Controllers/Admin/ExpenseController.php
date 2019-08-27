<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Expence\Company_CD;
use carbon\carbon;
use App\Model\Accounts\Company_Expense;
use App\Model\Accounts\Company_Account;

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
        
        $ca =Company_Account::find($id);
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

}
