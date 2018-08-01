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
	
	<div class="form-group <?php echo ($errors->first('country')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('country', trans("messages.user_management.country").'<span class="requireRed">  *</span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
			{{ Form::select("country",$countryList,'', ['data-rel'=>3,'class'=>'form-control chosen-select countries_id countries_id_3','placeholder'=>'Select Country']) }}
			<div class="error-message help-inline" id="country_error">
				<?php echo $errors->first('country'); ?>
			</div>
		</div>
	</div>

	<div class="form-group <?php echo ($errors->first('state')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('state', trans("State").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
		<div class="parent_state_div">
			{{ Form::select('state',array(null=>'State'),'',['data-rel'=>3,'class'=>'form-control state_id state_id_3 chosen-select']) }}
			<div class="error-message help-inline" id="state_error">
				<?php echo $errors->first('state'); ?>
			</div>
		</div>
		</div>
	</div>
	<div class="form-group <?php echo ($errors->first('city')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('city', trans("City").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
		<div class="parent_city_div">
			{{ Form::select('city',array(null=>'City'),'',['class'=>'form-control chosen-select city_id city_id_3']) }}
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
	<div class="form-group <?php echo ($errors->first('email')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('email', trans("messages.user_management.email").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
			{{ Form::text('email','',['class' => 'form-control']) }}
			<div class="error-message help-inline" id="email_error">
				<?php echo $errors->first('email'); ?>
			</div>
		</div>
	</div>
	<div class="form-group <?php echo ($errors->first('username')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('first_name',trans("Username").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('username','',['class' => 'form-control','id'=>'username']) }}
			<div class="error-message help-inline" id="username_error">
				<?php echo $errors->first('username'); ?>
			</div>
		</div>
	</div>
	<div class="form-group <?php echo ($errors->first('relationship')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('relationship', trans("Relationship").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
			{{ Form::text('relationship','',['class' => 'form-control']) }}
			<div class="error-message help-inline" id="relationship_error">
				<?php echo $errors->first('relationship'); ?>
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
	
	<div class="form-group <?php echo ($errors->first('date')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('date',trans("Date of Birth").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('date','',['class' => 'form-control parent_date','readonly'=>'readonly']) }}
			<div class="error-message help-inline" id="date_error">
				<?php echo $errors->first('date'); ?>
			</div>
		</div>
	</div>
	
</div>
<hr>
<div class="col-md-12">
	<table class="table table-responsive table-bordered lastsizedetailsrow0">
		
		<thead>
			<tr>
				<td>
					<h4>Please tell us about each of your Dancers
					<br><span>(Press "+" to add additional children)</span></h4> 
					
				</td>
				<td colspan="2" class="text-right">
					<span class="align text-right"><button title="Add More" class="btn btn-info" type="button"  onclick="danc_add_more(0);" ><i class="fa fa-plus"></i></button></span>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr class="panel panel-default delete_add_more_danc0" rel="0">
				<td colspan="2">
					<div class="col-md-3">
						<div class="form-group <?php echo ($errors->first('dancer[0][first_name]')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('dancer[0][first_name]',trans("messages.user_management.first_name").'<span class="requireRed">  </span>', ['class' => 'mws-form-label']))  !!}
							<div class="mws-form-item">
								{{ Form::text('dancer[0][first_name]','',['class' => 'form-control']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('dancer[0][first_name]'); ?>
								</div>
							</div>
						</div>
						
						<div class="form-group <?php echo ($errors->first('dancer[0][country]')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('dancer[0][country]', trans("messages.user_management.country").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::select("dancer[0][country]",$countryList,'', ['data-rel'=>4,'class'=>'form-control chosen-select countries_id countries_id_4','placeholder'=>'Select Country']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('dancer[0][country]'); ?>
								</div>
							</div>
						</div>
						<div class="form-group <?php echo ($errors->first('dancer[0][send_notification]')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('dancer[0][send_notification]', trans("Send notification to dancer (Y/N).").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::radio('dancer[0][send_notification]','Yes',true,array('id'=>'male_id' ,'class'=>'send_notification_id')) }}
								{{ Form::label('yes_id',trans("Yes")) }}
							
								{{ Form::radio('dancer[0][send_notification]','No',false,array('id'=>'female_id','class'=>'send_notification_id')) }}
								{{ Form::label('no_id',trans("No")) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('dancer[0][send_notification]'); ?>
								</div>
							</div>
						</div>
					</div>
						
					<div class="col-md-3">
						<div class="form-group <?php echo ($errors->first('dancer[0][last_name]')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('dancer[0][last_name]',trans("messages.user_management.last_name").'<span class="requireRed">  </span>', ['class' => 'mws-form-label']))  !!}
							<div class="mws-form-item">
								{{ Form::text('dancer[0][last_name]','',['class' => 'form-control']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('dancer[0][last_name]'); ?>
								</div>
							</div>
						</div>
						<div class="form-group <?php echo ($errors->first('dancer[0][state]')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('dancer[0][state]', trans("State").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
							<div class="parent_state_div">
								{{ Form::select('dancer[0][state]',array(null=>'State'),'',['data-rel'=>4,'class'=>'form-control state_id state_id_4 chosen-select']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('dancer[0][state]'); ?>
								</div>
							</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						
						<div class="form-group <?php echo ($errors->first('dancer[0][email]')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('dancer[0][email]',trans("messages.user_management.email").'<span class="requireRed">  </span>', ['class' => 'mws-form-label']))  !!}
							<div class="mws-form-item">
								{{ Form::text('dancer[0][email]','',['class' => 'form-control dancer_email']) }}
								<div class="error-message help-inline" id="dancer_email_error">
									<?php echo $errors->first('dancer[0][email]'); ?>
								</div>
							</div>
						</div>
						
						<div class="form-group <?php echo ($errors->first('dancer[0][city]')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('dancer[0][city]', trans("City").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
							<div class="parent_city_div">
								{{ Form::select('dancer[0][city]',array(null=>'City'),'',['class'=>'form-control chosen-select city_id city_id_4']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('dancer[0][city]'); ?>
								</div>
							</div>
							</div>
						</div>
						
					</div>
					<div class="col-md-3">
						
						<div class="form-group <?php echo ($errors->first('dancer[0][gender]')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('dancer[0][gender]', trans("Gender").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::radio('dancer[0][gender]','male',true,array('id'=>'male_id' ,'class'=>'gender_id')) }}
								{{ Form::label('male_id',trans("messages.user_management.male")) }}
							
								{{ Form::radio('dancer[0][gender]','female',false,array('id'=>'female_id','class'=>'gender_id')) }}
								{{ Form::label('female_id',trans("messages.user_management.female")) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('dancer[0][gender]'); ?>
								</div>
							</div>
						</div>
						
						<div class="form-group <?php echo ($errors->first('dancer[0][date]')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('dancer[0][date]',trans("Date").'<span class="requireRed">  </span>', ['class' => 'mws-form-label']))  !!}
							<div class="mws-form-item">
								{{ Form::text('dancer[0][date]','',['class' => 'form-control add_more_parent_dancer','readonly'=>'readonly']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('dancer[0][date]'); ?>
								</div>
							</div>
						</div>
						
					</div>
				</td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>
<?php //echo $counter;die;?>
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
