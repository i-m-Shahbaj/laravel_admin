@extends('admin.layouts.default')
@section('content')

{{ HTML::script('js/admin/jui/js/jquery-ui-1.9.2.min.js') }}
{{ HTML::script('js/admin/jui/js/timepicker/jquery-ui-timepicker.min.js') }}
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::script('js/admin/chosen/chosen.jquery.min.js') }}
{{ HTML::style('css/admin/chosen.min.css') }}
<?php $googleApiKey	= Config::get('Site.api');?>
<script src="{{ WEBSITE_JS_URL }}jquery.inputmask.bundle.js"></script>
<script src="{{ WEBSITE_JS_URL }}phone.js"></script>
<script type="text/javascript"> 
	$(document).ready(function(){
		$( ".dancer_date,.parent_date,.add_more_parent_dancer" ).datepicker({
			dateFormat 	: 'yy-mm-dd',
			changeMonth : true,
			changeYear 	: true,
			yearRange	: '-100y:c+nn',
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
		{{ trans("messages.user_management.add_user") }} 
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">{{ trans("User Management") }}</a></li>
		<li class="active">{{ trans("messages.user_management.add_user") }} </li>
	</ol>
</section>
<section class="content "> 
	<div class="row pad"> 
		<div class="col-md-12">
			<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
		</div> 
	</div>
	{{ Form::open(['role' => 'form','route' => $modelName.'.add','class' => 'mws-form','files'=>'true','id'=>'dancer_user_form']) }}
	<div class="row pad ">
		<div class="mws-form-item">
			<div class="col-md-6">
				<div class="form-group type_div form-group ">
					{!! HTML::decode( Form::label('user_type',trans("User Type").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
					<?php $userTypeList 	=	Config::get('user_type_list'); ?>
					{{ Form::select('user_type',$userTypeList,'',['class'=>'form-control chosen-select','id'=>'user_type']) }}
					<div class="error-message help-inline" id="user_type_error">
						<?php echo $errors->first('user_type'); ?>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group"></div>
			</div>
			<div class="clearfix"></div>	
			
			<div id="" class="row col-md-12">	
				<div class="user_info" id="user_info">	
					<div class="col-md-6">	
						<div class="form-group <?php echo ($errors->first('first_name')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('first_name',trans("messages.user_management.first_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
							<div class="mws-form-item">
								{{ Form::text('first_name','',['class' => 'form-control']) }}
								<div class="error-message help-inline" id="first_name_error">
									<?php echo $errors->first('first_name'); ?>
								</div>
							</div>
						</div>
						<div class="form-group <?php echo ($errors->first('last_name')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('last_name',trans("messages.user_management.last_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
							<div class="mws-form-item">
								{{ Form::text('last_name','',['class' => 'form-control']) }}
								<div class="error-message help-inline" id="last_name_error">
									<?php echo $errors->first('last_name'); ?>
								</div>
							</div>
						</div>
						<div class="form-group <?php echo ($errors->first('email')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('email', trans("messages.user_management.email").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::text('email','',['class' => 'form-control']) }}
								<div class="error-message help-inline" id="email_error">
									<?php echo $errors->first('email'); ?>
								</div>
							</div>
						</div>
						<div class="form-group <?php echo ($errors->first('country')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('country', trans("messages.user_management.country").'<span class="requireRed">  *</span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::select("country",$countryList,'', ['data-rel'=>0,'class'=>'form-control chosen-select countries_id countries_id_0','placeholder'=>'Select Country','id'=>'countries_id']) }}
								<div class="error-message help-inline" id="country_error">
									<?php echo $errors->first('country'); ?>
								</div>
							</div>
						</div>

						<div class="form-group <?php echo ($errors->first('state')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('state', trans("State").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
							<div class="state_div">
								{{ Form::select('state',array(null=>'State'),'',['data-rel'=>0,'class'=>'form-control state_id chosen-select state_id_0' ,'id'=>'state_id']) }}
								<div class="error-message help-inline" id="state_error">
									<?php echo $errors->first('state'); ?>
								</div>
							</div>
							</div>
						</div>
						<div class="form-group <?php echo ($errors->first('city')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('city', trans("City").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
							<div class="city_div">
								{{ Form::select('city',array(null=>'City'),'',['class'=>'form-control city_id chosen-select city_id_0','id'=>'city_id']) }}
								<div class="error-message help-inline" id="city_error">
									<?php echo $errors->first('city'); ?>
								</div>
							</div>
							</div>
						</div>
						
						<div class="form-group <?php echo ($errors->first('gender')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('gender', trans("Gender").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::radio('gender','male',true,array('id'=>'male_id' ,'class'=>'gender_id')) }}
								{{ Form::label('male_id',trans("messages.user_management.male")) }}
							
								{{ Form::radio('gender','female',false,array('id'=>'female_id','class'=>'gender_id')) }}
								{{ Form::label('female_id',trans("messages.user_management.female")) }}
								<div class="error-message help-inline" id="gender_error">
									<?php echo $errors->first('gender'); ?>
								</div>
							</div>
						</div>
						
						<div class="form-group <?php echo ($errors->first('date')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('date',trans("Date").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
							<div class="mws-form-item">
								{{ Form::text('date','',['class' => 'form-control dancer_date','readonly'=>'readonly']) }}
								<div class="error-message help-inline" id="date_error">
									<?php echo $errors->first('date'); ?>
								</div>
							</div>
						</div>
						
						<div class="form-group <?php echo ($errors->first('profile_image')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('profile_image', trans("messages.user_management.profile_image").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
							<span class='tooltipHelp' title="" data-html="true" data-toggle="tooltip"  data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;">
								<i class="fa fa-question-circle fa-2x"> </i>
							</span>
							<div class="mws-form-item">
								{{ Form::file('profile_image',['class'=>'']) }}
								<div class="error-message help-inline" id="profile_image_error">
									<?php echo $errors->first('profile_image'); ?>
								</div>
							</div>
						</div>
						
					</div>
					<div class="col-md-6">
						<div class="form-group <?php echo ($errors->first('username')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('first_name',trans("Username").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
							<div class="mws-form-item">
								{{ Form::text('username','',['class' => 'form-control']) }}
								<div class="error-message help-inline" id="username_error">
									<?php echo $errors->first('username'); ?>
								</div>
							</div>
						</div>
						<div class="form-group <?php echo ($errors->first('password')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('password', trans("Create Password").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::password('password',['class'=>'userPassword form-control']) }}
								<div class="error-message help-inline" id="password_error">
									<?php echo $errors->first('password'); ?>
								</div>
							</div>
						</div>
						<div class="form-group <?php echo ($errors->first('confirm_password')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('confirm_password', trans("Re-enter Password").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::password('confirm_password',['class'=>'form-control']) }}
								<div class="error-message help-inline" id="confirm_password_error">
									<?php echo $errors->first('confirm_password'); ?>
								</div>
							</div>
						</div>
						
						<div class="form-group <?php echo ($errors->first('attend_dance_team')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('attend_dance_team', trans("Do you dance on a school or a recreational league dance team?").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::radio('attend_dance_team',1,false,['class'=>'league_name']) }}<label>Yes</label>
								{{ Form::radio('attend_dance_team',0,false,['class'=>'league_name','checked'=>'checked']) }}<label>No</label>
								<div class="error-message help-inline" id="attend_dance_team_error">
									<?php echo $errors->first('confirm_password'); ?>
								</div>
							</div>
							<div class="col-md-10 attend_dance_data" style="display:none;">
								<div class="mws-form-item">
									<div class="form-group">
										{!! HTML::decode( Form::label('league_name',trans("School/league Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
										{{ Form::text('league_name','',['class'=>'form-control']) }}
										<div class="error-message help-inline" id="league_name_error">
											<?php echo $errors->first('league_name'); ?>
										</div>
									</div>
									<div class="form-group <?php echo ($errors->first('league_country')) ? 'has-error' : ''; ?>">
										{!! HTML::decode( Form::label('league_country', trans("messages.user_management.country").'<span class="requireRed">  *</span>', ['class' => 'mws-form-label'])) !!}
										<div class="mws-form-item">
											{{ Form::select("league_country",$countryList,'', ['data-rel'=>1,'class'=>'form-control chosen-select countries_id countries_id_1','placeholder'=>'Select Country']) }}
											<div class="error-message help-inline" id="league_country_error">
												<?php echo $errors->first('league_country'); ?>
											</div>
										</div>
									</div>

									<div class="form-group <?php echo ($errors->first('league_state')) ? 'has-error' : ''; ?>">
										{!! HTML::decode( Form::label('league_state', trans("State").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
										<div class="mws-form-item">
										<div class="league_state_div">
											{{ Form::select('league_state',array(null=>'State'),'',['data-rel'=>1,'class'=>'form-control state_id state_id_1 chosen-select']) }}
											<div class="error-message help-inline" id="league_state_error">
												<?php echo $errors->first('league_state'); ?>
											</div>
										</div>
										</div>
									</div>
									<div class="form-group <?php echo ($errors->first('league_city')) ? 'has-error' : ''; ?>">
										{!! HTML::decode( Form::label('league_city', trans("City").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
										<div class="mws-form-item">
										<div class="league_city_div">
											{{ Form::select('league_city',array(null=>'City'),'',['data-rel'=>1,'class'=>'form-control city_id chosen-select city_id_1']) }}
											<div class="error-message help-inline" id="league_city_error">
												<?php echo $errors->first('league_city'); ?>
											</div>
										</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group <?php echo ($errors->first('attend_dance_studio')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('attend_dance_studio', trans("Do you attend a dance studio?").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							
							<div class="mws-form-item">
								{{ Form::radio('attend_dance_studio',1,false,['class'=>'attend_dance_studio']) }}<label>Yes</label>
								{{ Form::radio('attend_dance_studio',0,false,['class'=>'attend_dance_studio','checked'=>'checked']) }}<label>No</label>
								<div class="error-message help-inline" id="attend_dance_studio_error">
									<?php echo $errors->first('confirm_password'); ?>
								</div>
							</div>
							<div class="mws-form-item col-md-10 studio_data" style="display:none;">
								<div class="form-group">
									{!! HTML::decode( Form::label('studio_name',trans("Studio Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
									{{ Form::text('studio_name','',['class'=>'form-control','id'=>'studio_name']) }}
									<div class="error-message help-inline" id="studio_name_error">
										<?php echo $errors->first('studio_name'); ?>
									</div>
								</div>
								<div class="form-group <?php echo ($errors->first('studio_country')) ? 'has-error' : ''; ?>">
									{!! HTML::decode( Form::label('studio_country', trans("messages.user_management.country").'<span class="requireRed">  *</span>', ['class' => 'mws-form-label'])) !!}
									<div class="mws-form-item">
										{{ Form::select("studio_country",$countryList,'', ['data-rel'=>2,'class'=>'form-control chosen-select countries_id countries_id_2','placeholder'=>'Select Country']) }}
										<div class="error-message help-inline" id="studio_country_error">
											<?php echo $errors->first('studio_country'); ?>
										</div>
									</div>
								</div>

								<div class="form-group <?php echo ($errors->first('studio_state')) ? 'has-error' : ''; ?>">
									{!! HTML::decode( Form::label('studio_state', trans("State").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
									<div class="mws-form-item">
									<div class="studio_state_div">
										{{ Form::select('studio_state',array(null=>'State'),'',['data-rel'=>2,'class'=>'form-control state_id state_id_2 chosen-select']) }}
										<div class="error-message help-inline" id="studio_state_error">
											<?php echo $errors->first('studio_state'); ?>
										</div>
									</div>
									</div>
								</div>
								<div class="form-group <?php echo ($errors->first('studio_city')) ? 'has-error' : ''; ?>">
									{!! HTML::decode( Form::label('studio_city', trans("City").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
									<div class="mws-form-item">
									<div class="studio_city_div">
										{{ Form::select('studio_city',array(null=>'City'),'',['class'=>'form-control city_id city_id_2 chosen-select']) }}
										<div class="error-message help-inline" id="studio_city_error">
											<?php echo $errors->first('studio_city'); ?>
										</div>
									</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		
	</div>
	<div class="mws-button-row">
		<div class="input" >
			<input type="button" value="{{ trans('messages.user_management.save') }}" onclick="user_save();" class="btn btn-danger">
			<a href="{{route($modelName.'.add')}}" class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans("Clear") }}</a>
			<a href="{{route($modelName.'.index')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans("Cancel") }}</a>
		</div>
	</div>
	{{ Form::close() }}
	
	<div id="loader_img"><center><img src="{{WEBSITE_IMG_URL}}loading.gif"></center></div>
</section>
<script type="text/javascript">
	function danc_add_more(id){
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		var get_last_id			=	$(".lastsizedetailsrow"+id).find('tr').last().attr('rel');
		var counter  	 		=  parseInt(get_last_id) + 1;
		$.ajax({
			headers: {
			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url:'{{ route($modelName.".addmoreDancer") }}',
			'type':'post',
			data:{'counter':counter,'id':id},
			success:function(response){
				$('#loader_img').hide();
				$('.lastsizedetailsrow'+id).find('tr').last().after(response);
				$(".chosen-select").chosen({width:'100%'});
			}
		});
	}
		
	function delete_danc_row(row_id,dataId){
		bootbox.confirm("Are you sure want to remove this ?",
		function(result){
			if(dataId!==''){
				$.ajax({
					headers: {
					 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					url:'{{ route($modelName.".removeDancer") }}',
					'type':'post',
					data:{'id':dataId},
					success:function(response){
						error_array 	= 	JSON.stringify(response);
						data			=	JSON.parse(error_array);
						if(data['success'] == 1) {
							$('.delete_add_more_danc'+row_id).remove();
						}
					}
				});
			}else{
				$('.delete_add_more_danc'+row_id).remove();
			}
		});
	}
	
	$(document).ready(function(){
		 
		$(".chosen-select").chosen({width: "100%"});
		$("#user_type").on('change',function(){ 
			$('#loader_img').show();
			var user_type	=	$("#user_type").val();
			if(user_type==''){
				$('#loader_img').hide();
			}
			if(user_type){
				$.ajax({
					headers: {
					 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					url: '{{ route($modelName.".getUserAddData") }}',
					type:'post',
					data:{'user_type':user_type},
					success:function(response){ 
						$(".user_info").html(response);
						$('#loader_img').hide();
					}
				});
			}
		});
		$("#user_type").trigger("change");
		if ($(".league_name").is(':checked') && $(".league_name").val() ==1) {
			 $(".attend_dance_data").show();
		}
		 if ($(".league_name").is(':checked') && $(".league_name").val() ==0) {
			 $(".attend_dance_data").hide();
		}
		$(".league_name").on('change',function(){
			 if ($(this).is(':checked') && $(this).val() ==1) {
				 $(".attend_dance_data").show();
			}else{
				$(".attend_dance_data").hide();
			}
		});
		 if ($(".attend_dance_studio").is(':checked') && $(".attend_dance_studio").val() ==1) {
			 $(".studio_data").show();
		}
		 if ($(".attend_dance_studio").is(':checked') && $(".attend_dance_studio").val() ==0) {
			 $(".studio_data").hide();
		}
		$(".attend_dance_studio").on('change',function(){
			 if ($(this).is(':checked') && $(this).val() ==1) {
				 $(".studio_data").show();
			}else{
				$(".studio_data").hide();
			}
		});
		
	});
	
	$(document).on('change','.countries_id',function(){ 
		var Id				=	$(this).val();  
		var rel				=	$(this).attr('data-rel');
		if(Id && rel){
			$('.state_id_'+rel).empty('').trigger("chosen:updated");
			$('.state_id_'+rel).append($("<option/>", {value: '',text: 'State'})).trigger("chosen:updated");
			$.ajax({
				headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: '{{ route($modelName.".getStateList") }}',
				type:'post',
				data:{'country_id':Id},
				success:function(response){  
					error_array 		= 	JSON.stringify(response);
					data				=	JSON.parse(error_array);
					var state_list		=	data['state_list'];
					if(state_list != ''){
						$.each(state_list, function(key,value){
							$(".state_id_"+rel).append($("<option/>", {
								value: key,
								text: value
							})); 
							$(".state_id_"+rel).trigger("chosen:updated");
						});
					}
				}
			});
		}
	});
		
	$(document).on('change','.state_id',function(){ 
		var Id1				=	$(this).val();  
		var rel1			=	$(this).attr('data-rel');
		if(Id1 && rel1){
			$('.city_id_'+rel1).empty('').trigger("chosen:updated");
			$('.city_id_'+rel1).append($("<option/>", {value: '',text: 'City'})).trigger("chosen:updated");
			$.ajax({
				headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: '{{ route($modelName.".getCityList") }}',
				type:'post',
				data:{'state_id':Id1},
				success:function(response){ 
					error_array 		= 	JSON.stringify(response);
					data				=	JSON.parse(error_array);
					var city_list		=	data['city_list'];
					if(city_list != ''){
						$.each(city_list, function(key,value){
							$(".city_id_"+rel1).append($("<option/>", {
								value: key,
								text: value
							})); 
							$(".city_id_"+rel1).trigger("chosen:updated");
						});
					}
				}
			});
		} 
	});	
		
	function user_save() {
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '{{ route($modelName.".add") }}',
			type:'post',
			data: $('#dancer_user_form').serialize(),
			success: function(r){
				error_array 	= 	JSON.stringify(r);
				data			=	JSON.parse(error_array);
				if(data['success'] == 1) {
					$('#dancer_user_form')[0].reset();
					window.location.href	 =	"{{ route($modelName.'.index') }}";
					show_message("User update successfully.",'success');
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
