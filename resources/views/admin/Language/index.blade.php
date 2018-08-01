@extends('admin.layouts.default')
@section('content')
<script>
	$(function(){
		/**
		 * Function to change status
		 *
		 * @param null
		 *
		 * @return void
		 */
		$(document).on('click', '.default_any_item', function(e){ 
			e.stopImmediatePropagation();
			url = $(this).attr('href');
			bootbox.confirm("Are you sure want to make default this language ?",
			function(result){
				if(result){
					window.location.replace(url);
				}
			});
			e.preventDefault();
		});
	});
</script>
<section class="content-header">
	<h1>
		{{ trans("Language") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{URL::to('admin/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">{{ trans("Language") }}</li>
	</ol>
</section>
<section class="content"> 
	<!--<div class="row">
		{{ Form::open(['method' => 'get','role' => 'form','route' => "$modelName.index",'class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="col-md-3 col-sm-3">
			<div class="form-group ">  
				{{ Form::text('title',((isset($searchVariable['title'])) ? $searchVariable['title'] : ''), ['class' => 'form-control','placeholder'=>"Title"]) }}
			</div>
		</div>
		<div class="col-md-4 col-sm-4">
			<button class="btn btn-primary"><i class='fa fa-search '></i> {{ trans('messages.search.text') }}</button>
			<a href="{{URL::to('admin/language')}}"  class="btn btn-primary"><i class='fa fa-refresh '></i> {{ trans("messages.reset.text") }}</a>
		</div>
		{{ Form::close() }}
		<div class="col-md-5 col-sm-5 ">
			<div class="form-group pull-right">  
				<!--<a href='{{route("$modelName.add")}}' class="btn btn-success btn-small align">{{ trans("Add Language") }} </a>
			</div>
		</div>
	</div>-->
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
					<th width="25%">
					{{
						link_to_route(
							"$modelName.index",
							trans("Title"),
							array(
								'sortBy' => 'title',
								'order' => ($sortBy == 'title' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'title' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'title' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					</th>
					<!--<th width="25%">
						{{
							link_to_route(
								"$modelName.index",
								trans("Folder Code"),
								array(
									'sortBy' => 'folder_code',
									'order' => ($sortBy == 'folder_code' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'folder_code' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'folder_code' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
						
					<th width="25%">
						{{
							link_to_route(
								"$modelName.index",
								trans("language Code"),
								array(
									'sortBy' => 'lang_code',
									'order' => ($sortBy == 'lang_code' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'lang_code' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'lang_code' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>-->
					<th width="25%">
						{{ 'Status' }}
					</th>
					<th width="25%">{{ trans("Action") }}</th>
					</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$model->isEmpty())
				@foreach($model as $result)
					<tr class="items-inner">
						<td data-th='{{ trans("messages.$modelName.title") }}'>{{ $result->title }}</td>
						<!--<td data-th='{{ trans("messages.$modelName.folder_code") }}'>{{ $result->folder_code }}</td>
						<td data-th='{{ trans("messages.$modelName.language_code") }}'>{{ $result->lang_code }}</td>-->
						<td data-th='{{ trans("Status") }}'>
							@if($result->is_active)
								<label class="label label-success">Activated</label>
							@else
								<label class="label label-warning">Deactivated</label>
							@endif
						</td>
						<td data-th='{{ trans("messages.$modelName.action") }}'>
							@if($result->is_active == 1)
								<a  title="Click To Deactivate" href='{{route("$modelName.status",array($result->id,0))}}' class="btn btn-success btn-small status_any_item"><span class="fa fa-ban"></span>
								</a>
							@else
								<a title="Click To Activate" href='{{route("$modelName.status",array($result->id,1))}}' class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
								</a> 
							@endif
							
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td class="alignCenterClass" colspan="5" >{{ trans("messages.user_management.no_record_found_message") }}</td>
					</tr>
					@endif 
				</tbody>
			</table>
		</div>
		<div class="box-footer clearfix">	
			<div class="col-md-3 col-sm-4 "></div>
			<div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $model])</div>
		</div>
	</div>
</section> 
@stop
