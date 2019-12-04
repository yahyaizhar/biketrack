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
use App\Tax_method;
use App\Bank_account;
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
        $client_id = $r->client_id;
        $client=Client::find($client_id);
        $month = Carbon::createFromFormat('d F Y', '01 '.$r->month)->format('Y-m-d');

        $invoice_found = Invoice::where(['client_id'=>$client_id, 'month'=>$month])->get()->first();
        $invoice = new Invoice;
        if(isset($invoice_found)){
            $invoice = $invoice_found;
        }
        
        $invoice->client_id=$client_id;
        $invoice->month=$month;

        $invoice->invoice_total=$r->invoice_total;
        $invoice->invoice_subtotal=$r->invoice_subtotal;

        $invoice->taxable_subtotal=$r->taxable_subtotal;
        $invoice->tax_method_id=$r->tax_method_id;
        $invoice->taxable_amount=$r->tax_value;

        
        $invoice->invoice_date=Carbon::createFromFormat('F d, Y', $r->invoice_date)->format('Y-m-d');
        $invoice->invoice_due=Carbon::createFromFormat('F d, Y', $r->due_date)->format('Y-m-d');

        $invoice->payment_status="pending";
        $invoice->generated_by=Auth::user()->id;
        
        $invoice->bank_id="0";
        $invoice->amount_paid=0;
        $invoice->due_balance=$r->invoice_total;

        // $invoice->received_date=$r->client_id;
        $invoice->invoice_status=$r->invoice_status;
        if (isset($r->discount) && $r->discount!=null && isset($r->discount_values) && $r->discount_values!=null) {
            $invoice->discount_type=$r->discount;
            $invoice->discount_value=$r->discount_values;
            $invoice->discount_amount=$r->discount_amount;
        }
        $invoice->message_on_invoice=$r->message_on_invoice;
        $invoice->billing_address=$r->billing_address;
        $invoice->status="open";

       $invoice->save();
       $invoice_modal = [];
       $invoice_modal['invoice']=$invoice->toArray();
       $invoice_modal['invoice']['invoice_items']=[];
       $invoice_modal['invoice']['client']=$client;
       Invoice_item::where('invoice_id', $invoice->id)->delete();
        foreach ($r->invoice_items as $invoice_itemObj) {
            
            $invoice_item = new Invoice_item;
            
            $invoice_item->invoice_id=$invoice->id;
            $invoice_item->item_desc=$invoice_itemObj['description'];
            $invoice_item->item_qty=$invoice_itemObj['qty'];
            $invoice_item->item_rate=$invoice_itemObj['rate'];
            $invoice_item->deductable=0;
            if(isset($invoice_itemObj['deductable'])){
                $invoice_item->deductable=1;
            }
            
            $invoice_item->item_amount= round($invoice_itemObj['qty'] * $invoice_itemObj['rate'],2) ;
            if(isset($invoice_itemObj['tax'])){
                $invoice_item->tax_method_id=$r->tax_method_id;
                $invoice_item->taxable_amount=$invoice_itemObj['tax_amount'];
            }
            $invoice_item->subtotal=$invoice_itemObj['amount'];
            $invoice_item->save();
            array_push($invoice_modal['invoice']['invoice_items'], $invoice_item->toArray());
        }

        
        return response()->json([
            'status'=>1,
            'invoice'=>$invoice_modal['invoice']
        ]);
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
    public function add_tax_method(){
        return view('admin.Invoices.tax_method');
    }
    public function store_tax_method(Request $request){
        $tax_method = new Tax_method();
        $tax_method->name=$request->name;
        $tax_method->type=$request->type;
        $tax_method->value=$request->value;
        $tax_method->save();
        return redirect(url('admin/invoice/tax_method/add'))->with('message', 'Record Created Successfully.');
    }
    public function add_bank_account(){
        return view('admin.Invoices.bank_accounts');
    }
    public function store_bank_account(Request $request){
        $BA = new Bank_account();
        $BA->name=$request->name;
        $BA->account_number=$request->account_number;
        $BA->save();
        return redirect(url('admin/invoice/bank_account/add'))->with('message', 'Record Created Successfully.');
    }
}
