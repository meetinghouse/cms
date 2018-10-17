<!DOCTYPE html>
<html lang="en" ng-app="app">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	@if($settings->enable_noindex)
		<meta name="robots" content="noindex">
	@endif
    <link rel="shortcut icon" href="../../assets/ico/favicon2.png">

    <title>
        @if(isset($seo))
        {!!$seo!!}
        @else
        {!!$settings->name!!}
        @endif
    </title>
	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('assets/css/font-awesome.css') }}" crossorigin="anonymous">
	<link rel="stylesheet" href="{{asset('assets/css/prettify.css') }}" crossorigin="anonymous">
	<link rel="stylesheet" href="{{asset('assets/css/main.css') }}" crossorigin="anonymous">
	<link rel="stylesheet" href="{{asset('assets/css/custom.css') }}" crossorigin="anonymous">
	<link rel="stylesheet" href="{{asset('assets/css/customProject.css') }}" crossorigin="anonymous">
	<link rel="stylesheet" href="{{asset('assets/css/houzz/css/houzz-icon-font.css') }}" crossorigin="anonymous">
	<link rel="stylesheet" href="{{asset('/bower_components/ng-tags-input/ng-tags-input.bootstrap.min.css') }}" crossorigin="anonymous">
	<link rel="stylesheet" href="{{asset('/bower_components/ng-tags-input/ng-tags-input.min.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('/bower_components/jquery-colorbox/example4/colorbox.css') }}" crossorigin="anonymous">
   
    @if($settings->theme == false)
		<link rel="stylesheet" href="{{asset('assets/css/colorfrog.css') }}" crossorigin="anonymous">
		<link rel="stylesheet" href="{{asset('assets/css/originalTheme.css') }}" crossorigin="anonymous">
    @endif
    @if($settings->theme == true)
    <link rel="stylesheet" href="{{asset('assets/css/dark.css') }}" crossorigin="anonymous">
	<link rel="stylesheet" href="{{asset('assets/css/flexslider.css') }}" crossorigin="anonymous">
    @endif
    




    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
	<script src="{{asset('assets/js/html5shiv.js') }}"></script>
    <script src="{{asset('assets/js/respond.js') }}"></script>
    <![endif]-->


    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '{!!$settings->google_analytics!!}']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

    </script>


</head>

<body class="{!!$settings->color!!}">
<!--
Use the corresponding body tag for your chosen theme
<body class="blue">
<body class="orange">
<body class="green">
<body class="bw">
-->

@if(Auth::user())
@include('shared.nav')
@endif
<!-- //start container -->
<div class="container">
    <header>
        @if($settings->logo && $settings->theme == false)
        <a href="/" id="logo">
			<img src='{{ asset("/img/settings/$settings->logo")}}' alt='{{$settings->name}}'/></a>
        @endif
    
		@if($settings->theme == false)
		@include('shared.top-nav')
		@endif
	</header>
</div>
<!-- //end container -->

<!-- start header -->

@if(isset($banner) && $banner == TRUE)
@include('shared.header')
@endif

<div class="row"><div class="span12"><hr></div></div>
<!-- //end header -->
<div class="container content main_content">
    <div class="row">
        <!--alerts-->
        @if (Session::has('message'))
        <div class="row clearfix">
            <div class="col-lg-12">
                @include('shared.alerts')
            </div>
        </div>
        @endif


        @if($settings->maintenance_mode == 1)
        <div class="alert alert-danger">
            Your site is in maintenance mode. Only logged in users can see the site.
            <br>
            Visit <a href="/settings/1/edit">Settings</a> to change it.
        </div>
        @endif
        @yield('content')
    </div>
	<!-- Delete confirm modal starts here -->
	<div id="delete_confirmation"  class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h2 class="title aligncenter">{!!$settings->name!!}</h2>
				</div>
				<div class="modal-body aligncenter">
					<p>Are you sure you want to delete this?</p>
				</div>
				<div class="modal-footer aligncenter">
					<p><button type="button" class="btn btn-info" data-dismiss="modal" id="delete">OK</button></p>
					<p><a class="btn-cancel" href="javascript:void(0)" data-dismiss="modal">Cancel</a></p>
				</div>
			</div>
		</div>
	</div>
<!-- modal ends here -->
</div>

@include('shared.footer')

</div><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script>
window.theme = {!!$settings->theme!!};
</script>
<script src="{{asset('assets/js/jquery-1.11.js') }}"></script>
<script src="{{asset('assets/js/respond.js') }}"></script>
	

<script src="{{asset('/assets/js/noty-2.2.2/js/noty/packaged/jquery.noty.packaged.min.js') }}"></script>
<script src="{{asset('/assets/js/jquery-sortable.js') }}"></script>
<script src="{{asset('/assets/js/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{asset('/assets/js/jquery.fitvids.js') }}"></script>
<script src="{{asset('/assets/js/colorfrog.js') }}"></script>
<script src="{{asset('/assets/js/lib/ckeditor-full/ckeditor.js') }}"></script>
<script src="{{asset('/bower_components/angular/angular.js') }}"></script>
<script src="{{asset('/bower_components/lodash/dist/lodash.js') }}"></script>
<script src="{{asset('/bower_components/restangular/dist/restangular.js') }}"></script>
<script src="{{asset('/bower_components/jquery-colorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{asset('/bower_components/readmore/readmore.min.js') }}"></script>
<!--<script src="{{asset('/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>-->
<!--<script src="{{asset('/bower_components/jquery-ui/ui/minified/sortable.min.js') }}"></script>-->
<!--<script src="{{asset('/bower_components/flow.js/dist/flow.js') }}"></script>-->
<script src="{{asset('/bower_components/ng-flow/dist/ng-flow-standalone.js') }}"></script>
<script src="{{asset('/assets/js/app.js') }}"></script>
<!--<script src="{{asset('/assets/js/cms_flow.js') }}"></script>-->
<script src="{{asset('/assets/js/angular_app.js') }}"></script>
<script src="{{asset('/assets/js/alertServices.js') }}"></script>
<script src="{{asset('/assets/js/uploadImagesCtrl.js') }}"></script>
<script src="{{asset('/assets/js/tagsCtrl.js') }}"></script>
<script src="{{asset('/bower_components/ng-tags-input/ng-tags-input.min.js') }}"></script>
@if($settings->theme == true)
<script src="{{asset('http://cdnjs.cloudflare.com/ajax/libs/jquery-backstretch/2.0.4/jquery.backstretch.min.js') }}"></script>
@endif

<script src="{{asset('/bower_components/flexslider/jquery.flexslider.js') }}"></script>
<script src="{{asset('/bower_components/angular-flexslider/angular-flexslider.js') }}"></script>
<script src="{{asset('/assets/js/naturalSortVersionDates.min.js') }}"></script>
<script src="{{asset('/assets/js/custom.js') }}"></script>
<script type="text/javascript">
	// Add padding to top of body tag if logged into admin on both themes
	jQuery(function($) {
		if ($('.navbar-fixed-top').length) {
			$('body').css('padding-top','30px');
		}
		if ($('.container.login').length) {
			$('.main_content').css('padding-top','initial');
		}
	// Functionality for mobile menu on dark theme.
		window_size = $(document).width() <= 767 ? true : false;
		if (window_size) {
			$('ul.nav-list').addClass('hide-menu');
			$('.border').addClass('hide-line');
			$('.mobile-menu a').click(function () {
				$('ul.nav-list').slideToggle();
				$('.border').toggleClass('hide-line');
				$('.mobile-menu a').toggleClass('active');
			});
		}
		// resizes gray background image on dark theme
		if ($('.navbar.navbar-fixed-top').length && (window_size)) {
				$('body').css('background-size', 'auto 149px');
				$('#social, .col-md-3').css('display','none');
		}
		// hides mobile menu on homepage for dark theme
		if($('body.home').length) {
 			$('ul.nav-list').css('display','block');
 			$('.mobile-menu').css('display','none');
 		}
	});
</script>
<script type="text/javascript">
	// Functionality for mobile menu on light theme.
	$(window).load(function(){
		var window_light_size = $(window).width() <= 767 ? true : false;
		if (window_light_size) {
			$('.mobile-menu.light-theme').show();
			$('#accordion').hide();
			$('.mobile-menu.light-theme a').click(function(){
				$('#accordion').slideToggle();
				$('.border').toggleClass('hide-line');
				$('.mobile-menu.light-theme a').toggleClass('active');
			});
		}
		else{
			$('.mobile-menu.light-theme').hide();
		}
	});
	

	$(document).on("click", ".delete", function(event){
		event.preventDefault();
		var $form=$(this).closest('form');
		$('#delete_confirmation').modal({ backdrop: 'static', keyboard: false })
			.one('click', '#delete', function() {
				$form.trigger('submit'); // submit the form
		});
	});
</script>
@yield('js')
</body>
</html>
