<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\GuestNewComer;
use App\Model\Rider\Rider_detail;
use App\Model\Accounts\Rider_salary;
use App\Model\Rider\Rider;
use Illuminate\Support\Arr;
use Batch;
use Carbon\Carbon;
use App\Model\Accounts\Income_zomato;

class GuestController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function newComer_view(){
        return view('guest.guest_newcomer');
    }
    public function newComer_edit($id){
      $newcomer_data = GuestNewComer::find($id);
      return view('guest.guest_newcomer_edit',compact('newcomer_data'));
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
        if($req->hasFile('newcommer_image2'))
        {
            $filename = $req->newcommer_image2->getClientOriginalName();
            $filesize = $req->newcommer_image2->getClientSize();
            $filepath = Storage::putfile('public/uploads/riders/new_commer_pics', $req->file('newcommer_image2'));
            $new_commer->newcommer_image2 = $filepath;
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
          if($req->hasFile('license_image2'))
        {
            $filename = $req->license_image2->getClientOriginalName();
            $filesize = $req->license_image2->getClientSize();
            $filepath = Storage::putfile('public/uploads/riders/new_commer_pics', $req->file('license_image2'));
            $new_commer->license_image2 = $filepath;
        
        }
        $new_commer->applying_for = $req->applying_for;
        $new_commer->email = $req->email;
        $new_commer->have_bike = $req->have_bike;
        $new_commer->full_name = $req->full_name;
        $new_commer->nationality = $req->nationality;
        $new_commer->phone_number = $req->phone_number;
        $new_commer->national_id_card_number = $req->national_id_card_number;
        $new_commer->whatsapp_number = $req->whatsapp_number;
        $new_commer->education = $req->education;
        $new_commer->visa_status = $req->visa_status;
        $new_commer->noc_status = $req->noc_status;
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
    public function newComer_store(Request $req,$id){
      // return $id;
      $new_commer = GuestNewComer::where('id', $id)->first();
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
        if(isset($req->email)){
        $new_commer->email = $req->email;
        }
        if(isset($req->phone_number)){
        $new_commer->phone_number = $req->phone_number;
        }
        if(isset($req->national_id_card_number)){
        $new_commer->national_id_card_number = $req->national_id_card_number;
        }
        if(isset($req->whatsapp_number)){
        $new_commer->whatsapp_number = $req->whatsapp_number;
        }
        if(isset($req->license_number)){
        $new_commer->license_number = $req->license_number;
        }
        if(isset($req->licence_issue_date)){
        $new_commer->licence_issue_date = $req->licence_issue_date;
        }
        if(isset($req->passport_number)){
        $new_commer->passport_number = $req->passport_number;
        }
        // $new_commer->missing_fields = "";
        $new_commer->save();
        return redirect(url('/guest/newcomer/add'))->with('message', 'Record Updated Successfully.');
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
    public function show_salary_slips(){
      return view('guest.rider_salary_slips');
    }
    public function get_slip_attendence(Request $request){
      $rider_id=null;
      $rider_name=null;
      $date_of_joining=null;
      $ncw=0;
      $tip=0;
      $bike_allowns=0;
      $bonus=0;
      $bike_fine=0;
      $advance=0;
      $salik=0;
      $sim=0;
      $denial_penalty=0;
      $dc=0;
      $macdonald=0;
      $rta=0;
      $mobile=0;
      $dicipline=0;
      $mics=0;
      $cash_paid_in_advance=0;
      $salary=0;
      $trips=0;
      $hours=0;
      $extra_trips=0;
      $salary_paid=0;
      $show_salaryslip=0;
      $show_attendanceslip=0;
      $month_salaryslip=0;
      $isshow=true;
      $month=0;
      $TimeSheet=null;
      $expiry_month="";
      $closing_balance_prev=0;

      

      $rider_detail=Rider_detail::where("emirate_id",$request->emirate_id)->get()->first();
      if (!isset($rider_detail)) {
        return response()->json([
          'status'=>0,
          'msg'=> 'No Rider found against this Emirate ID'
        ]);
      }
      if (isset($rider_detail)) {
        $is_show_salaryslip=$rider_detail->show_salaryslip;
        $is_show_attendanceslip=$rider_detail->show_attendanceslip;
        $dmy="";
        if ($is_show_salaryslip=='1' || $is_show_attendanceslip=='1') {
          if($is_show_salaryslip=='1') $show_salaryslip=1;
          if($is_show_attendanceslip=='1') $show_attendanceslip=1;
          
          $dmy=$rider_detail->salaryslip_month;
          $expiry_month=Carbon::parse($rider_detail->salaryslip_expiry);
          $current_date=Carbon::parse(Carbon::now()->format("Y-m-d"));
          if ($current_date->greaterThan($expiry_month)) {
            $show_salaryslip=0;
            $show_attendanceslip=0;
          }
        }
        $onlyMonth = Carbon::parse($rider_detail->salaryslip_month)->format('m');
        $onlyYear = Carbon::parse($rider_detail->salaryslip_month)->format('Y');
        $salary_generated = Rider_salary::where('rider_id',$rider_detail->rider_id)
        ->whereMonth("month",$onlyMonth)
        ->whereYear("month",$onlyYear)
        ->get()
        ->first();
        if(!isset($salary_generated)){
          return response()->json([
            'status'=>0,
            'msg'=> 'Salary is not generated yet'
          ]);
        }
        if($show_salaryslip==0 && $show_attendanceslip==0){
          return response()->json([
            'status'=>0,
            'msg'=> 'No data found against this Emirate ID'
          ]);
        }
        $rider_id=$rider_detail->rider_id;
        $rider=Rider::find($rider_id);
        $rider_name=$rider->name;
        $date_of_joining=$rider_detail->date_of_joining;

        $month=Carbon::parse($dmy)->format("Y-m-d");
        $from =Carbon::parse($month)->startOfMonth()->format("Y-m-d");
        $to =Carbon::parse($month)->endOfMonth()->format("Y-m-d");

         //prev payables
         $rider_debits_cr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
         ->where(function($q) {
             $q->where('type', "cr");
         })
         ->whereDate('month', '<',$from)
         ->sum('amount');
         
         $rider_debits_dr_prev_payable = \App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
         ->where(function($q) {
             $q->where('type', "cr_payable")
               ->orWhere('type', 'dr');
         })
         ->whereDate('month', '<',$from)
         ->sum('amount');
         $closing_balance_prev = round($rider_debits_cr_prev_payable - $rider_debits_dr_prev_payable,2);
         //ends prev payables

        $salary=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','salary')
        ->where('payment_status','pending')
        ->sum('amount');
        $hour=Income_zomato::where("rider_id",$rider_id)
        ->whereDate('date', '>=',$from)
        ->whereDate('date', '<=',$to)
        ->first();
        if (isset($hour)) {
            $absent_days=$hour->absents_count;
            $absent_hours=$absent_days*11;
            
            $work_days=$hour->working_days;
            $workable_hours=$work_days*11;

            $calculate_hour=$hour->calculated_hours;
            
            $total_hours=$workable_hours -  $calculate_hour;

            $hours=286 - $absent_hours - $total_hours; 
        }

        $trips=Income_zomato::where("rider_id",$rider_id)
        ->whereDate('date', '>=',$from)
        ->whereDate('date', '<=',$to)
        ->sum('trips_payable');
        if ( $trips > 400) $trips=400; 
        $extra_trips=Income_zomato::where("rider_id",$rider_id)
        ->whereDate('date', '>=',$from)
        ->whereDate('date', '<=',$to)
        ->sum('trips_payable');
        if ( $extra_trips > 400){
            $extra_trips=$extra_trips-400; 
        }
        else{
            $extra_trips=0;
        }

        $ncw=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','NCW Incentives')
        ->sum('amount');
        $tip=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Tips Payouts')
        ->sum('amount');
        $bike_allowns=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Bike Allowns')
        ->sum('amount');
        $bonus=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','400 Trips Acheivement Bonus')
        ->sum('amount');

        $bike_fine=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Bike Fine Paid')
        ->sum('amount');
        $advance=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','advance')
        ->sum('amount');
        $salik=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Salik')
        ->sum('amount');
        $sim=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Sim extra usage')
        ->sum('amount');
        $denial_penalty=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Denials Penalty')
        ->sum('amount');
        $dc=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','DC Deductions')
        ->sum('amount');
        $macdonald=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Mcdonalds Deductions')
        ->sum('amount');
        $rta=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->whereNotNull('id_charge_id')
        ->sum('amount');
        $mobile=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Mobile Installment')
        ->sum('amount');
        $dicipline=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where(function($q) {
            $q->where('source','Discipline Fine')
             ->orWhereNotNull('kingrider_fine_id');
        })
        ->sum('amount');
        $mics=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','Visa Charges')
        ->where("payment_status","paid")
        ->sum('amount');
        $cash_paid_in_advance=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('type','dr')
        ->where("payment_status","paid")
        ->where("source","!=","advance")
        ->where("source","!=","salary_paid")
        ->where("source","!=","Visa Charges")
        ->where("source","!=","Mobile Installment")
        ->sum('amount');

        $salary_paid=\App\Model\Accounts\Rider_Account::where("rider_id",$rider_id)
        ->whereDate('month', '>=',$from)
        ->whereDate('month', '<=',$to)
        ->where('source','salary_paid')
        ->where('payment_status','paid')
        ->sum('amount');

        if($show_attendanceslip==1){
          $TimeSheet=Income_Zomato::with('Time_sheet')->where('rider_id',$rider_id)
          ->whereDate('date', '>=',$from)
          ->whereDate('date', '<=',$to)
          ->get()
          ->first();
        }
        if($show_salaryslip==0){
          $date_of_joining=null;
          $ncw=0;
          $tip=0;
          $bike_allowns=0;
          $bonus=0;
          $bike_fine=0;
          $advance=0;
          $salik=0;
          $sim=0;
          $denial_penalty=0;
          $dc=0;
          $macdonald=0;
          $rta=0;
          $mobile=0;
          $dicipline=0;
          $mics=0;
          $cash_paid_in_advance=0;
          $salary=0;
          $trips=0;
          $hours=0;
          $extra_trips=0;
          $salary_paid=0;
          $closing_balance_prev=0;
        }

      }
      return response()->json([
        'status'=>1,
        'rider_id'=>$rider_id,
        'rider_name'=>$rider_name,
        'month'=>$month,
        'date_of_joining'=>$date_of_joining,

        'closing_balance_prev'=>$closing_balance_prev,
        'salary'=>$salary,
        'trips'=>$trips,
        'hours'=>$hours,
        'extra_trips'=>$extra_trips,
        'ncw'=>$ncw,
        'tip'=>$tip,
        'bike_allowns'=>$bike_allowns,
        'bonus'=>$bonus,

        'bike_fine'=>$bike_fine,
        'advance'=>$advance,
        'salik'=>$salik,
        'sim'=>$sim,
        'denial_penalty'=>$denial_penalty,
        'dc'=>$dc,
        'macdonald'=>$macdonald,
        'rta'=>$rta,
        'mobile'=>$mobile,
        'dicipline'=>$dicipline,
        'mics'=>$mics,
        'cash_paid_in_advance'=>$cash_paid_in_advance,

        'salary_paid'=>$salary_paid,

        'income_zomato'=>$TimeSheet,

        'show_salaryslip'=>$show_salaryslip,
        'show_attendanceslip'=>$show_attendanceslip,

        'payment_date'=>carbon::now()->format("M d,Y"),
        'expiry_month'=>$expiry_month,
        'current_date'=>$current_date,
      ]);
    }
}
