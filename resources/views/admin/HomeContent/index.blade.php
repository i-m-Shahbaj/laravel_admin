@extends('admin.layouts.default')
@section('content')
{{ HTML::script('js/admin/plugins/ckeditor/ckeditor.js') }}

<div class="row pad" >
	<!--Total Slide-->
	<?php $ts	=	'5' ; ?>
	{{ Form::open(['role' => 'form','route' => "$modelName.save",'class' => 'mws-form', 'files' => true,'id'=>"home_content_form"]) }}
	<div class="col-lg-12 col-md-12 col-xs-12 ">
		<div class="dshbrd_hdngs_dv">
			<ul class="nav nav-tabs">
				<?php for($i=1;$i<=$ts;$i++){ ?>
				<li class="<?php if($i==1){ echo 'active'; } ?>" data-id="{{$i}}"><a class="btn btn-primary" href="#tab{{$i}}" data-toggle="tab">{{{ trans("Slide $i")}}}</a></li>
				<?php } ?>
				</br>
			</ul>
		</div>
		<div class="dshbrd_tb_cntng_dv">
			<div class="tab-content">
			<?php 
				$i	=	'1' ; 
			?>
				@if(!empty($homeContents))
				@foreach($homeContents as $key=>$details)
				<div id="tab{{ $i }}" class="tab-pane fade in {{ ($i ==  1 )?'active':'' }} ">
					 <div class="box box-warning ">
						<div class="row pad">
							<div class="col-md-12 col-sm-12" >	
								<div class="box box-info">
									<div id="info1"></div>
									<div class="box-header with-border">
										<h3 class="box-title">
											Slide {{$i}}</h3>
									</div>
									<div class="box-body" style="display: block;padding:15px;"> 
									 {{ Form::hidden("data[$i][type]",'slide_'.$i, ['class' => '','id' => 'slide_1']) }}
										<div class="form-group <?php echo ($errors->first('image')?'has-error':''); ?>">
											<div class="mws-form-row ">
												{!! HTML::decode( Form::label($i.'_image', trans("Image").'<span class="requireRed"> *  </span>', ['class' => 'mws-form-label'])) !!}
												<div class="mws-form-item">
													{{ Form::file("data[$i][image]",['class'=>'image valid validate_'.$i]) }}
													<div class="error-message help-inline" id="image_error_{{$i}}">
														<?php echo $errors->first('image'); ?>
													</div>
													@if($details->image != '' && File::exists(HOME_CONTENT_IMAGE_ROOT_PATH.$details->image))
													<div class="image_display">
														<?php
															$image				=	HOME_CONTENT_IMAGE_URL.$details->image;
														?>
														<?php /*<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo $question_image; ?>">
															<img src="<?php echo WEBSITE_URL.'image.php?height=80px&cropratio=1&image='.$question_image; ?>">
														</a>*/?>
														<img id="blah{{$i}}" src="<?php echo WEBSITE_URL.'image.php?height=80px&cropratio=1&image='.$image; ?>" width='100' height="100" />
													</div>
													@else
													<div class="image_display image_display{{$i}}" style="display:none;">
														<img id="blah{{$i}}" src="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>" width='100' height="100" />
													</div>
													@endif
												</div>
											</div>
										</div>
											<div class="form-group <?php if($i == 1) {echo ($errors->first('description')?'has-error':'');} ?>">
												<div class="mws-form-row ">
													@if($i == 1)
													{!! HTML::decode( Form::label($i.'_description', trans("Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
													@else
													{!! HTML::decode( Form::label($i.'_description', trans("Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
													@endif
													<div class="mws-form-item">
														{{ Form::textarea("data[$i][description]",isset($details->content)?$details->content:'', ['class' => 'small','id' => 'description'.$i]) }}
														{{ Form::hidden("data[$i][ck_value]",'', ['class' => 'ck_value validate_'.$i,'data-id'=>$i,'id' => 'ck_value_'.$i]) }}
														<span class="error-message help-inline" id="description_error_{{$i}}">
															<?php echo ($i ==  $key ) ? $errors->first('description') : ''; ?>
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
														CKEDITOR.config.autoParagraph = false;	
															
													</script>
												</div>
											</div>
									
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php $i++; ?>
				@endforeach	
				@endif
				<?php for($i;$i<=$ts;$i++){ ?>
				<div id="tab{{ $i }}" class="tab-pane fade in {{ ($i ==  1 )?'active':'' }} ">
					 <div class="box box-warning ">
						<div class="row pad">
							<div class="col-md-12 col-sm-12" >	
								<div class="box box-info">
									<div id="info1"></div>
									<div class="box-header with-border">
										<h3 class="box-title">
											Slide {{$i}}</h3>
									</div>
									<div class="box-body" style="display: block;padding:15px;"> 
									 {{ Form::hidden("data[$i][type]",'slide_'.$i, ['class' => '','id' => 'slide_'.$i]) }}
										<div class="form-group <?php echo ($errors->first('image')?'has-error':''); ?>">
											<div class="mws-form-row ">
												{!! HTML::decode( Form::label($i.'_image', trans("Image").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
												<div class="mws-form-item">
													{{ Form::file("data[$i][image]",['class'=>'image validate_'.$i]) }}
													<div class="error-message help-inline">
														<?php echo $errors->first('image'); ?>
													</div>
													<div class="image_display image_display{{$i}}" style="display:none;">
													
														<img id="blah{{$i}}" src="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>" width='100' height="100" />
													
													</div>
												</div>
											</div>
										</div>
											<div class="form-group <?php if($i == 1) {echo ($errors->first('description')?'has-error':'');} ?>">
												<div class="mws-form-row ">
													@if($i == 1)
													{!! HTML::decode( Form::label($i.'_description', trans("Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
													@else
													{!! HTML::decode( Form::label($i.'_description', trans("Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
													@endif
													<div class="mws-form-item">
														{{ Form::textarea("data[$i][description]",'', ['class' => 'small ','id' => 'description'.$i]) }}
														{{ Form::hidden("data[$i][ck_value]",'', ['class' => ' ck_value validate_'.$i,'data-id'=>$i,'id' => 'ck_value_'.$i]) }}
														<span class="error-message help-inline" id="description_error_{{$i}}">
															<?php echo ($i ==  $key ) ? $errors->first('description') : ''; ?>
														</span>
													</div>
													<script type="text/javascript">
														/* CKEDITOR fro description */
														CKEDITOR.replace( <?php echo 'description'.$i; ?>,
														{
															allowedContent: 'p b i; a[!href]',
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
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<div class="mws-panel-body no-padding tab-content"> 
					<br />
					<div class="mws-button-row">
						<input type="button" value="{{ trans('messages.global.save') }}" class="btn btn-danger" onclick="submit_form();">
						<a href="{{ route($modelName.'.add')}}" class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
						
						<a href="{{URL::to('admin/block-manager')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
									{{ Form::close() }} 
</div> 

<script>

	function submit_form(){
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.mws-form-item').parent().removeClass('has-error');
		var form_id = $(".nav-tabs").find(".active").attr('data-id');
			$("#ck_value_"+form_id).val(CKEDITOR.instances['description'+form_id].getData());
			for ( instance in CKEDITOR.instances ) {
				CKEDITOR.instances[instance].updateElement();
			}
		var $inputs 				= 	$('#home_content_form :input.validate_'+form_id);
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG'];
		var image_validation		=	'<?php echo __('Please upload a valid attachment. Valid extensions are jpg, jpeg, png, jpeg')?>';
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
				//~ if($(this).val() ==''){
					//~ error	=	1;
					//~ $(this).next().addClass('error');
					//~ $(this).next().html('This field is required.');
				//~ }
			}else if($(this).val() ==''){
				error	=	1;
				if($(this).attr('name')=='data['+form_id+']ck_value'){
					$("#description_error_"+form_id).addClass('error');
					$("#description_error_"+form_id).html('This field is required.');
				}else{
					$(this).next().addClass('error');
					$(this).next().html('This field is required.');
				}
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});

		if(error == 0){
			var formData = $('#home_content_form')[0];
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: '{{ route($modelName.".save") }}',
				type:'post',
				data: new FormData(formData),
				//dataType: 'json',
				contentType: false, // The content type used when sending data to the server.
				cache: false, // To unable request pages to be cached
				processData:false,
				success: function(r){
					error_array 	= 	JSON.stringify(r);
					data			=	JSON.parse(error_array);
					if(data['success'] == 1) {
							$('#home_content_form')[0].reset();
							window.location.href	 =	"{{ route($modelName.'.index') }}";
							show_message("Question update successfully.",'success');
					}
					else {
						$.each(data['errors'],function(index,html){
							if($(this).attr('name')=='description'){
								$("#description_error_"+form_id).addClass('error');
								$("#description_error_"+form_id).html('This field is required.');
							}else{
								$("#"+index).parent().parent().addClass('has-error');
								$("#"+index+"_error").addClass('error');
								$("#"+index+"_error").html(html);
							}
						});
					}
					$('#loader_img').hide();
				}
			});
		}
	}
		
	function readURL(input){
		if (input.files && input.files[0]){
			var reader = new FileReader();
			reader.onload = function(e){
				var form_id = $(".nav-tabs").find(".active").attr('data-id');
				$('#blah'+form_id).attr('src', e.target.result);
				$('#blah'+form_id).parents().find(".image_display"+form_id).css("display","block");
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	$(".image").change(function() {
		readURL(this);
	});
</script>
@stop
