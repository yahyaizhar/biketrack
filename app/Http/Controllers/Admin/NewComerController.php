<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Bikes\bike;
use App\Model\Bikes\bike_detail; 
use App\Model\Client\Client;
use Illuminate\Support\Facades\Hash;
use App\Model\Rider\Rider;
use App\Model\Client\Client_Rider;
use Illuminate\Support\Facades\Storage;
use App\Model\Rider\Rider_Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\New_comer;
use App\GuestNewComer;

class NewComerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function new_comer_form(){
        return view('New_Comer.newcomer_form');
    }
     public function insert_newcomer(Request $request){
        $new_comer=New_comer::create($request->all());
        return redirect(route('NewComer.view'))->with('message', 'Record Added Successfully.');
     }


    public function new_comer_view(){
        $newComer_count=New_comer::all()->count();
        return view('New_Comer.newcomer_view',compact('newComer_count'));
    }
    public function new_comer_approval_view(){
        $newComer_approval_count=GuestNewComer::all()->count();
        return view('New_Comer.newcomer_approval_view',compact('newComer_approval_count'));
    }
    // public function show_approved_customer(){
    //     $show_approved_customer=GuestNewComer::all()->count();
    //     return view('New_Comer.newcomer_approved_view',compact('show_approved_customer'));
    // }
    public function delete_new_comer(Request $request){
        $newComer_id=$request->newComer_id;
        $newcomer=New_comer::find($newComer_id);
        $newcomer->active_status="D";
        $newcomer->update();
        return response()->json([
            'status' => true
        ]);
    }
    public function newComer_edit(Request $request, $id){
        $readonly=false;
        $newComer_id=$request->id;
        $newcomer=New_comer::find($newComer_id);
        return view('New_Comer.newcomer_edit',compact('readonly','newcomer'));
    }
    public function newComer_edit_view(Request $request, $id){
        $readonly=true;
        $newComer_id=$request->id;
        $newcomer=New_comer::find($newComer_id);
        return view('New_Comer.newcomer_edit',compact('readonly','newcomer'));
    }
    public function updateNewComer(Request $request,$id){
        $newComer_id=$request->id;
        $newcomer=New_comer::find($newComer_id);
      $newcomer->name = $request->name;
      $newcomer->nationality = $request->nationality;
      $newcomer->whatsapp_number = $request->whatsapp_number;
      $newcomer->education = $request->education;
      $newcomer->licence_issue_date = $request->licence_issue_date;
      $newcomer->phone_number = $request->phone_number;
      $newcomer->overall_remarks = $request->overall_remarks;
      $newcomer->interview_date = $request->interview_date;
      $newcomer->interview_By = $request->interview_By;
      $newcomer->joining_date = $request->joining_date;
      $newcomer->why_rejected = $request->why_rejected;
      $newcomer->source_of_contact=$request->source_of_contact;
      $newcomer->experiance=$request->experiance;
      $newcomer->passport_status=$request->passport_status;
      $newcomer->interview=$request->interview;
      $newcomer->interview_status=$request->interview_status;
      $newcomer->experience_input=$request->experience_input;
      $newcomer->passport_reason=$request->passport_reason;
      $newcomer->kingriders_interview=$request->kingriders_interview;
      $newcomer->priority=$request->priority;
      $newcomer->update();
     
      return redirect(route('NewComer.view'))->with('message', 'Record Updated Successfully.');
  
        
        
      }
      public function new_comer_approved(Request $request){
        $comer_id = $request->new_commer_id; 
        // return $request->missing_data;
        $a=[];
        if($request->missing_data){
        foreach($request->missing_data as $key => $value) {
            array_push($a,$value);
        }
    }
        $data=implode(",",$a);
        if($request->interview_date){
            $request->interview_status ='pending';
        }
        DB::table('guest_new_comers')->where('id', $comer_id) ->update(['missing_fields'=>$data,'approval_status' => $request->approval_status,'interview_status' => $request->approval_status, 'interview_date' => $request->interview_date , 'interview_status' => $request->interview_status ,'status_approval_message' => $request->status_approval_message]);
      }
      public function add_interview_status(Request $request){
        $comer_id = $request->new_commer_id;
        if($request->interview_status == 'approve'){
        DB::table('guest_new_comers') ->where('id', $comer_id) ->update(['interview_by' => $request->interview_by,'interview_status_message' => $request->interview_status_message,  'interview_status' => $request->interview_status, 'active_status' => '1']);
        }
        else{
            DB::table('guest_new_comers') ->where('id', $comer_id) ->update(['interview_by' => $request->interview_by,'interview_status_message' => $request->interview_status_message,  'interview_status' => $request->interview_status, 'active_status' => '0']);

        }
      }

      public function add_interview_date(Request $request){
        $comer_id = $request->new_commer_id;
        DB::table('guest_new_comers') ->where('id', $comer_id) ->update(['interview_status' => 'pending', 'interview_date' => $request->interview_date]);
      }
      public function newComer_popup($id){
          $newComer=New_comer::find($id);
         
          return view('New_Comer.popup_newComer',compact('newComer'));
      }
}
