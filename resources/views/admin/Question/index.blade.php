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
		<h1>Question Management</h1>
		<ol class="breadcrumb">
			<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Question Management</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				{{ Form::open(['method' => 'get','role' => 'form','route' => "$modelName.index",'class' => 'mws-form']) }}
				{{ Form::hidden('display') }}
					<div class="col-md-3 col-sm-3">
						<div class="form-group ">  
							{{Form::select('question_category_id',array(''=>'Select Category ')+$questionCategory,((isset($searchVariable['question_category_id'])) ? $searchVariable['question_category_id'] : ''),['class' => 'chosen-select category_list form-control']) }}
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="form-group "> 
							{{ Form::text('question',((isset($searchVariable['question'])) ? $searchVariable['question'] : ''), ['class' => 'form-control','placeholder'=>'Question',"id"=>"senteces_name"]) }}
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="form-group "> 
							{{ Form::text('question_grade_level',((isset($searchVariable['question_grade_level'])) ? $searchVariable['question_grade_level'] : ''), ['class' => 'form-control','placeholder'=>'Grade Level',"id"=>"question_grade_level"]) }}
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<div class="form-group ">  
							{{ Form::select('is_active',array(''=>trans('Select Status'),0=>'Inactive',1=>'Active'),((isset($searchVariable['is_active'])) ? $searchVariable['is_active'] : ''), ['class' => 'form-control chosen-select']) }}
						</div>
					</div>
					<div class="col-md-3 col-sm-3">
						<button class="btn btn-primary"><i class='fa fa-search '></i> Search</button>
						<a href="{{route($modelName.'.index')}}"  class="btn btn-primary"><i class='fa fa-refresh '></i> Clear Search</a>
					</div>
				{{ Form::close() }}
					
				<div class="col-md-3 col-sm-3  pull-right">
					<div class="form-group pull-right">
						<a href="{{route($modelName.'.add')}}" class="btn btn-success btn-small align">{{ trans("Add New Question") }} </a>
					</div>
				</div>
			</div>
		</div> 
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
					
						<th width="15%">
							<?php $categoryimage = ($sortBy == 'category_name') ? ($sortBy == 'category_name' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.".index",
								trans("Category").$categoryimage,
								array(
									'sortBy' => 'category_name',
									'order' => ($sortBy == 'category_name' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'category_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'category_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="15%">
							<?php $gradeLevelimage = ($sortBy == 'question_grade_level') ? ($sortBy == 'question_grade_level' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.".index",
								trans("Grade Level").$gradeLevelimage,
								array(
									'sortBy' => 'question_grade_level',
									'order' => ($sortBy == 'question_grade_level' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'question_grade_level' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'question_grade_level' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="25%">
							<?php $questionimage = ($sortBy == 'question') ? ($sortBy == 'question' && $order == 'desc') ? '<img src="'.WEBSITE_IMG_URL.'down-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-arrow.png'.'" width="12px" height="12px">' : '<img src="'.WEBSITE_IMG_URL.'up-down-arrow.png'.'" width="12px" height="12px">'; ?>
							{!!
								html_entity_decode(link_to_route(
								$modelName.".index",
								trans("Question").$questionimage,
								array(
									'sortBy' => 'question',
									'order' => ($sortBy == 'question' && $order == 'desc') ? 'asc' : 'desc',
									$query_string
								),
								array('class' => (($sortBy == 'question' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'question' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
								))
							!!}
						</th>
						<th width="10%">
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
						<th width="20%">{{ trans("messages.system_management.action") }}</th>
					</tr>
				</thead>
				<tbody id="powerwidgets">
					@if(!$result->isEmpty())
					@foreach($result as $record)
					<tr class="items-inner">
						
						<td data-th='{{ trans("messages.system_management.name") }}'>{{ $record->category_name }}</td> 
						<td data-th='{{ trans("messages.system_management.name") }}'>
							@if($record->question_grade_level==1)
								All Age
							@else
								Min Age: {{ isset($record->minimum_age) ? $record->minimum_age :'' }}<br/>
								Max Age: {{ isset($record->maximum_age) ? $record->maximum_age :'' }}
							@endif
						</td> 
						<td data-th='{{ trans("messages.system_management.name") }}'>
							<span>{{ strip_tags(Str::limit($record->question,400)) }}
							@if((strlen($record->question))>400)
							 <a class="question_{{$record->id}}" href="javascript:void(0);"> Read More</a></span>
								<span style="display:none;">{{ strip_tags($record->question) }}
								<a class="questionhide_{{$record->id}}" href="javascript:void(0);">Hide</a></span>
							@endif
						</td>
						
						
						
						<td data-th='{{ trans("messages.system_management.status") }}'>
							@if($record->is_active	== 0)
								<span class="label label-warning" >{{ trans("messages.user_management.deactivated") }}</span>
							@else
								<span class="label label-success" >{{ trans("messages.user_management.activated") }}</span>
							@endif
						</td>
						<td data-th='{{ trans("messages.system_management.action") }}'>
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
						<td align="center" style="text-align:center;" colspan="9" > No Result Found</td>
					  </tr>
					@endif 
				</tbody>
			</table>
		</div>
		<div class="box-footer clearfix">	
			<div class="col-md-3 col-sm-4 "></div>
			<div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $result])</div>                                  
		</div>
	</div>
</section>
<script>
	$(function(){
		$("[class^=questionhide_]").on("click",function(){
			var id	=	$(this).attr('class').replace('questionhide_','');
			$(".question_"+id).parent().show();
			$(".questionhide_"+id).parent().hide();
		});
		$("[class^=question_]").on("click",function(){
			var id	=	$(this).attr('class').replace('question_','');
			$(".question_"+id).parent().hide();
			$(".questionhide_"+id).parent().show();
		});
	});
	
</script>
@stop
