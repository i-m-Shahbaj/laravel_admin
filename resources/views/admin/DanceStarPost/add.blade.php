@extends('admin.layouts.default')

@section('content')

<!-- CKeditor start here-->
{{ HTML::script('js/admin/plugins/ckeditor/ckeditor.js') }}
<style>
	.table>tbody>tr>td{
		border-top:none;
	}
</style>
<section class="content-header">
	<h1>
		{{ trans("Add Post") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href='{{route("$modelName.index")}}'>{{ trans("DanceStar Post") }}</a></li>
		<li class="active">{{ trans("Add Post") }}</li>
	</ol>
</section>
<section class="content"> 
<div class="row pad"> 
	<div class="col-md-12">
		<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
	</div> 
</div>
	<div class="row pad">
		{{ Form::open(['role' => 'form','route' => ["$modelName.save"],'class' => 'mws-form','files'=>'true','id'=>'post_form']) }}
		
		<div class="col-md-12">	
			<div class="form-group">
				{!! HTML::decode( Form::label('message',trans("What's On Your Mind?").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::textarea("message",'', ['class' => 'form-control textarea_resize','id' => 'message',"rows"=>3,"cols"=>3]) }}
					{{ Form::hidden("ck_value",'', ['class' => 'valid','id' => 'ck_value']) }}
					<span class="error-message help-inline" id="message_error">
						<?php echo $errors->first('message'); ?>
					</span>
				</div>
				<script type="text/javascript">
				/* For CKEDITOR */
					
					CKEDITOR.replace( <?php echo 'message'; ?>,
					{
						height: 200,
						width: 1000,
						filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
						filebrowserImageWindowWidth : '640',
						filebrowserImageWindowHeight : '480',
						enterMode : CKEDITOR.ENTER_BR
					});
						
				</script>
			</div>
		</div>



		<?php
			$document_count		=	1;
		?>

			<div class="col-md-8 col-sm-8" id="getQuantity" >
				<?php //pr($productDetail->getProductQuantity); ?>
				<div class="form-group">
					{!! HTML::decode( Form::label('post_images',trans("Attachments").':', ['class' => 'mws-form-label'])) !!}<br/>
					<button style="float:right; margin-top:-3%;" type="button" onclick="add_more_form_documents()" class="btn btn-info">Add More</button>
				</div>
				<div class="mws-panel-body plan_price"> 	
					<div class="row">
						<div class="col-md-12">
							<table class="table table-responsive table-bordered ">
								<tbody id="document_section">
									<tr>
										<td>
											<label>Video/File</label>
										</td>
										<td>
											<label></label>
										</td>
									</tr>
									<tr class="" id="document_contant_{{$document_count}}" rel="{{$document_count}}">
						
										<td width="30;">
											{{ Form::file("formdocument[$document_count][documents]",['class'=>"document_upload validate_"."$document_count"])}}
											<div class="error-message help-inline"></div>
										</td>
										<td width="25%;">
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		
		
		<div class="col-md-6">	
			<div class="mws-button-row">
			<input type="button" value="{{ trans('messages.user_management.save') }}" onclick="add_post();" class="btn btn-danger">
				
				<a href='{{route("$modelName.add")}}' class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear')  }}</a>
				
				<a href='{{route("$modelName.index")}}' class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
			</div>
		</div>
		{{ Form::close() }} 
		</div>
	</div>
</section>
<script>
	function add_post() {
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$('.form-group').parent().removeClass('has-error');
		$('.mws-form-item').removeClass('has-error');
		$("#ck_value").val(CKEDITOR.instances['message'].getData());
		var formData  = $('#post_form')[0];
		var $inputs 				= 	$('#post_form :input.valid');
		var $documents 				= 	$('#post_form :input.document_link');
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG',"mp4"];
		var image_validation		=	'<?php echo __('Please upload a valid attachment. Valid extensions are jpg, jpeg, png, jpeg, mp4')?>';
		var error  =	0;	
		$inputs.each(function() {
			if($(this).val() ==''){
				error	=	1;
				if($(this).attr('name')=='ck_value'){
					$("#message_error").addClass('error');
					$("#message_error").html('This field is required.');
				}else{
					$(this).next().addClass('error');
					$(this).next().html('This field is required.');
				}
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});
		$documents.each(function() { 
			if($(this).attr('type') == 'file' ){
				var value 			=	 $(this).val();
				if(value != ''){
					var file 			=	 value.toLowerCase();
					var extension 		= 	 file.substring(file.lastIndexOf('.') + 1);
					if($.inArray(extension, allowedExtensions) == -1) {
						error	=	1;
						$(this).next().addClass('error');
						$(this).next().html(image_validation);
					}else{
						$(this).next().html('');
						$(this).next().removeClass('error');
					}
				}
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});
		if(error == 0){
			$("#post_form").submit();
		}
	}
	
	function add_more_form_documents() {
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$('.mws-form-item').removeClass('has-error');
		total_count			=	$('#document_section').find("tr").last().attr("rel");
		var $inputs 				= 	$('#document_section :input.validate_'+total_count);
		var $documents 				= 	$('#post_form :input.document_upload');
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG',"mp4"];
		var image_validation		=	'<?php echo __('Please upload a valid attachment. Valid extensions are jpg, jpeg, png, jpeg, mp4')?>';
		var error  =	0;	
		$inputs.each(function() {
			if($(this).attr('type') == 'file' ){
				var value 			=	 $(this).val();
				if(value==''){
					error	=	1;
					$(this).next().addClass('error');
					$(this).next().html('This field is required.');
				}
				if(value != ''){
					var file 			=	 value.toLowerCase();
					var extension 		= 	 file.substring(file.lastIndexOf('.') + 1);
					if($.inArray(extension, allowedExtensions) == -1) {
						error	=	1;
						$(this).next().addClass('error');
						$(this).next().html(image_validation);
					}else{
						$(this).next().html('');
						$(this).next().removeClass('error');
					}
				}
			}else if($(this).val() ==''){
					error	=	1;
					$(this).next().addClass('error');
					$(this).next().html('This field is required.');
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});
		$documents.each(function() { 
			if($(this).attr('type') == 'file' ){
				var value 			=	 $(this).val();
				if(value != ''){
					var file 			=	 value.toLowerCase();
					var extension 		= 	 file.substring(file.lastIndexOf('.') + 1);
					if($.inArray(extension, allowedExtensions) == -1) {
						error	=	1;
						$(this).next().addClass('error');
						$(this).next().html(image_validation);
					}else{
						$(this).next().html('');
						$(this).next().removeClass('error');
					}
				}
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});
		if(error == 0){
			if(typeof total_count !== "undefined") {
				total_count		=	parseInt(total_count)+1;
			}else {
				total_count		=	1;
			}
			$('#loader_img').show();
			
			$.ajax({
				headers: {
					 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
				url: '{{ route($modelName.".addMoreDocument") }}',
				type: 'POST',
				data: { total_count: total_count},
				success: function(response) {
					$('#document_section').append(response);
					$('#loader_img').hide();
				}
			});
		}
	}
	
	function delete_document(row_counter) {
		bootbox.confirm("Are you sure want to delete this ?",
		function(result){
			if(result){	
				$('#document_contant_'+row_counter).remove();
			}
		});
	}
	
</script>
@stop
