@extends('admin.layouts.default')

@section('content')
<section class="content-header">
	<h1>
		{{ trans("View Project") }} 
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href='{{route("$modelName.index")}}'>{{ trans("Library Management") }}</a></li>
		<li class="active">{{ trans("View Project") }}</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12 col-sm-6">
			<div class="row pad">
				<div class="col-md-12 col-sm-12" >	
					<div class="">
						<div id="info1"></div>
							<div class="box-body" style="display: block;">  
								<div>
									@if(!empty($model))
									@foreach($model as $model)
									<table class="table table-striped table-bordered" style="margin-top:10px;">
										<thead>
											<tr class="view bgcss">
												<th  width="30%" height="50%" class="" colspan="3" style="font-size:20px;">
													
												<span style="float:left;">PROJECT LIBRARY DETAILS</span>
												
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th  width="30%" class="text-right txtFntSze" >{{ trans("Author Name") }}</th>
												<td colspan="2" data-th='Name'>{{ $model->author }}</td>
											</tr>
											<tr>
												<th  width="30%" class="text-right txtFntSze" >{{ trans("Project Name") }}</th>
												<td colspan="2" data-th='Name'>{{ $model->project_name }}</td>
											</tr>
											<tr>
												<th  width="30%" class="text-right txtFntSze" >{{ trans("Authrized Group") }}</th>
												<td colspan="2" data-th='Name'>{{ $model->author_group }}</td>
											</tr>
											<tr>
												<th width="30%" class="text-right txtFntSze">{{ trans("messages.global.created") }}</th>
												<td colspan="2" data-th='Name'>
													{{ date(Config::get("Reading.date_format") , strtotime($model->created_at)) }}</td>
											</tr>
										</tbody>
									</table>
									@endforeach
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>
		<div class="col-md-12 col-sm-6">
			<div class="row pad">
				<div class="col-md-12">
				<h3 class="view bgcss" ><span style="font-size:20px;padding: 3px 8px !important;font-family: 'Source Sans Pro', sans-serif;font-weight: bold;">Folders</span></h3>
				@if(!($model->project_folder)->isEmpty())
					@foreach($model->project_folder as $projectMainFolder)
					<div class="panel-group" style="margin:10px 0;" >
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title"  style="font-size:23px;">
									<b>{{ $projectMainFolder->name	}}</b>
								</h4>
							</div>
						
						<div style="padding-bottom:10px;padding-left:10px;">
							@if(!empty($model->project_sub_folder))
								@foreach($model->project_sub_folder as $projectSubFolder)
								@if($projectSubFolder->parent_id == $projectMainFolder->id)
								<div class="panel-group" style="margin:10px 10px;" >
									<div class="panel panel-default">
										<div class="panel-heading" style="border:none;">
											<h4 class="panel-title">
												<a data-toggle="collapse" id="quick_button_id_{{$projectSubFolder->id}}" href="#quick_button_{{$projectSubFolder->id}}" style="font-size:23px;"><b>{{ $projectSubFolder->name	}}</b></a>
												
												<div class="pull-right" onclick="$('#quick_button_id_{{$projectSubFolder->id}}').trigger('click');" style="cursor: pointer;padding-top: 5px;"><i class="fa fa-plus"></i></div>
											</h4>
											
										</div>
										<div class="clearfix"></div>
										<div id="quick_button_{{$projectSubFolder->id}}" class="panel-collapse collapse " style="float:left;width:100%; background: #fff; border: 1px solid rgb(221, 221, 221);">
											<div style="padding-bottom:10px;padding-left:10px; padding-right: 10px;">
												<table class="table table-striped table-bordered" style="margin: 10px 0px 0px 0px;">
													<thead>
														<tr class="view bgcss">
															<th  width="30%" height="50%" class="" colspan="3" style="font-size:20px;">
																
															<span style="float:left;">ARTICLES</span>
															
															</th>
														</tr>
													</thead>
													<thead>
														<tr>
															<th width="30%">
																{{ trans("Name") }}
															</th>
															<th width="30%">
																{{ trans("Created at") }}
															</th>
															<th width="">
																{{ trans("Action") }}
															</th>
														</tr>
													</thead>
													<tbody>
														@if(!($model->project_articles)->isEmpty())
															@foreach($model->project_articles as $key=>$projectArticles)
																@if($projectArticles->project_folder_id == $projectSubFolder->id)
																<tr>
																	<td data-th='Name'>{{ $projectArticles->article_name }}</td>
																	<td data-th='Name'>{{ $projectArticles->created_at }}</td>
																	<td>
																		<a href='{{ route("ProjectFolderArticle.view",array("$model->id","$projectSubFolder->id","$projectArticles->id"))}}' class="btn btn-info" title="View"> <i class="fa fa-eye"></i> </a>
																	</td>
																</tr>
																<?php unset($model->project_articles[$key]); ?>
																@endif
															@endforeach
														@else
															<tr>
																<td colspan="3" class="text-center">{{ trans('No Record Found') }}</td>
															</tr>
														@endif
													</tbody>
												</table>
											</div>	
										</div>	
									</div>	
								</div>	
								@endif
								@endforeach
							@endif
						</div>	
						</div>	
					@endforeach
				@else
					<div class="panel-group" style="margin:10px 0;" >
							<div class="panel panel-default">
								<div class="panel-body">
									<div>{{ trans('No Folder Found') }}</div>
								</div>
							</div>
					</div>
				@endif	
					</div>
			</div>
		</div>
	</div>
</section>
<style>
	.txtFotnSize{
		font-size:14px !important;
	}
	.bgcss{
		background-color:#3c3f44; 
		color:white;
	}
	.error{
		color:red;
	}
	
</style>
@stop
