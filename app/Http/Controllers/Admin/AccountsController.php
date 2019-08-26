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
use Carbon\Carbon;
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
            'status'=>$r->status=='on'?1:0,
        ]);
        $ca = new \App\Model\Accounts\Company_Account;
        $ca->type='dr';
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
        $id_charge->amount=$r->amount;
        // $id_charge->=$r->;
        if($r->status)
            $id_charge->status = 1;
        else
            $id_charge->status = 0;
        $id_charge->update();

        $ca =\App\Model\Accounts\Company_Account::find($id);
        $ca->type='dr';
        $ca->rider_id=$r->rider_id;
        $ca->amount=$r->amount;
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

    // add new salary
    public function add_new_salary_create(){
        $riders=Rider::where("active_status","A")->get();
        return view('accounts.add_new_salary',compact('riders'));
    }
    public function new_salary_added(Request $request){
        $rider_id=$request->rider_id;
        $rider=Rider::find($rider_id); 
        $salary=$rider->Rider_salary()->create([
            'rider_id'=>$request->get('rider_id') ,
            'month'=> Carbon::parse($request->get('month'))->format('Y-m-d'),
            'salary'=> $request->get('salary'),
            'paid_by'=> $request->get('paid_by'),
            'status'=> $request->get('status')=='on'?1:0,
        ]);
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
    $bikes=bike::all();
    return view('admin.accounts.Fuel_Expense.FE_add',compact('bikes'));
}
public function fuel_expense_insert(Request $r){
    $bike_id=bike::find($r->bike_id);
    $fuel_expense=new Fuel_Expense();
    $fuel_expense->amount=$r->amount;
    $fuel_expense->type=$r->type;
    $fuel_expense->bike_id=$bike_id->id;
    if($r->status)
            $fuel_expense->status = 1;
        else
            $fuel_expense->status = 0;
    $fuel_expense->save();
if ($fuel_expense->type=="vip_tag") {
    
    $ca = new \App\Model\Accounts\Company_Account;
    $ca->type='dr';
    $ca->amount=$r->amount;
    $ca->source='fuel_expense';
    $ca->fuel_expense_id=$fuel_expense->id;
    $ca->save();
}
elseif($fuel_expense->type=="cash"){
    $ca = new \App\Model\Accounts\Company_Account;
    $ca->type='dr';
    $ca->amount=$r->amount;
    $ca->source='fuel_expense';
    $ca->fuel_expense_id=$fuel_expense->id;
    $ca->save();

    $ra = new \App\Model\Accounts\Rider_Account;
    $ra->type='cr';
    $ra->amount=$r->amount;
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
    $fuel_expense->type=$r->type;
    $fuel_expense->bike_id=$bike_id->id;
    if($r->status)
            $fuel_expense->status = 1;
        else
            $fuel_expense->status = 0;
    $fuel_expense->update();
if ($fuel_expense->type=="vip_tag") {
    
    $ca = \App\Model\Accounts\Company_Account::firstOrCreate([
        'fuel_expense_id'=>$fuel_expense->id
    ]);
    $ca->type='dr';
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
    $ca->source="fuel_expense"; 
    $ca->amount=$r->amount;
    $ca->save();
    

    $ra =\App\Model\Accounts\Rider_Account::updateOrCreate([
    'fuel_expense_id'=>$fuel_expense->id,
    ]);
    $ra->fuel_expense_id=$fuel_expense->id;
    $ra->type='cr';
    $ra->source="fuel_expense"; 
    $ra->amount=$r->amount;
    $ra->save();
    
}
    return redirect(route('admin.fuel_expense_view'));
}


// end Fuel Expense


}