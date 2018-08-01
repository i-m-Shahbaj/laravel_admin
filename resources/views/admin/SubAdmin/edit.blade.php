@extends('admin.layouts.default')
@section('content') 
<section class="content-header">
	<h1>
		{{ trans("messages.user_management.edit_user") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">{{ trans("User Management") }}</a></li>
		<li class="active">{{ trans("messages.user_management.edit_user") }} </li>
	</ol>
</section>
<section class="content"> 
	<div class="row pad"> 
		<div class="col-md-12">
			<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
		</div> 
	</div>
	{{ Form::open(['role' => 'form','route' => $modelName.'.update','class' => 'mws-form','files'=>'true', 'id'=>'dancer_user_form']) }}
	{{ Form::hidden('id',isset($userDetails->id) ? $userDetails->id :'',['class' => '','id'=>'user_id']) }}
	<div class="row pad">
	<div class="mws-form-item"> 
			<div id="" class="row col-md-12">	
				<div class="user_info" id="user_info">	
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
					</div>
					<div class="col-md-6">
						<div class="form-group <?php echo ($errors->first('username')) ? 'has-error' : ''; ?>">
							{!! HTML::decode( Form::label('first_name',trans("Username").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label']))  !!}
							<div class="mws-form-item">
								{{ Form::text('username',isset($userDetails->username) ? $userDetails->username :'',['class' => 'form-control']) }}
								<div class="error-message help-inline" id="username_error">
									<?php echo $errors->first('username'); ?>
								</div>
							</div>
						</div>
						<div class="mws-form-row">
							<div class="mws-form-message info">{{ trans("messages.user_management.please_leave_blank_if_you_do_not_want_to_change_password") }}
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
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<div class="mws-button-row">
		<div class="input" >
			<input type="button" value="{{ trans('messages.user_management.save') }}" class="btn btn-danger" onclick="update_dancer_user_data();">
			<a href="{{route($modelName.'.edit',$userDetails->id)}}" class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans("Clear") }}</a>
			<a href="{{route($modelName.'.index')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans("Cancel") }}</a>
		</div>
	</div>
	{{ Form::close() }}
	
	<div id="loader_img" style="display:none"><center><img src="{{WEBSITE_IMG_URL}}loading.gif"></center></div>
</section> 

<style> 
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
<script type="text/javascript">
	 
	
	function update_dancer_user_data() {
		var formData = $('#dancer_user_form')[0];
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$('.disabled_field').removeAttr('disabled');
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '{{ route($modelName.".update") }}',
			type:'post',
			data: new FormData(formData),
			dataType: 'json',
			contentType: false, // The content type used when sending data to the server.
			cache: false, // To unable request pages to be cached
			processData:false,
			success: function(r){
				error_array 	= 	JSON.stringify(r);
				data			=	JSON.parse(error_array);
				if(data['success'] == 1) {
					$('#dancer_user_form')[0].reset();
					window.location.href	 =	"{{ route($modelName.'.index') }}";
					show_message("User update successfully.",'success');
				}
				else {
					$('.disabled_field').attr('disabled','disabled');
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
 
@stop
