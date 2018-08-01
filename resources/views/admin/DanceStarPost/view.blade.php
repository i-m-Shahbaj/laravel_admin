@extends('admin.layouts.default')

@section('content')
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
    ebackground: black none repeat scroll 0 0;
    border: 1px solid black;
    border-radius: 50%;
    color: #fff;
    font-size: 13px;
    left: 115px;
    line-height: 12px;
    padding: 10px;
    position: relative;
    top: -79px;
}
</style>
{{ HTML::style('css/admin/magnific-popup.css') }}
{{ HTML::script('js/admin/jquery.magnific-popup.min.js') }}
<section class="content-header">
	<h1>
		{{ trans("View DanceStar Post") }} 
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href='{{route("$modelName.index")}}'>DanceStar Post</a></li>
		<li class="active">{{ trans("View DanceStar Post") }}</li>
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
				<div class="col-md-12 col-sm-6">
					<table class="table table-striped table-bordered" style="margin-top:10px;">
						<thead>
							<tr class="view bgcss">
								<th  width="30%" height="50%" class="" colspan="3" style="font-size:20px;">
									
								<span style="float:left;">DANCESTAR POST DETAILS</span>
								
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th  width="30%" class="text-right txtFntSze" >{{ trans("Username") }}</th>
								<td colspan="2" data-th='Name'>{{ $model->username }}</td>
							</tr>
							<tr>
								<th  width="30%" class="text-right txtFntSze" >{{ trans("Post") }}</th>
								<td colspan="2" data-th='Name'>
									{!! isset($model->message) ? $model->message:'' !!} 
								</td>
							</tr>
							<tr>
								<th  width="30%" class="text-right txtFntSze" >{{ trans("messages.global.created") }}</th>
								<td colspan="2" data-th='Start Date'>{{ date(Config::get("Reading.date_format") , strtotime($model->created_at)) }}</td>
							</tr>
							
						</tbody>
					</table>
				</div>
			</div>
			<div class="row pad">  
				<div class="col-md-12 col-sm-6">
					<table class="table table-striped table-bordered" style="margin-top:10px;">
						<thead>
							<tr class="view bgcss">
								<th  width="30%" height="50%" class="" colspan="6" style="font-size:20px;">
								<span style="float:left;">ATTACHMENTS</span>
								</th>
							</tr>
						</thead>
						<?php //echo "<pre>";print_r($dancerDetails);die; ?>
						<tbody>
							<tr>
						@if(!($form_documents)->isEmpty())
							@foreach($form_documents as $formdocument)
								<td data-th='Name'>
									<?php
										$file_ext			=	$formdocument->image;
										$file_ext			=	explode(".",$file_ext);
										$file_ext			=	end($file_ext);
										$doc_full_path		=	POST_IMAGE_URL.$formdocument->image;
									?>
									@if($file_ext == "pdf") 
										<a target="_blank" href="{{$doc_full_path}}">
											<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=50px&height=80px&image='.WEBSITE_IMG_URL."pdf.jpg" ?>">
										</a>
										<br />
									@elseif($file_ext == "doc" || $file_ext == "docx") 
										<br />
										<a target="_blank" href="{{$doc_full_path}}">
											<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=120px&height=80px&image='.WEBSITE_IMG_URL.'/'."doc.png" ?>">
										</a>
									@elseif($file_ext == "mp4" || $file_ext == "mp4v") 
										<br />
										<?php
												$video_path	=	str_ireplace('http://','',$doc_full_path);
												$video_path	=	str_ireplace('https://','',$doc_full_path);
												$video_path	=	'https://'.$doc_full_path;
												
												$watchurl 			=	str_replace("https://vimeo.com/","https://player.vimeo.com/video/",$doc_full_path);
												$embed_url			=	str_replace("https://vimeo.com/","https://player.vimeo.com/video/",$doc_full_path);
										?>
										<a class="video_demo_item"  href="<?php echo $watchurl; ?>">
											<i class="fa fa-play"></i>
											<iframe  width="200"  height="auto" src="{{$embed_url}}"></iframe>
										</a>
									@else 
										<br />
										<a class="fancybox-buttons"  data-fancybox-group="button" href="<?php echo POST_IMAGE_URL.$formdocument->image; ?>">
											<div class="usermgmt_image">
												<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=120px&height=80px&image='.POST_IMAGE_URL.'/'.$formdocument->image ?>">
											</div>
										</a>
									@endif
								</td>
							@endforeach
							</tr>
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
<script>
	$(document).ready(function(){
		$('body').magnificPopup({
			delegate: '.video_demo_item',
			type: 'iframe',
			tLoading: 'Loading video #%curr%...',
			mainClass: 'mfp-img-mobile',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1] // Will preload 0 - before current, and 1 after the current image
			},
			srcAction: 'iframe_src',
		});	
	});
</script>
@stop
