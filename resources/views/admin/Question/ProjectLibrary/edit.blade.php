@extends('admin.layouts.default')

@section('content')

<!-- CKeditor js and custom li js  strat here-->
{{ HTML::style('css/admin/custom_li_bootstrap.css') }}	
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor js and custom li js  end here--->
<section class="content-header">
	<h1>
		{{ trans("Edit Project") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href='{{route("$modelName.index")}}'>{{ trans("Library Management") }}</a></li>
		<li class="active">{{ trans("Edit Project") }}</li>
	</ol>
</section>
<section class="content"> 
	<div class="row pad">
		<div class="col-md-6">	
			{{ Form::open(['role' => 'form','route' => ["$modelName.update",$model->id],'class' => 'mws-form']) }}
			{{ Form::hidden('id', $model->id) }}
				<div class="form-group <?php  echo ($errors->first('author')) ? 'has-error' : ''; ?>">
					<div class="mws-form-row">
						{!! HTML::decode( Form::label('author',trans("Author Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text("author",$model->author, ['class' => 'form-control','id' => 'author']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('author'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group <?php echo ($errors->first('author_group')) ? 'has-error' : ''; ?>">
					<div class="mws-form-row">
						{!! HTML::decode( Form::label('author_group',trans("Author Group").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							<?php $userTypeList 	=	Config::get('user_type_list'); ?>
							{{ Form::text('author_group',$model->author_group,['class' => 'form-control']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('author_group'); ?>
							</div>
						</div>
					</div>
				</div>				
				<div class="form-group <?php  echo ($errors->first('project_name')) ? 'has-error' : ''; ?>">
					<div class="mws-form-row">
						{!! HTML::decode( Form::label('project_name',trans("Project Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text("project_name",$model->project_name, ['class' => 'form-control','id' => 'project_name']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('project_name'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="mws-button-row">
					<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
					
					<a href='{{route("$modelName.edit",$model->id)}}' class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear')  }}</a>
					
					<a href='{{route("$modelName.index")}}' class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
				</div>
			</div>
			{{ Form::close() }} 
		</div>
	</div>
</section>
@stop
