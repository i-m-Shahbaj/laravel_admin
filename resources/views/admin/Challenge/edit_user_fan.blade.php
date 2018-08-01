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

<style>
.chosen-container-single .chosen-single{
	padding: 5px 5px 5px 8px;
    height: 35px;
}
</style>
