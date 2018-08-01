@extends('admin.layouts.default')

@section('content')

{{ HTML::style('css/admin/jquery.ui.datepicker.css') }}
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::script('js/admin/jquery-ui-1.9.2.min.js') }}
<script type="text/javascript">
	$(document).ready(function() {
		$("#expiration_date").datepicker({
			inline: 	true ,
			minDate	  : 0 ,
			dateFormat: 'yy-mm-dd',
			numberOfMonths	: 1,
			changeMonth:true,
			changeYear:true,
		});
	});
		
</script>

<section class="content-header">
	<h1>
		{{ trans("Change Password") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('home.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">Users</a></li>
		<li class="active">{{ trans("Change Password") }}</li>
	</ol>
</section>

<section class="content"> 
	<div class="row pad">
		{{ Form::open(['role' => 'form','route' => $modelname.'.changePassword.'.$userDetails->id,'class' => 'mws-form','files'=>'true']) }}
		<div class="col-md-6">
			<div class="form-group <?php echo ($errors->first('password')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('password', trans("messages.user_management.password").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item passwordHelp">
					{{ Form::password('password',['class'=>'userPassword form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('password'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('confirm_password')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('confirm_password', trans("messages.user_management.repassword").'<span class="requireRed"> * 	</span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::password('confirm_password',["class"=>"form-control"]) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('confirm_password'); ?>
					</div>
				</div>
			</div>
		</div> 
	</div>
	<div class="mws-button-row">
		<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-primary">
	</div>
	{{ Form::close() }}
</section>
<style type="text/css">
	.textarea_resize {
		resize: vertical;
	}
</style>
@stop
