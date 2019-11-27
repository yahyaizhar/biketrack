<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
    public function showLoginForm()
    {
        if(!session()->has('url.intended'))
        {
            session(['url.intended' => url()->previous()]);
        }
        return view('admin.auth.login');
    }
    public function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'Active_status'=>'A'
        ];
        if(Auth::guard('admin')->attempt($credentials, $request->remember))
        {
            // If successfull, redirect to their intended location
            return redirect()->intended(route('admin.home'));
        }

        return $this->sendFailedLoginResponse($request);
    }
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}
