@extends('admin.layouts.default')
@section('content')

<!-- CKeditor start here-->
{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}

<!-- CKeditor ends-->

<section class="content-header">
	<h1>
		{{ trans("Edit Tutorial") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">Tutorials</a></li>
		<li class="active">Edit Tutorial</li>
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
			{{ Form::open(['role' => 'form','url' =>  route("$modelName.edit","$model->id"),'class' => 'mws-form', 'files' => true]) }}
			{{ Form::hidden('id', $model->id) }}
				<div class="form-group <?php echo ($errors->first('youtube_url')?'has-error':''); ?>">
					<div class="mws-form-row">
					{!! HTML::decode( Form::label('youtube_url', trans("Youtube Url").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text('youtube_url',$model->youtube_url, ['class' => 'form-control small']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('youtube_url'); ?>
							</div>
						</div>
					</div>	
				</div>
				
				<div class="form-group <?php echo ($errors->first('image')?'has-error':''); ?>">
					<div class="mws-form-row ">
						{!! HTML::decode( Form::label('image', trans("Image").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::file('image') }}
							<?php 
							$image	=	Input::old('image');
							$image	=	isset($image) ? $image : $model->image;
							?>
							@if($image && File::exists(TUTORIAL_IMAGE_ROOT_PATH.$model->image))
								{{ HTML::image( TUTORIAL_IMAGE_URL.$model->image, $model->image , array( 'width' => 70, 'height' => 70 )) }}
							@endif
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
							{{ Form::text("order",isset($model->tutorial_order)?$model->tutorial_order:'', ['class' => 'form-control small']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('order'); ?>
							</div>
						</div>
					</div>
				</div>
				
				<div class="form-group <?php echo ($errors->first('description')?'has-error':''); ?>">
					<div class="mws-form-row ">
						{!! HTML::decode( Form::label('description', trans("Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::textarea("description",isset($model->description)?$model->description:'', ['class' => 'small','id' => 'description']) }}
							<span class="error-message help-inline">
								<?php echo $errors->first('description'); ?>
							</span>
						</div>
						<script type="text/javascript">
							/* CKEDITOR for description */
							CKEDITOR.replace( <?php echo 'description'; ?>,
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
				<br />
				<div class="mws-button-row">
					<input type="submit" value="{{ trans('messages.global.save') }}" class="btn btn-danger">
					<a  href='{{ route("$modelName.edit","$model->id")}}' class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
					<a href='{{ route("$modelName.index")}}' class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
				</div>
			</div>
			{{ Form::close() }} 
		</div>
	</div>
</section>
@stop
