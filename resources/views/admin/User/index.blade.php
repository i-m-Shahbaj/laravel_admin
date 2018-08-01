@extends('admin.layouts.default')
@section('content')

{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::script('js/admin/chosen/chosen.jquery.min.js') }}
{{ HTML::style('css/admin/chosen.min.css') }}
<style>
.chosen-container-single .chosen-single{
	padding: 5px 5px 5px 8px;
    height: 35px;
}
</style>
<script type="text/javascript"> 
	$(document).ready(function(){
		 $(".chosen-select").chosen({width: "100%"});
	}); 
</script>
<section class="content-header">
	<h1>
	  {{ trans("User Management") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">{{ trans("User Management") }}</li>
	</ol>
</section>
<section class="content"> 
	<div class="row">
		{{ Form::open(['role' => 'form','route' => $modelName.".index",'class' => 'mws-form',"method"=>"get"]) }}
		{{ Form::hidden('display') }}
			<div class="col-md-2 col-sm-2">
				<div class="form-group ">  
					{{ Form::select('is_active',array(''=>trans('Select Status'),0=>'Inactive',1=>'Active'),((isset($searchVariable['is_active'])) ? $searchVariable['is_active'] : ''), ['class' => 'form-control chosen-select']) }}
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<div class="form-group ">  
					<?php $userTypeList 	=	Config::get('user_type_list'); ?>
					{{ Form::select('user_role_id',$userTypeList,((isset($searchVariable['user_role_id'])) ? $searchVariable['user_role_id'] : ''), ['class' => 'form-control chosen-select','placeholder'=>'Select User Type']) }}
				</div>
			</div>
			<div class="col-md-2 col-sm-2">
				<div class="form-group ">  
					{{ Form::text('full_name',((isset($searchVariable['full_name'])) ? $searchVariable['full_name'] : ''), ['class' => 'form-control','placeholder'=>'Full Name']) }}
				</div>
			</div>
			<div class="col-md-2 col-sm-2">
				<div class="form-group ">  
					{{ Form::text('email',((isset($searchVariable['email'])) ? $searchVariable['email'] : ''), ['class' => 'form-control','placeholder'=>'Email']) }}
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<div class="form-group pull-right">  
					<button class="btn btn-primary"><i class='fa fa-search '></i> {{ trans('messages.search.text') }}</button>
					<a href="{{route($modelName.'.index')}}"  class="btn btn-primary"><i class='fa fa-refresh '></i> {{ trans("Clear Search") }}</a>
				</div>
			</div>
		{{ Form::close() }}
	
	</div> 
	{{ Form::open(['role' => 'form','route' => "$modelName.deactivateUsers",'class' => 'mws-form deactivateAllData',"method"=>"post"]) }}
	{{ Form::hidden('type',"",['class'=>"system_type"]) }}
	<div class="row">
		<div class="col-md-9 col-sm-9">
			<div class="form-group  pull-left" style="margin:0;"> 
				<a href="javascript:void(0);" class="btn btn-primary btn-small align deactivate_user"  style="margin:0;">{{ trans("Deactivate User") }} </a>
				<a href="javascript:void(0);" class="btn btn-primary btn-small align delete_user"  style="margin:0;">{{ trans("Delete User") }} </a>
			</div>
		</div>
		<div class="col-md-3 col-sm-3">
			<div class="form-group pull-right">  
				<a href="{{route($modelName.'.add')}}" class="btn btn-success btn-small align">{{ trans("messages.user_management.add_user") }} </a>
			</div>
		</div>
	</div>  
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>
						<input type="checkbox" class="selected_all">
						</th>
						<th width="15%">
							Profile Image
						</th>
						<th width="15%">
							<?php $usertypeimage = ($sortBy == 'user_role_id') ? ($sortBy == 'user_role_id' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								"$modelName.index",
								trans("User Type").$usertypeimage,
								array(
									'sortBy' => 'user_role_id',
									'order' => ($sortBy == 'user_role_id' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'user_role_id' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'user_role_id' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="15%">
							<?php $fullnameimage = ($sortBy == 'full_name') ? ($sortBy == 'full_name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.".index",
								trans("Full Name").$fullnameimage,
								array(
									'sortBy' => 'full_name',
									'order' => ($sortBy == 'full_name' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'full_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'full_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="10%">
							<?php $emailimage = ($sortBy == 'email') ? ($sortBy == 'email' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.".index",
								trans("Email").$emailimage,
								array(
									'sortBy' => 'email',
									'order' => ($sortBy == 'email' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'email' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'email' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
							
						</th>
						<th width="20%">
							<?php $statusimage = ($sortBy == 'is_active') ? ($sortBy == 'is_active' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.".index",
								trans("Status").$statusimage,
								array(
									'sortBy' => 'is_active',
									'order' => ($sortBy == 'is_active' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'is_active' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'is_active' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="25%">
							Action
						</th>
					</tr>
				</thead>
				@if(!$result->isEmpty())
					@foreach($result as $key => $record)
						<tr> 
							<td>
								@if($record->is_deleted	== 0 || $record->is_active	== 1)
									<input type="checkbox" class="singlecheckbox" name="userIds[]" value="{{ $record->id }}" />
								@endif
							</td> 
							<td>
								@if($record->image != '' && File::exists(USER_PROFILE_IMAGE_ROOT_PATH.$record->image))
									<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo USER_PROFILE_IMAGE_URL.$record->image; ?>">
										<div class="usermgmt_image">
											<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1&image='.USER_PROFILE_IMAGE_URL.'/'.$record->image ?>">
										</div>
									</a>
								@else
									<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg">
										<div class="usermgmt_image">
											<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1&image='.WEBSITE_IMG_URL ?>admin/no_image.jpg">
										</div>
									</a>
								@endif
							</td>
							
							<td>
								@if($record->user_role_id==DANCER_ROLE_ID)
									{{ trans('Dancer') }}
								@elseif($record->user_role_id==PARENT_ROLE_ID)
									{{ trans('Parent or Guardian') }}
								@elseif($record->user_role_id==STUDIO_ROLE_ID)
									{{ trans('Dance Studio Teacher / Choreographer') }}
								@elseif($record->user_role_id==FAN_ROLE_ID)
									{{ trans('Fan') }}
								@endif
							</td>
							<td>
								{{ $record->full_name }}
							</td>
							<td>
								<a href="mailto:{{ $record->email }}" class="redicon">
									{{ $record->email }}
								</a>
							</td>
							<td>
								@if($record->is_active	==1)
									<span class="label label-success" >{{ trans("messages.user_management.activated") }}</span>
								@else
									<span class="label label-warning" >{{ trans("messages.user_management.deactivated") }}</span>
								@endif
								@if($record->is_verified	==1)
									<span class="label label-success" >{{ trans("messages.user_management.verified") }}</span>
								@else
									<span class="label label-warning" >{{ trans("messages.user_management.not_verified") }}</span>
								@endif
							</td>
							<td>
								@if($record->is_active == 1)
									<a  title="Click To Deactivate" href="{{route($modelName.'.status',array($record->id,0))}}" class="btn btn-success btn-small status_any_item"><span class="fa fa-ban"></span>
									</a>
								@else
									<a title="Click To Activate" href="{{route($modelName.'.status',array($record->id,1))}}" class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
									</a> 
								@endif 
								
								<a href="{{route($modelName.'.view',$record->id)}}" title="{{ trans('messages.global.view') }}" class="btn btn-info">
									<i class="fa fa-eye"></i>
								</a>
									
								<a title="{{ trans('messages.global.edit') }}" href="{{route($modelName.'.edit',$record->id)}}" class="btn btn-primary">
									<i class="fa fa-pencil"></i>
								</a>
								<a title="{{ trans('messages.global.delete') }}" href="{{ route($modelName.'.delete',$record->id) }}"  class="delete_any_item btn btn-danger">
									<i class="fa fa-trash-o"></i>
								</a>
								<a title="{{ trans('Change Password') }}" href="{{ route($modelName.'.changePassword',$record->id) }}" class="btn btn-success">
									<i class="fa fa-key"></i>
								</a> 
								<a title="{{ trans('Re-send login credentials') }}" href="{{ route($modelName.'.sendCredential',$record->id) }}" class="btn btn-success status_any_item">
									<i class="fa fa-share"></i>
								</a> 
							</td>
						</tr>
					 @endforeach
					 @else
						<tr>
							<td class="alignCenterClass" colspan="7" >{{ trans("messages.user_management.no_record_found_message") }}</td>
						</tr>
					@endif 
			</table>
		</div>
		<div class="box-footer clearfix">	
			<div class="col-md-3 col-sm-4 "></div>
			<div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $result])</div>
		</div> 
	</div> 
	{{ Form::close() }}
</section> 
<script>

	$(document).ready( function() {
		$(".deactivate_user").click( function() {
			var total_check_checkbox		=	$('.singlecheckbox:checked').length;
			if(total_check_checkbox > 0){
				bootbox.confirm("Are you sure want to deactivate system ?",
				function(result){
					if(result){
						$(".system_type").val("deactivate_user");
						$(".deactivateAllData").submit();
					}
				});
			}else {
				bootbox.alert("Please select at latest one checkbox");
			}
		});
		$(".delete_user").click( function() {
			var total_check_checkbox		=	$('.singlecheckbox:checked').length;
			if(total_check_checkbox > 0){
				bootbox.confirm("Are you sure want to delete user ?",
				function(result){
					if(result){
						$(".system_type").val("delete_user");
						$(".deactivateAllData").submit();
					}
				});
			}else {
				bootbox.alert("Please select at latest one checkbox");
			}
		});
		
		$(".selected_all").click( function() {
			if($(this).prop("checked") == true){
				$(".singlecheckbox").prop('checked', true);
			}else if($(this).prop("checked") == false){
				$(".singlecheckbox").prop('checked', false);
			}
		});

		$(".singlecheckbox").click( function() {
			var total_checkbox				=	$(".singlecheckbox").length;
			var total_check_checkbox		=	$('.singlecheckbox:checked').length;
			if(total_check_checkbox == total_checkbox){
				$(".selected_all").prop('checked', true);
			}else if($(this).prop("checked") == false){
				$(".selected_all").prop('checked', false);
			}
		});
	});
</script>
@stop
