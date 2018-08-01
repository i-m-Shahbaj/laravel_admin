@extends('front.layouts.default')
@section('content')
<div class="clearfix"></div>
<div class="cms-page-wrapper clearfix">



	<div class="login-form forgot-pswd">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="page-title-wrapper">
						<h1 class="page-title">Reset Password</h1>
					</div>
				
					@if(Session::has('login_message'))
						 <div class="alert alert-danger alert-dismissable fade in">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							{{ Session::get('login_message')}}
						</div>
					@endif
					@if(Session::has('error'))
						<div class="alert alert-danger">
							<strong>Error!</strong> {{ Session::get('error')}}
						</div>
					@endif
					@if(Session::has('success'))
						<div class="alert alert-success">
						  <strong>Success!</strong> {{ Session::get('success')}}
						</div>
					@endif
					@if(Session::has('flash_notice'))
						<div class="alert alert-info">
							<strong>Info!</strong> {{ Session::get('flash_notice')}}
						</div>
					@endif
					<div class="alert alert-danger alert-dismissable fade in error_box" style="display:none;">
						<a href="#" class="close" onclick="removeAlert();" aria-label="close">&times;</a>
						<span class="error_msg"></span>
					</div>
					<div class="login_form_field login-form-inner">
						{{ Form::open(['role' => 'form','url' => "saveResetPassword",'id'=>"user_profile_form"]) }}
						{{ Form::hidden("validate_string", $validateString, []) }}
						<div class="row">
						   <div class="col-sm-4"></div>
						   <div class="col-sm-4">
								<div class="form-group"> 
									{{ Form::password("new_password",['class' => 'form-control login_form_control','placeholder'=>trans("New Password")]) }}
									<span class="help-inline"></span>
								</div>
								<div class="form-group"> 
									{{ Form::password("confirm_password",['class' => 'form-control login_form_control','placeholder'=>trans("Confirm Password")]) }}
									<span class="help-inline"></span>
								</div>
								
								<div class="login_form_submit">
									<input type="button" class="submit-button btn btn-success" value="Submit" onclick="resetpassword();"> 
								</div>
                                <div class="form-group">				   
								   <a href="{{ URL('login') }}" class="Back_login">Back to login</a>
								</div>
							</div>
						   <div class="col-sm-4"></div>
						</div>
						{{ Form::close() }} 
						</div>
					</div>
				</div>	
			</div>  
		</div> 
	
@include('front.elements.footer')
</div>


<script>
	function resetpassword() {
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$.ajax({
			url: '{{ URL("reset-password") }}',
			type:'post',
			data: $('#user_profile_form').serialize(),
			success: function(r){
				error_array 	= 	JSON.stringify(r);
				data			=	JSON.parse(error_array);
				if(data['success'] == 1) {
					window.location.href	 =	"{{ URL('/') }}";
				}
				else{
					$.each(data['errors'],function(index,html){
						$("input[name = "+index+"]").next().addClass('error');
						$("input[name = "+index+"]").next().html(html);
					});
				}
				$('#loader_img').hide();
			}
		});
	}
	
	$('#user_profile_form').each(function(){
		$(this).find('input').keypress(function(e){
           if(e.which == 10 || e.which == 13){
				resetpassword();
				return false;
            }
        });
	});
	
	function removeAlert(){
		$('.error_box').hide();
	}
</script>
@stop
