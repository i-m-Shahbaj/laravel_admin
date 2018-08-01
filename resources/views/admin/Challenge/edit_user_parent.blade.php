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
	
	<div class="form-group <?php echo ($errors->first('country')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('country', trans("messages.user_management.country").'<span class="requireRed">  *</span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
			{{ Form::select("country",$countryList,isset($userDetails->country) ? $userDetails->country :'', ['data-rel'=>103,'class'=>'form-control chosen-select countries_id countries_id_103','placeholder'=>'Select Country']) }}
			<div class="error-message help-inline" id="country_error">
				<?php echo $errors->first('country'); ?>
			</div>
		</div>
	</div>

	<div class="form-group <?php echo ($errors->first('state')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('state', trans("State").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
		<div class="parent_state_div">
			{{ Form::select('state',$stateList,isset($userDetails->state) ? $userDetails->state :'',['data-rel'=>103,'class'=>'form-control state_id state_id_103 chosen-select']) }}
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
			{{ Form::select('city',$cityList,isset($userDetails->city) ? $userDetails->city :'',['class'=>'form-control chosen-select city_id city_id_103']) }}
			<div class="error-message help-inline" id="city_error">
				<?php echo $errors->first('city'); ?>
			</div>
		</div>
		</div>
	</div>
	
	<div class="form-group <?php echo ($errors->first('gender')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('gender', trans("Gender").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
			{{ Form::radio('gender','male',($userDetails->gender=='male')?true:false,array('id'=>'male_id' ,'class'=>'gender_id')) }}
			{{ Form::label('male_id',trans("messages.user_management.male")) }}
		
			{{ Form::radio('gender','female',($userDetails->gender=='female')?true:false,array('id'=>'female_id','class'=>'gender_id')) }}
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
	<div class="form-group <?php echo ($errors->first('email')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('email', trans("messages.user_management.email").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
			{{ Form::text('email',isset($userDetails->email) ? $userDetails->email :'',['class' => 'form-control']) }}
			<div class="error-message help-inline" id="email_error">
				<?php echo $errors->first('email'); ?>
			</div>
		</div>
	</div>
	<div class="form-group <?php echo ($errors->first('username')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('username',trans("Username").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('username',isset($userDetails->username) ? $userDetails->username :'',['class' => 'form-control','id'=>'username']) }}
			<div class="error-message help-inline" id="username_error">
				<?php echo $errors->first('username'); ?>
			</div>
		</div>
	</div>
	<div class="form-group <?php echo ($errors->first('relationship')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('relationship', trans("Relationship").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
		<div class="mws-form-item">
			{{ Form::text('relationship',isset($userDetails->relationship) ? $userDetails->relationship :'',['class' => 'form-control']) }}
			<div class="error-message help-inline" id="relationship_error">
				<?php echo $errors->first('relationship'); ?>
			</div>
		</div>
	</div>
	<?php /* <div class="mws-form-row">
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
	<div class="form-group <?php echo ($errors->first('date')) ? 'has-error' : ''; ?>">
		{!! HTML::decode( Form::label('date',trans("Date of Birth").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
		<div class="mws-form-item">
			{{ Form::text('date',isset($userDetails->date) ? $userDetails->date :'',['class' => 'form-control parent_date','readonly'=>'readonly']) }}
			<div class="error-message help-inline" id="date_error">
				<?php echo $errors->first('date'); ?>
			</div>
		</div>
	</div>
	
</div>
<hr>
<div class="col-md-12">
	<table class="table table-responsive table-bordered lastsizedetailsrow">
		
		<thead>
			<tr>
				<td>
					<h4>Please tell us about each of your Dancers
					<br><span>(Press "+" to add additional children)</span></h4> 
					
				</td>
				<td colspan="2" class="text-right">
					<span class="align text-right"><button title="Add More" class="btn btn-info" type="button"  onclick="danc_add_more();" ><i class="fa fa-plus"></i></button></span>
				</td>
			</tr>
		</thead>
		<tbody>
			@if(!($userParentDetails)->isEmpty())
			@foreach($userParentDetails as $k=>$childDetail)
				<tr class="panel panel-default delete_add_more_danc{{$k}}" rel="{{$k}}">
					<td colspan="2">
						{{ Form::hidden('dancer['.$k.'][id]',isset($childDetail->id) ? $childDetail->id :'',['class' => '']) }}
						<div class="col-md-3">
							<div class="form-group <?php echo ($errors->first('dancer['.$k.'][first_name]')) ? 'has-error' : ''; ?>">
								{!! HTML::decode( Form::label('dancer['.$k.'][first_name]',trans("messages.user_management.first_name").'<span class="requireRed">  </span>', ['class' => 'mws-form-label']))  !!}
								<div class="mws-form-item">
									{{ Form::text('dancer['.$k.'][first_name]',isset($childDetail->first_name) ? $childDetail->first_name :'',['class' => 'form-control']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('dancer[0][first_name]'); ?>
									</div>
								</div>
							</div>
							
							<div class="form-group <?php echo ($errors->first('dancer['.$k.'][country]')) ? 'has-error' : ''; ?>">
								{!! HTML::decode( Form::label('dancer['.$k.'][country]', trans("messages.user_management.country").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
									{{ Form::select("dancer[$k][country]",$countryList,isset($childDetail->country) ? $childDetail->country :'', ['data-rel'=>$k,'class'=>'form-control chosen-select countries_id countries_id_'.$k.'','placeholder'=>'Select Country']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('dancer['.$k.'][country]'); ?>
									</div>
								</div>
							</div>
							<div class="form-group <?php echo ($errors->first('dancer['.$k.'][send_notification]')) ? 'has-error' : ''; ?>">
								{!! HTML::decode( Form::label('dancer['.$k.'][send_notification]', trans("Send notification to dancer (Y/N).").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
									{{ Form::radio('dancer['.$k.'][send_notification]','Yes',($childDetail->send_notification=='Yes')?true:false,array('id'=>'male_id' ,'class'=>'send_notification_id')) }}
									{{ Form::label('yes_id',trans("Yes")) }}
								
									{{ Form::radio('dancer['.$k.'][send_notification]','No',($childDetail->send_notification=='No')?true:false,array('id'=>'female_id','class'=>'send_notification_id')) }}
									{{ Form::label('no_id',trans("No")) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('dancer['.$k.'][send_notification]'); ?>
									</div>
								</div>
							</div>
						</div>
							
						<div class="col-md-3">
							<div class="form-group <?php echo ($errors->first('dancer['.$k.'][last_name]')) ? 'has-error' : ''; ?>">
								{!! HTML::decode( Form::label('dancer['.$k.'][last_name]',trans("messages.user_management.last_name").'<span class="requireRed">  </span>', ['class' => 'mws-form-label']))  !!}
								<div class="mws-form-item">
									{{ Form::text('dancer['.$k.'][last_name]',isset($childDetail->last_name) ? $childDetail->last_name :'',['class' => 'form-control']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('dancer['.$k.'][last_name]'); ?>
									</div>
								</div>
							</div>
							<div class="form-group <?php echo ($errors->first('dancer['.$k.'][state]')) ? 'has-error' : ''; ?>">
								{!! HTML::decode( Form::label('dancer['.$k.'][state]', trans("State").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
								<div class="parent_state_div">
									{{ Form::select('dancer['.$k.'][state]',$stateList,isset($childDetail->state) ? $childDetail->state :'',['data-rel'=>$k,'class'=>'form-control state_id state_id_'.$k.' chosen-select']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('dancer['.$k.'][state]'); ?>
									</div>
								</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							
							<div class="form-group <?php echo ($errors->first('dancer['.$k.'][email]')) ? 'has-error' : ''; ?>">
								{!! HTML::decode( Form::label('dancer['.$k.'][email]',trans("messages.user_management.email").'<span class="requireRed">  </span>', ['class' => 'mws-form-label']))  !!}
								<div class="mws-form-item">
									{{ Form::text('dancer['.$k.'][email]',isset($childDetail->email) ? $childDetail->email :'',['class' => 'form-control']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('dancer['.$k.'][email]'); ?>
									</div>
								</div>
							</div>
							
							<div class="form-group <?php echo ($errors->first('dancer['.$k.'][city]')) ? 'has-error' : ''; ?>">
								{!! HTML::decode( Form::label('dancer['.$k.'][city]', trans("City").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
								<div class="parent_city_div">
									{{ Form::select('dancer['.$k.'][city]',$cityList,isset($childDetail->city) ? $childDetail->city :'',['class'=>'form-control chosen-select city_id city_id_'.$k.'']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('dancer['.$k.'][city]'); ?>
									</div>
								</div>
								</div>
							</div>
							
						</div>
						<div class="col-md-3">
							
							<div class="form-group <?php echo ($errors->first('dancer['.$k.'0][gender]')) ? 'has-error' : ''; ?>">
								{!! HTML::decode( Form::label('dancer['.$k.'][gender]', trans("Gender").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
									{{ Form::radio('dancer['.$k.'][gender]','male',($childDetail->gender=='male')?true:false,array('id'=>'male_id' ,'class'=>'gender_id')) }}
									{{ Form::label('male_id',trans("messages.user_management.male")) }}
								
									{{ Form::radio('dancer['.$k.'][gender]','female',($childDetail->gender=='female')?true:false,array('id'=>'female_id','class'=>'gender_id')) }}
									{{ Form::label('female_id',trans("messages.user_management.female")) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('dancer['.$k.'][gender]'); ?>
									</div>
								</div>
							</div>
							
							<div class="form-group <?php echo ($errors->first('dancer['.$k.'][date]')) ? 'has-error' : ''; ?>">
								{!! HTML::decode( Form::label('dancer['.$k.'][date]',trans("Date of Birth").'<span class="requireRed">  </span>', ['class' => 'mws-form-label']))  !!}
								<div class="mws-form-item">
									{{ Form::text('dancer['.$k.'][date]',isset($childDetail->date) ? $childDetail->date :'',['class' => 'form-control add_more_parent_dancer','readonly'=>'readonly']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('dancer['.$k.'][date]'); ?>
									</div>
								</div>
							</div>
							
						</div>
					</td>
					<td align="center">
						<a href="javascript:void(0);" onclick="delete_danc_row('{{$k}}','{{$childDetail->id}}');" id="{{$k}}" class="btn btn-danger btn-small pull-left">
							<i class="fa fa-trash-o"></i>
						</a>
					</td>
				</tr>
			@endforeach
			@else
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
									{{ Form::select("dancer[0][country]",$countryList,'', ['data-rel'=>3,'class'=>'form-control chosen-select countries_id countries_id_3','placeholder'=>'Select Country']) }}
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
									{{ Form::select('dancer[0][state]',array(null=>'State'),'',['data-rel'=>3,'class'=>'form-control state_id state_id_3 chosen-select']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('dancer[0][state]'); ?>
									</div>
								</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							
							<div class="form-group <?php echo ($errors->first('dancer[0][email]')) ? 'has-error' : ''; ?>">
								{!! HTML::decode( Form::label('dancer[0][email]',trans("messages.user_management.email").'<span class="requireRed">  </span>', ['class' => 'mws-form-label dancer_email']))  !!}
								<div class="mws-form-item">
									{{ Form::text('dancer[0][email]','',['class' => 'form-control']) }}
									<div class="error-message help-inline">
										<?php echo $errors->first('dancer[0][email]'); ?>
									</div>
								</div>
							</div>
							
							<div class="form-group <?php echo ($errors->first('dancer[0][city]')) ? 'has-error' : ''; ?>">
								{!! HTML::decode( Form::label('dancer[0][city]', trans("City").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
								<div class="mws-form-item">
								<div class="parent_city_div">
									{{ Form::select('dancer[0][city]',array(null=>'City'),'',['class'=>'form-control chosen-select city_id city_id_3']) }}
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
			@endif
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
	function danc_add_more(){
		
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		var get_last_id			=	$(".lastsizedetailsrow").find('tr').last().attr('rel');
		if(typeof get_last_id=="undefined"){
			var counter 		=	0;
		}else{
			var counter  	 	=  parseInt(get_last_id) + 1;	
		}
		$.ajax({
			headers: {
			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url:'{{ route($modelName.".addmoreDancer") }}',
			'type':'post',
			data:{'counter':counter},
			success:function(response){
				$('#loader_img').hide();
				$('.lastsizedetailsrow').find('tr').last().after(response);
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
</script>

<style>
.chosen-container-single .chosen-single{
	padding: 5px 5px 5px 8px;
    height: 35px;
}
</style>
