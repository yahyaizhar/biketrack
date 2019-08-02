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
                                    @if ($bike==null)
                                    <a><i class="fa fa-motorcycle"></i>No Bike assigned to this rider</a>     
                                    @else
                                    <a><i class="fa fa-motorcycle"></i>{{ $bike->bike_number }}</a> 
                                    @endif
                                   
                                </div>
            
                                <div class="kt-widget__info">
                                    <i class="flaticon-location"></i>&nbsp;
                                    <div class="kt-widget__desc">
                                        {{ $rider->address }}
                                    </div>
                                 
                                </div>
                            </div>
                        </div>
                        <div class="kt-widget__top" style="margin-top: 25px;margin-right: 100px;margin-left: -22px;">
                           <div class="kt-widget__content">
                                <div class="kt-widget__head">
                                    <a class="kt-widget__username">
                                        Rider details
                                    </a>
                                </div>
            <div class="row">
                    <div class="kt-widget__subhead col-md-8">
                            <div class="kt-widget__desc">  
                                    <label style="font-weight:900;">Date Of Joining:</label>
                                 <a >{{ $rider_details->date_of_joining }}</a>
                                </div>
                            
                            <div class="kt-widget__desc">  
                                    <label style="font-weight:900;">Is passport collected:</label>
                                  <a>{{ $rider_details->passport_collected }}</a>
                                </div>
                                <div class="kt-widget__desc">  
                                        <label style="font-weight:900;">Other Details:</label>
                                      <a>{{ $rider_details->other_details }}</a>
                                    </div>
                                    <div class="kt-widget__desc">  
                                            <label style="font-weight:900;">Official Given Number:</label>
                                          @if ($sim==null)
                                          <a>Sim is not assigned yet</a>
                                          @else
                                          <a>{{ $sim->sim_number }}</a>  
                                          @endif
                                            
                                        </div>
                    </div>
                    <div class="kt-widget__subhead col-md-4">
                                   
                            <div class="kt-widget__desc">
                                <label style="font-weight:900;">Start Time:</label>
                                {{ $rider->start_time }}
                              </div>
                            <div class="kt-widget__desc">
                                    <label style="font-weight:900;">End Time:</label>
                                {{ $rider->end_time }}
                            </div>
                            <div class="kt-widget__desc">
                                    <label style="font-weight:900;">Break Start Time:</label>
                                {{ $rider->break_start_time }}
                            </div>
                            <div class="kt-widget__desc">
                                    <label style="font-weight:900;">Break End Time:</label>
                                {{ $rider->break_end_time }} 
                            </div>
                            
                         
                        </div></div>
                        <div class="row">
                        <div class="kt-widget__subhead col-md-4">
                            <div class="kt-widget__desc">
                                <label style="font-weight:900;">Passport Expiry:</label>
                                {{ $rider_details->passport_expiry }}
                             </div>
                             <div class="kt-widget__desc">
                                <label style="font-weight:900;">Passport Image Front:</label>
                                  @if($rider_details->passport_image)
                                  <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->passport_image)) }}">Click To See </a>
                                @else
                            <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}">Click To See  </a>
                             @endif
                             </div>
                             <div class="kt-widget__desc">
                                <label style="font-weight:900;">Passport Image Back:</label>
                                @if($rider_details->passport_image_back)
                                <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->passport_image_back)) }}">Click To See </a>
                              @else
                          <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}">Click To See  </a>
                           @endif
                             </div>    
                        
                            </div>

                            <div class="kt-widget__subhead col-md-4">
                                <div class="kt-widget__desc">
                                    <label style="font-weight:900;">Licence Expiry:</label>
                                {{ $rider_details->licence_expiry }}
                              
                            </div>
                            <div class="kt-widget__desc">
                                <label style="font-weight:900;">Licence Front Image:</label>
                                @if($rider_details->licence_image)
                                <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->licence_image)) }}">Click To See </a>
                             
                                @else
                                <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}">Click To See </a>
                                @endif  
                           
                        </div>
                        <div class="kt-widget__desc">
                            <label style="font-weight:900;">Licence Back Image:</label>
                            @if($rider_details->licence_image_back)
                            <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->licence_image_back)) }}">Click To See </a>
                         
                            @else
                            <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}">Click To See </a>
                            @endif  
                       
                    </div></div>

                            <div class="kt-widget__subhead col-md-4">
                                <div class="kt-widget__desc">
                                    <label style="font-weight:900;">Visa Expiry:</label>
                                {{ $rider_details->visa_expiry }}
                               
                            </div>
                            <div class="kt-widget__desc">
                                <label style="font-weight:900;">Visa Front Image:</label>
                            @if($rider_details->visa_image)
                            <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->visa_image)) }}">Click To See </a>
                           
                             @else
                             <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}">Click To See </a>
                           
                             @endif
                        </div>
                        <div class="kt-widget__desc">
                            <label style="font-weight:900;">Visa Back Image:</label>
                            @if($rider_details->visa_image_back)
                            <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->visa_image_back)) }}">Click To See </a>
                           
                             @else
                             <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}">Click To See </a>
                           
                             @endif
                    </div>  
                          </div>
                        </div>
                        <div class="row">
                            <div class="kt-widget__subhead col-md-4">
                                <div class="kt-widget__desc">
                                    <label style="font-weight:900;">Emerate ID:</label>
                                {{ $rider_details->emirate_id }} 
                              
                            </div>
                            <div class="kt-widget__desc">
                                <label style="font-weight:900;">Emerate_ID Front Image:</label>
                                  @if($rider_details->emirate_image)
                            <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->emirate_image)) }}">Click To See </a>
                           
                            @else
                        <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}">Click To See </a>
                        @endif
                        </div>
                        <div class="kt-widget__desc">
                            <label style="font-weight:900;">Emerate_ID Back Image:</label>
                            @if($rider_details->emirate_image_back)
                            <a href="#" data-featherlight="{{ asset(Storage::url($rider_details->emirate_image_back)) }}">Click To See </a>
                           
                            @else
                        <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}">Click To See </a>
                        @endif
                      
                    </div>
                         </div>
                            @if ($bike==null)
                            @else
                            <div class="kt-widget__subhead col-md-4">
                                <div class="kt-widget__desc">
                                    <label style="font-weight:900;">Mulkiya Expiry:</label>
                                {{ $bike->mulkiya_expiry }} 
        
                            </div> 
                            <div class="kt-widget__desc">
                                <label style="font-weight:900;">Mulkiya Front Image:</label>
                            @if($bike->mulkiya_picture)
                            <a href="#" data-featherlight="{{ asset(Storage::url($bike->mulkiya_picture)) }}">Click To See </a>
                            @else
                        <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}">Click To See </a>
                        @endif
                        </div> 
                        <div class="kt-widget__desc">
                            <label style="font-weight:900;">Mulkiya Back Image:</label>
                            @if($bike->mulkiya_picture_back)
                            <a href="#" data-featherlight="{{ asset(Storage::url($bike->mulkiya_picture_back)) }}">Click To See </a>
                            @else
                        <a href="#" data-featherlight="{{ asset('dashboard/assets/media/users/default.jpg') }}">Click To See </a>
                        @endif
                    </div>     
                      </div>
                            @endif
                            </div>
                     </div>
                     </div>
                        </div>
                        <div class="row">
                            <div class="kt-widget__item col-md-10">
                                <textarea class="form-control" id="message_{{ $rider->id }}" name="message_{{ $rider->id }}" placeholder="Enter message here"></textarea>
                            </div>
                            <div class="kt-widget__item col-md-2" >
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