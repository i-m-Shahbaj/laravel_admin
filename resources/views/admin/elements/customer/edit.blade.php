@extends('admin.layouts.default')

@section('content')

<section class="content-header">
	<h1>
		{{ trans("Edit Customer") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{URL::to('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{URL::to('customers')}}">Customers</a></li>
		<li class="active">{{ trans("Edit Customer") }}</li>
	</ol>
</section>

<section class="content"> 
	<div class="row pad">
		{{ Form::open(['role' => 'form','url' => 'customers/edit-customer/'.$userDetails->id,'class' => 'mws-form','files'=>'true']) }}
		<div class="col-md-6">
			<div class="form-group">
			{!! HTML::decode( Form::label('name',trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text('name',$userDetails->full_name,["class"=>"form-control"]) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('name'); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				{!! HTML::decode( Form::label('email', trans("Email").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text('email',$userDetails->email,["class"=>"form-control"]) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('email'); ?>
					</div>
				</div>
			</div>
		</div> 
	</div>
	<div class="mws-button-row">
		<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-primary">
			
		<a href="{{URL::to('customers/edit-customer/'.$userDetails->id)}}" class="btn btn-danger"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset')  }}</a>
	</div>
	{{ Form::close() }}
</section>
@stop
