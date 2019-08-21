<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Rider\Rider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\RiderLocationResourceCollection;
use App\Model\Rider\Rider_Message;
use App\Model\Rider\Rider_Performance_Zomato;
use Illuminate\Support\Facades\Auth;
use App\Model\Rider\Rider_Report;
use App\Model\Rider\Rider_detail;
use App\Model\Bikes\bike;
use App\Model\Sim\Sim;
use App\Model\Sim\Sim_Transaction;
use App\Model\Sim\Sim_History;
use App\Model\Client\Client_Rider;
use Illuminate\Support\Arr;
use Batch;


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
        $rider_count=Rider::all()->count();
        return view('admin.rider.riders',compact('rider_count'));
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

    public function update_ClientRiders(Request $req){
        $client_rider=Client_Rider::where('client_id',$req->client_id)->where('rider_id',$req->rider_id)->get()->first();
        $client_rider->client_rider_id=$req->client_rider_id;
        $client_rider->update();
        return $client_rider;
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
            'start_time' => 'required | string',
            'end_time' => 'required | string',
            'break_start_time' => 'required | string',
            'break_end_time' => 'required | string',
            'address' => 'required | string',
        ]);
        $rider = new Rider();
        $rider->name = $request->name;
        $rider->email = $request->email;
        $rider->phone = $request->phone;
        $rider->date_of_birth = $request->date_of_birth;
        $rider->password = Hash::make($request->password);
        $rider->address = $request->address;
        $rider->start_time = $request->start_time;
        $rider->end_time = $request->end_time;
        $rider->break_start_time = $request->break_start_time;
        $rider->break_end_time = $request->break_end_time;
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
       $rider_detail->other_details = $request->other_details;
       $rider_detail->emirate_id = $request->emirate_id;
       $rider_detail->empoloyee_reference = $request->empoloyee_reference;
       $rider_detail->other_passport_given = $request->other_passport_given;
       $rider_detail->not_given = $request->not_given;
       if($request->passport_collected)
       $rider_detail->passport_collected = 'yes';
   else
       $rider_detail->passport_collected = 'no';
       
       if($request->hasFile('passport_image'))
        {
            // return 'yes';
            $filename = $request->passport_image->getClientOriginalName();
            $filesize = $request->passport_image->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/passport_image', $request->file('passport_image'));
            $rider_detail->passport_image = $filepath;
        }
        if($request->hasFile('passport_image_back'))
        {
            // return 'yes';
            $filename = $request->passport_image_back->getClientOriginalName();
            $filesize = $request->passport_image_back->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/passport_image_back', $request->file('passport_image_back'));
            $rider_detail->passport_image_back = $filepath;
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
       if($request->hasFile('visa_image_back'))
       {
           // return 'yes';
           $filename = $request->visa_image_back->getClientOriginalName();
           $filesize = $request->visa_image_back->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/visa_image_back', $request->file('visa_image_back'));
           $rider_detail->visa_image_back = $filepath;
       }
       $rider_detail->visa_expiry = $request->visa_expiry;
       if($request->hasFile('emirate_image'))
       {
           // return 'yes';
           $filename = $request->emirate_image->getClientOriginalName();
           $filesize = $request->emirate_image->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/emirate_image', $request->file('emirate_image'));
           $rider_detail->emirate_image = $filepath;
       }
       if($request->hasFile('emirate_image_back'))
       {
           // return 'yes';
           $filename = $request->emirate_image_back->getClientOriginalName();
           $filesize = $request->emirate_image_back->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/emirate_image_back', $request->file('emirate_image_back'));
           $rider_detail->emirate_image_back = $filepath;
       }
       if($request->hasFile('licence_image'))
       {
           // return 'yes';
           $filename = $request->licence_image->getClientOriginalName();
           $filesize = $request->licence_image->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/licence_image', $request->file('licence_image'));
           $rider_detail->licence_image = $filepath;
       }
       if($request->hasFile('licence_image_back'))
       {
           // return 'yes';
           $filename = $request->licence_image_back->getClientOriginalName();
           $filesize = $request->licence_image_back->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/licence_image_back', $request->file('licence_image_back'));
           $rider_detail->licence_image_back = $filepath;
       }
       $rider_detail->licence_expiry= $request->licence_expiry;
       
      
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
     * Import zomato data
     *
     * @param  Request $r
     * @return \Illuminate\Http\Response
     */
    public function import_zomato(Request $r)
    {
        $data = $r->data;
        $zomato_objects=[];
        $zp = Rider_Performance_Zomato::all(); // r1
        $update_data = [];
        $i=0;
        $unique_id=uniqid().'-'.time();
        foreach ($data as $item) {
            $i++;
            $zp_found = Arr::first($zp, function ($item_zp, $key) use ($item) {
                return $item_zp->date == $item['date'] && $item_zp->feid==$item['feid'];
            });
            if(!isset($zp_found)){
                $obj = [];
                $obj['import_id']=$unique_id;
                $obj['date']=isset($item['date'])?$item['date']:null;
                $obj['feid']=isset($item['feid'])?$item['feid']:null;
                $obj['adt']=isset($item['adt'])?$item['adt']:null;
                $obj['trips']=isset($item['trips'])?$item['trips']:null;
                $obj['average_pickup_time']=isset($item['average_pick_up_time'])?$item['average_pick_up_time']:null; 
                $obj['average_drop_time']=isset($item['average_drop_time'])?$item['average_drop_time']:null;
                $obj['loged_in_during_shift_time']=isset($item['logged_in_during_shift_time'])?$item['logged_in_during_shift_time']:null;
                $obj['total_loged_in_hours']=isset($item['total_log_in_hrs'])?$item['total_log_in_hrs']:null;
                $obj['cod_orders']=isset($item['cod_orders'])?$item['cod_orders']:null;
                $obj['cod_amount']=isset($item['cod_amount'])?$item['cod_amount']:null;
                $obj['rider_id']=isset($item['rider_id'])?$item['rider_id']:null;
                array_push($zomato_objects, $obj);
            }
            else{
                $objUpdate = [];
                $objUpdate['id']=$zp_found->id;
                $objUpdate['import_id']=$unique_id;
                $objUpdate['date']=isset($item['date'])?$item['date']:null;
                $objUpdate['feid']=isset($item['feid'])?$item['feid']:null;
                $objUpdate['adt']=isset($item['adt'])?$item['adt']:null;
                $objUpdate['trips']=isset($item['trips'])?$item['trips']:null;
                $objUpdate['average_pickup_time']=isset($item['average_pick_up_time'])?$item['average_pick_up_time']:null; 
                $objUpdate['average_drop_time']=isset($item['average_drop_time'])?$item['average_drop_time']:null;
                $objUpdate['loged_in_during_shift_time']=isset($item['logged_in_during_shift_time'])?$item['logged_in_during_shift_time']:null;
                $objUpdate['total_loged_in_hours']=isset($item['total_log_in_hrs'])?$item['total_log_in_hrs']:null;
                $objUpdate['cod_orders']=isset($item['cod_orders'])?$item['cod_orders']:null;
                $objUpdate['cod_amount']=isset($item['cod_amount'])?$item['cod_amount']:null;
                $objUpdate['rider_id']=isset($item['rider_id'])?$item['rider_id']:null;
                array_push($update_data, $objUpdate);
            }
        }
        DB::table('rider__performance__zomatos')->insert($zomato_objects); //r2
        $data=Batch::update(new Rider_Performance_Zomato, $update_data, 'id'); //r3
        return response()->json([
            'data'=>$zomato_objects,
            'count'=>$i
        ]);
    }
   

    public function delete_lastImport(){
        $import_id=Rider_Performance_Zomato::all()->last()->import_id;
        $performances=Rider_Performance_Zomato::where('import_id',$import_id)->get();
        foreach($performances as $performance)
            {
                $performance->delete();
            }

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
        $sim_date=$rider->Sim_History()->where('status','active')->get()->first();
        $sim_number=null;
        if(isset($sim_date)){
            $sim_number=$sim_date->Sim()->get()->first();
        }
        return view('admin.rider.edit', compact('rider','rider_detail','sim_date','sim_number'));
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
            'start_time' => 'required | string',
            'end_time' => 'required | string',
            'break_start_time' => 'required | string',
            'break_end_time' => 'required | string',
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
        $rider->date_of_birth = $request->date_of_birth;
        $rider->address = $request->address;
        $rider->start_time = $request->start_time;
        $rider->end_time = $request->end_time;
        $rider->break_start_time = $request->break_start_time;
        $rider->break_end_time = $request->break_end_time;
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
        $rider_detail->other_details = $request->other_details;
        $rider_detail->emirate_id = $request->emirate_id;
        $rider_detail->empoloyee_reference = $request->empoloyee_reference;
        $rider_detail->other_passport_given = $request->other_passport_given;
        $rider_detail->not_given = $request->not_given;
        if($request->passport_collected)
        $rider_detail->passport_collected = 'yes';
    else
        $rider_detail->passport_collected = 'no';
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
        if($request->hasFile('passport_image_back'))
        {
            if($rider_detail->passport_image_back)
           {
               Storage::delete($rider_detail->passport_image_back);
           }
            $filename = $request->passport_image_back->getClientOriginalName();
            $filesize = $request->passport_image_back->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/passport_image_back', $request->file('passport_image_back'));
            $rider_detail->passport_image_back = $filepath;
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
       if($request->hasFile('visa_image_back'))
       {
           // return 'yes';
           if($rider_detail->visa_image_back)
           {
               Storage::delete($rider_detail->visa_image_back);
           }
           $filename = $request->visa_image_back->getClientOriginalName();
           $filesize = $request->visa_image_back->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/visa_image_back', $request->file('visa_image_back'));
           $rider_detail->visa_image_back = $filepath;
       }
       $rider_detail->visa_expiry = $request->visa_expiry;
       if($request->hasFile('emirate_image'))
       {
           // return 'yes';
           if($rider_detail->emirate_image)
           {
               Storage::delete($rider_detail->emirate_image);
           }
           $filename = $request->emirate_image->getClientOriginalName();
           $filesize = $request->emirate_image->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/emirate_image', $request->file('emirate_image'));
           $rider_detail->emirate_image = $filepath;
       }
       if($request->hasFile('emirate_image_back'))
       {
           // return 'yes';
           if($rider_detail->emirate_image_back)
           {
               Storage::delete($rider_detail->emirate_image_back);
           }
           $filename = $request->emirate_image_back->getClientOriginalName();
           $filesize = $request->emirate_image_back->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/emirate_image_back', $request->file('emirate_image_back'));
           $rider_detail->emirate_image_back = $filepath;
       }
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
       if($request->hasFile('licence_image_back'))
       {
        if($rider_detail->licence_image_back)
        {
            Storage::delete($rider_detail->licence_image_back);
        }
           $filename = $request->licence_image_back->getClientOriginalName();
           $filesize = $request->licence_image_back->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/licence_image_back', $request->file('licence_image_back'));
           $rider_detail->licence_image_back = $filepath;
       }
       $rider_detail->licence_expiry= $request->licence_expiry;
       
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
        
        // if($rider->profile_picture)
        // {
        //     Storage::delete($rider->profile_picture);
        // }
        $rider->active_status="D";
        $rider->status='0';
        $rider->update();

        $rider_detail=$rider->Rider_detail;
        $rider_detail->active_status="D";
        $rider_detail->update();
        
        $clients = $rider->clients()->detach();

        $assign_bike=$rider->Assign_bike()->where('status','active')->get()->first();
        if(isset($assign_bike)){
            $assign_bike->status='deactive';
            $assign_bike->update();
            $bike=bike::find($assign_bike->bike_id);
            $bike->availability='yes';
            $bike->update();
        }     
        $sim_history=$rider->Sim_History()->where('status','active')->get()->first();
        if(isset($sim_history)){
            $sim_history->status='deactive';
            $sim_history->update();
        }
        
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
        $rider_details=$rider->Rider_detail()->get()->first();
        $assign_bike=$rider->Assign_bike()->where('status','active')->get()->first();
        $bike=[];
        if(isset($assign_bike)){
        $bike=bike::find($assign_bike->bike_id);
        }     
        $sim_history=$rider->Sim_History()->where('status','active')->get()->first();
        $sim=null;
        if(isset($sim_history)){
        $sim=Sim::find($sim_history->sim_id);
        }
        return view('admin.rider.profile', compact('rider','rider_details','bike','sim','sim_history'));
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
        $record->active_status="D";
        $record->update();
    }
    
    public function rider_details(){
        return view('admin.rider.Rider_detail');
    }
public function destroyer(Rider $rider,$id){
    $rider=Rider::find($id);

    return $rider_detail->id;
}
  
   public function RiderPerformance(){
        $performance_count=Rider_Performance_Zomato::all()->count();
        return view('admin.rider.rider_performance',compact('performance_count'));
   }
   public function getRider_active(){
    $rider_count=Rider::where('active_status','A')->get()->count();
    return view('admin.rider.active_riders',compact('rider_count'));
   }

}
