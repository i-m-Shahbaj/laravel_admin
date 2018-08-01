@extends('admin.layouts.default')
@section('content')
<section class="content-header">
	<h1>
	  {{ trans("Event Booking") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{URL::to('admin/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{URL::to('admin/event-manager')}}">Event Management</a></li>
		<li class="active">Event Booking</li>
	</ol>
</section>
<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
						<th width="20%">Event Name</th>
						<th width="20%">User Name</th>
						<th width="20%">Members</th>
						<th width="20%">Amount</th>
					</tr>
				</thead>
			
			@if(!empty($eventDeatil))
				@foreach($eventDeatil as $eventDeatils)
			<?php //echo '<pre>'; print_r($eventDeatils); die;?>
					<tr>
						<td>{{	$eventDeatils['event_name'] }}</td>
						<td>{{  $eventDeatils['user_name'] }}</td>
						<td>{{	$eventDeatils['member'] }}</td>
						<td>{{  $eventDeatils['currency'] }} {{  $eventDeatils['amount'] }}</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td class="alignCenterClass" colspan="4" >{{ trans("messages.user_management.no_record_found_message") }}</td>
				</tr>
			@endif
		</table>
	</div>
	</div>
</div>
@stop