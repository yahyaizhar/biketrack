<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
    <div class="kt-header-mobile__logo">
        <a href="{{ route('admin.home') }}">
            {{-- <img alt="Logo" src="{{ asset('dashboard/assets/media/logos/logo-light.png') }}" /> --}}
            <img alt="Logo" style="width:15%;" src="{{ asset('dashboard/assets/media/logos/company-logo.png') }}" />
        </a>
    </div>
    <div class="kt-header-mobile__toolbar">
        <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
        <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>
        <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
    </div>
</div>