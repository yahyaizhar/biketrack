<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Bikes\bike;
use App\Model\Bikes\bike_detail;
use App\Model\Client\Client;
use Illuminate\Support\Facades\Hash;
use App\Model\Rider\Rider;
use App\Model\Admin\Company_info;
use App\Model\Client\Client_Rider;
use Illuminate\Support\Facades\Storage;
use App\Model\Rider\Rider_Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Model\Sim\Sim;
use App\PurchasedInvoice;
use App\Model\Sim\Sim_Transaction;
use App\Model\Sim\Sim_History;
use App\Model\Mobile\Mobile;
use App\Model\Mobile\Mobile_installment;
use App\Model\Mobile\Mobile_Transaction;
use carbon\carbon;
use App\Model\Mobile\Accessory;
use App\Model\Mobile\Seller;
use App\Model\Mobile\MobileHistory;

class MobileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    } 
    public function add_purchased_invoices(){
        return view('admin.rider.add_purchased_invoices');
    }
    public function submit_purchased_invoices(Request $request){
        $data = new PurchasedInvoice;
        $data->invoice_purchase_id = $request->invoice_purchase_id;
        $data->invoice_amount = $request->invoice_amount;
        $data->purchasing_date = $request->purchasing_date;
        if($request->hasFile('invoice_picture'))
        {
            $filename = $request->invoice_picture->getClientOriginalName();
            $filesize = $request->invoice_picture->getClientSize();
            $filepath = Storage::putfile('public/uploads/riders/profile_pics', $request->file('invoice_picture'));
            $data->invoice_picture = $filepath;
        }
        $data->tex_amount = $request->tex_amount;
        $data->save();
        return redirect()->back()->with('message', 'Record Added Successfully.');;
    }
    // start mobile section
    public function update_mobile_GET($id){
        $mobile_edit=Mobile::find($id);
        return view('admin.rider.mobile.edit', compact('mobile_edit'));
    }

    public function update_mobile(Request $r,$id){
        $data=$r->mobiles;
        if (isset($r->mobiles)) {
            foreach ($data as $item) {
                $mobile_update=Mobile::find($id); 
                $mobile_update->model=$item['model'];
                $mobile_update->brand=$item['brand'];
                $mobile_update->imei_1=$item['imei_1'];
                $mobile_update->imei_2=$item['imei_2'];
                $mobile_update->purchase_price=$item['purchase_price'];
                $mobile_update->vat_paid=$item['vat_paid'];
                $mobile_update->sale_price=$item['sale_price'];
                $mobile_update->remaining_amount=($item['sale_price'])-($mobile_update->amount_received);
                $mobile_update->payment_status="pending";
                if ($mobile_update->remaining_amount<=0) {
                    $mobile_update->payment_status="paid";
                }
                $mobile_update->purchased_invoice_id=$r->invoice_purchase_id;
                $mobile_update->seller_id=$r->seller_detail;
                $mobile_update->purchasing_date=carbon::parse($r->purchasing_date)->format("Y-m-d");
                if($r->hasFile('invoice_picture'))
                {
                    if($mobile_update->invoice_picture)
                    {
                        Storage::delete($mobile_update->invoice_picture);
                    }
                    $filename = $r->invoice_picture->getClientOriginalName();
                    $filesize = $r->invoice_picture->getClientSize();
                    $filepath = Storage::putfile('public/uploads/riders/invoice_picture', $r->file('invoice_picture'));
                    $mobile_update->invoice_picture = $filepath;
                }
                
                $mobile_update->update();
            }
        }
        return redirect(route('mobile.show'))->with('message', 'Record Updated Successfully.');

    }

    public function create_mobile_GET(){
        $sellers=Seller::where("active_status","A")->get(); 
        return view('admin.rider.mobile.create',compact('sellers'));
    }
    public function create_mobile_POST(Request $r)
    {
        $data=$r->mobiles;
        if (isset($r->mobiles)) {
            foreach ($data as $item) {
                $mobile = new Mobile;
                $mobile->model=$item['model'];
                $mobile->brand=$item['brand'];
                $mobile->imei_1=$item['imei_1'];
                $mobile->imei_2=$item['imei_2'];
                $mobile->purchase_price=$item['purchase_price'];
                $mobile->vat_paid=$item['vat_paid'];
                $mobile->sale_price=$item['sale_price'];
                $mobile->remaining_amount=$item['sale_price'];
                $mobile->amount_received="0";
                $mobile->purchased_invoice_id=$r->invoice_purchase_id;
                $mobile->seller_id=$r->seller_detail;
                $mobile->purchasing_date=carbon::parse($r->purchasing_date)->format("Y-m-d");
                if($r->hasFile('invoice_picture'))
                {
                    $filename = $r->invoice_picture->getClientOriginalName();
                    $filesize = $r->invoice_picture->getClientSize();
                    $filepath = Storage::putfile('public/uploads/riders/invoice_picture', $r->file('invoice_picture'));
                    $mobile->invoice_picture = $filepath;
                }
                $mobile->save();
            }
        }
        if ($r->check_accessory==1) {
            $data_accessory=$r->accessory;
            if (isset($r->accessory)) {
                foreach ($data_accessory as $value) {
                    $accessory= new Accessory;
                    $accessory->description=$value['description'];
                    $accessory->amount=$value['amount'];
                    $accessory->seller_id=$r->seller_detail;
                    $accessory->purchasing_date=carbon::parse($r->purchasing_date)->format("Y-m-d");
                    $accessory->save();
                }
            }
        }
    }
    public function mobiles(){
        $mobiles=Mobile::all();
        $mobile_count=$mobiles->count();
        return view('admin.rider.mobile.mobiles', compact('mobiles','mobile_count'));
    }
    public function create_mobileInstallment(){
        $mobiles=Mobile::all();
        $riders=Rider::where("active_status","A")->get();
        return view('admin.rider.mobile.create_installment',compact('mobiles','riders'));
    }
    public function store_mobileInstallment(Request $request){
        $installment=new Mobile_installment();
        $installment->mobile_id=$request->mobile_id;
        $installment->month_year=carbon::parse($request->month_year)->startOfMonth()->format("Y-m-d");
        $installment->given_date=carbon::parse($request->given_date)->format("Y-m-d");
        $installment->per_month_installment_amount=$request->per_month_installment_amount;
        $installment->save();

        $mobile=Mobile::where("id",$request->mobile_id)->get()->first();
        $mobile->remaining_amount=$request->remaining_amount;
        $mobile->amount_received=$request->amount_received;
        if ($mobile->remaining_amount=="0") {
            $mobile->payment_status="paid";
        }
        $mobile->save();
        $rider_id=0;
        $mobile_history=MobileHistory::where("mobile_id",$request->mobile_id)->get()->first();
        if (isset($mobile_history)) {
            $rider_id=$mobile_history->rider_id;
        }

        $ra =new \App\Model\Accounts\Rider_Account;
        $ra->mobile_installment_id =$installment->id;
        $ra->type='cr_payable';
        $ra->rider_id=$rider_id;
        $ra->month =carbon::parse($request->month_year)->startOfMonth()->format("Y-m-d");
        $ra->source="Mobile Installment";
        $ra->amount=$request->per_month_installment_amount;
        $ra->given_date=carbon::parse($request->given_date)->format("Y-m-d");
        $ra->payment_status="paid";
        $ra->save();
        return redirect(route('mobile.show'));
    }

    public function consumption_mobile_records($mobile_id,$month){
        $mobile=Mobile::where('id',$mobile_id)->get()->first();
            $sale_price=$mobile->sale_price;
            $remaining_amount=$mobile->remaining_amount;
            $amount_received=$mobile->amount_received;
            $mobile_history=MobileHistory::where('mobile_id',$mobile_id)->get()->first();
            $rider_id_mh=null;
            $payment_type_mh=null;
            if (isset($mobile_history)) {
               $rider_id_mh=$mobile_history->rider_id;
               $payment_type_mh=$mobile_history->payment_type; 
            }
        return response()->json([
            'data' => true,
            'sale_price'=>$sale_price,
            'remaining_amount'=>$remaining_amount,
            'amount_received'=>$amount_received,
            'rider_id'=>$rider_id_mh,
            'payment_type'=>$payment_type_mh,
        ]); 
    }
    public function addSellerDeatil(Request $r){
    
    $seller=new Seller;
    $seller->name=$r->name;
    $seller->address=$r->address;
    $seller->phone_number=$r->phone_number;
    $seller->save();
    $seller_all=Seller::all();
        return response()->json([
        'seller_id'=>$seller->id,
        'seller_all'=>$seller_all,
        ]);

    }
    public function mobile_assign_to_rider($id){
    $rider=Rider::find($id);
    $mobiles=Mobile::where("active_status","A")->get();

    $mobile_histories=$rider->MobileHistory()->get();
    $mobile_history_count=$mobile_histories->count();
    return view('admin.rider.mobile.assign_mobile_to_rider',compact('rider','mobiles','mobile_history_count'));
    }
    public function mobile_is_assigned_to_rider(Request $request,$rider_id){
    $assign_mobile=new MobileHistory;
    $assign_mobile->mobile_id=$request->mobile_id;
    $assign_mobile->rider_id=$rider_id;
    $assign_mobile->mobile_assign_date=carbon::parse($request->mobile_assign_date)->format('Y-m-d');
    $assign_mobile->mobile_unassign_date=carbon::parse($request->mobile_assign_date)->format('Y-m-d');
    $assign_mobile->payment_type=$request->payment_type;
    if ($request->payment_type=="installment") {
        $assign_mobile->installment_amount=$request->installment_amount;
        $assign_mobile->installment_starting_date=carbon::parse($request->installment_starting_date)->format('Y-m-d');
        $assign_mobile->installment_ending_date=carbon::parse($request->installment_ending_date)->format('Y-m-d');

        $installment=new Mobile_installment();
        $installment->mobile_id=$request->mobile_id;
        $installment->month_year=carbon::parse($request->mobile_assign_date)->startOfMonth()->format("Y-m-d");
        $installment->given_date=carbon::parse($request->mobile_assign_date)->format("Y-m-d");
        $installment->per_month_installment_amount=$request->installment_amount;
        $installment->save();

        $ra =new \App\Model\Accounts\Rider_Account;
        $ra->mobile_installment_id =$installment->id;
        $ra->type='cr_payable';
        $ra->rider_id=$rider_id;
        $ra->month =carbon::parse($request->installment_starting_date)->startOfMonth()->format('Y-m-d');
        $ra->source="Mobile Installment";
        $ra->amount=$request->installment_amount;
        $ra->given_date=carbon::parse($request->installment_starting_date)->startOfMonth()->format('Y-m-d');
        $ra->payment_status="paid";
        $ra->save();
    }
        
    $assign_mobile->save();

    $mobile_sale=Mobile::find($request->mobile_id);
    $mobile_sale->sale_price=$request->sale_price;
    $mobile_sale->remaining_amount=$request->sale_price;
    $mobile_sale->save();

    $mobile=Mobile::find($request->mobile_id);
    $sale_price=$mobile->sale_price;
    if ($request->payment_type=="installment") {
        $mobile->amount_received=$request->installment_amount;
        $mobile->remaining_amount=$sale_price-($request->installment_amount);
    }
    if ($mobile->remaining_amount==0) {
        $mobile->payment_status="paid";
    }
    if ($request->payment_type=="cash") {
        $mobile->remaining_amount=0;
        $mobile->amount_received=$sale_price;
        $mobile->payment_status="paid";
    }
    $mobile->active_status="D";
    $mobile->save();
    

    return redirect(url('/admin/mobile/rider_history',$rider_id));
    
    }
    public function mobile_rider_history($rider_id){
    $rider=Rider::find($rider_id);
    $mobile_histories=$rider->MobileHistory()->get();
    $mobile_history_count=$mobile_histories->count();
    return view('admin.rider.mobile.mobile_history',compact('rider','mobile_histories','mobile_history_count')); 
    }
    public function change_Mobile_given_date(Request $request,$rider_id,$mobile_history_id){
    $mobile_history=MobileHistory::find($mobile_history_id);
    $mobile_history->mobile_assign_date=carbon::parse($request->mobile_assign_date)->format('Y-m-d');
    $mobile_history->mobile_unassign_date=carbon::parse($request->mobile_assign_date)->format('Y-m-d');
    $mobile_history->save();

    return response()->json([
        'mobile_history'=>$mobile_history,
        'rider_id'=>$rider_id,
        'mobile_history_id'=>$mobile_history_id,
    ]);
    }

    public function Mobile_view_ivoice_profile($mobile_id){
        $sellers=Seller::all();
        $mobile=Mobile::find($mobile_id);
        return view('admin.rider.mobile.view_mobile_invoices',compact('sellers','mobile'));
    }

    public function sellers_view(){
        return view('admin.rider.mobile.Sellers.view_sellers');
    }
    public function sellers_edit($seller_id){
        $seller=Seller::find($seller_id);
        return view ("admin.rider.mobile.Sellers.edit_seller",compact('seller'));
    }
    public function sellers_update(Request $request,$seller_id){
        $seller=Seller::find($seller_id);
        $seller->name=$request->name;
        $seller->address=$request->address;
        $seller->phone_number=$request->phone_number;
        $seller->update();
        return redirect(route('mobile.sellers_view'));
    }
    public function accessory_view(){
        return view('admin.rider.mobile.Accessory.view_accessory'); 
    }
    public function accessory_edit($accessory_id){
        $accessory=Accessory::find($accessory_id);
        return view ("admin.rider.mobile.Accessory.edit_accessory",compact('accessory'));
    }
    public function accessory_update(Request $request,$accessory_id){
        $accessory=Accessory::find($accessory_id);
        $accessory->description=$request->description;
        $accessory->amount=$request->amount;
        $accessory->purchasing_date=carbon::parse($request->purchasing_date)->format('Y-m-d');
        $accessory->update();
        return redirect(route('mobile.accessory_view'));
    }
}

