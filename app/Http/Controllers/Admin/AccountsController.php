<?php

namespace App\Http\Controllers\Admin; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Bikes\bike;
use App\Model\Bikes\bike_detail;
use App\Model\Client\Client;
use App\Model\Client\Client_History;
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
use App\Model\Accounts\Company_Expense;
use App\Assign_bike;
use App\Company_investment;
use App\Company_Tax;
use App\Model\Zomato\Riders_Payouts_By_Days;
use App\Export_data;
use App\Model\Accounts\Bill_change;
use App\Model\Sim\Sim_History;
use App\Deleted_data;
use App\Notification;


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
        $ca->type='dr_receivable';
        $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->rider_id=$r->rider_id;
        $ca->amount=$r->amount;
        $ca->source=str_replace('_',' ',ucfirst($r->type))." Charges";
        $ca->id_charge_id=$id_charge->id;
        $ca->save();

        $ra = new \App\Model\Accounts\Rider_Account;
        $ra->type='cr_payable';
        $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ra->rider_id=$r->rider_id;
        $ra->amount=$r->amount;
        $ra->source=str_replace('_',' ',ucfirst($r->type))." Charges";
        $ra->id_charge_id=$id_charge->id;
        $ra->save();

        return redirect(route('admin.accounts.id_charges_view'));
    }
    public function id_charges_view()
    { 
        return view('admin.accounts.id_charges_view');
    }
    public function id_charges_edit($id){
        $readonly=false;
        $riders=Rider::where('active_status', 'A')->get();
        $id_charges=Id_charge::find($id);
        return view('admin.accounts.id_charges_edit',compact('readonly','id_charges','riders'));
    }
    public function id_charges_edit_view($id){
        $readonly=true;
        $riders=Rider::where('active_status', 'A')->get();
        $id_charges=Id_charge::find($id);
        return view('admin.accounts.id_charges_edit',compact('readonly','id_charges','riders'));
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
        if(isset($ca)){
        $ca->type='dr_receivable';
        $ca->rider_id=$r->rider_id;
        $ca->amount=$r->amount;
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->source=str_replace('_',' ',ucfirst($r->type))." Charges";
        $ca->id_charge_id=$id_charge->id;
        $ca->update();
    }
        $ra =\App\Model\Accounts\Rider_Account::where("id_charge_id",$id_charge->id)->get()->first();
        $ra->type='cr_payable';
        $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ra->rider_id=$r->rider_id;
        $ra->amount=$r->amount;
        $ra->source=str_replace('_',' ',ucfirst($r->type))." Charges";
        $ra->id_charge_id=$id_charge->id;
        $ra->save();
        return redirect(route('admin.id_charges_edit_view',$id_charge->id));
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
        $readonly=false;
        $workshop=Workshop::find($id);
        return view('admin.accounts.workshop_edit',compact('readonly','workshop'));
    }
    public function workshop_edit_view($id){
        $readonly=true;
        $workshop=Workshop::find($id);
        return view('admin.accounts.workshop_edit',compact('readonly','workshop'));
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

        
        return redirect(route('admin.workshop_edit_view',$workshop->id));
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
        $readonly=false;
        $edirham=Edirham::find($id);
        return view('admin.accounts.Edirham.edirham_edit',compact('readonly','edirham'));
    }
    public function edirham_edit_view($id){
        $readonly=true;
        $edirham=Edirham::find($id);
        return view('admin.accounts.Edirham.edirham_edit',compact('readonly','edirham'));
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

        return redirect(route('admin.edirham_edit_view',$edirham->id));
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
            'paid_by_rider'=>$r->paid_by_rider,
            'paid_by_company'=>$r->paid_by_company,
            'status'=>$r->status=='on'?1:0,
        ]);
        if($r->hasFile('invoice_image'))
            {
                // return 'yes';
                $filename = $r->invoice_image->getClientOriginalName();
                $filesize = $r->invoice_image->getClientSize();
                // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
                $filepath = Storage::putfile('public/uploads/riders/invoice_image', $r->file('invoice_image'));
                $maintenance->invoice_image = $filepath;
            }
            $maintenance->save();
        $assign_bike=Assign_bike::where('bike_id', $maintenance->bike_id)
        ->whereDate('created_at','<=',Carbon::parse($r->month)->format('Y-m-d'))
        ->get()
        ->last();
        $rider_id = null;
        if($assign_bike){
            $rider_id=Rider::find($assign_bike->rider_id)->id;
        }
        if($maintenance->accident_payment_status == 'pending'){
            
            $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id
            ]);
            $ca->maintenance_id =$maintenance->id;
            $ca->type='dr_receivable';
            $ca->rider_id=$rider_id;
            $ca->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
            $ca->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ca->source="Bike Maintenance"; 
            $ca->amount=$r->amount;
            $ca->save();

            $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id
            ]);
            $ra->maintenance_id =$maintenance->id;
            $ra->type='cr_payable';
            $ra->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
            $ra->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ra->rider_id=$rider_id;
            $ra->source="Bike Maintenance"; 
            $ra->amount=$r->amount;
            $ra->save();
        }
        else if($maintenance->accident_payment_status == 'paid'){
            $ca_check = \App\Model\Accounts\Company_Account::where(['maintenance_id' => $maintenance->id, 'type'=>'dr_receivable'])->get()->first();
            if(!isset($ca_check)){
                $ca_dr = new \App\Model\Accounts\Company_Account;
                $ca_dr->maintenance_id =$maintenance->id;
                $ca_dr->type='dr_receivable';
                $ca_dr->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
                $ca_dr->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
                $ca_dr->rider_id=$rider_id;
                $ca_dr->source="Bike Maintenance"; 
                $ca_dr->amount=$r->amount;
                $ca_dr->save();
            }
            $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
                'maintenance_id'=>$maintenance->id,
                'type'=>'cr'
            ]);
            $ca->maintenance_id =$maintenance->id;
            $ca->type='cr';
            $ca->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
            $ca->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ca->rider_id=$rider_id;
            $ca->source="Bike Maintenance"; 
            $ca->amount=$r->amount;
            $ca->save();

            $ra_check = \App\Model\Accounts\Rider_Account::where(['maintenance_id' => $maintenance->id, 'type'=>'cr_payable'])->get()->first();
            if(!isset($ra_check)){
                $ra_dr = new \App\Model\Accounts\Rider_Account;
                $ra_dr->maintenance_id =$maintenance->id;
                $ra_dr->type='cr_payable';
                $ra_dr->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
                $ra_dr->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
                $ra_dr->rider_id=$rider_id;
                $ra_dr->source="Bike Maintenance"; 
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
            $ra->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
            $ra->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ra->source="Bike Maintenance";
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
            $ca->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
            $ca->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ca->source="Bike Maintenance"; 
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
        $readonly=false;
        $maintenance=Maintenance::find($id);
        $workshops=Workshop::where('active_status', 'A')->get();
        $bikes=bike::where("active_status","A")->get();
        return view('admin.accounts.maintenance_edit',compact('readonly','maintenance','workshops','bikes'));
    }
    public function maintenance_edit_view($id){
        $readonly=true;
        $maintenance=Maintenance::find($id);
        $workshops=Workshop::where('active_status', 'A')->get();
        $bikes=bike::where("active_status","A")->get();
        return view('admin.accounts.maintenance_edit',compact('readonly','maintenance','workshops','bikes'));
    }
    public function maintenance_update(Request $r,$id){
        $maintenance =Maintenance::find($id);
        $maintenance->maintenance_type=$r->maintenance_type;
        $maintenance->workshop_id=$r->workshop_id;
        $maintenance->bike_id=$r->bike_id;
        $maintenance->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $maintenance->amount=$r->amount;
        $maintenance->paid_by_company=$r->paid_by_company;
        $maintenance->paid_by_rider=$r->paid_by_rider;
        if($r->hasFile('invoice_image'))
        {
            // return 'yes';
            if($maintenance->invoice_image)
            {
                Storage::delete($maintenance->invoice_image);
            }
            $filename = $r->invoice_image->getClientOriginalName();
            $filesize = $r->invoice_image->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/invoice_image', $r->file('invoice_image'));
            $maintenance->invoice_image = $filepath;
        }

        // $id_charge->=$r->;
        if($r->status)
            $maintenance->status = 1;
        else
            $maintenance->status = 0;
        $maintenance->update();
        
        $assign_bike=Assign_bike::where('bike_id', $maintenance->bike_id)
        ->whereDate('created_at','<=',Carbon::parse($r->month)->format('Y-m-d'))
        ->get()
        ->last();
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
            $ca->type='dr_receivable';
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
            $ca_check = \App\Model\Accounts\Company_Account::where(['maintenance_id' => $maintenance->id, 'type'=>'dr_receivable'])->get()->first();
            if(!isset($ca_check)){
                $ca_dr = new \App\Model\Accounts\Company_Account;
                $ca_dr->maintenance_id =$maintenance->id;
                $ca_dr->type='dr_receivable';
                $ca_dr->rider_id=$rider_id;
                $ca_dr->month = Carbon::parse($r->get('month'))->format('Y-m-d');
                $ca_dr->source="maintenance"; 
                $ca_dr->amount=$r->amount;
                $ca_dr->save();
            }
            else {
                $ca_check->maintenance_id =$maintenance->id;
                $ca_check->type='dr_receivable';
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
        
        
        return redirect(route('admin.maintenance_edit_view',$maintenance->id));
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

    // investment
    public function kr_investment_index()
    {
        return view('admin.accounts.kr_investment_add');
    }
    public function kr_investment_post(Request $r)
    {
        $kr_investment = Company_investment::create([
            'investor_id'=>Auth::user()->id,
            'amount'=>$r->amount,
            'notes'=>$r->notes,
            'month' => Carbon::parse($r->get('month'))->format('Y-m-d'),
            'status'=>$r->status=='on'?1:0,
        ]);
        $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
            'investment_id'=>$kr_investment->id
        ]);
        $ca->investment_id =$kr_investment->id;
        $ca->type='cr';
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->source="Investment"; 
        $ca->amount=$r->amount;
        $ca->payment_status='paid';
        $ca->save();
        
        return redirect(route('admin.accounts.kr_investment_view'));
    } 
    public function kr_investment_view()
    { 
        return view('admin.accounts.kr_investment_view');
    }
    public function kr_investment_edit($id){
        $readonly=false;
        $kr_investment=Company_investment::find($id);
        return view('admin.accounts.kr_investment_edit',compact('readonly','kr_investment'));
    }
    public function kr_investment_edit_view($id){
        $readonly=true;
        $kr_investment=Company_investment::find($id);
        return view('admin.accounts.kr_investment_edit',compact('readonly','kr_investment'));
    }
    public function kr_investment_update(Request $r,$id){
        $kr_investment =Company_investment::find($id);
        $kr_investment->investor_id=$r->investor_id;
        $kr_investment->amount=$r->amount;
        $kr_investment->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        
        // $id_charge->=$r->;
        if($r->status)
            $kr_investment->status = 1;
        else
            $kr_investment->status = 0;
        $kr_investment->update();
        
        $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
            'investment_id'=>$kr_investment->id
        ]);
        $ca->investment_id =$kr_investment->id;
        $ca->type='cr';
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->source="Investment"; 
        $ca->amount=$r->amount;
        $ca->payment_status='paid';
        $ca->save();
        
        
        return redirect(route('admin.accounts.kr_investment_view'));
    }
    public function delete_kr_investment($id)
    {
        $kr_investment=Company_investment::find($id);
        $kr_investment->active_status="D";
        $kr_investment->status=0;
        $kr_investment->update();
    }
    public function updateStatusKr_investment($kr_investment_id)
    {
        $kr_investment=Company_investment::find($kr_investment_id);
        if($kr_investment->status == 1)
        {
            $kr_investment->status = 0;
        }
        else
        {
            $kr_investment->status = 1;
        }
        $kr_investment->update();
        return response()->json([
            'status' => true
        ]);
    }
    //ends investment 

    // add new salary
    public function add_new_salary_create(){
        $riders=Rider::where("active_status","A")->get();
        return view('accounts.add_new_salary',compact('riders'));
    }
    public function rider_account(){ 
        $riders = Rider::where('active_status', 'A')
        ->where(function($q) {
            $q->where('rider_type', "Rider")
              ->orWhereNull('rider_type'); 
        })->get();
        return view('admin.accounts.Rider_Debit.view_account',compact('riders')); 
    }
    public function company_account(){ 
        $riders = Rider::where(['active_status' => 'A'])
        ->where(function($q) {
            $q->where('rider_type', "Rider")
              ->orWhereNull('rider_type'); 
        })
        ->get(); 
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
    public static function get_salary_deduction(Request $r,$month, $rider_id){ 
        // before tthat check if rider is zomato's
        $startMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
        $month = Carbon::parse($month)->format('Y-m-d');
        $onlyMonth = Carbon::parse($month)->format('m');
        $onlyYear = Carbon::parse($month)->format('Y');

        $is_update = $r->get('update');
        if($is_update==true || $is_update=='true'){
            $is_update=true;
        }
        else {
            $is_update=false;
        }
        // return response()->json([
        //     'a'=>$is_update
        // ]);
        
        $rider = Rider::find($rider_id);

        //prev payables
        $rider_debits_cr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->where(function($q) {
            $q->where('type', "cr");
        })
        ->whereDate('month', '<',$startMonth)
        ->sum('amount');
        
        $rider_debits_dr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->where(function($q) {
            $q->where('type', "cr_payable")
              ->orWhere('type', 'dr');
        })
        ->whereDate('month', '<',$startMonth)
        ->sum('amount');
        $closing_balance_prev = round($rider_debits_cr_prev_payable - $rider_debits_dr_prev_payable,2);
        //ends prev payables

        $ra_payable=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->where(function($q) {
            $q->where('type', "cr_payable")
            ->orWhere('type', 'dr');
        })
        ->sum('amount'); 
        if($is_update)
        {
            $ra_payable=Rider_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$onlyMonth)
            ->where(function($q) {
                $q->where('type', "cr_payable")
                ->orWhere('type', 'dr');
            })
            ->where("source",'!=',"salary_paid")
            ->sum('amount');
        }
        
        $salary_paid=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->where(function($q) {
            $q->where('type', "cr_payable")
            ->orWhere('type', 'dr');
        })
        ->where(function($w) {
            $w->where('source', "salary_paid")
            ->orWhere('source', 'remaining_salary');
        })
        ->sum('amount');

        $ra_cr=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->where("payment_status","pending")
        ->where("type","cr")
        ->where("source",'!=',"salary")
        ->sum('amount');  
        if($closing_balance_prev < 0){ //deduct
            $ra_payable += abs($closing_balance_prev);
        }
        else {
            // add
            $ra_cr += abs($closing_balance_prev);
        }
        $total_salary_amt = 0;

        $client_history = Client_History::all(); 
        $history_found = Arr::first($client_history, function ($item, $key) use ($rider_id, $startMonth) {
            $start_created_at =Carbon::parse($item->assign_date)->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);

            $start_updated_at =Carbon::parse($item->deassign_date)->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($startMonth);

            if($item->status=='active'){    
                return $item->rider_id==$rider_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
            }

            return $item->rider_id==$rider_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });

        $feid=null;
        $client=null;
        $client_setting=[];
        $pm='';
        if (isset($history_found)) {
            $feid=$history_found->client_rider_id;
            $client=Client::find($history_found->client_id);
            if($client->salary_methods==null){
                # no salary method was found against this client
                return response()->json([
                    'status'=>0,
                    'msg'=>'No salary method is found under client '.$client->name
                ]);
            }
            $client_setting = json_decode($client->salary_methods, true);
            $pm = $client_setting['salary_method'];
        }

        $zomato_hours =0;
        $zomato_trips = 0;

        $calculated_hours =0;
        $calculated_trips = 0;
        $absent_count=0;
        $working_count=0;
        $absent_hours=0;
        $working_hours=0;
        $less_time=0;
        $bonus=0;
        $absent_app=0;
        $positions=0;
        $commission=0;
        $commission_value='0%';


        //some static_data
        $_s_maxTrips=400;
        $_s_tripsFormula=2;
        $_s_maxTripsFormula=4;
        $_s_monthlyHours=286;
        $_s_hoursFormula=7.87;
        $_s_maxHours=11;
        if(isset($feid) && $feid!=null){ // rider belongs to zomato or careem

            if($pm=='commission_based'){
                # rider is from commission based
                $basic_salary = Rider_salary::where('rider_id', $rider_id)
                ->whereMonth("month",$onlyMonth)
                ->sum('total_salary');  
                if($basic_salary>0){
                    $fixed_salary = $basic_salary;
                    $ra_salary= $ra_cr;
                    $ra_recieved=$ra_salary - $ra_payable;
                    $total_salary_amt = $fixed_salary;
                }
                else {
                    ## no salary generated 
                    return response()->json([
                        'status'=>0,
                        'msg'=>'Cannot generate salary. Rider is commission based'
                    ]);
                }
            }
            else {
                # rider is from zomato or jeebly - trip based
                $ra_zomatos=Income_zomato::where("rider_id",$rider_id)
                ->whereMonth("date",$onlyMonth)
                ->get()->first();  

                if ($pm=='trip_based') {
                    
                    $_s_maxTrips=$client_setting['tb_sm__bonus_trips'];
                    $_s_tripsFormula=$client_setting['tb_sm__trip_amount'];
                    $_s_maxTripsFormula=$client_setting['tb_sm__trips_bonus_amount'];
                    $_s_monthlyHours=286;
                    $_s_hoursFormula=$client_setting['tb_sm__hour_amount'];
                    $_s_maxHours=11; 
                }
            
                
                if(isset($ra_zomatos)){

                    if($ra_zomatos->error!=null){
                        # means there was an error, maybe weekly day cannot be finded
                        $error = json_decode($ra_zomatos->error,true);
                        return response()->json([
                            'status'=>0,
                            'msg'=>$error['error_message'].' Against this rider.'
                        ]);
                    }
                    
                    $absent_app=$ra_zomatos->approve_absents;
                    $absent_approve_hours=$absent_app*$_s_maxHours;
                    $calculated_absent_hours=$absent_approve_hours*$_s_hoursFormula;
                    
                    $absent_count=$ra_zomatos->absents_count;
                    $working_count=$ra_zomatos->working_days;

                    $weekly_off=$ra_zomatos->weekly_off;
                    $extra_day=$ra_zomatos->extra_day;
                    
                    $absent_hours=$absent_count*$_s_maxHours;
                    $working_hours=$working_count*$_s_maxHours;

                    $calculated_hours =$ra_zomatos->calculated_hours;
                    $calculated_trips = $ra_zomatos->calculated_trips;

                    $less_time=$working_hours-$calculated_hours;

                    $zomato_hours =$ra_zomatos->log_in_hours_payable;
                    $zomato_trips = $ra_zomatos->trips_payable;

                    $hours=$calculated_hours;
                    // $hours_payable=$hours*$_s_hoursFormula;

                    $trips = $calculated_trips > $_s_maxTrips?$_s_maxTrips:$calculated_trips;

                    ##  temp_coding
                    // if($trips <350) $_s_tripsFormula=2;
                    // else if($trips >=350 && $trips <=399) $_s_tripsFormula=2.5;
                    // else if($trips >=400) $_s_tripsFormula=3;

                    // if($trips <250) $_s_hoursFormula=6;
                    ## end temp coding

                    $trips_payable = $trips * $_s_tripsFormula;

                    $trips_EXTRA = $calculated_trips > $_s_maxTrips?$calculated_trips-$_s_maxTrips:0;
                    $trips_EXTRA_payable = $trips_EXTRA * $_s_maxTripsFormula;

                    // $trips_payable+=$trips_EXTRA_payable;

                    $payable_hours=round($_s_monthlyHours - $absent_hours - $less_time,2);

                    $hours_payable=$payable_hours*$_s_hoursFormula;
                    if($calculated_trips > $_s_maxTrips){
                        $bonus=50;
                        if ($ra_zomatos->setting!="") {
                            $position_setting = json_decode($ra_zomatos->setting, true);
                            $positions = $position_setting['top_position'];
                            if ($positions=="1") {
                                $bonus=150;
                            }
                            if ($positions=="2") {
                                $bonus=125;
                            }
                            if ($positions=="3") {
                                $bonus=100;
                            } 
                        }
                    }
                    $salary_hours=round($hours_payable,2);
                    $salary_trips=$trips_payable+$trips_EXTRA_payable;
                    $salary_credits=round($ra_cr,2);
                    $ra_salary=$salary_hours +$salary_trips  +$salary_credits ;
                    $ra_recieved=$ra_salary - $ra_payable;

                    $total_salary_amt = round($hours_payable+$trips_payable+$trips_EXTRA_payable,2);
                }
                else { 
                    # no record found in income zomato table --generate error 
                    return response()->json([
                        'status'=>0,
                        'msg'=>'No record found on Zomato Income against this rider.'
                    ]);
                }
            }

            
        }
        else { // other clients
            if(isset($rider->rider_type)&&$rider->rider_type=='Employee'){
                #### employee's salary
                $rd = $rider->Rider_detail;
                $basic_salary = 2000;
                if($rd->salary!=null){
                    $basic_salary=$rd->salary;  
                }
                $fixed_salary = $basic_salary;
                $ra_salary= $fixed_salary + $ra_cr;
                $ra_recieved=$ra_salary - $ra_payable;
                $total_salary_amt = $fixed_salary;
                $pm='employee';
            }
            else {
                #### rider's salary
                if($pm==''){
                    # no client was found undr this rider
                    return response()->json([
                        'status'=>0,
                        'msg'=>'No client assigned to this rider.'
                    ]);
                }
                if($pm=='fixed_based'){
                    $basic_salary=isset($client_setting['fb_sm__amount'])?$client_setting['fb_sm__amount']:0;
                    $client_income=Client_Income::where("rider_id",$rider_id)
                    ->whereMonth("month",$onlyMonth)
                    ->whereYear("month",$onlyYear)
                    ->get()->first(); 
                    if(isset($client_income)){
                        $basic_salary = isset($basic_salary)?$basic_salary:0;
                        $fb__working_hours = $client_income->total_hours;
                        $fb__extra_hours = $client_income->extra_hours;

                        // $fb__perHourSalary = $basic_salary/$fb__working_hours;
                        $fb__perHourSalary = isset($client_setting['fb_sm__exrta_hours'])?$client_setting['fb_sm__exrta_hours']:0;
                        $extra_salary = $fb__perHourSalary * $fb__extra_hours;

                        $fixed_salary = $basic_salary + $extra_salary;
                        $ra_salary= $fixed_salary + $ra_cr;
                        $ra_recieved=$ra_salary - $ra_payable;
                        $total_salary_amt = $fixed_salary;
                    }
                    else {
                        # no record found in income zomato table --generate error
                        return response()->json([
                            'status'=>0,
                            'msg'=>'No Payout found against this rider.'
                        ]);
                    }
                    
                }
                if($pm=='commission_based'){
                    return response()->json([
                        'status'=>0,
                        'msg'=>'No Captain ID found against this rider.'
                    ]);
                    $commission_val=isset($client_setting['cb_sm__amount'])?$client_setting['cb_sm__amount']:0;
                    $commission_type=isset($client_setting['cb_sm__type'])?$client_setting['cb_sm__type']:0;
                    $commission_value=$commission_val.($commission_type=='percentage'?'%':' AED');
                    $basic_salary=0;
                    $client_income=Client_Income::where("rider_id",$rider_id)
                    ->whereMonth("month",$onlyMonth)
                    ->whereYear("month",$onlyYear)
                    ->where('income_type', 'commission_based')
                    ->get()->sum('total_payout');
                    if(isset($client_income) && $client_income>0){
                        $basic_salary = $client_income;
                        
                        if($commission_type=='percentage'){
                            $commission=($basic_salary/100)*$commission_val;
                            $fixed_salary = $basic_salary - $commission;
                        }
                        else {
                            $commission=$commission_val;
                            $fixed_salary = $basic_salary - $commission;
                        }
                        $ra_salary= $fixed_salary + $ra_cr;
                        $ra_recieved=$ra_salary - $ra_payable;
                        $total_salary_amt = $fixed_salary;
                    }
                    else {
                        # no record found in income zomato table --generate error
                        return response()->json([
                            'status'=>0,
                            'msg'=>'No Payout found against this rider.'
                        ]);
                    }
                    
                }
                if($pm=='trip_based'){
                    # no FEID found and FEID is cumpulsory for trip based rider
                    return response()->json([
                        'status'=>0,
                        'msg'=>'No FEID found against this rider.'
                    ]);
                }
            }
        }

        $is_generated= Rider_salary::where('rider_id',$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->get()
        ->first();
       if (isset($is_generated)) {
            $is_generated_salary=true;
       }
       else{
            $is_generated_salary=false;
       }
        
       $is_paid= \App\Model\Accounts\Rider_Account::where("source","salary_paid")
       ->where("payment_status","paid")
       ->where("rider_id",$rider_id)
       ->whereMonth("month",$onlyMonth)
       ->get()
       ->first();
       if (isset($is_paid)) {
            $is_paid_salary=true;
       }else{
            $is_paid_salary=false;
       }

       //fixed based client
       $basic_salary=isset($basic_salary)?$basic_salary:0;
       $fb__working_hours=isset($fb__working_hours)?$fb__working_hours:0;
       $fb__extra_hours=isset($fb__extra_hours)?$fb__extra_hours:0;
       $fb__perHourSalary=isset($fb__perHourSalary)?$fb__perHourSalary:0;
       //end fixed based client

       $salary_hours=isset($salary_hours)?$salary_hours:0;
       $absent_approve_hours=isset($absent_approve_hours)?$absent_approve_hours:0;
       $salary_trips=isset($salary_trips)?$salary_trips:0;
       $salary_credits=isset($salary_credits)?$salary_credits:0;
       $weekly_off=isset($weekly_off)?$weekly_off:0;
       $extra_day=isset($extra_day)?$extra_day:0;
       $trips=isset($trips)?$trips:0;
       $hours=isset($hours)?$hours:0;
       $hours_payable=isset($hours_payable)?$hours_payable:0;
       $trips_payable=isset($trips_payable)?$trips_payable:0;
       $calculated_absent_hours=isset($calculated_absent_hours)?$calculated_absent_hours:0;
       $trips_EXTRA=isset($trips_EXTRA)?$trips_EXTRA:0;
       $trips_EXTRA_payable=isset($trips_EXTRA_payable)?$trips_EXTRA_payable:0;
       $less_time=isset($less_time)?$less_time:0;
       $payable_hours=isset($payable_hours)?$payable_hours:0;
        return response()->json([
            'status'=>1,
            'salary_method'=>$pm,
            'ra_salary'=>$ra_salary,
            'client'=>$client,
            
            'salary_hours'=>$salary_hours,
            'salary_trips'=>$salary_trips,
            'salary_credits'=>$salary_credits,

            'commission'=>$commission,
            'commission_value'=>$commission_value,

            'net_salary'=>round($ra_salary,2),
            'gross_salary'=>round($ra_recieved,2),
            'zomato_hours'=>round($zomato_hours,2),
            'zomato_trips'=>round($zomato_trips,2),
            'total_deduction'=>round($ra_payable,2),
            'total_salary'=>round($total_salary_amt,2),
            'salary_paid'=>round($salary_paid,2),
            'closing_balance_prev'=>$closing_balance_prev,
            'is_paid'=>$is_paid_salary,
            'is_generated'=>$is_generated_salary,
            'absent_count'=>$absent_count,
            'weekly_off'=>$weekly_off,
            'extra_day'=>$extra_day,
            'working_days'=>$working_count,
            'absent_hours'=>$absent_hours,
            'working_hours'=>$working_hours,
            'trips'=>round($trips,2),
            'hours'=>round($hours,2),
            'bonus'=>$bonus,
            'absent_approve_hours'=>$absent_approve_hours,
            'calculated_absent_hours'=>round($calculated_absent_hours,2),
            
            'hours_payable'=>round($hours_payable,2),
            'trips_payable'=>round($trips_payable,2),
            'trips_EXTRA'=>round($trips_EXTRA,2),
            'trips_EXTRA_payable'=>round($trips_EXTRA_payable,2),
            'less_time'=>round($less_time,2),
            'payable_hours'=>round($payable_hours,2),

            'basic_salary'=>round($basic_salary,2),
            'fb__working_hours'=>round($fb__working_hours,2),
            'fb__extra_hours'=>round($fb__extra_hours,2),
            'fb__perHourSalary'=>round($fb__perHourSalary,2),

            '_s_maxTrips'=>round($_s_maxTrips,2),
            '_s_tripsFormula'=>round($_s_tripsFormula,2),
            '_s_maxTripsFormula'=>round($_s_maxTripsFormula,2),
            '_s_monthlyHours'=>round($_s_monthlyHours,2),
            '_s_hoursFormula'=>round($_s_hoursFormula,2),
            '_s_maxHours'=>$_s_maxHours,

            'pm'=>$pm,
            'client_setting'=>$client_setting,
            'position'=>$positions,
        ]);
    }
    public function new_salary_added(Request $request){
        $rider_id=$request->rider_id;
        $rider=Rider::find($rider_id);
        $salary = new Rider_salary;
        $already_salary = Rider_salary::where(['rider_id'=>$rider_id, 'month'=>Carbon::parse($request->get('month'))->format('Y-m-d')])
        ->get()
        ->first();
        if(isset($already_salary)){
            //update salary
            $salary=$already_salary;
        }
        $salary->rider_id=$rider_id;
        $salary->month=Carbon::parse($request->get('month'))->format('Y-m-d');
        $salary->total_salary=$request->get('net_salary');
        $salary->gross_salary=$request->get('gross_salary');
        $salary->recieved_salary=$request->get('recieved_salary');
        $salary->remaining_salary=$request->get('remaining_salary');
        $salary->payment_status=$request->get('payment_status');
        $salary->paid_by=Auth::user()->id;
        $salary->status=$request->get('status')=='on'?1:0;
        $salary->save();

        $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
            'salary_id'=>$salary->id
        ]);
        $ca->salary_id =$salary->id;
        $ca->type='dr';
        $ca->rider_id=$rider_id;
        $ca->month = Carbon::parse($request->get('month'))->startOfMonth()->format('Y-m-d');
        $ca->given_date = Carbon::parse($request->get('given_date'))->format('Y-m-d');
        $ca->source="salary";
        $ca->payment_status="pending";
        $ca->amount=round($request->total_salary,2);
        $ca->save();

        $ra = \App\Model\Accounts\Rider_Account::firstOrCreate([
            'salary_id'=>$salary->id
        ]);
        $ra->salary_id =$salary->id;
        $ra->type='cr';
        $ra->rider_id=$rider_id;
        $ra->month = Carbon::parse($request->get('month'))->startOfMonth()->format('Y-m-d');
        $ra->given_date = Carbon::parse($request->get('given_date'))->format('Y-m-d');
        $ra->source="salary"; 
        $ra->payment_status="pending";
        $ra->amount=round($request->total_salary,2);
        $ra->save();

        $ed =Export_data::firstOrCreate([
            'source'=>"salary",
            'source_id'=>$salary->id,
        ]);
        $ed->type='dr';
        $ed->rider_id=$rider_id;
        $ed->amount=round($request->total_salary,2);
        $ed->month = Carbon::parse($request->get('month'))->startOfMonth()->format('Y-m-d');
        $ed->given_date = Carbon::parse($request->get('given_date'))->format('Y-m-d');
        $ed->source="salary"; 
        $ed->source_id=$salary->id;
        $ed->payment_status="pending";
        $ed->save();
        return redirect(route('admin.accounts.company_account'));
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
        $readonly=false;
        $month_id_array =$request->month_id;
        $month = Rider_salary::find($month_id_array);
        $riders=Rider::all();
        return view('accounts.month_edit',compact('readonly','month','riders'));
    }
    public function month_edit_view(Request $request,$month_id){
        $readonly=true;
        $month_id_array =$request->month_id;
        $month = Rider_salary::find($month_id_array);
        $riders=Rider::all();
        return view('accounts.month_edit',compact('readonly','month','riders'));
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
        $readonly=false;
        $developer_id_array =$request->developer_id;
        $developer = Rider_salary::find($developer_id_array);
        return view('accounts.developer_edit',compact('readonly','developer'));
    }
    public function developer_edit_view(Request $request,$developer_id){
        $readonly=true;
        $developer_id_array =$request->developer_id;
        $developer = Rider_salary::find($developer_id_array);
        return view('accounts.developer_edit',compact('readonly','developer'));
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
    $riders=Rider::where('active_status','A')->get();
    $bikes=bike::where('active_status', 'A')->get();
    return view('admin.accounts.Fuel_Expense.FE_add',compact('bikes','riders'));
}
public function fuel_rider_selector($rider_id,$bike_id){
    $assign_bike=Assign_bike::where('status','active')->where('rider_id',$rider_id)->get()->first();
    return response()->json([
    'assign_bike'=>$assign_bike,
    ]);
}
public function fuel_expense_insert(Request $r){
    $data=$r->data;
    $total_amount=$r->amount;
    if($total_amount=='') $total_amount=0;
    $pm='';
    
    if ($r->type=='cash') {
        $rider_id=$r->rider_id;
        $date_to_match=$r->get('month');
        $client_histories=Client_History::all();
        $history_found = Arr::first($client_histories, function ($iteration, $key) use ($rider_id, $date_to_match) {
            $start_created_at =Carbon::parse($iteration->assign_date)->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);

            $start_updated_at =Carbon::parse($iteration->deassign_date)->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($date_to_match);

            if($iteration->status=='active'){    
                return $iteration->rider_id==$rider_id && 
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
            }

            return $iteration->rider_id==$rider_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });
        if (isset($history_found)) {
            $client_id=$history_found->client_id;
            $client=Client::find($client_id);
            $c_setting=json_decode($client->setting,true);
            $pm=$c_setting['payout_method'];
        }
        $fuel_expense=new Fuel_Expense();
        $fuel_expense->amount=$total_amount;
        $fuel_expense->type=$r->type;
        $fuel_expense->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
        $fuel_expense->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
        $fuel_expense->bike_id=$r->bike_id;
        $fuel_expense->rider_id=$r->rider_id;
        
        if($r->status)
            $fuel_expense->status = 1;
        else
            $fuel_expense->status = 0;
        
        $fuel_expense->save();
        if ($pm=="commission_based") {
            $ra = new \App\Model\Accounts\Rider_Account;
            $ra->type='dr';
            $ra->amount=$total_amount;
            $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
            $ra->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ra->rider_id=$r->rider_id;
            $ra->source='fuel_expense_cash';
            $ra->fuel_expense_id=$fuel_expense->id;
            $ra->save();

            $ca = new \App\Model\Accounts\Company_Account;
            $ca->type='cr';
            $ca->amount=$total_amount;
            $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
            $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ca->rider_id=$r->rider_id;
            $ca->source='fuel_expense_cash';
            $ca->fuel_expense_id=$fuel_expense->id;
            $ca->save();

            $ca = new \App\Model\Accounts\Company_Account;
            $ca->type='dr';
            $ca->amount=$total_amount;
            $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
            $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ca->rider_id=$r->rider_id;
            $ca->source='fuel_expense_cash';
            $ca->fuel_expense_id=$fuel_expense->id;
            $ca->save();
        }
        else{
            $ca = new \App\Model\Accounts\Company_Account;
            $ca->type='dr';
            $ca->amount=$total_amount;
            $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
            $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
            $ca->rider_id=$r->rider_id;
            $ca->source='fuel_expense_cash';
            $ca->fuel_expense_id=$fuel_expense->id;
            $ca->save();
        }
        $ed = new Export_data;
        $ed->type='dr';
        $ed->rider_id=$r->rider_id;
        $ed->amount=$total_amount;
        $ed->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
        $ed->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
        $ed->source='fuel_expense_cash';
        $ed->source_id=$fuel_expense->id;
        $ed->bill_id=$r->bike_id;
        $ed->bill_acc="bike";
        $ed->save();

        #we need to send the notification if it is coming from daily ledger page
        if(isset($r->send_noti)){
            #send the noti
            $data=[
                "export_id"=>$ed->id,
                'status'=>'accept'
            ];
            $data_accept=[
                "type"=>"update",
                "data"=>$data,
                "url"=> 'admin/account/dailyledger/noticallback',
            ];
            $data=[
                "export_id"=>$ed->id,
                'status'=>'reject'
            ];
            $data_reject=[
                "type"=>"update",
                "data"=>$data,
                "url"=> 'admin/account/dailyledger/noticallback',
            ];
            $button_html_accepted="<i style='font-size:20px' class='flaticon2-correct text-success btn_label--hover' onclick='CallBackNotification(this,".json_encode($data_accept).")'></i>";
            $button_html_rejected="<i style='font-size:20px' class='flaticon-circle text-danger btn_label--hover' onclick='CallBackNotification(this,".json_encode($data_reject).")'></i>";
            $button=$button_html_accepted.$button_html_rejected;
            $current_url=url('account.daily_ledger');
            $action_data = [
                [
                    'type'=>"url",
                    'value'=>$current_url,    
                ] ,
                [
                    'type'=>"button",
                    'value'=>$button,    
                ] ,
            ];
            $user = Auth::user();
            if (isset($user->s_emp_id) && $user->s_emp_id!="") {
                $seniour_emp=$user->s_emp_id;
                $emp_name=$user->name;

                $given_date=Carbon::parse($ed->given_date)->format("M d, Y");

                $notification=new Notification;
                $notification->date_time=Carbon::now()->format("Y-m-d");
                $notification->employee_id=$seniour_emp;
                $notification->desc='skip_this_notification';
                $notification->action=json_encode($action_data);
                $notification->source_id=$ed->id;
                $notification->source_type='fuel_expense_cash';
                $notification->action_status='pending_new';
                $notification->feed=json_encode($ed);
                $notification->save();
                return response()->json([
                    'status'=>$notification
                ]);
            }
            // return response()->json([
            //     'status'=>1
            // ]);
        }
        
    }
    else {
        #storing any bill details so we can detect changes
        $splitted_amount=0;
        $bill_changes = new Bill_change;
        $bill_changes->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
        $bill_changes->type='fuel_vip';
        if ($r->type=='cash') {
            $bill_changes->type='fuel_cash';
        }
        $bill_changes->amount=$total_amount;
        $bill_changes->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
        $billchanges_feed=[];
        foreach ($data as $value) {
            $rider_id=$r->rider_id;
            if(isset($value['rider_id'])){
                $rider_id=$value['rider_id'];
            }
            $date_to_match=$r->get('month');
            $client_histories=Client_History::all();
            $history_found = Arr::first($client_histories, function ($iteration, $key) use ($rider_id, $date_to_match) {
                $start_created_at =Carbon::parse($iteration->assign_date)->startOfMonth()->format('Y-m-d');
                $created_at =Carbon::parse($start_created_at);
    
                $start_updated_at =Carbon::parse($iteration->deassign_date)->endOfMonth()->format('Y-m-d');
                $updated_at =Carbon::parse($start_updated_at);
                $req_date =Carbon::parse($date_to_match);
    
                if($iteration->status=='active'){    
                    return $iteration->rider_id==$rider_id && 
                    ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
                }
    
                return $iteration->rider_id==$rider_id &&
                    ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
            });
            if (isset($history_found)) {
                $client_id=$history_found->client_id;
                $client=Client::find($client_id);
                $c_setting=json_decode($client->setting,true);
                $pm=$c_setting['payout_method'];
            }
            if($value['amount_given_by_days']<=0 || $value['amount_given_by_days']=='') continue;
            $fuel_expense=new Fuel_Expense();
            $fuel_expense->amount=$value['amount_given_by_days'];
            $fuel_expense->type=$r->type;
            $fuel_expense->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
            $fuel_expense->bike_id=$r->bike_id;
            if(isset($value['bike_id'])){
                $fuel_expense->bike_id=$value['bike_id'];
                
            }
            $fuel_expense->rider_id=$r->rider_id;
            if(isset($value['rider_id'])){
                $fuel_expense->rider_id=$value['rider_id'];
                
            }
            
            if($r->status)
                $fuel_expense->status = 1;
            else
                $fuel_expense->status = 0;
            
            $fuel_expense->save();
            if ($fuel_expense->type=="vip_tag") {
                if ($pm=="commission_based") {
                    $ra = new \App\Model\Accounts\Rider_Account;
                    $ra->type='dr';
                    $ra->amount=$value['amount_given_by_days'];
                    $ra->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
                    $ra->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
                    $ra->rider_id=$r->rider_id;
                    if(isset($value['rider_id'])){
                        $ra->rider_id=$value['rider_id'];
                    }
                    $ra->source='fuel_expense_vip';
                    $ra->fuel_expense_id=$fuel_expense->id;
                    $ra->save();
    
                    $ca = new \App\Model\Accounts\Company_Account;
                    $ca->type='cr';
                    $ca->amount=$value['amount_given_by_days'];
                    $ca->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
                    $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
                    $ca->rider_id=$r->rider_id;
                    if(isset($value['rider_id'])){
                        $ca->rider_id=$value['rider_id'];
                    }
                    $ca->source='fuel_expense_vip';
                    $ca->fuel_expense_id=$fuel_expense->id;
                    $ca->save();
    
                    $ca = new \App\Model\Accounts\Company_Account;
                    $ca->type='dr';
                    $ca->amount=$value['amount_given_by_days'];
                    $ca->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
                    $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
                    $ca->rider_id=$r->rider_id;
                    if(isset($value['rider_id'])){
                        $ca->rider_id=$value['rider_id'];
                    }
                    $ca->source='fuel_expense_vip';
                    $ca->fuel_expense_id=$fuel_expense->id;
                    $ca->save();
                }
                else{
                    $ca = new \App\Model\Accounts\Company_Account;
                    $ca->type='dr';
                    $ca->amount=$value['amount_given_by_days'];
                    $ca->month=Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
                    $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
                    $ca->rider_id=$r->rider_id;
                    if(isset($value['rider_id'])){
                        $ca->rider_id=$value['rider_id'];
                    }
                    $ca->source='fuel_expense_vip';
                    $ca->fuel_expense_id=$fuel_expense->id;
                    $ca->save();
                }
                $ed =Export_data::firstOrCreate([
                    'source'=>"fuel_expense_vip",
                    'source_id'=>$fuel_expense->id,
                ]);
                $ed->type='dr';
                $ed->rider_id=$r->rider_id;
                if(isset($value['rider_id'])){
                    $ed->rider_id=$value['rider_id'];
                }
                $ed->amount=$value['amount_given_by_days'];
                $ed->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
                $ed->given_date = Carbon::parse($r->get('given_date'))->format('Y-m-d');
                $ed->source='fuel_expense_vip';
                $ed->source_id=$fuel_expense->id;
                $ed->bill_id=$r->bike_id;
                if(isset($value['bike_id'])){
                    $ed->bill_id=$value['bike_id'];     
                }
                $ed->bill_acc="bike";
                $ed->save();
    
                #we need to send the notification if it is coming from daily ledger page
                if(isset($r->send_noti)){
                    #send the noti
                    $data=[
                        "export_id"=>$ed->id,
                        'status'=>'accept'
                    ];
                    $data_accept=[
                        "type"=>"update",
                        "data"=>$data,
                        "url"=> 'admin/account/dailyledger/noticallback',
                    ];
                    $data=[
                        "export_id"=>$ed->id,
                        'status'=>'reject'
                    ];
                    $data_reject=[
                        "type"=>"update",
                        "data"=>$data,
                        "url"=> 'admin/account/dailyledger/noticallback',
                    ];
                    $button_html_accepted="<i style='font-size:20px' class='flaticon2-correct text-success btn_label--hover' onclick='CallBackNotification(this,".json_encode($data_accept).")'></i>";
                    $button_html_rejected="<i style='font-size:20px' class='flaticon-circle text-danger btn_label--hover' onclick='CallBackNotification(this,".json_encode($data_reject).")'></i>";
                    $button=$button_html_accepted.$button_html_rejected;
                    $current_url=url('account.daily_ledger');
                    $action_data = [
                        [
                            'type'=>"url",
                            'value'=>$current_url,    
                        ] ,
                        [
                            'type'=>"button",
                            'value'=>$button,    
                        ] ,
                    ];
                    $user = Auth::user();
                    if (isset($user->s_emp_id) && $user->s_emp_id!="") {
                        $seniour_emp=$user->s_emp_id;
                        $emp_name=$user->name;
    
                        $given_date=Carbon::parse($ed->given_date)->format("M d, Y");
    
                        $notification=new Notification;
                        $notification->date_time=Carbon::now()->format("Y-m-d");
                        $notification->employee_id=$seniour_emp;
                        $notification->desc='skip_this_notification';
                        $notification->action=json_encode($action_data);
                        $notification->source_id=$ed->id;
                        $notification->source_type='fuel_expense_cash';
                        $notification->action_status='pending_new';
                        $notification->feed=json_encode($ed);
                        $notification->save();
                        // return response()->json([
                        //     'status'=>$notification
                        // ]);
                    }
                    // return response()->json([
                    //     'status'=>1
                    // ]);
                }
            }
            $splitted_amount+=$value['amount_given_by_days'];
            #saving bill data to detect changes
            $obj_feed=[]; 
            $obj_feed['bike_id']=$r->bike_id;
            if(isset($value['bike_id'])){
                $obj_feed['bike_id']=$value['bike_id'];
            }
            $obj_feed['rider_id']=$r->rider_id;
            if(isset($value['rider_id'])){
                $obj_feed['rider_id']=$value['rider_id'];
            }
            $obj_feed['work_days']=$value['work_days_count'];
            $obj_feed['month_days']=$value['total_days'];
            $obj_feed['bill_amount']=$value['amount_given_by_days'];
            array_push($billchanges_feed,$obj_feed);
        }  
        if(count($billchanges_feed)>0){
            $bill_changes->feed=json_encode($billchanges_feed);
            $bill_changes->save();
        }
    
        $remaining_amount=$total_amount-$splitted_amount;
        if($remaining_amount>0 && $total_amount>0){
            //add this as company expense
            $bike = bike::find($r->bike_id);
            $ce=new Company_Expense();
            $ce->amount=$remaining_amount;
            // $ce->rider_id=$r->rider_id;
            $ce->month = Carbon::parse($r->get('month'))->format('Y-m-d');
            $ce->description="Bike rent remaining amount against ".$bike->bike_number;
            $ce->type="bike_rent";
            $ce->bill_id=$r->bike_id;
            $ce->bill_acc="bike";
            $ce->save();
        }
    }
    
    return response()->json([
        'status'=>1
        // 'ca'=>$ca,
        // 'amount'=>$splitted_amount,
        // 'total_amount'=>$r->amount,
        // 'remaining_amount'=>$remaining_amount,
        // 'ce'=>$ce,
    ]);
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
    $is_readonly=false;
    $expense=Fuel_Expense::find($id);
    $bikes=bike::all();
    $riders=Rider::all();
    return view('admin.accounts.Fuel_Expense.FE_edit',compact('is_readonly','expense','bikes','riders'));
}
public function edit_fuel_expense_view($id){
    $is_readonly=true;
    $expense=Fuel_Expense::find($id);
    $bikes=bike::all();
    $riders=Rider::all();
    return view('admin.accounts.Fuel_Expense.FE_edit',compact('is_readonly','expense','bikes','riders'));
}
public function update_edit_fuel_expense(Request $r,$id){
    $bike_id=bike::find($r->bike_id);
    $fuel_expense=Fuel_Expense::find($id);
    $fuel_expense->amount=$r->amount;
    $fuel_expense->month=Carbon::parse($r->get('month'))->format('Y-m-d');
    $fuel_expense->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
    $fuel_expense->type=$r->type;
    $fuel_expense->bike_id=$bike_id->id;
    $fuel_expense->rider_id=$r->rider_id;
    $fuel_expense->update();

    $rider_id=$r->rider_id;

    // $bike_histories = null;
    // $bike_history = Assign_bike::all()->toArray();
    // $date=Carbon::parse($r->get('month'))->format('Y-m-d');
    // $history_found = Arr::first($bike_history, function ($item, $key) use ($bike_id, $date) {
    //     $start_created_at =Carbon::parse($item['bike_assign_date'])->startOfMonth()->format('Y-m-d');
    //     $created_at =Carbon::parse($start_created_at);

    //     $start_updated_at =Carbon::parse($item['bike_unassign_date'])->endOfMonth()->format('Y-m-d');
    //     $updated_at =Carbon::parse($start_updated_at);
    //     $req_date =Carbon::parse($date);
        
    //     if($item['status']=='active'){
            
    //         return $item['bike_id']==$bike_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
    //     }
    //     return $item['bike_id']==$bike_id &&
    //         ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
    // });
    // if(isset($history_found)){
    //     $rider_id=$history_found->rider_id;
    // }
if ($fuel_expense->type=="vip_tag") {
    
    $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
        'fuel_expense_id'=>$fuel_expense->id
    ]);
    $ca->type='dr';
    $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
    $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
    $ca->rider_id = $rider_id;
    $ca->source="fuel_expense_vip"; 
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
    $ca->given_date=Carbon::parse($r->get('given_date'))->format('Y-m-d');
    $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
    $ca->source="fuel_expense_cash"; 
    $ca->amount=$r->amount;
    $ca->payment_status='paid';
    $ca->save();
    

    // $ra =\App\Model\Accounts\Rider_Account::updateOrCreate([
    // 'fuel_expense_id'=>$fuel_expense->id,
    // ]);
    // $ra->fuel_expense_id=$fuel_expense->id;
    // $ra->type='cr_payable';
    // $ra->rider_id = $rider_id;
    // $ra->month=Carbon::parse($r->get('month'))->format('Y-m-d');
    // $ra->source="fuel_expense"; 
    // $ra->amount=$r->amount;
    // $ra->save();
    
}
    return redirect(route('admin.edit_fuel_expense_view',$fuel_expense->id));
}


// end Fuel Expense


//income zomato
public function income_zomato_index(){
    return view('admin.accounts.Income.zomato');
}

public function income_zomato_import(Request $r){
    
    $data = $r->data;
    $tax_data=$r->tax_data;
    $zomato_obj=[];
    $ca_objects=[];

    $delete_data=[];
    $ca_delete_data=[];
    $ra_delete_data=[];

    $ca_objects_updates=[];
    $ra_objects=[];
    $ra_objects_updates=[];
    $zi = Income_zomato::all(); // r1
    $client_riders = Client_History::all();
    $update_data = [];
    $i=0;
    $unique_id=uniqid().'-'.time();
   
    // $a = Income_zomato::where('import_id', '5dd698a58ce69-1574344869')->get();
    // foreach ($a as $b) {
    //    $c = $b->p_id;
    //    Company_Account::where('income_zomato_id',$c)->delete();
    //    Rider_Account::where('income_zomato_id',$c)->delete();
    // }
    // return response()->json([
    //     'data'=>'ok'
    // ]);
    

    /*======================Finding top 3 riders===================*/
    $expected_month = isset($data[0]['onboarding_date'])?Carbon::createFromFormat('d/m/Y',$data[0]['onboarding_date'])->format('Y-m-y'):null;
    if($expected_month==null){
        //throw unexpencted error
        return response()->json([
            'status'=>0,
            'msg'=>'Invalid date. Check the sheet again.'
        ]);
    }
    $expected_month_monly = Carbon::parse($expected_month)->format('m');
    $expected_year_yonly = Carbon::parse($expected_month)->format('Y');
    $prev_zi = Income_zomato::whereMonth('date', $expected_month_monly)
    ->whereYear('date', $expected_year_yonly)
    ->get()
    ->toArray();

    $prev_ca = \App\Model\Accounts\Company_Account::whereMonth('month', $expected_month_monly)
    ->whereYear('month', $expected_year_yonly)
    ->get()
    ->toArray();

    $prev_ra = \App\Model\Accounts\Rider_Account::whereMonth('month', $expected_month_monly)
    ->whereYear('month', $expected_year_yonly)
    ->get()
    ->toArray();

    
    $merged_data=array_merge($data, $prev_zi);
    //filter data against same feid
    $filtered_arr = [];
    foreach ($merged_data as $top_rider) {
        $feid = $top_rider['feid'];
        $tempp = Arr::first($filtered_arr, function ($item, $key) use ($feid) {
            return $item['feid'] == $feid;
        });
        if(!isset($tempp)){
            array_push($filtered_arr,$top_rider);
        }
    }
    $top_riders = $filtered_arr;
    // $top_riders = $data;

    usort($top_riders, function($a, $b){
        $tripsA = isset($a['trips_payable'])?$a['trips_payable']:0;
        $hoursA = isset($a['log_in_hours_payable'])?$a['log_in_hours_payable']:0;
    
        $tripsB = isset($b['trips_payable'])?$b['trips_payable']:0;
        $hoursB = isset($b['log_in_hours_payable'])?$b['log_in_hours_payable']:0;
    
    
        if($tripsA < $tripsB){
            return 1;
        }else if($tripsA == $tripsB){
            if($hoursA > $hoursB){
                return 1;
            }
            return 0;
        }
        return -1;
    }); 
    //top 3 riders FEIDs isset($item['feid'])?$item['feid']:null;
    $top_rider_1_FEID = $top_riders[0]['feid'];
    $top_rider_2_FEID = isset($top_riders[1]['feid'])?$top_riders[1]['feid']:null;
    $top_rider_3_FEID = isset($top_riders[2]['feid'])?$top_riders[2]['feid']:null;

    $prev__ziUpdatesReset=[];
    $prev__caUpdatesReset=[];
    $prev__raUpdatesReset=[];
    $prev__ziUpdates=[];
    $prev__caUpdates=[];
    $prev__raUpdates=[];

    $prev__caAdded=[];
    $prev__raAdded=[];

    //loop through all top 3 riders
    for ($i=0; $i < 3; $i++) {
        $_trFEID = $top_riders[$i]['feid']; 
        //check if previous zomato income found against this fied 
        $prev_zi_foundObj = Arr::first($prev_zi, function ($item_zi, $key) use ($_trFEID) {
            return $item_zi['feid'] == $_trFEID;
        });

        $prev_zi_topriders = Arr::first($prev_zi, function ($item_zi, $key) use ($_trFEID, $i) {
            $zi__settings = json_decode($item_zi['setting'], true);
            $top_rider_index = $i+1;
            if(isset($zi__settings['top_position']) && $zi__settings['top_position']==$top_rider_index){
                return true;
            }
            return false;
        });
        //resetting old top riders bonus
        if(isset($prev_zi_topriders)){
            $prev__zi_id = $prev_zi_topriders['p_id'];
            //zomato income
            $objUpdate=[];
            $objUpdate['id']=$prev_zi_topriders['id'];
            $objUpdate['setting']=null;
            array_push($prev__ziUpdatesReset, $objUpdate);

            //company account
            $prev_ca_found = Arr::first($prev_ca, function ($item, $key) use ($prev__zi_id) {
                return $item['income_zomato_id'] == $prev__zi_id && strpos($item['source'], '400 Trips Acheivement Bonus')!==false;
            });
            if (isset($prev_ca_found)) {
                $objUpdate=[];
                $objUpdate['id']=$prev_ca_found['id'];
                $objUpdate['amount']=50;
                $objUpdate['source']='400 Trips Acheivement Bonus';
                array_push($prev__caUpdatesReset, $objUpdate);
            }

            //rider account
            $prev_ra_found = Arr::first($prev_ra, function ($item, $key) use ($prev__zi_id) {
                return $item['income_zomato_id'] == $prev__zi_id && strpos($item['source'], '400 Trips Acheivement Bonus')!==false;
            });
            if (isset($prev_ra_found)) {
                $objUpdate=[];
                $objUpdate['id']=$prev_ra_found['id'];
                $objUpdate['amount']=50;
                $objUpdate['source']='400 Trips Acheivement Bonus';
                array_push($prev__raUpdatesReset, $objUpdate);
            }
        }

        //if found - means this top rider belongs to previous sheet, so we need to change positions if needed
        //if not found - means this top rider belongs to the sheet currently importing, so we need to do nothing because we already handing this when importing current sheet
        $top_rider_pos = $i+1;
        $extra_msg='';

        if( $top_rider_pos==1){
            $bonus_amount = 100 + 50;
            $extra_msg = " + 1st Position Bonus";
        } 
        if( $top_rider_pos==2){
            $bonus_amount = 75 + 50;
            $extra_msg = " + 2nd Position Bonus";
        }
        if( $top_rider_pos==3){
            $bonus_amount = 50 + 50;
            $extra_msg = " + 3rd Position Bonus";
        } 
        if(isset($prev_zi_foundObj)){
            $prev__zi_id = $prev_zi_foundObj['p_id'];
            
            //zomato income
            $objUpdate=[];
            $objUpdate['id']=$prev_zi_foundObj['id'];

            $settings=[];
            $settings['top_position']=$top_rider_pos;
            $objUpdate['setting']=json_encode($settings);
            array_push($prev__ziUpdates, $objUpdate);

            //company account
            $prev_ca_found = Arr::first($prev_ca, function ($item, $key) use ($prev__zi_id) {
                return $item['income_zomato_id'] == $prev__zi_id && strpos($item['source'], '400 Trips Acheivement Bonus')!==false;
            });
            if (isset($prev_ca_found)) {
                $objUpdate=[];
                $objUpdate['id']=$prev_ca_found['id'];
                $objUpdate['rider_id']=$prev_ca_found['rider_id'];
                $objUpdate['amount']=$bonus_amount;
                $objUpdate['source']='400 Trips Acheivement Bonus'.$extra_msg;
                array_push($prev__caUpdates, $objUpdate);
            }

            //rider account
            $prev_ra_found = Arr::first($prev_ra, function ($item, $key) use ($prev__zi_id) {
                return $item['income_zomato_id'] == $prev__zi_id && strpos($item['source'], '400 Trips Acheivement Bonus')!==false;
            });
            if (isset($prev_ra_found)) {
                $objUpdate=[];
                $objUpdate['id']=$prev_ra_found['id'];
                $objUpdate['rider_id']=$prev_ca_found['rider_id'];
                $objUpdate['amount']=$bonus_amount;
                $objUpdate['source']='400 Trips Acheivement Bonus';
                array_push($prev__raUpdates, $objUpdate);
            }
        }
    }

    //updating record (income_zomatos, company__accounts, rider__accounts)
    $prev__data_zi=Batch::update(new Income_zomato, $prev__ziUpdatesReset, 'id'); //r5  
    $prev__data_ca=Batch::update(new \App\Model\Accounts\Company_Account, $prev__caUpdatesReset, 'id'); //r5  
    $prev__data_ra=Batch::update(new \App\Model\Accounts\Rider_Account, $prev__raUpdatesReset, 'id'); //r5  

    $prev__data_zi=Batch::update(new Income_zomato, $prev__ziUpdates, 'id'); //r5  
    $prev__data_ca=Batch::update(new \App\Model\Accounts\Company_Account, $prev__caUpdates, 'id'); //r5  
    $prev__data_ra=Batch::update(new \App\Model\Accounts\Rider_Account, $prev__raUpdates, 'id'); //r5  
    /*======================/Finding top 3 riders===================*/
    // return response()->json([
    //     'data'=>$top_riders,
    //     'deletes'=>$prev__ziUpdates ,
    //     'deletesReset'=>$prev__ziUpdatesReset,
    //     'deletes1'=>$prev__caUpdates,
    //     'deletes1Reset'=>$prev__caUpdatesReset,
    //     'deletes2'=>$prev__raUpdates,
    //     'deletes2Reset'=>$prev__raUpdatesReset,
    //     'expected_month'=>$expected_month
    // ]);
    
    $cr_warnings=[];
    foreach ($data as $item) {
        $i++;
        
        $cr_found = Arr::first($client_riders, function ($item_cr, $key) use ($item) {
            return $item_cr->client_rider_id == $item['feid'];
        });
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
        if (!isset($cr_found)) {
            $rider_not_fournd=[];
            $rider_not_fournd['warning']=$item['feid'].' is not assigned to anyone.';
            array_push($cr_warnings, $rider_not_fournd);
        }
        if(isset($zi_found)){ 
            //delete this
            //zomato
            $objDelete = [];
            $objDelete['id']=$zi_found->id; 
            array_push($delete_data, $objDelete);
            //ca
            $objDelete = [];
            $objDelete['income_zomato_id']=$zi_found->p_id; 
            array_push($ca_delete_data, $objDelete);
            //ra
            $objDelete = [];
            $objDelete['income_zomato_id']=$zi_found->p_id; 
            array_push($ra_delete_data, $objDelete);
        }
        $client_name=isset($item['jdid'])?"Jeebly":"Zomato";
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
        $obj['settlements']=isset($item['settlements'])?$item['settlements']:null;
        
        $obj['date']=isset($item['onboarding_date'])?Carbon::createFromFormat('d/m/Y',$item['onboarding_date'])->format('Y-m-d'):null;
        $obj['created_at']=Carbon::now();
        $obj['updated_at']=Carbon::now();

        $top_rider_pos = "";
        if( $obj['feid'] == $top_rider_1_FEID) $top_rider_pos=1;
        if( $obj['feid'] == $top_rider_2_FEID) $top_rider_pos=2;
        if( $obj['feid'] == $top_rider_3_FEID) $top_rider_pos=3;
        $obj['setting']="";
        if($top_rider_pos != ""){
            $settings=[];
            $settings['top_position']=$top_rider_pos;
            $obj['setting']=json_encode($settings);
        }

        array_push($zomato_obj, $obj);

        
        $ca_amt1 = $obj['total_to_be_paid_out'];
        if($ca_amt1 > 0){
            $ca_obj = [];
            $ca_obj['income_zomato_id']=$p_id;
            $ca_obj['source']=$client_name.' Payout';
            $ca_obj['rider_id']=$rider_id;
            $ca_obj['amount']=$ca_amt1;
            $ca_obj['month']=$obj['date'];
            $ca_obj['type']='cr';
            $ca_obj['given_date']=Carbon::now();
            $ca_obj['created_at']=Carbon::now();
            $ca_obj['updated_at']=Carbon::now();
            array_push($ca_objects, $ca_obj);
        }

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
            $ca_obj['given_date']=Carbon::now();
            $ca_obj['created_at']=Carbon::now();
            $ca_obj['updated_at']=Carbon::now();
            array_push($ca_objects, $ca_obj);
        }
        // $ra_amt2 = $obj['ncw_incentives'];
        // if($ra_amt2 > 0){
        //     $ra_obj = [];
        //     $ra_obj['income_zomato_id']=$p_id;
        //     $ra_obj['source']='NCW Incentives';
        //     $ra_obj['rider_id']=$rider_id;
        //     $ra_obj['amount']=$ra_amt2;
        //     $ra_obj['month']=$obj['date'];
        //     $ra_obj['type']='cr';
        //     $ra_obj['given_date']=Carbon::now();
        //     $ra_obj['created_at']=Carbon::now();
        //     $ra_obj['updated_at']=Carbon::now();
        //     array_push($ra_objects, $ra_obj);
        // }

        $ca_amt2 = $obj['tips_payouts'];
        if($ca_amt2 > 0){
            $ca_obj = [];
            $ca_obj['income_zomato_id']=$p_id;
            $ca_obj['source']='Tips Payouts';
            $ca_obj['rider_id']=$rider_id;
            $ca_obj['amount']=$ca_amt2;
            $ca_obj['month']=$obj['date'];
            $ca_obj['type']='dr';
            $ca_obj['given_date']=Carbon::now();
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
            $ra_obj['given_date']=Carbon::now();
            $ra_obj['created_at']=Carbon::now();
            $ra_obj['updated_at']=Carbon::now();
            array_push($ra_objects, $ra_obj);
        }

        $ca_amt2 = $obj['settlements'];
        if($ca_amt2 > 0){
            $ca_obj = [];
            $ca_obj['income_zomato_id']=$p_id;
            $ca_obj['source']=$client_name.' Settlements';
            $ca_obj['rider_id']=$rider_id;
            $ca_obj['amount']=$ca_amt2;
            $ca_obj['month']=$obj['date'];
            $ca_obj['type']='dr';
            $ca_obj['given_date']=Carbon::now();
            $ca_obj['created_at']=Carbon::now();
            $ca_obj['updated_at']=Carbon::now();
            array_push($ca_objects, $ca_obj);
        }
        

        //bonus after 400 trips
        
        $total_trips = $obj['trips_payable'];
        if($total_trips > 400){
            $bonus_amount = 50;  // bonus amount
            $extra_msg = "";
            if( $obj['feid'] == $top_rider_1_FEID){
                $bonus_amount = 100 + 50;
                $extra_msg = " + 1st Position Bonus";
            } 
            if( $obj['feid'] == $top_rider_2_FEID){
                $bonus_amount = 75 + 50;
                $extra_msg = " + 2nd Position Bonus";
            }
            if( $obj['feid'] == $top_rider_3_FEID){
                $bonus_amount = 50 + 50;
                $extra_msg = " + 3rd Position Bonus";
            } 

            $ca_obj = [];
            $ca_obj['income_zomato_id']=$p_id;
            $ca_obj['source']='400 Trips Acheivement Bonus'.$extra_msg;
            $ca_obj['rider_id']=$rider_id;
            $ca_obj['amount']=$bonus_amount;
            $ca_obj['month']=$obj['date'];
            $ca_obj['type']='dr';
            $ca_obj['given_date']=Carbon::now();
            $ca_obj['created_at']=Carbon::now();
            $ca_obj['updated_at']=Carbon::now();
            array_push($ca_objects, $ca_obj);

            $ra_obj = [];
            $ra_obj['income_zomato_id']=$p_id;
            $ra_obj['source']='400 Trips Acheivement Bonus';
            $ra_obj['rider_id']=$rider_id;
            $ra_obj['amount']=$bonus_amount;
            $ra_obj['month']=$obj['date'];
            $ra_obj['type']='cr';
            $ra_obj['given_date']=Carbon::now();
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
            $ra_obj['given_date']=Carbon::now();
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
            $ra_obj['given_date']=Carbon::now();
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
            $ra_obj['given_date']=Carbon::now();
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
            $ca_obj['given_date']=Carbon::now();
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
            $ca_obj['given_date']=Carbon::now();
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
            $ca_obj['given_date']=Carbon::now();
            $ca_obj['created_at']=Carbon::now();
            $ca_obj['updated_at']=Carbon::now();
            array_push($ca_objects, $ca_obj);
        }
        
    }

    $iz_deletes = DB::table('income_zomatos')
                    ->whereIn('id', $delete_data)
                    ->delete();

    $ca_deletes = DB::table('company__accounts')
                    ->whereIn('income_zomato_id', $ca_delete_data)
                    ->delete();
    $ra_deletes = DB::table('rider__accounts')
                    ->whereIn('income_zomato_id', $ra_delete_data)
                    ->delete();

    Income_zomato::insert($zomato_obj); //r2
    DB::table('company__accounts')->insert($ca_objects); //r4
    DB::table('rider__accounts')->insert($ra_objects); //r4
    // $data_ra=Batch::update(new \App\Model\Accounts\Rider_Account, $ra_objects_updates, 'income_zomato_id'); //r5  

    $tax=new Company_Tax();
    $tax->type="vat";
    $tax->z_import_id=$unique_id;
    $tax->month=Carbon::createFromFormat('d/m/Y',$tax_data['date'])->format('Y-m-d');
    $tax->total_to_be_paid_out=$tax_data['total_to_be_paid_out'];
    $tax->log_in_hours_payable=$tax_data['log_in_hours_payable'];
    $tax->trips_payable=$tax_data['trips_payable'];
    $tax->amount_for_login_hours=$tax_data['amount_for_login_hours'];
    $tax->amount_to_be_paid_against_orders_completed=$tax_data['amount_to_be_paid_against_orders_completed'];
    $tax->total_to_be_paid_out_with_tax=$tax_data['total_amount_with_tax'];
    $tax->taxable_amount=$tax_data['taxable_amount'];
    // $tax->save();

    return response()->json([
        'status'=>1,
        'cr_warning'=>$cr_warnings,
        'data'=>$zomato_obj,
        'iz_deletes'=>$iz_deletes,
        'data_ca'=>$ca_objects,
        'ca_deletes'=>$ca_deletes,
        'data_ra'=>$ra_objects,
        'ra_deletes'=>$ra_deletes,
        'count'=>$i,
        'tax'=>$tax,
    ]);
}

//ends income zomato

// client_income
public function client_income_index(){
   $clients=Client::where("active_status","A")->get();
   $riders=Rider::where("active_status","A")->get();
    return view('accounts.Client_income.add_income',compact("clients", 'riders'));
}
public function careem_payout_index(){
    $clients=Client::where("active_status","A")->get();
    $client_history=Client_History::with("rider")->get();
    return view('accounts.Client_income.careem_payout',compact("clients", 'client_history'));
 }
 public function rider_joining_date($rider_id,$month){
     $joining_date=Rider_detail::where("rider_id",$rider_id)->get()->first();
     if (isset($joining_date)) {
         $Join_date=$joining_date->date_of_joining;
     }
     $already_incomes = Client_Income::where(['rider_id'=>$rider_id, 'month'=>Carbon::parse($month)->format('Y-m-d')])
    ->get();
    return response()->json([
        'Join_date'=>$Join_date,
        'month'=>Carbon::parse($month)->format("Y-m-d"),
        'client__incomes'=>$already_incomes
    ]);
 }
public function client_income_getRiders($client_id, $month){ 
    $clients=Client::find($client_id);
    $onlymonth=Carbon::parse($month)->format('m');
    $startMonth=Carbon::parse($month)->startOfMonth()->format('Y-m-d');
    //$riders=$clients->riders;

    $client_history = Client_History::with('rider')->with('Client')->get()->toArray(); 
    $riders = Arr::where($client_history, function ($item, $key) use ($client_id, $startMonth) {
        $start_created_at =Carbon::parse($item['assign_date'])->startOfMonth()->format('Y-m-d');
        $created_at =Carbon::parse($start_created_at);

        $start_updated_at =Carbon::parse($item['deassign_date'])->endOfMonth()->format('Y-m-d');
        $updated_at =Carbon::parse($start_updated_at);
        $req_date =Carbon::parse($startMonth);

        if($item['status']=='active'){    
            return $item['client_id']==$client_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
        }

        return $item['client_id']==$client_id &&
            ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
    });
    return response()->json([
        'data' => $riders
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
    $readonly=false;
    $clients=Client::where("active_status","A")->get();
    $edit_client_income=Client_Income::find($id);
    return view('accounts.Client_income.edit_income',compact('readonly','edit_client_income','clients'));
}
public function client_income_edit_view($id){
    $readonly=true;
    $clients=Client::where("active_status","A")->get();
    $edit_client_income=Client_Income::find($id);
    return view('accounts.Client_income.edit_income',compact('readonly','edit_client_income','clients'));
}
public function client_comission_income_store(Request $request){
    $client = Client::find($request->client_id);
    $incomes = $request->incomes;
    #delete previous data
    $already_incomes = Client_Income::where(['rider_id'=>$request->rider_id, 'month'=>Carbon::parse($request->get('month'))->format('Y-m-d')])
    ->get();
    foreach ($already_incomes as $already_income) {
        \App\Model\Accounts\Company_Account::where('client_income_id', $already_income->id)->delete();
        $already_income->delete();
    }
    #end delete previous data
    foreach ($incomes as $income) {
        $client_income=new Client_Income();
        $client_income->client_id=$request->client_id;
        $client_income->rider_id=$request->rider_id;
        $client_income->month=Carbon::parse($request->get('month'))->startOfMonth()->format('Y-m-d');
        $client_income->given_date=Carbon::parse($request->get('date'))->format('Y-m-d');
        $client_income->cash=$income['cash'];
        $client_income->cash_trips=$income['cash_trips'];
        $client_income->bank=$income['bank'];
        $client_income->bank_trips=$income['bank_trips'];
        $client_income->captain_tips=$income['captain_tips'];
        $client_income->item_bought=$income['item_bought'];
        $client_income->item_qty=$income['item_qty'];
        $client_income->total_payout=$income['total_payout'];
        $client_income->status=1;
        $client_income->income_type="commission_based";
        $client_income->week_start=Carbon::parse($income['week_start'])->format('Y-m-d');
        $client_income->week_end=Carbon::parse($income['week_end'])->format('Y-m-d');
        if ($income['bank']!=null && $income['bank']!="0" || ($income['bank_trips']!=null && $income['bank_trips']!="0")) {
            $client_income->save();
        }
        

        $ca = new \App\Model\Accounts\Company_Account;
        $ca->client_income_id =$client_income->id;
        $ca->type='cr';
        $ca->rider_id=$client_income->rider_id;
        $ca->month = Carbon::parse($request->get('month'))->format('Y-m-d');
        $ca->given_date = Carbon::parse($request->get('date'))->format('Y-m-d');
        $ca->source=$client->name." Payout for Week (".Carbon::parse($income['week_start'])->format('d-M-y')." - ".Carbon::parse($income['week_end'])->format('d-M-y').")"; 
        $ca->amount=$client_income->total_payout;
        if ($income['bank']!=null && $income['bank']!="0" || ($income['bank_trips']!=null && $income['bank_trips']!="0")) {
            $ca->save();
        }
        
    }
    return redirect(route('admin.client_income_view'));
}
public function client_income_store(Request $request){
    $client = Client::find($request->client_id);
    $incomes = $request->incomes;
    foreach ($incomes as $income) {
        $client_income=new Client_Income();
        $client_income->client_id=$income['client_id'];
        $client_income->rider_id=$income['rider_id'];
        $client_income->month=Carbon::parse($request->get('month'))->format('Y-m-d');
        $client_income->given_date=Carbon::parse($request->get('date'))->format('Y-m-d');
        $client_income->perday_hours=$income['perday_hours'];
        $client_income->working_days=$income['working_days'];
        $client_income->total_hours=$income['total_hours'];
        $client_income->extra_hours=$income['extra_hours'];
        $client_income->total=$income['total'];
        $client_income->total_payout=$income['total_payout'];
        $client_income->status=1;
        $client_income->income_type="fixed_based";
        $client_income->save();

        $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
            'client_income_id'=>$client_income->id
        ]);
        $ca->client_income_id =$client_income->id;
        $ca->type='cr';
        $ca->rider_id=$client_income->rider_id;
        $ca->month = Carbon::parse($request->get('month'))->format('Y-m-d');
        $ca->given_date = Carbon::parse($request->get('date'))->format('Y-m-d');
        $ca->source=$client->name." Payout"; 
        $ca->amount=$client_income->total_payout;
        $ca->save();
    }
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
   
        return redirect(route('admin.client_income_edit_view',$update_income->id));
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
        $type=explode('@',$d_type);
        $ca = new \App\Model\Accounts\Company_Account;
        $ca->type='dr';
        $ca->amount=$r->amount;
        $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->rider_id = $r->rider_id;
        $ca->source=$type[1];
        $ca->save();
        $ra = new \App\Model\Accounts\Rider_Account;
        if($type[0]=='0'){
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
        $ra->source=$type[1];
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
    
    public function rider_remaining_salary_add(Request $r){
        $ra=Rider_Account::find($r->account_id);
        $salary = Rider_salary::find($ra->salary_id);
        //adding cash
        $statement_id=$r->statement_id;
        if(isset($statement_id) && $statement_id!=""){
            //update salary
            $new_ra=Rider_Account::find($statement_id);
        }
        else {
            $new_ra=new Rider_Account;
        }
        // return response()->json([
        //     'a'=>$new_ra
        // ]);
        $new_ra->type="dr";
        $new_ra->amount=$r->recieved_salary;
        $new_ra->source="salary_paid";
        $new_ra->payment_status="paid";
        $new_ra->rider_id=$ra->rider_id;
        $new_ra->salary_id=$ra->salary_id;
        $new_ra->month=Carbon::parse($r->month)->startOfMonth()->format('Y-m-d'); 
        $new_ra->given_date=Carbon::parse($r->given_date)->format('Y-m-d'); 
        $new_ra->save();

        //updating salary
        $salary->recieved_salary = $r->recieved_salary;
        $salary->remaining_salary = round($r->remaining_salary,2);
        $salary->update();

        return response()->json([
            'data'=>'true',
            'd'=> $r->all()
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
        else if($d_type=='cr'){
            $ca = new \App\Model\Accounts\Company_Account;
            $ca->type='cr';
            $ca->amount=$r->amount;
            $ca->month=Carbon::parse($r->get('month'))->format('Y-m-d');
            $ca->rider_id = $r->cash_rider_id;
            $ca->source=$r->desc;
            $ca->save();
            
            $ra = new \App\Model\Accounts\Rider_Account;
            $ra->type='cr';
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

  public function updateBillPaymentStatus($rider_id,$month,$type){
      $month_a=Carbon::parse($month)->startOfMonth()->format('m');
    $ca=Company_Account::where("rider_id",$rider_id)->whereMonth("month",$month_a)->where("source",$type)->get();
    foreach ($ca as $ca_all) {
      $ca_all->payment_status="paid";
      $ca_all->save();
    }
    
    // $ca->save();
    return response()->json([
        'data'=>'true',
        'ca'=>$ca,
    ]);

}
    // salary deduction with respect to ratio method
    public static function get_salary_deduction_v2($month,$rider_id){  
        // //dataset we need to fetch formula from
        // $dataset=array(
        //     ['trips'=>0,'hours'=>286,'rate_per_trip'=>2,'rate_per_hour'=>4],
        //     ['trips'=>100,'hours'=>286,'rate_per_trip'=>2,'rate_per_hour'=>4],
        //     ['trips'=>150,'hours'=>286,'rate_per_trip'=>2.25,'rate_per_hour'=>4.5],
        //     ['trips'=>200,'hours'=>286,'rate_per_trip'=>2.5,'rate_per_hour'=>5],
        //     ['trips'=>250,'hours'=>286,'rate_per_trip'=>2.75,'rate_per_hour'=>5.5],
        //     ['trips'=>300,'hours'=>286,'rate_per_trip'=>3,'rate_per_hour'=>5.75],
        //     ['trips'=>350,'hours'=>286,'rate_per_trip'=>3.25,'rate_per_hour'=>6],
        //     ['trips'=>400,'hours'=>286,'rate_per_trip'=>3.5,'rate_per_hour'=>6.125],
        //     ['trips'=>450,'hours'=>286,'rate_per_trip'=>3.5,'rate_per_hour'=>6.25],
        //     ['trips'=>500,'hours'=>286,'rate_per_trip'=>3.5,'rate_per_hour'=>6.375],
        //     ['trips'=>550,'hours'=>286,'rate_per_trip'=>3.5,'rate_per_hour'=>6.5],
        //     ['trips'=>1000,'hours'=>286,'rate_per_trip'=>3.5,'rate_per_hour'=>6.5],
        // );

        // //calculate ratios and store it into the data set
        // foreach ($dataset as $key => $data_item) {
        //     $item_trips = $dataset[$key]['trips'];
        //     $item_hours = $dataset[$key]['hours'];
        //     $dataset[$key]['ratio']=round($item_trips/$item_hours,6);
        // }
        
        // // $trips_v2=186;
        // // $hours_v2=275.6;

        // //calc current ratio
        // $current_ratio=round($trips_v2/$hours_v2,6);

        // //find where ratio falls
        // $current_ratio_index=null;
        // $current_ratio_Arr = Arr::first($dataset, function ($data_item, $key) use (&$current_ratio_index,$current_ratio) {
            
        //     if($current_ratio<$data_item['ratio']){
        //         $current_ratio_index=$key-1;
        //         return true;
        //     }
        //     if($current_ratio==$data_item['ratio']){
        //         $current_ratio_index=$key;
        //         return true;
        //     }
        //     return false;
        // });

        // //finding min and max value
        // $max_index=$current_ratio_index+1;
        // if($current_ratio_index == -1 || $current_ratio_index==count($dataset)){
        //     return response()->json([
        //         'status'=>0,
        //         'msg'=>'index is out of bounds'
        //     ]); 
        // }
        // //check if max is out of bound of array
        // if(($current_ratio_index+1)>=count($dataset)){
        //     // return response()->json([
        //     //     'status'=>0,
        //     //     'msg'=>'index is out of bounds'
        //     // ]); 
        //     $max_index=0; // reset to the 1st index
        // }

        // $min = $dataset[$current_ratio_index];
        // $max = $dataset[$max_index];

        // //min/max ratio
        // $min_ratio=$min['ratio'];
        // $max_ratio=$max['ratio'];

        // //finds %age of fall
        // $fall_percentage_of_ratio = round((($current_ratio-$min_ratio)/($max_ratio-$min_ratio))*100);

        // //min/max rate per trips
        // $min_rpt=$min['rate_per_trip'];
        // $max_rpt=$max['rate_per_trip'];

        // //finds current rate per trips
        // $current_rpt=((($max_rpt-$min_rpt)/100)*$fall_percentage_of_ratio)+$min_rpt;

        // //min/max rate per hour
        // $min_rph=$min['rate_per_hour'];
        // $max_rph=$max['rate_per_hour'];

        // //finds current rate per hours
        // $current_rph=((($max_rph-$min_rph)/100)*$fall_percentage_of_ratio)+$min_rph;


        // //salary
        // $salary = ($trips_v2*$current_rpt)+(($hours_v2*$current_rph));

        

        // return response()->json([
        //     'status'=>1,
        //     'data_set'=>$dataset,
        //     'input_trips'=>$trips_v2,
        //     'input_hours'=>$hours_v2,
        //     'current_index'=>$current_ratio_index,
        //     'current_ratio'=>$current_ratio,
        //     'min'=>$min,
        //     'max'=>$max,
        //     'percentage'=>$fall_percentage_of_ratio,
        //     'hours'=>$current_rph,
        //     'trips'=>$current_rpt,
        //     'salary'=>$salary,




        //     'test'=>count($dataset)
        // ]);
        // before tthat check if rider is zomato's
        $startMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
        $month = Carbon::parse($month)->format('Y-m-d');
        $onlyMonth = Carbon::parse($month)->format('m');
        $onlyYear = Carbon::parse($month)->format('Y');
        $rider = Rider::find($rider_id);

        //prev payables
        $rider_debits_cr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->where(function($q) {
            $q->where('type', "cr");
        })
        ->whereDate('month', '<',$startMonth)
        ->sum('amount');
        
        $rider_debits_dr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->where(function($q) {
            $q->where('type', "cr_payable")
              ->orWhere('type', 'dr');
        })
        ->whereDate('month', '<',$startMonth)
        ->sum('amount');
        $closing_balance_prev = round($rider_debits_cr_prev_payable - $rider_debits_dr_prev_payable,2);
        //ends prev payables

        $ra_payable=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->where(function($q) {
            $q->where('type', "cr_payable")
            ->orWhere('type', 'dr');
        })
        ->sum('amount');


        $ra_cr=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->where("payment_status","pending")
        ->where("type","cr")
        ->where("source",'!=',"salary")
        ->sum('amount');  
        if($closing_balance_prev < 0){ //deduct
            $ra_payable += abs($closing_balance_prev);
        }
        else {
            // add
            $ra_cr += abs($closing_balance_prev);
        }
        $total_salary_amt = 0;

        $client_history = Client_History::all(); 
        $history_found = Arr::first($client_history, function ($item, $key) use ($rider_id, $startMonth) {
            $start_created_at =Carbon::parse($item->assign_date)->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);

            $start_updated_at =Carbon::parse($item->deassign_date)->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($startMonth);

            if($item->status=='active'){    
                return $item->rider_id==$rider_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
            }

            return $item->rider_id==$rider_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });

        $feid=null;
        $client=null;
        $client_setting=[];
        $pm='';
        if (isset($history_found)) {
            $feid=$history_found->client_rider_id;
            $client=Client::find($history_found->client_id);
            if($client->salary_methods==null){
                # no salary method was found against this client
                return response()->json([
                    'status'=>0,
                    'msg'=>'No salary method is found under client '.$client->name
                ]);
            }
            $client_setting = json_decode($client->salary_methods, true);
            $pm = $client_setting['salary_method'];
        }

        $zomato_hours =0;
        $zomato_trips = 0;

        $calculated_hours =0;
        $calculated_trips = 0;
        $absent_count=0;
        $working_count=0;
        $absent_hours=0;
        $working_hours=0;
        $less_time=0;
        $bonus=0;
        $absent_app=0;
        $positions=0;
        $commission=0;
        $commission_value='0%';


        //some static_data
        $_s_maxTrips=400;
        $_s_tripsFormula=2;
        $_s_maxTripsFormula=4;
        $_s_monthlyHours=286;
        $_s_hoursFormula=7.87;
        $_s_maxHours=11;
        if(isset($feid) && $feid!=null){ // rider belongs to zomato

            $ra_zomatos=Income_zomato::where("rider_id",$rider_id)
            ->whereMonth("date",$onlyMonth)
            ->get()->first();  

            if ($pm=='trip_based') {
                
                $_s_maxTrips=$client_setting['tb_sm__bonus_trips'];
                $_s_tripsFormula=$client_setting['tb_sm__trip_amount'];
                $_s_maxTripsFormula=$client_setting['tb_sm__trips_bonus_amount'];
                $_s_monthlyHours=286;
                $_s_hoursFormula=$client_setting['tb_sm__hour_amount'];
                $_s_maxHours=11; 
            }
           
            
            if(isset($ra_zomatos)){
                
                $absent_app=$ra_zomatos->approve_absents;
                $absent_approve_hours=$absent_app*$_s_maxHours;
                $calculated_absent_hours=$absent_approve_hours*$_s_hoursFormula;
                
                $absent_count=$ra_zomatos->absents_count;
                $working_count=$ra_zomatos->working_days;

                $weekly_off=$ra_zomatos->weekly_off;
                $extra_day=$ra_zomatos->extra_day;
                
                $absent_hours=$absent_count*$_s_maxHours;
                $working_hours=$working_count*$_s_maxHours;

                $calculated_hours =$ra_zomatos->calculated_hours;
                $calculated_trips = $ra_zomatos->calculated_trips;

                $less_time=$working_hours-$calculated_hours;

                $zomato_hours =$ra_zomatos->log_in_hours_payable;
                $zomato_trips = $ra_zomatos->trips_payable;

                $hours=$calculated_hours;
                // $hours_payable=$hours*$_s_hoursFormula;

                $trips = $calculated_trips;
                $payable_hours=round($_s_monthlyHours - $absent_hours - $less_time,2);
                ##  temp_coding
                //dataset we need to fetch formula from
                $dataset=array(
                    ['trips'=>0,'hours'=>286,'rate_per_trip'=>2,'rate_per_hour'=>4],
                    ['trips'=>100,'hours'=>286,'rate_per_trip'=>2,'rate_per_hour'=>4],
                    ['trips'=>150,'hours'=>286,'rate_per_trip'=>2.25,'rate_per_hour'=>4.5],
                    ['trips'=>200,'hours'=>286,'rate_per_trip'=>2.5,'rate_per_hour'=>5],
                    ['trips'=>250,'hours'=>286,'rate_per_trip'=>2.75,'rate_per_hour'=>5.5],
                    ['trips'=>300,'hours'=>286,'rate_per_trip'=>3,'rate_per_hour'=>5.75],
                    ['trips'=>350,'hours'=>286,'rate_per_trip'=>3.25,'rate_per_hour'=>6],
                    ['trips'=>400,'hours'=>286,'rate_per_trip'=>3.5,'rate_per_hour'=>6.125],
                    ['trips'=>450,'hours'=>286,'rate_per_trip'=>3.5,'rate_per_hour'=>6.25],
                    ['trips'=>500,'hours'=>286,'rate_per_trip'=>3.5,'rate_per_hour'=>6.375],
                    ['trips'=>550,'hours'=>286,'rate_per_trip'=>3.5,'rate_per_hour'=>6.5],
                    ['trips'=>1000,'hours'=>286,'rate_per_trip'=>3.5,'rate_per_hour'=>6.5],
                );

                //calculate ratios and store it into the data set
                foreach ($dataset as $key => $data_item) {
                    $item_trips = $dataset[$key]['trips'];
                    $item_hours = $dataset[$key]['hours'];
                    $dataset[$key]['ratio']=round($item_trips/$item_hours,6);
                }
                
                $trips_v2=$trips;
                $hours_v2=$payable_hours;

                //calc current ratio
                if($hours_v2==0){
                    $current_ratio=0;
                }
                else {
                    $current_ratio=round($trips_v2/$hours_v2,6);
                }

                //find where ratio falls
                $current_ratio_index=null;
                $current_ratio_Arr = Arr::first($dataset, function ($data_item, $key) use (&$current_ratio_index,$current_ratio) {
                    
                    if($current_ratio<$data_item['ratio']){
                        $current_ratio_index=$key-1;
                        return true;
                    }
                    if($current_ratio==$data_item['ratio']){
                        $current_ratio_index=$key;
                        return true;
                    }
                    return false;
                });

                //finding min and max value
                $max_index=$current_ratio_index+1;
                if($current_ratio_index == -1 || $current_ratio_index==count($dataset)){
                    return response()->json([
                        'status'=>0,
                        'msg'=>'index is out of bounds'
                    ]); 
                }
                //check if max is out of bound of array
                if(($current_ratio_index+1)>=count($dataset)){
                    // return response()->json([
                    //     'status'=>0,
                    //     'msg'=>'index is out of bounds'
                    // ]); 
                    $max_index=0; // reset to the 1st index
                }

                $min = $dataset[$current_ratio_index];
                $max = $dataset[$max_index];

                //min/max ratio
                $min_ratio=$min['ratio'];
                $max_ratio=$max['ratio'];

                //finds %age of fall
                $fall_percentage_of_ratio = round((($current_ratio-$min_ratio)/($max_ratio-$min_ratio))*100,6);

                //min/max rate per trips
                $min_rpt=$min['rate_per_trip'];
                $max_rpt=$max['rate_per_trip'];

                //finds current rate per trips
                $current_rpt=((($max_rpt-$min_rpt)/100)*$fall_percentage_of_ratio)+$min_rpt;

                //min/max rate per hour
                $min_rph=$min['rate_per_hour'];
                $max_rph=$max['rate_per_hour'];

                //finds current rate per hours
                $current_rph=((($max_rph-$min_rph)/100)*$fall_percentage_of_ratio)+$min_rph;
                ## end temp coding

                $trips_payable = $trips_v2*$current_rpt;

                // $trips_payable+=$trips_EXTRA_payable;

                

                $hours_payable=$payable_hours*$current_rph;
                $salary_hours=round($hours_payable,2);
                $salary_trips=$trips_payable;
                $salary_credits=round($ra_cr,2);
                $ra_salary=$salary_hours +$salary_trips  +$salary_credits ;
                $ra_recieved=$ra_salary - $ra_payable;

                $total_salary_amt = round($salary_hours+$salary_trips,2);
            }
            else { 
                # no record found in income zomato table --generate error 
                return response()->json([
                    'status'=>0,
                    'msg'=>'No record found on Zomato Income against this rider.'
                ]);
            }
        }
        else { // other clients
            if(isset($rider->rider_type)&&$rider->rider_type=='Employee'){
                #### employee's salary
                $rd = $rider->Rider_detail;
                $basic_salary = 2000;
                if($rd->salary!=null){
                    $basic_salary=$rd->salary;  
                }
                $fixed_salary = $basic_salary;
                $ra_salary= $fixed_salary + $ra_cr;
                $ra_recieved=$ra_salary - $ra_payable;
                $total_salary_amt = $fixed_salary;
                $pm='employee';
            }
            else {
                #### rider's salary
                if($pm==''){
                    # no client was found undr this rider
                    return response()->json([
                        'status'=>0,
                        'msg'=>'No client assigned to this rider.'
                    ]);
                }
                if($pm=='fixed_based'){
                    $basic_salary=isset($client_setting['fb_sm__amount'])?$client_setting['fb_sm__amount']:0;
                    $client_income=Client_Income::where("rider_id",$rider_id)
                    ->whereMonth("month",$onlyMonth)
                    ->whereYear("month",$onlyYear)
                    ->get()->first(); 
                    if(isset($client_income)){
                        $basic_salary = isset($basic_salary)?$basic_salary:0;
                        $fb__working_hours = $client_income->total_hours;
                        $fb__extra_hours = $client_income->extra_hours;

                        // $fb__perHourSalary = $basic_salary/$fb__working_hours;
                        $fb__perHourSalary = isset($client_setting['fb_sm__exrta_hours'])?$client_setting['fb_sm__exrta_hours']:0;
                        $extra_salary = $fb__perHourSalary * $fb__extra_hours;

                        $fixed_salary = $basic_salary + $extra_salary;
                        $ra_salary= $fixed_salary + $ra_cr;
                        $ra_recieved=$ra_salary - $ra_payable;
                        $total_salary_amt = $fixed_salary;
                    }
                    else {
                        # no record found in income zomato table --generate error
                        return response()->json([
                            'status'=>0,
                            'msg'=>'No Payout found against this rider.'
                        ]);
                    }
                    
                }
                if($pm=='commission_based'){
                    $commission_val=isset($client_setting['cb_sm__amount'])?$client_setting['cb_sm__amount']:0;
                    $commission_type=isset($client_setting['cb_sm__type'])?$client_setting['cb_sm__type']:0;
                    $commission_value=$commission_val.($commission_type=='percentage'?'%':' AED');
                    $basic_salary=0;
                    $client_income=Client_Income::where("rider_id",$rider_id)
                    ->whereMonth("month",$onlyMonth)
                    ->whereYear("month",$onlyYear)
                    ->where('income_type', 'commission_based')
                    ->get()->sum('total_payout');
                    if(isset($client_income) && $client_income>0){
                        $basic_salary = $client_income;
                        
                        if($commission_type=='percentage'){
                            $commission=($basic_salary/100)*$commission_val;
                            $fixed_salary = $basic_salary - $commission;
                        }
                        else {
                            $commission=$commission_val;
                            $fixed_salary = $basic_salary - $commission;
                        }
                        $ra_salary= $fixed_salary + $ra_cr;
                        $ra_recieved=$ra_salary - $ra_payable;
                        $total_salary_amt = $fixed_salary;
                    }
                    else {
                        # no record found in income zomato table --generate error
                        return response()->json([
                            'status'=>0,
                            'msg'=>'No Payout found against this rider.'
                        ]);
                    }
                    
                }
                if($pm=='trip_based'){
                    # no FEID found and FEID is cumpulsory for trip based rider
                    return response()->json([
                        'status'=>0,
                        'msg'=>'No FEID found against this rider.'
                    ]);
                }
            }
        }

        $is_generated= Rider_salary::where('rider_id',$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->get()
        ->first();
       if (isset($is_generated)) {
            $is_generated_salary=true;
       }
       else{
            $is_generated_salary=false;
       }
        
       $is_paid= \App\Model\Accounts\Rider_Account::where("source","salary_paid")
       ->where("payment_status","paid")
       ->where("rider_id",$rider_id)
       ->whereMonth("month",$onlyMonth)
       ->get()
       ->first();
       if (isset($is_paid)) {
            $is_paid_salary=true;
       }else{
            $is_paid_salary=false;
       }

       //fixed based client
       $basic_salary=isset($basic_salary)?$basic_salary:0;
       $fb__working_hours=isset($fb__working_hours)?$fb__working_hours:0;
       $fb__extra_hours=isset($fb__extra_hours)?$fb__extra_hours:0;
       $fb__perHourSalary=isset($fb__perHourSalary)?$fb__perHourSalary:0;
       //end fixed based client

       $salary_hours=isset($salary_hours)?$salary_hours:0;
       $absent_approve_hours=isset($absent_approve_hours)?$absent_approve_hours:0;
       $salary_trips=isset($salary_trips)?$salary_trips:0;
       $salary_credits=isset($salary_credits)?$salary_credits:0;
       $weekly_off=isset($weekly_off)?$weekly_off:0;
       $extra_day=isset($extra_day)?$extra_day:0;
       $trips=isset($trips)?$trips:0;
       $hours=isset($hours)?$hours:0;
       $hours_payable=isset($hours_payable)?$hours_payable:0;
       $trips_payable=isset($trips_payable)?$trips_payable:0;
       $calculated_absent_hours=isset($calculated_absent_hours)?$calculated_absent_hours:0;
       $trips_EXTRA=isset($trips_EXTRA)?$trips_EXTRA:0;
       $trips_EXTRA_payable=isset($trips_EXTRA_payable)?$trips_EXTRA_payable:0;
       $less_time=isset($less_time)?$less_time:0;
       $payable_hours=isset($payable_hours)?$payable_hours:0;
        return response()->json([
            'status'=>1,
            'salary_method'=>$pm,
            'ra_salary'=>$ra_salary,
            'client'=>$client,
            
            'salary_hours'=>$salary_hours,
            'salary_trips'=>$salary_trips,
            'salary_credits'=>$salary_credits,

            'commission'=>$commission,
            'commission_value'=>$commission_value,

            'net_salary'=>round($ra_salary,2),
            'gross_salary'=>round($ra_recieved,2),
            'zomato_hours'=>round($zomato_hours,2),
            'zomato_trips'=>round($zomato_trips,2),
            'total_deduction'=>round($ra_payable,2),
            'total_salary'=>round($total_salary_amt,2),
            'closing_balance_prev'=>$closing_balance_prev,
            'is_paid'=>$is_paid_salary,
            'is_generated'=>$is_generated_salary,
            'absent_count'=>$absent_count,
            'weekly_off'=>$weekly_off,
            'extra_day'=>$extra_day,
            'working_days'=>$working_count,
            'absent_hours'=>$absent_hours,
            'working_hours'=>$working_hours,
            'trips'=>round($trips,2),
            'hours'=>round($hours,2),
            'bonus'=>$bonus,
            'absent_approve_hours'=>$absent_approve_hours,
            'calculated_absent_hours'=>round($calculated_absent_hours,2),
            
            'hours_payable'=>round($hours_payable,2),
            'trips_payable'=>round($trips_payable,2),
            'trips_EXTRA'=>round($trips_EXTRA,2),
            'trips_EXTRA_payable'=>round($trips_EXTRA_payable,2),
            'less_time'=>round($less_time,2),
            'payable_hours'=>round($payable_hours,2),

            'basic_salary'=>round($basic_salary,2),
            'fb__working_hours'=>round($fb__working_hours,2),
            'fb__extra_hours'=>round($fb__extra_hours,2),
            'fb__perHourSalary'=>round($fb__perHourSalary,2),

            '_s_maxTrips'=>round($_s_maxTrips,2),
            '_s_tripsFormula'=>round($_s_tripsFormula,2),
            '_s_maxTripsFormula'=>round($_s_maxTripsFormula,2),
            '_s_monthlyHours'=>round($_s_monthlyHours,2),
            '_s_hoursFormula'=>round($_s_hoursFormula,2),
            '_s_maxHours'=>$_s_maxHours,

            'pm'=>$pm,
            'client_setting'=>$client_setting,
            'position'=>$positions,
        ]);
    }
    //static function to calculate profit
    public static function calculate_profit($month,$rider_id){
        $startMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
        $month = Carbon::parse($month)->format('Y-m-d');
        $onlyMonth = Carbon::parse($month)->format('m');
        $onlyYear = Carbon::parse($month)->format('Y');
        $rider = Rider::find($rider_id);

        // //prev payables
        // $ca_debits_cr_prev_payable = \App\Model\Accounts\Company_Account::where("rider_id",$rider_id)
        // ->where(function($q) {
        //     $q->where('type', "dr_receivable")
        //     ->orWhere('type', 'cr');
        // })
        // ->whereDate('month', '<',$startMonth)
        // ->sum('amount');
        
        // $ca_debits_dr_prev_payable = \App\Model\Accounts\Company_Account::where("rider_id",$rider_id)
        // ->where(function($q) {
        //     $q->where('type', "dr")
        //     ->orWhere('type', 'pl');
        // })
        // ->whereDate('month', '<',$startMonth)
        // ->sum('amount');
        // $closing_balance_prev = round($ca_debits_cr_prev_payable - $ca_debits_dr_prev_payable,2);
        // //ends prev payables

        $ca_payable=Company_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->whereYear("month",$onlyYear)
        ->where("type","dr")
        ->sum('amount');


        $ca_cr=Company_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$onlyMonth)
        ->whereYear("month",$onlyYear)
        ->where(function($q) {
            $q->where('type', "dr_receivable")
            ->orWhere('type', 'cr');
        })
        ->sum('amount');  
        // if($closing_balance_prev < 0){ //deduct
        //     $ca_payable += abs($closing_balance_prev);
        // }
        // else {
        //     // add
        //     $ca_cr += abs($closing_balance_prev);
        // }

        $profit = round($ca_cr-$ca_payable,2);
        // return response()->json([
        //     'a'=>$ca_debits_cr_prev_payable,
        //     'b'=>$ca_debits_dr_prev_payable,
        //     'c'=>$closing_balance_prev,
        //     'd'=>$ca_payable,
        //     'e'=>$ca_cr,
        // ]);
        return $profit;
    }
    public static function calculate_bills($month,$rider_id){
        $startMonth = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
        $month = Carbon::parse($month)->format('Y-m-d');
        $onlyMonth = Carbon::parse($month)->format('m');
        $onlyYear = Carbon::parse($month)->format('Y');
        $rider = Rider::find($rider_id);

        //prev payables
        $bike_rent=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->where("source","Bike Rent")
        ->sum('amount');

        $dicipline_fine=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->where(function($q) {
            $q->where("source","Discipline Fine")
              ->orWhereNotNull("kingrider_fine_id"); 
        })
        ->sum('amount');

        $total_salik=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
         ->whereYear('month',$onlyYear)
        ->where("source","Salik")
        ->where("type","dr")
        ->sum('amount');
        $salik_extra=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
         ->whereYear('month',$onlyYear)
        ->where("source","Salik Extra")
        ->sum('amount');
        $salik=$total_salik-$salik_extra;

        $total_sim_bill=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
         ->whereYear('month',$onlyYear)
        ->where("source","Sim Transaction")
        ->sum('amount');
        $sim_extra_useage=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
         ->whereYear('month',$onlyYear)
        ->where("source","Sim extra usage")
        ->sum('amount');
        $sim=$total_sim_bill-$sim_extra_useage;

        $fuel_cash_amt=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        // ->whereNotNull("fuel_expense_id")
        ->where("source","fuel_expense_cash")
        ->sum('amount');

        $fuel_vip_amt=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        // ->whereNotNull("fuel_expense_id")
        ->where("source","fuel_expense_vip")
        ->sum('amount');

        $total_expense=$bike_rent+$salik+$sim+$fuel_cash_amt+$fuel_vip_amt;

        $fuel_single_cash=[];
        $fuel_single_vip=[];


        //returns Builder
        $fuel_both=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear)
        ->whereNotNull("fuel_expense_id");
        if(isset($fuel_both)){
            $fuel_single_cash=$fuel_both->where("source","fuel_expense_cash")->get();
            $fuel_single_vip=$fuel_both->where("source","fuel_expense_vip")->get();

            #appending export data id
            foreach ($fuel_single_cash as $key => $value) {
                $export_data=Export_data::where("source_id",$value->fuel_expense_id)->where("source","fuel_expense_cash")->get()->first();
                if (isset($export_data)) {
                    $fuel_single_cash[$key]['export_key']='FC'.$export_data->id;
                    $fuel_single_cash[$key]['export_id']=$export_data->id;
                }
            }
            foreach ($fuel_single_vip as $key => $value) {
                $export_data=Export_data::where("source_id",$value->fuel_expense_id)->where("source","fuel_expense_vip")->get()->first();
                if (isset($export_data)) {
                    $fuel_single_cash[$key]['export_key']='FE'.$export_data->id;
                    $fuel_single_cash[$key]['export_id']=$export_data->id;
                }
            }
        }

        return array(
            'bike_rent'=>$bike_rent,
            'dicipline_fine'=>$dicipline_fine,
            'salik'=>$salik,
            'salik_extra'=>$salik_extra,
            'sim'=>$sim,
            'sim_extra'=>$sim_extra_useage,
            'fuel_cash_amt'=>$fuel_cash_amt,
            'fuel_vip_amt'=>$fuel_vip_amt,
            'total'=>$total_expense,

            'fuel_cash'=>$fuel_single_cash,
            'fuel_vip'=>$fuel_single_vip,
            'fuel_both'=>$fuel_both,
        );
    }
    public function zomato_salary_sheet_export($client_id){
        $client=Client::find($client_id);
        return view('admin.accounts.Income.zomato_salary_sheet', compact('client'));
    }
    public function all_clients_salary_sheet_export(){
        $client=Client::all(); 
        return view('admin.accounts.Income.all_clients_salary_sheet', compact('client'));
    }
    public function salary_slip(){
        return view('salary_slip_month');
    }
    public function bike_account(){
        $bikes =bike::all();
        return view('admin.accounts.Bike-Debit.bike_accounts',compact('bikes'));
    }
    
    public function assign_client_rider_id($p_id,$feid,$rider_id){
        $ca=Company_Account::where("income_zomato_id",$p_id)->get();
        foreach ($ca as $value) {
            $value->rider_id=$rider_id;
            $value->update();
        }
        $ra=Rider_Account::where("income_zomato_id",$p_id)->get();
        foreach ($ra as $value) {
            $value->rider_id=$rider_id;
            $value->update();
        }
        $income=Income_Zomato::where("p_id",$p_id)->get()->first();
        $income->rider_id=$rider_id;
        $income->update();
    }
    public function view_riders_payouts_days(){
        return view('admin.zomato.riders_payouts_by_days');
    }
    public function resync_attendance_data(Request $r,$rider_id,$month)
    {
        $_onlyMonth=Carbon::parse($month)->format("m");
        $_onlyYear=Carbon::parse($month)->format("Y");

        $ca=Company_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$_onlyMonth)
        ->whereYear("month",$_onlyYear)
        ->whereNotNull("kingrider_fine_id")
        ->delete();

        $ra=Rider_Account::where("rider_id",$rider_id)
        ->whereMonth("month",$_onlyMonth)
        ->whereYear("month",$_onlyYear)
        ->whereNotNull("kingrider_fine_id")
        ->delete();
        
        $zincome=Income_zomato::where("rider_id",$rider_id)
        ->whereMonth("date",$_onlyMonth)
        ->whereYear("date",$_onlyYear)
        ->get()
        ->first();
        if (isset($zincome)) {
            $zincome->approve_absents=null;
            $zincome->save();
            $rpbds=Riders_Payouts_By_Days::where("zomato_income_id",$zincome->id)->get();
            foreach ($rpbds as $rpbd) {
                $rpbd->absent_status=null;
                $rpbd->absent_fine_id=null;
                $rpbd->save();
            }
        }
      
        $time_sheet=$r->time_sheet;
        $zi=$r->zomato_income;

        foreach ($time_sheet as $item) {
            $zi_timesheet=Riders_Payouts_By_Days::find($item['id']);
            $zi_timesheet->off_days_status=$item['off_days_status'];
            $zi_timesheet->save();
        }
        //updating zi
        $zomato_income=Income_zomato::find($zi['id']);
        $zomato_income->absents_count=$zi['absents_count'];
        $zomato_income->weekly_off=$zi['weekly_off'];
        $zomato_income->extra_day=$zi['extra_day'];
        $zomato_income->working_days=$zi['working_days'];
        $zomato_income->calculated_hours=round($zi['calculated_hours'],2);
        $zomato_income->calculated_trips=round($zi['calculated_trips'],2);
        $zomato_income->off_day=$zi['off_day'];
        $zomato_income->error=null;
        $zomato_income->save();

        return response()->json([
            'status'=>1,
            'data'=>$zomato_income,
            'data2'=>$zi_timesheet
        ]);
    }
    public function import_rider_daysPayouts(Request $r)
    {
        $data = $r->data;
        $zomato_objects=[];
        $zts =Riders_Payouts_By_Days::all(); // r1
        $zi =Income_zomato::all(); // r1
        $delete_data = [];
        $i=0;
        $unique_id=uniqid().'-'.time();

        $is_exception=false;
        $exception_msg='';
        $j=0;

        $time_sheets=[];

        
        foreach ($data as $item_row) {
            $i++;
            
            $date=isset($item_row['date'])?$item_row['date']:null;
            if($date==null){
                //throw exception. No feid found
                $is_exception=true;
                $exception_msg='No DATE found against feid '.$item_row['feid'].' and row '.$i.'. Please recheck the data';
                break;
            }
            $start_of_month = Carbon::parse($date)->startOfMonth()->format('Y-m-d');
            $feid=isset($item_row['feid'])?$item_row['feid']:null;
            $grand_total=isset($item_row['grand_total'])?$item_row['grand_total']:null;
            $login_hours=isset($item_row['login_hours'])?$item_row['login_hours']:null;
            $trips=isset($item_row['orders'])?$item_row['orders']:null;
            $payout_for_login_hours=isset($item_row['payout_for_login_hrs'])?$item_row['payout_for_login_hrs']:null;
            $payout_for_trips=isset($item_row['payout_for_orders'])?$item_row['payout_for_orders']:null;
            $rider_id=isset($item_row['rider_id'])?$item_row['rider_id']:null;

            if($feid==null){
                //throw exception. No feid found
                $is_exception=true;
                $exception_msg='No FEID found against date '.$date.'. Please recheck the data';
                break;
            }
            $zi_found = Arr::first($zi, function ($item_zi, $key) use ($start_of_month, $item_row) {
                return $item_zi->date == $start_of_month && $item_zi->feid==$item_row['feid'];
            });
            if(isset($zi_found)){
                //found the row in Income Zomato table
                $income_zomatoObj = $zi_found;
                $income_zomato_id=$zi_found->id;

                //finding if row exist in this table
                $zts_found = Arr::first($zts, function ($item_zts, $key) use ($date, $item_row) {
                    return $item_zts->date == $date && $item_zts->feid==$item_row['feid'];
                });

                if(isset($zts_found)){
                    $objDelete = [];
                    $objDelete['id']=$zts_found->id; 
                    array_push($delete_data, $objDelete);
                }

                
                $obj = [];
                $obj['date']=$date;
                $obj['feid']=$feid;
                $obj['zomato_income_id']=$income_zomato_id;
                $obj['rider_id']=$rider_id;
                $obj['login_hours']=$login_hours;
                $obj['trips']=$trips;
                $obj['off_days_status']=NULL;
                $obj['payout_for_login_hours']=$payout_for_login_hours;
                $obj['payout_for_trips']=$payout_for_trips;
                $obj['grand_total']=$grand_total;
                $obj['created_at']=Carbon::now();
                $obj['updated_at']=Carbon::now();
                

                //updating time sheet data (weekday, absents etc) on income zomato
                $timeSheetObj = isset($item_row['time_sheet'])?$item_row['time_sheet']:null;
                $timeSheetMutableObj = isset($item_row['time_sheet_mutable'])?$item_row['time_sheet_mutable']:null; // fot getting hours and trips
                if($timeSheetObj!=null){
                    $off_day_status=isset($timeSheetObj['off_day_status'])?$timeSheetObj['off_day_status']:null;
                    $obj['off_days_status']=$off_day_status;
                    // if ($off_day_status=='present') {
                    //     $login_hours=$login_hours>11?11:$login_hours;
                    //     $obj['login_hours']=$login_hours;
                    // }
                    // $obj['trips']=$trips;
                    
                }
                array_push($zomato_objects, $obj);

                $ts_found = Arr::first($time_sheets, function ($item_ts, $key) use ($income_zomatoObj) {
                    return $item_ts['id']==$income_zomatoObj->id;
                });
                if(!isset($ts_found)){
                    $off_day=isset($timeSheetObj['off_day'])?$timeSheetObj['off_day']:null;
                    $absents_count=isset($timeSheetObj['absents_count'])?$timeSheetObj['absents_count']:null;
                    $weekly_off=isset($timeSheetObj['weekly_off'])?$timeSheetObj['weekly_off']:null;
                    $extra_day=isset($timeSheetObj['extra_day'])?$timeSheetObj['extra_day']:null;
                    $working_days=isset($timeSheetObj['working_days'])?$timeSheetObj['working_days']:null;

                    $calculated_hours=isset($timeSheetMutableObj['calculated_hours'])?$timeSheetMutableObj['calculated_hours']:0; //Mutable data
                    $calculated_trips=isset($timeSheetMutableObj['calculated_trips'])?$timeSheetMutableObj['calculated_trips']:0;//Mutable data
                    //error
                    $is_error=isset($timeSheetObj['is_error'])?$timeSheetObj['is_error']:null;
                    $error_code=isset($timeSheetObj['error_code'])?$timeSheetObj['error_code']:null;
                    $error_message=isset($timeSheetObj['error_message'])?$timeSheetObj['error_message']:null;
                    $error_type=isset($timeSheetObj['error_type'])?$timeSheetObj['error_type']:null;

                

                    $obj = [];
                    $obj['id']=$income_zomato_id;
                    $obj['off_day']=$off_day;
                    $obj['absents_count']=$absents_count;
                    $obj['weekly_off']=$weekly_off;
                    $obj['extra_day']=$extra_day;
                    $obj['working_days']=$working_days;

                    $workable_days = $working_days * 11;
                    $absent_hours = $absents_count * 11;

                    $less_time = $workable_days - $calculated_hours;

                    $payable_hours = 286 - $absent_hours - $less_time;

                    $obj['actual_hours']=$payable_hours;
                    $obj['calculated_hours']=$calculated_hours;

                    $obj['calculated_trips']=$calculated_trips;
                    if($is_error==true){
                        $err=[];
                        $err['error_code']=$error_code;
                        $err['error_message']=$error_message;
                        $err['error_type']=$error_type;
                        $obj['error']=json_encode($err);
                    }
                    array_push($time_sheets, $obj); 
                }

                
            }
            else {
                //throw exception. No rows found
                $is_exception=true;
                $exception_msg='No data found in income_zomatos table against FEID '.$feid.' and month of '.$date;
                break;
            }
            

        }
        if($is_exception){
            return response()->json([
                'status'=>0,
                'message'=>$exception_msg
            ]);
        }
        $delete_data = DB::table('riders__payouts__by__days')
                    ->whereIn('id', $delete_data)
                    ->delete();
        DB::table('riders__payouts__by__days')->insert($zomato_objects);
        $time_sheets_update=Batch::update(new Income_zomato, $time_sheets, 'id'); //r3
        return response()->json([
            'status'=>1,
            'data'=>$zomato_objects,
            'deletedata_count'=>$delete_data,
            'time_sheet'=>$time_sheets,
            'count'=>$i
        ]);
    }
    public function hours_trips_details($month,$rider_id){
        $_only_month=Carbon::parse($month)->format("m");
        $data=Income_Zomato::with('Time_sheet')->where('rider_id',$rider_id)
        ->whereMonth("date",$_only_month)
        ->get()
        ->first();
        return response()->json([
            'month'=>$_only_month,
            'rider_id'=>$rider_id,
            'data'=>$data,
        ]);
    }
    public function weekly_days_off($month,$rider_id,$days){
        $_days =[];
        if ($days=="Monday") {
            $da=Carbon::MONDAY;
        }
        if ($days=="Tuesday") {
            $da=Carbon::TUESDAY;
        }
        if ($days=="Wednesday") {
            $da=Carbon::WEDNESDAY;
        }
        if ($days=="Thursday") {
            $da=Carbon::THURSDAY;
        }
        if ($days=="Friday") {
            $da=Carbon::FRIDAY;
        }
        if ($days=="Saturday") {
            $da=Carbon::SATURDAY;
        }
        if ($days=="Sunday") {
            $da=Carbon::SUNDAY;
        }
        $startOfMonth=Carbon::parse($month)->startOfMonth()->format("Y-m-00");
        $endOfMonth=Carbon::parse($month)->endOfMonth()->format("Y-m-d");
        $startDate = Carbon::parse($startOfMonth)->next($da); 
        $endDate = Carbon::parse($endOfMonth);
        
        for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
            $_days[]= $date->format('Y-m-d');
        }
        return response()->json([
            'a'=>$_days,
            'days'=>$days,
        ]);
    }
    public function weekly_days_sync_data($month,$rider_id,$weekly_off_day,$absent_days,$weekly_off,$extra_day){
        $_only_month=Carbon::parse($month)->format("m");
        $_total_days_in_month=Carbon::parse($month)->daysInMonth;
        $data=Income_Zomato::where('rider_id',$rider_id)
        ->whereMonth("date",$_only_month)
        ->get()
        ->first();
        if (isset($data)) {
            $data->off_day=$weekly_off_day;
            $data->absents_count=$absent_days-$weekly_off;
            $data->weekly_off=$weekly_off+$extra_day;
            $data->extra_day=$extra_day;
            $data->working_days=$_total_days_in_month-$absent_days-$extra_day;
            $data->save();
        }
        
        return response()->json([
            'data'=>$data,
        ]);
    }
    public function getPreviousMonthIncomeZomato($month){
        $only_month=Carbon::parse($month)->subMonth()->format("m");
        $income_zomato=Income_Zomato::whereMonth("date",$only_month)
        ->get();
       
        return response()->json([
            'income_zomato'=>$income_zomato,
        ]);
    }
    public function edit_company_account(Request $r){
       $statement_id=$r->statement_id;
       $desc=$r->desc;
       $amount = $r->amount;
       $company_statement = Company_account::find($statement_id);
       $company_statement->desc=$desc;
       $company_statement->amount=$amount;
       $company_statement->save();
        return response()->json([
            'status'=>1
        ]);
    }

    public static function update_row_company_account(Request $r,$statement_id,$status,$admin_id){
        // return response()->json([
        //     'r'=>$r->all(), 
        // ]);
        $rider_id=$r->rider_id;
        $_month=$r->bk_month;
        $_year=$r->bk_year;
        if ($status=="accept") {
            $amount=$r->amount;
            $rider_id_update=$r->rider_id_update;
            $month=carbon::parse($r->month_update)->startOfMonth()->format("Y-m-d");
            $given_date=carbon::parse($r->given_date)->format("Y-m-d");
            $statement = Company_Account::find($r->statement_id);
            if(!isset($statement)){
                #generate error
                return response()->json([
                    'status'=>0,
                    'msg'=>'No row found'
                ]);
            }
            if($r->source_id==''){
                #means there is not linking between tables, so we just edit this row
                $statement->amount=$amount;
                $statement->rider_id=$rider_id_update;
                $statement->month=$month;
                $statement->given_date=$given_date;
                $statement->desc=$r->desc;
                $statement->save();

                $login_user=Auth::user();
                if($login_user->type!='su'){ #filter admin

                    if(isset($r->dl_notifid)){
                        #means it is coming from daily ledger, we change the way we generate the notificaiton
                        $notification=Notification::find($r->dl_notifid);
                        if(isset($notification)){
                            if($notification->action_status=='pending_new'){
                                #we need to update the notification
                                $notification->feed=json_encode($statement);
                                $notification->update();
                            }
                            elseif($notification->action_status=='rejected'){
                                #we need to create new notification
                                if(isset($export_data)){
                                    $notification_new=new Notification;
                                    $notification_new->date_time=Carbon::now()->format("Y-m-d");
                                    $notification_new->employee_id=$notification->employee_id;
                                    $notification_new->desc=$notification->desc;
                                    $notification_new->action=$notification->action;
                                    $notification_new->source_id=$export_data->id;
                                    $notification_new->source_type=$notification->source_type;
                                    $notification_new->action_status='pending';
                                    $notification_new->feed=json_encode($export_data);
                                    $notification_new->save();
                                }
                            }
                            #add accept case here if needed
                        }
                        
                    }
                    else {
                        $notification=new Notification;
                        $notification->date_time=Carbon::now()->format("Y-m-d");
                        $notification->employee_id=$admin_id;
                        $notification->desc=$login_user->name." accepted your edit request";
                        $notification->action="";
                        $notification->source_id=$r->source_id;
                        $notification->source_type=$r->source_key;
                        $notification->save(); 
                    }

                    
                }
                return response()->json([
                    'status'=>1
                ]);
            }

            #just updating the current description
            $statement->desc=$r->desc;
            $statement->update();

            $_source=$statement->source; 
            $tt_model = ''; 
            $tt_tomatch='id';
            $tt_amountKey='amount';
            $tt_rider_idKey='rider_id';
            $tt_monthKey='month';
            $src = $r->source_key;
            if ($_source=="Salik Extra" || $_source=="Salik") {
                $tt_model = 'App\Model\Rider\Trip_Detail';
                $tt_tomatch='transaction_id';
                $tt_amountKey='amount_aed';
                $tt_rider_idKey='rider_id';
                $tt_monthKey='';
            }
            if ($_source=="fuel_expense_vip") {
                $tt_model = 'App\Model\Accounts\Fuel_Expense';
            }
            if ($_source=="fuel_expense_cash") { 
                $tt_model = 'App\Model\Accounts\Fuel_Expense';
            }
            if ($_source=="Sim Transaction" || $_source=="Sim extra usage") {
                $tt_model = 'App\Model\Sim\Sim_Transaction';
                $tt_amountKey='bill_amount';
                $tt_rider_idKey='';
                $tt_monthKey='month_year';
            }
            if ($src=="bike_fine") { 
                $tt_model = 'App\Model\Accounts\Bike_Fine';
            }
            
            
            
            #finding and updating third table
            $third_table=null;
            if($tt_model!=''){ # we need to check if third table exist
                $third_table=$tt_model::where($tt_tomatch,$r->source_id)->get()->first();
                if(isset($third_table)){
                    #update data
                    if($tt_amountKey!='')$third_table[$tt_amountKey]=$amount;
                    if($tt_rider_idKey!='')$third_table[$tt_rider_idKey]=$rider_id_update;
                    if($tt_monthKey!='')$third_table[$tt_monthKey]=$month;

                    $third_table->save();
                }
            }
            
            #updating Company Account
            $ca =Company_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$_month)
            ->whereYear("month",$_year)
            ->where($src,$r->source_id)
            ->get();
            $orginal_amount = null;
            foreach ($ca as $key=>$company_statement) {
                if($company_statement->id==$statement->id){
                    #this is original
                    $orginal_amount=$company_statement->amount;
                }
            }
            foreach ($ca as $key=>$company_statement) {
                #we need to check if this iteration is original by comparing this id to the id we received for editing
                if($company_statement->id==$statement->id){
                    #this is original
                    $orginal_amount=$company_statement->amount;
                    $updated_amount=$amount;
                }
                else {
                    # is this likelihood row, we need to add the difference to this amount
                    if($orginal_amount==null){
                        #seems like original row couldn't found, we raised an error -and breaks the loop
                        break;
                    }
                    $likelihood_amount = $amount - $orginal_amount;
                    $updated_amount=$company_statement->amount+$likelihood_amount;
                }
                if($updated_amount<0)$updated_amount=0;
                $company_statement->amount=$updated_amount;
                $company_statement->rider_id=$rider_id_update;
                $company_statement->month=$month;
                $company_statement->given_date=$given_date;

                #we need to get rider account linked with this row
                $alternate_type=$company_statement->type=='dr'?'cr':'dr'; # get the type of rider account, if company account has dr, then rider acc must has cr
                
                $rider_statement =Rider_Account::where("rider_id",$rider_id)
                ->whereMonth("month",$_month)
                ->whereYear("month",$_year)
                ->where($src,$r->source_id)
                ->where('source', $company_statement->source)
                // ->where('type', $alternate_type) 
                ->get()
                ->first();
                if(isset($rider_statement))
                {
                    #row found in rider account against this row -we need to update that too
                    $rider_statement->amount=$company_statement->amount;
                    $rider_statement->rider_id=$company_statement->rider_id;
                    $rider_statement->month=$company_statement->month;
                    $rider_statement->given_date=$company_statement->given_date;
                    $rider_statement->save();

                }

                $company_statement->save();
                $ca[$key]['ra']=$rider_statement;
            }

            #updating export data
            $_source=$_source=='Sim extra usage'?'Sim Transaction':$_source; #hack for when we have source=Sim extra usage
            $_source=$_source=='Bike Fine Paid'?'Bike Fine':$_source; #hack for when we have source=Bike Fine Paid
            $export_data=Export_data::whereMonth('month',$_month)
            ->whereYear("month",$_year)
            ->where('source',$_source)
            ->where('source_id',$r->source_id)
            ->get()
            ->first();
            if(isset($export_data)){
                #update data
                $export_data->amount=$amount;
                $export_data->rider_id=$rider_id_update;
                $export_data->month=$month;
                $export_data->given_date=$given_date;
                $export_data->save();
            }

            $login_user=Auth::user();
            if($login_user->type!='su'){ #filter admin
                if(isset($r->dl_notifid)){
                    #means it is coming from daily ledger, we change the way we generate the notificaiton
                    $notification=Notification::find($r->dl_notifid);
                    if(isset($notification)){
                        if($notification->action_status=='pending_new'){
                            #we need to update the notification
                            if(isset($export_data)){
                                $notification->feed=json_encode($export_data);
                                $notification->update();
                            }
                        }
                        elseif($notification->action_status=='rejected'){
                            #we need to create new notification
                            if(isset($export_data)){
                                $notification_new=new Notification;
                                $notification_new->date_time=Carbon::now()->format("Y-m-d");
                                $notification_new->employee_id=$notification->employee_id;
                                $notification_new->desc=$notification->desc;
                                $notification_new->action=$notification->action;
                                $notification_new->source_id=$export_data->id;
                                $notification_new->source_type=$notification->source_type;
                                $notification_new->action_status='pending';
                                $notification_new->feed=json_encode($export_data);
                                $notification_new->save();
                            }
                        }
                        #add accept case here if needed
                    }
                    
                }
                else {
                    $notification=new Notification;
                    $notification->date_time=Carbon::now()->format("Y-m-d");
                    $notification->employee_id=$admin_id;
                    $notification->desc=$login_user->name." accepted your edit request";
                    $notification->action="";
                    $notification->source_id=$r->source_id;
                    $notification->source_type=$r->source_key;
                    $notification->save();
                }
            }
            return response()->json([
                'status'=>1,
                'ca'=>$ca,
                // 'ra'=>$ra,
                'export_data'=>$export_data,
                'third_table'=>$third_table,
            ]);
        }
        else {
            # request rejected
            $login_user=Auth::user();
            $notification=new Notification;
            $notification->date_time=Carbon::now()->format("Y-m-d");
            $notification->employee_id=$admin_id;
            $notification->desc=$login_user->name." rejected your edit request";
            $notification->action="";
            $notification->source_id=$r->source_id;
            $notification->source_type=$r->source_key;
            $notification->save();
        }
    }

    public static function update_row_rider_account(Request $r,$statement_id,$status,$admin_id){
        // return response()->json([
        //     'r'=>$r->all(), 
        // ]);
        $rider_id=$r->rider_id;
        $_month=$r->bk_month;
        $_year=$r->bk_year;
        if ($status=="accept") {
            $amount=$r->amount;
            $rider_id_update=$r->rider_id_update;
            $month=carbon::parse($r->month_update)->startOfMonth()->format("Y-m-d");
            $statement = Rider_Account::find($r->statement_id);
            if(!isset($statement)){
                #generate error
                return response()->json([
                    'status'=>0,
                    'msg'=>'No row found'
                ]);
            }
            if($r->source_id==''){
                #means there is not linking between tables, so we just edit this row
                $statement->amount=$amount;
                $statement->rider_id=$rider_id_update;
                $statement->month=$month;
                $statement->desc=$r->desc;
                $statement->save();

                $login_user=Auth::user();
                if($login_user->type!='su'){ #filter admin
                    if(isset($r->dl_notifid)){
                        #means it is coming from daily ledger, we change the way we generate the notificaiton
                        $notification=Notification::find($r->dl_notifid);
                        if(isset($notification)){
                            if($notification->action_status=='pending_new'){
                                #we need to update the notification
                                $notification->feed=json_encode($statement);
                                $notification->update();
                            }
                            elseif($notification->action_status=='rejected'){
                                #we need to create new notification
                                if(isset($export_data)){
                                    $notification_new=new Notification;
                                    $notification_new->date_time=Carbon::now()->format("Y-m-d");
                                    $notification_new->employee_id=$notification->employee_id;
                                    $notification_new->desc=$notification->desc;
                                    $notification_new->action=$notification->action;
                                    $notification_new->source_id=$export_data->id;
                                    $notification_new->source_type=$notification->source_type;
                                    $notification_new->action_status='pending';
                                    $notification_new->feed=json_encode($export_data);
                                    $notification_new->save();
                                }
                            }
                            #add accept case here if needed
                        }
                        
                    }
                    else {
                        $notification=new Notification;
                        $notification->date_time=Carbon::now()->format("Y-m-d");
                        $notification->employee_id=$admin_id;
                        $notification->desc=$login_user->name." accepted your edit request";
                        $notification->action="";
                        $notification->source_id=$r->source_id;
                        $notification->source_type=$r->source_key;
                        $notification->save();
                    }
                }
                return response()->json([
                    'status'=>1
                ]);
            }

            #just updating the current description
            $statement->desc=$r->desc;
            $statement->update();

            $_source=$statement->source; 
            $tt_model = ''; 
            $tt_tomatch='id';
            $tt_amountKey='amount';
            $tt_rider_idKey='rider_id';
            $tt_monthKey='month';
            $src = $r->source_key;
            if ($_source=="Salik Extra" || $_source=="Salik") {
                $tt_model = 'App\Model\Rider\Trip_Detail';
                $tt_tomatch='transaction_id';
                $tt_amountKey='amount_aed';
                $tt_rider_idKey='rider_id';
                $tt_monthKey='';
            }
            if ($_source=="fuel_expense_vip") {
                $tt_model = 'App\Model\Accounts\Fuel_Expense';
            }
            if ($_source=="fuel_expense_cash") { 
                $tt_model = 'App\Model\Accounts\Fuel_Expense';
            }
            if ($_source=="Sim Transaction" || $_source=="Sim extra usage") {
                $tt_model = 'App\Model\Sim\Sim_Transaction';
                $tt_amountKey='bill_amount';
                $tt_rider_idKey='';
                $tt_monthKey='month_year';
            }
            if ($src=="bike_fine") { 
                $tt_model = 'App\Model\Accounts\Bike_Fine';
            }
            if ($src=="advance_return_id") { 
                $tt_model = 'App\Model\Accounts\AdvanceReturn';
            }
            if ($src=="Mobile Installment") { 
                $tt_model = 'App\Model\Mobile\Mobile_Transaction';
            }
            
            #finding and updating third table
            $third_table=null;
            if($tt_model!=''){ # we need to check if third table exist
                $third_table=$tt_model::where($tt_tomatch,$r->source_id)->get()->first();
                if(isset($third_table)){
                    #update data
                    if($tt_amountKey!='')$third_table[$tt_amountKey]=$amount;
                    if($tt_rider_idKey!='')$third_table[$tt_rider_idKey]=$rider_id_update;
                    if($tt_monthKey!='')$third_table[$tt_monthKey]=$month;

                    $third_table->save();
                }
            }
            
            #updating Company Account
            $ca =Company_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$_month)
            ->whereYear("month",$_year)
            ->where($src,$r->source_id)
            ->get();
            $orginal_amount = null;
            foreach ($ca as $key=>$company_statement) {
                if($key==0){
                    #this is original
                    $orginal_amount=$company_statement->amount;
                }
            }
            foreach ($ca as $key=>$company_statement) {
                #we need to check if this iteration is original by comparing this id to the id we received for editing
                if($key==0){
                    #this is original
                    $orginal_amount=$company_statement->amount;
                    $updated_amount=$amount;
                }
                else {
                    # is this likelihood row, we need to add the difference to this amount
                    if($orginal_amount==null){
                        #seems like original row couldn't found, we raised an error -and breaks the loop
                        break;
                    }
                    $likelihood_amount = $amount - $orginal_amount;
                    $updated_amount=$company_statement->amount+$likelihood_amount;
                }
                if($updated_amount<0)$updated_amount=0;
                $company_statement->amount=$updated_amount;
                $company_statement->rider_id=$rider_id_update;
                $company_statement->month=$month;

                #we need to get rider account linked with this row
                $alternate_type=$company_statement->type=='dr'?'cr':'dr'; # get the type of rider account, if company account has dr, then rider acc must has cr
                
                $rider_statement =Rider_Account::where("rider_id",$rider_id)
                ->whereMonth("month",$_month)
                ->whereYear("month",$_year)
                ->where($src,$r->source_id)
                ->where('source', $company_statement->source)
                // ->where('type', $alternate_type) 
                ->get()
                ->first();
                if(isset($rider_statement))
                {
                    #row found in rider account against this row -we need to update that too
                    $rider_statement->amount=$company_statement->amount;
                    $rider_statement->rider_id=$company_statement->rider_id;
                    $rider_statement->month=$company_statement->month;
                    $rider_statement->save();

                }

                $company_statement->save();
                $ca[$key]['ra']=$rider_statement;
            }

            #need to check if this row is not linked from company acc
            if(count($ca)<=0){
                #no record found in company account, we need to reverse the algorithm
                $ca =Rider_Account::where("rider_id",$rider_id)
                ->whereMonth("month",$_month)
                ->whereYear("month",$_year)
                ->where($src,$r->source_id)
                ->get();
                $orginal_amount = null;
                foreach ($ca as $key=>$rider_statement) {
                    $rider_statement->amount=$amount;
                    $rider_statement->rider_id=$rider_id_update;
                    $rider_statement->month=$month;
                    $rider_statement->save();
                }
            }

            #updating export data
            $_source=$_source=='Sim extra usage'?'Sim Transaction':$_source; #hack for when we have source=Sim extra usage
            $_source=$_source=='Bike Fine Paid'?'Bike Fine':$_source; #hack for when we have source=Bike Fine Paid
            $export_data=Export_data::whereMonth('month',$_month)
            ->whereYear("month",$_year)
            ->where('source',$_source)
            ->where('source_id',$r->source_id)
            ->get()
            ->first();
            if(isset($export_data)){
                #update data
                $export_data->amount=$amount;
                $export_data->rider_id=$rider_id_update;
                $export_data->month=$month;
                $export_data->save();
            }

            $login_user=Auth::user();
            if($login_user->type!='su'){ #filter admin
                if(isset($r->dl_notifid)){
                    #means it is coming from daily ledger, we change the way we generate the notificaiton
                    $notification=Notification::find($r->dl_notifid);
                    if(isset($notification)){
                        if($notification->action_status=='pending_new' || $notification->action_status=='pending'){
                            #we need to update the notification
                            if(isset($export_data)){
                                $notification->feed=json_encode($export_data);
                                $notification->update();
                            }
                        }
                        elseif($notification->action_status=='rejected'){
                            #we need to create new notification
                            if(isset($export_data)){
                                $notification_new=new Notification;
                                $notification_new->date_time=Carbon::now()->format("Y-m-d");
                                $notification_new->employee_id=$notification->employee_id;
                                $notification_new->desc=$notification->desc;
                                $notification_new->action=$notification->action;
                                $notification_new->source_id=$export_data->id;
                                $notification_new->source_type=$notification->source_type;
                                $notification_new->action_status='pending';
                                $notification_new->feed=json_encode($export_data);
                                $notification_new->save();
                            }
                        }
                        #add accept case here if needed
                    }
                    
                }
                else {
                    $notification=new Notification;
                    $notification->date_time=Carbon::now()->format("Y-m-d");
                    $notification->employee_id=$admin_id;
                    $notification->desc=$login_user->name." accepted your edit request";
                    $notification->action="";
                    $notification->source_id=$r->source_id;
                    $notification->source_type=$r->source_key;
                    $notification->save();
                }
            }
            return response()->json([
                'status'=>1,
                'ca'=>$ca,
                // 'ra'=>$ra,

                '_source'=>$tt_model,
                'export_data'=>$export_data,
                'third_table'=>$third_table,
            ]);
        }
        else {
            # request rejected
            $login_user=Auth::user();
            $notification=new Notification;
            $notification->date_time=Carbon::now()->format("Y-m-d");
            $notification->employee_id=$admin_id;
            $notification->desc=$login_user->name." rejected your edit request";
            $notification->action="";
            $notification->source_id=$r->source_id;
            $notification->source_type=$r->source_key;
            $notification->save();
        }
    }
    public static function delete_account_rows(Request $r,$id,$status,$admin_id,$statement_type){
        // return response()->json([
        //     'r'=>$r->all(),
        //     'id'=>$id,
        //     'status'=>$status,
        //     'admin_id'=>$admin_id,
        // ]);
        
        if ($status=="accept") {
            $_month=$r->month;
            $_year=$r->year;
            $rider_id=$r->rider_id;
            $feed=[];
            
            if($statement_type=='rider__accounts') $statement = Rider_Account::find($r->id);
            else $statement = Company_Account::find($r->id);
            
            if(!isset($statement)){
                # it seems like row has been already deleted -generate error
                return response()->json([
                    'status'=>0,
                    'msg'=>'No row found'
                ]);
            }
            if($r->source_id==''){
                #means there is not linking between tables, so we just delete this row
                $obj=[];
                $obj['model']=get_class($statement);
                $obj['data']=json_encode($statement);
                array_push($feed,$obj);
                $statement->delete();

                $del_data=new Deleted_data;
                $del_data->date=Carbon::now()->format('Y-m-d');
                $del_data->deleted_by=$admin_id;
                $del_data->feed=json_encode($feed);
                $del_data->save();

                $login_user=Auth::user();
                if($login_user->type!='su'){
                    $notification=new Notification;
                    $notification->date_time=Carbon::now()->format("Y-m-d");
                    $notification->employee_id=$admin_id;
                    $notification->desc=$login_user->name." accepted your deleted request";
                    $notification->action="";
                    $notification->source_id=$r->source_id;
                    $notification->source_type=$r->source_key;
                    $notification->save();
                }
                return response()->json([
                    'status'=>1,
                    'del_data'=>$del_data
                ]);
            }

            $_source=$statement->source; 
            $tt_model = ''; 
            $tt_tomatch='id';
            $src = $r->source_key;
            if ($_source=="Salik Extra" || $_source=="Salik") {
                $tt_model = 'App\Model\Rider\Trip_Detail';
                $tt_tomatch='transaction_id';
            }
            if ($_source=="fuel_expense_vip") {
                $tt_model = 'App\Model\Accounts\Fuel_Expense';
            }
            if ($_source=="fuel_expense_cash") { 
                $tt_model = 'App\Model\Accounts\Fuel_Expense';
            }
            if ($_source=="Sim Transaction" || $_source=="Sim extra usage") {
                $tt_model = 'App\Model\Sim\Sim_Transaction';
            }
            if ($src=="bike_fine") { 
                $tt_model = 'App\Model\Accounts\Bike_Fine';
            }
            if ($src=="advance_return_id") { 
                $tt_model = 'App\Model\Accounts\AdvanceReturn';
            }
            if ($src=="Mobile Installment") { 
                $tt_model = 'App\Model\Mobile\Mobile_Transaction';
            }
            
            
            
            #finding and updating third table
            $third_table=null;
            if($tt_model!=''){ # we need to check if third table exist
                $third_table=$tt_model::where($tt_tomatch,$r->source_id)->get()->first();
                if(isset($third_table)){
                    #delete data
                    $obj=[];
                    $obj['model']=get_class($third_table);
                    $obj['data']=json_encode($third_table);
                    array_push($feed,$obj);
                    $third_table->delete();
                }
            }

            #an array to get ra
            $ra=[];
            
            #updating Company Account
            $ca =Company_Account::where("rider_id",$rider_id)
            ->whereMonth("month",$_month)
            ->whereYear("month",$_year)
            ->where($src,$r->source_id)
            ->get();
            foreach ($ca as $key=>$company_statement) {
                #we need to check if this iteration is original by comparing this id to the id we received for deleting
                

                #we need to get rider account linked with this row
                $alternate_type=$company_statement->type=='dr'?'cr':'dr'; # get the type of rider account, if company account has dr, then rider acc must has cr
                
                $rider_statement =Rider_Account::where("rider_id",$rider_id)
                ->whereMonth("month",$_month)
                ->whereYear("month",$_year)
                ->where($src,$r->source_id)
                ->where('source', $company_statement->source)
                // ->where('type', $alternate_type) 
                ->get()
                ->first();
                if(isset($rider_statement))
                {
                    #row found in rider account against this row -we need to delete that too
                    $obj=[];
                    $obj['model']=get_class($rider_statement);
                    $obj['data']=json_encode($rider_statement);
                    array_push($feed,$obj);
                    // array_push($ra,$rider_statement);
                    // $ca[$key]['ra']=$rider_statement;
                    $rider_statement->delete();

                }
                $obj=[];
                $obj['model']=get_class($company_statement);
                $obj['data']=json_encode($company_statement);
                array_push($feed,$obj);
                $company_statement->delete();
            }

            #need to check if this row is not linked from company acc
            if(count($ca)<=0){
                #no record found in company account, we need to reverse the algorithm
                $ca =Rider_Account::where("rider_id",$rider_id)
                ->whereMonth("month",$_month)
                ->whereYear("month",$_year)
                ->where($src,$r->source_id)
                ->get();
                foreach ($ca as $key=>$rider_statement) {
                    $obj=[];
                    $obj['model']=get_class($rider_statement);
                    $obj['data']=json_encode($rider_statement);
                    array_push($feed,$obj);
                    $rider_statement->delete();
                }
            }

            #updating export data
            $_source=$_source=='Sim extra usage'?'Sim Transaction':$_source; #hack for when we have source=Sim extra usage
            $_source=$_source=='Bike Fine Paid'?'Bike Fine':$_source; #hack for when we have source=Bike Fine Paid
            $export_data=Export_data::whereMonth('month',$_month)
            ->whereYear("month",$_year)
            ->where('source',$_source)
            ->where('source_id',$r->source_id)
            ->get()
            ->first();
            if(isset($export_data)){
                #delete data
                $obj=[];
                $obj['model']=get_class($export_data);
                $obj['data']=json_encode($export_data);
                array_push($feed,$obj);
                $export_data->delete();
            }
            
            #saving what we deleted to this table so we can retieve it later
            $del_data=new Deleted_data;
            $del_data->date=Carbon::now()->format('Y-m-d');
            $del_data->deleted_by=$admin_id;
            $del_data->feed=json_encode($feed);
            $del_data->save();

            $login_user=Auth::user();
            if($login_user->type!='su'){ #filter admin
                $notification=new Notification;
                $notification->date_time=Carbon::now()->format("Y-m-d");
                $notification->employee_id=$admin_id;
                $notification->desc=$login_user->name." accepted your deleted request";
                $notification->action="";
                $notification->source_id=$r->source_id;
                $notification->source_type=$r->source_key;
                $notification->save();
            }
            
            return response()->json([
                'status'=>1,
                'ca'=>$ca,
                // 'ra'=>$ra,
                'del_data'=>$del_data,
                'export_data'=>$export_data,
                'third_table'=>$third_table,
            ]);
        }
        elseif ($status=="reject") {
            $emp_name=Auth::user();
            $notification=new Notification;
            $notification->date_time=Carbon::now()->format("Y-m-d");
            $notification->employee_id=$admin_id;
            $notification->desc=$emp_name->name." rejected your deleted request";
            $notification->action="";
            $notification->source_id=$r->source_id;
            $notification->source_type=$r->source_key;
            $notification->save();
        }
        // if (isset($model_class)) {
        //     $alter=$model_class::find($model_id);
        //     if (isset($alter)) {
        //         $alter->delete();
        //     }
           
        // }
        // $CA=Company_Account::where("rider_id",$rider_id)
        // ->whereMonth("month",$month)
        // ->where($string,$model_id)
        // ->get();
        // foreach ($CA as $ca) {
        //     $ca->delete();
        // }
        // $RA=Rider_Account::where("rider_id",$rider_id)
        // ->whereMonth("month",$month)
        // ->where($string,$model_id)
        // ->get();
        // foreach ($RA as $ra) {
        //     $ra->delete();
        // }
    }
    public function dailyledger_noticallback(Request $r)
    {
        #called only when new row is added.
        $export_id = $r->get('export_id');
        $noti_id = $r->get('noti_id');
        $status = $r->get('status');
        $update_status='accepted';
        if($status=='reject'){
            $update_status='rejected';
        }
        $notification = Notification::find($noti_id);
        if(!$notification){
            #wrong noti id
            return response()->json([
                'status'=>0,
                'msg'=>'No notification found against this id',
                'data'=>$r->all()
            ]);
        }
        #just update the status of notification
        $notification->action_status=$update_status;
        $notification->status='read';
        $notification->update();
        
        return response()->json([
            'status'=>1,
            'data'=>$r->all(),
            'response'=>$notification
        ]);
    }

    public function edit_rider_account(Request $r){
        $statement_id=$r->statement_id;
        $source=$r->source;
        $amount = $r->amount;
        $company_statement = Rider_account::find($statement_id);
        $company_statement->amount=$amount;
        $company_statement->save();
         return response()->json([
             'status'=>1
         ]);
     }
     public function absents_status($rider_id,$month,$rider_payout_date,$status){
        $onlyMonth = Carbon::parse($month)->format('m');
        $onlyYear = Carbon::parse($month)->format('Y');
        $zi=Income_zomato::with('Time_sheet')
        ->whereMonth("date",$onlyMonth)
        ->whereYear("date",$onlyYear)
        ->where("rider_id",$rider_id)
        ->get()
        ->first();
        if (isset($zi)) {
            $absent_rider_payout=$zi->Time_sheet()->whereDate("date",$rider_payout_date)
            ->get()
            ->first();	
        }

         if (isset($absent_rider_payout)) {
            if ($absent_rider_payout->absent_status=="Rejected" || $absent_rider_payout->absent_status==null){
             if ($status=="approved") {
                $absent_rider_payout->absent_status="Approved";
                $zomato_id=$absent_rider_payout->zomato_income_id;
                $income_zomato=Income_zomato::find($zomato_id);
                if(isset($income_zomato)){
                    if ( $income_zomato->approve_absents>0 ||  $income_zomato->approve_absents!=null) {
                        $income_zomato->approve_absents-=1;
                        $income_zomato->save();
                    }
                }
                if(isset($absent_rider_payout->absent_fine_id)){
                    $ra =Rider_Account::where("kingrider_fine_id",$absent_rider_payout->absent_fine_id)->get()->first();
                    if(isset($ra)) $ra->delete();

                    $ca =Company_Account::where("kingrider_fine_id",$absent_rider_payout->absent_fine_id)->get()->first();
                    if (isset($ca)) $ca->delete();
                    
                }
                $absent_rider_payout->absent_fine_id=null;
                $absent_rider_payout->save();
             }
             if ($status=="rejected") {
                 if ($absent_rider_payout->absent_status!="Rejected") {
                $absent_rider_payout->absent_status="Rejected";
                $absent_rider_payout->absent_fine_id=$absent_rider_payout->id;
                $absent_rider_payout->save();
                $zomato_id=$absent_rider_payout->zomato_income_id;
                $income_zomato=Income_zomato::find($zomato_id);
                if(isset($income_zomato)){
                    $income_zomato->approve_absents+=1;
                    $income_zomato->save();
                }
                $amt=100;
                $ra =new Rider_Account;
                $ra->type='dr';
                $ra->month = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
                $ra->given_date=Carbon::now()->format('Y-m-d');
                $ra->amount=round($amt,2);
                $ra->rider_id=$rider_id;
                $ra->source='Absent Fine (on '.Carbon::parse($rider_payout_date)->format('Y-m-d').')';
                $ra->payment_status='pending';
                $ra->kingrider_fine_id=$absent_rider_payout->id; 
                $ra->save();

                $ca =new Company_Account;
                $ca->type='cr';
                $ca->month = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
                $ca->given_date=Carbon::now()->format('Y-m-d');
                $ca->amount=round($amt,2);
                $ca->rider_id=$rider_id;
                $ca->source='Absent Fine (on '.Carbon::parse($rider_payout_date)->format('Y-m-d').')';
                $ca->payment_status='pending';
                $ca->kingrider_fine_id=$absent_rider_payout->id; 
                $ca->save();
             }
            }
            }
            else{
                if ($status=="approved") {
                    $absent_rider_payout->absent_status="Approved";
                    $absent_rider_payout->absent_fine_id=null;
                    $absent_rider_payout->save();
                 }
                 if ($status=="rejected") {
                    $absent_rider_payout->absent_status="Rejected";
                    $absent_rider_payout->absent_fine_id=$absent_rider_payout->id;
                    $absent_rider_payout->save();
                    $zomato_id=$absent_rider_payout->zomato_income_id;
                    $income_zomato=Income_zomato::find($zomato_id);
                    if(isset($income_zomato)){
                        $income_zomato->approve_absents+=1;
                        $income_zomato->save();
                    } 
                    $amt=100;
                    $ra =new Rider_Account;
                    $ra->type='dr';
                    $ra->month = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
                    $ra->given_date=Carbon::now()->format('Y-m-d');
                    $ra->amount=round($amt,2);
                    $ra->rider_id=$rider_id;
                    $ra->source='Absent Fine (on '.Carbon::parse($rider_payout_date)->format('Y-m-d').')';
                    $ra->payment_status='pending';
                    $ra->kingrider_fine_id=$absent_rider_payout->id; 
                    $ra->save();

                    $ca =new Company_Account;
                    $ca->type='cr';
                    $ca->month = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
                    $ca->given_date=Carbon::now()->format('Y-m-d');
                    $ca->amount=round($amt,2);
                    $ca->rider_id=$rider_id;
                    $ca->source='Absent Fine (on '.Carbon::parse($rider_payout_date)->format('Y-m-d').')';
                    $ca->payment_status='pending';
                    $ca->kingrider_fine_id=$absent_rider_payout->id; 
                    $ca->save();
                 }
                 $absent_rider_payout->save();
            }
         }
         return response()->json([
            'rider_id'=>$rider_id,
            'month'=>$month,
            'rider_payout_date'=>$rider_payout_date,
            'status'=>$status,
        ]);
     }
    public function manage_salaryslips(){
        $riders = Rider::with('Rider_detail')->where('active_status', 'A')->get();
        return view('accounts.manage_salaryslips',compact('riders'));
    }
    public function update_salaryslips(Request $r){
        // return $r->all();
        $rider_id = $r->get('rider_id');
        $checked = $r->get('is_checked');
        $month = $r->get('month');
        $expiry = $r->get('expiry_date');
        if($rider_id!='null' && $rider_id!=null){
            //add show_salaryslip option
            $type=$r->get('type')=='show_atsh'?'show_attendanceslip':'show_salaryslip';
            if($rider_id==0){
                //add to all riders
                $rider_details = Rider_detail::all();
                $rider_details_updates=[];
                foreach ($rider_details as $rider_detail) {
                    $obj=[];
                    $obj['id']=$rider_detail->id;
                    $obj['salaryslip_month']=$month;
                    $obj['salaryslip_expiry']=$expiry;
                    $obj[$type]=($checked=='true'?1:0);
                    array_push($rider_details_updates, $obj);
                }
                $update_data=Batch::update(new Rider_detail, $rider_details_updates, 'id'); //r5 
                return response()->json([
                    'rider_detail'=>Rider_detail::all(),
                    'd'=>0
                ]); 
            }
            else {
                //add against 1 rider only
                $rider_details = Rider::find($rider_id)->Rider_detail;
                $rider_details['salaryslip_month']=$month;
                $rider_details['salaryslip_expiry']=$expiry;
                $rider_details[$type]=($checked=='true'?1:0);
                $rider_details->update();
            }
            return response()->json([
                'rider_detail'=>Rider_detail::all(),
                'd'=>1
            ]);
        }
        
        //add month option to all riders
        $rider_details = Rider_detail::all();
        $rider_details_updates=[];
        foreach ($rider_details as $rider_detail) {
            $obj=[];
            $obj['id']=$rider_detail->id;
            $obj['salaryslip_month']=$month;
            $obj['salaryslip_expiry']=$expiry;
            array_push($rider_details_updates, $obj);
        }
        $update_data=Batch::update(new Rider_detail, $rider_details_updates, 'id'); //r5  
        
        $_data=[];
        $rider_details = Rider_detail::all();
        foreach ($rider_details as $rider_detail) {
            $onlyMonth = Carbon::parse($rider_detail->salaryslip_month)->format('m');
            $onlyYear = Carbon::parse($rider_detail->salaryslip_month)->format('Y');
            $salary_generated = Rider_salary::where('rider_id',$rider_detail->rider_id)
            ->whereMonth("month",$onlyMonth)
            ->whereYear("month",$onlyYear)
            ->get()
            ->first();
            $is_salary_generated = false;
            if(isset($salary_generated)) $is_salary_generated = true;

            $obj=[];
            $obj['rider_detail']=$rider_detail;
            $obj['salary_generated']=$is_salary_generated;
            array_push($_data, $obj);
        }
        return response()->json([
            'data'=>$_data,
            'd'=>2
        ]);
    }
    public static function detect_bill_changes(Request $r)
    {
        // return $r->get('rider_id');
        $rider_id=$r->get('rider_id');
        $month = $r->get('month');
        
        $onlyMonth=Carbon::parse($month)->format('m');
        $onlyYear=Carbon::parse($month)->format('Y');
        $month_start = Carbon::parse($month)->startOfMonth();
        $month_end = Carbon::parse($month)->endOfMonth();
        $changes=[];

        #===================================================================#
        #                   check for salary changes
        #--- we match total salary amount from already generated salary-----
        #===================================================================#
        $salary_data = AccountsController::get_salary_deduction($r,$month,$rider_id);
        if(isset($salary_data->original)){
            $generated_salary=$salary_data->original;
            if($generated_salary['status']==1){
                //salary found
                $already_salary = \App\Model\Accounts\Company_Account::where([
                    'rider_id'=>$rider_id, 
                    'month'=>$month,
                    'source'=>'salary'
                ])
                ->whereNotNull('salary_id')
                ->get()
                ->first();
                if(isset($already_salary)){
                    //match amount
                    if($already_salary->amount!=$generated_salary['total_salary']){
                        array_push($changes, 'Salary');
                    }
                }
            }
        }

        #===================================================================#
        #                   check for sim bill changes
        #  we match total working days with already added bill's working days
        #===================================================================#
        $sim_recalculated=[];

        # 1) we need to get current days againts each bill
        $sim_history = Sim_History::with('Rider')->with('Sim')->get()->toArray();
        $simh_f = Arr::where($sim_history, function ($item, $key) use ($rider_id, $month) {
            $start_created_at =Carbon::parse($item['given_date'])->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);

            $start_updated_at =Carbon::parse($item['return_date'])->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($month);
            
            if($item['status']=='active'){
                return $item['rider_id']==$rider_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
            }
            return $item['rider_id']==$rider_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });
        if(isset($simh_f)){
            #sim found against this rider
            $previous_unassign_date = null;
            foreach ($simh_f as $sim_history) {
                # setting the assign and unassign date
                $sim_id=$sim_history['sim_id'];
                $assign_date = Carbon::parse($sim_history['given_date']);
                $unassign_date = Carbon::parse($sim_history['return_date']);
                if($assign_date->lessThan($month_start)){ #assign date will be start of month
                    $assign_date = $month_start;
                }
                if($unassign_date->greaterThan($month_end) || $sim_history['status']=='active'){ #unassign date will be end of month
                    $unassign_date = $month_end;
                }

                #storing previous unassign date, to check...
                #if previous iteration unassign date is same as current iteration assign date, 
                if ($previous_unassign_date!=null) {
                    # then we'll add 1 day to current iteration assign date
                    if ($previous_unassign_date->equalTo($assign_date)) {
                        $assign_date = $assign_date->addDay();
                    }
                }
                $previous_unassign_date = $unassign_date;

                #now we just find total working days by subtracting assign_date and unassign_date +1 for adding first day
                $working_days = $unassign_date->diffInDays($assign_date)+1;


                #-------------------------------------------
                # now we will get current added working days
                #-------------------------------------------
                $paid_bills = Bill_change::whereMonth('month', $onlyMonth)
                ->whereYear('month', $onlyYear)
                // ->where('type', 'sim')
                ->get();

                #now we need to fetch only sim bill from all paid bills
                $bill_workdays=0;
                foreach ($paid_bills as $paid_bill) {
                    if($paid_bill->type=='sim'){ #filters only sim bills
                        $feed = json_decode($paid_bill->feed,true);
                        foreach ($feed as $feed_item) {
                            # match rider_id
                            if($feed_item['rider_id']==$rider_id && $feed_item['sim_id']==$sim_id){
                                $bill_workdays=round($feed_item['work_days']);
                                #check if predicted bill is matched to paid bill
                                if($bill_workdays!=$working_days){
                                    array_push($changes, 'Sim bill against '.$sim_history['sim']['sim_number']);
                                }
                            }
                        }
                    }
                    
                    
                }
                // if($bill_workdays!=0){
                //     #check if predicted bill is matched to paid bill
                //     if($bill_workdays!=$working_days){
                //         array_push($changes, 'Sim bill against '.$sim_history['sim']['sim_number']);
                //     }


                //     #store it to some array - FOR TESTING
                //     $obj=[];
                //     $obj['working_days']=$working_days;
                //     $obj['bill_workdays']=$bill_workdays;
                //     $obj['ass_d']=$assign_date;
                //     $obj['unass_d']=$unassign_date;
                //     $obj['sim_number']=$sim_history['sim']['sim_number'];
                //     array_push($sim_recalculated, $obj);
                // }

                
            }
        }

        #===================================================================#
        #                   check for bike rent changes
        #  we match total working days with already added bill's working days
        #===================================================================#
        // $sim_recalculated=[];

        # 1) we need to get current days againts each bill
        $bike_history = Assign_bike::with('Rider')->with('bike')->get()->toArray();
        $bikeh_f = Arr::where($bike_history, function ($item, $key) use ($rider_id, $month) {
            $start_created_at =Carbon::parse($item['bike_assign_date'])->startOfMonth()->format('Y-m-d');
            $created_at =Carbon::parse($start_created_at);

            $start_updated_at =Carbon::parse($item['bike_unassign_date'])->endOfMonth()->format('Y-m-d');
            $updated_at =Carbon::parse($start_updated_at);
            $req_date =Carbon::parse($month);
            
            if($item['status']=='active'){
                return $item['rider_id']==$rider_id && ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at));
            }
            return $item['rider_id']==$rider_id &&
                ($req_date->isSameMonth($created_at) || $req_date->greaterThanOrEqualTo($created_at)) && ($req_date->isSameMonth($updated_at) || $req_date->lessThanOrEqualTo($updated_at));
        });
        if(isset($bikeh_f)){
            #sim found against this rider
            $previous_unassign_date = null;
            foreach ($bikeh_f as $bike_history) {
                # setting the assign and unassign date
                $bike_id=$bike_history['bike_id'];
                $bike=$bike_history['bike'];
                $assign_date = Carbon::parse($bike_history['bike_assign_date']);
                $unassign_date = Carbon::parse($bike_history['bike_unassign_date']);
                if($assign_date->lessThan($month_start)){ #assign date will be start of month
                    $assign_date = $month_start;
                }
                if($unassign_date->greaterThan($month_end) || $bike_history['status']=='active'){ #unassign date will be end of month
                    $unassign_date = $month_end;
                }

                #storing previous unassign date, to check...
                #if previous iteration unassign date is same as current iteration assign date, 
                if ($previous_unassign_date!=null) {
                    # then we'll add 1 day to current iteration assign date
                    if ($previous_unassign_date->equalTo($assign_date)) {
                        $assign_date = $assign_date->addDay();
                    }
                }
                $previous_unassign_date = $unassign_date;

                #now we just find total working days by subtracting assign_date and unassign_date +1 for adding first day
                $working_days = $unassign_date->diffInDays($assign_date)+1;


                #-------------------------------------------
                # now we will get current added working days
                #-------------------------------------------
                $paid_bills = Bill_change::whereMonth('month', $onlyMonth)
                ->whereYear('month', $onlyYear)
                // ->where('type', 'sim')
                ->get();

                #now we need to fetch only sim bill from all paid bills
                $bill_workdays=0;
                $fuel_workdays=0;
                $bill_msg='';
                foreach ($paid_bills as $paid_bill) {
                    #filters only bike rents
                    if($paid_bill->type=='bike_rent'){
                        $feed = json_decode($paid_bill->feed,true);
                        foreach ($feed as $feed_item) {
                            # match rider_id
                            if($feed_item['rider_id']==$rider_id && $feed_item['bike_id']==$bike_id){
                                $bill_workdays=round($feed_item['work_days']);
                                if($bill_workdays!=$working_days){
                                    array_push($changes, 'Bike rent against '.$bike['bike_number']);
                                }

                                #check if accurate bike rent 
                                if($bike['owner']=='self'){
                                    #rent (alowns) should be 450
                                    if($paid_bill->amount!=450){
                                        $bill_msg='Wrong Bike rent! It should be 450';
                                        array_push($changes, $bill_msg.' against '.$bike['bike_number']);
                                    }
                                }
                                else if($bike['owner']=='kr_bike' || $bike['owner']=='rent'){
                                    #rent should be 550
                                    if($paid_bill->amount!=550){
                                        $bill_msg='Wrong Bike rent! It should be 550';
                                        array_push($changes, $bill_msg.' against '.$bike['bike_number']);
                                    }
                                }
                            }
                        }
                    }
                    
                    #filters only fuels
                    if($paid_bill->type=='fuel_cash' || $paid_bill->type=='fuel_vip'){
                        $feed = json_decode($paid_bill->feed,true);
                        foreach ($feed as $feed_item) {
                            # match rider_id
                            if($feed_item['rider_id']==$rider_id && $feed_item['bike_id']==$bike_id){
                                $fuel_workdays=round($feed_item['work_days']);
                                #check if predicted bill is matched to paid bill
                                if($fuel_workdays!=$working_days){
                                    array_push($changes, 'Fuel against '.$bike['bike_number']);
                                }
                            }
                        }
                    }
                }
                // if($bill_workdays!=0){
                //     #check if predicted bill is matched to paid bill
                //     if($bill_workdays!=$working_days){
                //         array_push($changes, 'Bike rent against '.$bike['bike_number']);
                //     }
                //     if($bill_msg!=''){
                //         #some bill msg was found
                //         array_push($changes, $bill_msg.' against '.$bike['bike_number']);
                //     }


                //     #store it to some array - FOR TESTING
                //     $obj=[];
                //     $obj['working_days']=$working_days;
                //     $obj['bill_workdays']=$bill_workdays;
                //     $obj['ass_d']=$assign_date;
                //     $obj['unass_d']=$unassign_date;
                //     $obj['bike_number']=$bike['bike_number'];
                //     array_push($sim_recalculated, $obj);
                // }

                // if($fuel_workdays!=0){
                //     #check if predicted bill is matched to paid bill
                //     if($fuel_workdays!=$working_days){
                //         array_push($changes, 'Fuel against '.$bike['bike_number']);
                //     }


                //     #store it to some array - FOR TESTING
                //     $obj=[];
                //     $obj['working_days']=$working_days;
                //     $obj['bill_workdays']=$bill_workdays;
                //     $obj['ass_d']=$assign_date;
                //     $obj['unass_d']=$unassign_date;
                //     $obj['bike_number']=$bike['bike_number'];
                //     array_push($sim_recalculated, $obj);
                // }

                
            }
        }
        
        return response()->json([
            'status'=>1,
            'changes'=>$changes,
            'data'=>$salary_data,
            'sim_recalculated'=>$sim_recalculated,
        ]);
    }
    public function rider_salary_status(){
        return view('admin.rider.rider_salary_status');
    }
    public function expense_data(){
        return view('admin.accounts.Company_Expense.expense_data');
    }
    public function daily_ledger(){
        $riders=Rider::where("active_status","A")->get();
        return view('admin.accounts.Company_Expense.daily_ledger',compact('riders'));
    }
    public function bills_details_for_riders($rider_id,$month){
        $bills=$this->calculate_bills($month,$rider_id);
        return response()->json([
            'rider_id'=>$rider_id,
            'month'=>$month,
            'bills'=>$bills,
        ]);
    }

    public function is_bill_pending($rider_id,$month){
        $onlyMonth=Carbon::parse($month)->format("m");
        $onlyYear=Carbon::parse($month)->format("Y");

        $fuel_cash=[];
        $fuel_vip=[];
        $salik=[];
        $sim=[];
        $bike_rent=[];

        $bill_fuel_cash=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear);
        if (isset($bill_fuel_cash)) {
            $fuel_cash=$bill_fuel_cash->where("source","fuel_expense_cash")->get();
        }
        $bill_fuel_vip=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear);
        if (isset($bill_fuel_vip)) {
            $fuel_vip=$bill_fuel_vip->where("source","fuel_expense_vip")->get();
        }
        $bill_salik=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear);
        if (isset($bill_salik)) {
            $salik=$bill_salik->where("source","Salik")->get();
        }
        $bill_sim=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear);
        if (isset($bill_sim)) {
            $sim=$bill_sim->where("source","Sim Transaction")->get();
        }
        $bill_bike_rent=Company_Account::where("rider_id",$rider_id)
        ->whereMonth('month',$onlyMonth)
        ->whereYear('month',$onlyYear);
        if (isset($bill_bike_rent)) {
            $bike_rent=$bill_bike_rent->where("source","Bike Rent")->get();
        }


        return response()->json([
            'rider_id'=>$rider_id,
            'month'=>$month,

            'fuel_cash'=>$fuel_cash,
            'fuel_vip'=>$fuel_vip,
            'salik'=>$salik,
            'sim'=>$sim,
            'bike_rent'=>$bike_rent,
        ]);
    }

    public function check_salary_status(Request $r,$rider_id,$month){
        $onlyMonth=Carbon::parse($month)->format('m');
        $onlyYear=Carbon::parse($month)->format('Y');
        $status=0;
        $msg='Salary is not generated';

        $salary_data = $this->get_salary_deduction($r,$month,$rider_id);
        if(isset($salary_data->original)){
            $generated_salary=$salary_data->original;
            if($generated_salary['status']==1){
                if ($salary_data->original['is_paid']!=true) {
                    $status=1;
                    $msg='Salary is not paid';
                }
                if ($salary_data->original['is_paid']==true) {
                    $status=2;
                    $msg='Salary is already paid';
                }
            }
        }
        return response()->json([
            'rider_id'=>$rider_id,
            'month'=>$month,

            'salary_data'=>$salary_data,
            'status'=>$status,
            'msg'=>$msg,

        ]);
    }
    public function update_remaining_salary(){
        $riders=Rider::where("active_status","A")->get();
        return view('accounts.remaining_salary',compact("riders"));
    }
    public function add_update_remaining_salary(Request $request){
        $rider_id=$request->rider_id;
        $_onlyMonth=Carbon::parse($request->month)->format("m");
        $_onlyYear=Carbon::parse($request->month)->format("Y");

        $rider_salary=Rider_salary::where("rider_id",$rider_id)
        ->whereMonth("month",$_onlyMonth)
        ->whereYear("month",$_onlyYear)
        ->get()
        ->first();
        if(isset($rider_salary)){
            $rider_salary->recieved_salary=($request->salary_paid)+($request->reamining_salary);
            $rider_salary->remaining_salary=round((($request->total_remaining)-($request->reamining_salary)),2);
            $rider_salary->save();
        }

        $ra=new Rider_Account;
        $ra->type='dr';
        $ra->month = Carbon::parse($request->month)->startOfMonth()->format('Y-m-d');
        $ra->given_date=Carbon::parse($request->given_date)->format('Y-m-d');
        $ra->amount=round($request->reamining_salary,2);
        $ra->rider_id=$rider_id;
        $ra->source='remaining_salary';
        $ra->payment_status='paid';
        $ra->salary_id=$rider_salary->id; 
        $ra->save();

        // return $rider_salary;
        
    }
    // private static function crypto_rand_secure($min, $max)
    // {
    //     $range = $max - $min;
    //     if ($range < 1) return $min; // not so random...
    //     $log = ceil(log($range, 2));
    //     $bytes = (int) ($log / 8) + 1; // length in bytes
    //     $bits = (int) $log + 1; // length in bits
    //     $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    //     do {
    //         $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
    //         $rnd = $rnd & $filter; // discard irrelevant bits
    //     } while ($rnd > $range);
    //     return $min + $rnd;
    // }

    public static function getUniqueId($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        function crypto_rand_secure($min, $max)
        {
            $range = $max - $min;
            if ($range < 1) return $min; // not so random...
            $log = ceil(log($range, 2));
            $bytes = (int) ($log / 8) + 1; // length in bytes
            $bits = (int) $log + 1; // length in bits
            $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
            do {
                $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
                $rnd = $rnd & $filter; // discard irrelevant bits
            } while ($rnd > $range);
            return $min + $rnd;
        }

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
        }

        return $token;
    }
}