@extends('admin.layouts.default')
@section('content')
<!-- datetime picker js and css start here-->
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::script('js/admin/jquery-ui.min.js') }}
{{ HTML::style('css/admin/chosen.min.css') }}
{{ HTML::script('js/admin/chosen.jquery.js') }}
<!-- date time picker js and css and here-->

<script type="text/javascript"> 
	$(function(){
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
jQuery(document).ready(function(){
$(".main_category").chosen();
$(".event_type").chosen();
$(".event_category").chosen();
});
</script>
<section class="content-header">
	<h1>{{ trans("Product Management") }}</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li class="active">{{ trans("Product Management") }}</li>
	</ol>
</section>
<section class="content"> 
<div class="box box-primary pie_chart_header">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-search "></i>Search</h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse" type="button">
					<i class="fa fa-minus"></i>
				</button>
				<button class="btn btn-box-tool" data-widget="remove" type="button">
					<i class="fa fa-times"></i>
				</button>
			</div>
		</div>
		<div class="box-body" style="display: block;">
		<div class="row">
			{{ Form::open(['method' => 'get','role' => 'form','url' => 'cmeshinepanel/product-manager','class' => 'mws-form']) }}
			{{ Form::hidden('display') }}
				<div class="col-md-3 col-sm-3">
					<div class="form-group ">
						{{Form::select('category_id',array(''=>'Select Category')+$listCategory,((isset($searchVariable['category_id'])) ? $searchVariable['category_id'] : ''),['class' => 'main_category form-control']) }} 
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<div class="form-group ">  
						{{ Form::select('is_active',array(''=>trans('Select Status'),ACTIVE=>'Active',INACTIVE=>'Inactive'),((isset($searchVariable['is_active'])) ? $searchVariable['is_active'] : ''), ['class' => 'form-control change_status main_category']) }}
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<div class="form-group ">
						{{Form::select('product_size',array(''=>'Select Size')+$listSize,((isset($searchVariable['product_size'])) ? $searchVariable['product_size'] : ''),['class' => 'main_category form-control']) }} 
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'form-control', 'placeholder' => 'Product Name']) }}
					</div>
				</div>

		
				<div class="col-md-3 col-sm-3">
					<div class="form-group ">  
						{{ Form::text('date_from',((isset($date_from)) ? $date_from : ''), ['class' => 'form-control datepicker','placeholder'=>'Product Start Date']) }}
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<div class="form-group ">  
						{{ Form::text('date_to',((isset($date_to)) ? $date_to : ''), ['class' => 'form-control datepicker1','placeholder'=>'Product End Date']) }}
					</div>
				</div> 
			</div>
		<div class="row">
				<div class="col-md-6 col-sm-6">
				<button class="btn btn-primary"><i class='fa fa-search '></i> Search</button>
					<a href="{{URL::to('cmeshinepanel/product-manager')}}" class="btn btn-primary"><i class='fa fa-refresh '></i> Reset</a>
				<div class="btn-group" >
				<ul class="dropdown-menu" role="menu">
					<li>
						<a  href="{{URL::to('cmeshinepanel/product-manager/export')}}" class="btn-small ml5">All product reports</a>
					</li>
					<li>
						<a  href="{{URL::to('cmeshinepanel/product-manager/export-filtered')}}" class="btn-small ml5">Filtered product reports</a>
					</li>
				</ul>
			</div>
				</div>
				
			{{ Form::close() }}
			<div class="col-md-6 col-sm-3 col-xs-12">
				<div class="form-group pull-right">  
					<a href="{{URL::to('cmeshinepanel/product-manager/add-product')}}" class="btn btn-success btn-small align">{{ trans("Add New Product") }} </a>
				</div>
			</div>
		</div> 
	</div>
</div>
<div class="row">
	<div class="col-md-3 col-sm-3 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-orange"><i class="fa fa-shopping-cart"></i></span>
		<div class="info-box-content">
			<span class="info-box-text"><b>Total Products </b><br/>(Till Now)</span>
			<span class="info-box-number">{{{ $total_products }}}</span>
		</div>
	  </div>
	</div>
	<div class="col-md-3 col-sm-3 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>
		<div class="info-box-content">
		  <span class="info-box-text"><b>Total Products </b><br/>(In This Month)</span>
		  <span class="info-box-number">{{{ $this_month_products }}}</span>
		</div>
	  </div>
	</div>
	<div class="col-md-3 col-sm-3 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-purple"><i class="fa fa-shopping-cart"></i></span>
		<div class="info-box-content">
		  <span class="info-box-text"><b>Total Products </b><br/>(In The Last Month)</span>
		  <span class="info-box-number">{{{ $last_month_products }}}</span>
		</div>
	  </div>
	</div>
	<div class="col-md-3 col-sm-3 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-red"><i class="fa fa-shopping-cart"></i></span>
		<div class="info-box-content">
		  <span class="info-box-text"><b>Total Products </b><br/>(In Current Year)</span>
		  <span class="info-box-number">{{{ $currentYearProducts }}}</span>
		</div>
	  </div>
	</div>
</div>
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
						<th width="12%">{{ trans("IMAGE") }} </th>
						<th width="12%">
							{{
								link_to_route(
								"Product.index",
								trans("Product Name"),
								array(
									'sortBy' => 'products.name',
									'order' => ($sortBy == 'products.name' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'products.name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'products.name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
							}}
						</th>
						<th width="12%">
							{{
								link_to_route(
								"Product.index",
								trans("Category"),
								array(
									'sortBy' => 'category_id',
									'order' => ($sortBy == 'category_id' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'category_id' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'category_id' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
							}}
						</th>
						<th width="12%">{{
								link_to_route(
								"Product.index",
								trans("Price"),
								array(
									'sortBy' => 'price',
									'order' => ($sortBy == 'price' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'price' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'price' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
							}}</th>
						<th width="12%">
							{{
								link_to_route(
								"Product.index",
								trans("Created"),
								array(
									'sortBy' => 'products.created_at',
									'order' => ($sortBy == 'products.created_at' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'products.created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'products.created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								)
							}} 
						</th>
						<th width="12%">{{ trans("STATUS") }} </th>
						<th width="20%" >{{ trans("messages.system_management.action") }}</th>
					</tr>
				</thead>
				<tbody>
					@if(!$result->isEmpty())
						@foreach($result as $record)
							<tr class="items-inner">
								<td>
									@if($record->main_image != '' && File::exists(PRODUCTS_IMAGE_ROOT_PATH.$record->main_image)) 
										<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo PRODUCTS_IMAGE_URL.$record->main_image; ?>">
											<div class="usermgmt_image">
												<img class="img-circle"  src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1:1&image='.PRODUCTS_IMAGE_URL.'/'.$record->main_image ?>">
											</div>
										</a>
									@else
										<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg">
											<div class="usermgmt_image">
												<img class="img-circle" src="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg">
											</div>
										</a>
									@endif
								</td>
								<td>{{ $record->name}}</td>
								<td>{{ isset($listCategory[$record->category_id]) ? $listCategory[$record->category_id] : ''}}</td>
								<td>${{isset($record->price) ? $record->price: ''}}</td>
								<td>{{ isset($record->created_at)?$record->created_at:'' }}</td>
								<td>
									@if($record->is_active	== 1)
										<span class="label label-success" >{{ trans("messages.global.activated") }}</span>
									@else
										<span class="label label-warning" >{{ trans("messages.global.deactivated") }}</span>
									@endif
									@if($record->is_featured == 1)
										<span class="label label-success" >{{ trans("Featured") }}</span>
									@endif
								</td>
								<td data-th='{{ trans("messages.system_management.action") }}'>
									@if($record->is_active == 1)
										<a  title="Click To Deactivate" href="{{URL::to('cmeshinepanel/product-manager/update-status/'.$record->id.'/0')}}" class="btn btn-success btn-small status_any_item"><span class="fa fa-check"></span>
										</a>
									@else
										<a title="Click To Activate" href="{{URL::to('cmeshinepanel/product-manager/update-status/'.$record->id.'/1')}}" class="btn btn-warning btn-small status_any_item"><span class="fa fa-ban"></span>
										</a> 
									@endif
<!--
									@if($record->is_featured == 1)
										<a  title="Click To Remove From Featured" href="{{URL::to('cmeshinepanel/product-manager/update-featured-status/'.$record->id.'/0')}}" class="btn btn-success btn-small status_any_item"><span class="fa fa-check"></span>
										</a>
									@else
										<a title="Click To Mark As Featured" href="{{URL::to('cmeshinepanel/product-manager/update-featured-status/'.$record->id.'/1')}}" class="btn btn-warning btn-small status_any_item"><span class="fa fa-ban"></span>
										</a> 
									@endif
-->
									<a title="Edit" href="{{URL::to('cmeshinepanel/product-manager/edit-product/'.$record->id)}}" class="btn btn-primary"><span class="fa fa-pencil"></span></a>
									<a title="{{ trans('Delete') }}" href="{{ URL::to('cmeshinepanel/product-manager/delete-product/'.$record->id) }}"  class="delete_any_item btn btn-danger">
										<i class="fa fa-trash-o"></i>
									</a>
									<a href="{{URL::to('cmeshinepanel/product-manager/view-product/'.$record->id)}}" title="{{ trans('messages.global.view') }}" class="btn btn-info">
										<i class="fa fa-eye"></i>
									</a>
								</td>
							</tr>
						 @endforeach
					 @else
						<tr>
							<td class="alignCenterClass" colspan="7" >{{ trans("messages.user_management.no_record_found_message") }}</td>
						</tr>
					@endif 
				</tbody>
			</table>
		</div>
		<div class="box-footer clearfix">	
			<div class="col-md-3 col-sm-4 "></div>
			<div class="col-md-9 col-sm-8 text-right ">@include('pagination.default',['paginator' => $result])</div>
		</div>
	</div>
</section> 
<script type="text/javascript">
	//~ $('#goals,#ingredients').multiselect({ 
	    //~ numberDisplayed: 2,
		//~ includeSelectAllOption: true,
	    //~ enableFiltering:true  
	//~ });

</script>
<script type="text/javascript">
	/**
	 * Datepicker for date range
	 */
	$( ".datepicker" ).datepicker({
		dateFormat 	: 'yy-mm-dd',
		changeMonth : true,
		changeYear 	: true,
		yearRange	: '-100y:c+nn',
		onSelect	: function( selectedDate ){ $(".datepicker1").datepicker("option","minDate",selectedDate); }
	});
	$( ".datepicker1" ).datepicker({
		dateFormat 	: 'yy-mm-dd',
		changeMonth : true,
		changeYear 	: true,
		yearRange	: '-100y:c+nn',
		onSelect	: function( selectedDate ){ $(".datepicker").datepicker("option","maxDate",selectedDate); }
	});
</script>
@stop
