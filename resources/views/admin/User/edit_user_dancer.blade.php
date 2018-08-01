<div class="col-md-6">	
	<div class="form-group <?php echo ($errors->first('first_name')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('first_name',trans("messages.user_management.first_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('first_name',isset($userDetails->first_name) ? $userDetails->first_name :'',['class' => 'form-control']) }}
			<div class="error-message help-inline" id="first_name_error">
				<?php echo $errors->first('first_name'); ?>
			</div>
		</div>
	</div>
	<div class="form-group <?php echo ($errors->first('last_name')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('last_name',trans("messages.user_management.last_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('last_name',isset($userDetails->last_name) ? $userDetails->last_name :'',['class' => 'form-control']) }}
			<div class="error-message help-inline" id="last_name_error">
				<?php echo $errors->first('last_name'); ?>
			</div>
		</div>
	</div>
	<div class="form-group <?php echo ($errors->first('email')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('email', trans("messages.user_management.email").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
			{{ Form::text('email',isset($userDetails->email) ? $userDetails->email :'',['class' => 'form-control']) }}
			<div class="error-message help-inline" id="email_error">
				<?php echo $errors->first('email'); ?>
			</div>
		</div>
	</div>
		<div class="form-group <?php echo ($errors->first('country')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('country', trans("messages.user_management.country").'<span class="requireRed">  *</span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
			{{ Form::select("country",$countryList,isset($userDetails->country) ? $userDetails->country :'', ['data-rel'=>0,'class'=>'form-control chosen-select countries_id countries_id_0','placeholder'=>'Select Country','id'=>'countries_id']) }}
			<div class="error-message help-inline" id="country_error">
				<?php echo $errors->first('country'); ?>
			</div>
		</div>
	</div>

	<div class="form-group <?php echo ($errors->first('state')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('state', trans("State").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
		<div class="state_div">
			{{ Form::select('state',$stateList,isset($userDetails->state) ? $userDetails->state :'',['data-rel'=>0,'class'=>'form-control state_id chosen-select state_id_0' ,'id'=>'state_id']) }}
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
			{{ Form::select('city',$cityList,isset($userDetails->city) ? $userDetails->city :'',['class'=>'form-control city_id chosen-select city_id_0','id'=>'city_id']) }}
			<div class="error-message help-inline" id="city_error">
				<?php echo $errors->first('city'); ?>
			</div>
		</div>
		</div>
	</div>
	
	<div class="form-group <?php echo ($errors->first('gender')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('gender', trans("Gender").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
			{{ Form::radio('gender','male',($userDetails->gender=='male') ?true:false,array('id'=>'male_id' ,'class'=>'gender_id')) }}
			{{ Form::label('male_id',trans("messages.user_management.male")) }}
		
			{{ Form::radio('gender','female',($userDetails->gender=='female') ?true:false,array('id'=>'female_id','class'=>'gender_id')) }}
			{{ Form::label('female_id',trans("messages.user_management.female")) }}
			<div class="error-message help-inline" id="gender_error">
				<?php echo $errors->first('gender'); ?>
			</div>
		</div>
	</div>
	
	<div class="form-group <?php echo ($errors->first('date')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('date',trans("Date of Birth").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('date',isset($userDetails->date) ? $userDetails->date :'',['class' => 'form-control dancer_date','readonly'=>'readonly']) }}
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
			{{ Form::file('profile_image') }}
			<br />
			<?php 
				$oldImage	=	Input::old('profile_image');
				$image		=	isset($oldImage) ? $oldImage : $userDetails->image;
			?>
			@if($image != '' && File::exists(USER_PROFILE_IMAGE_ROOT_PATH.$image))
				<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo USER_PROFILE_IMAGE_URL.$userDetails->image; ?>">
					<div class="usermgmt_image">
						<img  src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&image='.USER_PROFILE_IMAGE_URL.'/'.$userDetails->image ?>">
					</div>
				</a>
			@endif
			<div class="error-message help-inline" id="profile_image_error">
				<?php echo $errors->first('profile_image'); ?>
			</div>
		</div>
	</div>
	
</div>
<div class="col-md-6">
	<div class="form-group <?php echo ($errors->first('username')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('username',trans("Username").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('username',isset($userDetails->username) ? $userDetails->username :'',['class' => 'form-control']) }}
			<div class="error-message help-inline" id="username_error">
				<?php echo $errors->first('username'); ?>
			</div>
		</div>
	</div>
	<?php /*<div class="mws-form-row">
		<div class="mws-form-message info">{{ trans("messages.user_management.please_leave_blank_if_you_do_not_want_to_change_password") }}
		</div>
	</div>
	<br />
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
	*/ ?>
	<div class="form-group <?php echo ($errors->first('attend_dance_team')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('attend_dance_team', trans("Do you dance on a school or a recreational league dance team?").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		
		<div class="mws-form-item">
			{{ Form::radio('attend_dance_team',1,($userDetails->attend_dance_team==1) ? true :false,['class'=>'attend_dance_team attend_dance_team_yes']) }}<label>Yes</label>
			{{ Form::radio('attend_dance_team',0,($userDetails->attend_dance_team==0) ? true :false,['class'=>'attend_dance_team attend_dance_team_no']) }}<label>No</label>
			<div class="error-message help-inline" id="attend_dance_team_error">
				<?php echo $errors->first('attend_dance_team'); ?>
			</div>
		</div>
		<div class="col-md-10 attend_dance_data" style="display:none;">
			<div class="mws-form-item">
				<div class="form-group">
					{!! HTML::decode( Form::label('league_name',trans("School/league Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
					{{ Form::text('league_name',isset($userDetails->league_name) ? $userDetails->league_name :'',['class'=>'form-control']) }}
					<div class="error-message help-inline" id="league_name_error">
						<?php echo $errors->first('league_name'); ?>
					</div>
				</div>
				<div class="form-group <?php echo ($errors->first('league_country')) ? 'has-error' : ''; ?>">
					{!! HTML::decode( Form::label('league_country', trans("messages.user_management.country").'<span class="requireRed">  *</span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::select("league_country",$countryList,isset($userDetails->league_country) ? $userDetails->league_country :'', ['data-rel'=>1,'class'=>'form-control chosen-select countries_id countries_id_1','placeholder'=>'Select Country']) }}
						<div class="error-message help-inline" id="league_country_error">
							<?php echo $errors->first('league_country'); ?>
						</div>
					</div>
				</div>

				<div class="form-group <?php echo ($errors->first('league_state')) ? 'has-error' : ''; ?>">
					{!! HTML::decode( Form::label('league_state', trans("State").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
					<div class="league_state_div">
						{{ Form::select('league_state',$leagueStateList,isset($userDetails->league_state) ? $userDetails->league_state :'',['data-rel'=>1,'class'=>'form-control chosen-select state_id state_id_1 chosen-select']) }}
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
						{{ Form::select('league_city',$leagueCityList,isset($userDetails->league_city) ? $userDetails->league_city :'',['data-rel'=>1,'class'=>'form-control chosen-select city_id chosen-select city_id_1']) }}
						<div class="error-message help-inline" id="league_city_error">
							<?php echo $errors->first('league_city'); ?>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		{!! HTML::decode( Form::label('attend_dance_studio', trans("Do you attend a dance studio?").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
			{{ Form::radio('attend_dance_studio',1,($userDetails->attend_dance_studio==1) ? true:false,['class'=>'attend_dance_studio_yes attend_dance_studio']) }}<label>Yes</label>
			{{ Form::radio('attend_dance_studio',0,($userDetails->attend_dance_studio==0) ? true:false,['class'=>'attend_dance_studio_no attend_dance_studio']) }}<label>No</label>
			<div class="error-message help-inline" id="attend_dance_studio_error">
				<?php echo $errors->first('attend_dance_studio'); ?>
			</div>
		</div>
		<div class="mws-form-item col-md-10 studio_data" style="display:none;">
			<div class="form-group">
				{!! HTML::decode( Form::label('studio_name',trans("Studio Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
				{{ Form::text('studio_name',isset($userDetails->studio_name) ? $userDetails->studio_name :'',['class'=>'form-control','id'=>'studio_name']) }}
				<div class="error-message help-inline" id="studio_name_error">
					<?php echo $errors->first('studio_name'); ?>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('studio_country')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('studio_country', trans("messages.user_management.country").'<span class="requireRed">  *</span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::select("studio_country",$countryList,isset($userDetails->studio_country) ? $userDetails->studio_country :'', ['data-rel'=>2,'class'=>'form-control chosen-select countries_id countries_id_2','placeholder'=>'Select Country']) }}
					<div class="error-message help-inline" id="studio_country_error">
						<?php echo $errors->first('studio_country'); ?>
					</div>
				</div>
			</div>

			<div class="form-group <?php echo ($errors->first('studio_state')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('studio_state', trans("State").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
				<div class="studio_state_div">
					{{ Form::select('studio_state',$studioStateList,isset($userDetails->studio_state) ? $userDetails->studio_state :'',['data-rel'=>2,'class'=>'form-control chosen-select state_id state_id_2 chosen-select']) }}
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
					{{ Form::select('studio_city',$studioCityList,isset($userDetails->studio_city) ? $userDetails->studio_city :'',['class'=>'form-control chosen-select city_id city_id_2 chosen-select']) }}
					<div class="error-message help-inline" id="studio_city_error">
						<?php echo $errors->first('studio_city'); ?>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript"> 
	$(document).ready(function(){
		$( ".dancer_date" ).datepicker({
			dateFormat 	: 'yy-mm-dd',
			changeMonth : true,
			changeYear 	: true,
			yearRange	: '-100y:c+nn',
		}); 
		 $(".chosen-select").chosen({width: "100%"});
	});
</script>
<script>

 if ($(".attend_dance_team_yes").is(':checked') || $(".attend_dance_team").val() == 1) {
			 $(".attend_dance_data").show();
		}
 if ($(".attend_dance_team_no").is(':checked') || $(".attend_dance_team").val() == 0) {
	 $(".attend_dance_data").hide();
}
$(".attend_dance_team").on('change',function(){
	 if ($(this).is(':checked') && $(this).val() == 1) {
		 $(".attend_dance_data").show();
	}else{
		$(".attend_dance_data").hide();
	}
});
 if ($(".attend_dance_studio_yes").is(':checked') || $(".attend_dance_studio").val() == 1) {
	 $(".studio_data").show();
}
 if ($(".attend_dance_studio_no").is(':checked') || $(".attend_dance_studio").val() == 0) {
	 $(".studio_data").hide();
}
$(".attend_dance_studio").on('change',function(){
	 if ($(this).is(':checked') && $(this).val() == 1) {
		 $(".studio_data").show();
	}else{
		$(".studio_data").hide();
	}
});
</script>

<style>
.chosen-container-single .chosen-single{
	padding: 5px 5px 5px 8px;
    height: 35px;
}
</style>
