<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function newComer_view(){
        return view('newcomer_form_accesible');
    }
    
}
