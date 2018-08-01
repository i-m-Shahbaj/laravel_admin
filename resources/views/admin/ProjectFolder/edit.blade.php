
@extends('admin.layouts.default')

@section('content')

<!-- CKeditor js and custom li js  strat here-->
{{ HTML::style('css/admin/custom_li_bootstrap.css') }}	
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor js and custom li js  end here--->
<section class="content-header">
	<h1>
		{{ trans("Edit Category") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href='{{route("$modelName.index")}}'>Blog Management</a></li>
		<li><a href="{{route('ProjectFolder.index')}}">Cateogories</a></li>
		<li class="active">{{ trans("Edit Category") }}</li>
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
			{{ Form::open(['role' => 'form','route' => [$modelName.'.update', $model->id],'class' => 'mws-form','files'=>'true']) }}
			{{ Form::hidden('id', $model->id) }}
<!--
					<div class="form-group <?php echo ($errors->first('type')) ? 'has-error' : ''; ?>">
						<div class="mws-form-row">
							{!! HTML::decode( Form::label('type',trans("Type").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								@if(empty($listfolders))
									{{ Form::select('type',array('0'=>'Main Folder')+$listfolders,$model->parent_id,['class' => 'form-control']) }}
								@else
									{{ Form::select('type',array(''=>'Please Select Main Folder')+$listfolders,$model->parent_id,['class' => 'form-control']) }}
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
								{{ Form::text("name",$model->name, ['class' => 'form-control','id' => 'name']) }}
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
								<div class="error-message help-inline" id="image_error">
									<?php echo $errors->first('image'); ?>
								</div>
									@if($model->image != '' && File::exists(PROJECT_FOLDER_IMAGE_ROOT_PATH.$model->image))
										<a href="javascript:void(null)" id="<?php echo $model->id ?>" class="delete_image"><i class="fa fa-times" aria-hidden="true"> </i></a>
										@endif
									<div class="image_display">
									@if($model->image != '' && File::exists(PROJECT_FOLDER_IMAGE_ROOT_PATH.$model->image))
											<?php
												$image				=	PROJECT_FOLDER_IMAGE_URL.$model->image;
											?>
											<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo $image; ?>">
												<img src="<?php echo WEBSITE_URL.'image.php?height=80px&cropratio=1&image='.$image; ?>">
											</a>
										@else
											<img id="blah" src="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>" width='100' height="100" />
									@endif
									</div>
							</div>
						</div>
					</div>
					<div class="preview"></div>			
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
								/* CKEDITOR fro description */
								CKEDITOR.replace( <?php echo 'description'; ?>,
								{
									height: 150,
									width: 507,
									filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
									filebrowserImageWindowWidth : '240',
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
					
					<a href='{{route("$modelName.edit",["$model->id"])}}' class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear')  }}</a>
					
					<a href='{{route("$modelName.index")}}' class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
				</div>
			</div>
			{{ Form::close() }} 
		</div>
		
	</div>
	
</section>
<script>

/* Delete images */
$(document).on('click','.delete_image', function(e){
	var id = this.id;  
	e.stopImmediatePropagation();
	url = $(this).attr('href');
	bootbox.confirm("Are you sure you want to delete this ?",
	function(result){
		if(result){
			$('#loader_img').show();
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: "{{ route('ProjectFolder.deleteFeaturedImage') }}",
				type: "POST",
				data: {id: id},
				success: function(response){
					error_array 	= 	JSON.stringify(response);
					data			=	JSON.parse(error_array);
					if(data['success'] == 1){
						$('#loader_img').hide();
						$(".image_display").remove();
						$('#'+id).remove();
					}else{
						show_message('Something went wrong.','error');
					}
				},
				error:function(){
					$('#loader_img').hide();
				}
			});
		}
	});
	e.preventDefault();
});
</script>
@stop
