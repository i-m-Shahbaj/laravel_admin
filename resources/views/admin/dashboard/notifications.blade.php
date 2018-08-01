@extends('admin.layouts.default')
@section('content')
{{ HTML::style('css/admin/dashboard.css') }}
{{ HTML::style('css/admin/blogcomment.css') }}
<!-- Product Listing And Order Listing -->
<div class="row pad">
	<div class="col-lg-12 col-xs-12 col-sm-12">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject bold font-dark uppercase">Notifications</span>
				</div>
			</div>
			<div class="portlet-body">
				<div class="tabbable-line">
					<div class="table-responsive">
						<table class="table table-striped table-hover table-bordered">
							<thead>
								<tr>
									<th class="orderTh" > User Name </th>
									<th class="orderTh" > Blog </th>
									<th class="orderTh" > Comment </th>
									<th class="orderTh" > Date </th>
									<th class="orderTh" > Action </th>
								</tr>
							</thead>
							<tbody>
								@if(!($result)->isEmpty())
									@foreach($result as $record)
										<?php $data		=	json_decode($record->jsondata); 
										//pr($record);die;
										?>
										<tr>
											<td>{{ $record->full_name }} </td>
											<td>{{ $record->blog_name }} </td>
											<td>{{ $record->comment_name }} </td>
											<td> {{ $record->created_at }} </td>
											<td>
												<a href="{{URL::to('cmeshinepanel/blog/content/view-article/'.$record->project_folder_id.'/'.$data->blog_id)}}" class="btn btn-sm btn-default"><i class="fa fa-search"></i> View Detail </a>
											</td>
										</tr>
									@endforeach
								@else
									<tr>
										<td colspan="5" align="center"><label>No notification found.</label></td>
									</tr>
								@endif
							</tbody>
						</table>
					</div>
					<div class="box-footer clearfix">	
						<div class="col-md-3 col-sm-4 "></div>
						<div class="col-md-9 col-sm-8 text-right ">@include('pagination.default', ['paginator' => $result])	</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
