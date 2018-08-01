@extends('front.layouts.default')
@section('content')
<div class="clearfix"></div>
<div class="cms-page-wrapper clearfix" ng-controller="forgotData">
	<div class="login-form forgot-pswd">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
                	<aside>
					<div class="page-title-wrapper">
						<h1 class="page-title">Forgot Password</h1>
					</div>
				
					@if(Session::has('login_message'))
						 <div class="alert alert-danger alert-dismissable fade in error_box">
							<a href="#" class="close" onclick="removeAlert();" aria-label="close">&times;</a>
							{{ Session::get('login_message')}}
						</div>
					@endif
					<div class="alert alert-danger alert-dismissable fade in error_box" style="display:none;">
						<a href="#" class="close" onclick="removeAlert();" aria-label="close">&times;</a>
						<span class="error_msg"></span>
					</div>
					<div class="login_form_field login-form-inner">
						<form ng-submit="forgotFormSubmit()" name="formForgot" id="forget_password_form" novalidate>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-group">
							{{ Form::text("forgot_email",'', ['class' => 'form-control login_form_control','placeholder'=>trans("Email Address"),'required','ng-model'=>'formData.forgot_email','ng-focus'=>'formForgot.$valid = false']) }}
								<span class="help-inline" id="forgot_email_error"></span>
							<span style="color:red" ng-show="formForgot.forgot_email.$error.required" ng-if="submit">Please enter email address.</span>
						
							</div>
							<div class="login_form_submit">
								<input type="submit" class="submit-button btn" ng-model="submit" ng-click="submit = true" value="Submit">
							</div>
							<div class="form-group">				   
							   <a href="{{ URL('login') }}" class="Back_login">Back to login</a>
							</div>
						</form>
						</div>
					</aside>
				</div>
			</div>
		</div>
	</div>
	
@include('front.elements.footer')
</div>

<script>
	 
	mainApp.controller('forgotData', function($scope, $http){
		$scope.formData = {};
		$scope.error ={};
		//3. attach originalStudent model object
		$scope.originalForm = {
			forgot_email: '',
		};
		
		$scope.forgotFormSubmit = function() {
			$scope.error = false;
			$('#loader_img').show();
			$('.help-inline').html('');
			$('.help-inline').removeClass('error');
			$http({
				headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-CSRF-TOKEN': $('meta[name="token"]').attr('content') },
				method  : 'POST',
				url     : '{{ URL("forgot-password") }}',
				data    : $.param($scope.formData)
			})
			.success(function(data) {
				if(!data.success){
					
				}else if(data['success']==1){
					window.location.href	 =	"{{ URL::to('/') }}";
				}else if(data['success'] == 2) {
					document.getElementById("forget_password_form").reset();
					$('.error_box').show();
					$(".error_msg").html(data['message']);
				}else if(data['success'] == 3) {
					$.each(data['errors'],function(index,html){
						$("#"+index+"_error").addClass('error');
						$("#"+index+"_error").html(html);
					});
				}
				$('#loader_img').hide();
			});
		};
		
		$('#forget_password_form').each(function() {
			$(this).find('input').keypress(function(e) {
			   if(e.which == 10 || e.which == 13) {
				  $scope.forgotFormSubmit();
				}
			});
		});
	});
	
	
	
	
	function removeAlert(){
		$('.error_box').hide();
	}
</script>
@stop
