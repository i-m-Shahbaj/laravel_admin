@extends('admin.layouts.default')

@section('content')

<?php
	$userInfo	=	Auth::user();
	//echo "<pre>";
	//print_r($userInfo);die;
	$full_name	=	(isset($userInfo->full_name)) ? $userInfo->full_name : '';
	$username	=	(isset($userInfo->username)) ? $userInfo->username : '';
	$email		=	(isset($userInfo->email)) ? $userInfo->email : '';
	$first_name	=	(isset($userInfo->first_name)) ? $userInfo->first_name : '';
	$last_name	=	(isset($userInfo->last_name)) ? $userInfo->last_name : '';
	$image		=	(isset($userInfo->image)) ? $userInfo->image : '';
?>

<section class="content-header">
	<h1 class="text-center">My Account
		<span class="pull-right">
			<a href="{{route($modelName.'.changePassword')}}" class="btn btn-danger"><i class=\"icon-refresh\"></i>Change Password</a>
		</span>
	</h1>
	<div class="clearfix"></div>
</section>
<section class="content">
	{{ Form::open(['role' => 'form','route' => $modelName.'.myaccountUpdate','class' => 'mws-form','files'=>'true']) }}
	<div class="row">
		<div class="col-md-6">
			<div class="form-group <?php echo (!empty($errors->first('first_name'))?"has-error":''); ?>">
				{!! HTML::decode( Form::label('first_name', trans("First Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text('first_name', $first_name, ['class' => 'form-control']) }}  
					<div class="error-message help-inline">
						<?php echo $errors->first('first_name'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo (!empty($errors->first('last_name'))?"has-error":''); ?>">
				{!! HTML::decode( Form::label('last_name', trans("Last Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text('last_name', $last_name, ['class' => 'form-control']) }}  
					<div class="error-message help-inline">
						<?php echo $errors->first('last_name'); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group <?php echo (!empty($errors->first('email'))?"has-error":''); ?>">
				{!! HTML::decode( Form::label('email', trans("Email").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::text('email', $email, ['class' => 'form-control']) }}  
					<div class="error-message help-inline">
						<?php echo $errors->first('email'); ?>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo (!empty($errors->first('image'))?"has-error":''); ?>">
				{!! HTML::decode( Form::label('image', trans("messages.user_management.profile_image").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label floatleft'])) !!}
				<span class='tooltipHelp' title="" data-html="true" data-toggle="tooltip"  data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;" data-placement="right">
					<i class="fa fa-question-circle fa-2x"> </i>
				</span>
				<div class="mws-form-item">			
					{{ Form::file("image",['id'=>"image"]) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('image'); ?>
					</div>
				</div>
			</div>
			@if($image != '' && File::exists(USER_PROFILE_IMAGE_ROOT_PATH.$image))
				<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo USER_PROFILE_IMAGE_URL.$image; ?>">
					<div class="usermgmt_image">
						<img id="profile_image_src"  src="<?php echo WEBSITE_URL.'image.php?width=150px&height=150px&image='.USER_PROFILE_IMAGE_URL.'/'.$image ?>">
					</div>
				</a>
			@else
				<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo USER_PROFILE_IMAGE_URL.$image; ?>">
					<div class="usermgmt_image">
						<img id="profile_image_src"  src="<?php echo WEBSITE_URL.'image.php?width=150px&height=150px&image='.WEBSITE_IMG_URL.'/admin/no_image.jpg' ?>">
					</div>
				</a>
			@endif
		</div>
		
	</div> 
	<div class="mws-button-row">
		<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
		<a href="{{route($modelName.'.myaccount')}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset')  }}</a>
	</div>	  	
	{{ Form::close() }}
</section>
<style>
#MyaccountAddress {
	resize: vertical; /* user can resize vertically, but width is fixed */
}
.error-message{
	color: #f56954 !important;
}
</style>


@stop
