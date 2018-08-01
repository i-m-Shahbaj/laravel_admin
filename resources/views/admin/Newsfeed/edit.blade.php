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
		{{ trans("Edit Press Release") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{URL::to('cmeshinepanel/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{URL::to('cmeshinepanel/newsfeed')}}">Press Release</a></li>
		<li class="active">Edit Press Release</li>
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
		{{ Form::open(['role' => 'form','route' =>["Newsfeed.update","$eventDetail->id"],'class' => 'mws-form', 'files' => true]) }}
			
			<div class="form-group <?php echo ($errors->first('name')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('name', trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text('name',isset($eventDetail->name) ? $eventDetail->name :'',['class' => 'form-control']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('name'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('description')) ? 'has-error' : ''; ?>">
				{!! HTML::decode( Form::label('description', trans("Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
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
			
			<div class="mws-button-row">
				<input type="submit" value="{{ trans('messages.global.save') }}" class="btn btn-danger">
				
				<a href='{{URL::to("cmeshinepanel/newsfeed/edit-newsfeed/".$eventDetail->id)}}' class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('messages.global.reset') }}</a>
			
				<a href="{{URL::to('cmeshinepanel/newsfeed')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
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
