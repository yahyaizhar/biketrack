<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Rider\Rider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\RiderLocationResourceCollection;
use App\Model\Rider\Rider_Message;
use Illuminate\Support\Facades\Auth;
use App\Model\Rider\Rider_Report;
use App\Model\Rider\Rider_detail;

class RiderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.rider.riders');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.rider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'name' => 'required | string | max:255',
            'email' => 'required | email | unique:riders',
            'phone' => 'required | string | max:255',
            'password' => 'required | string | confirmed',
            'vehicle_number' => 'required | string',
            'address' => 'required | string',
        ]);
        $rider = new Rider();
        $rider->name = $request->name;
        $rider->email = $request->email;
        $rider->phone = $request->phone;
        $rider->password = Hash::make($request->password);
        $rider->vehicle_number = $request->vehicle_number;
        $rider->address = $request->address;
        if($request->status)
            $rider->status = 1;
        else
            $rider->status = 0;
        if($request->hasFile('profile_picture'))
        {
            // return 'yes';
            $filename = $request->profile_picture->getClientOriginalName();
            $filesize = $request->profile_picture->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/profile_pics', $request->file('profile_picture'));
            $rider->profile_picture = $filepath;
        }
        // return 'no';
        $rider->save();
        
       $rider_detail=new Rider_detail();
       $rider_detail->rider_id = $rider->id;
       $rider_detail->date_of_joining = $request->date_of_joining;
       $rider_detail->official_given_number = $request->official_given_number;
       $rider_detail->official_sim_given_date = $request->official_sim_given_date;
       if($request->hasFile('passport_image'))
        {
            // return 'yes';
            $filename = $request->passport_image->getClientOriginalName();
            $filesize = $request->passport_image->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/passport_image', $request->file('passport_image'));
            $rider_detail->passport_image = $filepath;
        }
       $rider_detail->passport_expiry = $request->passport_expiry;
       if($request->hasFile('visa_image'))
       {
           // return 'yes';
           $filename = $request->visa_image->getClientOriginalName();
           $filesize = $request->visa_image->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/visa_image', $request->file('visa_image'));
           $rider_detail->visa_image = $filepath;
       }
       $rider_detail->visa_expiry = $request->visa_expiry;
       if($request->hasFile('licence_image'))
       {
           // return 'yes';
           $filename = $request->licence_image->getClientOriginalName();
           $filesize = $request->licence_image->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/licence_image', $request->file('licence_image'));
           $rider_detail->licence_image = $filepath;
       }
       $rider_detail->licence_expiry= $request->licence_expiry;
       if($request->hasFile('mulkiya_image'))
       {
           // return 'yes';
           $filename = $request->mulkiya_image->getClientOriginalName();
           $filesize = $request->mulkiya_image->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/mulkiya_image', $request->file('mulkiya_image'));
           $rider_detail->mulkiya_image = $filepath;
       }
       $rider_detail->mulkiya_expiry = $request->mulkiya_expiry;
       $rider_detail->save();
        return redirect(route('admin.riders.index'))->with('message', 'Rider created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Rider $rider,Request $request,Rider_detail $rider_detail)
    {    
        $rider_detail=$rider->Rider_detail()->get()->first();
        return view('admin.rider.edit', compact('rider','rider_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rider $rider)
    {
        
        $this->validate($request, [
            'name' => 'required | string | max:255',
            'email' => 'required | email',
            'phone' => 'required | string | max:255',
            'vehicle_number' => 'required | string',
            'address' => 'required | string',
        ]);
        if($request->change_password)
        {
            $this->validate($request, [
                'password' => 'required | string | confirmed',
            ]);
            $rider->password = Hash::make($request->password);
        }
        $rider->name = $request->name;
        $rider->email = $request->email;
        $rider->phone = $request->phone;
        $rider->vehicle_number = $request->vehicle_number;
        $rider->address = $request->address;
        if($request->status)
            $rider->status = 1;
        else
            $rider->status = 0;
        if($request->hasFile('profile_picture'))
        {
            // return 'yes';
            if($rider->profile_picture)
            {
                Storage::delete($rider->profile_picture);
            }
            $filename = $request->profile_picture->getClientOriginalName();
            $filesize = $request->profile_picture->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/profile_pics', $request->file('profile_picture'));
            $rider->profile_picture = $filepath;
        }
        // return 'no';
        $rider->update();
        $rider_detail=$rider->Rider_detail()->get()->first();
        $rider_detail->date_of_joining = $request->date_of_joining;
        $rider_detail->official_given_number = $request->official_given_number;
        $rider_detail->official_sim_given_date = $request->official_sim_given_date;
        if($request->hasFile('passport_image'))
        {
            if($rider_detail->passport_image)
           {
               Storage::delete($rider_detail->passport_image);
           }
            $filename = $request->passport_image->getClientOriginalName();
            $filesize = $request->passport_image->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/passport_image', $request->file('passport_image'));
            $rider_detail->passport_image = $filepath;
        }
       $rider_detail->passport_expiry = $request->passport_expiry;
       if($request->hasFile('visa_image'))
       {
           // return 'yes';
           if($rider_detail->visa_image)
           {
               Storage::delete($rider_detail->visa_image);
           }
           $filename = $request->visa_image->getClientOriginalName();
           $filesize = $request->visa_image->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/visa_image', $request->file('visa_image'));
           $rider_detail->visa_image = $filepath;
       }
       $rider_detail->visa_expiry = $request->visa_expiry;
       if($request->hasFile('licence_image'))
       {
        if($rider_detail->licence_image)
        {
            Storage::delete($rider_detail->licence_image);
        }
           $filename = $request->licence_image->getClientOriginalName();
           $filesize = $request->licence_image->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/licence_image', $request->file('licence_image'));
           $rider_detail->licence_image = $filepath;
       }
       $rider_detail->licence_expiry= $request->licence_expiry;
       if($request->hasFile('mulkiya_image'))
       {
        if($rider_detail->mulkiya_image)
        {
            Storage::delete($rider_detail->mulkiya_image);
        }
           $filename = $request->mulkiya_image->getClientOriginalName();
           $filesize = $request->mulkiya_image->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/mulkiya_image', $request->file('mulkiya_image'));
           $rider_detail->mulkiya_image = $filepath;
       }
       $rider_detail->mulkiya_expiry = $request->mulkiya_expiry;
       $rider_detail->update();
        return redirect(route('admin.riders.index'))->with('message', 'Record Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rider $rider)
    {
        
        if($rider->profile_picture)
        {
            Storage::delete($rider->profile_picture);
        }
        
        $rider->delete();
        $rider_detail=$rider->Rider_detail;
        $rider_detail->delete();
    }
    // public function showMessages(Rider $rider)
    // {

    // }
    // public function loadLocations()
    // {
    //     $riders = Rider::where('status', 1)->get();
    //     return RiderLocationResourceCollection::collection($riders);
    // }
    public function showRiderLocation(Rider $rider)
    {
        return view('admin.rider.location', compact('rider'));
    }
    public function showRiderProfile(Rider $rider)
    {
        return view('admin.rider.profile', compact('rider'));
    }
    
    public function sendSMS(Rider $rider, Request $request)
    {
        $message = new Rider_Message();
        $message->admin_id = Auth::user()->id;
        $message->rider_id = $rider->id;
        $message->message = $request->message;
        $message->save();
        return response()->json([
            'status' => true
        ]);
    }
    public function updateStatus(Rider $rider)
    {
        if($rider->status == 1)
        {
            $rider->status = 0;
        }
        else
        {
            $rider->status = 1;
        }
        $rider->update();
        return response()->json([
            'status' => true
        ]);
    }

    public function showRidesReport(Rider $rider)
    {
        return view('admin.rider.ridesReport', compact('rider'));
    }
    public function deleteRidesReportRecord(Rider_Report $record)
    {
        $record->delete();
    }
    
    public function rider_details(){
        return view('admin.rider.Rider_detail');
    }
public function destroyer(Rider $rider,$id){
    $rider=Rider::find($id);

    return $rider_detail->id;
}

}
