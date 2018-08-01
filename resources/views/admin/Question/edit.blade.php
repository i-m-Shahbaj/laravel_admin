@extends('admin.layouts.default')
@section('content')
{{ HTML::script('js/admin/plugins/ckeditor/ckeditor.js') }}
{{HTML::script('js/admin/jquery.sumoselect.min.js') }}
{{HTML::script('js/ImagePrewiew.js') }}
{{HTML::style('css/admin/sumoselect.min.css') }}
<script>
	jQuery(document).ready(function(){
		$(".question_category_id1").SumoSelect({
			search: true, 
			searchText: 'Enter Here',
			placeholder: 'Question Category',	
		});
	});

</script>
<style>
.chosen-container-single .chosen-single{
	height:34px !important;
	padding:3px 6px;
}
.preview > div {
  display: inline-block;
  text-align:center;
}
</style>

<section class="content-header">
	<h1>{{ trans("Edit Question") }}</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">Questions</a></li>
		<li class="active">{{ trans("Edit Question") }}</li>
	</ol>
</section>
<section class="content">
<div class="row pad"> 
	<div class="col-md-12">
		<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
	</div> 
</div>
	{{ Form::open(['role' => 'form','url' =>  '#','class' => 'mws-form','files'=>'true',"id"=>"question_form"]) }} 
	<div class="row pad">
		
		<div class="col-md-6">
			<div class="form-group <?php echo ($errors->first('question_category_id')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					{!!  HTML::decode( Form::label('question_category_id', trans("Category").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::select('question_category_id[]',$questionCategory,
							explode(",",$details->question_category_id),
							['class' => 'form-control question_category_id1 ','multiple'=>"multiple"]) }}
						<div class="error-message help-inline" id="question_category_id_error">
							<?php echo $errors->first('question_category_id'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<br/>
			<br/>
			<div class="form-group">
				{!! HTML::decode( Form::label('question_grade_level', trans("Grade Level").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::radio('question_grade_level',1,($details->question_grade_level == 1) ? true :false,['class'=>'question_grade_level']) }}<label>All Dancer</label>
					{{ Form::radio('question_grade_level',0,($details->question_grade_level == 0) ? true :false,['class'=>'question_grade_level']) }}<label>Select Age Range</label>
					<div class="error-message help-inline" id="assign_dancer_error">
						<?php echo $errors->first('confirm_password'); ?>
					</div>
				</div>
				<div class="col-md-12 question_grade_level_data" style="display:none;">
					<div class="mws-form-item">
						<div class="col-md-6">
							<div class="form-group">
								{!! HTML::decode( Form::label('minimum_age',trans("Min").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
								{{ Form::text('minimum_age',($details->minimum_age > 0)?$details->minimum_age:'',['class'=>'form-control']) }}
								<div class="error-message help-inline" id="minimum_age_error">
									<?php echo $errors->first('minimum_age'); ?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								{!! HTML::decode( Form::label('maximum_age',trans("Max").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
								{{ Form::text('maximum_age',($details->maximum_age > 0)?$details->maximum_age:'',['class'=>'form-control']) }}
								<div class="error-message help-inline" id="maximum_age_error">
									<?php echo $errors->first('maximum_age'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('question')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					{!! HTML::decode( Form::label('question', trans("Question").'<span class="requireRed"> * </span>:', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::textarea('question',$details->question,['class' => 'form-control','id'=>'question']) }}
						
						<div class="error-message help-inline"  id="question_error">
						</div>
					</div>
				</div>
			</div>
			<div class="form-group image_section">
				<div class="mws-form-row">
					{!! HTML::decode( Form::label('question_image', trans("Upload Image"), ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::file('question_image',['class' => 'form-control question_image ']) }}
						<div class="error-message help-inline" id="question_image_error">
							<?php echo $errors->first('question_image'); ?>
						</div>
						<div class="image_display">
						@if($details->question_image != '' && File::exists(QUESTION_IMAGE_ROOT_PATH.$details->question_image))
							<?php
								$question_image				=	QUESTION_IMAGE_URL.$details->question_image;
							?>
							<?php /*<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo $question_image; ?>">
								<img src="<?php echo WEBSITE_URL.'image.php?height=80px&cropratio=1&image='.$question_image; ?>">
							</a>*/?>
							<img id="blah" src="<?php echo WEBSITE_URL.'image.php?height=80px&cropratio=1&image='.$question_image; ?>" width='100' height="100" />
						@endif
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	
	<div class="row pad multiple_answer_div" >
		<div class="col-md-6">
			<?php
				$answer_count		=	1;
			?>
			<div class="form-group">
				{!! HTML::decode( Form::label('video_url', trans("Answer Options"), ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-col-4-8 pull-right"> <a href="javascript:void(0);" class="btn btn-success btn-small align" onclick="add_more_multiple_answers();" style="">{{ trans("Add Answer") }} </a></div>
				<table class="table">
					<tbody id="answer_section">
						@if(!empty($option_details ))
							@foreach($option_details as $key => $options)
							<?php //pr($options);die;?>
							<?php
								$answer_count			=	$options->id;
							?>
							<tr id="answer_contant_{{$answer_count}}" rel="{{ $answer_count }}">
								{{ Form::hidden("formanswer[$answer_count][question_option_id]",$answer_count,["class"=>"form-control"]) }}
								
								<td width="80%">{{ Form::text("formanswer[$answer_count][answer]",$options->question_option,['class' => 'form-control',"id"=>""] )}}</td>
								<td>{{ Form::radio("formanswer[is_answer]",$options->id,($options->is_answer == 1) ? true : '', ['class'=>'question_checked']) }}</td>
								<td><a title="Delete" onclick="delete_answer_row_database({{$answer_count}});" href="javascript:void(0);" class="btn btn-danger btn-small"><span class="ti-trash"></span></a></td>
							</tr>
							@endforeach
						@else
							<tr id="answer_contant_{{$answer_count}}" rel="{{ $answer_count }}">
	
							<td width="80%">{{ Form::text("formanswer[$answer_count][answer]",'',['class' => 'form-control ' ,"id"=>""] )}}
								<div class="error-message help-inline">
								</div>
							</td>
							
							<td>{{ Form::radio("formanswer[is_answer]",$answer_count,"",['class'=>'question_checked']) }}</td>
							<td width="20%;">
								
							</td>
						</tr>
						@endif
					</tbody>
				</table>
				
				<div class="error-message help-inline" id="formanswer_error">
					<?php echo $errors->first('formanswer'); ?>
				</div>
			</div>
			
		</div>
	</div>
	
	<div class="mws-button-row">
		<input type="button" onclick="submit_form();" value="{{ trans('messages.system_management.save') }}" class="btn btn-primary">
		<a href="{{route($modelName.'.add')}}" class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans("Clear") }}</a>
		<a href="{{route($modelName.'.index')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans("Cancel") }}</a>
	</div>
	{{ Form::close() }}
</section>
<style>
	.table>tbody>tr>td {
		border:none;
	}
</style>
<script>
	$(document).ready(function(){
		function assign_dancer(){
			checked_value	=	$('input[class=question_grade_level]:radio:checked').val();
			if(checked_value == "1"){
				$(".question_grade_level_data").hide();
			}else {
				$(".question_grade_level_data").show();
			}
		}
		
		$(function(){
			assign_dancer();
			$(".question_grade_level").click(function(){
				assign_dancer();
			});
		});
	});
	
	function submit_form(){
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.mws-form-item').parent().removeClass('has-error');
		var $inputs 				= 	$('#question_form :input.valid');
		var error  =	0;	
		var checked  =	1;	
		$inputs.each(function() {
			if($(this).val() ==''){
				error	=	1;
				if($(this).attr('name')=='question_category_id'){
					$("#question_category_id_error").addClass('error');
					$("#question_category_id_error").html('This field is required.');
				}else{
					$(this).next().addClass('error');
					$(this).next().html('This field is required.');
				}
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});
		$(".question_checked").each(function() {
			if(this.checked){
				checked	=	0;
			}else{
				$("#formanswer_error").html("please select answer.")
			
			}
		});

		if(error == 0 && checked == 0){
		var formData = $('#question_form')[0];
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: '{{ route($modelName.".update","$details->id") }}',
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
						checkedNum	=	$('input[class="question_checked"]:radio:checked').length;
						//~ if(checkedNum == 0){
							//~ //bootbox.alert("Please select correct answer first.");
							//~ $("#formanswer").parent().parent().addClass('has-error');
							//~ $("#formanswer_error").addClass('error');
							//~ $("#formanswer_error").html("Please select correct answer first.");
						//~ }else {
							$('#question_form')[0].reset();
							window.location.href	 =	"{{ route($modelName.'.index') }}";
							show_message("Question update successfully.",'success');
						//}
					}
					else {
						$.each(data['errors'],function(index,html){
							$("#"+index).parent().parent().addClass('has-error');
							$("#"+index+"_error").addClass('error');
							$("#"+index+"_error").html(html);
						});
					}
					$('#loader_img').hide();
				}
			});
		}
	}
	
	
	function add_more_multiple_answers() {
		total_count			=	$('#answer_section').find("tr").last().attr("rel");
		var $inputs 				= 	$('#answer_section :input.validate_'+total_count);
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG'];
		var image_validation		=	'<?php echo __('Please upload a valid image. Valid extensions are jpg, jpeg, png, jpeg')?>';
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
				url: '{{ route($modelName.".addMoreAnswer") }}',
				type: 'POST',
				data: { total_count: total_count},
				success: function(response) {
					$('#answer_section').append(response);
					$('#loader_img').hide();
				}
			});
		}
	}
	
	function delete_answer(row_counter) {
		bootbox.confirm("Are you sure want to delete this ?",
		function(result){
			if(result){	
				$('#answer_contant_'+row_counter).remove();
			}
		});
	}

	function delete_answer_row_database(row_counter) {
		bootbox.confirm("Are you sure want to delete this ?",
		function(result){
			if(result){	
				$('#answer_contant_'+row_counter).remove();
				$.ajax({
					headers: {
						 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
					url: '{{ route($modelName.".deleteFromQuestionOption") }}',
					type: 'POST',
					data: { question_option_id: row_counter},
					success: function(response) {
					}
				});
			}
		});
	}

	/* $(function () {
		$('input[type=file]').change(function () {
			$(".image_display").hide();
		});
		$("input[name=question_image]").previewimage({
			div: ".preview",
			imgwidth: 180,
			imgheight: 120
		});
		
	}); */
	
	
	function readURL(input){
		if (input.files && input.files[0]){
			var reader = new FileReader();
			reader.onload = function(e){
				$('#blah').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	$(".question_image").change(function() {
		readURL(this);
	});
	
	
</script>

@stop
