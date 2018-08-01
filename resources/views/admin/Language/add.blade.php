@extends('admin.layouts.default')
@section('content')
<section class="content-header">
	<h1>
		{{ trans("Add Language") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{URL::to('admin/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{URL::to('admin/language')}}">{{ trans("Language") }}</a></li>
		<li class="active">{{ trans("Add Language") }} </li>
	</ol>
</section>
<section class="content"> 
	{{ Form::open(['role' => 'form','url' =>'admin/language/save-language','class' => 'mws-form', 'files' => true]) }}
	<div class="row pad">
		<div class="col-md-6">
			<div class="form-group <?php echo ($errors->first('title')) ? 'has-error' : ''; ?>">
				{{ HTML::decode( Form::label('title',trans("Title").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('title','',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('title'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('languagecode')) ? 'has-error' : ''; ?>">
				{{ HTML::decode( Form::label('languagecode',trans("Language Code").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('languagecode','',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('languagecode'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('foldercode')) ? 'has-error' : ''; ?>">
				{{ HTML::decode( Form::label('foldercode',trans("Folder Code").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('foldercode','',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('foldercode'); ?>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.user_management.save') }}" class="btn btn-danger">
					<a href="{{URL::to('admin/language/add-language')}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans("messages.user_management.reset") }}</a>
					<a href="{{URL::to('admin/language')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans("Cancel") }}</a>
				</div>
			</div>
		</div>
		{{ Form::close() }}
	</div>
</section>
@stop