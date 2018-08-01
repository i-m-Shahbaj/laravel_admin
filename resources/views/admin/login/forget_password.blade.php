@extends('admin.layouts.login_layout')

@section('content')

<div class="form-box" id="login-box">
	<div class="header">Forgot Password</div>
	{{ Form::open(['role' => 'form','route' => 'login.sendPassword']) }}
	<div class="body bg-gray">
		<div class="form-group">
			{{ Form::text('email', null, ['placeholder' => 'Email','class'=>'form-control']) }}
			<div class="error-message help-inline">
				<?php echo $errors->first('email'); ?>
			</div>
		</div>
	</div>
	<div class="footer">                                                               
		<button type="submit" class="btn bg-olive btn-block">Submit</button> 
		<a class="btn bg-olive btn-block"  href="{{ route('login.index')}}">Cancel</a>
	</div>
	{{ Form::close() }}
</div>
