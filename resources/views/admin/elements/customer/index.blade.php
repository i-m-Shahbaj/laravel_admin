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
		<h1>
			Customer Management
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{URL::to('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Customer Management</li>
		</ol>
	</section>
	
	
	<section class="content"> 
		<div class="row">
			<div class="col-md-9 col-sm-4 ">
				{{ Form::open(['role' => 'form','url' => 'customers','class' => 'mws-form']) }}
				{{ Form::hidden('display') }}
					<div class="col-md-3 col-sm-3">
						<div class="form-group ">  
							{{ Form::text('full_name',((isset($searchVariable['full_name'])) ? $searchVariable['full_name'] : ''), ['class' => 'form-control','placeholder'=>'Name']) }}
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="form-group ">  
							{{ Form::text('email',((isset($searchVariable['email'])) ? $searchVariable['email'] : ''), ['class' => 'form-control','placeholder'=>'Email']) }}
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<button class="btn btn-primary"><i class='fa fa-search '></i> Search</button>
						<a href="{{URL::to('customers')}}"  class="btn btn-primary"><i class='fa fa-refresh '></i> Reset</a>
					</div>
				{{ Form::close() }}
			</div>
			<div class="col-md-3 col-sm-3 ">
				<div class="form-group pull-right">  
					<a href="{{URL::to('customers/add-customer')}}" class="btn btn-success btn-small align">{{ trans("Add New Customer") }} </a>
				</div>
			</div>
		</div> 


	@if(Auth::user()->user_role_id == ADMIN_ID)
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
						<th width="12%">
							{{
								link_to_route(
								"Customers.index",
								trans("Name"),
								array(
									'sortBy' => 'full_name',
									'order' => ($sortBy == 'full_name' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'full_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'full_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
							}}
						</th>
						<th width="17%">
							{{
								link_to_route(
								"Customers.index",
								trans("Email"),
								array(
									'sortBy' => 'email',
									'order' => ($sortBy == 'email' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'email' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'email' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
							}}
						</th>
						<th width="12%">{{ trans("messages.system_management.status") }}</th>
						<th width="23%">{{ trans("messages.system_management.action") }}</th>
					</tr>
				</thead>
				<tbody id="powerwidgets">
					@if(!$result->isEmpty())
					@foreach($result as $record)
					<tr class="items-inner">
						<td data-th='{{ trans("messages.system_management.full_name") }}'>{{ $record->full_name }}</td>
						<td data-th='{{ trans("messages.system_management.email") }}'>{{ $record->email }}</td>
						<td data-th='{{ trans("messages.system_management.status") }}'>
							@if($record->active	== 0)
								<span class="label label-warning" >{{ trans("messages.user_management.deactivated") }}</span>
							@else
								<span class="label label-success" >{{ trans("messages.user_management.activated") }}</span>
							@endif
						</td>
						<td data-th='{{ trans("messages.system_management.action") }}'>
							<a title="Edit" href="{{URL::to('customers/edit-customer/'.$record->id)}}" class="btn btn-primary btn-small"><span class="ti-pencil"></span></a>
							@if($record->active	== 0)
								<a title="Click To Activate" href="{{URL::to('customers/update-status/'.$record->id.'/1')}}" class="status_user btn btn-success btn-small status_any_item "><span class="ti-check"></span></a>
							@else
								<a  title="Click To Deactivate" href="{{URL::to('customers/update-status/'.$record->id.'/0')}}" class="status_user btn btn-warning btn-small status_any_item"><span class="ti-na"></span></a>
							@endif
							<a title="Delete" href="{{URL::to('customers/delete-customer/'.$record->id)}}" class="btn btn-danger btn-small delete_any_item"><span class="ti-trash"></span></a>
							
							<a title="{{ trans('messages.user_management.send_login_credentials') }}"  class="status_user btn btn-primary btn-small" href="{{ URL::to('customers/send-credential/'.$record->id) }}">
								<i class="fa fa-share greenicon"></i>
							</a>
							
						</td>
					</tr>
					 @endforeach
					 @else
						<tr>
						<td align="center" style="text-align:center;" colspan="6" > No Result Found</td>
					  </tr>
					@endif 
				</tbody>
			</table>
		</div>
		<div class="box-footer clearfix">	
			<div class="col-md-3 col-sm-4 "></div>
			<div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $result])</div>                                  
		</div>
	</div>
	@else
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
						<th width="12%">
							{{
								link_to_route(
								"Customers.index",
								trans("Name"),
								array(
									'sortBy' => 'full_name',
									'order' => ($sortBy == 'full_name' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'full_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'full_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
							}}
						</th>
						<th width="17%">
							{{
								link_to_route(
								"Customers.index",
								trans("Email"),
								array(
									'sortBy' => 'email',
									'order' => ($sortBy == 'email' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'email' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'email' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
							}}
						</th>
						<th width="12%">{{ trans("messages.system_management.status") }}</th>
					</tr>
				</thead>
				<tbody id="powerwidgets">
					@if(!$result->isEmpty())
					@foreach($result as $record)
					<tr class="items-inner">
						<td data-th='{{ trans("messages.system_management.full_name") }}'>{{ $record->full_name }}</td>
						<td data-th='{{ trans("messages.system_management.email") }}'>{{ $record->email }}</td>
						<td data-th='{{ trans("messages.system_management.status") }}'>
							@if($record->is_approved	== 0)
								<span class="label label-warning" >{{ trans("Waiting For Approval") }}</span>
							@else
								<span class="label label-success" >{{ trans("Approved") }}</span>
							@endif
						</td>
					</tr>
					 @endforeach
					 @else
						<tr>
						<td align="center" style="text-align:center;" colspan="6" > No Result Found</td>
					  </tr>
					@endif 
				</tbody>
			</table>
		</div>
		<div class="box-footer clearfix">	
			<div class="col-md-3 col-sm-4 "></div>
			<div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $result])</div>                                  
		</div>
	</div>
	@endif
	
</section>
{{ HTML::style('js/admin/plugins/fancybox/jquery.fancybox.css') }}
{{ HTML::script('js/admin/plugins/fancybox/jquery.fancybox.js') }}
<script>
	$(function(){
		$('.fancybox').fancybox();
		$('.fancybox-buttons').fancybox({
			openEffect  : 'none',
			closeEffect : 'none',
			prevEffect 	: 'none',
			nextEffect 	: 'none',
		});
	});
</script>
@stop
