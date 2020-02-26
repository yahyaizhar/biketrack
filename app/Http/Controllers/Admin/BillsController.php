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
use carbon\carbon;
use \App\Model\Accounts\Company_Account;
use \App\Model\Accounts\Bike_Accounts;
use \App\Model\Accounts\Rider_Account;
use App\Assign_bike;
use App\insurance_company;
use Arr;

class BillsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function rider_generated_bills(){
        $clients=Client::all();
        return view('admin.Bills.generated_bills',compact('clients'));
    }
    public function bills_loss(){
        return view('admin.Bills.bills_loss');
    }
    public function get_updatebills(Request $r)
    {
        #fetching bill details
        $source=$r->get('source');
        $rider_id=$r->get('rider_id');
        $month=$r->get('month');
        
        $onlyMonth=Carbon::parse($month)->format('m');
        $onlyYear=Carbon::parse($month)->format('Y');
        $month_start = Carbon::parse($month)->startOfMonth();
        $month_end = Carbon::parse($month)->endOfMonth();

        #fetcing records against source
        $company_accounts = Company_Account::whereMonth('month', $onlyMonth)
        ->whereYear('month',$onlyYear)
        ->where('rider_id', $rider_id)
        ->where('source', $source)
        ->get();
        $source_id='';
        switch ($source) {
            case 'Sim Transaction':
                $source_id='sim_transaction_id';
                break;
            case 'Salik':
                $source_id='salik_id';
                break;
            case 'fuel_expense_vip':
            case 'fuel_expense_cash':
                $source_id='fuel_expense_id';
                break;
            case 'Bike Rent':
                $source_id='bike_rent_id';
                break;
            
            default:
                # No source found - throw exception
                break;
        }
        foreach ($company_accounts as $company_account) {
            $source_value=$company_account[$source_id]; # source table id
            

        }
        /**
         * ---------------WORK FOR TOMORROW------------------
         * 1) get bike id or sim id
         * 2) calculate days 
         * 3) get the already entered amount -if not found then ask from user
         * 4) update the bill in the folowwing tables (CA,RA,source table,export data,bill_changes)
         */

        return $company_accounts;
    }
}
