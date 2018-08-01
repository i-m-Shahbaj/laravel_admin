<!DOCTYPE html>
<html>
	<head>
		<title><?php echo Config::get("Site.title"); ?></title>
		{{ HTML::style('css/admin/bootstrap.min.css') }}
		{{ HTML::style('css/admin/font-awesome.min.css') }}
		{{ HTML::style('css/admin/AdminLTE.css') }}
		
		{{ HTML::script('js/admin/jquery.min.js') }}
	</head>
	<body class="bg-black">
		@if(Session::has('error'))
			<div class="box-body01"> 
				<div class="alert alert-danger alert-dismissable">
					<i class="fa fa-ban"></i>
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> {{ Session::get('error') }}
				</div>
			</div>
			
		@endif
		
		@if(Session::has('success'))
			<div class="box-body01"> 
				<div class="alert alert-success alert-dismissable">
					<i class="fa fa-check"></i>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					{{ Session::get('success') }}
				</div>
			</div>
			
		@endif

		@if(Session::has('flash_notice'))
			<div class="box-body01"> 
				<div class="alert alert-success alert-dismissable">
					<i class="fa fa-check"></i>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					{{ Session::get('flash_notice') }}
				</div>
			</div>
		@endif
		@yield('content')
		
		{{ HTML::script('js/admin/core/mws.js') }}
		{{ HTML::script('js/admin/core/themer.js') }}
		{{ HTML::script('js/admin/bootstrap.min.js') }}
		{{ HTML::script('js/admin/app.js') }}
		<style type="text/css">
			.error-message{
				color:#f56954 !important;
			}
		</style>
	</body>
</html>