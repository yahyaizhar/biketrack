@extends('admin.layouts.app')
@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        
        <h3 class="kt-subheader__title">{{ $rider->name }}</h3>

        <span class="kt-subheader__separator kt-subheader__separator--v"></span>

        <span class="kt-subheader__desc">Clients</span>
        <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
            <input type="text" class="form-control" placeholder="Search order..." id="generalSearch">
            <span class="kt-input-icon__icon kt-input-icon__icon--right">
                    <span><i class="flaticon2-search-1"></i></span>
            </span>
        </div>
    </div>
</div>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content" style="margin-top:60px;">

    @if(count($clients) > 0)
        @foreach ($clients as $client)
        <div class="row">
            <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-3">
                            <div class="kt-widget__top">
                                <div class="kt-widget__content">
                                    <div class="kt-widget__head">
                                        <a class="kt-widget__username">
                                            {{ $client->name }}
                                            @if ($client->active_status=="A")
                                                <i class="flaticon2-correct"></i>                                            
                                            @endif
                                        </a>
                                        <div class="kt-widget__action">
                                            <button onclick="deleteRider({{$client->id}}, {{$rider->id}})" class="btn btn-label-info btn-sm btn-upper">Remove</button>&nbsp;
                                        </div>
                                    </div>
                
                                    <div class="kt-widget__subhead">
                                        <a href="mailto:{{ $client->email }}"><i class="flaticon2-new-email"></i>{{ $client->email }}</a>
                                        <a><i class="flaticon2-calendar-3"></i>{{ $client->phone }} </a>
                                    </div>
                
                                    <div class="kt-widget__info">
                                        <i class="flaticon-location"></i>&nbsp;
                                        <div class="kt-widget__desc">
                                            {{ $client->address }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        @endforeach
    @else
    <div class="kt-section__content">
        <div class="alert alert-danger fade show" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">No client assigned yet.</div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
@section('foot')
    <script>
        function deleteRider(client_id, rider_id)
        {
            var url = "{{ url('admin/client') }}" + "/" + client_id + "/removeRider/" + rider_id;
            console.log(url);
            sendDeleteRequest(url, true);
        }
    </script>
@endsection