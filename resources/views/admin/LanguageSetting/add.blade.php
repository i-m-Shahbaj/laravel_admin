@extends('admin.layouts.default')

@section('content')
<section class="content-header">
	<h1>
		{{ trans("Add New Language Setting") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href='{{route("dashboard.showdashboard")}}'><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href='{{route("$modelName.index")}}'>Language Setting</a></li>
		<li class="active">Add New Language Setting</li>
	</ol>
</section>
<section class="content"> 
	<div class="row pad">
		<div class="col-md-6">
			<!--<div class="mws-panel-header">
				<span> {{ trans("messages.management.add_new_word") }}</span>
				<a href="{{URL::to('admin/language-settings')}}" class="btn btn-success btn-small align">{{ trans("messages.management.back_to_listing") }} </a>
			</div>-->
			{{ Form::open(['role' => 'form','route' => "$modelName.add",'class' => 'mws-form', 'files' => true]) }}
			<div class="mws-form-inline">
				<div class="form-group">
				
					{!! HTML::decode( Form::label('default',trans("Default").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text('default', '', ['class' => 'form-control']) }} 
						<div class="error-message help-inline">
							<?php echo $errors->first('default'); ?>
						</div>
					</div>
				</div>
				@if(!empty($languages))
					@foreach($languages as $key => $val)
						<div class="form-group">
							{!!  Form::label('email', $val->title, ['class' => 'mws-form-label']) !!}
							<div class="mws-form-item">
								{{ Form::text("language[$val->lang_code]",'', ['class' => 'form-control']) }} 
								<div class="error-message help-inline">
									<?php echo $errors->first('email'); ?>
								</div>
							</div>
						</div>
					@endforeach
				@endif
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
					<a href="{{URL::to('admin/language-settings/add-setting')}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('messages.user_management.reset') }}</a>
					<a href="{{URL::to('admin/users')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans("Cancel") }}</a>
				</div>
			</div>
		
		{{ Form::close() }}
	</div>    	
</div>
@stop
