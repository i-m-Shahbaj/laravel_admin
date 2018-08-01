<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<title>CMEShine</title>
<link rel="stylesheet" type="text/css" href="<?php echo WEBSITE_CSS_URL; ?>bootstrap.css" />
<link href="<?php echo WEBSITE_CSS_URL; ?>animate.css" rel="stylesheet" />
<link href="<?php echo WEBSITE_CSS_URL; ?>font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo WEBSITE_CSS_URL; ?>style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WEBSITE_CSS_URL; ?>responsive.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WEBSITE_CSS_URL; ?>custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WEBSITE_CSS_URL; ?>ecommerce.css" />
<?php $logo	=	CustomHelper::get_system_image('system-icon'); ?>
@if(!empty($logo->name) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$logo->name))
	<link href="<?php echo SYSTEM_IMAGE_URL.$logo->name; ?>" type="image/x-icon" rel="icon">
@endif
{{ HTML::style('css/admin/notification/jquery.toastmessage.css') }}
{{ HTML::script('js/jquery.min.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('css/admin/notification/jquery.toastmessage.js') }}
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>
{{ HTML::script('js/angular-sanitize.js') }}
 <script src="//code.angularjs.org/snapshot/angular.min.js"></script>
<script>
function show_message(message,message_type) {
	$().toastmessage('showToast', {	
		text: message,
		sticky: false,
		stayTime : 10000,
		position: 'top-right',
		type: message_type,
	});
}
	var liveApp = angular.module('liveApp',['ngSanitize'], function($interpolateProvider) {
		$interpolateProvider.startSymbol("<@");
		$interpolateProvider.endSymbol("@>");
	});
</script>
</head>
<body ng-app="liveApp">
@if(Session::has('error'))
	<script type="text/javascript">
		show_message("{{ Session::get('error')}}",'error');
	</script>
@endif
@if(Session::has('success'))
	<script type="text/javascript">
		show_message("{{ Session::get('success')}}",'success');
	</script>
@endif
@if(Session::has('flash_notice'))
	<script type="text/javascript">
		show_message("{{ Session::get('flash_notice')}}",'success');
	</script>
@endif
@yield('content')
<div id="loader_img">
<div class="web-loader">
<div class="loader-wrap"><img src="{{ WEBSITE_IMG_URL }}logo-white.png" alt="" />
<div class="loader-data"></div>
</div>
</div> <!-- /.web-loader END -->
</div>

<script>
$("input,textarea,select").focus(function(){
	if(typeof $(this).next('.error').html() !== "undefined") {
		$(this).next('.error').html(" ");
		$(this).next('.error').removeClass("error");
	}
});
$("input,textarea,select").keyup(function(event){
	if(typeof $(this).next('.error').html() !== "undefined") {
		$(this).next('.error').html(" ");
		$(this).next('.error').removeClass("error");
	}
});
// makes sure the whole site is loaded
jQuery(window).load(function() {
	jQuery(".preloader").delay(1000).fadeOut("slow");
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

</body>
</html>
