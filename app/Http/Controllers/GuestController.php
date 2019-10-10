<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function newComer_view(){
        return view('guest.guest_newcomer');
    }
    
}
