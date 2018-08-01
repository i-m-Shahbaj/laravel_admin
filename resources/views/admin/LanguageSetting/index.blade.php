@extends('admin.layouts.default')
@section('content')
<script>
	$(function(){
		/**
		 * Function to change status
		 *
		 * @param null
		 *
		 * @return void
		 */
		$(document).on('click', '.default_any_item', function(e){ 
			e.stopImmediatePropagation();
			url = $(this).attr('href');
			bootbox.confirm("Are you sure want to make default this language ?",
			function(result){
				if(result){
					window.location.replace(url);
				}
			});
			e.preventDefault();
		});
	});
</script>
<script type="text/javascript">
$(function(){
	/**
	 * Function to edit string
	 *
	 * @param null
	 *
	 * @return void
	 */
	$("a.edit_button").click(function(e){ 
		var btn			=	$(this);
		btn.button('loading');
		var id			=	this.id.replace('edit_','');
		var save_url	=	this.href; 
		$("#actual_div_"+id).hide();
		$("#edit_div_"+id).show();
		$.ajax({
			url:this.href,
			success:function(r){ 
				btn.button('reset');
				$("#edit_div_"+id).empty().html(r);
				$("#edit_div_"+id).find("#cancel").click(function(e){
					$("#actual_div_"+id).show();
					$("#edit_div_"+id).hide();
					return false;
				});
				$("#edit_div_"+id).find("#editgroup").click(function(e){ 
					$("#editgroup").button('loading');
					if($("#edit_msgstr").val()==''){
						$("#edit_msgstr").css( {'color':'#EE5F5B','border-color':'#EE5F5B'});
						$("#editgroup").button('reset');
						return false;
					}else{  
						var msg =  $("#edit_msgstr").val(); 
						$.ajax({ 
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},	
							url:"{{URL('language-settings/edit-setting/')}}"+"/"+id,
							type: "POST",
							data: {'id':id,'msgstr':msg},
							success: function(r){ 
								window.location.href	=	window.location.href;
								return false;
							}
						});	
					}
				});
			}
		});
		return false;
	});
});
</script>
<section class="content-header">
	<h1>
		{{ trans("Language Settings") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">{{ trans("Language Settings") }}</li>
	</ol>
</section>
<section class="content"> 
	<div class="row">
		{{ Form::open(['method' => 'get','role' => 'form','route' => "$modelName.index",'class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="col-md-3 col-sm-3">
			<div class="form-group ">  
				{{ Form::text('msgid',((isset($searchVariable['msgid'])) ? $searchVariable['msgid'] : ''), ['class' => 'form-control','placeholder'=>"Title"]) }}
			</div>
		</div>
		<div class="col-md-3 col-sm-3">
			<div class="form-group ">  
				{{ Form::text('msgstr',((isset($searchVariable['msgstr'])) ? $searchVariable['msgstr'] : ''), ['class' => 'form-control','placeholder'=>"String"]) }}
			</div>
		</div>
		<div class="col-md-4 col-sm-4">
			<button class="btn btn-primary"><i class='fa fa-search '></i> {{ trans('messages.search.text') }}</button>
			<a href='{{route("$modelName.index")}}'  class="btn btn-primary"><i class='fa fa-refresh '></i> {{ trans("messages.reset.text") }}</a>
		</div>
		{{ Form::close() }}
		<div class="col-md-5 col-sm-5 ">
			<div class="form-group pull-right">  
				<a href='{{route("$modelName.add")}}' class="btn btn-success btn-small align">{{ trans("Add Language") }} </a>
			</div>
		</div>
	</div>
	<div class="box">
		<div class="box-body ">
			<table class="table table-hover">
				<thead>
					<tr>
					<th width="30%">
					{{
						link_to_route(
							"$modelName.index",
							trans("Title"),
							array(
								'sortBy' => 'msgid',
								'order' => ($sortBy == 'msgid' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'msgid' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'msgid' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					</th>
					<th width="25%">
						{{
							link_to_route(
								"$modelName.index",
								trans("String"),
								array(
									'sortBy' => 'msgstr',
									'order' => ($sortBy == 'msgstr' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'msgstr' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'msgstr' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
						
					<th width="25%">
						{{
							link_to_route(
								"$modelName.index",
								trans("Language Code"),
								array(
									'sortBy' => 'locale',
									'order' => ($sortBy == 'locale' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'locale' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'locale' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="25%">{{ trans("Action") }}</th>
					</tr>
			</thead>
			<tbody id="powerwidgets">
				@if(!$result->isEmpty())
				@foreach($result as $results) <?php ?>
					<tr class="items-inner">
						<td data-th='{{ trans("Title") }}'>{{ $results->msgid }}</td>
						
						<td data-th='{{ trans("String") }}'>
								<div id="actual_div_<?php echo $results->id; ?>">
										{{ $results->msgstr }}
								</div>
								<div style="display:none;" id="edit_div_<?php echo $results->id; ?>">
												&nbsp;
								</div>
						
						</td>
							
						<td data-th='{{{ trans("messages.$modelName.language_code") }}}'>{{{ $results->locale }}}</td>
						<td>
						<a title="Edit" href='{{route("$modelName.update","$results->id")}}' class="edit_button btn btn-primary"
						id="edit_<?php echo $results->id?>">Edit</span>
						
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td class="alignCenterClass" colspan="5" >{{ trans("messages.user_management.no_record_found_message") }}</td>
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
@stop
