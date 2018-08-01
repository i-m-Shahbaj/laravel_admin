@extends('admin.layouts.default')

@section('content')
<section class="content-header">
	<h1>
		{{ trans("View Library") }} 
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href='{{route("$modelName.index")}}'>Blog Management</a></li>
		<li class="active">{{ trans("View Category") }}</li>
	</ol>
</section>
<section class="content">
	<div class="row pad"> 
		<div class="col-md-12">
			<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
		</div> 
	</div>
	<div class="row">
		<div class="col-md-12 col-sm-6">
			<div class="row pad">
				<div class="col-md-12 col-sm-12" >	
					<div class="">
						<div id="info1"></div>
							<div class="box-body" style="display: block;">  
								<div>
									<table class="table table-striped table-bordered" style="margin-top:10px;">
										<thead>
											<tr class="view bgcss">
												<th  width="30%" height="50%" class="" colspan="3" style="font-size:20px;">
													
												<span style="float:left;">CATEGORY DETAILS</span>
												
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th  width="30%" class="text-right txtFntSze" >{{ trans("Name") }}</th>
												<td colspan="2" data-th='Challenge Name'>{{ isset($model->name) ? $model->name:''  }}</td>
											</tr>
											
											<tr>
												<th  width="30%" class="text-right txtFntSze" >{{ trans("messages.global.created") }}</th>
												<td colspan="2" data-th='Start Date'>{{ date(Config::get("Reading.date_format") , strtotime($model->created_at)) }}</td>
											</tr>
											
										</tbody>
									</table>
								</div>
							</div>
								
					</div>
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
