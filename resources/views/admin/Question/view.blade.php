@extends('admin.layouts.default')

@section('content')
 {{HTML::script('js/admin/vendors/match-height/jquery.equalheights.js') }}
<style>
.table.table-striped td {
    font-size: 14px;
}
.table.table-striped th {
    font-size: 14px;
}
.view{
	background-color:#3c3f44; color:white;"
}
.video_demo_item .fa.fa-play {
    background: black none repeat scroll 0 0;
    border: 1px solid black;
    border-radius: 50%;
    color: #fff;
    font-size: 23px;
    left: 134px;
    line-height: 16px;
    padding: 24px;
    position: relative;
    top: -90px;
}
</style>
{{ HTML::style('css/admin/magnific-popup.css') }}
{{ HTML::script('js/admin/jquery.magnific-popup.min.js') }}
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
		{{ trans("Question Details") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">Questions</a></li>
		<li class="active">{{ trans("Question Details") }}</li>
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
			<table class="table table-striped table-bordered" style="margin-top:10px;">
				<thead>
					<tr class="view">
						<th  width="30%" height="50%" class="" colspan="2" style="font-size:14px;">QUESTION DETAILS</th>
					</tr>
				</thead>
				
				<tr>
					<th width="30%" class="text-right txtFntSze">Category</th>
					<td >{{ $questionDetails->category_name }}</td>
				</tr>
				<tr>
					<th width="30%" class="text-right txtFntSze">Grade Level</th>
					<td >
						@if($questionDetails->question_grade_level == 1)
							All Age
						@else
							Min Age: {{ isset($questionDetails->minimum_age) ? $questionDetails->minimum_age :'' }}<br/>
							Max Age: {{ isset($questionDetails->maximum_age) ? $questionDetails->maximum_age :'' }}
						@endif
					</td>
				</tr>
				<tr>
					<th width="30%" class="text-right txtFntSze">Question</th>
					<td >{!! nl2br($questionDetails->question) !!}</td>
				</tr>
				<tr>
					<th width="30%" class="text-right txtFntSze">Question Options</th>
					<td>
						@if(!empty($questionDetails->question_option))
							<table class="table table-bordered" style="width:50%;margin-top:10px;">
								<tr>
									<th>Quetion Option</th>
									<th>Answer</th>
								</tr>
								@foreach($questionDetails->question_option as $questionOption)
									<tr>
										<td>{{$questionOption->question_option }}</td>
										
											<td>
												@if($questionOption->is_answer == 1)
													<a title="Correct Answer" href="javascript:void(0);"><i class="fa fa-check"></i></a>
												@endif
												
											</td>
										
									</tr>
								@endforeach
							</table>
						@endif
					</td>
				</tr>
				@if($questionDetails->question_image != '')
				<tr>
					<th width="30%" class="text-right txtFntSze">Question Image</th>
					<td >
						@if($questionDetails->question_image != '' && File::exists(QUESTION_IMAGE_ROOT_PATH.$questionDetails->question_image))
							<?php
								$question_image				=	QUESTION_IMAGE_URL.$questionDetails->question_image;
							?>
							<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo $question_image; ?>">
								<img src="<?php echo WEBSITE_URL.'image.php?height=80px&cropratio=1&image='.$question_image; ?>">
							</a>
						@endif
						
					</td>
				</tr>
				@endif
			</table>
		</div>
	</div>
	
</section>

@stop
