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
	  {{ trans("Content") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li class="active"> {{ trans("Blog Management") }}</li>
		<li class="active"> {{ trans("Content") }}</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		{{ Form::open(['role' => 'form','route' => ["$modelName.conetentIndex"],'class' => 'mws-form',"method"=>"get"]) }}
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
			<a href='{{ route("$modelName.conetentIndex")}}'  class="btn btn-primary btn-small"><i class="fa fa-refresh"></i> {{ trans("Clear Search") }}</a>
		</div>
		
		{{ Form::close() }}
		<div class="col-md-3 col-sm-3"></div>
		<div class="col-md-3 col-sm-3  pull-right">
			<div class="form-group pull-right">  
				<a href='{{route("$modelName.add")}}' class="btn btn-success btn-small align">{{ trans("Add Article") }} </a>
			</div>
		</div>
	</div>
	
	<div class="box">
		<div class="box-body">
			  <div class="panel-group" id="accordion">
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
			</div>
			  
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
		</div>
		<div class="box-footer clearfix">	
			<div class="col-md-3 col-sm-4 "></div>
			<div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $model])</div>
		</div>
	</div>
</section> 

<script>
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
