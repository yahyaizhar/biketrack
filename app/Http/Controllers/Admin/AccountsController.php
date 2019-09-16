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
use App\Model\Accounts\Rider_salary;
use App\Model\Accounts\Id_charge;
use App\Model\Accounts\Workshop;
use App\Model\Accounts\Maintenance;
use App\Model\Accounts\Rider_Account;
use App\Model\Accounts\Edirham;
use Carbon\Carbon;
use App\Model\Rider\Rider_detail;
use App\Model\Accounts\Fuel_Expense;
use App\Model\Accounts\Client_Income;
use App\Model\Accounts\Income_zomato;
use Arr;
use Batch;
use App\Model\Accounts\Company_Account;


class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function id_charges_index()
    {
        $riders=Rider::where('active_status', 'A')->get();
        return view('admin.accounts.id_charges_add', compact('riders'));
    }
    public function id_charges_post(Request $r)
    {
        $rider=Rider::find($r->rider_id);
        $id_charge = $rider->id_charges()->create([
            'type'=>$r->type,
            'amount'=>$r->amount,
            'month' => Carbon::parse($r->get('month'))->format('Y-m-d'),
            'status'=>$r->status=='on'?1:0,
        ]);
        $ca = new \App\Model\Accounts\Company_Account;
        $ca->type='dr';
        $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->rider_id=$r->rider_id;
        $ca->amount=$r->amount;
        $ca->source='id_charges';
        $ca->id_charge_id=$id_charge->id;
        $ca->save();

        $ra = new \App\Model\Accounts\Rider_Account;
        $ra->type='cr_payable';
        $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ra->rider_id=$r->rider_id;
        $ra->amount=$r->amount;
        $ra->source='id_charges';
        $ra->id_charge_id=$id_charge->id;
        $ra->save();

        return redirect(route('admin.accounts.id_charges_view'));
    }
    public function id_charges_view()
    { 
        return view('admin.accounts.id_charges_view');
    }
    public function id_charges_edit($id){
        $riders=Rider::where('active_status', 'A')->get();
        $id_charges=Id_charge::find($id);
        return view('admin.accounts.id_charges_edit',compact('id_charges','riders'));
    }
    public function id_charges_update(Request $r,$id){
        $rider=Rider::find($r->rider_id);
        $id_charge =Id_charge::find($id);
        $id_charge->rider_id=$rider->id;
        $id_charge->type=$r->type;
        $id_charge->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $id_charge->amount=$r->amount;
        // $id_charge->=$r->;
        if($r->status)
            $id_charge->status = 1;
        else
            $id_charge->status = 0;
        $id_charge->update();

        $ca =\App\Model\Accounts\Company_Account::where("id_charge_id",$id_charge->id)->get()->first();
        $ca->type='dr';
        $ca->rider_id=$r->rider_id;
        $ca->amount=$r->amount;
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->source='id_charges';
        $ca->id_charge_id=$id_charge->id;
        $ca->update();

        $ra =\App\Model\Accounts\Rider_Account::where("id_charge_id",$id_charge->id)->get()->first();
        $ra->type='cr_payable';
        $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ra->rider_id=$r->rider_id;
        $ra->amount=$r->amount;
        $ra->source='id_charges';
        $ra->id_charge_id=$id_charge->id;
        $ra->save();
        return redirect(route('admin.accounts.id_charges_view'));
    }
    public function delete_id_charges($id)
    {
        $id_charge=Id_charge::find($id);
        $id_charge->active_status="D";
        $id_charge->status=0;
        $id_charge->update();

        $ca =\App\Model\Accounts\Company_Account::where("id_charge_id",$id_charge->id)->get()->first();
        if(isset($ca)){
            $ca->active_status="D";
            $ca->update();
        }
        
    }
    public function updateStatusIdCharges($id_charge_id)
    {
        $id_charge=Id_charge::find($id_charge_id);
        if($id_charge->status == 1)
        {
            $id_charge->status = 0;
        }
        else
        {
            $id_charge->status = 1;
        }
        $id_charge->update();
        return response()->json([
            'status' => true
        ]);
    }


    // workshops
    public function workshop_index()
    {
        
        return view('admin.accounts.workshop_add');
    }
    public function workshop_post(Request $r)
    {
        $workshop = Workshop::create([
            'name'=>$r->name,
            'address'=>$r->address,
            'status'=>$r->status=='on'?1:0,
        ]);
        // $workshop->Bike()->attach($workshop->id);
        
        return redirect(route('admin.accounts.workshop_view'));
    }
    public function workshop_view()
    { 
        return view('admin.accounts.workshop_view');
    }
    public function workshop_edit($id){
        $workshop=Workshop::find($id);
        return view('admin.accounts.workshop_edit',compact('workshop'));
    }
    public function workshop_update(Request $r,$id){
        $workshop =Workshop::find($id);
        $workshop->name=$r->name;
        $workshop->address=$r->address;
        // $id_charge->=$r->;
        if($r->status)
            $workshop->status = 1;
        else
            $workshop->status = 0;
        $workshop->update();

        
        return redirect(route('admin.accounts.workshop_view'));
    }
    public function delete_workshop($id)
    {
        $workshop=Workshop::find($id);
        $workshop->active_status="D";
        $workshop->status=0;
        $workshop->update();
    }
    public function updateStatusWorkshop($workshop_id)
    {
        $workshop=Workshop::find($workshop_id);
        if($workshop->status == 1)
        {
            $workshop->status = 0;
        }
        else
        {
            $workshop->status = 1;
        }
        $workshop->update();
        return response()->json([
            'status' => true
        ]);
    }
    //ends workshop

    // edirhams
    public function edirham_index()
    {
        return view('admin.accounts.Edirham.edirham_add');
    }
    public function edirham_post(Request $r)
    {
        $edirham = Edirham::create([
            'amount'=>$r->amount,
            'month' => Carbon::parse($r->get('month'))->format('Y-m-d'),
            'status'=>$r->status=='on'?1:0,
        ]);
        $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
            'edirham_id'=>$edirham->id
        ]);
        $ca->edirham_id =$edirham->id;
        $ca->type='dr';
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->source="edirham"; 
        $ca->amount=$r->amount;
        $ca->save();
        
        return redirect(route('admin.accounts.edirham_view'));
    }
    public function edirham_view()
    { 
        return view('admin.accounts.Edirham.edirham_view');
    }
    public function edirham_edit($id){
        $edirham=Edirham::find($id);
        return view('admin.accounts.Edirham.edirham_edit',compact('edirham'));
    }
    public function edirham_update(Request $r,$id){
        $edirham =Edirham::find($id);
        $edirham->amount=$r->amount;
        $edirham->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        if($r->status)
            $edirham->status = 1;
        else
            $edirham->status = 0;
        $edirham->update();

        $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
            'edirham_id'=>$edirham->id,
            'type' => 'dr'
        ]);
        $ca->edirham_id =$edirham->id;
        $ca->type='dr';
        $ca->source="edirham"; 
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->amount=$r->amount;
        $ca->save();

        return redirect(route('admin.accounts.edirham_view'));
    }
    public function delete_edirham($id)
    {
        $edirham=Edirham::find($id);
        $edirham->active_status="D";
        $edirham->status=0;
        $edirham->update();
    }
    public function updateStatusEdirham($edirham_id)
    {
        $edirham=Edirham::find($edirham_id);
        if($edirham->status == 1)
        {
            $edirham->status = 0;
        }
        else
        {
            $edirham->status = 1;
        }
        $edirham->update();
        return response()->json([
            'status' => true
        ]);
    }
    //ends edirhams

    // maintenance
    public function maintenance_index()
    {
        $workshops=Workshop::where('active_status', 'A')->get();
        $bikes=bike::where("active_status","A")->get();
        return view('admin.accounts.maintenance_add',compact('workshops', 'bikes'));
    }
    public function maintenance_post(Request $r)
    {
        $maintenance = Maintenance::create([
            'maintenance_type'=>$r->maintenance_type,
            'workshop_id'=>$r->workshop_id,
            'bike_id'=>$r->bike_id,
            'month' => Carbon::parse($r->get('month'))->format('Y-m-d'),
            'amount'=>$r->amount,
            'accident_payment_status'=>$r->accident_payment_status,
            'status'=>$r->status=='on'?1:0,
        ]);
        $assign_bike=\App\Assign_bike::where('bike_id', $maintenance->bike_id)->where('status','active')->get()->first();
        $rider_id = null;
        if($assign_bike){
            $rider_id=Rider::find($assign_bike->rider_id)->id;
        }
        if($maintenance->accident_payment_status == 'pending'){
            
            $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id
            ]);
            $ca->maintenance_id =$maintenance->id;
            $ca->type='dr';
            $ca->rider_id=$rider_id;
            $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ca->source="maintenance"; 
            $ca->amount=$r->amount;
            $ca->save();

            $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id
            ]);
            $ra->maintenance_id =$maintenance->id;
            $ra->type='cr_payable';
            $ra->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ra->rider_id=$rider_id;
            $ra->source="maintenance"; 
            $ra->amount=$r->amount;
            $ra->save();
        }
        else if($maintenance->accident_payment_status == 'paid'){
            $ca_check = \App\Model\Accounts\Company_Account::where(['maintenance_id' => $maintenance->id, 'type'=>'dr'])->get()->first();
            if(!isset($ca_check)){
                $ca_dr = new \App\Model\Accounts\Company_Account;
                $ca_dr->maintenance_id =$maintenance->id;
                $ca_dr->type='dr';
                $ca_dr->month = Carbon::parse($r->get('month'))->format('Y-m-d');
                $ca_dr->rider_id=$rider_id;
                $ca_dr->source="maintenance"; 
                $ca_dr->amount=$r->amount;
                $ca_dr->save();
            }
            $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id,
                'type'=>'cr'
            ]);
            $ca->maintenance_id =$maintenance->id;
            $ca->type='cr';
            $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ca->rider_id=$rider_id;
            $ca->source="maintenance"; 
            $ca->amount=$r->amount;
            $ca->save();

            $ra_check = \App\Model\Accounts\Rider_Account::where(['maintenance_id' => $maintenance->id, 'type'=>'cr_payable'])->get()->first();
            if(!isset($ra_check)){
                $ra_dr = new \App\Model\Accounts\Rider_Account;
                $ra_dr->maintenance_id =$maintenance->id;
                $ra_dr->type='cr_payable';
                $ra_dr->month = Carbon::parse($r->get('month'))->format('Y-m-d');
                $ra_dr->rider_id=$rider_id;
                $ra_dr->source="maintenance"; 
                $ra_dr->amount=$r->amount;
                $ra_dr->save();
            }

            $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id,
                'type'=>'dr_payable'
            ]);
            $ra->maintenance_id =$maintenance->id;
            $ra->type='dr_payable';
            $ra->rider_id=$rider_id;
            $ra->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ra->source="maintenance";
            $ra->amount=$r->amount;
            $ra->save();
        }
        else {
            //regular
            
            $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id
            ]);
            $ca->maintenance_id =$maintenance->id;
            $ca->type='dr';
            $ca->rider_id=$rider_id;
            $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ca->source="maintenance"; 
            $ca->amount=$r->amount;
            $ca->save();
        }
        
        return redirect(route('admin.accounts.maintenance_view'));
    }
    public function maintenance_view()
    { 
        return view('admin.accounts.maintenance_view');
    }
    public function maintenance_edit($id){
        $maintenance=Maintenance::find($id);
        $workshops=Workshop::where('active_status', 'A')->get();
        $bikes=bike::where("active_status","A")->get();
        return view('admin.accounts.maintenance_edit',compact('maintenance','workshops','bikes'));
    }
    public function maintenance_update(Request $r,$id){
        $maintenance =Maintenance::find($id);
        $maintenance->maintenance_type=$r->maintenance_type;
        $maintenance->workshop_id=$r->workshop_id;
        $maintenance->bike_id=$r->bike_id;
        $maintenance->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $maintenance->accident_payment_status=$r->accident_payment_status;
        $maintenance->amount=$r->amount;
        // $id_charge->=$r->;
        if($r->status)
            $maintenance->status = 1;
        else
            $maintenance->status = 0;
        $maintenance->update();
        
        $assign_bike=\App\Assign_bike::where('bike_id', $maintenance->bike_id)->where('status','active')->get()->first();
        $rider_id = null;
        if($assign_bike){
            $rider_id=Rider::find($assign_bike->rider_id)->id;
        }
        if($maintenance->accident_payment_status == 'pending'){
            $ra_check = \App\Model\Accounts\Rider_Account::where(['maintenance_id' => $maintenance->id, 'type'=>'dr_payable'])->get()->first();
            if(isset($ra_check)){
                $ra_check->delete();
            }

            $ca_check = \App\Model\Accounts\Company_Account::where(['maintenance_id' => $maintenance->id, 'type'=>'cr'])->get()->first();
            if(isset($ca_check)){
                $ca_check->delete();
            }
            $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id
            ]);
            $ca->maintenance_id =$maintenance->id;
            $ca->type='dr';
            $ca->rider_id=$rider_id;
            $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ca->source="maintenance"; 
            $ca->amount=$r->amount;
            $ca->save();

            $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id
            ]);
            $ra->maintenance_id =$maintenance->id;
            $ra->type='cr_payable';
            $ra->rider_id=$rider_id;
            $ra->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ra->source="maintenance"; 
            $ra->amount=$r->amount;
            $ra->save();
        }
        else if($maintenance->accident_payment_status == 'paid'){
            $ca_check = \App\Model\Accounts\Company_Account::where(['maintenance_id' => $maintenance->id, 'type'=>'dr'])->get()->first();
            if(!isset($ca_check)){
                $ca_dr = new \App\Model\Accounts\Company_Account;
                $ca_dr->maintenance_id =$maintenance->id;
                $ca_dr->type='dr';
                $ca_dr->rider_id=$rider_id;
                $ca_dr->month = Carbon::parse($r->get('month'))->format('Y-m-d');
                $ca_dr->source="maintenance"; 
                $ca_dr->amount=$r->amount;
                $ca_dr->save();
            }
            else {
                $ca_check->maintenance_id =$maintenance->id;
                $ca_check->type='dr';
                $ca_check->rider_id=$rider_id;
                $ca_check->month = Carbon::parse($r->get('month'))->format('Y-m-d');
                $ca_check->source="maintenance"; 
                $ca_check->amount=$r->amount;
                $ca_check->save();
            }
            $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id,
                'type'=>'cr'
            ]);
            $ca->maintenance_id =$maintenance->id;
            $ca->type='cr';
            $ca->rider_id=$rider_id;
            $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ca->source="maintenance"; 
            $ca->amount=$r->amount;
            $ca->save();

            $ra_check = \App\Model\Accounts\Rider_Account::where(['maintenance_id' => $maintenance->id, 'type'=>'cr_payable'])->get()->first();
            if(!isset($ra_check)){
                $ra_dr = new \App\Model\Accounts\Rider_Account;
                $ra_dr->maintenance_id =$maintenance->id;
                $ra_dr->type='cr_payable';
                $ra_dr->month = Carbon::parse($r->get('month'))->format('Y-m-d');
                $ra_dr->rider_id=$rider_id;
                $ra_dr->source="maintenance"; 
                $ra_dr->amount=$r->amount;
                $ra_dr->save();
            }
            else {
                $ra_check->maintenance_id =$maintenance->id;
                $ra_check->type='cr_payable';
                $ra_check->rider_id=$rider_id;
                $ra_check->month = Carbon::parse($r->get('month'))->format('Y-m-d');
                $ra_check->source="maintenance"; 
                $ra_check->amount=$r->amount;
                $ra_check->save();
            }

            $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id,
                'type'=>'dr_payable'
            ]);
            $ra->maintenance_id =$maintenance->id;
            $ra->type='dr_payable';
            $ra->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ra->rider_id=$rider_id;
            $ra->source="maintenance";
            $ra->amount=$r->amount;
            $ra->save();
        }
        else {
            //regular
            $ra_check = \App\Model\Accounts\Rider_Account::where(['maintenance_id' => $maintenance->id])->get();
            if(isset($ra_check)){
                foreach ($ra_check as $del) {
                    $del->delete();
                }
            }

            $ca_check = \App\Model\Accounts\Company_Account::where(['maintenance_id' => $maintenance->id])->get();
            if(isset($ca_check)){
                foreach ($ca_check as $del) {
                    $del->delete();
                }
            }
            $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id
            ]);
            $ca->maintenance_id =$maintenance->id;
            $ca->type='dr';
            $ca->rider_id=$rider_id;
            $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ca->source="maintenance"; 
            $ca->amount=$r->amount;
            $ca->save();
        }
        
        
        return redirect(route('admin.accounts.maintenance_view'));
    }
    public function delete_maintenance($id)
    {
        $maintenance=Maintenance::find($id);
        $maintenance->active_status="D";
        $maintenance->status=0;
        $maintenance->update();
    }
    public function updateStatusMaintenance($maintenance_id)
    {
        $maintenance=Maintenance::find($maintenance_id);
        if($maintenance->status == 1)
        {
            $maintenance->status = 0;
        }
        else
        {
            $maintenance->status = 1;
        }
        $maintenance->update();
        return response()->json([
            'status' => true
        ]);
    }
    //ends maintenence

    // add new salary
    public function add_new_salary_create(){
        $riders=Rider::where("active_status","A")->get();
        return view('accounts.add_new_salary',compact('riders'));
    }
    public function rider_account(){ 
        $riders = Rider::where('active_status', 'A')->get();
        return view('admin.accounts.Rider_Debit.view_account',compact('riders')); 
    }
    public function company_account(){ 
        $riders = Rider::where('active_status', 'A')->get();
        return view('admin.accounts.Company_Debit.view_account',compact('riders')); 
    }
    public function get_rider_account($rider_id, $d1, $d2){
        $rider = Rider::find($rider_id);
        // $opening_balance = $rider->Rider_detail->salary; 
        $opening_balance = 0; 
        $month = 07;
        $rider_statements = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$month)
        ->get();

        $rider_debits_cr_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$month)
        ->where("type","cr_payable")
        ->orWhere('type', 'cr')
        ->sum('amount');
        
        $rider_debits_dr_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$month)
        ->where("type","dr_payable")
        ->orWhere('type', 'dr')
        ->sum('amount');

        $closing_balance = ($opening_balance + $rider_debits_cr_payable) - $rider_debits_dr_payable;
                        
        return view('admin.accounts.Rider_Debit.view_account',compact('closing_balance','rider', 'rider_statements', 'opening_balance')); 
    }
    public function get_salary_deduction($month, $rider_id){   
        // before tthat check if rider is zomato's
        $rider = Rider::find($rider_id);
        $ra_payable=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$month)
        ->where("type","cr_payable")
        ->sum('amount'); 
        $ra_cr=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$month)
        ->where("type","cr")
        ->sum('amount');  
        $feid = $rider->clients()->first()->pivot->client_rider_id;
        if(isset($feid)){ // rider belongs to zomato
            $ra_zomatos=Income_zomato::where("rider_id",$rider_id)
            ->whereMonth("date",$month)
            ->get()->first();  
            $ra_zomatos_no_of_hours =0;
            $ra_zomatos_no_of_trips = 0;
            if(isset($ra_zomatos)){
                $ra_zomatos_no_of_hours = $ra_zomatos->log_in_hours_payable > 286?286:$ra_zomatos->log_in_hours_payable;
                $ra_zomatos_no_of_hours = $ra_zomatos_no_of_hours * 7.87;
                $ra_zomatos_no_of_trips = $ra_zomatos->trips_payable * 2;
            }
        
            
            $ra_deduction = $ra_payable;
            $ra_salary=$ra_zomatos_no_of_hours + $ra_zomatos_no_of_trips + $ra_cr;
            $ra_recieved=$ra_salary - $ra_deduction;
        }
        else { // other clients
            $fixed_salary = $rider->Rider_Detail->salary;
            $fixed_salary = isset($fixed_salary)?$fixed_salary:0;
            $ra_deduction = $ra_payable;
            $ra_salary= $fixed_salary + $ra_cr;
            $ra_recieved=$ra_salary - $ra_deduction;
        }
       
        return response()->json([
            'net_salary'=>round($ra_salary,2),
            'gross_salary'=>round($ra_recieved,2) ,
            'total_salary'=>round($ra_zomatos_no_of_hours+$ra_zomatos_no_of_trips,2),
            'total_deduction'=>round($ra_payable,2),
            'total_bonus'=>round($ra_cr,2),
        ]);
    }
    public function new_salary_added(Request $request){
        $rider_id=$request->rider_id;
        $rider=Rider::find($rider_id); 
        $salary=$rider->Rider_salary()->create([
            'rider_id'=>$request->get('rider_id') ,
            'month'=> Carbon::parse($request->get('month'))->format('Y-m-d'),
            'total_salary'=> $request->get('net_salary'),
            'gross_salary'=> $request->get('gross_salary'),
            'recieved_salary'=> $request->get('recieved_salary'),
            'remaining_salary'=> $request->get('remaining_salary'),
            'payment_status'=>$request->get('payment_status'), 
            'paid_by'=>Auth::user()->id,
            'status'=> $request->get('status')=='on'?1:0,
        ]);


        


        // $RA=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        // ->whereMonth("month", Carbon::parse($request->get('month'))->format('m'))
        // ->get();

        // $RA_cr=\App\Model\Accounts\Rider_Account::
        // where("rider_id",$rider_id)
        // ->whereMonth("month", Carbon::parse($request->get('month'))->format('m'))
        // ->where(function($q) {
        //     $q->where('type', "cr_payable")
        //       ->orWhere('type', 'cr');
        // })
        // ->get();
        // $ra__debits=[];
        // $ca__debits=[];
        // foreach ($RA_cr as $check_rider_cr_payable) {
        //     $check_rider_dr_payable=Arr::first($RA, function ($item_zi, $key) use ($check_rider_cr_payable) {
        //         if($check_rider_cr_payable->type=="cr"){
        //             return $item_zi->type == "dr" && $item_zi->rider_id == $check_rider_cr_payable->rider_id &&  $item_zi->month == $check_rider_cr_payable->month && $item_zi->amount == $check_rider_cr_payable->amount;
        //         }
        //         elseif ($check_rider_cr_payable->type=="cr_payable") {
        //             return $item_zi->type == "dr_payable" && $item_zi->rider_id == $check_rider_cr_payable->rider_id &&  $item_zi->month == $check_rider_cr_payable->month && $item_zi->amount == $check_rider_cr_payable->amount;
        //         }
                
        //     });
        //     if (!isset($check_rider_dr_payable)) {
        //         // not found, add one
        //         if($check_rider_cr_payable->type=="cr"){
        //             $obj=[];
        //             $obj['salary_id'] =$check_rider_cr_payable->salary_id;
        //             $obj['income_zomato_id'] =$check_rider_cr_payable->income_zomato_id;
        //             $obj['advance_return_id'] =$check_rider_cr_payable->advance_return_id;
        //             $obj['id_charge_id'] =$check_rider_cr_payable->id_charge_id;
        //             $obj['wps_id'] =$check_rider_cr_payable->wps_id;
        //             $obj['fuel_expense_id'] =$check_rider_cr_payable->fuel_expense_id;
        //             $obj['maintenance_id'] =$check_rider_cr_payable->maintenance_id;
        //             $obj['edirham_id'] =$check_rider_cr_payable->edirham_id;
        //             $obj['company_expense_id'] =$check_rider_cr_payable->company_expense_id;
        //             $obj['salik_id'] =$check_rider_cr_payable->salik_id;
        //             $obj['sim_transaction_id'] =$check_rider_cr_payable->sim_transaction_id;
        //             $obj['type']='dr';
        //             $obj['rider_id']=$rider_id;
        //             $obj['month'] = $check_rider_cr_payable->month;
        //             $obj['source']=$check_rider_cr_payable->source; 
        //             $obj['amount']=$check_rider_cr_payable->amount;
        //             array_push($ra__debits, $obj);

        //             // $obj['type']='dr';
        //             // array_push($ca__debits, $obj);
        //         }
        //         elseif ($check_rider_cr_payable->type=="cr_payable") {
        //             $obj=[];
        //             $obj['salary_id'] =$check_rider_cr_payable->salary_id;
        //             $obj['income_zomato_id'] =$check_rider_cr_payable->income_zomato_id;
        //             $obj['advance_return_id'] =$check_rider_cr_payable->advance_return_id;
        //             $obj['id_charge_id'] =$check_rider_cr_payable->id_charge_id;
        //             $obj['wps_id'] =$check_rider_cr_payable->wps_id;
        //             $obj['fuel_expense_id'] =$check_rider_cr_payable->fuel_expense_id;
        //             $obj['maintenance_id'] =$check_rider_cr_payable->maintenance_id;
        //             $obj['edirham_id'] =$check_rider_cr_payable->edirham_id;
        //             $obj['company_expense_id'] =$check_rider_cr_payable->company_expense_id;
        //             $obj['salik_id'] =$check_rider_cr_payable->salik_id;
        //             $obj['sim_transaction_id'] =$check_rider_cr_payable->sim_transaction_id;
        //             $obj['type']='dr_payable';
        //             $obj['rider_id']=$rider_id;
        //             $obj['month'] = $check_rider_cr_payable->month;
        //             $obj['source']=$check_rider_cr_payable->source; 
        //             $obj['amount']=$check_rider_cr_payable->amount;
        //             array_push($ra__debits, $obj);

        //             $obj['type']='cr';
        //             array_push($ca__debits, $obj);
        //         }
                
        //     }
        // }
        // DB::table('rider__accounts')->insert($ra__debits); //r4
        // DB::table('company__accounts')->insert($ca__debits); //r4

        $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
            'salary_id'=>$salary->id
        ]);
        $ca->salary_id =$salary->id;
        $ca->type='dr';
        $ca->rider_id=$rider_id;
        $ca->month = Carbon::parse($request->get('month'))->format('Y-m-d');
        $ca->source="salary";
        $ca->payment_status="pending";
        $ca->amount=$request->total_salary;
        $ca->save();

        $ca = \App\Model\Accounts\Rider_Account::firstOrCreate([
            'salary_id'=>$salary->id
        ]);
        $ca->salary_id =$salary->id;
        $ca->type='cr';
        $ca->rider_id=$rider_id;
        $ca->month = Carbon::parse($request->get('month'))->format('Y-m-d');
        $ca->source="salary"; 
        $ca->payment_status="pending";
        $ca->amount=$request->total_salary;
        $ca->save();
    
        // $remaining_salary=$request->remaining_salary;
        // if ($remaining_salary>0) { // rider will give to company
        //     $ca = new \App\Model\Accounts\Company_Account;
        //     $ca->salary_id =$salary->id;
        //     $ca->type='dr';
        //     $ca->rider_id=$rider_id;
        //     $ca->month = Carbon::parse($request->get('month'))->addMonth()->format('Y-m-d');
        //     $ca->source="Remaining Salary From Previous Month"; 
        //     $ca->amount=abs($remaining_salary);
        //     $ca->save();
    
        //     $ra = new \App\Model\Accounts\Rider_Account;
        //     $ra->salary_id =$salary->id;
        //     $ra->type='cr_payable';
        //     $ra->rider_id=$rider_id;
        //     $ra->month = Carbon::parse($request->get('month'))->addMonth()->format('Y-m-d');
        //     $ra->source="Remaining Salary From Previous Month"; 
        //     $ra->amount=abs($remaining_salary);
        //     $ra->save();
        // }
        // else if ($remaining_salary<0) {  // company will give to rider
        //     $ca = new \App\Model\Accounts\Company_Account;
        //     $ca->salary_id =$salary->id;
        //     $ca->type='dr';
        //     $ca->rider_id=$rider_id;
        //     $ca->month = Carbon::parse($request->get('month'))->addMonth()->format('Y-m-d');
        //     $ca->source="Remaining Salary From Previous Month"; 
        //     $ca->amount=abs($remaining_salary);
        //     $ca->save();
    
        //     $ra = new \App\Model\Accounts\Rider_Account;
        //     $ra->salary_id =$salary->id;
        //     $ra->type='cr';
        //     $ra->rider_id=$rider_id;
        //     $ra->month = Carbon::parse($request->get('month'))->addMonth()->format('Y-m-d');
        //     $ra->source="Remaining Salary From Previous Month"; 
        //     $ra->amount=abs($remaining_salary);
        //     $ra->save();
        // }
        
        // foreach ($RA as $check_rider_cr_payable) {
        //     $check_rider_dr_payable=\App\Model\Accounts\Rider_Account::where("source",$check_rider_cr_payable->source)
        //     ->where("type","dr_payable")
        //    ->where("rider_id",$check_rider_cr_payable->rider_id)
        //    ->where("month",$check_rider_cr_payable->month)
        //    ->get()
        //    ->first();

        // if (!isset($check_rider_dr_payable)) {
        //     $ra = new \App\Model\Accounts\Rider_Account;
        //     $ra->salary_id =$check_rider_cr_payable->salary_id;
        //     $ra->income_zomato_id =$check_rider_cr_payable->income_zomato_id;
        //     $ra->advance_return_id =$check_rider_cr_payable->advance_return_id;
        //     $ra->id_charge_id =$check_rider_cr_payable->id_charge_id;
        //     $ra->wps_id =$check_rider_cr_payable->wps_id;
        //     $ra->fuel_expense_id =$check_rider_cr_payable->fuel_expense_id;
        //     $ra->maintenance_id =$check_rider_cr_payable->maintenance_id;
        //     $ra->edirham_id =$check_rider_cr_payable->edirham_id;
        //     $ra->company_expense_id =$check_rider_cr_payable->company_expense_id;
        //     $ra->salik_id =$check_rider_cr_payable->salik_id;
        //     $ra->sim_transaction_id =$check_rider_cr_payable->sim_transaction_id;
        //     $ra->type='dr_payable';
        //     $ra->rider_id=$rider_id;
        //     $ra->month = $check_rider_cr_payable->month;
        //     $ra->source=$check_rider_cr_payable->source; 
        //     $ra->amount=$check_rider_cr_payable->amount;
        //     $ra->save();

        //     $ca = new \App\Model\Accounts\Company_Account;
        //     $ca->salary_id =$check_rider_cr_payable->salary_id;
        //     $ca->income_zomato_id =$check_rider_cr_payable->income_zomato_id;
        //     $ca->advance_return_id =$check_rider_cr_payable->advance_return_id;
        //     $ca->id_charge_id =$check_rider_cr_payable->id_charge_id;
        //     $ca->wps_id =$check_rider_cr_payable->wps_id;
        //     $ca->fuel_expense_id =$check_rider_cr_payable->fuel_expense_id;
        //     $ca->maintenance_id =$check_rider_cr_payable->maintenance_id;
        //     $ca->edirham_id =$check_rider_cr_payable->edirham_id;
        //     $ca->company_expense_id =$check_rider_cr_payable->company_expense_id;
        //     $ca->salik_id =$check_rider_cr_payable->salik_id;
        //     $ca->sim_transaction_id =$check_rider_cr_payable->sim_transaction_id;
        //     $ca->type='cr';
        //     $ca->rider_id=$rider_id;
        //     $ca->month = $check_rider_cr_payable->month;
        //     $ca->source=$check_rider_cr_payable->source; 
        //     $ca->amount=$check_rider_cr_payable->amount;
        //     $ca->save();
        // } 
       
    // }
        return redirect(route('account.developer_salary'));
    }

    // end add new salary
    
    // salary by month
    
    public function salary_by_month_create(){
        return view('accounts.salary_by_month');
    }
    public function DeleteMonth(Request $request){
        $month_id_array =$request->month_id;
        $month = Rider_salary::find($month_id_array);
        $month->active_status='D';
        $month->update();
        return response()->json([
            'status' => true
        ]);
    }
    public function updateStatusmonth(Request $request)
    {   $month_id_array =$request->month_id;
        $month = Rider_salary::find($month_id_array);
        if($month->status == 1)
        {
            $month->status = 0;
        }
        else
        {
            $month->status = 1;
        }
        
        $month->update();
        return response()->json([
            'status' => true
        ]);
    }
    public function month_edit(Request $request,$month_id){
        $month_id_array =$request->month_id;
        $month = Rider_salary::find($month_id_array);
        return view('accounts.month_edit',compact('month'));
    }
    public function month_update(Request $request,Rider_salary $month,$id){

        $this->validate($request, [
          'salary' => 'required | string | max:255',
          'paid_by' => 'required | string |max:255',
      ]);
      $month_id_array =$request->month_id;
      $month = Rider_salary::find($month_id_array);
      $month->salary = $request->salary;
      $month->paid_by = $request->paid_by;
      
      
      
      if($month->status)
          $month->status = 1;
      else
          $month->status = 0;
     
      $month->update();
     
      return redirect(route('account.month_salary'))->with('message', 'Record Updated Successfully.');
  
        }
    // end salary by month
    
    // salary by developer
    public function salary_by_developer_create(){
        $riders=Rider::where("active_status","A")->get();
        return view('accounts.salary_by_developer' ,compact('riders'));
    }

    public function DeleteDeveloper(Request $request){
        $developer_id_array =$request->developer_id;
        $developer = Rider_salary::find($developer_id_array);
        $developer->active_status='D';
        $developer->update();
        return response()->json([
            'status' => true
        ]);
    }
    public function updateStatusdeveloper(Request $request)
    {   $developer_id_array =$request->developer_id;
        $developer = Rider_salary::find($developer_id_array);
        if($developer->status == 1)
        {
            $developer->status = 0;
        }
        else
        {
            $developer->status = 1;
        }
        
        $developer->update();
        return response()->json([
            'status' => true
        ]);
    }
    public function developer_edit(Request $request,$developer_id){
        $developer_id_array =$request->developer_id;
        $developer = Rider_salary::find($developer_id_array);
        return view('accounts.developer_edit',compact('developer'));
    }
    public function developer_update(Request $request,Rider_salary $developer,$id){

        $this->validate($request, [
          'salary' => 'required | string | max:255',
          'paid_by' => 'required | string |max:255',
      ]);
      $developer_id_array =$request->developer_id;
      $developer = Rider_salary::find($developer_id_array);
      $developer->salary = $request->salary;
      $developer->paid_by = $request->paid_by;
      
      
      
      if($developer->status)
          $developer->status = 1;
      else
          $developer->status = 0;
     
      $developer->update();
     
      return redirect(route('account.developer_salary'))->with('message', 'Record Updated Successfully.');
  
// end salary by developer
}
//   Fuel Expense
public function fuel_expense_create(){
    $bikes=bike::where('active_status', 'A')->get();
    return view('admin.accounts.Fuel_Expense.FE_add',compact('bikes'));
}
public function fuel_expense_insert(Request $r){
    $bike_id=bike::find($r->bike_id);
    $fuel_expense=new Fuel_Expense();
    $fuel_expense->amount=$r->amount;
    $fuel_expense->type=$r->type;
    $fuel_expense->month=Carbon::parse($r->get('month'))->format('Y-m-d');
    $fuel_expense->bike_id=$bike_id->id;
    if($r->status)
            $fuel_expense->status = 1;
        else
            $fuel_expense->status = 0;
    $fuel_expense->save();
    $assign_bike=\App\Assign_bike::where('bike_id', $fuel_expense->bike_id)->where('status','active')->get()->first();
    $rider_id = null;
    if($assign_bike){
        $rider_id=Rider::find($assign_bike->rider_id)->id;
    }
if ($fuel_expense->type=="vip_tag") {
    
    $ca = new \App\Model\Accounts\Company_Account;
    $ca->type='dr';
    $ca->amount=$r->amount;
    $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
    $ca->rider_id = $rider_id;
    $ca->source='fuel_expense';
    $ca->fuel_expense_id=$fuel_expense->id;
    $ca->save();
}
elseif($fuel_expense->type=="cash"){
    $ca = new \App\Model\Accounts\Company_Account;
    $ca->type='dr';
    $ca->amount=$r->amount;
    $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
    $ca->rider_id = $rider_id;
    $ca->source='fuel_expense';
    $ca->fuel_expense_id=$fuel_expense->id;
    $ca->save();

    $ra = new \App\Model\Accounts\Rider_Account;
    $ra->type='cr_payable';
    $ra->amount=$r->amount;
    $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
    $ra->rider_id = $rider_id;
    $ra->source='fuel_expense';
    $ra->fuel_expense_id=$fuel_expense->id;
    $ra->save();
}
    return redirect(route('admin.fuel_expense_view'));
}
public function fuel_expense_view()
{ 
    return view('admin.accounts.Fuel_Expense.FE_view');
}
public function delete_fuel_expense($id)
{ 
    $delete_expense=Fuel_Expense::find($id);
    $delete_expense->status=0;
    $delete_expense->active_status="D";
    $delete_expense->update();

    $ra=\App\Model\Accounts\Rider_Account::where("fuel_expense_id",$delete_expense->id)->get();
    foreach ($ra as $item) {
        $item->active_status="D";
        $item->update();
    }
    
    $ca=\App\Model\Accounts\Company_Account::where("fuel_expense_id",$delete_expense->id)->get();
    foreach ($ca as $elem) {
        $elem->active_status="D"; 
        $elem->update();
    }
    

}
public function update_fuel_expense($id)
{
    $update_expense=Fuel_Expense::find($id);
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
public function edit_fuel_expense($id){
    $expense=Fuel_Expense::find($id);
    $bikes=bike::all();
    return view('admin.accounts.Fuel_Expense.FE_edit',compact('expense','bikes'));
}
public function update_edit_fuel_expense(Request $r,$id){
    $bike_id=bike::find($r->bike_id);
    $fuel_expense=Fuel_Expense::find($id);
    $fuel_expense->amount=$r->amount;
    $fuel_expense->month=Carbon::parse($r->get('month'))->format('Y-m-d');
    $fuel_expense->type=$r->type;
    $fuel_expense->bike_id=$bike_id->id;
    if($r->status)
            $fuel_expense->status = 1;
        else
            $fuel_expense->status = 0;
    $fuel_expense->update();

    $assign_bike=\App\Assign_bike::where('bike_id', $fuel_expense->bike_id)->where('status','active')->get()->first();
    $rider_id = null;
    if($assign_bike){
        $rider_id=Rider::find($assign_bike->rider_id)->id;
    }
if ($fuel_expense->type=="vip_tag") {
    
    $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
        'fuel_expense_id'=>$fuel_expense->id
    ]);
    $ca->type='dr';
    $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
    $ca->rider_id = $rider_id;
    $ca->source="fuel_expense"; 
    $ca->amount=$r->amount;
    $ca->save();

    
    $ra=\App\Model\Accounts\Rider_Account::where("fuel_expense_id",$fuel_expense->id)->get()->first();
    if (isset($ra)) {
        $ra->delete();
    }

}
elseif($fuel_expense->type=="cash"){
    $ca =\App\Model\Accounts\Company_Account::updateOrCreate([
    'fuel_expense_id'=>$fuel_expense->id,
    ]);
    $ca->type='dr';
    $ca->rider_id = $rider_id;
    $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
    $ca->source="fuel_expense"; 
    $ca->amount=$r->amount;
    $ca->save();
    

    $ra =\App\Model\Accounts\Rider_Account::updateOrCreate([
    'fuel_expense_id'=>$fuel_expense->id,
    ]);
    $ra->fuel_expense_id=$fuel_expense->id;
    $ra->type='cr_payable';
    $ra->rider_id = $rider_id;
    $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
    $ra->source="fuel_expense"; 
    $ra->amount=$r->amount;
    $ra->save();
    
}
    return redirect(route('admin.fuel_expense_view'));
}


// end Fuel Expense


//income zomato
public function income_zomato_index(){
    return view('admin.accounts.Income.zomato');
}
public function income_zomato_import(Request $r){
    $data = $r->data;
    $zomato_obj=[];
    $ca_objects=[];
    $ca_objects_updates=[];
    $ra_objects=[];
    $ra_objects_updates=[];
    $zi = Income_zomato::all(); // r1
    $client_riders = Client_Rider::all();
    $update_data = [];
    $i=0;
    $unique_id=uniqid().'-'.time();
    foreach ($data as $item) {
        $i++;
        $zi_found = Arr::first($zi, function ($item_zi, $key) use ($item) {
            return $item_zi->feid == $item['feid'] && $item_zi->date == Carbon::createFromFormat('d/m/Y',$item['onboarding_date'])->format('Y-m-d');
        });
        $rider_found = Arr::first($client_riders, function ($client_rider, $key) use ($item) {
            return $client_rider->client_rider_id == $item['feid'];
        });
        $rider_id = null;
        if(isset($rider_found)){
            $rider_id = $rider_found->rider_id;
        }
        $p_id=uniqid().time().rand();
        if(!isset($zi_found)){
            $obj = [];
            $obj['p_id']=$p_id;
            $obj['import_id']=$unique_id;
            $obj['rider_id']=$rider_id;
            $obj['feid']=isset($item['feid'])?$item['feid']:null;
            $obj['log_in_hours_payable']=isset($item['log_in_hours_payable'])?$item['log_in_hours_payable']:null;
            $obj['trips_payable']=isset($item['trips_payable'])?$item['trips_payable']:null;
            $obj['total_to_be_paid_out']=isset($item['total_to_be_paid_out'])?$item['total_to_be_paid_out']:null;
            $obj['amount_for_login_hours']=isset($item['amount_for_login_hours'])?$item['amount_for_login_hours']:null;
            $obj['amount_to_be_paid_against_orders_completed']=isset($item['amount_to_be_paid_against_orders_completed'])?$item['amount_to_be_paid_against_orders_completed']:null; 
            $obj['ncw_incentives']=isset($item['ncw_incentives'])?$item['ncw_incentives']:null;
            $obj['tips_payouts']=isset($item['tips_payouts'])?$item['tips_payouts']:null;
            $obj['denials_penalty']=isset($item['denials_penalty'])?$item['denials_penalty']:null;
            $obj['dc_deductions']=isset($item['dc_deductions'])?$item['dc_deductions']:null;
            $obj['mcdonalds_deductions']=isset($item['mcdonalds_deductions'])?$item['mcdonalds_deductions']:null;
            
            $obj['date']=isset($item['onboarding_date'])?Carbon::createFromFormat('d/m/Y',$item['onboarding_date'])->format('Y-m-d'):null;
            $obj['created_at']=Carbon::now();
            $obj['updated_at']=Carbon::now();
            array_push($zomato_obj, $obj);

            $ca_amt1 = ($obj['amount_for_login_hours']+$obj['amount_to_be_paid_against_orders_completed']+$obj['ncw_incentives']+$obj['tips_payouts'])
            - ($obj['dc_deductions'] + $obj['mcdonalds_deductions'] + $obj['denials_penalty']);
            $ca_obj = [];
            $ca_obj['income_zomato_id']=$p_id;
            $ca_obj['source']='Zomato Payout';
            $ca_obj['rider_id']=$rider_id;
            $ca_obj['amount']=$ca_amt1;
            $ca_obj['month']=$obj['date'];
            $ca_obj['type']='cr';
            $ca_obj['created_at']=Carbon::now();
            $ca_obj['updated_at']=Carbon::now();
            array_push($ca_objects, $ca_obj);

            //bonus
            $ca_amt2 = $obj['ncw_incentives'];
            if($ca_amt2 > 0){
                $ca_obj = [];
                $ca_obj['income_zomato_id']=$p_id;
                $ca_obj['source']='NCW Incentives';
                $ca_obj['rider_id']=$rider_id;
                $ca_obj['amount']=$ca_amt2;
                $ca_obj['month']=$obj['date'];
                $ca_obj['type']='dr';
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects, $ca_obj);
            }

            $ca_amt2 = $obj['tips_payouts'];
            if($ca_amt2 > 0){
                $ca_obj = [];
                $ca_obj['income_zomato_id']=$p_id;
                $ca_obj['source']='Tips Payouts';
                $ca_obj['rider_id']=$rider_id;
                $ca_obj['amount']=$ca_amt2;
                $ca_obj['month']=$obj['date'];
                $ca_obj['type']='dr';
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects, $ca_obj);
            }

            
            $ra_amt2 = $obj['tips_payouts'];
            if($ra_amt2 > 0){
                $ra_obj = [];
                $ra_obj['income_zomato_id']=$p_id;
                $ra_obj['source']='Tips Payouts';
                $ra_obj['rider_id']=$rider_id;
                $ra_obj['amount']=$ra_amt2;
                $ra_obj['month']=$obj['date'];
                $ra_obj['type']='cr';
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects, $ra_obj);
            }

            $ra_amt2 = $obj['ncw_incentives'];
                if($ra_amt2 > 0){
                $ra_obj = [];
                $ra_obj['income_zomato_id']=$p_id;
                $ra_obj['source']='NCW Incentives';
                $ra_obj['rider_id']=$rider_id;
                $ra_obj['amount']=$ra_amt2;
                $ra_obj['month']=$obj['date'];
                $ra_obj['type']='cr';
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects, $ra_obj);
            }

            //deductions

            //ra
            $ra_amt1 = $obj['mcdonalds_deductions'];
            if($ra_amt1 > 0){
                $ra_obj = [];
                $ra_obj['income_zomato_id']=$p_id;
                $ra_obj['source']='Mcdonalds Deductions'; 
                $ra_obj['rider_id']=$rider_id;
                $ra_obj['amount']=$ra_amt1;
                $ra_obj['month']=$obj['date'];
                $ra_obj['type']='cr_payable';
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects, $ra_obj);
            }

            $ra_amt1 = $obj['dc_deductions'];
            if($ra_amt1 > 0){
                $ra_obj = [];
                $ra_obj['income_zomato_id']=$p_id;
                $ra_obj['source']='DC Deductions'; 
                $ra_obj['rider_id']=$rider_id;
                $ra_obj['amount']=$ra_amt1;
                $ra_obj['month']=$obj['date'];
                $ra_obj['type']='cr_payable';
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects, $ra_obj);
            }

            $ra_amt1 = $obj['denials_penalty'];
            if($ra_amt1 > 0){
                $ra_obj = [];
                $ra_obj['income_zomato_id']=$p_id;
                $ra_obj['source']='Denials Penalty'; 
                $ra_obj['rider_id']=$rider_id;
                $ra_obj['amount']=$ra_amt1;
                $ra_obj['month']=$obj['date'];
                $ra_obj['type']='cr_payable';
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects, $ra_obj);
            }
            

            //ca
            $ca_amt1 = $obj['mcdonalds_deductions'];
            if($ca_amt1 > 0){
                $ca_obj = [];
                $ca_obj['income_zomato_id']=$p_id;
                $ca_obj['source']='Mcdonalds Deductions'; 
                $ca_obj['rider_id']=$rider_id;
                $ca_obj['amount']=$ca_amt1;
                $ca_obj['month']=$obj['date'];
                $ca_obj['type']='dr_receivable';
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects, $ca_obj);
            }

            $ca_amt1 = $obj['dc_deductions'];
            if($ca_amt1 > 0){
                $ca_obj = [];
                $ca_obj['income_zomato_id']=$p_id;
                $ca_obj['source']='DC Deductions'; 
                $ca_obj['rider_id']=$rider_id;
                $ca_obj['amount']=$ca_amt1;
                $ca_obj['month']=$obj['date'];
                $ca_obj['type']='dr_receivable';
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects, $ca_obj);
            }

            $ca_amt1 = $obj['denials_penalty'];
            if($ca_amt1 > 0){
                $ca_obj = [];
                $ca_obj['income_zomato_id']=$p_id;
                $ca_obj['source']='Denials Penalty'; 
                $ca_obj['rider_id']=$rider_id;
                $ca_obj['amount']=$ca_amt1;
                $ca_obj['month']=$obj['date'];
                $ca_obj['type']='dr_receivable';
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects, $ca_obj);
            }
        }
        else{
            $objUpdate = [];
            $objUpdate['id']=$zi_found->id;
            $objUpdate['p_id']=$zi_found->p_id;
            $objUpdate['import_id']=$unique_id;
            $objUpdate['rider_id']=$rider_id;
            $objUpdate['feid']=isset($item['feid'])?$item['feid']:null;
            $objUpdate['log_in_hours_payable']=isset($item['log_in_hours_payable'])?$item['log_in_hours_payable']:null;
            $objUpdate['trips_payable']=isset($item['trips_payable'])?$item['trips_payable']:null;
            $objUpdate['total_to_be_paid_out']=isset($item['total_to_be_paid_out'])?$item['total_to_be_paid_out']:null;
            $objUpdate['amount_for_login_hours']=isset($item['amount_for_login_hours'])?$item['amount_for_login_hours']:null;
            $objUpdate['amount_to_be_paid_against_orders_completed']=isset($item['amount_to_be_paid_against_orders_completed'])?$item['amount_to_be_paid_against_orders_completed']:null; 
            $objUpdate['ncw_incentives']=isset($item['ncw_incentives'])?$item['ncw_incentives']:null;
            $objUpdate['tips_payouts']=isset($item['tips_payouts'])?$item['tips_payouts']:null;
            $objUpdate['denials_penalty']=isset($item['denials_penalty'])?$item['denials_penalty']:null;
            $objUpdate['dc_deductions']=isset($item['dc_deductions'])?$item['dc_deductions']:null;
            $objUpdate['mcdonalds_deductions']=isset($item['mcdonalds_deductions'])?$item['mcdonalds_deductions']:null;
            $objUpdate['date']=isset($item['onboarding_date'])?Carbon::createFromFormat('d/m/Y',$item['onboarding_date'])->format('Y-m-d'):null;
            $objUpdate['created_at']=Carbon::now();
            $objUpdate['updated_at']=Carbon::now();
            array_push($update_data, $objUpdate);

            $ca_amt1 = ($objUpdate['amount_for_login_hours']+$objUpdate['amount_to_be_paid_against_orders_completed']+$objUpdate['ncw_incentives']+$objUpdate['tips_payouts'])
            - ($objUpdate['dc_deductions'] + $objUpdate['mcdonalds_deductions'] + $objUpdate['denials_penalty']);
            $ca_obj = [];
            $ca_obj['income_zomato_id']=$objUpdate['p_id'];
            $ca_obj['source']='income_zomato@payout';
            $ca_obj['rider_id']=$rider_id;
            $ca_obj['month']=$objUpdate['date']; 
            $ca_obj['amount']=$ca_amt1;
            $ca_obj['type']='cr';
            $ca_obj['updated_at']=Carbon::now();
            array_push($ca_objects_updates, $ca_obj);



            //bonus
            $ca_amt2 = $objUpdate['ncw_incentives'];
            if($ca_amt2 > 0){
                $ca_obj = [];
                $p_id1=$objUpdate['p_id'];
                $ca_obj['income_zomato_id']=$p_id1;
                $ca_obj['source']='NCW Incentives';
                $ca_obj['rider_id']=$rider_id;
                $ca_obj['amount']=$ca_amt2;
                $ca_obj['month']=$objUpdate['date'];
                $ca_obj['type']='dr';
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects_updates, $ca_obj);
            }

            $ca_amt2 = $objUpdate['tips_payouts'];
            if($ca_amt2 > 0){
                $ca_obj = [];
                $p_id1=$objUpdate['p_id'];
                $ca_obj['income_zomato_id']=$p_id1;
                $ca_obj['source']='Tips Payouts';
                $ca_obj['rider_id']=$rider_id;
                $ca_obj['amount']=$ca_amt2;
                $ca_obj['month']=$objUpdate['date'];
                $ca_obj['type']='dr';
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects_updates, $ca_obj);
            }

            
            $ra_amt2 = $objUpdate['tips_payouts'];
            if($ra_amt2 > 0){
                $ra_obj = [];
                $p_id1=$objUpdate['p_id'];
                $ra_obj['income_zomato_id']=$p_id1;
                $ra_obj['source']='Tips Payouts';
                $ra_obj['rider_id']=$rider_id;
                $ra_obj['amount']=$ra_amt2;
                $ra_obj['month']=$objUpdate['date'];
                $ra_obj['type']='cr';
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects_updates, $ra_obj);
            }

            $ra_amt2 = $objUpdate['ncw_incentives'];
                if($ra_amt2 > 0){
                $ra_obj = [];
                $p_id1=$objUpdate['p_id'];
                $ra_obj['income_zomato_id']=$p_id1;
                $ra_obj['source']='NCW Incentives';
                $ra_obj['rider_id']=$rider_id;
                $ra_obj['amount']=$ra_amt2;
                $ra_obj['month']=$objUpdate['date'];
                $ra_obj['type']='cr';
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects_updates, $ra_obj);
            }

            //deductions

            //ra
            $ra_amt1 = $objUpdate['mcdonalds_deductions'];
            if($ra_amt1 > 0){
                $ra_obj = [];
                $p_id1=$objUpdate['p_id'];
                $ra_obj['income_zomato_id']=$p_id1;
                $ra_obj['source']='Mcdonalds Deductions'; 
                $ra_obj['rider_id']=$rider_id;
                $ra_obj['amount']=$ra_amt1;
                $ra_obj['month']=$objUpdate['date'];
                $ra_obj['type']='cr_payable';
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects_updates, $ra_obj);
            }

            $ra_amt1 = $objUpdate['dc_deductions'];
            if($ra_amt1 > 0){
                $ra_obj = [];
                $p_id1=$objUpdate['p_id'];
                $ra_obj['income_zomato_id']=$p_id1;
                $ra_obj['source']='DC Deductions'; 
                $ra_obj['rider_id']=$rider_id;
                $ra_obj['amount']=$ra_amt1;
                $ra_obj['month']=$objUpdate['date'];
                $ra_obj['type']='cr_payable';
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects_updates, $ra_obj);
            }

            $ra_amt1 = $objUpdate['denials_penalty'];
            if($ra_amt1 > 0){
                $ra_obj = [];
                $ra_obj['income_zomato_id']=$objUpdate['p_id'];
                $ra_obj['source']='Denials Penalty'; 
                $ra_obj['rider_id']=$rider_id;
                $ra_obj['amount']=$ra_amt1;
                $ra_obj['month']=$objUpdate['date'];
                $ra_obj['type']='cr_payable';
                $ra_obj['created_at']=Carbon::now();
                $ra_obj['updated_at']=Carbon::now();
                array_push($ra_objects_updates, $ra_obj);
            }

            //ca
            $ca_amt1 = $objUpdate['mcdonalds_deductions'];
            if($ca_amt1 > 0){
                $ca_obj = [];
                $p_id1=$objUpdate['p_id'];
                $ca_obj['income_zomato_id']=$p_id1;
                $ca_obj['source']='Mcdonalds Deductions'; 
                $ca_obj['rider_id']=$rider_id;
                $ca_obj['amount']=$ca_amt1;
                $ca_obj['month']=$objUpdate['date'];
                $ca_obj['type']='dr_receivable';
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects_updates, $ca_obj);
            }

            $ca_amt1 = $objUpdate['dc_deductions'];
            if($ca_amt1 > 0){
                $ca_obj = [];
                $p_id1=$objUpdate['p_id'];
                $ca_obj['income_zomato_id']=$p_id1;
                $ca_obj['source']='DC Deductions'; 
                $ca_obj['rider_id']=$rider_id;
                $ca_obj['amount']=$ca_amt1;
                $ca_obj['month']=$objUpdate['date'];
                $ca_obj['type']='dr_receivable';
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects_updates, $ca_obj);
            }

            $ca_amt1 = $objUpdate['denials_penalty'];
            if($ca_amt1 > 0){
                $ca_obj = [];
                $ca_obj['income_zomato_id']=$objUpdate['p_id'];
                $ca_obj['source']='Denials Penalty'; 
                $ca_obj['rider_id']=$rider_id;
                $ca_obj['amount']=$ca_amt1;
                $ca_obj['month']=$objUpdate['date'];
                $ca_obj['type']='dr_receivable';
                $ca_obj['created_at']=Carbon::now();
                $ca_obj['updated_at']=Carbon::now();
                array_push($ca_objects_updates, $ca_obj);
            }

        }
    }
    Income_zomato::insert($zomato_obj); //r2
    $data=Batch::update(new Income_zomato, $update_data, 'id'); //r3  

    DB::table('company__accounts')->insert($ca_objects); //r4
    $data_ca=Batch::update(new \App\Model\Accounts\Company_Account, $ca_objects_updates, 'income_zomato_id'); //r5  

    DB::table('rider__accounts')->insert($ra_objects); //r4
    $data_ra=Batch::update(new \App\Model\Accounts\Rider_Account, $ra_objects_updates, 'income_zomato_id'); //r5  
    return response()->json([
        'data'=>$zomato_obj,
        'data_ca'=>$ca_objects,
        'data_ca_update'=>$ca_objects_updates,
        'data_ra'=>$ra_objects,
        'data_ra_update'=>$ra_objects_updates,
        'count'=>$i 
    ]);
}

//ends income zomato

// client_income
public function client_income_index(){
   $clients=Client::where("active_status","A")->get();
   $riders=Rider::where("active_status","A")->get();
    return view('accounts.Client_income.add_income',compact("clients", 'riders'));
}
public function client_income_getRiders($client_id){
    $clients=Client::find($client_id);
    $riders=$clients->riders;
     return response()->json([
         'riders' => $riders
     ]);
 }

public function client_income_view(){
    return view('accounts.Client_income.view_income');  
}
public function client_income_updatestatus(Request $request,$id){
    $update_client_income=Client_Income::find($id);
    if($update_client_income->status == 1)
    {
        $update_client_income->status = 0;
    }
    else
    {
        $update_client_income->status = 1;
    }
    $update_client_income->update();
    return response()->json([
        'status' => true
    ]);
}
public function client_income_delete(Request $request,$id){
    $delete_client_income=Client_Income::find($id);
    $delete_client_income->active_status="D";
    $delete_client_income->save();
    return response()->json([
        'status' => true
    ]);
}
public function client_income_edit($id){
    $clients=Client::where("active_status","A")->get();
    $edit_client_income=Client_Income::find($id);
    return view('accounts.Client_income.edit_income',compact('edit_client_income','clients'));
}
public function client_income_store(Request $request){
    $client = Client::find($request->client_id);
    $client_income=new Client_Income();
    $client_income->amount=$request->amount;
    $client_income->month=Carbon::parse($request->get('month'))->format('Y-m-d');
    $client_income->client_id=$request->client_id;
    $client_income->rider_id=$request->rider_id;
    if($request->status)
        $client_income->status = 1;
    else
        $client_income->status = 0;
    $client_income->save();

    $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
        'client_income_id'=>$client_income->id
    ]);
    $ca->client_income_id =$client_income->id;
    $ca->type='cr';
    $ca->rider_id=$client_income->rider_id;
    $ca->month = Carbon::parse($request->get('month'))->format('Y-m-d');
    $ca->source=$client->name." Income"; 
    $ca->amount=$client_income->amount;
    $ca->save();

    return redirect(route('admin.client_income_view'));
}
public function client_income_update(Request $request,$id){
    $client = Client::find($request->client_id);
    $update_income=Client_Income::find($id);
    $update_income->amount=$request->amount;
    $update_income->month=Carbon::parse($request->get('month'))->format('Y-m-d');
    $update_income->client_id=$request->client_id;
    $update_income->rider_id=$request->rider_id;
    if($request->status)
        $update_income->status = 1;
    else
        $update_income->status = 0;
    $update_income->save();
    $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
        'client_income_id'=>$update_income->id
    ]);
    $ca->client_income_id =$update_income->id;
    $ca->type='cr';
    $ca->rider_id=$update_income->rider_id;
    $ca->month = Carbon::parse($request->get('month'))->format('Y-m-d');
    $ca->source=$client->name." Income"; 
    $ca->amount=$update_income->amount;
    $ca->save();
   
        return redirect(route('admin.client_income_view'));
}
// end Client_income

    public function rider_expense_get()
    {
        $riders=Rider::where("active_status","A")->get();
        return view('admin.accounts.Rider_Debit.rider_expense',compact('riders'));
    }
    public function rider_expense_post(Request $r)
    {
        $d_type = $r->d_type;
        $ca = new \App\Model\Accounts\Company_Account;
        $ca->type='dr';
        $ca->amount=$r->amount;
        $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->rider_id = $r->rider_id;
        $ca->source=$r->desc;
        $ca->save();
        $ra = new \App\Model\Accounts\Rider_Account;
        if($d_type=='payable'){
            // cr_payable
            $ra->type='cr_payable';
        }
        else{
            //cr
            $ra->type='cr';
        }
        $ra->amount=$r->amount;
        $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ra->rider_id = $r->rider_id;
        $ra->source=$r->desc;
        $ra->save();
        
        return redirect(route('admin.accounts.rider_account'));
    }
    public function updatePaymentStatus($id){
        $ra=Rider_Account::find($id);
        $salary = Rider_salary::find($ra->salary_id);
        $new_ra=new Rider_Account;
        $new_ra->type="dr";
        $new_ra->amount=$salary->recieved_salary;
        $new_ra->source="salary_paid";
        $new_ra->payment_status="paid";
        $new_ra->rider_id=$ra->rider_id;
        $new_ra->salary_id=$ra->salary_id;
        $new_ra->month=$ra->month;
        $new_ra->save();
        return response()->json([
            'data'=>'true',
        ]);

    }

    public function add_company_profit(Request $r){
        $ra = new \App\Model\Accounts\Company_Account;
        $ra->type='pl';
        $ra->amount=$r->profit;
        $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ra->rider_id = $r->rider_id;
        $ra->source=$r->profit>0?"Profit":"Loss";
        $ra->payment_status="paid";
        $ra->save();
        return response()->json([
            'data'=>$r->all(),
        ]);

    }

    public function rider_cash_add(Request $r){
        $d_type = $r->d_type;
        if($d_type=='cash_paid'){ // add cash to rider account
            $ra = new \App\Model\Accounts\Rider_Account;
            $ra->type='dr';
            $ra->amount=$r->amount;
            $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
            $ra->rider_id = $r->cash_rider_id;
            $ra->source=$r->desc;
            $ra->payment_status="paid";
            $ra->save();
        }
        else if($d_type=='dr'){ // add cash to company account from rider account
            $ca = new \App\Model\Accounts\Company_Account;
            $ca->type='cr';
            $ca->amount=$r->amount;
            $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
            $ca->rider_id = $r->cash_rider_id;
            $ca->source=$r->desc;
            $ca->save();
            
            $ra = new \App\Model\Accounts\Rider_Account;
            $ra->type='dr';
            $ra->amount=$r->amount;
            $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
            $ra->rider_id = $r->cash_rider_id;
            $ra->source=$r->desc;
            $ra->save();
        }

        return response()->json([
            'data'=>$r->all(),
        ]);

    } 

  public function company_overall_report(){
      return view('admin.accounts.Company_Debit.company_overall_report');
  }
}