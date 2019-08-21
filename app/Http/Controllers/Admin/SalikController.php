<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Model\Rider\Trip_Detail; 
use Batch;
use Illuminate\Support\Arr;
use App\Model\Bikes\bike;
use App\Model\Rider\Rider;

class SalikController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    } 
    public function import_salik_data(){
        return view('admin.rider.view_salik');
    }
    public function import_Salik(Request $r)
    {
        $data = $r->data;
        $trip_objects=[];
        $zp = Trip_Detail::all(); // r1
        $update_data = [];
        $i=0;
        $unique_id=uniqid().'-'.time();
        foreach ($data as $item) {
            $i++;
            $zp_found = Arr::first($zp, function ($item_zp, $key) use ($item) {
                return $item_zp->transaction_id == $item['transaction_id'];
            });
            if(!isset($zp_found)){
                $obj = [];
                $obj['import_id']=$unique_id;
                $obj['transaction_id']=isset($item['transaction_id'])?$item['transaction_id']:null;
                $obj['toll_gate']=isset($item['toll_gate'])?$item['toll_gate']:null;
                $obj['direction']=isset($item['direction'])?$item['direction']:null;
                $obj['tag_number']=isset($item['tag_number'])?$item['tag_number']:null;
                $obj['plate']=isset($item['plate'])?$item['plate']:null; 
                $obj['amount_aed']=isset($item['amount_aed'])?$item['amount_aed']:null;
                $obj['trip_date']=isset($item['trip_date'])?$item['trip_date']:null;
                $obj['trip_time']=isset($item['trip_time'])?$item['trip_time']:null;
                $obj['transaction_post_date']=isset($item['transaction_post_date'])?$item['transaction_post_date']:null;
                array_push($trip_objects, $obj);
            }
            else{
                $objUpdate = [];
                $objUpdate['id']=$zp_found->id;
                $objUpdate['import_id']=$unique_id;
                $objUpdate['transaction_id']=isset($item['transaction_id'])?$item['transaction_id']:null;
                $objUpdate['toll_gate']=isset($item['toll_gate'])?$item['toll_gate']:null;
                $objUpdate['direction']=isset($item['direction'])?$item['direction']:null;
                $objUpdate['tag_number']=isset($item['tag_number'])?$item['tag_number']:null;
                $objUpdate['plate']=isset($item['plate'])?$item['plate']:null; 
                $objUpdate['amount_aed']=isset($item['amount_aed'])?$item['amount_aed']:null;
                $objUpdate['trip_date']=isset($item['trip_date'])?$item['trip_date']:null;
                $objUpdate['trip_time']=isset($item['trip_time'])?$item['trip_time']:null;
                $objUpdate['transaction_post_date']=isset($item['transaction_post_date'])?$item['transaction_post_date']:null;
                array_push($update_data, $objUpdate);
            }
        }
        DB::table('trip__details')->insert($trip_objects); //r2
        $data=Batch::update(new Trip_Detail, $update_data, 'id'); //r3
        return response()->json([
            'data'=>$trip_objects,
            'count'=>$i
        ]);
    }
    public function delete_lastImportSalik(){
        $import_id=Trip_Detail::all()->last()->import_id;
        $performances=Trip_Detail::where('import_id',$import_id)->get();
        foreach($performances as $performance)
            {
                $performance->delete();
            }

    }

    public function bike_salik($id){
        $bike=bike::find($id);
        return view('admin.Bike.salik_bike',compact('bike')); 
    }
    public function rider_salik($id){
        $rider=Rider::find($id);
        $assign_bike=$rider->Assign_bike()->get()->first();
        $bike=bike::find($assign_bike->bike_id);
        return view('admin.rider.salik_rider',compact('bike'));
    }

}
