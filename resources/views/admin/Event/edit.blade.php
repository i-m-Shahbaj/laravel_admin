@extends('admin.layouts.default')
@section('content')
<!-- CKeditor start here-->
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor ends-->
<!-- datetime picker js and css start here-->
{{ HTML::script('js/admin/jui/js/jquery-ui-1.9.2.min.js') }}
{{ HTML::script('js/admin/jui/js/timepicker/jquery-ui-timepicker.min.js') }}
{{ HTML::script('js/admin/prettyCheckable.js') }}
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::style('css/admin/prettyCheckable.css') }}
{{ HTML::style('css/admin/timepicker.css') }}
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
	});
</script>
	<section class="content-header">
	<h1>
		{{ trans("Edit Event") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{URL::to('admin/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{URL::to('admin/event-manager')}}">Event Management</a></li>
		<li class="active">Edit Event</li>
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
		{{ Form::open(['role' => 'form','url' =>"admin/event-manager/edit-event/".$eventDetail->id,'class' => 'mws-form', 'files' => true]) }}
			
			<div class="form-group">
				<div class="mws-form-row">
					{{ HTML::decode( Form::label('category_id',trans("Category").'<span class="requireRed">*</span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
					{{ Form::select(
					'category_id',$listCategory,$eventDetail->category_id,['class' => 'form-control chosen_select category','placeholder' => 'Select category']
					) }} 
					<div class="error-message help-inline">
						<?php echo $errors->first('category_id'); ?>
					</div>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('name')) ? 'has-error' : ''; ?>">
				{{ HTML::decode( Form::label('first_name',trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('name',isset($eventDetail->name) ? $eventDetail->name :'',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('name'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('location')) ? 'has-error' : ''; ?>">
				{{ HTML::decode( Form::label('location',trans("Location").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('location',isset($eventDetail->location) ? $eventDetail->location :'',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('location'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('description')) ? 'has-error' : ''; ?>">
				{{ HTML::decode( Form::label('description',trans("Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::textarea('description',isset($eventDetail->description) ? $eventDetail->description :'',['class' => 'form-control textarea_resize','id'=>'body', "rows"=>3,"cols"=>3]) }}
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
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('user_id',trans("Organized By"), ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
				{{ Form::select(
				'user_id',$ListUser,$eventDetail->user_id,['class' => 'form-control chosen_select organiser','placeholder' => 'Select organizer']
				) }} 
				</div>
				<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
					<b>{{ trans("Leave blank for self") }}</b>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('start_datetime')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					{{  HTML::decode(Form::label('start_datetime', trans("Start Datetime").'<span class="requireRed">*</span>' ,['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::text('start_datetime', isset($eventDetail->start_datetime) ? $eventDetail->start_datetime :'', ['class' => 'form-control' ,'id' => 'scheduled_time' ,'readonly'=>'readonly']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('start_datetime'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('end_datetime')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					{{  HTML::decode(Form::label('end_datetime', trans("End Datetime").'<span class="requireRed">*</span>' ,['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::text('end_datetime', isset($eventDetail->end_datetime) ? $eventDetail->end_datetime :'', ['class' => 'form-control' ,'id' => 'scheduled_end_time' ,'readonly'=>'readonly']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('end_datetime'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="mws-panel-body"> 
				<div class="mws-form-inline"> 
					<div class="form-group">
						{{ HTML::decode( Form::label('event_type',trans("Event Type").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							{{ Form::radio('event_type','free',( isset($eventDetail->event_type) && $eventDetail->event_type =='free') ? 'true' : '', array('id'=>'free_event')) }}
							{{ Form::label('event_type',trans("Free")) }}
							{{ Form::radio('event_type','paid',( isset($eventDetail->event_type) && $eventDetail->event_type =='paid') ? 'true' : '',array('id'=>'paid_event')) }}
							{{ Form::label('event_type',trans("Paid")) }}
							<span class="error-message help-inline">
								<?php echo $errors->first('event_type'); ?>
							</span>
						</div>
					</div>
				</div>
				<?php //echo $eventDetail->price;?>
				
				<div class="price_of_event">
					<?php $currencies = CustomHelper::get_active_currencies();?>
					<div class="form-group <?php echo ($errors->first('currency')?'has-error':''); ?>">
						{{ HTML::decode( Form::label('currency', trans("Currency").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							@if(!empty($currencies))
							<select name="currency" id="currency" class="form-control currencyCls" >
								<option value="">{{ trans("messages.messages.event.select.currency") }}</option>
							  @foreach($currencies as $currency)
								<?php
									$selectedCurrency	=	"";
									$select_curr		=	isset($eventDetail->currency)  ? $eventDetail->currency :'';
									if($select_curr == $currency->currency){
										$selectedCurrency			=	"selected=selected";
									}
								?>
								<option value="{{{ $currency->currency }}}" <?php echo $selectedCurrency; ?> >{{{ $currency->currency }}}</option>
							  @endforeach
							</select>
							@endif
							<div class="error-message help-inline">
								<?php echo $errors->first('currency'); ?>
							</div>
						</div>
					</div>
				</div>
				
				<div class="price_of_event">
					<div class="form-group <?php echo ($errors->first('price')?'has-error':''); ?>">
						{{ HTML::decode( Form::label('price', trans("Price").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							{{ Form::text("price" ,isset($eventDetail-> price) ? $eventDetail->price :'', ['class' => 'form-control price_class']) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('price'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<input type="submit" value="{{ trans('messages.global.save') }}" class="btn btn-danger">
				
				<a href='{{URL::to("admin/event-manager/edit-event/".$eventDetail->id)}}' class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('messages.global.reset') }}</a>
			
				<a href="{{URL::to('admin/event-manager')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
			</div>
		{{ Form::close() }}
		</div>
	</div>
</section>
<style>
	.textarea_resize {
		resize: vertical;
	}
</style>
<script>
	var val	=	"{{{ $eventDetail->event_type }}}";
	if(val	==	'paid'){
		$(".price_of_event").show();
	}else{
		$(".price_of_event").hide();
	}
	$("#paid_event").click(function(){
		$(".price_of_event").show();
		 $(".price_class").val('{{{ $eventDetail->price }}}');
		 $("#currency").val('{{{ $eventDetail->currency }}}');
	});
	$("#free_event").click(function(){
		$(".price_of_event").hide();
		 $(".price_class").val('');
		 $("#currency").val('');
	});
</script>	
@stop