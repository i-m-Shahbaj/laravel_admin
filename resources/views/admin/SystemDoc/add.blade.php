@extends('admin.layouts.default')
@section('content')
<!--set width of  Select box on date picker -->
<section class="content-header">
	<h1>
		{{ trans("Add System Image") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">{{ trans("System Images") }}</a></li>
		<li class="active">{{ trans("Add System Image") }} </li>
	</ol>
</section>
<section class="content"> 
<div class="row pad"> 
	<div class="col-md-12">
		<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
	</div> 
</div>
	{{ Form::open(['role' => 'form','route' => $modelName.'.add','class' => 'mws-form','enctype'=> 'multipart/form-data']) }}
	<div class="row pad">
		<div class="col-md-6">
			<div class="form-group <?php echo ($errors->first('title')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('title',trans("messages.system_management.title").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text('title','',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('title'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('file')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('file',trans("Image").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!} <a class='tooltipHelp' title="" data-html="true" data-toggle="tooltip" data-placement="right"  data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;">
						<i class="fa fa-question-circle fa-2x"> </i>
					</a>
				<div class="mws-form-item">
					{{ Form::file('file') }}
					<div class="error-message help-inline">
						<?php echo $errors->first('file'); ?>
					</div>
				</div>
			</div>
		</div>		
	</div>
	<div class="mws-button-row">
		<div class="input" >
			<input type="submit" value="{{ trans('messages.user_management.save') }}" class="btn btn-danger">
			<a href="{{route($modelName.'.add')}}" class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans("Clear") }}</a>
			<a href="{{route($modelName.'.index')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans("Cancel") }}</a>
		</div>
	</div>
	{{ Form::close() }}
</section>
@stop
