@extends('admin.layouts.app')
@section('main-content')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        
        <h3 class="kt-subheader__title">{{ $rider->name }}</h3>

        <span class="kt-subheader__separator kt-subheader__separator--v"></span>

        <span class="kt-subheader__desc">Rider Spell Time</span>
        <div class="kt-input-icon kt-input-icon--right kt-subheader__search kt-hidden">
            <input type="text" class="form-control" placeholder="Search order..." id="generalSearch">
            <span class="kt-input-icon__icon kt-input-icon__icon--right">
                    <span><i class="flaticon2-search-1"></i></span>
            </span>
        </div>
    </div> 
</div>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content" style="margin-top:60px;">
@if (isset($dates))
    @foreach ($dates as $date) 
        <div class="row">
            <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-3">
                            <div class="kt-widget__top">
                                <div class="kt-widget__content">
                                    <div class="kt-widget__head">
                                        <a class="kt-widget__username">
                                           {{ $rider->name }}
                                        </a>
                                        <div class="kt-widget__action">
                                        </div>
                                    </div>
                                    <div class="kt-widget__subhead">
                                       <strong>Start Date:</strong> {{ $date['start_time'] }}
                                    </div>
                
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__desc">
                                            @if ($date['end_time']!="")
                                            <strong>End Date:</strong> {{ $date['end_time'] }}
                                            @else
                                            <strong>End Date:</strong> Currently Working...
                                            @endif 
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
                <div class="alert-text">No Time assigned yet.</div>
                <div class="alert-close">
                </div>
            </div>
        </div>  
@endif
</div>
@endsection
@section('foot')
@endsection