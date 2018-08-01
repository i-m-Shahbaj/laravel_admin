@extends('admin.layouts.default')
@section('content')
{{ HTML::script('js/admin/plugins/ckeditor/ckeditor.js') }}
<section class="content-header">
	<h1>
		{{ trans("Add New Block") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">Block Management</a></li>
		<li class="active">Add New Block</li>
	</ol>
</section>
<section class="content">
<div class="row pad"> 
	<div class="col-md-12">
		<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
	</div> 
</div> 
	<div class="row pad">
		<div class="col-md-6">	
			@if(count($languages) > 1)
				<div  class="default_language_color">
					{{ Config::get('default_language.message') }}
				</div>
				<div class="wizard-nav wizard-nav-horizontal">
					<ul class="nav nav-tabs">
						<?php $i = 1 ; ?>
						@foreach($languages as $value)
							<li class=" {{ ($i ==  $language_code )?'active':'' }}">
								<a data-toggle="tab" href="#{{ $i }}div">
									{{ $value -> title }}
								</a>
							</li>
							<?php $i++; ?>
						@endforeach
					</ul>
				</div>
			@endif
			{{ Form::open(['role' => 'form','route' => "$modelName.save",'class' => 'mws-form', 'files' => true]) }}
				
				<div class="form-group <?php echo ($errors->first('page_name')?'has-error':''); ?>">
					<div class="mws-form-row">
					{!! HTML::decode( Form::label('page_name', trans("messages.$modelName.page_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('page_name','', ['class' => 'form-control small']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('page_name'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group <?php echo ($errors->first('block_name')?'has-error':''); ?>">
					<div class="mws-form-row ">
						{!! HTML::decode( Form::label('block_name', trans("messages.$modelName.block_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text("block_name",'', ['class' => 'form-control small']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('block_name'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group <?php echo ($errors->first('image')?'has-error':''); ?>">
					<div class="mws-form-row ">
						{!! HTML::decode( Form::label('image', trans("Image").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::file('image') }}
							<div class="error-message help-inline">
								<?php echo $errors->first('image'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group <?php echo ($errors->first('order')?'has-error':''); ?>">
					<div class="mws-form-row ">
						{!! HTML::decode( Form::label('order', trans("Order").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text("order",'', ['class' => 'form-control small']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('order'); ?>
							</div>
						</div>
					</div>
				</div>
				@if(count($languages) > 1)
					<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
						<hr class ="hrLine"/>
						<b>{{ trans("messages.system_management.language_field") }}</b>
					</div>
				@endif
				<div class="mws-panel-body no-padding tab-content"> 
				<?php $i = 1 ; ?>
			@foreach($languages as $value)
				<div id="{{ $i }}div" class="tab-pane {{ ($i ==  $language_code )?'active':'' }} ">
					<div class="form-group <?php if($i == 1) {echo ($errors->first('description')?'has-error':'');} ?>">
						<div class="mws-form-row ">
							@if($i == 1)
							{!! HTML::decode( Form::label($i.'_body', trans("Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							@else
							{!! HTML::decode( Form::label($i.'_body', trans("Description").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
							@endif
							<div class="mws-form-item">
								{{ Form::textarea("data[$i][description]",'', ['class' => 'small','id' => 'description'.$i]) }}
								<span class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('description') : ''; ?>
								</span>
							</div>
							<script type="text/javascript">
								/* CKEDITOR fro description */
								CKEDITOR.replace( <?php echo 'description'.$i; ?>,
								{
									height: 350,
									width: 507,
									filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
									filebrowserImageWindowWidth : '640',
									filebrowserImageWindowHeight : '480',
									enterMode : CKEDITOR.ENTER_BR
								});
								CKEDITOR.config.allowedContent = true;	
									
							</script>
						</div>
					</div>
				</div>
				<?php $i++ ; ?>
				@endforeach
			</div>
				
			<div class="mws-panel-body no-padding tab-content"> 
				<br />
				<div class="mws-button-row">
					<input type="submit" value="{{ trans('messages.global.save') }}" class="btn btn-danger">
					
					<a href="{{ route($modelName.'.add')}}" class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
					
					<a href="{{URL::to('admin/block-manager')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
				</div>
			</div>
			{{ Form::close() }} 
		</div>
	</div>
</section>
@stop
