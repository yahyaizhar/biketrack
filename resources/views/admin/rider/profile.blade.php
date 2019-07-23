@extends('admin.layouts.app')
@section('main-content')

<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
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
                                        <a href="{{ route('admin.riders.edit', $rider->id) }}" class="btn btn-label-info btn-sm btn-upper">Edit</a>&nbsp;
                                        <a href="{{ route('admin.rider.location', $rider->id) }}" class="btn btn-label-danger btn-sm btn-upper">View Location</a>&nbsp;
                                        {{-- <button class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp; --}}
                                    </div>
                                </div>
            
                                <div class="kt-widget__subhead">
                                    <a href="mailto:{{ $rider->email }}"><i class="flaticon2-new-email"></i>{{ $rider->email }}</a>
                                    <a><i class="flaticon2-calendar-3"></i>{{ $rider->phone }} </a>
                                    <a><i class="fa fa-motorcycle"></i>{{ $rider->vehicle_number }}</a>
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
            var data = {
                'message' : $(textbox_id).val()
            };
            
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