@extends('admin.layouts.default')
@section('content')
<!-- datetime picker js and css start here-->
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::script('js/jquery-ui.min.js') }}
<!-- date time picker js and css and here-->
{{ HTML::style('css/admin/style.css') }}
<script>
jQuery(document).ready(function(){
$(".organiser_name").chosen();
$(".event_type").chosen();
$(".event_category").chosen();
});
</script>
<section class="content-header">
	<h1>
	  {{ trans("Event Management") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{URL::to('admin/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">{{ trans("Event Management ") }}</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		{{ Form::open(['method' => 'get','role' => 'form','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
			<div class="col-md-3 col-sm-3">
				<div class="form-group ">  
					{{Form::select('user_id',array(''=>'Select Event Oraganiser ')+$event_organiser_list,((isset($searchVariable['user_id'])) ? $searchVariable['user_id'] : ''),['class' => 'chosen_select organiser_name form-control']) }}
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<div class="form-group ">  
					{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'form-control','placeholder'=>'Event Name']) }}
				</div>
			</div>
	</div>
	<div class="row">
		<div class="col-md-3 col-sm-3">
			<div class="form-group ">  
				{{ Form::text('location',((isset($searchVariable['location'])) ? $searchVariable['location'] : ''), ['class' => 'form-control','placeholder'=>'Event Location']) }}
			</div>
		</div>
		<div class="col-md-3 col-sm-3">
			<div class="form-group ">  
				{{ Form::text('date_from',((isset($date_from)) ? $date_from : ''), ['class' => 'form-control datepicker','placeholder'=>'Event Start Date']) }}
			</div>
		</div>
			<div class="col-md-3 col-sm-3">
				<div class="form-group ">  
					{{ Form::text('date_to',((isset($date_to)) ? $date_to : ''), ['class' => 'form-control datepicker1','placeholder'=>'Event End Date']) }}
				</div>
			</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-sm-6">
			<button class="btn btn-primary"><i class='fa fa-search '></i> {{ trans('messages.search.text') }}</button>
			<a href="{{URL::to('admin/event-manager/')}}"  class="btn btn-primary"><i class='fa fa-refresh '></i> {{ trans("messages.reset.text") }}</a>
			<div class="btn-group" >
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					Export CSV &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
						<li>
							<a  href="{{URL::to('cmeshinepanel/events/export')}}" class="btn-small ml5">All event reports</a>
						</li>
						<li>
							<a  href="{{URL::to('cmeshinepanel/events/export-filtered')}}" class="btn-small ml5">Filtered event reports</a>
						</li>
				</ul>
			</div>
		</div>
		{{ Form::close() }}
		<div class="col-md-6 col-sm-6 ">
			<div class="form-group pull-right"> 
				<a href="{{URL::to('cmeshinepanel/events/add-event')}}"  class="btn btn-success btn-small align">{{ trans("Add New Event") }}</a>
			</div>
		</div>
	</div>
	
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
						<th width="10%">
							{{
								link_to_route(
								"Event.index",
								trans("Name"),
								array(
									'sortBy' => 'name',
									'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
							}}
						</th>
						<th width="10%">
						{{
							link_to_route(
							"Event.index",
							trans("Location"),
							array(
								'sortBy' => 'location',
								'order' => ($sortBy == 'location' && $order == 'desc') ? 'asc' : 'desc',
								$query_string
							),
							array('class' => (($sortBy == 'location' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'location' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}</th>
						<th width="10%"> Event Type</th>
						<th width="15%">Event Category</th>
						<th width="13%">Organised By</th>
						<th width="15%">Description</th>
						<th width="12%">Status</th>
						<th width="15%">Action</th>
					</tr>
				</thead>
				@if(!$result->isEmpty())
					@foreach($result as $key => $record)
					<tr>
						<td>
							{{ $record->name }}
						</td>
						<td>
							{{ $record->location }}
						</td>
						<td>
							{{ $record->event_type }}
						</td>
						<td>
							{{ $record->category_name }}
						</td>
						<td>
							@if( $record->user_name == 'Admin')
								{{ Config::get('Site.title') }}
							@else
								{{ $record->user_name }}
							@endif
						</td>
						<td>
							{{  strip_tags(Str::limit($record->description, 250)) }}
						</td>
						<td>
							@if($record->is_active	== 1)
								<span class="label label-success" >{{ trans("messages.global.activated") }}</span>
							@else
								<span class="label label-warning" >{{ trans("messages.global.deactivated") }}</span>
							@endif
							@if($record->is_approved	== 1)
								<span class="label label-success" >{{ trans("messages.user_management.approved") }}</span>
							@else
								<span class="label label-warning" >{{ trans("messages.user_management.not_approved") }}</span>
							@endif
							@if($record->is_previous	== 1)
								<span class="label label-success" >{{ trans("messages.event.previous") }}</span>
							@endif
						</td>
						<td>
							@if($record->is_active == 1)
								<a   title="Click To Deactivate"  href="{{URL::to('cmeshinepanel/events/update-status/'.$record->id.'/0')}}"  class="is_active_user btn btn-success btn-small status_any_item"><span class="fa fa-check"></span> </a>
							@else
								<a title="Click To Activate" href="{{URL::to('cmeshinepanel/events/update-status/'.$record->id.'/1')}}"   class="is_active_user btn btn-warning btn-small status_any_item"><span class="fa fa-ban"></span></a>
							@endif
							@if($record->is_approved == 1)
								<a   title="Click To Not approved"  href="{{URL::to('cmeshinepanel/events/update-approved-status/'.$record->id.'/0')}}"  class="is_active_user btn btn-success btn-small status_any_item"><span class="fa fa-check"></span> </a>
							@else
								<a title="Click To Approved" href="{{URL::to('cmeshinepanel/events/update-approved-status/'.$record->id.'/1')}}"   class="is_active_user btn btn-warning btn-small status_any_item"><span class="fa fa-ban"></span></a>
							@endif
							<a title="{{ trans('messages.global.edit') }}" href="{{URL::to('cmeshinepanel/events/edit-event/'.$record->id)}}" class="btn btn-primary">
								<i class="fa fa-pencil"></i>
							</a>
							
							<a title="{{ trans('messages.global.delete') }}" href="{{ URL::to('cmeshinepanel/events/delete-event/'.$record->id) }}"  class="delete_any_item btn btn-danger">
								<i class="fa fa-trash-o"></i>
							</a>
							
							<a title="{{ trans('Booking') }}" href="{{ URL::to('cmeshinepanel/events/event_booking/'.$record->id) }}"  class="btn btn-success">
								<i class="fa fa-ticket"></i>
							</a>
							
						</td>
					</tr>
					@endforeach
					@else
						<tr>
							<td class="alignCenterClass" colspan="8" >{{ trans("messages.user_management.no_record_found_message") }}</td>
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
<script type="text/javascript">
	/**
	 * Datepicker for date range
	 */
	$( ".datepicker" ).datepicker({
		dateFormat 	: 'yy-mm-dd',
		changeMonth : true,
		changeYear 	: true,
		yearRange	: '-100y:c+nn',
		onSelect	: function( selectedDate ){ $(".datepicker1").datepicker("option","minDate",selectedDate); }
	});
	$( ".datepicker1" ).datepicker({
		dateFormat 	: 'yy-mm-dd',
		changeMonth : true,
		changeYear 	: true,
		yearRange	: '-100y:c+nn',
		onSelect	: function( selectedDate ){ $(".datepicker").datepicker("option","maxDate",selectedDate); }
	});
</script>
@stop
