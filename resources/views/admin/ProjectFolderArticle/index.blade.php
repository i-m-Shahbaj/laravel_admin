@extends('admin.layouts.default')
@section('content')
{{ HTML::script('js/admin/multiple_delete.js') }}
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
	  {{ trans("Article Management") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="{{route('ProjectFolder.index')}}">Blog Management</a></li>
		<li class="active"> {{ trans("Articles") }}</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		{{ Form::open(['role' => 'form','route' => [$modelName.'.index',$project_folder_id],'class' => 'mws-form','method'=>'get']) }}
		{{ Form::hidden('display') }}
		<div class="col-md-3 col-sm-3">
			<div class="form-group ">
				{{ Form::text('article_name',((isset($searchVariable['article_name'])) ? $searchVariable['article_name'] : ''), ['class' => 'form-control small','placeholder'=>"Name"]) }}
			</div>
		</div>
		
		<div class="col-md-2 col-sm-2">
			<div class="form-group ">  
				{{ Form::select('is_active',array(''=>trans('Select Status'),0=>'Inactive',1=>'Active'),((isset($searchVariable['is_active'])) ? $searchVariable['is_active'] : ''), ['class' => 'form-control chosen-select']) }}
			</div>
		</div>
		<div class="col-md-3 col-sm-3">
			<button class="btn btn-primary"><i class='fa fa-search '></i> Search</button>
			<a href='{{ route("$modelName.index",array($project_folder_id))}}'  class="btn btn-primary btn-small"><i class="fa fa-refresh"></i> {{ trans("Clear Search") }}</a>
		</div>
		
		{{ Form::close() }}
		<div class="col-md-3 col-sm-3"></div>
		<div class="col-md-3 col-sm-3  pull-right">
			<div class="form-group pull-right">  
				<a href='{{route("$modelName.add",array($project_folder_id))}}' class="btn btn-success btn-small align">{{ trans("Add Article") }} </a>
			</div>
		</div>
	</div>
	
	<div class="box">
		<div class="box-body">
			<table class="table table-hover">
			<thead>
				<tr>
					<th width="20%">
						<?php $nameimage = ($sortBy == 'article_name') ? ($sortBy == 'article_name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
							"$modelName.index",
							trans("Name").$nameimage,
							array(
								'project_folder_id' => "$project_folder_id",
								'sortBy' => 'article_name',
								'order' => ($sortBy == 'article_name' && $order == 'desc') ? 'asc' : 'desc',
								$query_string
							),
							array('class' => (($sortBy == 'article_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'article_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th>Image</th>
					<th width="25%">
						<?php $createdAt = ($sortBy == 'created_at') ? ($sortBy == 'created_at' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
						{!!
							html_entity_decode(link_to_route(
							$modelName.'.index',
							trans("Created At").$createdAt,
							array(
								'project_folder_id' => "$project_folder_id",
								'sortBy' => 'created_at',
								'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc',
								$query_string
							),
							array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th width="15%" >
						<?php $isactiveimage = ($sortBy == 'is_active') ? ($sortBy == 'is_active' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
						{!!
							html_entity_decode(link_to_route(
							$modelName.'.index',
							trans("Status").$isactiveimage,
							array(
								'project_folder_id' => "$project_folder_id",
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
					<td data-th='{{ trans("messages.$modelName.name") }}'>{{ $result->article_name }}</td>
					<td>
						@if($result->image != '' && File::exists(PROJECT_ARTICLE_IMAGE_ROOT_PATH.$result->image))
							<?php
								$image				=	PROJECT_ARTICLE_IMAGE_URL.$result->image;
							?>
							<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo $image; ?>">
								<img src="<?php echo WEBSITE_URL.'image.php?height=100px&width=100px&cropratio=1:1&image='.$image; ?>">
							</a>
						@else
							<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>">
								<img src="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>" width='100' height="100" />
							</a>
						@endif
					</td>
					<td data-th='{{ trans("messages.$modelName.subject") }}'> {{ $result->created_at }} </td>
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
						<a href='{{ route("$modelName.view",array("$project_folder_id","$result->id"))}}' class="btn btn-info" title="View"> <i class="fa fa-eye"></i> </a>
						<a href='{{ route("$modelName.edit",array("$project_folder_id","$result->id"))}}' class="btn btn-success " title="Edit"> <i class="fa fa-pencil"></i> </a>
						@if($result->is_check_this_out == 1)
							<a  title="Remove From Check This Out" href="{{route($modelName.'.checkThisOut',array($result->id,0))}}" class="btn btn-danger btn-small status_any_item"><i class="fa fa-sign-in"></i>
							</a>
						@endif	
						@if($result->is_check_this_out == 0)
							<a title="Add To Check This Out" href="{{route($modelName.'.checkThisOut',array($result->id,1))}}" class="btn btn-success btn-small status_any_item"><i class="fa fa-sign-in"></i>
							</a> 
						@endif
						
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
