@extends('admin.layouts.default')

@section('content')

<!-- CKeditor js and custom li js  strat here-->
{{ HTML::style('css/admin/custom_li_bootstrap.css') }}	
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor js and custom li js  end here--->
<section class="content-header">
	<h1>
		{{ trans("Add Category") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route('ProjectFolder.index')}}">Blog Management</a></li>
		<li><a href="{{route('ProjectFolder.index')}}">Cateogories</a></li>
		<li class="active">{{ trans("Add Category") }}</li>
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
			{{ Form::open(['role' => 'form','route' => ["$modelName.save"],'class' => 'mws-form','files'=>'true']) }}
<!--
				<div class="form-group <?php echo ($errors->first('type')) ? 'has-error' : ''; ?>">
					<div class="mws-form-row">
						{!! HTML::decode( Form::label('type',trans("Type").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							@if(empty($listfolders))
								{{ Form::select('type',array('0'=>'Main Folder')+$listfolders,'null',['class' => 'form-control']) }}
							@else
								{{ Form::select('type',array(""=>'Please Select Main Folder')+$listfolders,'null',['class' => 'form-control']) }}
							@endif
							<div class="error-message help-inline">
								<?php echo $errors->first('type'); ?>
							</div>
						</div>
					</div>
				</div>	
-->
				<div class="form-group <?php  echo ($errors->first('name')) ? 'has-error' : ''; ?>">
					<div class="mws-form-row">
						{!! HTML::decode( Form::label('name',trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text("name",'', ['class' => 'form-control','id' => 'name']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('name'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group image_section">
					<div class="mws-form-row">
						{!!  HTML::decode( Form::label('image', trans("Featured Image"), ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::file('image','',['class' => 'form-control image valid','type'=>'file']) }}
							<div class="error-message help-inline" id="question_image_error">
								<?php echo $errors->first('image'); ?>
							</div>
							<div class="image_display" style="display:none;">
								<img id="blah" src="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>" width='100' height="100" />
							</div>
						</div>
					</div>
				</div>
				<div class="preview"></div>
				<div class="row pad">	
					<div class="col-md-6">
						<div class="form-group <?php echo $errors->first('description')?'has-error':''; ?>">
							<div class="mws-form-row ">
								{!! HTML::decode( Form::label('description', trans("Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
									{{ Form::textarea("description",'', ['class' => 'small','id' => 'description']) }}
									<span class="error-message help-inline">
									<?php echo $errors->first('description'); ?>
									</span>
								</div>
								<script type="text/javascript">
									/* CKEDITOR fro description */
									CKEDITOR.replace( <?php echo 'description'; ?>,
									{
										height: 350,
										width: 507,
										filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
										filebrowserImageWindowWidth : '640',
										filebrowserImageWindowHeight : '480',
										enterMode : CKEDITOR.ENTER_BR
									});
										
								</script>
							</div>
						</div>
					</div>
				</div>
				<div class="mws-button-row">
					<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
					
					<a href='{{route("$modelName.add")}}' class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear')  }}</a>
					
					<a href='{{route("$modelName.index")}}' class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
				</div>
			</div>
			{{ Form::close() }} 
		</div>
	</div>
</section>
<script>

	function readURL(input){
		if (input.files && input.files[0]){
			var reader = new FileReader();
			reader.onload = function(e){
				$('.image_display').css("display", "block");
				$('#blah').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	$("#image").change(function() {
		readURL(this);
	});
</script>
@stop
