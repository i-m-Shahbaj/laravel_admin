@extends('admin.layouts.default')
@section('content')

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
	  {{ trans("Challenges") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">{{ trans("Challenges") }}</li>
	</ol>
</section>
<section class="content"> 
	<div class="row">
		{{ Form::open(['role' => 'form','route' => $modelName.".index",'class' => 'mws-form',"method"=>"get"]) }}
		{{ Form::hidden('display') }}
			<div class="col-md-2 col-sm-2">
				<div class="form-group ">  
					{{ Form::text('sponsor_name',((isset($searchVariable['sponsor_name'])) ? $searchVariable['sponsor_name'] : ''), ['class' => 'form-control','placeholder'=>'Sponsor Name']) }}
				</div>
			</div>
			<div class="col-md-2 col-sm-2">
				<div class="form-group ">  
					{{ Form::text('challenge_name',((isset($searchVariable['challenge_name'])) ? $searchVariable['challenge_name'] : ''), ['class' => 'form-control','placeholder'=>'Challenge Name']) }}
				</div>
			</div>
			<?php /*<div class="col-md-2 col-sm-2">
				<div class="form-group ">  
					{{ Form::text("from", ((isset($dateS)) ? $dateS : ''),['class' => 'form-control date_from dt_frms_ico','placeholder'=>trans("Date From")]) }}
				</div>
			</div>
			<div class="col-md-2 col-sm-2">
				<div class="form-group ">  
					{{ Form::text("to", ((isset($dateE)) ? $dateE : ''),['class' => 'form-control date_to dt_frms_ico ','placeholder'=>trans("Date To")]) }}
				</div>
			</div> */ ?>
			<div class="col-md-2 col-sm-2">
				<div class="form-group ">  
					{{ Form::select('is_active',array(''=>trans('Select Status'),0=>'Inactive',1=>'Active'),((isset($searchVariable['is_active'])) ? $searchVariable['is_active'] : ''), ['class' => 'form-control chosen-select']) }}
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<div class="form-group">  
					<button class="btn btn-primary"><i class='fa fa-search '></i> {{ trans('messages.search.text') }}</button>
					<a href="{{route($modelName.'.index')}}"  class="btn btn-primary"><i class='fa fa-refresh '></i> {{ trans("Clear Search") }}</a>
				</div>
			</div>
		{{ Form::close() }}
	
		<div class="col-md-3 col-sm-3 pull-right">
			<div class="form-group pull-right">  
				<a href="{{route($modelName.'.add')}}" class="btn btn-success btn-small align">{{ trans("Add Challenge") }} </a>
			</div>
		</div>
	</div> 
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
						<th width="15%">
							<?php $challengeimage = ($sortBy == 'image') ? ($sortBy == 'image' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								"$modelName.index",
								trans("Image").$challengeimage,
								array(
									'sortBy' => 'image',
									'order' => ($sortBy == 'image' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'image' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'image' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="15%">
							<?php $sponsortypeimage = ($sortBy == 'sponsor_name') ? ($sortBy == 'sponsor_name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								"$modelName.index",
								trans("Sponsor Name").$sponsortypeimage,
								array(
									'sortBy' => 'sponsor_name',
									'order' => ($sortBy == 'sponsor_name' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'sponsor_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'sponsor_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="15%">
							<?php $usertypeimage = ($sortBy == 'challenge_name') ? ($sortBy == 'challenge_name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								"$modelName.index",
								trans("Challenge Name").$usertypeimage,
								array(
									'sortBy' => 'challenge_name',
									'order' => ($sortBy == 'challenge_name' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'challenge_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'challenge_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="12%">
							<?php $startDateimage = ($sortBy == 'start_date') ? ($sortBy == 'start_date' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.".index",
								trans("Start Date").$startDateimage,
								array(
									'sortBy' => 'start_date',
									'order' => ($sortBy == 'start_date' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'start_date' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'start_date' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="10%">
							<?php $endDateimage = ($sortBy == 'end_date') ? ($sortBy == 'end_date' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.".index",
								trans("End Date").$endDateimage,
								array(
									'sortBy' => 'end_date',
									'order' => ($sortBy == 'end_date' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'end_date' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'end_date' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
							
						</th>
						<th width="15%">
							<?php $statusimage = ($sortBy == 'is_active') ? ($sortBy == 'is_active' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.".index",
								trans("Status").$statusimage,
								array(
									'sortBy' => 'is_active',
									'order' => ($sortBy == 'is_active' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'is_active' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'is_active' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="25%">
							Action
						</th>
					</tr>
				</thead>
				@if(!$result->isEmpty())
					@foreach($result as $key => $record)
						<tr>
							<td>
								@if($record->image != '' && File::exists(CHALLENGE_IMAGE_ROOT_PATH.$record->image))
									<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo CHALLENGE_IMAGE_URL.$record->image; ?>">
										<div class="usermgmt_image">
											<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1&image='.CHALLENGE_IMAGE_URL.'/'.$record->image ?>">
										</div>
									</a>
								@else
									<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg">
										<div class="usermgmt_image">
											<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1&image='.WEBSITE_IMG_URL ?>admin/no_image.jpg">
										</div>
									</a>
								@endif
							</td>
							
							<td>
								{{ $record->sponsor_name }}
							</td>
							<td>
								{{ $record->challenge_name }}
							</td>
							<td>
								{{ $record->start_date }}
							</td>
							<td>
								{{ $record->end_date }}
							</td>
							
							<td>
								@if($record->is_active	==1)
									<span class="label label-success" >{{ trans("messages.user_management.activated") }}</span>
								@else
									<span class="label label-warning" >{{ trans("messages.user_management.deactivated") }}</span>
								@endif
								
							</td>
							<td>
								@if($record->is_active == 1)
									<a  title="Click To Deactivate" href="{{route($modelName.'.status',array($record->id,0))}}" class="btn btn-success btn-small status_any_item"><span class="fa fa-ban"></span>
									</a>
								@else
									<a title="Click To Activate" href="{{route($modelName.'.status',array($record->id,1))}}" class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
									</a> 
								@endif 
								
								<a href="{{route($modelName.'.view',$record->id)}}" title="{{ trans('messages.global.view') }}" class="btn btn-info">
									<i class="fa fa-eye"></i>
								</a>
									
								<a title="{{ trans('messages.global.edit') }}" href="{{route($modelName.'.edit',$record->id)}}" class="btn btn-primary">
									<i class="fa fa-pencil"></i>
								</a>
								<a title="{{ trans('messages.global.delete') }}" href="{{ route($modelName.'.delete',$record->id) }}"  class="delete_any_item btn btn-danger">
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
			</table>
		</div>
		<div class="box-footer clearfix">	
			<div class="col-md-3 col-sm-4 "></div>
			<div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $result])</div>
		</div>
	</div> 
</section> 

<!-- datetime picker js and css start here-->
{{ HTML::script('js/admin/jquery-ui-1.9.2.min.js') }}
{{ HTML::script('js/admin/jquery-ui-timepicker.min.js') }}
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
<!-- date time picker js and css and here-->
<script type="text/javascript">
	
$(document).ready(function() {
	 $( ".date_from" ).datepicker({
		dateFormat 	: 'yy-mm-dd',
		changeMonth : true,
		changeYear 	: true,
		yearRange	: '-100y:c+nn',
		onSelect	: function( selectedDate ){ $(".date_to").datepicker("option","minDate",selectedDate); }
	});
	$( ".date_to" ).datepicker({
		dateFormat 	: 'yy-mm-dd',
		changeMonth : true,
		changeYear 	: true,
		yearRange	: '-100y:c+nn',
		onSelect	: function( selectedDate ){ $(".date_from").datepicker("option","maxDate",selectedDate); }
	});
})

</script>
@stop
