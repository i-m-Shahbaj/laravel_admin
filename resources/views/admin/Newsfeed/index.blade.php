@extends('admin.layouts.default')
@section('content')
<!-- datetime picker js and css start here-->
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::script('js/jquery-ui.min.js') }}
<!-- date time picker js and css and here-->
{{ HTML::style('css/admin/style.css') }}
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::script('js/admin/chosen/chosen.jquery.min.js') }}
{{ HTML::style('css/admin/chosen.min.css') }}
<style>
.chosen-container-single .chosen-single{
	padding: 5px 5px 5px 8px;
    height: 35px;
}
</style>
<script>
jQuery(document).ready(function(){
$(".organiser_name").chosen();
$(".event_type").chosen();
$(".event_category").chosen();
});
</script>
<script type="text/javascript"> 
	$(document).ready(function(){
		 $(".chosen-select").chosen({width: "100%"});
	}); 
</script>
<section class="content-header">
	<h1>
	  {{ trans("Press Release") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{URL::to('admin/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">{{ trans("Press Release") }}</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		{{ Form::open(['method' => 'get','role' => 'form','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
			<div class="col-md-3 col-sm-3">
				<div class="form-group ">  
					{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'form-control','placeholder'=>'Name']) }}
				</div>
			</div>
			<div class="col-md-2 col-sm-2">
				<div class="form-group ">  
					{{ Form::select('is_active',array(''=>trans('Select Status'),0=>'Inactive',1=>'Active'),((isset($searchVariable['is_active'])) ? $searchVariable['is_active'] : ''), ['class' => 'form-control chosen-select']) }}
				</div>
			</div>
		<div class="col-md-3 col-sm-3">
			<button class="btn btn-primary"><i class='fa fa-search '></i> {{ trans('messages.search.text') }}</button>
			<a href="{{URL::to('cmeshinepanel/newsfeed')}}"  class="btn btn-primary"><i class='fa fa-refresh '></i> {{ trans("messages.reset.text") }}</a>
		</div>
		{{ Form::close() }}
		<div class="col-md-3 col-sm-3 ">
			<div class="form-group pull-right"> 
				<a href="{{URL::to('cmeshinepanel/newsfeed/add-newsfeed')}}"  class="btn btn-success btn-small align">{{ trans("Add New Press Release") }}</a>
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
								"Newsfeed.index",
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
						<th width="15%">Description</th>
						<th width="12%">
							{{
								link_to_route(
								"Newsfeed.index",
								trans("Status"),
								array(
									'sortBy' => 'is_active',
									'order' => ($sortBy == 'is_active' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'is_active' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'is_active' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
							}}
						</th>
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
							{{  strip_tags(Str::limit($record->description, 50)) }}
						</td>
						<td>
							@if($record->is_active	== 1)
								<span class="label label-success" >{{ trans("messages.global.activated") }}</span>
							@else
								<span class="label label-warning" >{{ trans("messages.global.deactivated") }}</span>
							@endif
						</td>
						<td>
							@if($record->is_active == 1)
								<a   title="Click To Deactivate"  href="{{URL::to('cmeshinepanel/newsfeed/update-status/'.$record->id.'/0')}}"  class="is_active_user btn btn-success btn-small status_any_item"><span class="fa fa-check"></span> </a>
							@else
								<a title="Click To Activate" href="{{URL::to('cmeshinepanel/newsfeed/update-status/'.$record->id.'/1')}}"   class="is_active_user btn btn-warning btn-small status_any_item"><span class="fa fa-ban"></span></a>
							@endif
							<a title="{{ trans('messages.global.edit') }}" href="{{URL::to('cmeshinepanel/newsfeed/edit-newsfeed/'.$record->id)}}" class="btn btn-primary">
								<i class="fa fa-pencil"></i>
							</a>
							
							<a title="{{ trans('messages.global.delete') }}" href="{{ URL::to('cmeshinepanel/newsfeed/delete-newsfeed/'.$record->id) }}"  class="delete_any_item btn btn-danger">
								<i class="fa fa-trash-o"></i>
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
