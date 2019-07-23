<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Model\Admin\Client_Email;

class ProfileController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function showProfile()
    {
        $user = Auth::user();
        return view('client.profile.profile', compact('user'));
    }
    public function edit()
    {
        $user = Auth::user();
        return view('client.profile.edit', compact('user'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'name' => 'required | string',
            'email' => 'required | email',
            'phone' => 'required | string',
            'address' => 'required | string',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        // $user->about = $request->about;
        $user->address = $request->address;
        if($request->hasFile('logo'))
        {
            // return 'yes';
            if($user->logo)
            {
                Storage::delete($user->logo);
            }
            $filename = $request->logo->getClientOriginalName();
            $filesize = $request->logo->getClientSize();
            $filepath = Storage::putfile('public/uploads/clients/logos', $request->file('logo'));
            $user->logo = $filepath;
        }
        // return 'no';
        $user->update();
        return redirect(route('client.profile'));
    }
    public function messageToSupport()
    {
        return view('client.profile.messageToSupport');
    }
    public function sendMessageToSupport(Request $request)
    {
        $this->validate($request, [
            'from' => 'required | email',
            'subject' => 'required | string',
            'message' => 'required | string'
        ]);
        $email = new Client_Email();
        $email->from = $request->from;
        $email->subject = $request->subject;
        $email->message = $request->message;
        $email->save();
        return redirect()->back()->with('message', 'You message has been sent successfully.');
    }
}
