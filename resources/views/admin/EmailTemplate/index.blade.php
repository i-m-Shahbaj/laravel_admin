@extends('admin.layouts.default')

@section('content')
{{HTML::script('js/admin/vendors/match-height/jquery.equalheights.js') }}

<script type="text/javascript"> 
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
</script>

<section class="content-header">
	<h1>
	  Email Templates
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">Email Templates</li>
	</ol>
</section>

<section class="content"> 
	<div class="row">
		{{ Form::open(['method' => 'get','role' => 'form','route' => $modelName.'.index','class' => '']) }}
		{{ Form::hidden('display') }}
			<div class="col-md-3 col-sm-3">
				<div class="form-group ">  
					{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'form-control' , 'placeholder' => 'Name']) }}
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<div class="form-group "> 
					{{ Form::text('subject',((isset($searchVariable['subject'])) ? $searchVariable['subject'] : ''), ['class' => 'form-control' ,'placeholder' => 'Subject']) }}
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<button class="btn btn-primary"><i class='fa fa-search '></i> Search</button>
				<a href="{{route($modelName.'.index')}}"  class="btn btn-primary"><i class='fa fa-refresh '></i> Clear Search</a>
			</div>
		{{ Form::close() }}
		@if(Config::get('app.debug'))
			<div class="col-md-3 col-sm-3 ">
				<div class="form-group pull-right">  
					<a href="{{route($modelName.'.add')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.add_email_template") }} </a>
				</div>
			</div>
		@endif
	</div> 
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>
							<?php $nameimage = ($sortBy == 'name') ? ($sortBy == 'name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.'.index',
								 trans("messages.system_management.name").$nameimage ,
								array(
									'sortBy' => 'name',
									'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th>
							<?php $subjectimage = ($sortBy == 'subject') ? ($sortBy == 'subject' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.'.index',
								 trans("messages.system_management.subject").$subjectimage,
								array(
									'sortBy' => 'subject',
									'order' => ($sortBy == 'subject' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'subject' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'subject' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th>
							<?php $createdatimage = ($sortBy == 'created_at') ? ($sortBy == 'created_at' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.'.index',
								 trans("messages.system_management.created").$createdatimage,
								array(
									'sortBy' => 'created_at',
									'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th>{{ trans("messages.system_management.action") }}</th>
					</tr>
				</thead>
				<tbody id="powerwidgets">
					<?php
					if(!$result->isEmpty()){
					foreach($result as $record){?>
					<tr class="items-inner">
						<td>{{ $record->name }}</td>
						<td>{{ $record->subject }}</td>
						<td>{{ date(Config::get("Reading.date_format"),strtotime($record->created_at)) }}</td>
						<td>
							<a title="Edit" href="{{route($modelName.'.edit',$record->id)}}" class ="btn btn-primary" >
								<span class="fa fa-pencil"></span>
							</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php }else{ 
				?>
					 <tr>
						<td align="center" style="text-align:center;" colspan="4" > No Result Found</td>
					  </tr>
				<?php
			} ?>
			</table>
		</div>
		<div class="box-footer clearfix">	
			<div class="col-md-3 col-sm-4 "></div>
			<div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $result])</div>                                  
		</div>
	</div>
</section> 
@stop

