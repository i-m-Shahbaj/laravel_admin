@extends('admin.layouts.default')
@section('content')

<!--pop js start here-->
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
	/* For open Email detail popup */
	function getPopupClient(id){
		var url_path 	=	$(".popup_url_"+id).attr("data-route");
		$.ajax({  
			url: url_path,
			type: "POST",
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success : function(r){
				$("#getting_basic_list_popover").html(r);
				$("#getting_basic_list_popover").modal('show');
			}
		});
	}
	
</script>
<!--pop js end here-->

<!--pop div start here-->
<div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="getting_basic_list_popover" class="modal fade in" style="display: none;">
</div>
<!-- popup div end here-->
<section class="content-header">
	<h1>
	  {{ trans("messages.system_management.email_logs") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active"> {{ trans("messages.system_management.email_logs") }}</li>
	</ol>
</section>
<section class="content"> 
	<div class="row">
			{{ Form::open(['method' => 'get','role' => 'form','route' => $modelName.'.listEmail','class' => 'mws-form']) }}
			{{ Form::hidden('display') }}
			<?php
				$email_to	=	Input::get('email_to'); 
				$subject	=	Input::get('subject'); 
			?>
				<div class="col-md-3 col-sm-3">
					<div class="form-group ">  
						{{ Form::text(
								'email_to', 
								 isset($email_to) ? Input::get('email_to') :'', 
								 ['class' =>'form-control','id'=>'country','placeholder'=>'Email']) 
						}}
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<div class="form-group ">  
						{{ Form::text(
								'subject', 
								 isset($subject) ? $subject :'', 
								 ['class' =>'form-control','id'=>'country','placeholder'=>'Subject']) 
						}}
					</div>
				</div>
				<div class="col-md-4 col-sm-4">
					<button class="btn btn-primary"><i class='fa fa-search '></i> {{ trans('messages.system_management.search') }}</button>
					<a href="{{route($modelName.'.listEmail')}}"  class="btn btn-primary"><i class='fa fa-refresh '></i> {{ trans('Clear Search') }}</a>
				</div>
			{{ Form::close() }}
	</div> 
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
						<th width="20%">
							{{ trans('messages.system_management.email_to') }}
						</th>
						<th width="20%">
							{{ trans('messages.system_management.email_from') }}
						</th>
						<th width="20%">
							{{ trans('messages.system_management.subject') }}
						</th>
						<th width="20%">
							<?php $createdatimage = ($sortBy == 'created_at') ? ($sortBy == 'created_at' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
									'EmailLogs.listEmail',
									'Mail Sent On'.$createdatimage,
									array(
										'sortBy' => 'created_at',
										'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc',
										$query_string
									),
								   array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!} 
						</th>
						<th width="10%">
							{{ trans('messages.system_management.action') }}
						</th>
					</tr>
				</thead>
				<tbody>
					@if(!$result->isEmpty())
					@foreach($result as $data)
						<tr>
							<td data-th="{{ trans('messages.system_management.email_to') }}">{{ $data->email_to }}</td>
							<td data-th="{{ trans('messages.system_management.email_from') }}">{{ $data->email_from }}</td>
							<td data-th="{{ trans('messages.system_management.subject') }}">{{ $data->subject}}</td>
							<td data-th="{{ trans('messages.system_management.created') }}">{{ date(Config::get("Reading.date_format"),strtotime($data->created_at)) }}</td>
							<td data-th="{{ trans('messages.system_management.action') }}">
								<a href='javascript:void(0);' class="btn btn-info popup_url_{{$data->id}}" title='{{ trans("messages.system_management.view_email_logs") }}' data-route="{{route($modelName.'.popup',$data->id)}}" onclick="getPopupClient({{ $data->id }})"> <i class="fa fa-eye" ></i> </a>
							</td>
						</tr>
					@endforeach
					@else
						<tr>
							<td colspan="5" style="text-align:center;font-weight:bold;">
								{{ trans("messages.system_management.no_records_found") }}
							</td>
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
</section> 
@stop
