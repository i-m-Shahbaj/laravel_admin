@extends('front.layouts.default')
@section('content')
<style>
.social_logif,.social_logig,.social_logit {
    padding: 10px 0;
    text-align: center;
}
.social_logif a,.social_logig a,.social_logit a {
    width: 100%;
    padding: 11px 20px;
}
.social_logif a {
    display: inline-block;
    width: 40px;
    padding: 11px 11px;
    font-size: 15px;
    font-weight: 500;
    border-radius: 2px;
}
.social_logig a {
    display: inline-block;
    width: 50px;
    padding: 11px 11px;
    font-size: 15px;
    font-weight: 500;
    border-radius: 2px;
}
.social_logit a {
    display: inline-block;
    width: 40px;
    padding: 11px 11px;
    font-size: 15px;
    font-weight: 500;
    border-radius: 2px;
}
.social_logif a i,.social_logig a i,.social_logit a i {
    float: left;
    font-size: 25px;
}
html { overflow:auto}
</style>
<div class="clearfix"></div>
<div class="cms-page-wrapper" ng-controller="loginData">
<!--page title END-->
  <div class="login-form">
    <div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="page-title-wrapper">
					<h1 class="page-title">Login</h1>
				</div>
			</div>
		</div>
		 
       <div class="login-form-inner"> 
        <div class="row">
        	<div class="col-md-6 col-sm-6 col-lg-6">
            	<div class="login-one">
				<div class="alert alert-danger alert-dismissable fade in error_box" style="display:none;">
					<a href="#" class="close" onclick="removeAlert();" aria-label="close">&times;</a>
					<span class="error_msg"></span>
				</div>
				<form ng-submit="liveFormSubmit()" name="formLogin" novalidate>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                   {{ Form::text("email",'', ['class' => 'form-control','id'=>'email','placeholder'=>trans("Username"),'required','ng-model'=>'formData.email']) }}
					<span class="help-inline" id="email_error"></span>
					<span style="color:red" ng-show="formLogin.email.$error.required" ng-if="submit" >Please enter username.</span>
                </div>
                <div class="form-group">
					{{ Form::password("password",['class' => 'form-control login_form_control','id'=>'password','placeholder'=>trans("Password"),'required','ng-model'=>'formData.password']) }}
					<span class="help-inline" id="password_error"></span>
					<span style="color:red" ng-show="formLogin.password.$error.required" ng-if="submit">Please enter Password.</span>
                </div>
                <div class="form-group">
                  <div class="checkbox">
                    <label>
                      <input value="" class="form-control" type="checkbox">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span> Remember me?</label>
                  </div>
                  <a href="{{ url('forgot-password') }}" class="forgot-password">Forgot Password?</a> 
               </div>
               
                <div class="form-group">                
                  <button class="btn-block" type="submit" ng-model="submit" ng-click="submit = true">Login</button>
				</div>
               
              {{ Form::close() }}
              </div>
              </div>
                <div class="clear"></div>
        	<div class="col-md-6 col-sm-6 col-lg-6">
                  <div class="social-btn"> 
                  <a href="{{URL('/login-with-social/facebook')}}" class="fb-btn-text"><i class="fa fa-facebook"></i>sign in with Facebook</a>
                  <p>or</p>
                  <a href="{{URL('/login-with-social/google')}}" class="google-btn-text"><i class="fa fa-google-plus"></i>sign in with Google+</a>
                  <p>or</p>
                  <a href="{{URL('/login-with-social/twitter')}}" class="twitter-btn-text"><i class="fa fa-twitter"></i>sign in with Twitter</a>
                  </div>
              </div>
              
        
        </div>
        
      
    </div>
  </div>
  </div>
  
@include('front.elements.footer')
</div>
<script>
	 
	mainApp.controller('loginData', function($scope, $http){
		$scope.formData = {};
		$scope.error = {};
		
		$scope.liveFormSubmit = function login() {
			$scope.formLogin.$setSubmitted();
			$('#loader_img').show();
			$('.help-inline').html('');
			$('.help-inline').removeClass('error');
			$http({
			  headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
			  method  : 'POST',
			  url     : '{{ URL("login") }}',
			  data    : $.param($scope.formData)
			 })
			.success(function(data) {
				if(!data.success){
					$scope.error = true;
				}else if(data['success']==1){
					window.location.href	 =	"{{ URL::to('/') }}";
				}else if(data['success'] == 2) {
					//document.getElementById("login_form").reset();
					$('.error_box').show();
					$(".error_msg").html(data['message']);
				}else {
					$.each(data['errors'],function(index,html){
						$("input[name = "+index+"]").next().addClass('error');
						$("input[name = "+index+"]").next().html(html);
					});
				}
				$('#loader_img').hide();
			});
		};
		
		
		$('#login_form').each(function() {
			$(this).find('input').keypress(function(e) {
			   if(e.which == 10 || e.which == 13) {
				  $scope.liveFormSubmit();
				}
			});
		});
	});
	
	function removeAlert(){
		$('.error_box').hide();
	}
</script>


@stop
