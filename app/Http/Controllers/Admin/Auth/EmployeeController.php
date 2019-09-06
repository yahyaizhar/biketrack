<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Model\Admin\Admin;
use App\Model\Admin\Role;
use Illuminate\Support\Facades\DB;
use Batch;
use Arr;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest:admin')->except('logout');
        $this->middleware('auth:admin');
    }
    public function showloginform()
    {
        return view('admin.auth.employee_login');    
    }
    public function viewEmployee(){
        return view('admin.auth.employee_view');
    }
    public function edit_employee($employee_id){
        $edit_employee=Admin::find($employee_id); 
        $users=$edit_employee->Role()->get()->toArray();
         
        $Dashboard= Arr::first($users, function ($item) {
            return $item['action_name']=='dashboard';});

        $Riders= Arr::first($users, function ($item) {
                return $item['action_name']=='riders';   });
        
         $Clients= Arr::first($users, function ($item) {
                return $item['action_name']=='clients';   });
           
         $Bikes= Arr::first($users, function ($item) {
                return $item['action_name']=='bikes';   });
         
         $Sim= Arr::first($users, function ($item) {
                return $item['action_name']=='sim';   });
        
         $Mobile= Arr::first($users, function ($item) {
                return $item['action_name']=='mobile';   });
           
         $NewComer= Arr::first($users, function ($item) {
                return $item['action_name']=='new_comer';   });
        
         $Accounts= Arr::first($users, function ($item) {
                return $item['action_name']=='accounts';   });
         
         $Expense= Arr::first($users, function ($item) {
                return $item['action_name']=='expense';   });
       
         $Salik= Arr::first($users, function ($item) {
                return $item['action_name']=='salik';   });    
        
        return view('admin.auth.employee_edit',compact('edit_employee','Dashboard','Riders','Clients','Bikes','Sim','Mobile','NewComer','Accounts','Expense','Salik'));
    }
    public function deleteEmployee($employee_id){
        $delete_users=Auth::user()->find($employee_id);
        $delete_users->active_status="D";
        $delete_users->save();
        return $delete_users;
    }
    public function insert_employee(Request $request){
        $this->validate($request, [
            'name' => 'required | string | max:255',
            'email' => 'required | email | unique:clients',
            'password' => 'required | string | confirmed',
        ]);
        $employee=new Admin();
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->password = Hash::make($request->password);
        if($request->hasFile('logo'))
        {
            $filename = $request->logo->getClientOriginalName();
            $filesize = $request->logo->getClientSize();
            // $filepath = $request->logo->storeAs('public/uploads/clients/logos', $filename);
            $filepath = Storage::putfile('public/uploads/employee/logos', $request->file('logo'));
            $employee->logo = $filepath;
        }
        $employee->save();
       
        $roles = $request->get('action_name');
            $user_roles=[];
        foreach ($roles  as $role) {
        $obj=[];
        $obj['admin_id']=$employee->id;
        $obj['action_name']=$role;
        $obj['created_at']=Carbon::now();
        $obj['updated_at']=Carbon::now();
        array_push($user_roles, $obj);
        }
        DB::table('roles')->insert($user_roles);        
        return redirect(url('/admin/show/employee'));
    }
    public function update_employee(Request $request,$id){
        $user = Admin::find($id);
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
        ]);
        if($request->change_password)
        {
            $this->validate($request, [
                'password' => 'required | string | confirmed',
            ]);
            $user->password = Hash::make($request->password);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->hasFile('logo'))
        {
            // return 'yes';
            if($user->logo)
            {
                Storage::delete($user->logo);
            }
            $filename = $request->logo->getClientOriginalName();
            $filesize = $request->logo->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/employee/logos', $request->file('logo'));
            $user->logo = $filepath;
        }
        $user->update();
        
        $role=$user->Role()->get();
        foreach ($role as $role_delete) {
            $role_delete->delete();
        }
        $roles = $request->get('action_name');
            $user_roles=[];
        foreach ($roles  as $role) {
        $obj=[];
        $obj['admin_id']=$user->id;
        $obj['action_name']=$role;
        $obj['created_at']=Carbon::now();
        $obj['updated_at']=Carbon::now();
        array_push($user_roles, $obj);
        }
        DB::table('roles')->insert($user_roles);
        
        
        return redirect(url('/admin/show/employee'));
    }
    public function getEmployee()
    {
        $users=Auth::user()->where("type","normal")->where("active_status","A")->get();
        // return $clients;
        return DataTables::of($users)
       
        ->addColumn('id', function($users){
            return '1000'.$users->id;
        })
        ->addColumn('name', function($users){
            return $users->name;
        })
        ->addColumn('email', function($users){
            return $users->email;
        })
       
       
        ->addColumn('actions', function($users){
            $status_text = $users->status == 1 ? 'Inactive' : 'Active';
            return '<span class="dtr-data">
            <span class="dropdown">
                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                <i class="la la-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="'.route('Employee.edit_employee', $users).'"><i class="fa fa-edit"></i> Edit</a>
                    <button class="dropdown-item" onclick="deleteEmployee('.$users->id.');"><i class="fa fa-trash"></i> Delete</button>
                    </div>
            </span>
        </span>';
        })
        ->rawColumns(['name','email','actions'])
        ->make(true);
    }
}