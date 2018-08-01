@extends('admin.layouts.default')
@section('content')

{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::script('js/admin/chosen/chosen.jquery.min.js') }}
{{ HTML::style('css/admin/chosen.min.css') }}

<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

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
	  {{ trans("Cateogories") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li class="active"> {{ trans("Blog Management") }}</li>
		<li class="active"> {{ trans("Cateogories") }}</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		{{ Form::open(['role' => 'form','route' => ["$modelName.index"],'class' => 'mws-form',"method"=>"get"]) }}
		{{ Form::hidden('display') }}
		<div class="col-md-3 col-sm-3">
			<div class="form-group ">
				{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'form-control small','placeholder'=>"Name"]) }}
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
				<a href='{{route("$modelName.add")}}' class="btn btn-success btn-small align">{{ trans("Add Category") }} </a>
			</div>
		</div>
	</div>
	
	<div class="box">
		<div class="box-body">
			<!--   <div class="panel-group" id="accordion">
			  <?php
				if(!$model->isEmpty()){
				foreach($model as $result){?>
			  <div class="panel panel-default">
				<div class="panel-heading">
				  <h4 class="panel-title">
					<a class="accordion-toggle getArticleOfCategory" data-rel="<?php echo $result->id; ?>" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $result->id; ?>">
					  {{ $result->name }}
					</a>
				  </h4>
				</div>
				<div id="collapse<?php echo $result->id; ?>" class="panel-collapse collapse">
				  <div class="panel-body">
					
				  </div>
				</div>
			  </div>
				<?php }
				} ?>
			</div> -->
			  
			<style>
			.panel-heading .accordion-toggle:after {
				/* symbol for "opening" panels */
				font-family: 'Glyphicons Halflings';  /* essential for enabling glyphicon */
				content: "\e114";    /* adjust as needed, taken from bootstrap.css */
				float: right;        /* adjust as needed */
				color: grey;         /* adjust as needed */
			}
			.panel-heading .accordion-toggle.collapsed:after {
				/* symbol for "collapsed" panels */
				content: "\e080";    /* adjust as needed, taken from bootstrap.css */
			}

			</style>
		
		 
			<table class="table table-hover">
			<thead>
				<tr>
					<th width="15%">
						<?php $nameimage = ($sortBy == 'name') ? ($sortBy == 'name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
							"$modelName.index",
							trans("Name").$nameimage,
							array(
								'sortBy' => 'name',
								'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc',
								$query_string
							),
							array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th>Image</th>
					<th width="15%">
						<?php $articleimage = ($sortBy == 'total_articles') ? ($sortBy == 'total_articles' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
							"$modelName.index",
							trans("Total Article").$articleimage,
							array(
								'sortBy' => 'total_articles',
								'order' => ($sortBy == 'total_articles' && $order == 'desc') ? 'asc' : 'desc',
								$query_string
							),
							array('class' => (($sortBy == 'total_articles' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'total_articles' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							))
						!!}
					</th>
					<th width="20%">
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
					<th width="15%" >
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
			<tbody id="page_list">
				<?php
				if(!$model->isEmpty()){
				foreach($model as $result){?>
				<tr id="{{ $result->id }}">
					<td data-th='{{ trans("messages.$modelName.name") }}'>{{ $result->name }}</td>
					<td data-th='{{ trans("messages.$modelName.name") }}'>
						@if($result->image != '' && File::exists(PROJECT_FOLDER_IMAGE_ROOT_PATH.$result->image))
							<?php
								$image				=	PROJECT_FOLDER_IMAGE_URL.$result->image;
							?>
							<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo $image; ?>">
								<img src="<?php echo WEBSITE_URL.'image.php?height=100px&width=100pxcropratio=1:1&image='.$image; ?>">
							</a>
						@else
							<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>">
								<img src="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>" width='100' height="100" />
							</a>
						@endif
					</td>
					<td data-th='{{ trans("messages.$modelName.name") }}'>{{ $result->total_articles }}</td>
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

						<?php /* <a href='{{ route("$modelName.view",array("$result->id"))}}' class="btn btn-info" title="View"> <i class="fa fa-eye"></i> </a>*/?>

						<a href='{{ route("$modelName.edit",array("$result->id"))}}' class="btn btn-info " title="Edit"> <i class="fa fa-pencil"></i> </a>
						<?php /* 	<a href='{{ route("ProjectFolderArticle.index",array($result->id))}}' class="btn btn-primary " title="Add Article"> <i class="fa fa-plus"></i> </a> */ ?>
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
<script>
$(document).ready(function(){
	$( "#page_list" ).sortable({
		placeholder : "ui-state-highlight",
		update  : function(event, ui){
			var page_id_array = new Array();
			$('#page_list tr').each(function(){
				page_id_array.push($(this).attr("id"));
			});
			$.ajax({
				headers:{
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url:"{{ route('ProjectFolder.updateOrder') }}",
				method:"POST",
				data:{page_id_array:page_id_array},
				success:function(data){
					show_message(data,"success");
				}
			});
		}
	});
});
$(".getArticleOfCategory").click(function(){
	var id = $(this).attr("data-rel");
	$.ajax({
		headers:{
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		url: '{{ route("ProjectFolder.getArticleCategories") }}',
		type:'post',
		data: {'id':id},
		success: function(r){
			$("#collapse"+id).find(".panel-body").html(r);
		}	
	})
});
</script>
@stop
