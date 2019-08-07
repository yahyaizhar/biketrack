<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\Rider\Rider;
use App\Model\Client\Client;
use App\Model\Client\Client_Rider;

class RiderController extends Controller
{
    //
    public function showAllRidersLocations()
    {
        return view('client.rider.allRidersLocations');
    }
    public function showRiders(Request $request)
    {
        $total_riders = Auth::user()->riders->count();
        $riders = Auth::user()->riders;
        
        return view('client.rider.riders', compact('riders', 'total_riders'));
    }
    public function showRiderLocation(Rider $rider)
    {
        return view('client.rider.location');
    }
   public function update_ClientRiders(Request $req){

$client_rider=Client_Rider::where('client_id',Auth::user()->id)->where('rider_id',$req->rider_id)->get()->first();
$client_rider->client_rider_id=$req->client_rider_id;
$client_rider->update();
return $client_rider;
   }
}
