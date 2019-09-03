<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Model\Admin\Admin;
use App\Model\Admin\Role;
use Illuminate\Support\Facades\DB;
use Batch;
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
        return redirect(url('/admin'));
    }
}
