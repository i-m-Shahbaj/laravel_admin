@extends('admin.layouts.default')
@section('content')
<script type="text/javascript">
	var action_url = '<?php echo route("$modelName.Multipleaction"); ?>';
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
			success : function(r){
				$("#getting_basic_list_popover").html(r);
				$("#getting_basic_list_popover").modal('show');
			}
		});
	}
	
</script>
<section class="content-header">
	<h1>
	  {{ trans("Block Management") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active"> {{ trans("Block Management") }}</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		{{ Form::open(['method' => 'get','role' => 'form','route' => "$modelName.index",'class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
			<div class="col-md-3 col-sm-3">
				<div class="form-group ">  
					{{ Form::text('page_name',((isset($searchVariable['page_name'])) ? $searchVariable['page_name'] : ''), ['class' => ' form-control','placeholder'=>'Page Name']) }}
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<div class="form-group ">  
					{{ Form::text('block_name',((isset($searchVariable['block_name'])) ? $searchVariable['block_name'] : ''), ['class' => 'form-control','placeholder'=>'Block Name']) }}
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<button class="btn btn-primary"><i class='fa fa-search '></i> Search</button>
				<a href='{{ route("$modelName.index")}}'  class="btn btn-primary"> <i class="fa fa-refresh "></i> {{ trans('Clear Search') }}</a>
			</div>
			
			<div class="col-md-3 col-sm-3 ">
				@if(Config::get('app.debug'))
					<a href='{{route("$modelName.add")}}'  class="btn btn-success btn-small align pull-right"> {{ trans("messages.$modelName.add_new") }} </a>
				@endif
			</div>
		{{ Form::close() }}
	
	</div>
	<div class="box">
		<div class="box-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<!--<th width="5%"></th>-->
						<th width="12%">
							<?php $pagenameimage = ($sortBy == 'page_name') ? ($sortBy == 'page_name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
									"$modelName.index",
									trans("messages.$modelName.page_name").$pagenameimage,
									array(
									'sortBy' => 'page_name',
									'order' => ($sortBy == 'page_name' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
									),
									array('class' => (($sortBy == 'page_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'page_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="15%">
							<?php $blocknameimage = ($sortBy == 'block_name') ? ($sortBy == 'block_name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
									"$modelName.index",
									trans("messages.$modelName.block_name").$blocknameimage,
									array(
									'sortBy' => 'block_name',
									'order' => ($sortBy == 'block_name' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
									),
									array('class' => (($sortBy == 'block_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'block_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="20%">
							<?php $descriptionimage = ($sortBy == 'description') ? ($sortBy == 'description' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
									"$modelName.index",
									trans("messages.$modelName.description").$descriptionimage,
									array(
									'sortBy' => 'description',
									'order' => ($sortBy == 'description' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
									),
									array('class' => (($sortBy == 'description' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'description' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="10%">
							{{ 'Image' }}
						</th>
						<th width="10%">
							<?php $orderimage = ($sortBy == 'block_order') ? ($sortBy == 'block_order' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
									"$modelName.index",
									trans("Order").$orderimage,
									array(
									'sortBy' => 'block_order',
									'order' => ($sortBy == 'block_order' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
									),
									array('class' => (($sortBy == 'block_order' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'block_order' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="10%">
							<?php $createdatimage = ($sortBy == 'created_at') ? ($sortBy == 'created_at' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
									"$modelName.index",
									trans("messages.$modelName.created_at").$createdatimage,
									array(
									'sortBy' => 'created_at',
									'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
									),
									array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="5%">
							<?php $isactiveimage = ($sortBy == 'is_active') ? ($sortBy == 'is_active' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								"$modelName.index",
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
						<th width="15%">{{ trans("messages.global.action") }}</th>
					</tr>
				</thead>
					<tbody id="powerwidgets">
						@if(!$model->isEmpty())
							@foreach($model as $result)
							<?php
								/* echo '<pre>';
								print_r($result);die; */
							?>
								<tr class="items-inner">
									<!--<td data-th='{{ trans("messages.$modelName.select") }}'>
										{{ Form::checkbox('status',$result->id,null,['class'=> 'userCheckBox'] )}}
									</td>-->
									<td data-th='{{ trans("messages.$modelName.page_name") }}'>{{ $result->page_name }}</td>
									<td data-th='{{ trans("messages.$modelName.block_name") }}'>{{ strip_tags($result->block_name) }}</td>
									<td data-th='{{ trans("messages.$modelName.description") }}'>{{ str_limit(strip_tags($result->description),100) }}</td>
									<td data-th='{{ trans("Image") }}'>
										@if($result->image != '' && File::exists(BLOCK_ROOT_PATH.$result->image))
											<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo BLOCK_URL.$result->image; ?>">
												<div class="usermgmt_image">
													<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=75px&height=75px&cropratio=1:1&image='.BLOCK_URL.'/'.$result->image ?>">
												</div>
											</a>
										@else
											<img class="img-circle" src="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg" style="height:75px;width:75px;">
										@endif
									</td>
									<td  data-th='{{ trans("Order") }}'>
										<span style="color:#0088CC;cursor:pointer;" id="link_<?php echo $result->block_order."_".$result->id ?>" onclick="change(this)">
											{{ $result->block_order }}
										</span>
											<div id="change_div<?php echo $result->id ?>" style="display:none; ">
												{{ Form::text(
														'order_by', 
														$result->block_order,
														['class'=>'form-control','id'=>'order_by_'.$result->id]
													) 
												}}
												<a class="btn btn btn-success"  id="link_<?php echo $result->block_order."_".$result->id ?>" onclick="order(this)"  href="javascript:void(0);">
													<i class="fa fa-check"></i>
												</a>
											</div>
									</td>
									
									<td data-th='{{ trans("messages.$modelName.created_at") }}'>{{ 		date(Config::get("Reading.date_format") , strtotime($result->created_at)) }}</td>
									<td  data-th='{{ trans("messages.$modelName.status") }}'>
										@if($result->is_active	== 1)
											<span class="label label-success" >{{ trans("messages.global.activated") }}</span>
										@else
											<span class="label label-warning" >{{ trans("messages.global.deactivated") }}</span>
										@endif
									</td>
									<td data-th='{{ trans("messages.global.action") }}'>
										@if($result->is_active == 1)
											<a  title="Click To Deactivate" href='{{route("$modelName.status",array($result->id,0))}}' class="btn btn-success btn-small status_any_item"><span class="fa fa-ban"></span>
											</a>
										@else
											<a title="Click To Activate" href='{{route("$modelName.status",array($result->id,1))}}' class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
											</a> 
										@endif 
										<a href='{{route("$modelName.edit","$result->id")}}' class="btn btn-primary" title="Edit"> <span class="fa fa-pencil"></span></a>
										@if(Config::get('app.debug'))
											<a href='{{route("$modelName.delete","$result->id")}}' data-delete="delete" class="delete_any_item btn btn-danger" title="Delete">
											<span class="fa fa-trash-o"></span>
											</a>
										@endif
									</td>
								</tr>
							@endforeach  
							@else
							<tr>
								<td colspan="8" class="alignCenterClass"> {{ trans("messages.global.no_record_found_message") }}</td>
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
		url: "<?php  echo route('Block.changeOrder'); ?>",
		data: { current_id: current_id,current_order: current_order,order_by: order_by },
		success : function(res){
			if(res.success != 1) {
				alert(res.message); return false; 
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
</script>
@stop

