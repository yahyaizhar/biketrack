<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\GuestNewComer;

class GuestController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function newComer_view(){
        return view('guest.guest_newcomer');
    }
    public function newComer_add(Request $req){
      $_check_id_card = GuestNewComer::where('national_id_card_number', $req->national_id_card_number)->get()->first();
      if(!isset($_check_id_card)){
      $new_commer = new GuestNewComer;
      if($req->hasFile('newcommer_image'))
      {
          $filename = $req->newcommer_image->getClientOriginalName();
          $filesize = $req->newcommer_image->getClientSize();
          $filepath = Storage::putfile('public/uploads/riders/new_commer_pics', $req->file('newcommer_image'));
          $new_commer->newcommer_image = $filepath;
      }
      if($req->hasFile('passport_image'))
      {
          $filename = $req->passport_image->getClientOriginalName();
          $filesize = $req->passport_image->getClientSize();
          $filepath = Storage::putfile('public/uploads/riders/new_commer_pics', $req->file('passport_image'));
          $new_commer->passport_image = $filepath;
      }
      if($req->hasFile('license_image'))
      {
          $filename = $req->license_image->getClientOriginalName();
          $filesize = $req->license_image->getClientSize();
          $filepath = Storage::putfile('public/uploads/riders/new_commer_pics', $req->file('license_image'));
          $new_commer->license_image = $filepath;
      }
      $new_commer->full_name = $req->full_name;
      $new_commer->nationality = $req->nationality;
      $new_commer->phone_number = $req->phone_number;
      $new_commer->national_id_card_number = $req->national_id_card_number;
      $new_commer->whatsapp_number = $req->whatsapp_number;
      $new_commer->education = $req->education;
      $new_commer->license_check = $req->license_check;
      $new_commer->license_number = $req->license_number;
      $new_commer->licence_issue_date = $req->licence_issue_date;
      $new_commer->experiance = $req->experiance;
      $new_commer->passport_status = $req->passport_status;
      $new_commer->passport_number = $req->passport_number;
      $new_commer->current_residence = $req->current_residence;
      if($new_commer->current_residence =="uae"){
        $new_commer->current_residence_countries = "United Arab Emirates";
      }else{
      $new_commer->current_residence_countries = $req->current_residence_countries;
       }
      $new_commer->source = $req->source;
      $new_commer->overall_remarks = $req->overall_remarks;
      $new_commer->save();
      return redirect(url('/guest/newcomer/add'))->with('message', 'Record Created Successfully.');
      }
      else{
        return redirect(url('/guest/newcomer/add'))->with('message', 'You have already registred.');
      }
    }

    public function newComer_status(Request $request){
      $national_id_card_no = $request->national_id_card_no;
      $data = DB::table('guest_new_comers') ->where('national_id_card_number', $national_id_card_no) ->get();

      if($data->count() > 0){
        return $data;
      }
      else{
        return 'error';
      }
    }
    
}
