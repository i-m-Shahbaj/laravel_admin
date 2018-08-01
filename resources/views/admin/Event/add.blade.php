@extends('admin.layouts.default')
@section('content')
<!-- CKeditor start here-->
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor ends-->
<!-- datetime picker js and css start here-->
{{ HTML::script('js/admin/jui/js/jquery-ui-1.9.2.min.js') }}
{{ HTML::script('js/admin/jui/js/timepicker/jquery-ui-timepicker.min.js') }}
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
<!-- date time picker js and css and here-->

<script type="text/javascript">

/* For datetimepicker */
	$(function(){
		//$(".chzn-select").chosen();
		$("#scheduled_time").datetimepicker({
			inline: 	true ,
			minDate	  : 0 ,
			dateFormat: 'yy-mm-dd',
			numberOfMonths	: 1,
			changeMonth:true,
			changeYear:true,
			onSelect: function( selectedDate ){
				$("#scheduled_end_time").datetimepicker("option","minDate",selectedDate);
			}
		});
		
		$("#scheduled_end_time").datetimepicker({
			inline: 	true,
			minDate	  : 0 ,
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 1,
			changeMonth:true,
			changeYear:true,
			onSelect: function( selectedDate ){
				$("#scheduled_time").datetimepicker("option","maxDate",selectedDate);
			}
		});
	});
	jQuery(document).ready(function(){
		$(".organiser").chosen();
		$(".category").chosen();
		//$(".currencyCls").chosen();
	});
</script>
<section class="content-header">
	<h1>
		{{ trans("Add New Event") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href='{{route("$modelName.index")}}'>Event Management</a></li>
		<li class="active">Add New Event</li>
	</ol>
</section>
<section class="content"> 
<div class="row pad"> 
	<div class="col-md-12">
		<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
	</div> 
</div>
	<div class="row pad">
		<div class="col-md-6">	
		{{ Form::open(['role' => 'form','url' => 'cmeshinepanel/events/add-event','class' => 'mws-form']) }}
			
			<div class="form-group <?php echo ($errors->first('name')?'has-error':''); ?>">
				{!! HTML::decode( Form::label('name', trans("Event Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text("name" , null , ['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('name'); ?>
					</div>
				</div>
			</div>
			
			<div class="form-group <?php echo ($errors->first('point_of_contact')?'has-error':''); ?>">
				{!! HTML::decode( Form::label('point_of_contact', trans("Point of Contact").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text("point_of_contact" , null , ['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('point_of_contact'); ?>
					</div>
				</div>
			</div>
			
			<div class="form-group <?php echo ($errors->first('phone_number')?'has-error':''); ?>">
				{!! HTML::decode( Form::label('phone_number', trans("Phone Number").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text("phone_number" , null , ['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('phone_number'); ?>
					</div>
				</div>
			</div>
			
			<div class="form-group <?php echo ($errors->first('email')?'has-error':''); ?>">
				{!! HTML::decode( Form::label('email', trans("Email").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text("email" , null , ['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('email'); ?>
					</div>
				</div>
			</div>
			
			<div class="form-group <?php echo ($errors->first('location')?'has-error':''); ?>">
				{!! HTML::decode( Form::label('location', trans("Event Location").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text("location" , null , ['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('location'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('description')?'has-error':''); ?>">
				{!! HTML::decode( Form::label('description', trans("Event Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::textarea("description",'', 
					['class' => 'form-control textarea_resize','id'=>'body',"rows"=>3,"cols"=>3]) }}
					<script type="text/javascript">
								/* For CKEDITOR */
						CKEDITOR.replace( <?php echo 'body'; ?>,
						{
							height: 350,
							width: 507,
							filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
							filebrowserImageWindowWidth : '640',
							filebrowserImageWindowHeight : '480',
							enterMode : CKEDITOR.ENTER_BR
						});
										
					</script>
					<div class="error-message help-inline">
						<?php echo $errors->first('description'); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="mws-form-row">
				{!! HTML::decode( Form::label('user_id',trans("Organized By"), ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
					{{ Form::select(
					'user_id',
					array(''=>'Select organizer')+$ListUser,
					'null', 
					['class' => 'form-control chosen_select organiser']
					) }} 
					</div>
				<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
					<b>{{ trans("Leave blank for self") }}</b>
				</div>
				</div>
			</div>
			
			<div class="form-group <?php echo ($errors->first('start_datetime')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					{!!  HTML::decode(Form::label('start_datetime', trans("Start Datetime").'<span class="requireRed">*</span>' ,['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text('start_datetime', '', ['class' => 'form-control' ,'id' => 'scheduled_time' ,'readonly'=>'readonly']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('start_datetime'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('end_datetime')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					{!!  HTML::decode(Form::label('end_datetime', trans("End Datetime").'<span class="requireRed">*</span>' ,['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text('end_datetime', '', ['class' => 'form-control' ,'id' => 'scheduled_end_time' ,'readonly'=>'readonly']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('end_datetime'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="mws-panel-body"> 
				<div class="mws-form-inline"> 
					<div class="form-group">
						{!! HTML::decode( Form::label('event_type',trans("Event Type").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::radio('event_type','free',true,array('id'=>'free_event')) }}
							{{ Form::label('event_type',trans("Free")) }}
							{{ Form::radio('event_type','paid',false,array('id'=>'paid_event')) }}
							{{ Form::label('event_type',trans("Paid")) }}
							<span class="error-message help-inline">
								<?php echo $errors->first('event_type'); ?>
							</span>
						</div>
					</div>
				</div>
				
				<div class="price_of_event" <?php if(!empty( $errors->first('currency') || $errors->first('price'))) { } else { ?> style='display:none' <?php } ?> >
					<?php $currencies = '';?>
					<div class="form-group <?php echo ($errors->first('currency')?'has-error':''); ?>">
						{!! HTML::decode( Form::label('currency', trans("Currency").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							@if(!empty($currencies))
							<select name="currency" id="currency" class="form-control currencyCls" >
								<option value="">{{ trans("messages.messages.event.select.currency") }}</option>
							  @foreach($currencies as $currency)
								<?php 
									$selected	=	'';
									if(!empty(Input::old('currency')) ){
										if(Input::old('currency')	==	$currency->currency){
											$selected	=	'selected';
										}
									}
								?>
								<option value="{{{ $currency->currency }}}" <?php echo $selected; ?> >{{{ $currency->currency }}}</option>
							  @endforeach
							</select>
							@endif
							<div class="error-message help-inline">
								<?php echo $errors->first('currency'); ?>
							</div>
						</div>
					</div>
				</div>
				
				
				<div class="price_of_event" <?php if(!empty( $errors->first('price')) || $errors->first('currency') ) { } else { ?> style='display:none' <?php } ?>>
					<div class="form-group <?php echo ($errors->first('price')?'has-error':''); ?>">
						{!! HTML::decode( Form::label('price', trans("Price").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
						<div class="mws-form-item">
							{{ Form::text("price" , null , ['class' => 'form-control amount_type']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('price'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<input type="submit" value="{{ trans('messages.global.save') }}" class="btn btn-danger">
				
				<a href="{{URL::to('cmeshinepanel/events/add-event')}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset')  }}</a>
				
				<a href="{{URL::to('cmeshinepanel/events')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</section>
<style>
	.textarea_resize {
		resize: vertical;
	}
	
	#currency_chosen{
		//width:529px !important;
	}
</style>
<script>
	var val	=	"{{{ Input::old('event_type') }}}";
	if(val	==	'paid'){
		$(".price_of_event").show();
	}
	$("#paid_event").click(function(){
	$(".price_of_event").show();
});
	$("#free_event").click(function(){
	$(".price_of_event").hide();
	$(".amount_type").val('');
	$("#currency").val('');
});
</script>
@stop
