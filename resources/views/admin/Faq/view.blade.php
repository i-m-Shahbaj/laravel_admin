@extends('admin.layouts.default')
@section('content')
<style>
.view {
    background-color: #3c3f44;
    color: white;
}
.table.table-striped th {
    font-size: 14px;
}
.table.table-striped td {
    font-size: 14px;
}
</style>
<section class="content-header">
	<h1>
		{{ trans("View FAQ Manager") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route('Faq.listFaq')}}">{{ trans("FAQ Manager") }}</a></li>
		<li class="active">{{ trans("View FAQ Manager") }}</li>
	</ol>
</section>
<section class="content"> 
<div class="row pad"> 
	<div class="col-md-12">
		<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
	</div> 
</div>
	<div class="row pad">
		<div class="col-md-12">	
			@if(count($languages) > 1)
				<div class="wizard-nav wizard-nav-horizontal">
					<ul class="nav nav-tabs">
						@foreach($languages as $value)
						<?php $i = $value -> id ; ?>
							<li class=" {{ ($i ==  $language_code )?'active':'' }}">
								<a data-toggle="tab" href="#{{ $i }}div">
									{{ $value -> title }}
								</a>
							</li>
						@endforeach
					</ul>
				</div>
			@endif
		</div>
	</div>
	<div class="row">
		@foreach($languages as  $key => $value)
		<div class="col-md-12 col-sm-6" id="{{  $value->id }}div" class="tab-pane fade {{ ( $value->id ==  $language_code )?'in active':'' }}">
			<table class="table table-striped table-bordered" style="margin-top:-10px;">
				<thead>
					<tr class="view">
						<th  width="30%" height="50%" class="" colspan="2" style="font-size:14px;">FAQ MANAGER DETAILS</th>
					</tr>
				</thead>
				<tbody>
				<tr>
					<th width="30%" class="text-right">
						Category
					</td>
					<td>
						{{ (isset($categorName))? $categorName :'' }}
						
					</td>
				</tr>
				<tr>
					<th width="30%" class="text-right">
						Question
					</td>
					<td>
						{{ (isset($multiLanguage[$value->id]['question']))? $multiLanguage[$value->id]['question'] :'' }}
						
					</td>
				</tr>
				<tr>
					<th width="30%" class="text-right">
						Answer
					</td>
					<td>
						{{ isset($multiLanguage[$value->id]['answer'])? $multiLanguage[$value->id]['answer']:'' }}
					</td>
				</tr>
				<tr>
					<th width="30%" class="text-right">
						Status
					</td>
					<td>
						@if($AdminFaq->is_active)
							<label class="label label-success">Activated</label>
						@else
							<label class="label label-warning">Deactivated</label>
						@endif
					</td>
				</tr>
				
				</tbody>
			</table>
		</div>
		@endforeach
	</div>	
</section>
@stop
