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
		 {{ trans("View Challenge") }} 
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">{{ trans("Challenges") }}</a></li>
		<li class="active"> {{ trans("View Challenge") }}  </li>
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
											<tr class="view">
												<th  width="30%" height="50%" class="" colspan="3" style="font-size:20px;">
													
												<span style="float:left;">CHALLENGE DETAILS</span>
												
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th  width="30%" class="text-right txtFntSze" >Sponsor Name</th>
												<td colspan="2" data-th='Name'>{{ isset($challengeDetails->sponsor_name) ? $challengeDetails->sponsor_name :''  }}</td>
											</tr>
											
											<tr>
												<th  width="30%" class="text-right txtFntSze" >Challenge Name</th>
												<td colspan="2" data-th='Challenge Name'>{{ isset($challengeDetails->challenge_name) ? $challengeDetails->challenge_name :''  }}</td>
											</tr>
											
											<tr>
												<th  width="30%" class="text-right txtFntSze" >Start Date</th>
												<td colspan="2" data-th='Start Date'>{{ isset($challengeDetails->start_date) ? $challengeDetails->start_date :''  }}</td>
											</tr>
											<tr>
												<th  width="30%" class="text-right txtFntSze" >End Date</th>
												<td colspan="2" data-th='End Date'>{{ isset($challengeDetails->end_date) ? $challengeDetails->end_date :''  }}</td>
											</tr>
											<tr>
												<th  width="30%" class="text-right txtFntSze" >Number of Questions</th>
												<td colspan="2" data-th='Number of Questions'>{{ isset($challengeDetails->no_of_questions) ? $challengeDetails->no_of_questions :''  }}</td>
											</tr>
											<tr>
												<th width="30%" class="text-right txtFntSze">Category</th>
												<td >{{ $challengeDetails->category_name }}</td>
											</tr>
											<tr>
												<th  width="30%" class="text-right txtFntSze" >Grade</th>
												<td colspan="2" data-th='Grade'>{{ isset($challengeDetails->grade_level) ? $challengeDetails->grade_level :''  }}</td>
											</tr>
											
											<tr>
												<th  width="30%" class="text-right txtFntSze" >Assign Dancers</th>
												<td colspan="2" data-th='Assign Dancers'>
													@if($challengeDetails->assign_dancer == 1)
														All Dancers
													@else
														Min Age: {{ isset($challengeDetails->minimum_age) ? $challengeDetails->minimum_age :'' }}<br/>
														Max Age: {{ isset($challengeDetails->maximum_age) ? $challengeDetails->maximum_age :'' }}
													@endif
												</td>
											</tr>
											<tr>
												<th  width="30%" class="text-right txtFntSze" >How many winners</th>
												<td colspan="2" data-th='Grade'>{{ isset($challengeDetails->how_many_winners) ? $challengeDetails->how_many_winners :''  }}</td>
											</tr>
											<tr>
												<th  width="30%" class="text-right txtFntSze" >Description</th>
												<td colspan="2" data-th='Leaderboards'>{!! !empty($challengeDetails->description)?nl2br($challengeDetails->description):''  !!}</td>
											</tr>
											<tr>
												<th  width="30%" class="text-right txtFntSze" >Terms & Conditions</th>
												<td colspan="2" data-th='Leaderboards'>{{ !empty($challengeDetails->term_condition)?$challengeDetails->term_condition:''  }}</td>
											</tr>
											<tr>
												<th  width="30%" class="text-right txtFntSze" >Image</th>
												<td colspan="2" data-th='Leaderboards'>
													@if($challengeDetails->image != '' && File::exists(CHALLENGE_IMAGE_ROOT_PATH.$challengeDetails->image))
														<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo CHALLENGE_IMAGE_URL.$challengeDetails->image; ?>">
															<div class="usermgmt_image">
																<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1&image='.CHALLENGE_IMAGE_URL.'/'.$challengeDetails->image ?>">
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
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="box-body" style="display: block;">  
								<div>
									<table class="table table-striped table-bordered" style="margin-top:10px;">
										<thead>
											<tr class="view">
												<th  width="30%" height="50%" class="" colspan="6" style="font-size:20px;">
												<span style="float:left;">PRIZE DETAILS</span>
												</th>
											</tr>
										</thead>
										<thead>
											<tr>
												<th width="15%">
													Prize Name
												</th>
												<th width="15%">
													Description
												</th>
												<th width="20%">
													Image
												</th>
											</tr>
										</thead>
										<?php //echo "<pre>";print_r($dancerDetails);die; ?>
										<tbody>
										@if(!($challengePrizes)->isEmpty())
											@foreach($challengePrizes as $prizeDetail)
											<tr>
												<td data-th='Name'>{{ isset($prizeDetail->prize_name) ? ucfirst($prizeDetail->prize_name) : '' }}</td>
											
												<td data-th='Email'>{{ $prizeDetail->prize_description }}</td>
											
												<td data-th='Country'>
													<?php $image		=	isset($prizeDetail->image) ? $prizeDetail->image : '';?>
													@if($image != '' && File::exists(PRIZE_IMAGE_ROOT_PATH.$image))
														<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo PRIZE_IMAGE_URL.$prizeDetail->image; ?>">
															<div class="usermgmt_image">
																<img  src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1&image='.PRIZE_IMAGE_URL.'/'.$prizeDetail->image ?>">
															</div>
														</a>	
														@else
															<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1&image='.WEBSITE_IMG_URL ?>admin/no_image.jpg">
													@endif 
												</td>
											</tr>
											@endforeach
										@else
											<tr>
												<td colspan="3" class="text-center" data-th='Date of Birth'>{{{ trans('No Record Found.') }}}</td>
											</tr>
										@endif		
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
