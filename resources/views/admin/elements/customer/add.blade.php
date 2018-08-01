@extends('admin.layouts.default')

@section('content')

<section class="content-header">
	<h1>
		{{ trans("Add New Customer") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{URL::to('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{URL::to('customers')}}">Customers</a></li>
		<li class="active">{{ trans("Add New Customer") }}</li>
	</ol>
</section>

<section class="content"> 
	<div class="row pad">
		{{ Form::open(['role' => 'form','url' => 'customers/add-customer','class' => 'mws-form','files'=>'true']) }}
		<div class="col-md-6">
			<div class="form-group">
			{!! HTML::decode( Form::label('name',trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text('name','',["class"=>"form-control"]) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('name'); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				{!! HTML::decode( Form::label('email', trans("Email").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text('email','',["class"=>"form-control"]) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('email'); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				{!! HTML::decode( Form::label('password', trans("messages.user_management.password").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item passwordHelp">
					{{ Form::password('password',['class'=>'userPassword form-control']) }}
					
					<span class='tooltipHelp' title="" data-html="true" data-placement="right" data-toggle="tooltip"  data-original-title="{{ trans('Password must have be minimum 6 characters and combination of numeric,alphabet and special characters') }}" style="cursor:pointer;">
						<i class="fa fa-question-circle fa-2x"> </i>
					</span>
					
					<div class="error-message help-inline">
						<?php echo $errors->first('password'); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
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
			
		<a href="{{URL::to('customers/add-customer')}}" class="btn btn-danger"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset')  }}</a>
	</div>
	{{ Form::close() }}
</section>
@stop
