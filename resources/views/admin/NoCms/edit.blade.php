@extends('admin.layouts.default')
@section('content')
<!--set width of  Select box on date picker -->
<section class="content-header">
	<h1>
		{{ trans("Edit Seo Page") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">{{ trans("Seo Page") }}</a></li>
		<li class="active">{{ trans("Edit Seo Page") }} </li>
	</ol>
</section>
<section class="content"> 
	{{ Form::open(['role' => 'form','route' => [$modelName.'.edit',$doc->id],'class' => 'mws-form','enctype'=> 'multipart/form-data']) }}
	<div class="row pad">
		<div class="col-md-6">
			<div class="form-group <?php echo ($errors->first('page_id')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('page_id',trans("Page ID").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text('page_id',isset($doc->title)?$doc->page_id:'',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('page_id'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('page_name')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('page_name',trans("Page Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text('page_name',isset($doc->page_name)?$doc->page_name:'',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('page_name'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('title')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('title',trans("messages.system_management.title").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text('title',isset($doc->title)?$doc->title:'',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('title'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('meta_description')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('meta_description',trans("Meta Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::textarea('meta_description',isset($doc->meta_description)?$doc->meta_description:'',['class' => 'form-control','rows'=>false,'cols'=>false]) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('meta_description'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('meta_keywords')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('meta_keywords',trans("Meta Keywords").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::textarea('meta_keywords',isset($doc->meta_keywords)?$doc->meta_keywords:'',['class' => 'form-control','rows'=>false,'cols'=>false]) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('meta_keywords'); ?>
					</div>
				</div>
			</div>
		</div>		
	</div>
	<div class="mws-button-row">
		<div class="input" >
			<input type="submit" value="{{ trans('messages.user_management.save') }}" class="btn btn-danger">
			<a href="{{route($modelName.'.edit',$doc->id)}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans("messages.user_management.reset") }}</a>
			<a href="{{route($modelName.'.index')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans("Cancel") }}</a>
		</div>
	</div>
	{{ Form::close() }}
</section>
@stop
