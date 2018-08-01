<div class="col-md-6">	
	<div class="form-group <?php echo ($errors->first('full_name')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('full_name',trans("Studio / Trainer Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('full_name','',['class' => 'form-control']) }}
			<div class="error-message help-inline" id="full_name_error">
				<?php echo $errors->first('full_name'); ?>
			</div>
		</div>
	</div>
	
	<div class="form-group <?php echo ($errors->first('email')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('email', trans("Email Address").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
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
	
	<div class="form-group <?php echo ($errors->first('date')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('date',trans("Date of Birth").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('date','',['class' => 'form-control parent_date','readonly'=>'readonly']) }}
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
			{{ Form::text('username','',['class' => 'form-control','id'=>'username']) }}
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
	
	<div class="form-group <?php echo ($errors->first('address')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('address',trans("Street address").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::textarea('address','',['rows'=>'4','class' => 'form-control']) }}
			<div class="error-message help-inline" id="address_error">
				<?php echo $errors->first('address'); ?>
			</div>
		</div>
	</div>
	
	<div class="form-group <?php echo ($errors->first('phone_number')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('phone_number', trans("Cell Phone").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
			{{ Form::text('phone_number','',['class' => 'form-control']) }}
			<div class="error-message help-inline" id="phone_number_error">
				<?php echo $errors->first('phone_number'); ?>
			</div>
		</div>
	</div>
	
	<div class="form-group <?php echo ($errors->first('zip_code')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('zip_code',trans("Zip Code").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('zip_code','',['class' => 'form-control ']) }}
			<div class="error-message help-inline" id="zip_code_error">
				<?php echo $errors->first('zip_code'); ?>
			</div>
		</div>
	</div>
	
	<div class="form-group <?php echo ($errors->first('website_address')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('website_address',trans("Website Address").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('website_address','',['class' => 'form-control']) }}
			<div class="error-message help-inline" id="website_address_error">
				<?php echo $errors->first('website_address'); ?>
			</div>
		</div>
	</div>
	
	<div class="form-group <?php echo ($errors->first('how_many_dancers_train_monthly')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('how_many_dancers_train_monthly',trans("How Many Dancers do you train Monthly?").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('how_many_dancers_train_monthly','',['class' => 'form-control ']) }}
			<div class="error-message help-inline" id="how_many_dancers_train_monthly_error">
				<?php echo $errors->first('how_many_dancers_train_monthly'); ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript"> 
	$(document).ready(function(){
		$( ".parent_date,.add_more_parent_dancer" ).datepicker({
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
