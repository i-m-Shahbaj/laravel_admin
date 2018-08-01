@extends('admin.layouts.default')
@section('content')
{{ HTML::script('js/admin/bootstrap-modal.min.js') }}
{{ HTML::style('css/admin/bootmodel.css') }}
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
	  {{ trans("Library Management") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li class="active"> {{ trans("Library Management") }}</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		{{ Form::open(['role' => 'form','route' => "$modelName.index",'class' => 'mws-form',"method"=>"get"]) }}
		{{ Form::hidden('display') }}
		<div class="col-md-3 col-sm-3">
			<div class="form-group ">
				{{ Form::text('author',((isset($searchVariable['author'])) ? $searchVariable['author'] : ''), ['class' => 'form-control small','placeholder'=>"Author Name"]) }}
			</div>
		</div>
		<div class="col-md-3 col-sm-3">
			<div class="form-group ">
				{{ Form::text('project_name',((isset($searchVariable['project_name'])) ? $searchVariable['project_name'] : ''), ['class' => 'form-control small','placeholder'=>"Project Name"]) }}
			</div>
		</div>
		<div class="col-md-3 col-sm-3">
			<div class="form-group ">
				{{ Form::text('author_group',((isset($searchVariable['author_group'])) ? $searchVariable['author_group'] : ''), ['class' => 'form-control small','placeholder'=>"Authorized Group"]) }}
			</div>
		</div>
		
		<div class="col-md-2 col-sm-2">
			<div class="form-group ">  
				{{ Form::select('is_active',array(''=>trans('Select Status'),0=>'Inactive',1=>'Active'),((isset($searchVariable['is_active'])) ? $searchVariable['is_active'] : ''), ['class' => 'form-control chosen-select']) }}
			</div>
		</div>
		<div class="col-md-3 col-sm-3">
			<button class="btn btn-primary"><i class='fa fa-search '></i> Search</button>
			<a href='{{ route("$modelName.index")}}'  class="btn btn-primary btn-small"><i class="fa fa-refresh"></i> {{ trans("Clear Search") }}</a>
		</div>
		
		{{ Form::close() }}
		<div class="col-md-3 col-sm-3"></div>
		<div class="col-md-3 col-sm-3  pull-right">
			<div class="form-group pull-right">  
				<a href='{{route("$modelName.add")}}' class="btn btn-success btn-small align">{{ trans("Add Project") }} </a>
			</div>
		</div>
	</div>
	
	<div class="box">
		<div class="box-body">
			<table class="table table-hover">
			<thead>
				<tr>
					<th width="15%">
						<?php $nameimage = ($sortBy == 'author') ? ($sortBy == 'author' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
							"$modelName.index",
							trans("Author Name").$nameimage,
							array(
								'sortBy' => 'author',
								'order' => ($sortBy == 'author' && $order == 'desc') ? 'asc' : 'desc',
								$query_string
							),
							array('class' => (($sortBy == 'author' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'author' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th width="15%">
						<?php $nameimage = ($sortBy == 'project_name') ? ($sortBy == 'project_name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
							"$modelName.index",
							trans("Project Name").$nameimage,
							array(
								'sortBy' => 'project_name',
								'order' => ($sortBy == 'project_name' && $order == 'desc') ? 'asc' : 'desc',
								$query_string
							),
							array('class' => (($sortBy == 'project_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'project_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th width="20%">
						<?php $nameimage = ($sortBy == 'author_group') ? ($sortBy == 'author_group' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
							"$modelName.index",
							trans("Authorized Group").$nameimage,
							array(
								'sortBy' => 'author_group',
								'order' => ($sortBy == 'author_group' && $order == 'desc') ? 'asc' : 'desc',
								$query_string
							),
							array('class' => (($sortBy == 'author_group' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'author_group' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th width="15%">
						<?php $createdAt = ($sortBy == 'created_at') ? ($sortBy == 'created_at' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
						{!!
							html_entity_decode(link_to_route(
							$modelName.'.index',
							trans("Created At").$createdAt,
							array(
								'sortBy' => 'created_at',
								'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc',
								$query_string
							),
							array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th width="10%" >
						<?php $isactiveimage = ($sortBy == 'is_active') ? ($sortBy == 'is_active' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
						{!!
							html_entity_decode(link_to_route(
							$modelName.'.index',
							trans("Status").$isactiveimage,
							array(
								'sortBy' => 'is_active',
								'order' => ($sortBy == 'is_active' && $order == 'desc') ? 'asc' : 'desc',
								$query_string
							),
							array('class' => (($sortBy == 'is_active' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'is_active' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th>{{ trans("messages.global.action") }}</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(!$model->isEmpty()){
				foreach($model as $result){?>
				<tr>
					<td data-th='{{ trans("messages.$modelName.name") }}'>{{ $result->author }}</td>
					<td data-th='{{ trans("messages.$modelName.name") }}'>{{ $result->project_name }}</td>
					<td data-th='{{ trans("messages.$modelName.name") }}'>
						{{ $result->author_group }}
					</td>
					<td data-th='{{ trans("messages.$modelName.subject") }}'> 
						{{ date(Config::get('Reading.date_format'),strtotime($result->created_at)) }} </td>
					<td data-th='{{ trans("messages.$modelName.subject") }}'> 
						@if($result->is_active)
							<label class="label label-success">Activated</label>
						@else
							<label class="label label-warning">Deactivated</label>
						@endif	
					</td>
					<td data-th='{{ trans("messages.$modelName.action") }}'>
							@if($result->is_active == 1)
								<a  title="Click To Deactivate" href="{{route($modelName.'.status',array($result->id,0))}}" class="btn btn-success btn-small status_any_item"><span class="fa fa-ban"></span>
								</a>
							@else
								<a title="Click To Activate" href="{{route($modelName.'.status',array($result->id,1))}}" class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
								</a> 
							@endif
							<a href='{{ route("$modelName.edit",array("$result->id"))}}' class="btn btn-success " title="Edit"> <i class="fa fa-pencil"></i> </a>
						<a href='{{ route("$modelName.view","$result->id")}}' class="btn btn-info" title="View"> <i class="fa fa-eye"></i> </a>
						<a href='{{ route("ProjectFolder.index","$result->id")}}' class="btn btn-primary " title="Add Folder"> <i class="fa fa-plus"></i> </a>
					</td>
				</tr>
				<?php
				}
					}else{
				?>
					<tr>
						<td class="alignCenterClass" colspan="6" >{{ trans("messages.user_management.no_record_found_message") }}</td>
					</tr>
				<?php
					}
				?> 
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
