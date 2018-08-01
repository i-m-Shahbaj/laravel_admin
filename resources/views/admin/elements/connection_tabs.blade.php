<?php 
	$controller				=	Request::segment(1);
	$action					=	Request::segment(2);
	$connection_id			=	Request::segment(3);
	
?>
		
<div class="col-md-12 col-sm-8">
	<ul class="nav nav-tabs">
		<li class="{{ ($action == 'view-1hourly-graph' || $action == 'view-4hourly-graph' || $action == 'view-30hourly-graph' || $action == 'view-1week-graph' || $action == 'view-1month-graph') ? 'active' : '' }}"><a href="{{URL::to('bookings/view-30hourly-graph/'.$connection_id)}}">PRIMARY GRAPHS</a></li>
		@if(!empty($service_details->management_ip_2))
			<li class="{{ ($action == 'view-1hourly-backup-graph' || $action == 'view-4hourly-backup-graph' || $action == 'view-30hourly-backup-graph' || $action == 'view-1week-backup-graph' || $action == 'view-1month-backup-graph') ? 'active' : '' }}"><a href="{{URL::to('bookings/view-30hourly-backup-graph/'.$connection_id)}}">BACKUP GRAPHS</a></li> 
		@endif
		<li class="{{ ($action == 'view-booking') ? 'active' : '' }}" ><a href="{{URL::to('bookings/view-booking/'.$connection_id)}}">DETAILS</a></li>
		<li class="{{ ($action == 'view-notes') ? 'active' : '' }}" ><a href="{{URL::to('bookings/view-notes/'.$connection_id)}}">PUBLIC NOTES</a></li>
		
		@if(Auth::user()->user_role_id == SUPER_ADMIN_ROLE_ID || Auth::user()->user_role_id == SUPER_ADMIN_STAFF_ROLE_ID ||  Auth::user()->user_role_id == RESELLER_ROLE_ID ||  Auth::user()->user_role_id == RESELLER_STAFF_ROLE_ID)
			<li class="{{ ($action == 'view-private-notes') ? 'active' : '' }}" ><a href="{{URL::to('bookings/view-private-notes/'.$connection_id)}}">PRIVATE NOTES</a></li>
		@endif
		<li class="{{ ($action == 'view-tickets' || $action == 'add-connection-ticket') ? 'active' : '' }}" ><a href="{{URL::to('bookings/view-tickets/'.$connection_id)}}">TICKETS</a></li>
		
		@if(Auth::user()->user_role_id == SUPER_ADMIN_ROLE_ID || Auth::user()->user_role_id == SUPER_ADMIN_STAFF_ROLE_ID ||  Auth::user()->user_role_id == RESELLER_ROLE_ID ||  Auth::user()->user_role_id == RESELLER_STAFF_ROLE_ID)
			<li class="{{ ($action == 'contract') ? 'active' : '' }}" ><a href="{{URL::to('bookings/contract/'.$connection_id)}}">CONTRACT</a></li>
		@endif
		
	</ul>
</div>