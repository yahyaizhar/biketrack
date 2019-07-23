@extends('admin.layouts.app')
@section('main-content')

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <!--Begin:: Portlet-->
            <div class="kt-portlet kt-portlet--head-noborder">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title  kt-font-danger">
                            {{ $email->from }}
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <span class="kt-badge kt-badge--bolder kt-badge kt-badge--inline kt-badge--danger">{{ $email->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="kt-portlet__body kt-portlet__body--fit-top">
                    <div class="kt-section kt-section--space-sm">
                        <h4>{{$email->subject}}</h4>
                    </div>
                    <div class="kt-section kt-section--space-sm">
                        {{ $email->message }}
                    </div>
                    <div class="kt-section kt-section--last">
                        {{-- <a href="#" class="btn btn-brand btn-sm btn-bold"><i class=""></i> Set up</a>&nbsp;
                        <a href="#" class="btn btn-clean btn-sm btn-bold">Dismiss</a> --}}
                    </div>
                </div>
            </div>
            <!--End:: Portlet-->
        </div>
    </div>
</div>
@endsection