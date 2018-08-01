@extends('admin.layouts.default')

@section('content')

<?php
	$userInfo	=	Auth::user();
	$full_name	=	(isset($userInfo->full_name)) ? $userInfo->full_name : '';
	$username	=	(isset($userInfo->username)) ? $userInfo->username : '';
	$email		=	(isset($userInfo->email)) ? $userInfo->email : '';
?>

<section class="content-header">
	<h1 class="text-center">Change Password
		<span class="pull-right">
			<a href="{{route($modelName.'.myaccount')}}" class="btn btn-danger"><i class=\"icon-refresh\"></i>Back</a>
		</span>
	</h1>
	
	<div class="clearfix"></div>
</section>
<section class="content">
	{{ Form::open(['role' => 'form','route' => $modelName.'.changedPassword','class' => 'mws-form','files'=>'true']) }}
	<div class="row pad">
		<div class="col-md-6">
			<div class="form-group <?php echo (!empty($errors->first('old_password'))?"has-error":''); ?>">
				{!! HTML::decode( Form::label('email', trans("messages.dashboard.old_password"), ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::password('old_password',['class' => 'form-control']) }}
					<!-- Toll tip div end here -->
					<div class="error-message help-inline">
						<?php echo $errors->first('old_password'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo (!empty($errors->first('new_password'))?"has-error":''); ?>">
				{!! HTML::decode( Form::label('email', trans("messages.dashboard.new_password"), ['class' => 'mws-form-label'])) !!}
				
				<div class="mws-form-item">
					{{ Form::password('new_password', ['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('new_password'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo (!empty($errors->first('confirm_password'))?"has-error":''); ?>">
				{!! HTML::decode( Form::label('email', trans("messages.dashboard.confirm_password"), ['class' => 'mws-form-label'])) !!}
				
				<div class="mws-form-item">
					{{ Form::password('confirm_password', ['class' => 'form-control']) }}
					
					<div class="error-message help-inline">
						<?php echo $errors->first('confirm_password'); ?>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
				<a href="{{route($modelName.'.changePassword')}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset')  }}</a>
			</div>	
		</div>
	</div>   	
	{{ Form::close() }}
</section>
@stop
