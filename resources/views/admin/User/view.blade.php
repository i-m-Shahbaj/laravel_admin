@extends('admin.layouts.default')
@section('content')
<style>
.view {
    background-color: #3c3f44;
    color: white;
}
.table.table-striped th {
    font-size: 14px;
}
.table.table-striped td {
    font-size: 14px;
}
</style>
<section class="content-header">
	<h1>
		 {{ trans("messages.user_management.user_detail") }} 
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">{{ trans("User Management") }}</a></li>
		<li class="active"> {{ trans("messages.user_management.user_detail") }}  </li>
	</ol>
</section>
<section class="content"> 
	<div class="row"> 
		<div class="col-md-12 col-sm-12">
			<div class="row pad">
				<div class="col-md-12 col-sm-12" >	
					<span style="float:left;">	
					@if($userDetails->is_active == 1)
						<a  title="Click To Deactivate" href="{{route($modelName.'.status',array($userDetails->id,0))}}" class="btn btn-success btn-small status_any_item"><span class="fa fa-ban"></span>
						</a>
					@else
						<a title="Click To Activate" href="{{route($modelName.'.status',array($userDetails->id,1))}}" class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
						</a> 
					@endif 
					<a title="{{ trans('messages.global.edit') }}" href="{{route($modelName.'.edit',array($userDetails->id))}}" class="btn btn-primary">
						<i class="fa fa-pencil"></i>
					</a>
					
					<a title="{{ trans('messages.global.delete') }}" href="{{route($modelName.'.delete',array($userDetails->id)) }}"  class="delete_any_item btn btn-danger">
						<i class="fa fa-trash-o"></i>
					</a>
				
<!--
					<a title="{{ trans('messages.user_management.send_login_credentials') }}" href="{{ URL::to('admin/users/send-credential/'.$userDetails->id) }}" class="btn btn-success">
						<i class="fa fa-share"></i>
					</a>
					
-->
					<a title="{{ trans('Change Password') }}" href="{{ route('dashboard.changePassword',array($userDetails->id)) }}" class="btn btn-success">
						<i class="fa fa-key"></i>
					</a>
					</span>
					<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
				</div>
			</div>
		</div>
		<div class="col-md-12 col-sm-6">
			<div class="row pad">
				<div class="col-md-12 col-sm-12" >	
					<div class="">
						<div id="info1"></div>
							<div class="box-body" style="display: block;">  
								<div>
									<table class="table table-striped table-bordered" style="margin-top:10px;">
										<thead>
											<tr class="view">
												<th  width="30%" height="50%" class="" colspan="3" style="font-size:20px;">
													<?php 
													if($userDetails->user_role_id==DANCER_ROLE_ID){
														$userType	=	'Dancer';
													}elseif($userDetails->user_role_id==PARENT_ROLE_ID){
														$userType	=	'Parent or Guardian';
													}elseif($userDetails->user_role_id==STUDIO_ROLE_ID){
														$userType	=	'Dance Studio Teacher / Choreographer';
													}elseif($userDetails->user_role_id==FAN_ROLE_ID){
														$userType	=	'Fan';
													}
													?>
												<span style="float:left;">{{ strtoupper($userType) }} DETAILS</span>
												
												</th>
											</tr>
										</thead>
										<tbody>
										<tr>
											<th  width="30%"  class="text-right txtFntSze" >Profile Image</th>
											<td colspan="2" data-th='Profile Image'><?php $image		=	isset($userDetails->image) ? $userDetails->image : '';?>
												@if($image != '' && File::exists(USER_PROFILE_IMAGE_ROOT_PATH.$image))
													<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo USER_PROFILE_IMAGE_URL.$userDetails->image; ?>">
														<div class="usermgmt_image">
															<img  src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1&image='.USER_PROFILE_IMAGE_URL.'/'.$userDetails->image ?>">
														</div>
													</a>	
													@else
														<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1&image='.WEBSITE_IMG_URL ?>admin/no_image.jpg">
												@endif 
											</td>
										</tr>	
										<tr>
											<th  width="30%" class="text-right txtFntSze" >{{ ($userDetails->user_role_id==STUDIO_ROLE_ID) ? trans('Studio / Trainer Name') : trans('Name') }}</th>
											<td colspan="2" data-th='Name'>{{ isset($userDetails->full_name) ? ucfirst($userDetails->full_name):ucfirst($userDetails->first_name.' '.$userDetails->last_name) }}</td>
										</tr>
										
										@if($userDetails->user_role_id==DANCER_ROLE_ID || $userDetails->user_role_id==PARENT_ROLE_ID || $userDetails->user_role_id==FAN_ROLE_ID)
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Username</th>
											<td colspan="2" data-th='Username'>{{ isset($userDetails->username) ? $userDetails->username :''  }}</td>
										</tr>
										@endif
										
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Email</th>
											<td colspan="2" data-th='Email'><a href="mailTo:{{ $userDetails->email }}">{{ $userDetails->email }}</a></td>
										</tr>
										@if($userDetails->user_role_id==DANCER_ROLE_ID || $userDetails->user_role_id==PARENT_ROLE_ID || $userDetails->user_role_id==FAN_ROLE_ID)
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Gender</th>
											<td colspan="2" data-th='Gender'>{{ isset($userDetails->gender) ? ucfirst($userDetails->gender) :''  }}</td>
										</tr>
										
										@endif
										
										@if($userDetails->user_role_id==PARENT_ROLE_ID)
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Relationship</th>
											<td colspan="2" data-th='Email'>{{ isset($userDetails->relationship) ? $userDetails->relationship : '' }}</td>
										</tr>
										@endif
										
										@if($userDetails->user_role_id==STUDIO_ROLE_ID)
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Street Address</th>
											<td colspan="2" data-th='Address'>{{ isset($userDetails->address) ? $userDetails->address :''  }}</td>
										</tr>
										@endif
										
										@if($userDetails->user_role_id==DANCER_ROLE_ID || $userDetails->user_role_id==PARENT_ROLE_ID || $userDetails->user_role_id==FAN_ROLE_ID)
										
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Date of Birth</th>
											<td colspan="2" data-th='Date of Birth'>{{ isset($userDetails->date) ? $userDetails->date :''  }}</td>
										</tr>
											
										@endif
										
										
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Country</th>
											<td colspan="2" data-th='Country'>{{ isset($countryName) ? $countryName :''  }}</td>
										</tr>
										
										<tr>
											<th  width="30%" class="text-right txtFntSze" >State</th>
											<td colspan="2" data-th='State'>{{ isset($stateName) ? $stateName :''  }}</td>
										</tr>
										
										<tr>
											<th  width="30%" class="text-right txtFntSze" >City</th>
											<td colspan="2" data-th='City'>{{ isset($cityName) ? $cityName :''  }}</td>
										</tr>
										
										@if($userDetails->user_role_id==STUDIO_ROLE_ID || $userDetails->user_role_id==FAN_ROLE_ID)
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Cell Phone</th>
											<td colspan="2" data-th='Cell Phone'>{{ isset($userDetails->phone_number) ? $userDetails->phone_number :''  }}</td>
										</tr>
										@endif
										@if($userDetails->user_role_id==STUDIO_ROLE_ID)
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Website Address</th>
											<td colspan="2" data-th='Website Address'>{{ isset($userDetails->website_address) ? $userDetails->website_address :''  }}</td>
										</tr>
										
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Zip Code</th>
											<td colspan="2" data-th='Zip Code'>{{ isset($userDetails->zip_code) ? $userDetails->zip_code :''  }}</td>
										</tr>
										
										<tr>
											<th  width="30%" class="text-right txtFntSze" >How Many Dancers do you train Monthly?</th>
											<td colspan="2" data-th='How Many Dancers do you train Monthly?'>{{ isset($userDetails->how_many_dancers_train_monthly) ? $userDetails->how_many_dancers_train_monthly :''  }}</td>
										</tr>
										@endif
										
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Verified</th>
											
											<td colspan="2" data-th='Verified'>
												<?php if(isset($userDetails->is_verified) && $userDetails->is_verified == '1'){
													echo '<span class="label label-success">Yes</span>';
												}else{
													echo '<span class="label label-danger">No</span>';
												}?>
											</td>
										</tr>
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Status</th>
											
											<td colspan="2" data-th='Status'>
												<?php if(isset($userDetails->is_active) && $userDetails->is_active == '1'){
													echo '<span class="label label-success">Activated</span>';
												}else{
													echo '<span class="label label-danger">Deactivated</span>';
												}?>
											
											</td>
										</tr>
										<tr>
											<th  width="30%" class="text-right txtFntSze" >Created On</th>
											
											<td colspan="2" data-th='Created On'>{{ date(Config::get("Reading.date_format") , strtotime($userDetails->created_at)) }}</td>
										</tr>
										
										@if($userDetails->user_role_id==DANCER_ROLE_ID)
											<tr>
												<th  width="30%" class="text-right txtFntSze" >Do you dance on a school or a recreational league dance team? </th>
												@if($userDetails->attend_dance_team==1)
												<td data-th='Date of Birth'>{{{ trans('Yes') }}}</td>
												<td>
													<table class="table table-striped table-bordered" style="margin-top:10px;">
														<tbody>
															<tr>
																<th  width="30%" class="text-right txtFntSze" >School/league Name</th>
																<td data-th='Date of Birth'>{{ isset($userDetails->league_name) ? $userDetails->league_name :''  }}</td>
															</tr>
															<tr>
																<th  width="30%" class="text-right txtFntSze" >Location</th>
																<td data-th='Country'>
																	{{ isset($leagueCityName) ? $leagueCityName.', ' :''  }}
																	{{ isset($leagueStateName) ? $leagueStateName.', ' :''  }}
																	{{ isset($leagueCountryName) ? $leagueCountryName :''  }}
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												@else
													<td colspan="2" data-th='Date of Birth'>{{{ trans('No') }}}</td>
												@endif
											</tr>
											
											<tr>
												<th  width="30%" class="text-right txtFntSze" >Do you attend a dance studio? </th>
												
												@if($userDetails->attend_dance_studio==1)
													<td data-th='Date of Birth'>{{{ trans('Yes') }}}</td>
													<td>
														<table class="table table-striped table-bordered" style="margin-top:10px;">
															<tbody>
																<tr>
																	<th  width="30%" class="text-right txtFntSze" >Studio Name</th>
																	<td data-th='Date of Birth'>{{ isset($userDetails->studio_name) ? $userDetails->studio_name :''  }}</td>
																</tr>
																<tr>
																	<th  width="30%" class="text-right txtFntSze" >Location</th>
																	<td data-th='Country'>
																		{{ isset($studioCityName) ? $studioCityName.', ' :''  }}
																		{{ isset($studioStateName) ? $studioStateName.', ' :''  }}
																		{{ isset($studioCountryName) ? $studioCountryName :''  }}
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												@else
													<td colspan="2" data-th='Date of Birth'>{{{ trans('No') }}}</td>
												@endif
												
											</tr>
											
											@endif
											
										</tbody>
									</table>
								</div>
							</div>
							@if($userDetails->user_role_id==PARENT_ROLE_ID)
							<div class="box-body" style="display: block;">  
								<div>
									<table class="table table-striped table-bordered" style="margin-top:10px;">
										<thead>
											<tr class="view">
												<th  width="30%" height="50%" class="" colspan="6" style="font-size:20px;">
												<span style="float:left;">DANCER DETAILS</span>
												</th>
											</tr>
										</thead>
										<thead>
											<tr>
												<th width="15%">
													Name
												</th>
												<th width="15%">
													Email
												</th>
												<th width="20%">
													Location
												</th>
												<th width="15%">
													Gender
												</th>
												<th width="15%">
													Date of Birth
												</th>
												<th>
													Send Notification to dancer
												</th>
											</tr>
										</thead>
										<?php //echo "<pre>";print_r($dancerDetails);die; ?>
										<tbody>
											@foreach($dancerDetails as $dancerDetail)
											<tr>
												<td data-th='Name'>{{ isset($dancerDetail->first_name) ? ucfirst($dancerDetail->first_name.' '.$dancerDetail->last_name) : '' }}</td>
											
												<td data-th='Email'><a href="mailTo:{{ $dancerDetail->email }}">{{ $dancerDetail->email }}</a></td>
											
												<td data-th='Country'>
													{{ isset($dancerDetail->city_name) ? $dancerDetail->city_name.', ' :''  }}
													{{ isset($dancerDetail->state_name) ? $dancerDetail->state_name.', ' :''  }}
													{{ isset($dancerDetail->country_name) ? $dancerDetail->country_name :''  }}
												</td>
												
												<td data-th='Gender'>{{ isset($dancerDetail->gender) ? ucfirst($dancerDetail->gender) :''  }}</td>
												
												<td data-th='Date of Birth'>{{ isset($dancerDetail->date) ? $dancerDetail->date :''  }}</td>
												
												<td data-th='Send Notification'>{{ isset($dancerDetail->send_notification) ? $dancerDetail->send_notification :''  }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
							@endif				
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<style>
	<style>
	.txtFotnSize{
		font-size:14px !important;
	}
	.bgcss{
		background-color:#3c3f44; 
		color:white;
	}
	.error{
		color:red;
	}
	
</style>
</style>
@stop
