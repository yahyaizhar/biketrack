<head>

		<!--begin::Base Path (base relative path for assets of this page) -->
		<base href="../">

		<!--end::Base Path -->
		<meta charset="utf-8" />
		<title>Administrator | Dashboard</title>
		<meta name="description" content="Updates and statistics">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!--begin::Fonts -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		</script>

		<!--end::Fonts -->
		{{-- <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet"> --}}

		<!--begin::Page Vendors Styles(used by this page) -->
		<link href="{{ asset('dashboard/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />

		<!--end::Page Vendors Styles -->

		<!--begin:: Global Mandatory Vendors -->
		<link href="{{ asset('dashboard/assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" type="text/css" />

		<!--end:: Global Mandatory Vendors -->

		<!--begin:: Global Optional Vendors -->
		
		<style>
				/*Checkboxes styles*/
						input[type="checkbox"] { display: none; }
						
						input[type="checkbox"] + label {
						  display: block;
						  position: relative;
						  padding-left: 35px;
						  margin-bottom: 20px;
						  font: 14px/20px 'Open Sans', Arial, sans-serif;
						  color: black;
						  cursor: pointer;
						  -webkit-user-select: none;
						  -moz-user-select: none;
						  -ms-user-select: none;
						}
						
						input[type="checkbox"] + label:last-child { margin-bottom: 0; }
						
						input[type="checkbox"] + label:before {
						  content: '';
						  display: block;
						  width: 20px;
						  height: 20px;
						  border: 1px solid black;
						  position: absolute;
						  left: 0;
						  top: 0;
						  opacity: .6;
						  -webkit-transition: all .12s, border-color .08s;
						  transition: all .12s, border-color .08s;
						}
						
						input[type="checkbox"]:checked + label:before {
						  width: 10px;
						  top: -5px;
						  left: 5px;
						  border-radius: 0;
						  opacity: 1;
						  border-top-color: transparent;
						  border-left-color: transparent;
						  -webkit-transform: rotate(45deg);
						  transform: rotate(45deg);
						}
						#hover_Checkbox:hover{
							background-color: white;
						}
						#hover_Checkbox1:hover{
							background-color: white;
						}
						#hover_Checkbox{
							width:18%;
							margin-left:82%;
							margin-bottom:10px;
						}
						#hover_Checkbox1{
							width:18%;
							margin-left:82%;
							margin-bottom:10px;
						}
						@media screen and (max-width: 720px) {
							#hover_Checkbox {
    margin-left: 37%;
    margin-bottom: 10px;
    width: auto; 
  }
  #hover_Checkbox1 {
    margin-left: 37%;
    margin-bottom: 10px;
    width: auto; 
	max-width: fit-content;
  }
}
			.psbScroll .dropdown-menu{
				max-height: 16rem;
				overflow-y: scroll;		

			}			

						</style>
						<link href="//cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.css" type="text/css" rel="stylesheet" />
		<link href="{{ asset('dashboard/assets/vendors/general/tether/dist/css/tether.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/select2/dist/css/select2.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/ion-rangeslider/css/ion.rangeSlider.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/nouislider/distribute/nouislider.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/owl.carousel/dist/assets/owl.carousel.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/dropzone/dist/dropzone.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/summernote/dist/summernote.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/animate.css/animate.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/morris.js/morris.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/sweetalert2/dist/sweetalert2.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/socicon/css/socicon.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/custom/vendors/line-awesome/css/line-awesome.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/custom/vendors/flaticon/flaticon.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/custom/vendors/flaticon2/flaticon.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css" />

		<!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="{{ asset('dashboard/assets/css/demo1/style.bundle.css') }}" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link href="{{ asset('dashboard/assets/css/demo1/skins/header/base/light.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/css/demo1/skins/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/css/demo1/skins/brand/dark.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('dashboard/assets/css/demo1/skins/aside/dark.css') }}" rel="stylesheet" type="text/css" />

		<!--end::Layout Skins -->
		{{-- <link rel="shortcut icon" href="{{ asset('dashboard/assets/media/logos/dorbean-favicon.png') }}" /> --}}
		<link rel="shortcut icon" href="{{ asset('dashboard/assets/media/logos/company-logo.png') }}" />
		<link href="{{ asset('dashboard/assets/css/custom/custom.css') }}" rel="stylesheet" type="text/css" />
	</head>
	@section('head')
		
	@show