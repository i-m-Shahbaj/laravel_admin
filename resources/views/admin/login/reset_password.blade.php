@extends('admin.layouts.login_layout')

@section('content')

<div class="form-box" id="login-box">
	<div class="header">Reset Password</div>
	{{ Form::open(['role' => 'form','route' => 'login.resetPassword']) }}
		{{ Form::hidden('validate_string',$validate_string, []) }}
	<div class="body bg-gray">
		<div class="form-group">
			{{ Form::password('new_password',  ['placeholder' => 'New Password', 'class' => 'form-control']) }}
			<div class="error-message help-inline">
				<?php echo $errors->first('new_password'); ?>
			</div>
		</div>
		<div class="form-group">
		   {{ Form::password('new_password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) }}
		   <div class="error-message help-inline">
				<?php echo $errors->first('new_password_confirmation'); ?>	
			</div>
		</div>
	</div>
	<div class="footer">                                                               
		<button type="submit" class="btn bg-olive btn-block">Submit</button> 
	</div>
	{{ Form::close() }}
</div>

@stop

