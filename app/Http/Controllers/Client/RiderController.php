<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\Rider\Rider;

class RiderController extends Controller
{
    //
    public function showAllRidersLocations()
    {
        return view('client.rider.allRidersLocations');
    }
    public function showRiders()
    {
        $total_riders = Auth::user()->riders->count();
        // return $total_riders;
        $riders = Auth::user()->riders;
        return view('client.rider.riders', compact('riders', 'total_riders'));
    }
    public function showRiderLocation(Rider $rider)
    {
        return view('client.rider.location');
    }
}
