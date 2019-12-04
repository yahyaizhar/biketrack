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
use App\Company_investment;
use App\Model\Accounts\EmployeeAccounts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Model\Sim\Sim;
use App\Model\Sim\Sim_Transaction;
use App\Model\Sim\Sim_History;
use App\Model\Admin\Admin;


class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function salary_generated(){
        $employees=Admin::where("type","normal")->get();
        $sims=Sim::where("active_status","A")->get();
        return view("admin.Employee.view_accounts",compact("employees","sims"));
    }
    public function employee_bonus(Request $request){
        $bonus=new EmployeeAccounts();
        $bonus->type="cr";
        $bonus->amount=$request->amount;
        $bonus->month=Carbon::parse($request->month)->startOfMonth()->format("Y-m-d");
        $bonus->given_date=Carbon::parse($request->given_date)->format("Y-m-d"); 
        $bonus->source="Bonus";
        $bonus->payment_status="paid";
        $bonus->employee_id=$request->employee_id;
        $bonus->save();
    }
    public function employee_fine(Request $request){
        $fine=new EmployeeAccounts();
        $fine->type="dr";
        $fine->amount=$request->amount;
        $fine->month=Carbon::parse($request->month)->startOfMonth()->format("Y-m-d");
        $fine->given_date=Carbon::parse($request->given_date)->format("Y-m-d"); 
        $fine->source=$request->source;
        $fine->payment_status="paid";
        $fine->employee_id=$request->employee_id;
        $fine->save();
    }
}
