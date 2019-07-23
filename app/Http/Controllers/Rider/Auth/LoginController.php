<?php

namespace App\Http\Controllers\Rider\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Rider\Rider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Model\Client\Client_Rider;
use App\Model\Rider\Rider_Online_Time;
use Carbon\Carbon;

class LoginController extends Controller
{
    //
    public function login(Request $request)
    {
        $this->validateLogin($request);

        $attempt = Rider::where('email', $request->email)
        ->where('status', "1")
        ->first();
        if($attempt)
        {
            if(Hash::check($request->password, $attempt->password))
            {
                $client_details = $attempt->clients;
                // return $client_details;
                if(count($client_details)>0)
                {
                    $restaurant_name = $client_details[0]->name;
                    $restaurant_address = $client_details[0]->address;
                    $restaurant_latitude = $client_details[0]->latitude;
                    $restaurant_longitude = $client_details[0]->longitude;
                }
                else
                {
                    $restaurant_name = "";
                    $restaurant_address = "";
                    $restaurant_latitude = "";
                    $restaurant_longitude = "";
                }
                $attempt->online = 1;
                $attempt->update();

                $online_time = new Rider_Online_Time();
                $online_time->rider_id = $attempt->id;
                $online_time->online_time = Carbon::now();
                $online_time->save();

                return response()->json([
                    'user_id' => $attempt->id,
                    'rider_id' => $attempt->id,
                    'email' => $attempt->email,
                    'full_name' => $attempt->name,
                    'mobile' => $attempt->phone,
                    'driver_pic' => $attempt->profile_picture ? asset(Storage::url($attempt->profile_picture)) : asset('dashboard/assets/media/users/default.jpg'),
                    'vehicle_number' => $attempt->vehicle_number,
                    'restaurant_name' => $restaurant_name,
                    'restaurant_address' => $restaurant_address,
                    'restaurant_latitude' => $restaurant_latitude,
                    'restaurant_longitude' => $restaurant_longitude,
                    'status' => "success"
                ]);
            }
            else
            {
                return response()->json([
                    'error' => 'Invalid credentials',
                    'status' => "error"
                ]);
            }
        }
        else
        {
            return response()->json([
                'error' => 'Invalid credentials',
                'status' => "error"
            ]);
        }
    }
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }
}
