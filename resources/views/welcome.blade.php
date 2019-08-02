<!DOCTYPE html>
<html lang="en">
<head>

  <!-- SITE TITTLE -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KingRiders</title>
  
  <!-- PLUGINS CSS STYLE -->
  <!-- Bootstrap -->
  <link href="{{ asset('landing-page/plugins/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
  <!-- themify icon -->
  <link rel="stylesheet" href="{{ asset('landing-page/plugins/themify-icons/themify-icons.css') }}">
  <!-- Owl Carousel -->
  <link href="{{ asset('landing-page/plugins/owl-carousel/assets/owl.carousel.min.css') }}" rel="stylesheet" media="screen">
  <!-- Owl Carousel Theme -->
  <link href="{{ asset('landing-page/plugins/owl-carousel/assets/owl.theme.green.min.css') }}" rel="stylesheet" media="screen">
  <!-- Fancy Box -->
  <link href="{{ asset('landing-page/plugins/fancybox/jquery.fancybox.min.css') }}" rel="stylesheet">
  <!-- AOS -->
  <link rel="stylesheet" href="{{ asset('landing-page/plugins/aos/aos.css') }}">

  <!-- CUSTOM CSS -->
  <link href="{{ asset('landing-page/css/style.css') }}" rel="stylesheet">

  <!-- FAVICON -->
  <link href="{{ asset('landing-page/images/logo/company-logo.png') }}" rel="shortcut icon">

</head>

<body class="body-wrapper" data-spy="scroll" data-target=".privacy-nav">


<!--============================
=            Banner            =
=============================-->
<div class="section banner-full">
	<div class="container">
		<div class="row">
			
			<div class="col-lg-12 align-self-center text-center">
				<div class="block">
					<div class="logo">
						<a href="{{ url('/') }}"><img src="{{ asset('landing-page/images/logo/company-logo.png') }}" alt="logo"></a>
						{{-- <a href="{{ url('/') }}"><img src="{{ asset('landing-page/images/logo/dorbean-web-logo-black.png') }}" alt="logo"></a> --}}
					</div>
					<h1>Rider Tracking Application 
						<br></h1>
					<p>
						The simple, intuitive app that makes it easy 
						<br>to track live locations of riders.
					</p>
					<ul class="list-inline app-badge">
						{{-- <li class="list-inline-item">
							<a href="#"><img src="{{ asset('landing-page/images/app/appple-store.jpg') }}" alt="Apple Store"></a>
						</li>
						<li class="list-inline-item">
							<a href="#"><img src="{{ asset('landing-page/images/app/google-play.jpg') }}" alt="Google Play"></a>
                        </li> --}}
                        <li class="list-inline-item">
                            <a href="{{asset('application/Dorbean.apk')}}" class="btn btn-lg btn-primary">Download App</a>
                        </li>
					</ul>
					{{-- <div class="support">
						<img class="img-fluid" src="{{ asset('landing-page/images/icons/supported-services.png') }}" alt="supported-services">
					</div> --}}
				</div>
			</div>
		</div>
	</div>
</div>
<!--====  End of Banner  ====-->

<!--============================
=            Footer            =
=============================-->

{{-- <footer class="footer-classic">
  <ul class="social-icons list-inline">
    <li class="list-inline-item">
      <a href="https://www.facebook.com/themefisher"><i class="ti-facebook"></i></a>
    </li>
    <li class="list-inline-item">
      <a href="https://twitter.com/themefisher"><i class="ti-twitter"></i></a>
    </li>
    <li class="list-inline-item">
      <a href="https://www.instagram.com/themefisher/"><i class="ti-instagram"></i></a>
    </li>
    <li class="list-inline-item">
      <a href="https://dribbble.com/themefisher"><i class="ti-dribbble"></i></a>
    </li>
  </ul>
  <ul class="footer-links list-inline">
    <li class="list-inline-item">
      <a href="#">Download</a>
    </li>
    <li class="list-inline-item">
      <a href="blog.html">Blog</a>
    </li>
    <li class="list-inline-item">
      <a href="privacy-policy.html">Privacy</a>
    </li>
    <li class="list-inline-item">
      <a href="team.html">Developer</a>
    </li>
    <li class="list-inline-item">
      <a href="contact.html">Support</a>
    </li>
    <li class="list-inline-item">
      <a href="career.html">Career</a>
    </li>
  </ul>
</footer> --}}


  <!-- JAVASCRIPTS -->
  <script src="{{ asset('landing-page/plugins/jquery/jquery.js') }}"></script>
  <script src="{{ asset('landing-page/plugins/popper/popper.min.js') }}"></script>
  <script src="{{ asset('landing-page/plugins/bootstrap/bootstrap.min.js') }}"></script>
  <script src="{{ asset('landing-page/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('landing-page/plugins/fancybox/jquery.fancybox.min.js') }}"></script>
  <script src="{{ asset('landing-page/plugins/syotimer/jquery.syotimer.min.js') }}"></script>
  <script src="{{ asset('landing-page/plugins/aos/aos.js') }}"></script>
  <!-- google map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgeuuDfRlweIs7D6uo4wdIHVvJ0LonQ6g"></script>
  <script src="{{ asset('landing-page/plugins/google-map/gmap.js') }}"></script>
  
  <script src="{{ asset('landing-page/js/custom.js') }}"></script>
</body>

</html>
