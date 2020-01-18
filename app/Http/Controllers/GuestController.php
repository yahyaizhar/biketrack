<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\GuestNewComer;
use App\Model\Rider\Rider_detail;
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
      $month_salaryslip=0;
      $isshow=true;
      $month=0;
      $TimeSheet=null;

      

      $rider_detail=Rider_detail::where("emirate_id",$request->emirate_id)->get()->first();
      if (isset($rider_detail)) {
        $show_salaryslip=$rider_detail->show_salaryslip;
        $dmy="2019-11-01";
        if ($show_salaryslip!=null || $show_salaryslip!=0) {
          $show_salaryslip=1;
          $dmy=$rider_detail->salaryslip_month;
        }
        $rider_id=$rider_detail->rider_id;
        $rider=Rider::find($rider_id);
        $rider_name=$rider->name;
        $date_of_joining=$rider_detail->date_of_joining;

        $month=Carbon::parse($dmy)->format("Y-m-d");
        $from =Carbon::parse($month)->startOfMonth()->format("Y-m-d");
        $to =Carbon::parse($month)->endOfMonth()->format("Y-m-d");

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
        ->where('source','Discipline Fine')
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

        $TimeSheet=Income_Zomato::with('Time_sheet')->where('rider_id',$rider_id)
        ->whereDate('date', '>=',$from)
        ->whereDate('date', '<=',$to)
        ->get()
        ->first();

      }
      return response()->json([
        'status'=>$request->emirate_id,
        'rider_id'=>$rider_id,
        'rider_name'=>$rider_name,
        'month'=>$month,
        'date_of_joining'=>$date_of_joining,

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
        

        'payment_date'=>carbon::now()->format("M d,Y"),
      ]);
    }
}
