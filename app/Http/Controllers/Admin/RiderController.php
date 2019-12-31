<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Rider\Rider;
use App\Model\Client\Client_History;
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
use App\Model\Client\Client;
use Illuminate\Support\Arr;
use Batch;
use Carbon\Carbon;
use App\Assign_bike;

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

    public function update_ClientRiders($rider_id,Request $req){
        $client_rider=Client_Rider::where('client_id',$req->client_id)->where('rider_id',$rider_id)->get()->first();
        if (isset($client_rider)) {
            $client_rider->client_rider_id=$req->client_rider_id;
            $client_rider->update();
        }
        $client_rider_history=Client_History::where('client_id',$req->client_id)->where('rider_id',$rider_id)->get()->first();
        $client_rider_history->client_rider_id=$req->client_rider_id;
        $client_rider_history->update();
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
            // 'phone' => 'required | string | max:255',
            'password' => 'required | string | confirmed',
        ]);
        $rider = new Rider();
        $rider->name = $request->name;
        $rider->email = $request->email;
        $rider->phone = $request->phone;
        $rider->date_of_birth = $request->date_of_birth;
        $rider->password = Hash::make($request->password);
        $rider->kingriders_id = $request->kingriders_id;
        $rider->address = $request->address;
        $rider->start_time = $request->start_time;
        $rider->end_time = $request->end_time;
        $rider->break_start_time = $request->break_start_time;
        $rider->break_end_time = $request->break_end_time;
        $rider->active_month = Carbon::parse($request->active_month)->format('Y-m-d');
        $rider->status = 1;
        $ca=json_decode($rider->spell_time,true);
        if (!isset($ca)) {
            $ca=[];
        }
        $obj=[];
        $obj['start_time']=Carbon::now()->format('d-m-Y');
        $obj['end_time']="";
        array_push($ca, $obj);
        $rider->spell_time=json_encode($ca);

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
       if ($request->date_of_joining!=null) {
        $rider_detail->date_of_joining = Carbon::parse($request->date_of_joining)->format('Y-m-d');
       }
       $rider_detail->official_given_number = $request->official_given_number;
       $rider_detail->official_sim_given_date = $request->official_sim_given_date;
       $rider_detail->other_details = $request->other_details;
       $rider_detail->emirate_id = $request->emirate_id;
       $rider_detail->salary = $request->salary;
       $rider_detail->is_guarantee = $request->is_guarantee;
       $rider_detail->empoloyee_reference = $request->empoloyee_reference;
       $rider_detail->salik_amount = $request->salik_amount;
       $rider_detail->other_passport_given = $request->other_passport_given;
       $rider_detail->not_given = $request->not_given;
    
       if($request->passport_collected)
       $rider_detail->passport_collected = 'yes';
   else
       $rider_detail->passport_collected = 'no';
       if($request->hasFile('passport_document_image'))
       {
           // return 'yes';
           $filename = $request->passport_document_image->getClientOriginalName();
           $filesize = $request->passport_document_image->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/passport_document_image', $request->file('passport_document_image'));
           $rider_detail->passport_document_image = $filepath;
       }
       if($request->hasFile('agreement_image'))
       {
           // return 'yes';
           $filename = $request->agreement_image->getClientOriginalName();
           $filesize = $request->agreement_image->getClientSize();
           // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
           $filepath = Storage::putfile('public/uploads/riders/agreement_image', $request->file('agreement_image'));
           $rider_detail->agreement_image = $filepath;
       }



       
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
        if ($request->passport_expiry!=null) {
            $rider_detail->passport_expiry = Carbon::parse($request->passport_expiry)->format('Y-m-d');
        }
       
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
       if ($request->visa_expiry!=null) {
        $rider_detail->visa_expiry = Carbon::parse($request->visa_expiry)->format('Y-m-d');
       }
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
       if ($request->licence_expiry!=null) {
        $rider_detail->licence_expiry= Carbon::parse()->format('Y-m-d');
       }
       
       
      
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
            if(isset($item['date'])){
            $date=Carbon::parse($item['date'])->format('Y-m-d');
             }
            if(!isset($zp_found)){
                $obj = [];
                $obj['import_id']=$unique_id;
                $obj['date']=isset($item['date'])?$date:null;
                $obj['area']=isset($item['area'])?$item['area']:null;
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
                $objUpdate['date']=isset($item['date'])?$date:null;
                $objUpdate['area']=isset($item['area'])?$item['area']:null;
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
    public function edit(Rider $rider,Request $request,Rider_detail $rider_detail,$id)
    {    
        $rider=Rider::find($id);
        if(isset($rider)){
            $rider_detail=$rider->Rider_detail()->get()->first();
        }        
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
            // 'phone' => 'required | string | max:255',
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
        $rider->kingriders_id = $request->kingriders_id;
        $rider->start_time = $request->start_time;
        $rider->end_time = $request->end_time;
        $rider->break_start_time = $request->break_start_time;
        $rider->break_end_time = $request->break_end_time;
        $rider->active_month = Carbon::parse($request->active_month)->format('Y-m-d');
        // if($request->status){
        //     $rider->status = 1;
        //     }
        // else{
        //     $rider->status = 0;
        // }
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
        $rider_detail->date_of_joining = Carbon::parse($request->date_of_joining)->format('Y-m-d');
        $rider_detail->official_given_number = $request->official_given_number;
        $rider_detail->official_sim_given_date = $request->official_sim_given_date;
        $rider_detail->other_details = $request->other_details;
        $rider_detail->emirate_id = $request->emirate_id;
        $rider_detail->salary = $request->salary;
        $rider_detail->is_guarantee = $request->is_guarantee;
        $rider_detail->salik_amount = $request->salik_amount;
        $rider_detail->empoloyee_reference = $request->empoloyee_reference;
        $rider_detail->other_passport_given = $request->other_passport_given;
        $rider_detail->not_given = $request->not_given;
        
        if($request->passport_collected)
        $rider_detail->passport_collected = 'yes';
    else
        $rider_detail->passport_collected = 'no';
        if($request->hasFile('passport_document_image'))
        {
            if($rider_detail->passport_document_image)
           {
               Storage::delete($rider_detail->passport_document_image);
           }
            $filename = $request->passport_document_image->getClientOriginalName();
            $filesize = $request->passport_document_image->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/passport_document_image', $request->file('passport_document_image'));
            $rider_detail->passport_document_image = $filepath;
        }  
        if($request->hasFile('agreement_image'))
        {
            if($rider_detail->agreement_image)
           {
               Storage::delete($rider_detail->agreement_image);
           }
            $filename = $request->agreement_image->getClientOriginalName();
            $filesize = $request->agreement_image->getClientSize();
            // $filepath = $request->profile_picture->storeAs('public/uploads/riders/profile_pics', $filename);
            $filepath = Storage::putfile('public/uploads/riders/agreement_image', $request->file('agreement_image'));
            $rider_detail->agreement_image = $filepath;
        }



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
       $rider_detail->passport_expiry = Carbon::parse($request->passport_expiry)->format('Y-m-d');
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
       $rider_detail->visa_expiry = Carbon::parse($request->visa_expiry)->format('Y-m-d');
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
       $rider_detail->licence_expiry= Carbon::parse($request->licence_expiry)->format('Y-m-d');
       
       $rider_detail->update();
    
       return redirect(route('admin.rider.profile',$rider->id))->with('message', 'Record Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rider $rider)
    {
        $rider_id=$rider->id;
        $client_id=$rider->clients()->get()->first();
        $client_history=Client_History::where('rider_id', $rider_id)
        ->where('client_id', $client_id->id)
        ->where("status","active")
        ->get()
        ->first();
        $client_history->status="deactive";
        $client_history->deassign_date=Carbon::now()->format("Y-m-d");
        
        $client_history->save();
        
        $clients = $rider->clients()->detach();

        $assign_bike=$rider->Assign_bike()->where('status','active')->get()->first();
        if(isset($assign_bike)){
            $assign_bike->status='deactive';
            $assign_bike->bike_unassign_date=Carbon::now()->format("Y-m-d");
            $assign_bike->update();
            $bike=bike::find($assign_bike->bike_id);
            $bike->availability='yes';
            $bike->update();
        }     
        $sim_history=$rider->Sim_History()->where('status','active')->get()->first();
        if(isset($sim_history)){
            $sim_history->status='deactive';
            $sim_history->return_date=Carbon::now()->format("Y-m-d");
            $sim_history->update();
        }

        $rider->active_status='D';
        $rider->update();

        $rider_detail=$rider->Rider_detail;
        $rider_detail->active_status='D';
        $rider_detail->update();
        return redirect(url('admin/riders'))->with('message', 'Record Deleted Successfully.');
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
        $bike_html=$rider->Assign_bike()->get()->first();
        $sim_history_change = $rider->Sim_history()->where('status', 'active')->get()->first();
        return view('admin.rider.profile', compact('rider','sim_history_change','rider_details','bike','sim','sim_history','bike_html','assign_bike'));
    }
    public function showRiderAccount(Rider $rider)
    {

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
    public function updateStatus(Rider $rider,Request $req)
    {
        $r_active_month=$rider->active_month; 
        if($rider->status == 1)
        {
            // inactive
            $rider->inactive_month=Carbon::parse($req->inactive_month)->format('Y-m-d');
            $rider->inactive_reason=$req->inactive_reason;
            $assign_bike=Assign_bike::where("rider_id",$rider->id)->where("status","active")->get()->first();
            if (isset($assign_bike)) {
                $assign_bike->status="deactive";
                $assign_bike->bike_unassign_date=Carbon::parse($req->inactive_month)->format('Y-m-d');
                $assign_bike->update();
                $bike=bike::find($assign_bike->bike_id);
                if (isset($bike)) {
                    $bike->availability="yes";
                    $bike->update();
                }
            }
           
            $sim_history=Sim_History::where("rider_id",$rider->id)->where("status","active")->get()->first();
            if (isset($sim_history)) {
                $sim_history->status="deactive";
                $sim_history->return_date=Carbon::parse($req->inactive_month)->format("Y-m-d");
                $sim_history->update();
            }
            
            $client=$rider->clients()->get()->first();
            if (isset($client)) {
                $client_id=$client->id;
                $client_history=Client_History::where('rider_id', $rider->id)
                ->where('client_id', $client_id)
                ->where("status","active")
                ->get()
                ->first();
                $client_history->status="deactive";
                $client_history->deassign_date=Carbon::parse($req->inactive_month)->format("Y-m-d");
                $client_history->save();
            }
          
            
            $client=$rider->clients()->detach();

            $rider->status = 0;
            if (isset($rider->spell_time)) {
                $ca=json_decode($rider->spell_time,true);
                $ca_key=null;
                $date = Arr::first($ca, function ($item, $key) use (&$ca_key){
                    $ca_key=$key;
                    return isset($item['end_time']) && $item['end_time'] == "" ;
                }); 
                if(isset($date)){
                    $ca[$ca_key]['end_time']=Carbon::now()->format('d-m-Y');
                }
                $rider->spell_time=json_encode($ca);
            }
        }
        else
        {
            //active

                $rider->status = 1;
                $ca=json_decode($rider->spell_time,true);
                if (!isset($ca)) {
                   $ca=[];
                }
                $obj=[];
                $obj['start_time']=Carbon::now()->format('d-m-Y');
                $obj['end_time']="";
                array_push($ca, $obj);
                $rider->spell_time=json_encode($ca);
        }
        $rider->update();
        return response()->json([
            'status' => true,
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
  
   public function RiderPerformance(){
        $performance_count=Rider_Performance_Zomato::all()->count();
        return view('admin.rider.rider_performance',compact('performance_count'));
   }
   public function Rider_Range_ADT(Request $r){
       $ZAD=[];
    $performance=Rider_Performance_Zomato::distinct('import_id')->pluck('import_id');
    foreach ($performance as $data_distinct) {
        $data=Rider_Performance_Zomato::where('import_id',$data_distinct)->get();
        $obj=[];
        $obj['r1']=$data->max('date');
        $obj['r2']=$data->min('date');
        $obj['import_id']=$data_distinct;
        $isFound = false;
        for ($i=0; $i <count($ZAD) ; $i++) { 
            if ($ZAD[$i]['r1'] == $obj['r1'] && $ZAD[$i]['r2'] == $obj['r2']) {
                $isFound = true;
                break;
            }
        }
        if(!$isFound){
            array_push($ZAD,$obj);
        }
    }
    return view('admin.rider.rider_ranges_adt',compact('ZAD'));
   }
   public function update_extra_adt(Request $request,$feid,$start_date,$end_date){
    $from = date($start_date);
    $to = date($end_date);
      $zomato=Rider_Performance_Zomato::where("feid",$feid)
      ->whereBetween('date',[$from,$to])
      ->get()
      ->first();
      $zomato->called_over=$request->called_over;
      $zomato->status=$request->status;
      $zomato->comments=$request->comments;
      $zomato->save();
    return response()->json([
        'status' => $zomato,
    ]);
   }
   public function getRider_active(){
    $rider_count=Rider::where('active_status','A')->get()->count();
    return view('admin.rider.active_riders',compact('rider_count'));
   }
   public function client_history($id){
       $rider=Rider::find($id);
       $clients=Client_History::where('rider_id', $id)->orderByDesc('id')->get();
       return view('admin.rider.client_history',compact('rider','clients'));
   }
   public function Spell_time($id){
    $rider=Rider::find($id);
    $dates=json_decode($rider->spell_time,true);
    return view('admin.rider.rider_spell_time',compact('dates','rider'));
   }

}
