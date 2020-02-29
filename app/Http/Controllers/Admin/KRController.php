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
use App\Model\Accounts\Rider_salary;
use App\Model\Accounts\Id_charge;
use App\Model\Accounts\Workshop;
use App\Model\Accounts\Maintenance;
use App\Model\Accounts\Rider_Account;
use App\Model\Accounts\Edirham;
use Carbon\Carbon;
use App\Model\Rider\Rider_detail;
use App\Model\Accounts\Fuel_Expense;
use App\Model\Accounts\Client_Income;
use App\Model\Accounts\Income_zomato;
use App\Model\Accounts\Bike_Fine;
use Arr;
use Batch;
use App\Model\Admin\Admin;
use App\Model\Accounts\Company_Account;
use App\Assign_bike;
use App\Log_activity;
use App\Model\Accounts\Absent_detail;
use App\Model\Zomato\Riders_Payouts_By_Days;
use App\Deleted_data;
use App\Notification;


class KRController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function account_view(){
        $riders = Rider::where('active_status', 'A')->get();
        return view('admin.KR_Bikes.accounts',compact('riders')); 
    }
     
    public function activity_view(){
        return view('activity_log_view');
    }
    public function delete_activity_log($id){
    $la=Log_activity::find($id);
    $la->active_status="D";
    $la->save();
    return response()->json([
        'status' => true
    ]);
    }
    public function gov_tax(){
        return view('tax');
    }

    public function BF_index(){
        $riders=Rider::where('active_status','A')->get();
        $bikes=bike::where('active_status', 'A')->get();
        return view('admin.accounts.Bike_Fine.BF_add',compact('bikes','riders'));
    }
    
    public function BF_store(Request $r){
        $bf=new Bike_Fine();
        $bf->rider_id=$r->rider_id;
        $bf->bike_id=$r->bike_id;
        $bf->description='Bike Fine';
        $bf->amount=$r->amount;
        $bf->given_date=Carbon::parse($r->given_date)->format('Y-m-d');
        $bf->month=Carbon::parse($r->month)->format('Y-m-d');
        $bf->save();

        $ca = new Company_Account();
        $ca->type='dr';
        $ca->month = Carbon::parse($r->get('month'))->format('Y-m-d');
        $ca->given_date=Carbon::parse($r->given_date)->format('Y-m-d');
        $ca->amount=$r->amount;
        $ca->rider_id=$r->rider_id;
        $ca->source='Bike Fine';
        $ca->bike_fine=$bf->id;
        $ca->save();

        return redirect(route('admin.BF_view'));
    }
    public function BF_view()
    { 
        return view('admin.accounts.Bike_Fine.BF_view');
    }
    public function BF_delete($id)
    { 
        $delete_bf=Bike_Fine::find($id);
        $delete_bf->delete();
    }
    public function BF_edit($id){
        $is_readonly=false;
        $bf=Bike_Fine::find($id);
        $bikes=bike::all();
        $riders=Rider::all();
        return view('admin.accounts.Bike_Fine.BF_edit',compact('is_readonly','bf','bikes','riders'));
    }
    public function BF_edit_view($id){
        $is_readonly=true;
        $bf=Bike_Fine::find($id);
        $bikes=bike::all();
        $riders=Rider::all();
        return view('admin.accounts.Bike_Fine.BF_edit',compact('is_readonly','bf','bikes','riders'));
    }
    public function BF_update(Request $r,$id){
        $bf=Bike_Fine::find($id);
        $bf->rider_id=$r->rider_id;
        $bf->bike_id=$r->bike_id;
        $bf->description='Bike Fine';
        $bf->amount=$r->amount;
        $bf->month=Carbon::parse($r->month)->format('Y-m-d');
        $bf->given_date=Carbon::parse($r->given_date)->format('Y-m-d');
        $bf->update();

        $ca=Company_Account::where('bike_fine',$bf->id)->get();
        foreach ($ca as $value) {
            if ($value->source=='Bike Fine') {
                $value->type='dr';
                $value->source='Bike Fine';
            }if($value->source=='Bike Fine Paid'){
                $value->type='cr';
                $value->source='Bike Fine Paid';
            }
            $value->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
            $value->given_date=Carbon::parse($r->given_date)->format('Y-m-d');
            $value->amount=$r->amount;
            $value->bike_fine=$bf->id;
            $value->rider_id=$r->rider_id;
            $value->update();
        }
        $ra =Rider_Account::firstOrCreate([
            'bike_fine'=>$bf->id
        ]);
        $ra->type='dr';
        $ra->month = Carbon::parse($r->get('month'))->startOfMonth()->format('Y-m-d');
        $ra->given_date=Carbon::parse($r->given_date)->format('Y-m-d');
        $ra->amount=$r->amount;
        $ra->rider_id=$r->rider_id;
        $ra->source='Bike Fine Paid';
        $ra->bike_fine=$bf->id;
        $ra->payment_status='pending';
        $ra->save();
            
        return redirect(route('admin.BF_edit_view',$bf->id));
    }
    public function paid_fine_by_rider($amount,$rider_id,$bike_fine_id,$month,$given_date){
        $ca = new Company_Account();
        $ca->type='cr';
        $ca->month = Carbon::parse($month)->format('Y-m-d');
        $ca->given_date = Carbon::parse($given_date)->format('Y-m-d');
        $ca->amount=$amount;
        $ca->rider_id=$rider_id;
        $ca->source='Bike Fine Paid';
        $ca->bike_fine=$bike_fine_id;
        $ca->payment_status='paid';
        $ca->save();

        $ra = new Rider_Account();
        $ra->type='dr';
        $ra->month = Carbon::parse($month)->format('Y-m-d');
        $ra->given_date = Carbon::parse($given_date)->format('Y-m-d');
        $ra->amount=$amount;
        $ra->rider_id=$rider_id;
        $ra->source='Bike Fine Paid';
        $ra->bike_fine=$bike_fine_id;
        $ra->payment_status='pending';
        $ra->save();

        return response()->json([
            'status'=>$ca,
            'ra'=>$ra,

        ]);
    }

    public function absent_detail(){
        $riders=Rider::where("active_status","A")->get();
        return view('admin.accounts.Rider_Debit.absent_form',compact('riders'));
    }

    public function absent_detail_store(Request $request){
        $absent_detail=new Absent_detail;
        $already_absent_detail = Absent_detail::where(['rider_id'=>$request->rider_id, 'absent_date'=>Carbon::parse($request->absent_date)->format('Y-m-d')])
        ->get()
        ->first();
        if(isset($already_absent_detail)){
            $absent_detail=$already_absent_detail;
        }
        $absent_detail->rider_id=$request->rider_id;
        $absent_detail->absent_reason=$request->absent_reason;
        $absent_detail->absent_date=carbon::parse($request->absent_date)->format("Y-m-d");
        $absent_detail->email_sent=$request->email_sent;
        $absent_detail->approval_status=$request->approval_status;
        if($request->hasFile('document_image'))
        {
            $filename = $request->document_image->getClientOriginalName();
            $filesize = $request->document_image->getClientSize();
            $filepath = Storage::putfile('public/uploads/riders/absent_document_image', $request->file('document_image'));
            $absent_detail->document_image = $filepath;
        }
        $check_zomato_payout=Riders_Payouts_By_Days::where("rider_id",$request->rider_id)->where("date", $absent_detail->absent_date)->get()->first();
        if (isset($check_zomato_payout)) {
        if ($request->approval_status=="accepted") {
            $rpbd=Riders_Payouts_By_Days::where("rider_id",$request->rider_id)->where("date", $absent_detail->absent_date)->get()->first();
            if ($rpbd->absent_status!="Approved") {
                $absent_detail->save();
                $rpbd->absent_status="Approved";
                if(isset($rpbd->absent_fine_id)){
                    $ra =Rider_Account::where("kingrider_fine_id",$rpbd->absent_fine_id)->get()->first();
                    if(isset($ra)) $ra->delete();

                    $ca =Company_Account::where("kingrider_fine_id",$rpbd->absent_fine_id)->get()->first();
                    if (isset($ca)) $ca->delete();
                    
                }
                $rpbd->absent_fine_id=null;
                $rpbd->absent_detail_status="1";
                $income_id=$rpbd->zomato_income_id;
                $rpbd->save();
                $income_zomato=Income_zomato::find($income_id);
                if ($income_zomato->approve_absents!=0 || $income_zomato->approve_absents!=null) {
                    $income_zomato->approve_absents-=1;
                }
                $income_zomato->save();
            }
        }
        if ($request->approval_status=="rejected") {
            $rpbd=Riders_Payouts_By_Days::where("rider_id",$request->rider_id)->where("date", $absent_detail->absent_date)->get()->first();
            if ($rpbd->absent_status!="Rejected") {
                $absent_detail->save();
                $rpbd->absent_status="Rejected";
                $rpbd->absent_fine_id=$rpbd->id;
                $income_id=$rpbd->zomato_income_id;
                $rpbd->absent_detail_status="1";
                $rpbd->save();
                $income_zomato=Income_zomato::find($income_id);
                $income_zomato->approve_absents+=1;
                $income_zomato->save();

                $amt=100;
                $ra =new Rider_Account;
                $ra->type='dr';
                $ra->month = Carbon::parse($absent_detail->absent_date)->startOfMonth()->format('Y-m-d');
                $ra->given_date=Carbon::now()->format('Y-m-d');
                $ra->amount=round($amt,2);
                $ra->rider_id=$request->rider_id;
                $ra->source='Absent Fine (on '.Carbon::parse($absent_detail->absent_date)->format('Y-m-d').')';
                $ra->payment_status='pending';
                $ra->kingrider_fine_id=$rpbd->id; 
                $ra->save();

                $ca =new Company_Account;
                $ca->type='cr';
                $ca->month = Carbon::parse($absent_detail->absent_date)->startOfMonth()->format('Y-m-d');
                $ca->given_date=Carbon::now()->format('Y-m-d');
                $ca->amount=round($amt,2);
                $ca->rider_id=$request->rider_id;
                $ca->source='Absent Fine (on '.Carbon::parse($absent_detail->absent_date)->format('Y-m-d').')';
                $ca->payment_status='pending';
                $ca->kingrider_fine_id=$rpbd->id; 
                $ca->save();
            }
        }
    }
    else{
        $absent_detail->save();
    }
        return redirect(route('account.absent_detail'));
    }
    public function absent_detail_ajax($month,$rider_id,$_date){
        $rider_name="";
        $absnt_date="";
        $absnt_reason="";
        $absnt_email="";
        $absnt_app_status="";

        $absent_detail=Absent_detail::where("rider_id",$rider_id)
        ->where("absent_date",$_date)
        ->get()
        ->first();
        if (isset($absent_detail)) {
            $absnt_date=$absent_detail->absent_date;
            $absnt_reason=$absent_detail->absent_reason;
            $absnt_email=$absent_detail->email_sent;
            $absnt_app_status=$absent_detail->approval_status;
            $rider=Rider::find($absent_detail->rider_id);
            if (isset($rider)) {
                $rider_name=$rider->name;
            }
            $status="1";
        }
        if (!isset($absent_detail)) {
            $status="0";
        }
        return response()->json([
            'rider_name'=>$rider_name,
            'absnt_date'=>$absnt_date,
            'absnt_reason'=>$absnt_reason,
            'absnt_email'=>$absnt_email,
            'absnt_app_status'=>$absnt_app_status,
            'status'=>$status,
        ]);
    }
    public function check_payout($month,$rider_id,$date){
        $income_zomato=Income_zomato::where("rider_id",$rider_id)
        ->whereMonth("date",$month)
        ->get()
        ->first();
        $is_payout="0";
        if (isset($income_zomato)) {
            $is_payout="1";
        }
        $rpbd=Riders_Payouts_By_Days::where("rider_id",$rider_id)->where("date", $date)->get()->first();
        $day_status='';
        if (isset($rpbd)) {
            if($rpbd->off_days_status=="present" || $rpbd->off_days_status=="extraday" || $rpbd->off_days_status=="weeklyoff"){
                if ($rpbd->off_days_status=="present") {
                   $day_status="present"; 
                }
                if ($rpbd->off_days_status=="extraday") {
                    $day_status="extraday";
                }
                if ($rpbd->off_days_status=="weeklyoff") {
                    $day_status="weeklyoff";
                }
            }
        }
        return response()->json([
            'month'=>$month,
            'rider_id'=>$rider_id,
            'is_payout'=>$is_payout,
            'day_status'=>$day_status,
        ]);
    }
    public function change_payout_data(Request $request,$rider_id,$month){
        $data=$request->data;
        foreach ($data as $value) {
            $_date=carbon::parse($value['date'])->format('Y-m-d');
            $rpbd=Riders_Payouts_By_Days::where("rider_id",$rider_id)->where("date",$_date)->get()->first();
            if (isset($rpbd)) {
                $rpbd->login_hours=$value['hours'];
                $rpbd->trips=$value['trip'];
                $rpbd->update();
            }
        }
        return response()->json([
            'rider_id'=>$rider_id,
            'month'=>$month,
        ]);
    }
    public function view_deleted_data(){
        return view('admin.accounts.Deleted_Data.data');
    }
    public function retreive_data($id,$status,$admin_id){
        if ($status=="accept") {
            $deleted_data=Deleted_data::find($id);
            $deleted_data->status="paid";
            $feed=json_decode($deleted_data->feed,true);
            
            foreach ($feed as $item) {
                $k='';
                $d_item='';
                $count=0;
                $table_name=(new $item['model'])->getTable();
                $model_data=json_decode($item['data'],true);
                foreach ($model_data as $data_key => $data_item) {
                    if ($count==count($model_data)-1) {
                    
                        $k.='`'.$data_key."`";

                        
                        if ($data_item=='' || $data_item==null || $data_item=='null') $d_item.="NULL";
                        else $d_item.='\''.$data_item."'";
                    }
                    else{
                        
                        $k.='`'.$data_key."`,";

                        
                        if ($data_item=='' || $data_item==null || $data_item=='null') $d_item.="NULL,";
                        else $d_item.='\''.$data_item."',";
                    }
                    $count = $count + 1;
                }
                
                $insert_data='INSERT INTO '.$table_name.' ('.$k.') VALUES('.$d_item.')';
                // DB::insert($insert_data);
            }
            $deleted_data->delete();
            $emp_name=Auth::user()->name;
            $notification=new Notification;
            $notification->date_time=Carbon::now()->format("Y-m-d");
            $notification->employee_id=$admin_id;
            $notification->desc=$emp_name." accepted your retreive data request";
            $notification->action="";
            // $notification->status="read";
            $notification->save();
            return response()->json([
                'status'=>$insert_data,
                'count'=>$count,
            ]);
        }
        if ($status=="reject") {
            $emp_name=Auth::user();
            $notification=new Notification;
            $notification->date_time=Carbon::now()->format("Y-m-d");
            $notification->employee_id=$admin_id;
            $notification->desc=$emp_name->name." rejected your retreive request";
            $notification->action="";
            // $notification->status="read";
            $notification->save();
        }
        
    }
    public function retreive_notification($id){
        $del_data=Deleted_data::find($id);
        $feed=json_decode($del_data->feed,true);
        foreach ($feed as $key => $feed_item) {
            # fetching data for generating meaningful description
            if(strpos($feed_item['model'],'Company_Account') !== false || strpos($feed_item['model'],'Rider_Account') !== false){
                # if company account or rider account was founded (one of them must be there)
                $feed_data=json_decode($feed_item['data'],true);
            }
        }
        
        if(!isset($feed_data)){
            # well, no company account or rider account found? that was unexpencted -we just throw the error
            return response()->json([
                'status'=>0,
                'msg'=>'No Company Account or Rider Account found!',
            ]);
        }
        $given_date=Carbon::parse($feed_data['given_date'])->format("d M, Y");
        $source=$feed_data['source'];
        $rider_id=$feed_data['rider_id'];

        $user = Auth::user();
        if (isset($user->s_emp_id) && $user->s_emp_id!="") {
            $seniour_emp=$user->s_emp_id;
            $emp_name=$user->name;
        }
        else{
            $seniour_emp="1";
            $emp_name="Admin";
        }
        $data_accept=[
            "type"=>"retreive",
            "data"=>"",
            "url"=> 'admin/retreive_data/ajax/'. $id . "/accept/" . Auth::user()->id,
        ];
        $data_reject=[
            "type"=>"retreive",
            "data"=>"",
            "url"=> 'admin/retreive_data/ajax/'. $id . "/reject/" . Auth::user()->id,
        ];
        $button_html_accepted="<i style='font-size:20px' class='flaticon2-correct text-success' onclick='CallBackNotification(this,".json_encode($data_accept).")'></i>";
        $button_html_rejected="<i style='font-size:20px' class='flaticon-circle text-danger' onclick='CallBackNotification(this,".json_encode($data_reject).")'></i>";
        $button=$button_html_accepted.$button_html_rejected;
        $current_url=url()->current();
        $action_data = [
            [
                'type'=>"url",
                'value'=>$current_url,    
            ] ,
            [
                'type'=>"button",
                'value'=>$button,    
            ] ,  
        ];
        $notification=new Notification;
        $notification->date_time=Carbon::now()->format("Y-m-d");
        $notification->employee_id=$seniour_emp;
        $notification->desc=$emp_name." want to retreive ".$source." for KR".$rider_id." on ".$given_date;
        $notification->action=json_encode($action_data);
        $notification->save();

        return response()->json([
            'status'=>1,
            'id'=>$id,
            'notification'=>$notification,
        ]);
    }
    public function sendDeleteNotification(Request $request){
        $data=[
            "id"=>$request->id,
            "model_class"=>$request->model_class,
            "model_id"=>$request->model_id,
            "rider_id"=>$request->rider_id,
            "string"=>$request->string,
            "month"=>$request->month,
            "year"=>$request->year,
            "source_id"=>$request->source_id,
            "source_key"=>$request->source_key,
            "given_date"=>$request->given_date,
        ];
        $data_accept=[
            "type"=>"delete",
            "data"=>$data,
            "url"=> 'admin/delete/accounts/rows'. "/" . $request->id . "/accept/" . Auth::user()->id."/".$request->statement_type,
        ];
        $data_reject=[
            "type"=>"delete",
            "data"=>$data,
            "url"=> 'admin/delete/accounts/rows'. "/" . $request->id . "/reject/" . Auth::user()->id."/".$request->statement_type,
        ];
        $button_html_accepted="<i style='font-size:20px' class='flaticon2-correct text-success' onclick='CallBackNotification(this,".json_encode($data_accept).")'></i>";
        $button_html_rejected="<i style='font-size:20px' class='flaticon-circle text-danger' onclick='CallBackNotification(this,".json_encode($data_reject).")'></i>";
        $button=$button_html_accepted.$button_html_rejected;
        $current_url=url()->current();
        $action_data = [
            [
                'type'=>"url",
                'value'=>$current_url,    
            ] ,
            [
                'type'=>"button",
                'value'=>$button,    
            ] ,
        ];
        $user = Auth::user();
        if (isset($user->s_emp_id) && $user->s_emp_id!="") {
            #some senior person found, we need an approval from them
            $seniour_emp=$user->s_emp_id;
            $emp_name=$user->name;
        }
        else{
            # no senior person found, so just delete the data

            $seniour_emp="1";
            $emp_name="Admin";

            $data = $data;
            $ch = curl_init('admin/delete/accounts/rows'. "/" . $request->id . "/reject/" . Auth::user()->id."/".$request->statement_type);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));

            $response = curl_exec($ch);

            if (!$response) 
            {
                // return false;
            }
            return response()->json([
                'status'=>0,
                'r'=>$response,
            ]);
        }
        $given_date=Carbon::parse($request->given_date)->format("M d, Y");
        $notification=new Notification;
        $notification->date_time=Carbon::now()->format("Y-m-d");
        $notification->employee_id=$seniour_emp;
        $notification->desc=$emp_name." want to delete ".$request->model_id." for KR".$request->rider_id." on ".$given_date;
        $notification->action=json_encode($action_data);
        $notification->save();
        return response()->json([
            'notification'=>$notification,
            'r'=>$request->all(),
        ]);
    }
    public static function curlGet($url)
    {
        $ch = curl_init();  
    
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    //  curl_setopt($ch,CURLOPT_HEADER, false); 
    
        $output=curl_exec($ch);
    
        curl_close($ch);
        return $output;
    }
    public function sendUpdateNotification(Request $request){
        $data=[
            "statement_id"=>$request->statement_id,
            "source_key"=>$request->source_key,
            "source_id"=>$request->source_id,
            "amount"=>$request->amount,
            "rider_id_update"=>$request->rider_id_update,
            "desc"=>$request->desc,
            "month_update"=>$request->month_update,
            "given_date"=>$request->given_date,
            "source"=>$request->source,
            "bk_month"=>$request->bk_month,
            "bk_year"=>$request->bk_year,
            "rider_id"=>$request->rider_id,
        ];
        $account_type='company';
        if($request->statement_type=='rider__accounts')$account_type='rider';
        $data_accept=[
            "type"=>"update",
            "data"=>$data,
            "url"=> 'admin/accounts/'.$account_type.'/update_row/' . $request->statement_id . "/accept/" . Auth::user()->id,
        ];
        $data_reject=[
            "type"=>"update",
            "data"=>$data,
            "url"=> 'admin/accounts/'.$account_type.'/update_row/' . $request->statement_id . "/reject/" . Auth::user()->id,
        ];
        $button_html_accepted="<i style='font-size:20px' class='flaticon2-correct text-success' onclick='CallBackNotification(this,".json_encode($data_accept).")'></i>";
        $button_html_rejected="<i style='font-size:20px' class='flaticon-circle text-danger' onclick='CallBackNotification(this,".json_encode($data_reject).")'></i>";
        $button=$button_html_accepted.$button_html_rejected;
        $current_url=url()->current();
        $action_data = [
            [
                'type'=>"url",
                'value'=>$current_url,    
            ] ,
            [
                'type'=>"button",
                'value'=>$button,    
            ] ,
        ];
        $user = Auth::user();
        if (isset($user->s_emp_id) && $user->s_emp_id!="") {
            $seniour_emp=$user->s_emp_id;
            $emp_name=$user->name;
        }
        else{
            $seniour_emp="1";
            $emp_name="Admin";
        }
        $given_date=Carbon::parse($request->given_date)->format("M d, Y");
        $notification=new Notification;
        $notification->date_time=Carbon::now()->format("Y-m-d");
        $notification->employee_id=$seniour_emp;
        $notification->desc=$emp_name." want to update ".$request->source." for KR".$request->rider_id_update." on ".Carbon::parse($request->given_date)->format("M d, Y");
        $notification->action=json_encode($action_data);
        $notification->save();
        return response()->json([
            'notification'=>$notification,
            'r'=>$request->all(),
        ]);
    }
    public function ReadNotification($id){
        $notification=Notification::find($id);
        $notification->status="read";
        $notification->save();
        return response()->json([
            'id'=>$id,
        ]);
    }
}
