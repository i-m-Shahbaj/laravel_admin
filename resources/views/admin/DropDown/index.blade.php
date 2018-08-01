
@extends('admin.layouts.default')

@section('content')

{{HTML::script('js/admin/vendors/match-height/jquery.equalheights.js') }}

<script type="text/javascript"> 
	$(function(){
		/**
		 * For match height of div 
		 */
		$('.items-inner').equalHeights();
		/**
		 * For tooltip
		 */
		var tooltips = $( "[title]" ).tooltip({
			position: {
				my: "right bottom+50",
				at: "right+5 top-5"
			}
		});
	});	
</script>
<section class="content-header">
	<h1>{{ ucwords(str_plural(str_replace("-"," ",$type))) }}</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">{{ ucwords(str_plural(str_replace("-"," ",$type))) }}</li>
	</ol>
</section>
	
<section class="content"> 
	<div class="row">
		{{ Form::open(['role' => 'form','route' => ['DropDown.listDropDown',$type],'class' => 'mws-form',"method"=>"get"]) }}
		{{ Form::hidden('display') }}
			<div class="col-md-3 col-sm-3">
				<div class="form-group ">  
					{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'form-control','placeholder'=>studly_case($type)]) }}
				</div>
			</div>
			<div class="col-md-4 col-sm-4">
				<button class="btn btn-primary"><i class='fa fa-search '></i> Search</button>
				<a href="{{route('DropDown.listDropDown',$type)}}"  class="btn btn-primary"><i class='fa fa-refresh '></i> Reset</a>
			</div>
		{{ Form::close() }}
		<div class="col-md-5 col-sm-5 ">
			<div class="form-group ">  
				<a href="{{route('DropDown.add',$type)}}" class="btn btn-success btn-small align pull-right">{{ 'Add New '.studly_case($type) }} </a>
			</div>
		</div>
	</div> 
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
			<thead>
				<tr>
					<th width="40%">
						<?php $nameimage = ($sortBy == 'name') ? ($sortBy == 'name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.'.listDropDown',
								'Name'.$nameimage,
								array(
									$type,
									'sortBy' => 'name',
									'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
							   array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th width="30%">
						<?php $createdatimage = ($sortBy == 'created_at') ? ($sortBy == 'created_at' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.'.listDropDown',
								'Created '.$createdatimage,
								array(
									$type,
									'sortBy' => 'created_at',
									'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
							   array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th>
						<?php $isactiveimage = ($sortBy == 'is_active') ? ($sortBy == 'is_active' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.'.listDropDown',
								'Status'.$isactiveimage,
								array(
									$type,
									'sortBy' => 'is_active',
									'order' => ($sortBy == 'is_active' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
							   array('class' => (($sortBy == 'is_active' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'is_active' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$result->isEmpty())
				@foreach($result as $record)
				<tr class="items-inner">
					<td data-th='Name'>{{ $record->name }}</td>
					<td data-th='Created At'>{{ date(Config::get("Reading.date_format") , strtotime($record->created_at)) }}</td>
					<td>
						@if($record->is_active	==1)
							<span class="label label-success" >{{ trans("messages.user_management.activated") }}</span>
						@else
							<span class="label label-warning" >{{ trans("messages.user_management.deactivated") }}</span>
						@endif
					</td>
					<td data-th='Action'>
						@if($record->is_active == 1)
							<a  title="Click To Deactivate" href="{{route('DropDown.status',[$record->id,0,$type])}}" class="btn btn-success btn-small status_any_item"><span class="fa fa-ban"></span>
							</a>
						@else
							<a title="Click To Activate" href="{{route('DropDown.status',[$record->id,'1',$type])}}" class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
							</a> 
						@endif 
						<a title="Edit" href="{{route('DropDown.edit',[$record->id,$type])}}" class="btn btn-primary">
							<i class="fa fa-pencil"></i>
						</a>
						<a title="Delete" href="{{route('DropDown.delete',[$record->id,$type])}}"  class="delete_any_item btn btn-danger">
							<i class="fa fa-trash-o"></i>
						</a>
					</td>
				</tr>
				 @endforeach
					 @else
						<tr>
							<td class="alignCenterClass" colspan="4" >{{ trans("messages.user_management.no_record_found_message") }}</td>
						</tr>
					@endif 
			</table>
		</div>
		<div class="box-footer clearfix">	
			<div class="col-md-3 col-sm-4 "></div>
			<div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $result])</div>
		</div>
	</div> 
</section> 
@stop
