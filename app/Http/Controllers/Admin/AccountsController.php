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
    public function get_rider_account($rider_id){
        $rider = Rider::find($rider_id);
        $opening_balance = $rider->Rider_detail->salary; 
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
        $ra=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$month)
        ->where("type","cr_payable")
        ->get();  
        $rider_detail=Rider_detail::where("active_status","A")
        ->where("rider_id",$rider_id)
        ->get()
        ->first();
        $ra_sum=$ra->sum("amount");
        $ra_recieved=$rider_detail->salary - $ra_sum;

       
        return response()->json([
            'gross_salary'=>$ra_recieved ,
            'recieved_salary'=>$ra_recieved,
            'total_salary'=>$rider_detail->salary,
        ]);
    }
    public function new_salary_added(Request $request){
        $rider_id=$request->rider_id;
        $rider=Rider::find($rider_id); 
        $salary=$rider->Rider_salary()->create([
            'rider_id'=>$request->get('rider_id') ,
            'month'=> Carbon::parse($request->get('month'))->format('Y-m-d'),
            'total_salary'=> $request->get('total_salary'),
            'gross_salary'=> $request->get('gross_salary'),
            'recieved_salary'=> $request->get('recieved_salary'),
            'remaining_salary'=> $request->get('remaining_salary'),
            'paid_by'=> $request->get('paid_by'),
            'status'=> $request->get('status')=='on'?1:0,
        ]);
        $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
            'salary_id'=>$salary->id
        ]);
        $ca->salary_id =$salary->id;
        $ca->type='dr';
        $ca->rider_id=$rider_id;
        $ca->month = Carbon::parse($request->get('month'))->format('Y-m-d');
        $ca->source="salary"; 
        $ca->amount=$request->recieved_salary;
        $ca->save();

        $ca = \App\Model\Accounts\Rider_Account::firstOrCreate([
            'salary_id'=>$salary->id
        ]);
        $ca->salary_id =$salary->id;
        $ca->type='cr';
        $ca->rider_id=$rider_id;
        $ca->month = Carbon::parse($request->get('month'))->format('Y-m-d');
        $ca->source="salary"; 
        $ca->amount=$request->recieved_salary;
        $ca->save();

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


}