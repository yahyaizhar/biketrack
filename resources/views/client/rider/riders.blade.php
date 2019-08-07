@extends('client.layouts.app')
@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        
        <h3 class="kt-subheader__title">{{ Auth::user()->name }}</h3>

        <span class="kt-subheader__separator kt-subheader__separator--v"></span>

        <span class="kt-subheader__desc">Total Assigned Riders</span>

        <a href="javascript:void();" class="btn btn-label-success btn-bold btn-sm btn-icon-h kt-margin-l-10">
            {{$total_riders}}
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
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
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
                                    <img src="{{ asset('dashboard/assets/media/users/default.jpg') }}" alt="image">
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
                                    @php
                                    $client_id=Auth::user()->id;
                                    $client_rider=App\Model\Client\Client_Rider::where('client_id',$client_id)->where('rider_id',$rider->id)->get()->first();
                                @endphp
                                
                                
                                    <div class="kt-widget__action">
                                    <a href="" data-toggle="modal" data-target="#form" data-rider-id="{{$rider->id}}"  data-client-rider-id="{{$client_rider->client_rider_id}}" class="btn btn-label-success btn-sm btn-upper">Add Rider ID</a>&nbsp;
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
                        {{-- <div class="kt-widget__bottom">
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-piggy-bank"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Earnings</span>
                                    <span class="kt-widget__value"><span>$</span>249,500</span>
                                </div>
                            </div>
            
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-confetti"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Expances</span>
                                    <span class="kt-widget__value"><span>$</span>164,700</span>
                                </div>
                            </div>
            
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-pie-chart"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Net</span>
                                    <span class="kt-widget__value"><span>$</span>164,700</span>
                                </div>
                            </div>
            
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-file-2"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">73 Tasks</span>
                                    <a href="#" class="kt-widget__value kt-font-brand">View</a>
                                </div>
                            </div>
            
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-chat-1"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">648 Comments</span>
                                    <a href="#" class="kt-widget__value kt-font-brand">View</a>
                                </div>
                            </div>
            
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-network"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <div class="kt-section__content kt-section__content--solid">
                                        <div class="kt-badge kt-badge__pics">
                                            <a href="#" class="kt-badge__pic" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="John Myer">
                                                <img src="{{ asset('dashboard/assets/media/users/100_7.jpg') }}" alt="image">
                                            </a>
                                            <a href="#" class="kt-badge__pic" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="Alison Brandy">
                                                <img src="{{ asset('dashboard/assets/media/users/100_3.jpg') }}" alt="image">
                                            </a>
                                            <a href="#" class="kt-badge__pic" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="Selina Cranson">
                                                <img src="{{ asset('dashboard/assets/media/users/100_2.jpg') }}" alt="image">
                                            </a>
                                            <a href="#" class="kt-badge__pic" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="Luke Walls">
                                                <img src="{{ asset('dashboard/assets/media/users/100_13.jpg') }}" alt="image">
                                            </a>
                                            <a href="#" class="kt-badge__pic" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="Micheal York">
                                                <img src="{{ asset('dashboard/assets/media/users/100_4.jpg') }}" alt="image">
                                            </a>
                                            <a href="#" class="kt-badge__pic kt-badge__pic--last kt-font-brand">
                                                +7
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Applications/User/Profile3-->    
        </div>
    </div>
    @endforeach
</div>

<div>
 <div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
           <div class="modal-content">
             <div class="modal-header border-bottom-0">
               <h5 class="modal-title" id="exampleModalLabel">Assigned Rider Id</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
               </button>
             </div>
             <form class="kt-form" id="form_dates"  enctype="multipart/form-data">
                 <div class="modal-body">
                     <div class="form-group">
<input type="hidden" name="rider_id" id="rider_id">
                     </div>
                         <div class="form-group">
                                 <label>Rider Id:</label>
                         <input type="text" id="body_value" class="form-control @if($errors->has('client_rider_id')) invalid-field @endif" name="client_rider_id" placeholder="Client Assign Rider Id"  >
                                </div>
                        </div>
                       
               <div class="modal-footer border-top-0 d-flex justify-content-center">
                 <button type="submit" id="submit_btn_dates" class="btn btn-success">Assign Id</button>
               </div>
             </form>
           </div>
         </div>
       </div>
    </div>
<!-- end:: Content -->
@endsection
@section('foot')
<script>
    var current_open_target=null;
     $("#form").on('shown.bs.modal', function (event){
        // 
        current_open_target=$(event.relatedTarget);
          var client_rider_id_val=$(event.relatedTarget).attr("data-client-rider-id"); 
          $('#body_value').val(client_rider_id_val);
          var rider_id_val=$(event.relatedTarget).attr("data-rider-id"); 
          $('#rider_id').val(rider_id_val);

       });
    $(document).ready(function(){
       // submit_btn_dates
        $('#form_dates').submit(function(e){
            e.preventDefault();
            var form=$(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
       $.ajax({
        headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                url:"{{route('ClientRiders.update')}}",
                data: form.serializeArray(),
                method: "POST"
            })
            .done(function(data) {  
                console.log(data);
                $('#form').modal('toggle');
                if(current_open_target){
                    current_open_target.attr("data-client-rider-id", data.client_rider_id);
                }
            });
            
        });
    });
</script>

@endsection
