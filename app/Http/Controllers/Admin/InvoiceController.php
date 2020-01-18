<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Model\Client\Client;
use App\Model\Accounts\Client_Income;
use App\Model\Client\Client_History;
use App\Model\Invoice\Invoice;
use App\Model\Invoice\Invoice_Payment;
use App\Tax_method;
use App\Model\Bank\Bank_account;
use App\Model\Bank\Bank_transaction;
use App\Model\Invoice\Invoice_item;
use App\Model\Accounts\Company_Account;
use App\Model\Accounts\Rider_Account;
use App\Model\Accounts\Income_zomato;
use App\Model\Client\Client_Rider;
use App\Model\Admin\Company_info;
use App\Model\Admin\Admin;  
use Arr;
use DB;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    } 
    public function add_invoice(){
        $company_info = Company_info::all()->first();
        $clients=Client::where("active_status","A")->get();
        return view('admin.Invoices.add_invoice',compact('clients','company_info'));
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
        $invoice->invoice_id=$r->invoice_id;

        $invoice->invoice_total=$r->invoice_total;
        $invoice->invoice_subtotal=$r->invoice_subtotal;

        $invoice->taxable_subtotal=$r->taxable_subtotal;
        $invoice->tax_method_id=$r->tax_method_id;
        $invoice->taxable_amount=$r->tax_value;

        
        $invoice->invoice_date=Carbon::createFromFormat('F d, Y', $r->invoice_date)->format('Y-m-d');
        $invoice->invoice_due=Carbon::createFromFormat('F d, Y', $r->due_date)->format('Y-m-d');

        $invoice->payment_status="pending";
        $invoice->generated_by=Auth::user()->id;
        
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

        //received amount
        $invoice_payment=$invoice->Invoice_payment;
        $invoice_modal['invoice']['invoice_payment']=$invoice_payment;
        if(count($invoice_payment) > 0){
            //payments received
            $total_payment = $invoice_payment->sum('payment');
            $balance_due = $invoice->invoice_total - $total_payment;

            //updating the invoice
            $inv_paymentStatus="pending";
            $inv_invoiceStatus="partially_paid";
            $inv_receivedDate=null;
            $inv_status="open";
            if($balance_due==0){
                //invoice paid
                $inv_paymentStatus="paid";
                $inv_invoiceStatus="paid";
                $inv_receivedDate=Carbon::now()->format('Y-m-d');
                $inv_status="closed";
            }
            $invoice->amount_paid=$total_payment;
            $invoice->due_balance=$balance_due;
            $invoice->payment_status=$inv_paymentStatus;
            $invoice->invoice_status=$inv_invoiceStatus;
            $invoice->received_date =$inv_receivedDate;
            $invoice->status=$inv_status;
            $invoice->save();


            
            // return response()->json([
            //     'status'=>21,
            //     'invoice'=>$balance_due
            // ]);
            if($balance_due<0){
                //delete all payments and add a new one

                // $last_payment=$invoice_payment->last();

                
                $invoice_payment = new Invoice_Payment;
                $invoice_payment->invoice_id=$invoice->id;

                $payment_amount=$invoice->invoice_total;
                $inv_status='closed';
                $due=0;

                $invoice->amount_paid=$invoice->invoice_total;
                $invoice->due_balance=$due;
                $invoice->payment_status='paid';
                $invoice->invoice_status='paid';
                $invoice->received_date =Carbon::now()->format('Y-m-d');
                $invoice->status=$inv_status;



                //saving payment
                $payment_method='cash';
                $invoice_payment->payment_method=$payment_method;
                // if($payment_method=="bank"){
                //     $invoice_payment->bank_id=$last_payment->bank_id; 
                //     //saving transaction
                //     $bank_transaction=new Bank_transaction;
                //     $bank_transaction->bank_id=$last_payment->bank_id;
                //     $bank_transaction->type='cr';
                //     $bank_transaction->amount=$payment_amount;
                //     $bank_transaction->source=get_class($invoice);
                //     $bank_transaction->source_id=$invoice->id;
                //     $bank_transaction->created_by=Auth::user()->id;
                //     $bank_transaction->save();
                // }

                $invoice_payment->payment_date=Carbon::now()->format('Y-m-d'); 
                $invoice_payment->payment=$payment_amount;

                $invoice_payment->payment_received_by=Auth::user()->id; 
                $invoice_payment->status=$inv_status;
                $invoice_payment->notes='AED '.abs($balance_due).' is due to the customer'; 

                
                $invoice->Invoice_payment()->delete();
                $invoice->save();
                $invoice_payment->save();
                $invoice_modal['invoice']['invoice_payment']=$invoice_payment;


            }
        }

        
        return response()->json([
            'status'=>1,
            'invoice'=>$invoice_modal['invoice']
        ]);
    }

    public function save_payment(Request $r){
        $payments = $r->payments;
        foreach ($payments as $payment) {
            $payment_method=$r->payment_method;
            $invoice = Invoice::find($payment['invoice_id']);
            $invoice_payment = new Invoice_Payment;
            $invoice_payment->invoice_id=$invoice->id;

            $payment_amount=$payment['amount'];
            if($payment_amount <=0) continue;
            $total_paid =round($invoice->amount_paid + $payment_amount,2);
            $due = round($invoice->due_balance - $payment_amount,2);

            //updating the invoice
            $inv_paymentStatus="pending";
            $inv_invoiceStatus="partially_paid";
            $inv_receivedDate=null;
            $inv_status="open";
            if($due<=0){
                //invoice paid
                $inv_paymentStatus="paid";
                $inv_invoiceStatus="paid";
                $inv_receivedDate=Carbon::now()->format('Y-m-d');
                $inv_status="closed";
            }
            $invoice->amount_paid=$total_paid;
            $invoice->due_balance=$due;
            $invoice->payment_status=$inv_paymentStatus;
            $invoice->invoice_status=$inv_invoiceStatus;
            $invoice->received_date =$inv_receivedDate;
            $invoice->status=$inv_status;



            //saving payment
            $invoice_payment->payment_method=$payment_method;
            if($payment_method=="bank"){
                $invoice_payment->bank_id=$r->bank_id; 
                //saving transaction
                $bank_transaction=new Bank_transaction;
                $bank_transaction->bank_id=$r->bank_id;
                $bank_transaction->type='cr';
                $bank_transaction->amount=$payment_amount;
                $bank_transaction->source=get_class($invoice);
                $bank_transaction->source_id=$invoice->id;
                $bank_transaction->created_by=Auth::user()->id;
                $bank_transaction->save();
            }

            $invoice_payment->payment_date=Carbon::parse($r->payment_date)->format('Y-m-d'); 
            $invoice_payment->payment=$payment_amount;

            $invoice_payment->payment_received_by=Auth::user()->id; 
            $invoice_payment->status=$inv_status; 

            

            $invoice->save();
            $invoice_payment->save();
        }
        return response()->json([
            'status'=>1,
            'data'=>Invoice_Payment::all()
        ]);
    }
    public function getOpenIvoices($client_id)
    {

        $open_invoices = Invoice::where([
            'client_id'=>$client_id,
            'status'=>'open',
            'active_status'=>'A'
        ])
        ->get();
        return response()->json([
            'open_invoices'=>$open_invoices
        ]);

    }
    public function get_invoice_by_id($invoice_id){
        $invoice = Invoice::where('invoice_id', $invoice_id)
        ->get()
        ->first();
        if(isset($invoice)){
            return response()->json([
                'status'=>1,
                'invoice'=>$invoice
            ]);
        }
        return response()->json([
            'status'=>0
        ]);
    }
    public function get_ajax_client_details($client_id, $formatted_month){
        $client=Client::find($client_id);
        $client_riders=Client_History::where('client_id', $client->id)->get();
        $billing_address=$client->address;
        $month = Carbon::parse($formatted_month)->format('m');

        $open_invoice = Invoice::with('Invoice_item')->with('Invoice_payment')->where([
            'client_id'=>$client_id,
            'month'=>$formatted_month,
            'active_status'=>'A'
        ])
        // ->where(function($q) {
        //     $q->where('invoice_status', "partially_paid")
        //       ->orWhere('invoice_status', 'paid');
        // })
        ->get()
        ->first();
        

        $client_settings = json_decode( $client->setting,true );
        $payment_method = $client_settings['payout_method'];

        if(isset($open_invoice)){ // an invoice is found 
            $invoice_items = $open_invoice->Invoice_item;
            $invoice_items_data=[];
            foreach ($invoice_items as $invoice_item) {
                $obj=[
                    'desc'=>$invoice_item->item_desc,
                    'amount'=> $invoice_item->item_amount,
                    'rate'=> $invoice_item->item_rate,
                    'qty'=> $invoice_item->item_qty,
                    'is_taxable'=> $invoice_item->tax_method_id!=null?true:false,
                    'is_deductable'=> $invoice_item->deductable==0?false:true
                ];
                array_push($invoice_items_data,$obj);
            }
            $genrated_by=Admin::find($open_invoice->generated_by);
            $open_invoice->generated_by=$genrated_by;
            $open_invoice->client=$client;

            $next_invoice_id=$open_invoice->invoice_id;
            return response()->json([
                'status'=>1,
                'billing_address' => $billing_address,
                'payment_method'=>$payment_method,
                'items'=>$invoice_items_data,
                'invoice'=>$open_invoice,
                'is_edit'=>true,
                'next_id'=>$next_invoice_id
            ]);
        }
        
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

                }
                if ($total_hours == 0) {
                    //zomato payour sheet isnot imported
                    return response()->json([
                        'status'=>0,
                        'message'=>'Customer\'s Payout sheet for '.Carbon::parse($formatted_month)->format('M Y').' is not imported yet.'
                    ]);
                }
                $total_hours_payable=$total_hours*$per_hour_amount;
                $total_trips_payable=$total_trips*$per_trip_amount;
                // end rider summary
                $invoice_items_data=[
                    [
                       'desc'=>'No of Hours for riders',
                       'amount'=> round($total_hours_payable,2),
                       'rate'=> $per_hour_amount,
                       'qty'=> round($total_hours,2),
                       'is_taxable'=> true,
                       'is_deductable'=> false
                    ],
                    [
                        'desc'=>'No of Trips for riders',
                        'amount'=> round($total_trips_payable,2),
                        'rate'=> $per_trip_amount,
                        'qty'=> round($total_trips,2),
                        'is_taxable'=> true,
                        'is_deductable'=> false
                    ],
                    [
                        'desc'=>'Incentives',
                        'amount'=> round($ncw,2),
                        'rate'=> round($ncw,2),
                        'qty'=> 1,
                        'is_taxable'=> false,
                        'is_deductable'=> false
                    ],
                    [
                        'desc'=>'Settlements',
                        'amount'=> round($adhoc,2),
                        'rate'=> round($adhoc,2),
                        'qty'=> 1,
                        'is_taxable'=> false,
                        'is_deductable'=> false
                    ],
                    [
                        'desc'=>'Tips',
                        'amount'=> round($tips,2),
                        'rate'=> round($tips,2),
                        'qty'=> 1,
                        'is_taxable'=> false,
                        'is_deductable'=> false
                    ],
                    [
                        'desc'=>'Deduction against Penalties',
                        'amount'=> round($panalties,2),
                        'rate'=> round($panalties,2),
                        'qty'=> 1,
                        'is_taxable'=> true,
                        'is_deductable'=> true
                    ],
                    [
                        'desc'=>'Deductions agains COD + DC charges',
                        'amount'=> round($dc_deduction,2)+round($mcdonald_deduction,2),
                        'rate'=> round($dc_deduction,2)+round($mcdonald_deduction,2),
                        'qty'=> 1,
                        'is_taxable'=> false,
                        'is_deductable'=> true
                    ]
                ];
                $next_invoice_id=DB::select("SHOW TABLE STATUS LIKE 'invoices'");
                return response()->json([
                    'status'=>1,
                    'billing_address' => $billing_address,
                    'payment_method'=>$payment_method,
                    'items'=>$invoice_items_data,
                    'is_edit'=>false,
                    'next_id'=>$next_invoice_id[0]->Auto_increment
                ]);
                break;
            case 'fixed_based':
                $riders_count = count($client_riders);

                $client_incomes=Client_Income::with('Client')->with('Rider')->whereMonth('month',$month)
                ->where('client_id', $client_id)
                ->get();
                if (count($client_incomes) == 0) {
                    //client payout sheet isnot imported
                    return response()->json([
                        'status'=>0,
                        'message'=>'Customer\'s Payout for '.Carbon::parse($formatted_month)->format('M Y').' is not added yet.'
                    ]);
                }
                $invoice_items_data=[];
                foreach ($client_incomes as $client_income) {
                    $obj=[
                        'desc'=>$client_income->Rider->name.' Payout',
                        'amount'=> $client_income->total_payout,
                        'rate'=> $client_income->total_payout,
                        'qty'=> 1,
                        'is_taxable'=> true,
                        'is_deductable'=> false
                    ];
                    array_push($invoice_items_data,$obj);
                }
                $next_invoice_id=DB::select("SHOW TABLE STATUS LIKE 'invoices'");
                return response()->json([
                    'status'=>1,
                    'billing_address' => $billing_address,
                    'payment_method'=>$payment_method,
                    'items'=>$invoice_items_data,
                    'is_edit'=>false,
                    'next_id'=>$next_invoice_id[0]->Auto_increment
                ]);
                break;
            case 'commission_based':
                return response()->json([
                    'status'=>0,
                    'message'=>'Cannot generate invoices against commission based customers.'
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
        $clients=Client::where("active_status","A")->get();
        $banks=Bank_account::where("active_status","A")->get();
        return view('admin.Invoices.view_invoice',compact('clients', 'banks'));
    }
    public function add_tax_method(){
        return view('admin.Invoices.tax_method');
    }
    public function store_tax_method(Request $request){
        $tax_method = new Tax_method();
        $tax_method->name=$request->name;
        $tax_method->type=$request->type;
        $tax_method->value=$request->value;
        $tax_method->is_default=$request->is_default;
        $tax_method->save();
        return redirect(url('admin/invoice/tax_method/add'))->with('message', 'Record Created Successfully.');
    }
    public function add_bank_account(){
        return view('admin.Invoices.bank_accounts');
    }
    public function view_bank_account(){
        return view('admin.Invoices.view_bank_accounts');
    }
    public function store_bank_account(Request $request){
        $BA = new Bank_account();
        $BA->name=$request->name;
        $BA->account_number=$request->account_number;
        $BA->save();
        return redirect(url('admin/invoice/bank_account/add'))->with('message', 'Record Created Successfully.');
    }
    public function invoive_payments(){
        return view('admin.Invoices.invoice_payments');
    }
    public function company_info(){
        $company_info = Company_info::all()->first();
        return view('admin.Invoices.add_company_info',compact('company_info'));
    }
    public function company_info_store(Request $request){
        $BA = new Company_info();
        $BA_first = Company_info::all()->first();
        if(isset($BA_first)){
            $BA=$BA_first;
        }
        $BA->company_name=$request->company_name;
        $BA->company_address=$request->company_address;
        $BA->company_phone_no=$request->company_phone_no;
        $BA->company_email=$request->company_email;
        $BA->company_tax_return_no=$request->company_trn;
        $BA->company_account_no=$request->company_account_no;
        $BA->company_bank_name=$request->company_bank_name;
        $BA->save();
        return redirect(url('admin/companyinfo'))->with('message', 'Record Created Successfully.');
    }
}
