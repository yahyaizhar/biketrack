@extends('admin.layouts.app')
@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        
        <h3 class="kt-subheader__title">{{ $client->name }}</h3>

        <span class="kt-subheader__separator kt-subheader__separator--v"></span>

        <span class="kt-subheader__desc">Riders</span>

        <a href="{{ route('admin.clients.assignRiders', $client->id) }}" class="btn btn-label-warning btn-bold btn-sm btn-icon-h kt-margin-l-10">
            Assign Riders
        </a>

        <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
            <input type="text" class="form-control" placeholder="Search order..." id="generalSearch">
            <span class="kt-input-icon__icon kt-input-icon__icon--right">
                    <span><i class="flaticon2-search-1"></i></span>
            </span>
        </div>
    </div>
</div>
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content" style="margin-top:60px;">
@if (strpos($client->email, "zomato") !== false)
    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">
                <div class="col-md-12 col-lg-4 col-xl-4">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <a href="{{ route('admin.riderPerformance') }}" class="kt-widget24__info">
                                <span class="kt-widget24__stats kt-font-danger">
                                    <button type="button" class="btn btn-primary">
                                        <i class="fa fa-tachometer-alt"></i>
                                        <p class="p-0 m-0">Rider Performance</p>
                                    </button>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 col-xl-4">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <a href="{{ route('admin.ranges.adt') }}" class="kt-widget24__info">
                                <span class="kt-widget24__stats kt-font-success">
                                    <button type="button" class="btn btn-success">
                                        <i class="flaticon2-percentage"></i>
                                        <p class="p-0 m-0">ADT Performance</p>
                                    </button>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 col-xl-4">
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <a href="{{ route('admin.accounts.income_zomato_index') }}" class="kt-widget24__info">
                                <span class="kt-widget24__stats kt-font-brand">
                                    <button type="button" class="btn btn-danger">
                                        <i class="fa fa-dollar-sign"></i>
                                        <p class="p-0 m-0">Income Report</p>
                                    </button>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
    @if(count($riders) > 0)
        @foreach ($riders as $rider)
        <div class="row">
            <div class="col-xl-12">
            <!--begin:: Widgets/Applications/User/Profile3-->
            <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-3">
                            <div class="kt-widget__top">
                                <div class="kt-widget__media kt-hidden-">
                                    @if($rider->profile_picture)
                                        <img src="{{ asset(Storage::url($rider->profile_picture)) }}" alt="image">
                                    @else
                                        <img src="{{ asset('dashboard/assets/media/users/default.jpg') }}" />
                                    @endif
                                </div>
                                {{-- <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                    JM
                                </div> --}}
                                <div class="kt-widget__content">
                                    <div class="kt-widget__head">
                                        <a class="kt-widget__username">
                                            {{ $rider->name }}
                                            @if ($rider->online)
                                                <i class="flaticon2-correct"></i>                                            
                                            @endif
                                        </a>
                                        
                
                                        <div class="kt-widget__action">
                                            <a href="{{ route('admin.rider.location', $rider->id) }}" class="btn btn-label-danger btn-sm btn-upper">View Location</a>&nbsp;
                                            <button onclick="deleteRider({{$client->id}}, {{$rider->pivot->rider_id}})" class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp;
                                        </div>
                                    </div>
                
                                    <div class="kt-widget__subhead">
                                        @php
                                        $client_rider = App\Model\Client\Client_Rider::where('client_id',$client->id)->where('rider_id',$rider->id)->get()->first();
                                        $assign_bike=$rider->Assign_bike()->where('status','active')->get()->first();
                                        $bike_number='No bike assigned';
                                        if(isset($assign_bike)){
                                            $bike=App\Model\Bikes\bike::find($assign_bike->bike_id);
                                            $bike_number=$bike->bike_number;
                                        } 
                                        $feid='No FIED';
                                        if(isset($client_rider->client_rider_id)){
                                            $feid=$client_rider->client_rider_id;
                                        }
                                        @endphp
                                        <a>FEID: {{$feid}}</a>
                                        <a href="mailto:{{ $rider->email }}"><i class="flaticon2-new-email"></i>{{ $rider->email }}</a>
                                        <a><i class="flaticon2-calendar-3"></i>{{ $rider->phone }} </a>
                                        <a><i class="fa fa-motorcycle"></i>{{ $bike_number }}</a>
                                    </div>
                
                                    <div class="kt-widget__info">
                                        <i class="flaticon-location"></i>&nbsp;
                                        <div class="kt-widget__desc">
                                            {{ $rider->address }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="kt-widget__bottom">
                                <div class="kt-widget__item col-md-10">
                                    <textarea class="form-control" id="message_{{ $rider->id }}" name="message_{{ $rider->id }}" placeholder="Enter message here"></textarea>
                                </div>
                                <div class="kt-widget__item">
                                    <button onclick="sendSMS({{$rider->id}})" class="btn btn-label-success btn-sm btn-upper">Send SMS</button>&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Applications/User/Profile3-->    
        </div>
        @endforeach
    @else
    <div class="kt-section__content">
        <div class="alert alert-danger fade show" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">No rider assigned yet.</div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- end:: Content -->
@endsection
@section('foot')
    <script>
        function sendSMS(id)
        {
            var rider_id = id;
            var textbox_id = "#message_"+id;
            var url = "{{ url('admin/rider') }}" + "/" + id + "/sendMessage";
            var method = 'POST';
            // console.log(textbox_id);
            // console.log($(textbox_id).val());
            var data = {
                'message' : $(textbox_id).val()
            }
            if(data.message == '')
            {
                swal.fire("Message Empty!", "Please enter some message.", "error");
            }
            else
            {
                // console.log(data.message);
                sendRequest(url, method, data, true, false, null, true);
                $(textbox_id).val('');
            }
        }
        function deleteRider(client_id, rider_id)
        {
            // console.log(client_id + ' , ' + rider_id);
            var url = "{{ url('admin/client') }}" + "/" + client_id + "/removeRider/" + rider_id;
            console.log(url);
            sendDeleteRequest(url, true);
        }
    </script>
@endsection