@extends('admin.layouts.login_layout')

@section('content')

<div class="form-box" id="login-box">
	<div class="header">Login</div>
	{{ Form::open(['role' => 'form','route' => 'login.index']) }}    
	<div class="body bg-gray">
		<div class="form-group">
			{{ Form::text('email', null, ['placeholder' => 'Email/Username', 'class' => 'form-control']) }}
			<div class="error-message help-inline">
				<?php echo $errors->first('email'); ?>
			</div>
		</div>
		<div class="form-group">
		   {{ Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control','autocomplete'=>false]) }}
		   <div class="error-message help-inline">
				<?php echo $errors->first('password'); ?>
			</div>
		</div>
		@if(Session::get('failed_attampt_login') >= 11)
			<div class="form-group">
				{{ Form::text('captcha', null, ['placeholder' => 'Captcha Code', 'class' => 'form-control']) }}
				<div class="error-message help-inline">
					<?php echo $errors->first('captcha'); ?>
				</div>
				<?php echo captcha_img('flat');?>
			</div>
		@endif
		
	</div>
	<div class="footer">                                                               
		<button type="submit" class="btn bg-olive btn-block">Login</button> 
		<a href="{{ route('login.forgetPassword')}}">Forgot your password?</a>
	</div>
	{{ Form::close() }}
</div>
