<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Model\Client\Client;
use App\Model\Client\Client_History;
use App\Model\Client\Invoice;
use App\Model\Client\Invoice_item;
use App\Model\Accounts\Company_Account;
use App\Model\Accounts\Rider_Account;
use App\Model\Accounts\Income_zomato;
use App\Model\Client\Client_Rider;
use Arr;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    } 
    public function add_invoice(){
        $clients=Client::where("active_status","A")->get();
        return view('admin.Invoices.add_invoice',compact('clients'));
    }
    public function add_invoice_post(Request $r){
        $due_date = Carbon::createFromFormat('F d, Y', $r->due_date)->format('Y-m-d');
        $r->invoice_date = Carbon::createFromFormat('F d, Y', $r->invoice_date)->format('Y-m-d');

        // $invoice = new Invoice;
        // $invoice->client_id=$r->client_id;
        // $invoice->invoice_amount=$r->invoice_total;
        // $invoice->month=$r->month;
        // $invoice->client_id=$r->client_id;
        // $invoice->client_id=$r->client_id;
        // $invoice->client_id=$r->client_id;
        // $invoice->client_id=$r->client_id;
        // $invoice->client_id=$r->client_id;
        // $invoice->client_id=$r->client_id;
        // $invoice->client_id=$r->client_id;
        // $invoice->client_id=$r->client_id;
        // $invoice->client_id=$r->client_id;
        // $invoice->client_id=$r->client_id;
        // $invoice->client_id=$r->client_id;
        // $invoice->client_id=$r->client_id;

        return $r->all();
    }
    public function get_ajax_client_details($client_id, $formatted_month){
        $client=Client::find($client_id);
        $client_riders=Client_Rider::where('client_id', $client->id)->get();
        $billing_address=$client->address;
        $month = Carbon::parse($formatted_month)->format('m');

        $client_settings = json_decode( $client->setting,true );
        $payment_method = $client_settings['payout_method'];
        
        switch ($payment_method) {
            case 'trip_based':
                // rider summary
                
                $total_hours=0;
                $total_trips=0;
                
                $aed_trips=0;
                $aed_hours=0;
                $total_hours_payable=0;
                $total_trips_payable=0;

                $ncw = 0;
                $tips=0;
                $adhoc=0;

                $panalties=0;
                $dc_deduction=0;
                $mcdonald_deduction=0;

                $per_trip_amount = $client_settings['tb__trip_amount']; // 6.75
                $per_hour_amount = $client_settings['tb__hour_amount']; // 6
                foreach ($client_riders as $riders) {
                    $_trips=Income_zomato::whereMonth('date',$month)
                    ->where("rider_id",$riders->rider_id)
                    ->sum('trips_payable');
                    

                    $hours=Income_zomato::whereMonth('date',$month)
                    ->where("rider_id",$riders->rider_id)
                    ->sum('log_in_hours_payable');
                    

                    if ($_trips>400) {
                        $extra_trips=($_trips-400)*4;
                        $remain_trips=400*2;
                        $aed_trips+=$extra_trips+$remain_trips;
                    }
                    else{
                        $remain_trips=$_trips*2;
                        $aed_trips+=$remain_trips;
                    }
                    $_hours=$hours;
                    

                    $settlements=Income_zomato::whereMonth('date',$month)
                    ->where("rider_id",$riders->rider_id)
                    ->sum('settlements');
                    $ncw_incentives=Income_zomato::whereMonth('date',$month)
                    ->where("rider_id",$riders->rider_id)
                    ->sum('ncw_incentives');
                    $tips_payout=Income_zomato::whereMonth('date',$month)
                    ->where("rider_id",$riders->rider_id)
                    ->sum('tips_payouts');

                    $danial_panalties=Income_zomato::whereMonth('date',$month)
                    ->where("rider_id",$riders->rider_id)
                    ->sum('denials_penalty');
                    $dc=Income_zomato::whereMonth('date',$month)
                    ->where("rider_id",$riders->rider_id)
                    ->sum('dc_deductions');
                    $mcdonald=Income_zomato::whereMonth('date',$month)
                    ->where("rider_id",$riders->rider_id)
                    ->sum('mcdonalds_deductions');

                    $ncw+=$ncw_incentives;
                    $adhoc+=$settlements;
                    $tips+=$tips_payout;

                    $panalties+=$danial_panalties;
                    $dc_deduction+= $dc;
                    $mcdonald_deduction+=$mcdonald;

                    $total_trips+=$_trips;
                    $total_hours+=$hours;

                    if ($_hours>286) {
                        $_hours=286;
                    }
                    $aed_hours+=$_hours*7.87;

                }
                $total_hours_payable=$total_hours*$per_hour_amount;
                $total_trips_payable=$total_trips*$per_trip_amount;
                // end rider summary
                return response()->json([
                    'status'=>1,
                    'billing_address' => $billing_address,

                    'payment_method'=>$payment_method,
                    'per_hour_amount'=>$per_hour_amount,
                    'per_trip_amount'=>$per_trip_amount,

                    'total_hours'=>round($total_hours,2),
                    'total_trips'=>round($total_trips,2),

                    'aed_trips'=>round($aed_trips,2),
                    'aed_hours'=>round($aed_hours,2),
                    'total_hours_payable'=>round($total_hours_payable,2),
                    'total_trips_payable'=>round($total_trips_payable,2),

                    'ncw'=>round($ncw,2),
                    'tips'=>round($tips,2),
                    'adhoc'=>round($adhoc,2),

                    'panalties'=>round($panalties,2),
                    'dc_deduction'=>round($dc_deduction,2),
                    'mcdonald_deduction'=>round($mcdonald_deduction,2),

                ]);
                break;
            case 'fixed_based':
                $riders_count = count($client_riders);
                $fixed_amount = $client_settings['fb__amount'];
                $total_payable = $fixed_amount * $riders_count;
                return response()->json([
                    'status'=>1,
                    'billing_address' => $billing_address,

                    'payment_method'=>$payment_method,

                    'total_payable'=>$total_payable,
                    'fixed_amount'=>$fixed_amount,
                    'riders_count'=>$riders_count
                ]);
                break;
            case 'commission_based':
                return response()->json([
                    'status'=>1,
                    'billing_address' => $billing_address,

                    'payment_method'=>$payment_method,
                ]);
                break;
            
            default:
                return response()->json([
                    'status'=>0,
                    'message'=>'No payment method is selected for this client.'
                ]);
                break;
        }
        return response()->json([
            'status'=>0,
            'message'=>'No payment method is selected for this client.',
            'billing_address' => $billing_address,
        ]);
    }
    public function view_invoices(){ 
        return view('admin.Invoices.view_invoice'); 
    }
}
