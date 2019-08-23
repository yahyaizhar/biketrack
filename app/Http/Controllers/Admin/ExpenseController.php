<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Expence\Company_CD;
use carbon\carbon;

class ExpenseController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function company_debit_index()
    {
        return view('admin.accounts.company_debit');
    }
    /**
     * ajax request for salary deduction.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function company_debit_getSalaryDeduction($month, $rider_id)
    {
        $total_salary=27000;
        $total_deduction = 0;
        $salary_deducted = \App\Model\Expense\Company_CD::whereMonth('month', $month)
        ->where(['rider_id' => $rider_id, 'type' => 'dr', 'type_db' =>'advance', 'advance_deducted_by'=>'salary'])
        ->sum('amount');
        $total_deduction+=$salary_deducted;

        $gross_salary = $total_salary - $total_deduction;
        return response()->json([
            'total_salary'=>$total_salary,
            'total_deduction'=>$total_deduction,
            'gross_salary'=>$gross_salary
        ]);
    }
    //company_debit_getSalaryDeduction
    public function insert_company_DC(Request $request){
        $this->validate($request, [
            'type_db'=> 'required | string |max:255',
            'month' => 'required | string | max:255',
            'amount'=> 'required | string |max:255',
            'advance_deducted_by'=> 'required | string |max:255',
            'advance_notes'=> 'required | string |max:255',
        ]);
        $val=new Company_CD();
        $val->type="dr";
        if($request->type_db=="advance"){
            $val->rider_id=$request->rider_id;
            $val->type_db=$request->type_db;
            $val->month=Carbon::parse($request->get('month'))->format('Y-m-d');
            $val->amount=$request->amount;
            $val->advance_deducted_by=$request->advance_deducted_by;
            $val->advance_notes=$request->advance_notes;
        }
        $val->save();
        return redirect(route('admin.accounts.company_debits'));
    }
}
