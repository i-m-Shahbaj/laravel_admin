@extends('admin.layouts.default')
@section('content')

{{ HTML::script('js/admin/jui/js/jquery-ui-1.9.2.min.js') }}
{{ HTML::script('js/admin/jui/js/timepicker/jquery-ui-timepicker.min.js') }}
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::script('js/admin/chosen/chosen.jquery.min.js') }}
{{ HTML::style('css/admin/chosen.min.css') }}
{{HTML::script('js/admin/jquery.sumoselect.min.js') }}
{{HTML::style('css/admin/sumoselect.min.css') }}
<!-- CKeditor start here-->
{{ HTML::script('js/admin/plugins/ckeditor/ckeditor.js') }}

<?php $googleApiKey	= Config::get('Site.api');?>
<script type="text/javascript"> 
	$(document).ready(function(){
		$( "#start_date" ).datepicker({
			dateFormat : 'yy-mm-dd',
			changeMonth : true,
			changeYear : true,
			yearRange	: '-100y:c+nn',
			onSelect	: function( selectedDate ){ $("#end_date").datepicker("option","minDate",selectedDate); }
			});
		$( "#end_date" ).datepicker({
			dateFormat : 'yy-mm-dd',
			changeMonth : true,
			changeYear : true,
			yearRange	: '-100y:c+nn',
			onSelect	: function( selectedDate ){ $("#start_date").datepicker("option","maxDate",selectedDate); }
		});
	});
	jQuery(document).ready(function(){
		$(".question_category_id1").SumoSelect({
			search: true, 
			searchText: 'Enter Here',
			placeholder: 'Any',
		});
	});

</script>

<style>
.chosen-container-single .chosen-single{
	padding: 5px 5px 5px 8px;
    height: 35px;
}
</style>

<section class="content-header">
	<h1>
		{{ trans("Add Challenge") }} 
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">{{ trans("Challenges") }}</a></li>
		<li class="active">{{ trans("Add Challenge") }} </li>
	</ol>
</section>
<section class="content"> 
<div class="row pad"> 
	<div class="col-md-12">
		<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
	</div> 
</div>
	{{ Form::open(['role' => 'form','route' => $modelName.'.add','class' => 'mws-form','files'=>'true','id'=>'challenges_form']) }}
	<div class="row pad">
		<div class="mws-form-item">
			
			<div id="" class="row col-md-12">	
				<div class="col-md-6">	
					<div class="form-group ">
						{!! HTML::decode( Form::label('sponsor_name',trans("Sponsor Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
						<div class="mws-form-item">
							{{ Form::text('sponsor_name',Config::get("Site.defaultSponsor"),['class' => 'form-control']) }}
							<div class="error-message help-inline" id="sponsor_name_error">
								<?php echo $errors->first('sponsor_name'); ?>
							</div>
						</div>
					</div>
					<div class="form-group">
						{!! HTML::decode( Form::label('challenge_name',trans("Challenge Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
						<div class="mws-form-item">
							{{ Form::text('challenge_name','',['class' => 'form-control']) }}
							<div class="error-message help-inline" id="challenge_name_error">
								<?php echo $errors->first('challenge_name'); ?>
							</div>
						</div>
					</div>
					<div class="form-group ">
						{!! HTML::decode( Form::label('start_date',trans("Start Date").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
						<div class="mws-form-item">
							{{ Form::text('start_date','',['class' => 'form-control start_date','readonly'=>'readonly']) }}
							<div class="error-message help-inline" id="start_date_error">
								<?php echo $errors->first('start_date'); ?>
							</div>
						</div>
					</div>
					<div class="form-group">
						{!! HTML::decode( Form::label('end_date',trans("End Date").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
						<div class="mws-form-item">
							{{ Form::text('end_date','',['class' => 'form-control end_date','readonly'=>'readonly']) }}
							<div class="error-message help-inline" id="end_date_error">
								<?php echo $errors->first('end_date'); ?>
							</div>
						</div>
					</div>
					<div class="form-group">
						{!! HTML::decode( Form::label('no_of_questions', trans("Number of Questions").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
						<div class="state_div">
							{{ Form::select('no_of_questions',array(null=>'Please select no of questions')+Config::get("no_of_questions"),'',['class'=>'form-control state_id chosen-select']) }}
							<div class="error-message help-inline" id="no_of_questions_error">
								<?php echo $errors->first('no_of_questions'); ?>
							</div>
						</div>
						</div>
					</div>
					<div class="form-group">
						<div class="mws-form-row">
							{!!  HTML::decode( Form::label('term_condition', trans("Terms & Conditions").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::textarea('term_condition','',['class' => 'form-control question_id valid','id'=>'term_condition','rows'=>3]) }}
								<div class="error-message help-inline"  id="term_condition_error">
								</div>
							</div>
							<script type="text/javascript">
							/* For CKEDITOR */
								
								CKEDITOR.replace( <?php echo 'term_condition'; ?>,
								{
									height: 200,
									width: 480,
									filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
									filebrowserImageWindowWidth : '640',
									filebrowserImageWindowHeight : '480',
									enterMode : CKEDITOR.ENTER_BR
								});
									
							</script>
						</div>
					</div>
					<div class="form-group image_section">
						<div class="mws-form-row">
							{!!  HTML::decode( Form::label('image', trans("Upload Image").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::file('image','',['class' => 'form-control image valid','type'=>'file']) }}
								<div class="error-message help-inline" id="image_error">
									<?php echo $errors->first('image'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						{!! HTML::decode( Form::label('grade_level', trans("Grade").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
						<div class="state_div">
							{{ Form::select('grade_level',array(null=>'Please select grade')+Config::get("challenges_grade"),'',['class'=>'form-control state_id chosen-select','id'=>'grade']) }}
							<div class="error-message help-inline" id="grade_error">
								<?php echo $errors->first('grade_level'); ?>
							</div>
						</div>
						</div>
					</div>
					<div class="form-group">
						{!! HTML::decode( Form::label('assign_dancer', trans("Assign Dancers").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::radio('assign_dancer',1,true,['class'=>'assign_dancer_range']) }}<label>All Dancer</label>
							{{ Form::radio('assign_dancer',0,false,['class'=>'assign_dancer_range']) }}<label>Select Age Range</label>
							<div class="error-message help-inline" id="assign_dancer_error">
								<?php echo $errors->first('confirm_password'); ?>
							</div>
						</div>
						<div class="col-md-12 assign_dancer_data" style="display:none;">
							<div class="mws-form-item">
								<div class="col-md-6">
									<div class="form-group">
										{!! HTML::decode( Form::label('minimum_age',trans("Min").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
										{{ Form::text('minimum_age','',['class'=>'form-control']) }}
										<div class="error-message help-inline" id="minimum_age_error">
											<?php echo $errors->first('minimum_age'); ?>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										{!! HTML::decode( Form::label('maximum_age',trans("Max").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
										{{ Form::text('maximum_age','',['class'=>'form-control']) }}
										<div class="error-message help-inline" id="maximum_age_error">
											<?php echo $errors->first('maximum_age'); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						{!! HTML::decode( Form::label('how_many_winners',trans("How many winners").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
						<div class="mws-form-item">
							{{ Form::text('how_many_winners','',['class' => 'form-control']) }}
							<div class="error-message help-inline" id="how_many_winners_error">
								<?php echo $errors->first('how_many_winners'); ?>
							</div>
						</div>
					</div>
					<div class="form-group">
						{!! HTML::decode( Form::label('laederboards',trans("Leaderboards(Number of Performers)").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
						<div class="mws-form-item">
							{{ Form::text('laederboards',10,['class' => 'form-control']) }}
							<div class="error-message help-inline" id="laederboards_error">
								<?php echo $errors->first('laederboards'); ?>
							</div>
						</div>
					</div>
					<div class="form-group <?php echo ($errors->first('question_category_id')) ? 'has-error' : ''; ?>">
						<div class="mws-form-row">
							{!!  HTML::decode( Form::label('category', trans("Category").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::select('category[]',$challengesCategory,
									'null',
									['class' => 'form-control question_category_id1 ','multiple'=>"multiple"]) }}
								<div class="error-message help-inline" id="category_error">
									<?php echo $errors->first('question_category_id'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						{!! HTML::decode( Form::label('description',trans("Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::textarea("description",'', ['class' => 'form-control textarea_resize','id' => 'description',"rows"=>3,"cols"=>3]) }}
							{{ Form::hidden("ck_value",'', ['class' => 'valid','id' => 'ck_value']) }}
							<span class="error-message help-inline" id="description_error">
								<?php echo $errors->first('description'); ?>
							</span>
						</div>
						<script type="text/javascript">
						/* For CKEDITOR */
							
							CKEDITOR.replace( <?php echo 'description'; ?>,
							{
								height: 200,
								width: 480,
								filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
								filebrowserImageWindowWidth : '640',
								filebrowserImageWindowHeight : '480',
								enterMode : CKEDITOR.ENTER_BR
							});
								
						</script>
					</div>
					<div class="form-group">
						{!! HTML::decode( Form::label('instruction',trans("Instructions").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::textarea("instruction",'', ['class' => 'form-control textarea_resize','id' => 'instruction',"rows"=>3,"cols"=>3]) }}
							{{ Form::hidden("ck_value",'', ['class' => 'valid','id' => 'ck_value']) }}
							<span class="error-message help-inline" id="instruction_error">
								<?php echo $errors->first('instruction'); ?>
							</span>
						</div>
						<script type="text/javascript">
						/* For CKEDITOR */
							
							CKEDITOR.replace( <?php echo 'instruction'; ?>,
							{
								height: 200,
								width: 480,
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
		
	</div>
	<?php
		$prize_count		=	1;
	?>
	<div class="row">
		<div class="form_document">
			<div class="col-md-8">
				<div style="font-size:20px;font-weight:bold;">Add Prize</div>
			</div>
			<div class="mws-form-col-4-8 pull-right">
				<a href="javascript:void(0);" onclick="add_more_prizes();" class="btn btn-primary"><i class="fa fa-plus"></i></a>
			</div>
			
			<div class="col-md-12">
				<table class="table">
					<tbody id="prize_section">
						<tr class="" id="prize_contant_{{$prize_count}}" rel="{{$prize_count}}">
							<td width="25%;">
								{{ Form::text("prize[$prize_count][prize_name]","",['class'=>'form-control validate_'."$prize_count","placeholder"=>"Prize Name"])}}
								<div class="error-message help-inline"></div>
							</td>
							<td width="30%;">
								{{ Form::textarea(
									 "prize[$prize_count][prize_description]",'', ['class' => 'form-control validate_'."$prize_count","placeholder"=>"Prize Description","cols"=>"3","rows"=>"4"]
									) 
								}}
								<div class="error-message help-inline"></div>
							</td>
							<td width="4%;">
								{{ Form::file("prize[$prize_count][image]",['class'=>'document_upload validate_'."$prize_count"])}}
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
	<br />
	<br />
	<div class="mws-button-row">
		<div class="input" >
			<input type="button" value="{{ trans('messages.user_management.save') }}" onclick="save_challenges();" class="btn btn-danger">
			<a href="{{route($modelName.'.add')}}" class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans("Clear") }}</a>
			<a href="{{route($modelName.'.index')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans("Cancel") }}</a>
		</div>
	</div>
	{{ Form::close() }}
	
	<div id="loader_img"><center><img src="{{WEBSITE_IMG_URL}}loading.gif"></center></div>
</section>
<script type="text/javascript">
	$(document).ready(function(){
		function assign_dancer(){
			checked_value	=	$('input[class=assign_dancer_range]:radio:checked').val();
			if(checked_value == "1"){
				$(".assign_dancer_data").hide();
			}else {
				$(".assign_dancer_data").show();
			}
		}
		
		$(function(){
			assign_dancer();
			$(".assign_dancer_range").click(function(){
				assign_dancer();
			});
		});
	});
	
	
	function add_more_prizes() {
		total_count			=	$('#prize_section').find("tr").last().attr("rel");
		var $inputs 				= 	$('#prize_section :input.validate_'+total_count);
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
				url: '{{ route($modelName.".addMorePrize") }}',
				type: 'POST',
				data: { total_count: total_count},
				success: function(response) {
					$('#prize_section').append(response);
					$('#loader_img').hide();
				}
			});
		}
	}

	function delete_prize(row_counter) {
		bootbox.confirm("Are you sure want to delete this ?",
		function(result){
			if(result){	
				$('#prize_contant_'+row_counter).remove();
			}
		});
	}
	function save_challenges() {
		$('#loader_img').show();
		var formData  = $('#challenges_form')[0];
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$('.form-group').parent().removeClass('has-error');
		$('.mws-form-item').removeClass('has-error');
		for ( instance in CKEDITOR.instances ) {
		CKEDITOR.instances[instance].updateElement();
		}
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '{{ route($modelName.".add") }}',
			type:'post',
			data: new FormData(formData), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,
			success: function(r){
				error_array 	= 	JSON.stringify(r);
				data			=	JSON.parse(error_array);
				if(data['success'] == 1) {
					$('#challenges_form')[0].reset();
					window.location.href	 =	"{{ route($modelName.'.index') }}";
					show_message("Challenge added successfully.",'success');
				}
				else {
					$.each(data['errors'],function(index,html){
						if(index=='grade_level'){
							$("#grade").parent().parent().find('.form-group').addClass('has-error');
							$("#grade").parent().parent().addClass('has-error');
							$("#grade_error").addClass('error');
							$("#grade_error").html(html);
						}else if(index=='term_condition'){
							$("#term_condition").parent().parent().find('.form-group').addClass('has-error');
							$("#term_condition").parent().parent().addClass('has-error');
							$("#term_condition_error").addClass('error');
							$("#term_condition_error").html(html);
						}else if(index=='instruction'){
							$("#instruction").parent().parent().find('.form-group').addClass('has-error');
							$("#instruction").parent().parent().addClass('has-error');
							$("#instruction_error").addClass('error');
							$("#instruction_error").html(html);
						}else{
							$("#"+index).parent().parent().find('.form-group').addClass('has-error');
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
</script>
<style>
.chosen-container-single .chosen-single{
	padding: 5px 5px 5px 8px;
    height: 35px;
}
#loader_img {
    background-color: #000 !important;
    height: 100% !important;
    top: 0 !important;
    left: 0 !important;
    position: fixed !important;
    width: 100% !important;
    z-index: 99999 !important;
    opacity: 0.5 !important;
    display: none;
}
#loader_img img {
    width: 100px;
    height: 100px;
    position: absolute;
    top: 50%;
}
</style>
@stop
