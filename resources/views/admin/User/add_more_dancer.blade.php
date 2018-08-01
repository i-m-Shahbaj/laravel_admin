
<tr class="panel panel-default delete_add_more_danc{{$counter}}" rel="{{$counter}}">
	
	<td colspan="2">
		<div class="col-md-3">
			<div class="form-group <?php echo ($errors->first('dancer['.$counter.'][first_name]')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('dancer['.$counter.'][first_name]',trans("messages.user_management.first_name").'<span class="requireRed">  </span>', ['class' => 'mws-form-label']))  !!}
				<div class="mws-form-item">
					{{ Form::text('dancer['.$counter.'][first_name]','',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('dancer['.$counter.'][first_name]'); ?>
					</div>
				</div>
			</div>
			
			<div class="form-group <?php echo ($errors->first('dancer['.$counter.'][country]')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('dancer['.$counter.'][country]', trans("messages.user_management.country").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::select("dancer[$counter][country]",$countryList,'', ['data-rel'=>3,'class'=>'form-control chosen-select countries_id countries_id_3','placeholder'=>'Select Country']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('dancer['.$counter.'][country]'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('dancer['.$counter.'][send_notification]')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('dancer[send_notification][0]', trans("Send notification to dancer (Y/N).").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::radio('dancer['.$counter.'][send_notification]','Yes',true,array('id'=>'male_id' ,'class'=>'send_notification_id')) }}
					{{ Form::label('yes_id',trans("Yes")) }}
				
					{{ Form::radio('dancer['.$counter.'][send_notification]','No',false,array('id'=>'female_id','class'=>'send_notification_id')) }}
					{{ Form::label('no_id',trans("No")) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('dancer['.$counter.'][send_notification]'); ?>
					</div>
				</div>
			</div>
			
			
		</div>
		<div class="col-md-3">
			<div class="form-group <?php echo ($errors->first('dancer['.$counter.'][last_name]')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('dancer[last_name][0]',trans("messages.user_management.last_name").'<span class="requireRed">  </span>', ['class' => 'mws-form-label']))  !!}
				<div class="mws-form-item">
					{{ Form::text('dancer['.$counter.'][last_name]','',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('dancer['.$counter.'][last_name]'); ?>
					</div>
				</div>
			</div>
			
			<div class="form-group <?php echo ($errors->first('dancer['.$counter.'][state]')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('dancer['.$counter.'][state]', trans("State").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
				<div class="parent_state_div">
					{{ Form::select('dancer['.$counter.'][state]',array(null=>'State'),'',['data-rel'=>3,'class'=>'form-control state_id state_id_3 chosen-select']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('dancer['.$counter.'][state]'); ?>
					</div>
				</div>
				</div>
			</div>
			
		</div>
		<div class="col-md-3">
			<div class="form-group <?php echo ($errors->first('dancer['.$counter.'][email]')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('dancer['.$counter.'][email]',trans("messages.user_management.email").'<span class="requireRed">  </span>', ['class' => 'mws-form-label']))  !!}
				<div class="mws-form-item">
					{{ Form::text('dancer['.$counter.'][email]','',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('dancer['.$counter.'][email]'); ?>
					</div>
				</div>
			</div>
			
			<div class="form-group <?php echo ($errors->first('dancer['.$counter.'][city]')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('dancer['.$counter.'][city]', trans("City").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
				<div class="parent_city_div">
					{{ Form::select('dancer['.$counter.'][city]',array(null=>'City'),'',['class'=>'form-control chosen-select city_id city_id_3']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('dancer['.$counter.'][city]'); ?>
					</div>
				</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			
			<div class="form-group <?php echo ($errors->first('dancer['.$counter.'][gender]')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('dancer['.$counter.'][gender]', trans("Gender").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::radio('dancer['.$counter.'][gender]','male',true,array('id'=>'male_id' ,'class'=>'gender_id')) }}
					{{ Form::label('male_id',trans("messages.user_management.male")) }}
				
					{{ Form::radio('dancer['.$counter.'][gender]','female',false,array('id'=>'female_id','class'=>'gender_id')) }}
					{{ Form::label('female_id',trans("messages.user_management.female")) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('dancer['.$counter.'][gender]'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('dancer['.$counter.'][date]')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('dancer['.$counter.'][date]',trans("Date").'<span class="requireRed">  </span>', ['class' => 'mws-form-label']))  !!}
				<div class="mws-form-item">
					{{ Form::text('dancer['.$counter.'][date]','',['class' => 'form-control add_more_parent_dancer','readonly'=>'readonly']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('dancer['.$counter.'][date]'); ?>
					</div>
				</div>
			</div>
			
		</div>
	</td>
	<td align="center">
		<a href="javascript:void(0);" onclick="delete_danc_row('{{$counter}}','');" id="{{$counter}}" class="btn btn-danger btn-small pull-left">
			<i class="fa fa-trash-o"></i>
		</a>
	</td>
</tr>
<script type="text/javascript"> 
	$(document).ready(function(){
		$( ".add_more_parent_dancer" ).datepicker({
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
