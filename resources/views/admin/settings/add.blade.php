@extends('admin.layouts.default')
@section('content')
<section class="content-header">
	<h1>
		{{ trans("Add New Setting") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="{{route($modelName.'.listSetting')}}">Setting</a></li>
		<li class="active">Add New Setting</li>
	</ol>
</section>
<section class="content"> 
	<div class="row pad">
		<div class="col-md-6">
		{{ Form::open(['role' => 'form','route' => $modelName.'.add','class' => 'mws-form']) }}
			<div class="mws-panel-body no-padding tab-content">
				<div class="form-group <?php echo ($errors->first('title')?'has-error':''); ?>">
					<div class="mws-form-row">
						{!! HTML::decode( Form::label('title',trans("Title").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('title', null, ['class' => 'form-control']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('title'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group <?php echo ($errors->first('key')?'has-error':''); ?>">
					<div class="mws-form-row">
						{!! HTML::decode( Form::label('key',trans("Key").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('key', null, ['class' => 'form-control']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('key'); ?>
							</div>
							<small>e.g., 'Site.title'</small>
						</div>
					</div>
				</div>
				<div class="form-group <?php echo ($errors->first('value')?'has-error':''); ?>">
					<div class="mws-form-row">
						{!! HTML::decode( Form::label('value',trans("Value").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::textarea('value', null, ['class' => 'form-control small','rows'=>false,'cols'=>false,]) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('value'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group <?php echo ($errors->first('input_type')?'has-error':''); ?>">
					<div class="mws-form-row">
						{!! HTML::decode( Form::label('input_type',trans("Input Type").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('input_type', null, ['class' => 'form-control']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('input_type'); ?>
							</div>
							<small><em><?php echo "e.g., 'text' or 'textarea'";?></em></small>
						</div>
					</div>
				</div>
				<div class="form-group ">
					<div class="mws-form-row">
						{!!  Form::label('editable', 'Editable', ['class' => 'mws-form-label']) !!}
						<div class="mws-form-item">
							<div class="input-prepend">
								<span class="add-on"> 
									{{ Form::checkbox('editable',1, true, ['class' => 'small']) }}
								</span>
								<input type="text" size="16" name="prependedInput2" id="prependedInput2" value="<?php echo "Editable"; ?>" disabled="disabled" style="width:415px;" class="small">
							</div>
						</div>
					</div>
				</div>
				<div class="mws-button-row">
					<input type="submit" value="Save" class="btn btn-danger">
					
					<a href="{{route($modelName.'.add')}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset')  }}</a>
					
					<a href="{{route($modelName.'.listSetting')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
				</div>
			</div>
		{{ Form::close() }}
		</div>    	
	</div>
</section>
@stop
