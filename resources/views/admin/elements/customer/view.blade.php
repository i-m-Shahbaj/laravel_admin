@extends('admin.layouts.default')

@section('content')
 
{{HTML::script('js/bootstrap.min.js')}}

<!-- View user detail div  -->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header" style="height:8%">
		<span>
			<i class="icon-table"></i> {{ trans("messages.user_management.user_detail") }} 
		</span>
			
			<a href="{{URL::to('users')}}" class="btn btn-success btn-small align" style="margin-left:5px"> {{ trans("messages.user_management.back") }}</a>
			<a href="{{URL::to('users/edit-user/'.$userDetails->id)}}" class="btn btn-primary btn-small align">{{ trans("messages.user_management.edit") }}</a>
	</div>

	<div class="mws-panel-body no-padding dataTables_wrapper"> 
		<table class="mws-table mws-datatable">
			<tbody>
				<tr>
					<th class="text-right" width="30%">Image</th>
					<td data-th='First Name'>
					
							@if(isset($userDetails->image) && USER_PROFILE_IMAGE_ROOT_PATH.$userDetails->image)
								{{ HTML::image( USER_PROFILE_IMAGE_URL.$userDetails->image, $userDetails->image , array( 'width' => 70, 'height' => 70 )) }}
							@endif
					</td>
				</tr>
				<tr>
					<th class="text-right" width="30%">First Name</th>
					<td data-th='First Name'>{{ isset($userDetails->first_name) ? $userDetails->first_name:'' }}</td>
				</tr>
				<tr>
					<th class="text-right" width="30%">Middle Name</th>
					<td data-th='Middle Name'>{{ isset($userDetails->middle_name) ? $userDetails->middle_name :''  }}</td>
				</tr>
				<tr>
					<th class="text-right" width="30%">Last Name</th>
					<td data-th='Last Name'>{{ isset($userDetails->last_name) ? $userDetails->last_name :'' }}</td>
				</tr>
				<tr>
					<th class="text-right" width="30%">Username</th>
					<td data-th='Username'>{{ $userDetails->username }}</td>
				</tr>
				<tr>
					<th class="text-right" width="30%">Email</th>
					<td data-th='Email'><a href="mailTo:{{ $userDetails->email }}">{{ $userDetails->email }}</a></td>
				</tr>
				
			
				<tr>
					<th class="text-right" width="30%">Phone</th>
					<td data-th='Phone'>{{ isset($userDetails->phone) ? $userDetails->phone :''  }}</td>
				</tr>
				
				<tr>
					<th class="text-right" width="30%">Address</th>
					<td data-th='Address'>{{ $userDetails->address }}</td>
				</tr>
				
				
				<tr>
					<th class="text-right" width="30%">Gender</th>
					<td data-th='Gender'>{{ ucfirst($userDetails->gender) }}</td>
				</tr>
				
				<tr>
					<th width="20%" class="text-right">Country</th>
					<td data-th='Country'>{{ isset($countryName) ? $countryName :''  }}</td>
				</tr>
				
				<tr>
					<th width="30%" class="text-right">Region</th>
					<td data-th='Region'>{{ isset($regionName) ? $regionName : ''  }}</td>
				</tr>
				
				<tr>
					<th width="30%" class="text-right">City</th>
					<td data-th='City'>{{ isset($regionName) ? $cityName :'' }}</td>
				</tr>
				
				<tr>
					<th class="text-right" width="30%">Created On</th>
					<td data-th='Created On'>{{ date(Config::get("Reading.date_format") , strtotime($userDetails->created_at)) }}</td>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>
<!--View user detail div end here -->




</div>

@stop
