@extends('admin.layouts.default')
@section('content')
{{ HTML::script('js/admin/multiple_delete.js') }}
{{ HTML::script('js/admin/bootstrap-modal.min.js') }}
{{ HTML::style('css/admin/bootmodel.css') }}

<section class="content-header">
	<h1>
	  {{ trans("Contact Management") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li class="active"> {{ trans("Contact Management") }}</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		{{ Form::open(['role' => 'form','route' => "$modelName.index",'class' => 'mws-form',"method"=>"get"]) }}
		{{ Form::hidden('display') }}
		<div class="col-md-2 col-sm-2">
			<div class="form-group ">
				{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'form-control small','placeholder'=>"Name"]) }}
			</div>
		</div>
		<div class="col-md-2 col-sm-2">
			<div class="form-group ">
				{{ Form::text('email',((isset($searchVariable['email'])) ? $searchVariable['email'] : ''), ['class' => 'form-control small','placeholder'=>"Email"]) }}
			</div>
		</div>
		<div class="col-md-2 col-sm-2">
			<div class="form-group ">
				{{ Form::text('subject',((isset($searchVariable['subject'])) ? $searchVariable['subject'] : ''), ['class' => 'form-control small','placeholder'=>"Subject"]) }}
			</div>
		</div>
		<div class="col-md-2 col-sm-2">
			<div class="form-group ">
				{{ Form::text('message',((isset($searchVariable['message'])) ? $searchVariable['message'] : ''), ['class' => 'form-control small','placeholder'=>"Message"]) }}
			</div>
		</div>
		<div class="col-md-3 col-sm-3">
			<button class="btn btn-primary"><i class='fa fa-search '></i> Search</button>
			<a href='{{ route("$modelName.index")}}'  class="btn btn-primary btn-small"><i class="fa fa-refresh"></i> {{ trans("Clear Search") }}</a>
		</div>
		{{ Form::close() }}
	</div>
	
	<div class="box">
		<div class="box-body">
			<table class="table table-hover">
			<thead>
				<tr>
					<th width="20%">
						<?php $nameimage = ($sortBy == 'name') ? ($sortBy == 'name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
							"$modelName.index",
							trans("messages.$modelName.name").$nameimage,
							array(
								'sortBy' => 'name',
								'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc',
								$query_string
							),
							array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th width="20%">
						<?php $emailimage = ($sortBy == 'email') ? ($sortBy == 'email' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
							"$modelName.index",
							trans("messages.$modelName.email").$emailimage,
							array(
								'sortBy' => 'email',
								'order' => ($sortBy == 'email' && $order == 'desc') ? 'asc' : 'desc',
								
							),
							array('class' => (($sortBy == 'email' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'email' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th width="25%">{{ trans("messages.$modelName.subject") }}</th>
					<th width="25%">{{ trans("messages.$modelName.message") }}</th>
					<th>{{ trans("messages.global.action") }}</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(!$model->isEmpty()){
				foreach($model as $result){?>
				<tr>
					<td data-th='{{ trans("messages.$modelName.name") }}'>{{ $result->name }}</td>
					<td data-th='{{ trans("messages.$modelName.email") }}'><a href="mailTo: {{ $result->email }} "> {{ $result->email }} </a></td>
					<td data-th='{{ trans("messages.$modelName.subject") }}'> {{ $result->subject }} </td>
					<td data-th='{{ trans("messages.$modelName.message") }}'>{{ strip_tags(Str::limit( $result->message, 300)) }}</td>
					<td data-th='{{ trans("messages.$modelName.action") }}'>
						<a href='{{ route("$modelName.view","$result->id")}}' class="btn btn-info" title="View"> <i class="fa fa-eye"></i> </a>
						<a href='{{ route("$modelName.view","$result->id")}}#reply' data-delete="delete" class="btn btn-success " title="Reply"> <i class="fa fa-share"></i> </a>
					</td>
				</tr>
				<?php
				}
					}else{
				?>
					<tr>
						<td class="alignCenterClass" colspan="5" >{{ trans("messages.user_management.no_record_found_message") }}</td>
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
