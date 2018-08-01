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
    background: black repeat scroll 0 0;
    border: 1px solid black;
    border-radius: 50%;
    color: #fff;
    font-size: 13px;
    left: 96px;
    line-height: 5px;
    padding: 13px;
    position: relative;
    top: -47px;
}
.thumbnail{
    min-height: 150px;
}
.usermgmt_image{
	margin: 3px 0px 0px 30px;
}
.comnt_author {
    padding-top: 5px !important;
    padding-bottom: 5px !important;
}
.comnt_author {
    padding-top: 5px !important;
    padding-bottom: 5px !important;
}
</style>
{{ HTML::style('css/admin/magnific-popup.css') }}
{{ HTML::script('js/admin/jquery.magnific-popup.min.js') }}
<section class="content-header">
	<h1>
		{{ trans("View Article") }} 
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href='{{route("ProjectFolder.index")}}'>Blog Management</a></li>
		<li><a href='{{route("$modelName.conetentIndex")}}'>Content</a></li>
		<li class="active">{{ trans("View Article") }}</li>
	</ol>
</section>
<section class="content">
<div class="row pad"> 
	<div class="col-md-12">
			<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
		</div> 
	</div>
	<div class="row pad">
		<div class="col-md-12 col-sm-6">
			<div class="row pad">
				<table class="table table-striped table-bordered" style="margin-top:10px;">
					<thead>
						<tr class="view bgcss">
							<th  width="30%" height="50%" class="" colspan="3" style="font-size:20px;">
								
							<span style="float:left;">ARTICLE DETAILS</span>
							
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th  width="30%" class="text-right txtFntSze" >{{ trans("Folder Name") }}</th>
							<td colspan="2" data-th='Name'>{{ $model->folder_name }}</td>
						</tr>
						<tr>
							<th  width="30%" class="text-right txtFntSze" >{{ trans("Article Name") }}</th>
							<td colspan="2" data-th='Name'>{{ $model->article_name }}</td>
						</tr>
						<tr>
							<th  width="30%" class="text-right txtFntSze" >{{ trans("Article Description") }}</th>
							<td colspan="2" data-th='Name'>
								{!! isset($model->article_description) ? $model->article_description:'' !!} 
							</td>
						</tr>
						<tr>
							<th  width="30%" class="text-right txtFntSze" >{{ trans("Featured Image") }}</th>
							<td colspan="2" data-th='Name'>
								@if($model->image != '' && File::exists(PROJECT_ARTICLE_IMAGE_ROOT_PATH.$model->image))
									<?php
										$image				=	PROJECT_ARTICLE_IMAGE_URL.$model->image;
									?>
									<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo $image; ?>">
										<img src="<?php echo WEBSITE_URL.'image.php?height=80px&cropratio=1&image='.$image; ?>">
									</a>
								@else
									<img id="blah" src="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>" width='100' height="100" />
								@endif
							</td>
						</tr>
						
					</tbody>
				</table>
			</div>
			<div class="row pad">  
				<div class="col-md-12 col-sm-6">
					<table class="table table-striped table-bordered" style="margin-top:10px;">
						<thead>
							<tr class="view bgcss">
								<th  width="30%" height="50%" class="" colspan="6" style="font-size:20px;">
								<span style="float:left;">WEB LINKS</span>
								</th>
							</tr>
						</thead>
						<?php //echo "<pre>";print_r($dancerDetails);die; ?>
						<tbody>
						@if(!($articleLink)->isEmpty())
							@foreach($articleLink as $linkDetail)
							<tr>
								<?php $url = 'http://'.$linkDetail->url; ?>
								<td data-th='Name'><a href="{{$url}}" target="_blank">{{ isset($linkDetail->url) ? ($linkDetail->url) : '' }}</a></td>
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
			<div class="row pad">
				<div class="col-md-12 col-sm-6">
					<div class="" style="margin:10px;">
						<div class="row">
							<div class="view bgcss">
								<h3 class="" colspan="{{$count_documents}}" style="font-size:20px;padding: 3px 8px !important;display: table-row;vertical-align: inherit;border-color: inherit;font-weight: bold;padding: 8px;line-height: 1.428571429;
								vertical-align: top; border-top: 1px solid #ddd;">
								<span style="float:left;padding: 3px 8px !important;">FILE ATTACHMENTS</span>
								</h3>
							</div>
						</div>
						<?php //echo "<pre>";print_r($dancerDetails);die; ?>
						<div class="row">
						@if(!($form_documents)->isEmpty())
							@foreach($form_documents as $formdocument)
								<div class="col-md-3 image-gallery">
									<div class="thumbnail">
										<?php
											$file_ext			=	$formdocument->documents;
											$file_ext			=	explode(".",$file_ext);
											$file_ext			=	end($file_ext);
											$doc_full_path		=	PROJECT_ARTICLE_IMAGE_URL.$formdocument->documents;
										?>
										@if($file_ext == "pdf") 
											<a target="_blank" href="{{$doc_full_path}}">
												<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=120px&height=120px&image='.WEBSITE_IMG_URL."pdf.jpg" ?>">
											</a>
											<br />
										@elseif($file_ext == "doc" || $file_ext == "docx") 
											<br />
											<a target="_blank" href="{{$doc_full_path}}">
												<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=120px&height=120px&image='.WEBSITE_IMG_URL.'/'."doc.png" ?>">
											</a>
										@elseif($file_ext == "zip") 
											<br />
											<a href="{{$doc_full_path}}">
												<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=120px&height=80px&image='.WEBSITE_IMG_URL.'/'."zip.png" ?>">
											</a>
										@elseif($file_ext == "csv") 
											<br />
											<a href="{{$doc_full_path}}">
												<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=120px&height=80px&image='.WEBSITE_IMG_URL.'/'."csv.png" ?>">
											</a>
										@elseif($file_ext == "mp4" || $file_ext == "mp4v" || $file_ext == "wmv") 
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
												<iframe  width="150px"  height="100px" src="{{$embed_url}}"></iframe>
											</a>
										@else 
											<br />
											<a class="fancybox-buttons"  data-fancybox-group="button" href="<?php echo PROJECT_ARTICLE_IMAGE_URL.$formdocument->documents; ?>">
												<div class="usermgmt_image">
													<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=150px&height=100px&image='.PROJECT_ARTICLE_IMAGE_URL.'/'.$formdocument->documents ?>">
												</div>
											</a>
										@endif
									</div>
								</div>
							@endforeach
						@else
							<div class="row">>
								<span colspan="3" class="text-center" data-th='Date of Birth'>{{{ trans('No Record Found.') }}}</span>
							</div>
						@endif		
						</div>
					</div>
				</div>
			</div>
			<div class="row pad">
				<div class="col-md-12 col-sm-6">
					<div class="" style="margin:10px;">
						<div class="row">
							<div class="view bgcss">
								<h3 class="" colspan="{{$count_documents}}" style="font-size:20px;padding: 3px 8px !important;display: table-row;vertical-align: inherit;border-color: inherit;font-weight: bold;padding: 8px;line-height: 1.428571429;
								vertical-align: top; border-top: 1px solid #ddd;">
								<span style="float:left;padding: 3px 8px !important;">COMMENTS</span>
								</h3>
							</div>
						</div>
						<?php //echo "<pre>";print_r($comments);die; ?>
						@if(!($comments)->isEmpty())
							@foreach($comments as $key=>$comment)
							<div class="comment center-block">
							  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							  <div class="panel panel-default">
								<div class="panel-heading" role="tab" id="heading{{$comment->id}}">
								  <h4 class="panel-title">
									<a class="commentlink commentlink{{$comment->id}}" role="button" data-toggle="collapse" data-parent="#accordion" href="collapse{{$comment->id}}" aria-expanded="true" aria-controls="collapse{{$comment->id}}" data-rel="{{ $comment->id }}">{{ substr($comment->message,0,30)."..." }}
									</a>
								  </h4>
								</div>
								<div id="collapse{{$comment->id}}" class="panel-collapse collapse commentcollaspe commentcollaspe{{ $comment->id }} " role="tabpanel" aria-labelledby="heading{{$comment->id}}">
								  <div class="panel-body append_comment{{ $comment->id }}">
									
								  </div>
								</div>
							  </div>
							</div>
							</div>
							@endforeach
						@else
							<div class="row text-center">
								<span class="text-center" data-th='Date of Birth'>{{{ trans('No Record Found.') }}}</span>
							</div>
						@endif	
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
	.comment{
	  width:100%;
	}
	@media(max-width:992px){
	.comment{
	  width:100%;
	} 
	}
	.comment .panel-heading {
	  padding: 0;
		border:0;
	}
	.comment .panel-title>a, .panel-title>a:active{
		display:block;
		padding:15px;
	  color:#555;
	  font-size:16px;
	  font-weight:bold;
		text-transform:uppercase;
		letter-spacing:1px;
	  word-spacing:3px;
		text-decoration:none;
	}
	.comment .panel-heading  a:before {
	   font-family: 'Glyphicons Halflings';
	   content: "\e114";
	   float: right;
	   transition: all 0.5s;
	}
	.comment .panel-heading.active a:before {
		-webkit-transform: rotate(180deg);
		-moz-transform: rotate(180deg);
		transform: rotate(180deg);
	} 
	.image-gallery{
		margin: 0px;
		padding: 0px;
	}
	.thumbnail{
		display: block;
		padding: 0px;
		margin-bottom: 0px;
		line-height: 1.428571429;
		background-color: #fff;
		border: none;
		border-radius: 0px;
		-webkit-transition: all .2s ease-in-out;
		transition: all .2s ease-in-out;
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

	/* 	$.ajax({
			headers: {
			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '{{ route("$modelName.commentReplyData") }}',
			type: 'post',
			data:{'comment_id':comment_id},
			success:function(response){ 
				$(".append_comment"+comment_id).html(response);
			}
		}); */

	});
	
	$('.commentlink').bind('click', function (e) { 
		var comment_id	=	$(this).attr('data-rel'); 
		$.ajax({
			headers: {
			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '{{ route("$modelName.commentReplyData") }}',
			type: 'post',
			data:{'comment_id':comment_id},
			success:function(response){ 
				$(".append_comment"+comment_id).html(response);
				if($(".commentcollaspe"+comment_id).hasClass('in')){
					$(".commentcollaspe"+comment_id).removeClass('in');
					$(".commentcollaspe").removeClass('in');
					$(".commentcollaspe"+comment_id).siblings('.panel-heading').removeClass('active');
				}else{
					$(".commentcollaspe").removeClass('in');
					$(".commentcollaspe"+comment_id).addClass('in');
					$(".commentcollaspe"+comment_id).siblings('.panel-heading').addClass('active');
				}
			}
		});
	});
	
	$('.panel-collapse').on('show.bs.collapse', function () {
		$(this).siblings('.panel-heading').addClass('active');
	});

	$('.panel-collapse').on('hide.bs.collapse', function () {
		$(this).siblings('.panel-heading').removeClass('active');
	});
</script>
@stop
