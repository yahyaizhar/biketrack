<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Model\Client\Client;
use App\Model\Client\Client_Rider;
use Arr;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    } 
    public function add_invoice(){
        $clients=Client::where("active_status","A")->get();
        return view('admin.Invoices.add_invoice',compact('clients'));
    }
    public function get_ajax_client_details($client_id){
        $clients=Client::find($client_id);
        $billing_address=$clients->address;
        return response()->json([
            'billing_address' => $billing_address,
        ]);
    }
    public function view_invoices(){ 
        return view('admin.Invoices.view_invoice'); 
    }
}
