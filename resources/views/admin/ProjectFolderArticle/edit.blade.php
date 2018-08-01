@extends('admin.layouts.default')

@section('content')
<style>
.table.table-striped td {
    font-size: 14px;
}
.table.table-striped th {
    font-size: 14px;
}
.view{
	background-color:#3c3f44; color:white;"
}
.video_demo_item .fa.fa-play {
    background: black repeat scroll 0 0;
    border: 1px solid black;
    border-radius: 50%;
    color: #fff;
    font-size: 13px;
    left: 96px;
    line-height: 5px;
    padding: 13px;
    position: relative;
    top: -47px;
}
</style>
{{ HTML::style('css/admin/magnific-popup.css') }}
{{ HTML::script('js/admin/jquery.magnific-popup.min.js') }}
<!-- CKeditor start here-->
{{ HTML::script('js/admin/plugins/ckeditor/ckeditor.js') }}
{{ HTML::script('js/admin/jquery-ui-1.9.2.min.js') }}
{{ HTML::script('js/admin/jquery-ui-timepicker.min.js') }}
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
<script>
jQuery(document).ready(function(){
	 $( "#comment_end_date" ).datepicker({
		dateFormat 	: 'yy-mm-dd',
		changeMonth : true,
		changeYear 	: true,
		 minDate: 0,
		//yearRange	: '1950:2013',
	});
});
</script>
<section class="content-header">
	<h1>
		{{ trans("Edit Article") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route('ProjectFolder.index')}}">Blog Management</a></li>
		<li><a href='{{route("$modelName.conetentIndex",array("$project_folder_id"))}}'>{{ trans("Content") }}</a></li>
		<li class="active">{{ trans("Edit Article") }}</li>
	</ol>
</section>
<section class="content"> 
<div class="row pad"> 
		<div class="col-md-12">
			<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
		</div> 
	</div>
	{{ Form::open(['role' => 'form','route' => [$modelName.'.update',$project_folder_id, $model->id],'class' => 'mws-form','files'=>'true','id'=>'article_form']) }}
			
	<div class="row pad">
		<div class="col-md-6">	
			{{ Form::hidden('id', $model->id) }}
			<div class="form-group <?php  echo ($errors->first('article_name')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					{!! HTML::decode( Form::label('article_name',trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text("article_name",$model->article_name, ['class' => 'form-control valid','id' => 'name']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('article_name'); ?>
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
						<div class="image_display">
							@if($model->image != '' && File::exists(PROJECT_ARTICLE_IMAGE_ROOT_PATH.$model->image))
								<?php
									$image				=	PROJECT_ARTICLE_IMAGE_URL.$model->image;
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
		</div>
		<div class="col-md-12">	
			<div class="form-group">
				{!! HTML::decode( Form::label('article_description',trans("Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::textarea("article_description",$model->article_description, ['class' => 'form-control textarea_resize','id' => 'description',"rows"=>3,"cols"=>3]) }}
					{{ Form::hidden("ck_value",'', ['class' => 'valid','id' => 'ck_value']) }}
					<span class="error-message help-inline" id="article_description_error">
						<?php echo $errors->first('article_description'); ?>
					</span>
				</div>
				<script type="text/javascript">
				/* For CKEDITOR */
					
					CKEDITOR.replace( <?php echo 'description'; ?>,
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
			<div class="form-group <?php  echo ($errors->first('is_check_this_out')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					<div class="mws-form-item">
						{{ Form::checkbox("is_check_this_out",'1',$model->is_check_this_out, ['class' => '','id' => 'is_check_this_out']) }} {!! HTML::decode( Form::label('is_check_this_out',trans("Add to Check this out"), ['class' => 'mws-form-label '])) !!}
						<div class="error-message help-inline">
							<?php echo $errors->first('is_check_this_out'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group <?php  echo ($errors->first('allow_comments')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					<div class="mws-form-item">
						{{ Form::checkbox("allow_comments",'1',$model->allow_comments, ['class' => 'valid','id' => 'allow_comments']) }} 
						{!! HTML::decode( Form::label('allow_comments',trans("Allow comments"), ['class' => 'mws-form-label '])) !!}
						<div class="error-message help-inline">
							<?php echo $errors->first('allow_comments'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group comment_end_date <?php  echo ($errors->first('comment_end_date')) ? 'has-error' : ''; ?>" style="display:none;">
				<div class="mws-form-row  col-md-6">
					{!! HTML::decode( Form::label('comment_end_date',trans("Comment End Date"), ['class' => 'mws-form-label '])) !!}
					<div class="mws-form-item">
						{{ Form::text("comment_end_date",$model->comment_end_date,['class' => 'form-control','id' => 'comment_end_date','readonly'=>'readonly']) }} 
						<div class="error-message help-inline">
							<?php echo $errors->first('comment_end_date'); ?>
						</div>
					</div>
				</div>
				<br/>
				<br/>
				<br/>
			</div>
		</div>

		<div class="col-md-12">	
			<div class="col-md-8 col-sm-8" id="getQuantity" >
				<div class="form-group">
					{!! HTML::decode( Form::label('video_file',trans("Add Web Links").':', ['class' => 'mws-form-label','files'=>'true'])) !!}<br/>
					<button style="float:right; margin-top:-3%;" type="button" onclick="add_more_form_links()" class="btn btn-info"><i class="fa fa-plus"></i></button>
				</div>
				<div class="mws-panel-body plan_price"> 	
				<div class="row">
				<div class="col-md-12">
					<table class="table table-responsive table-bordered ">
						<tbody id="document_link_section">
							@if(!$form_links->isEmpty())
								@foreach($form_links as $formlink)
									<?php
										$document_link			=	$formlink->id;
									?>
									<tr class="" id="document_link_contant_{{$document_link}}" rel="{{$document_link}}">
										
										<td width="90%;">
											{{ Form::hidden("formlink[$document_link][article_link_id]",$document_link,["class"=>"form-control "]) }}
											{{ Form::text("formlink[$document_link][url]",$formlink->url,['class'=>"form-control document_link validate_"."$document_link"])}}
											<div class="error-message help-inline"></div>
										</td>
										<td width="10%;">
											<a title="Delete" onclick="delete_document_link({{$document_link}});" href="javascript:void(0);" class="btn btn-danger btn-small"><span class="ti-trash"></span></a>
											
										</td>
									</tr>
								@endforeach
							@else
								<?php
									$document_link			=	1;
								?>
								<tr class="" id="document_link_contant_{{$document_link}}" rel="{{$document_link}}">
									<td width="90%;">
										{{ Form::text("formlink[$document_link][url]","",['class'=>"form-control document_link validate_"."$document_link","placeholder"=>"Enter Url"])}}
										<div class="error-message help-inline"></div>
									</td>
									<td width="10%;">
										
									</td>
								</tr>
							@endif
							
						</tbody>
					</table> 
				</div>
				</div>
				</div>
				
			</div>
		</div>
		<div class="col-md-12">	
			<div class="col-md-8 col-sm-8" id="getQuantity" >
			<div class="form-group">
				{!! HTML::decode( Form::label('video_file',trans("Upload Attachments (Video/File)").':', ['class' => 'mws-form-label'])) !!}<br/>
				<button style="float:right; margin-top:-3%;" type="button" onclick="add_more_form_documents()" class="btn btn-info"><i class="fa fa-plus"></i></button>
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
										{{ Form::hidden("formdocument[$document_count][article_document_id]",$document_count,["class"=>""]) }}
										{{ Form::file("formdocument[$document_count][documents]",['class'=>"document_upload validate_"."$document_count"])}}
										
										<div class="error-message help-inline"></div>
										<?php
											$file_ext			=	$formdocument->documents;
											$file_ext			=	explode(".",$file_ext);
											$file_ext			=	end($file_ext);
											$doc_full_path		=	PROJECT_ARTICLE_IMAGE_URL.$formdocument->documents;
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
										@elseif($file_ext == "zip") 
											<br />
											<a href="{{$doc_full_path}}">
												<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=120px&height=80px&image='.WEBSITE_IMG_URL.'/'."zip.png" ?>">
											</a>
										@elseif($file_ext == "csv") 
											<br />
											<a href="{{$doc_full_path}}">
												<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=120px&height=80px&image='.WEBSITE_IMG_URL.'/'."csv.png" ?>">
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
												<iframe  width="150px"  height="100px" src="{{$embed_url}}"></iframe>
											</a>
										@else 
											<br />
											<a class="fancybox-buttons"  data-fancybox-group="button" href="<?php echo PROJECT_ARTICLE_IMAGE_URL.$formdocument->documents; ?>">
												<div class="usermgmt_image">
													<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=150px&height=100px&image='.PROJECT_ARTICLE_IMAGE_URL.'/'.$formdocument->documents ?>">
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
	</div>
		
		<div class="col-md-6">	
			<div class="mws-button-row">
			<input type="button" value="{{ trans('messages.user_management.save') }}" onclick="update_articles();" class="btn btn-danger">
				<a href='{{route("$modelName.edit",[$project_folder_id,$model->id])}}' class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear')  }}</a>
				<a href='{{route("$modelName.conetentIndex",array("$project_folder_id"))}}' class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
			</div>
		</div>
		
		</div>
	</div>
	{{ Form::close() }} 
</section>
<script>
	function update_articles() {
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$('.form-group').parent().removeClass('has-error');
		$('.mws-form-item').removeClass('has-error');
		$("#ck_value").val(CKEDITOR.instances['description'].getData());
		var formData  = $('#article_form')[0];
		var $inputs 				= 	$('#article_form :input.valid');
		var $documents 				= 	$('#article_form :input.document_upload');
		var $documentLinks 				= 	$('#article_form :input.document_link');
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG','mp4','MP4','mp4v','MP4V'];
		var image_validation		=	'<?php echo __('Please upload a valid image. Valid extensions are jpg, jpeg, png, jpeg, mp4, mp4v')?>';
		var error  =	0;	
		$inputs.each(function() {
			if($(this).attr('name') == 'allow_comments'){
				if($(this).prop("checked")){
					if($("#comment_end_date").val() == ""){
						error	=	1;
						$("#comment_end_date").next().addClass("error");
						$("#comment_end_date").next().html('Please select comment end date.');
					}
				}else{
					$("#comment_end_date").next().html('');
					$("#comment_end_date").next().removeClass('error');
				}
			}if($(this).val() ==''){
					error	=	1;
					if($(this).attr('name')=='article_description'){
						$("#article_description_error").addClass('error');
						$("#article_description_error").html('This field is required.');
					}else if($(this).attr('name')=='ck_value'){
						$("#article_description_error").addClass('error');
						$("#article_description_error").html('This field is required.');
					}else{
						$(this).next().addClass('error');
						$(this).next().html('This field is required.');
					}
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});
		$documentLinks.each(function() { 
			if($(this).val() !=''){
				url	=	$(this).val();
				url_validate = /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
				if(!url_validate.test(url)){
					error	=	1;
					$(this).next().addClass('error');
					$(this).next().html('Please enter valid url.');
				}
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});
		if(error == 0){
			$("#article_form").submit();
		}
	}
	
	function add_more_form_documents() {
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$('.mws-form-item').removeClass('has-error');
		total_count			=	$('#document_section').find("tr").last().attr("rel");
		var $inputs 				= 	$('#document_section :input.validate_'+total_count);
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG','mp4','MP4','mp4v','MP4V'];
		var image_validation		=	'<?php echo __('Please upload a valid image. Valid extensions are jpg, jpeg, png, jpeg, mp4, mp4v')?>';
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

	function add_more_form_links() {
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$('.mws-form-item').removeClass('has-error');
		total_count			=	$('#document_link_section').find("tr").last().attr("rel");
		var $inputs 				= 	$('#document_link_section :input.validate_'+total_count);
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG'];
		var image_validation		=	'<?php echo __('Please upload a valid image. Valid extensions are jpg, jpeg, png, jpeg')?>';
		var error  =	0;	
		$inputs.each(function() {
			var url = $(this).val();
			if(url ==''){
				error	=	1;
				$(this).next().addClass('error');
				$(this).next().html('This field is required.');
			}else if(url != ''){
				url_validate = /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
				if(!url_validate.test(url)){
					error	=	1;
					$(this).next().addClass('error');
					$(this).next().html('Please enter valid url.');
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
				url: '{{ route($modelName.".addMoreDocumentLink") }}',
				type: 'POST',
				data: { total_count: total_count},
				success: function(response) {
					$('#document_link_section').append(response);
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
	function delete_document_link(row_counter) {
		bootbox.confirm("Are you sure want to delete this ?",
		function(result){
			if(result){	
				$('#document_link_contant_'+row_counter).remove();
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
					url: '{{ route($modelName.".deleteProjectDocument") }}',
					type: 'POST',
					data: { id: row_counter},
					success: function(response) {
						
					}
				});
				$('#document_contant_'+row_counter).remove();
			}
		});
	}

	
</script>

<script>
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
		
		if($("#allow_comments").prop("checked") == true){
			$(".comment_end_date").show();
		}else{
			$(".comment_end_date").hide();
		}
	});
	$("#allow_comments").click(function(){
		if($(this).prop("checked") == true){
			$(".comment_end_date").show();
		}else{
			$(".comment_end_date").hide();
		}
	});
</script>
@stop
