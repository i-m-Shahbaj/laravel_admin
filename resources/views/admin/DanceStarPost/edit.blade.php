@extends('admin.layouts.default')

@section('content')
<style>
iframe.table.table-striped td {
    font-size: 14px;
}
iframe.table.table-striped th {
    font-size: 14px;
}
iframe.view{
	background-color:#3c3f44; color:white;"
}
.video_demo_item .fa.fa-play {
    ebackground: black none repeat scroll 0 0;
    border: 1px solid black;
    border-radius: 50%;
    color: #fff;
    font-size: 13px;
    left: 115px;
    line-height: 12px;
    padding: 10px;
    position: relative;
    top: -79px;
}
</style>
{{ HTML::style('css/admin/magnific-popup.css') }}
{{ HTML::script('js/admin/jquery.magnific-popup.min.js') }}
<!-- CKeditor start here-->
{{ HTML::script('js/admin/plugins/ckeditor/ckeditor.js') }}
<section class="content-header">
	<h1>
		{{ trans("Edit Post") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href='{{route("$modelName.index")}}'>{{ trans("DanceStar Post") }}</a></li>
		<li class="active">{{ trans("Edit Post") }}</li>
	</ol>
</section>
<section class="content"> 
<div class="row pad"> 
	<div class="col-md-12">
		<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
	</div> 
</div>
	{{ Form::open(['role' => 'form','route' => [$modelName.'.update', $model->id],'class' => 'mws-form','files'=>'true','id'=>'post_form']) }}
			
	<div class="row pad">
		<div class="col-md-12">	
			<div class="form-group">
				{!! HTML::decode( Form::label('message',trans("What's On Your Mind?").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::textarea("message",$model->message, ['class' => 'form-control textarea_resize','id' => 'message',"rows"=>3,"cols"=>3]) }}
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

			<div class="col-md-8 col-sm-8" id="getQuantity" >
			<div class="form-group">
				{!! HTML::decode( Form::label('video_file',trans("Attachments").':', ['class' => 'mws-form-label'])) !!}<br/>
				<button style="float:right; margin-top:-3%;" type="button" onclick="add_more_form_documents()" class="btn btn-info">Add More</button>
			</div>
			<div class="mws-panel-body plan_price"> 	
			<div class="row">
			<div class="col-md-12">
				<table class="table table-responsive table-bordered ">
					<tbody id="document_section">
						<?php //print_r($form_documents);die;?>
						@if(!$form_documents->isEmpty())
							@foreach($form_documents as $formdocument)
								<?php
									$document_count			=	$formdocument->id;
								?>
								<tr class="" id="document_contant_{{$document_count}}" rel="{{$document_count}}">
									
									<td width="30%;">
										{{ Form::hidden("formdocument[$document_count][post_document_id]",$document_count,["class"=>""]) }}
										{{ Form::file("formdocument[$document_count][documents]",['class'=>"document_upload validate_"."$document_count"])}}
										
										<div class="error-message help-inline"></div>
										<?php
											$file_ext			=	$formdocument->image;
											$file_ext			=	explode(".",$file_ext);
											$file_ext			=	end($file_ext);
											$doc_full_path		=	POST_IMAGE_URL.$formdocument->image;
										?>
										@if($file_ext == "pdf") 
											<a target="_blank" href="{{$doc_full_path}}">
												<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=50px&height=80px&image='.WEBSITE_IMG_URL."pdf.jpg" ?>">
											</a>
											<br />
										@elseif($file_ext == "doc" || $file_ext == "docx") 
											<br />
											<a target="_blank" href="{{$doc_full_path}}">
												<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=120px&height=80px&image='.WEBSITE_IMG_URL.'/'."doc.png" ?>">
											</a>
										@elseif($file_ext == "mp4" || $file_ext == "mp4v") 
											<br />
											<?php
													$video_path	=	str_ireplace('http://','',$doc_full_path);
													$video_path	=	str_ireplace('https://','',$doc_full_path);
													$video_path	=	'https://'.$doc_full_path;
													
													$watchurl 			=	str_replace("https://vimeo.com/","https://player.vimeo.com/video/",$doc_full_path);
													$embed_url			=	str_replace("https://vimeo.com/","https://player.vimeo.com/video/",$doc_full_path);
											?>
											<a class="video_demo_item"  href="<?php echo $watchurl; ?>">
												<i class="fa fa-play"></i>
												<iframe  width="200"  height="auto" src="{{$embed_url}}"></iframe>
											</a>
										@else 
											<br />
											<a class="fancybox-buttons"  data-fancybox-group="button" href="<?php echo POST_IMAGE_URL.$formdocument->image; ?>">
												<div class="usermgmt_image">
													<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=120px&height=80px&image='.POST_IMAGE_URL.'/'.$formdocument->image ?>">
												</div>
											</a>
										@endif
									</td>
									<td width="10%;">
										<a title="Delete" onclick="delete_docs({{$document_count}});" href="javascript:void(0);" class="btn btn-danger btn-small"><span class="ti-trash"></span></a>
										
									</td>
								</tr>
							@endforeach
						@else
							<?php
								$document_count			=	1;
							?>
							<tr class="" id="document_contant_{{$document_count}}" rel="{{$document_count}}">
								<td width="30%;">
									{{ Form::file("formdocument[$document_count][documents]",['class'=>"document_upload"])}}
									<div class="error-message help-inline"></div>
								</td>
								<td width="25%;">
									
								</td>
							</tr>
						@endif
						
					</tbody>
				</table> 
			</div>
			</div>
			
		</div>
	</div>
		
		<div class="col-md-6">	
			<div class="mws-button-row">
			<input type="button" value="{{ trans('messages.user_management.save') }}" onclick="update_post();" class="btn btn-danger">
				<a href='{{route("$modelName.edit",[$model->id])}}' class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear')  }}</a>
				<a href='{{route("$modelName.index")}}' class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
			</div>
		</div>
		
		</div>
	</div>
	{{ Form::close() }} 
</section>
<script>
	function update_post() {
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$('.form-group').parent().removeClass('has-error');
		$('.mws-form-item').removeClass('has-error');
		$("#ck_value").val(CKEDITOR.instances['message'].getData());
		var formData  = $('#post_form')[0];
		var $inputs 				= 	$('#post_form :input.valid');
		var $documents 				= 	$('#post_form :input.document_upload');
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
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG',"mp4"];
		var image_validation		=	'<?php echo __('Please upload a valid attachment. Valid extensions are jpg, jpeg, png, jpeg, mp4')?>';
		var error  =	0;	
		$inputs.each(function() {
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
			}else if($(this).val() ==''){
					error	=	1;
					$(this).next().addClass('error');
					$(this).next().html('This field is required.');
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
	function delete_docs(row_counter) {
		bootbox.confirm("Are you sure want to delete this ?",
		function(result){
			if(result){	
				$.ajax({
					headers: {
						 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
					url: '{{ route($modelName.".deletePostDocument") }}',
					type: 'POST',
					data: { id: row_counter},
					success: function(response) {
						
					}
				});
				$('#document_contant_'+row_counter).remove();
			}
		});
	}

	$(document).ready(function(){
		$('body').magnificPopup({
			delegate: '.video_demo_item',
			type: 'iframe',
			tLoading: 'Loading video #%curr%...',
			mainClass: 'mfp-img-mobile',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1] // Will preload 0 - before current, and 1 after the current image
			},
			srcAction: 'iframe_src',
		});	
	});
</script>
@stop
