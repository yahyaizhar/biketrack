<?php

namespace App\Http\Controllers\Rider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Rider\Rider_Location;
use App\Model\Rider\Rider;
use Illuminate\Support\Facades\Storage;
use App\Model\Rider\Rider_Report;
use App\Model\Rider\Rider_Online_Time;
use Carbon\Carbon;

class RiderController extends Controller
{
    //
    public function storeLocation(Request $request)
    {
        $this->validate($request, [
            'driver_id' => 'required | exists:riders,id',
            'latitude' => 'required | numeric',
            'longitude' => 'required | numeric',
        ]);
        $location = new Rider_Location();
        $location->rider_id = $request->driver_id;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->save();
    }
    public function get_reports(Request $r) 
    {
        $rider = Rider::find($r->rider_id);
        $rider_reports=$rider->Rider_Report()->whereDate('created_at','=',Carbon::parse($r->date)->toDateString())->get();
        return response()->json([
            'reports'=>$rider_reports,
            'status' => "success"
        ]);
    }
    public function getLatestData(Rider $rider)
    {
        $client_details = $rider->clients;
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
        return response()->json([
            'user_id' => $rider->id,
            'rider_id' => $rider->id,
            'email' => $rider->email,
            'full_name' => $rider->name,
            'mobile' => $rider->phone,
            'driver_pic' => $rider->profile_picture ? asset(Storage::url($rider->profile_picture)) : asset('dashboard/assets/media/users/default.jpg'),
            'vehicle_number' => $rider->vehicle_number,
            'restaurant_name' => $restaurant_name,
            'restaurant_address' => $restaurant_address,
            'restaurant_latitude' => $restaurant_latitude,
            'restaurant_longitude' => $restaurant_longitude,
            'status' => "success"
        ]);
    }
    public function changeStatus(Request $request)
    {
        $this->validate($request, [
            'rider_id' => 'required | exists:riders,id',
            'status' => 'required|integer|between:1,2'
        ]);
        $rider = Rider::where('id', $request->rider_id)->first();
        $rider->online = $request->status;
        $rider->update();

        if($request->status == 2)
        {
            $record = Rider_Online_Time::where('rider_id', $request->rider_id)->whereNull('offline_time')->first();
            if($record)
            {
                $record->offline_time = Carbon::now();

                $start = Carbon::parse($record->online_time);
                $end = Carbon::parse(Carbon::now());
                $minutes = $end->diffInMinutes($start);

                $record->total_minutes = $minutes;
                $record->update();
            }
        }
        elseif($request->status == 1)
        {
            $check_record = Rider_Online_Time::where('rider_id', $request->rider_id)->whereNull('offline_time')->first();
            if(!$check_record)
            {
                $new_time_record = new Rider_Online_Time();
                $new_time_record->rider_id = $request->rider_id;
                $new_time_record->online_time = Carbon::now();
                $new_time_record->save();
            }
        }
    }
    public function saveRideDetailsAndEndday(Request $request)
    { 
        $rider = Rider::find($request->rider_id);
        if($rider){
            $start = Carbon::parse($request->started_at);
            $end = Carbon::parse(Carbon::now());
            $minutes = $end->diffInMinutes($start);
            $rider->online = $request->status;
            $rider->update();
            $report = new Rider_Report();
            $report->rider_id = $request->rider_id;
            $report->online_hours = round($minutes/60, 2);
            $report->no_of_trips = $request->no_of_trips;
            $report->started_location=$request->start_loc;
            $report->ended_location=$request->end_loc;
            $report->no_of_hours=$request->no_of_hours;
            $report->mileage=$request->mileage;
            $report->save();
            $times = Rider_Online_Time::where('rider_id', $request->driver_id)->get();
            foreach($times as $time)
            {
                $time->delete();
            }
            return response()->json([
                'status' => "success",
                'status_code'=>200
            ]);
        } 
        else{
            return response()->json([
                'error' => 'Rider not found',
                'status' => "error",
                'status_code'=>404
            ]);
        }
        
    }
    public function startday(Request $r)
    {
        $rider = Rider::find($r->rider_id);
        if($rider){
            $client_details = $rider->clients;
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
            $rider->online = 1;
            $rider->update();

            $online_time = new Rider_Online_Time();
            $online_time->rider_id = $rider->id;
            $online_time->online_time = Carbon::now();
            $online_time->save();

            return response()->json([
                'user_id' => $rider->id,
                'rider_id' => $rider->id,
                'email' => $rider->email,
                'full_name' => $rider->name,
                'mobile' => $rider->phone,
                'driver_pic' => $rider->profile_picture ? asset(Storage::url($rider->profile_picture)) : asset('dashboard/assets/media/users/default.jpg'),
                'vehicle_number' => $rider->vehicle_number,
                'restaurant_name' => $restaurant_name,
                'restaurant_address' => $restaurant_address,
                'restaurant_latitude' => $restaurant_latitude,
                'restaurant_longitude' => $restaurant_longitude,
                'status' => "success",
                'status_code'=>200
            ]);
        }
        else{
            return response()->json([
                'error' => 'Rider not found',
                'status' => "error",
                'status_code'=>404
            ]);
        }

    }

    public function saveRideDetailsAndLogout(Request $request)
    {
        $this->validate($request, [
            'driver_id' => 'required | exists:riders,id',
            'status' => 'required | in: 3',
            'no_of_trips' => 'required | integer',
            'location' => 'required | string'
        ]);
        // return $request->all();
        
        $rider = Rider::where('id', $request->driver_id)->first();
        $rider->online = $request->status;
        $rider->update();

        $record = Rider_Online_Time::where('rider_id', $request->driver_id)->whereNull('offline_time')->first();
        if($record)
        {
            $record->offline_time = Carbon::now();

            // calculate hours
            $start = Carbon::parse($record->online_time);
            $end = Carbon::parse(Carbon::now());
            $minutes = $end->diffInMinutes($start);

            $record->total_minutes = $minutes;
            $record->update();
        }

        $total_online_hours = Rider_Online_Time::where('rider_id', $request->driver_id)->sum('total_minutes');
        
        $report = new Rider_Report();
        $report->rider_id = $request->driver_id;
        $report->online_hours = round($total_online_hours/60, 2);
        $report->no_of_trips = $request->no_of_trips;
        $report->location = $request->location;
        $report->save();

        $times = Rider_Online_Time::where('rider_id', $request->driver_id)->get();
        foreach($times as $time)
        {
            $time->delete();
        }

        return response()->json([
            'status' => "success"
        ]);
    }
}
