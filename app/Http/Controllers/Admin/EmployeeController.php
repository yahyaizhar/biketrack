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

    public function viewCompanyEmployeeAccount(){
        return view('admin.Employee.company_employee_account');
    }

    public function viewEmployeeAccount(){
        return view('admin.Employee.employee_account');
    }
}
