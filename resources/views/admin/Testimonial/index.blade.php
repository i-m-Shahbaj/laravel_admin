@extends('admin.layouts.default')

@section('content')

<section class="content-header">
	<h1>
		{{ trans("messages.$modelName.table_heading_index")}}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li class="active">{{ trans("messages.$modelName.table_heading_index")}}</li>
	</ol>
</section>
<section class="content"> 
	<div class="row">
		{{ Form::open(['method' => 'get','role' => 'form','route' => "$modelName.index",'class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="col-md-3 col-sm-3">
			<div class="form-group ">  
				{{ Form::text('client_name',((isset($searchVariable['client_name'])) ? $searchVariable['client_name'] : ''), ['class' => 'form-control','placeholder'=>'Client Name']) }}
			</div>
		</div>
		<div class="col-md-3 col-sm-3">
			<button class="btn btn-primary"><i class='fa fa-search '></i> {{ trans('messages.search.text') }}</button>
			<a href='{{ route("$modelName.index")}}'  class="btn btn-primary"><i class='fa fa-refresh '></i> {{ trans("Clear Search") }}</a>
		</div>
		{{ Form::close() }}
		<div class="col-md-6 col-sm-6 ">
			<div class="form-group pull-right">  
				<a href='{{route("$modelName.add")}}' class="btn btn-success btn-small align">{{ trans("messages.$modelName.add_new") }} </a>
			</div>
		</div>
	</div>
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
						<th width="10%">
							Client Image
						</th>
						<th width="15%">
							<?php $clientnameimage = ($sortBy == 'client_name') ? ($sortBy == 'client_name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
									"$modelName.index",
									trans("messages.$modelName.client_name").$clientnameimage,
									array(
										'sortBy' => 'client_name',
										'order' => ($sortBy == 'client_name' && $order == 'desc') ? 'asc' : 'desc',
										$query_string
									),
								   array('class' => (($sortBy == 'client_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'client_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="10%">
							<?php $locationimage = ($sortBy == 'location') ? ($sortBy == 'location' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
									"$modelName.index",
									trans("location").$locationimage,
									array(
										'sortBy' => 'location',
										'order' => ($sortBy == 'location' && $order == 'desc') ? 'asc' : 'desc',
										$query_string
									),
								   array('class' => (($sortBy == 'location' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'location' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="25%">
							{{
								trans("Client Comment")
							}}
						</th>
						<th width="10%">
							<?php $orderimage = ($sortBy == 'testimonial_order') ? ($sortBy == 'testimonial_order' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
									"$modelName.index",
									trans("Order").$orderimage,
									array(
										'sortBy' => 'testimonial_order',
										'order' => ($sortBy == 'testimonial_order' && $order == 'desc') ? 'asc' : 'desc',
										$query_string
									),
								   array('class' => (($sortBy == 'testimonial_order' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'testimonial_order' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="10%">
							<?php $isactiveimage = ($sortBy == 'is_active') ? ($sortBy == 'is_active' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
									"$modelName.index",
									trans("messages.global.status").$isactiveimage,
									array(
										'sortBy' => 'is_active',
										'order' => ($sortBy == 'is_active' && $order == 'desc') ? 'asc' : 'desc',
										$query_string
									),
								   array('class' => (($sortBy == 'is_active' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'is_active' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="15%">{{ trans("messages.global.action") }}</th>
					</tr>
				</thead>
				<tbody>
					@if(!$model->isEmpty())
					@foreach($model as $result)
					<tr>
						<td>
							@if($result->image != '' && File::exists(TESTIMONIAL_ROOT_PATH.$result->image))
								<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo TESTIMONIAL_URL.$result->image; ?>">
									<div class="usermgmt_image">
										<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=75px&height=75px&cropratio=1:1&image='.TESTIMONIAL_URL.'/'.$result->image ?>">
									</div>
								</a>
							@else
								<img class="img-circle" src="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg" style="height:75px;width:75px;">
							@endif
						</td> 
						<td data-th='{{ trans("messages.$modelName.client_name") }}'>
							{{ $result->client_name }}
						</td> 
						<td data-th='{{ trans("Location") }}'>
							{{ $result->location }}
						</td> 
						<td data-th='{{ trans("messages.$modelName.comment") }}'>
							{{ str_limit(strip_tags($result->comment),75) }}
							<?php
							/* @if(strlen($result->comment) < 150)
								{{ $result->comment }}
							@else
								{{ substr($result->comment,'0',150).'..'}}
							@endif */ ?>
						</td>
						<td  data-th='{{ trans("Order") }}'>
							<span style="color:#0088CC;cursor:pointer;" id="link_<?php echo $result->testimonial_order."_".$result->id ?>" onclick="change(this)">
								{{ $result->testimonial_order }}
							</span>
							<div id="change_div<?php echo $result->id ?>" style="display:none; ">
								{{ Form::text(
										'order_by', 
										$result->testimonial_order,
										['class'=>'form-control','id'=>'order_by_'.$result->id]
									) 
								}}
								<a class="btn btn btn-success"  id="link_<?php echo $result->testimonial_order."_".$result->id ?>" onclick="order(this)"  href="javascript:void(0);">
									<i class="fa fa-check"></i>
								</a>
							</div>
						</td>
						<td data-th='{{ trans("messages.global.status") }}'>
							@if($result->is_active	== 1)
								<span class="label label-success" >{{ trans("messages.global.activated") }}</span>
							@else
								<span class="label label-warning" >{{ trans("messages.global.deactivated") }}</span>
							@endif
						</td> 
						<td  data-th='{{ trans("messages.global.status") }}'>
							@if($result->is_active == 1)
								<a  title="Click To Deactivate" href='{{route("$modelName.status",array($result->id,0))}}' class="btn btn-success btn-small status_any_item"><span class="fa fa-ban"></span>
								</a>
							@else
								<a title="Click To Activate" href='{{route("$modelName.status",array($result->id,1))}}' class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
								</a> 
							@endif 
							
							<a title='{{ trans("messages.system_management.edit") }}' href='{{route("$modelName.edit","$result->id")}}' class="btn btn-primary">
								<i class="fa fa-pencil"></i>
							</a>
							
							<a title='{{ trans("messages.system_management.delete") }}' href='{{route("$modelName.delete","$result->id")}}' class="delete_any_item btn btn-danger">
								<i class="fa fa-trash-o"></i>
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
			<div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $model])</div>
		</div>
	</div>
</section>
{{ HTML::script('js/admin/lightbox.js') }}
<script type="text/javascript">
// when click on order by field value,button will appear to change the order by value
function change(obj){
	id_array		=	obj.id.split("_");
	current_id		=	id_array[2]; 
	current_order	=	id_array[1];
	order_by		=	$("#order_by_"+current_id).val();
	$("#change_div"+current_id).show();
	$("#link_"+current_order+"_"+current_id).hide();
	return false; 
 }
 
 // for update the orderby value
  function order(obj){
	id_array		=	obj.id.split("_");
	current_id		=	id_array[2]; 
	current_order	=	id_array[1]; 
	order_by		=	$("#order_by_"+current_id).val();
	$.ajax({
		headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		type: "POST",
		url: "<?php  echo route('Testimonial.change_order'); ?>",
		data: { current_id: current_id,current_order: current_order,order_by: order_by },
		success : function(res){
			if(res.success != 1) {
				bootbox.alert("'"+res.message+"'"); 
				return false; 
			}else{
			
			//$("#order_by_"+current_id).css({'border-color':'#CCCCCC'});
			$("#change_div"+current_id).hide();
			$("#link_"+current_order+"_"+current_id).html(res.order_by);
			$("#link_"+current_order+"_"+current_id).show();
				return true;
		}
	 }
	}) 
		return false; 
 }
 
</script>
@stop
