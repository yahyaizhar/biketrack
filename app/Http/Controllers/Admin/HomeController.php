<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Client\Client;
use App\Model\Rider\Rider;
use Yajra\DataTables\DataTables;
use App\Model\Client\Client_Rider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Model\Rider\Rider_area;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $riders = Rider::all()->count();
        $clients = Client::all()->count();
        $online_riders = Rider::where('status', 1)->get()->count();
        $clients_online = Client::where('status', 1)->get()->count();
        // $clients_online = Client_Rider::select('client_id')->distinct()->get()->count();

        $latest_riders = Rider::orderBy('created_at', 'DESC')->take(5)->get();
        $latest_clients = Client::orderBy('created_at', 'DESC')->take(5)->get();
        // return $latest_riders;
        return view('admin.home', compact('riders', 'clients', 'online_riders', 'clients_online', 'latest_riders', 'latest_clients'));
    }

    public function livemap()
    {
        return view('admin.map.livemap');
    }
    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
        ]);
        if($request->change_password)
        {
            $this->validate($request, [
                'password' => 'required | string | confirmed',
            ]);
            $user->password = Hash::make($request->password);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->update();
        return redirect()->back()->with('message', 'Record Updated Successfully.');
    }
    public function assign_area()
    {
        $riders = Rider::all();
        return view('admin.map.assignArea', ['riders'=>$riders]);
    }
    public function assign_area_POST(Request $request)
    {
        $area_bounds = $request->area_bounds;
        $filename = uniqid().uniqid().'-'.time().'.json';
        $filepath = Storage::put('public/uploads/riders/areas/'.$filename,json_encode($area_bounds));
        //  $rider_area=$filepath->Rider_area()->create($request->all());
        $rider_area= new Rider_area();
        $rider_area->name=$request->name;
        $rider_area->path= $filename;
        $rider_area->save();
        return $rider_area;
    }
    public function assign_area_to_rider($id){
        $rider=Rider::find($id);
        $rider_area=Rider_area::all();
        // return $rider_area;
        return view('admin.map.assign_area_to_rider',compact('rider','rider_area'));
    }
}
