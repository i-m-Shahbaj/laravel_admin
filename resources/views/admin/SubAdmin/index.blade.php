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
	  {{ trans("Sub Admin Management") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">{{ trans("Sub Admin Management") }}</li>
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
			<div class="col-md-3 col-sm-3">
				<div class="form-group pull-right">  
					<a href="{{route($modelName.'.add')}}" class="btn btn-success btn-small align">{{ trans("messages.user_management.add_user") }} </a>
				</div>
			</div>
		{{ Form::close() }} 
	</div>  
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>   
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
								 
								<a title="{{ trans('messages.global.edit') }}" href="{{route($modelName.'.edit',$record->id)}}" class="btn btn-primary">
									<i class="fa fa-pencil"></i>
								</a>
								<a title="{{ trans('messages.global.delete') }}" href="{{ route($modelName.'.delete',$record->id) }}"  class="delete_any_item btn btn-danger">
									<i class="fa fa-trash-o"></i>
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
@stop
