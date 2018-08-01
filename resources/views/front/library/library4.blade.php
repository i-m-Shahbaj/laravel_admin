@extends('front.layouts.default')
@section('content')
{{ HTML::style('css/admin/magnific-popup.css') }}
{{ HTML::script('js/admin/jquery.magnific-popup.min.js') }}
<style>
html { overflow:auto}
.video_demo_item .fa.fa-play {
	background: black;
	border: 1px solid black;
	border-radius: 50%;
	color: #fff;
	font-size: 8px;
	left: 0;
	line-height: 18px;
	padding: 0;
	position: absolute;
	top: -33px;
	width: 20px;
	height: 20px;
	right: 0;
	margin: 0 auto;
	text-align: center;
}
.highlight_word{
	background-color : rgb(250, 255, 189);
}
@media print {
#action_button {
	display :  none;
}
}
</style>
<div id="pagepiling">
<div class="section cms-wrapper article-detail-top" id="section1">
  
  <div class="container">
    <div class="breadcrumb-wrapper">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <ol class="breadcrumb">
            <li><a href="{{URL::to('dashboard')}}">Home</a></li>
            <li><a href="{{ route('Library.index') }}">Blog</a></li>
            <li><a href='{{ route("Library.folderArticle","$folderData->slug") }}'>{!!$folderData->name!!}</a></li>
            <li class="active"><a href="javascript:void(0);">{!!ucfirst($topicData->article_name)!!}</a></li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
   <div class="folder_article_head">
      <div class="row">
        <div class="col-sm-4 col-md-4">
<!--
			<h1 class="inner-folder-title title">{{$topicData->article_name}}</h1>
-->
        </div>
        <div class="col-sm-8 col-md-8">
          <div class="search-box-wrapper">
            <div class="search-box">
              <div class="form-group">
                <input name="keyword" data-url='{{route("Library.articleDetail","$topicData->slug")}}' type="search" class="form-control search-bar valid" placeholder="Search" value="<?php if(isset($keyword) && !empty($keyword)){ echo $keyword; }?>"/>
                <span><i class="fa fa-search" aria-hidden="true"></i></span> </div>
            </div>
            <div class="action_button" id="action_button">
              <?php
				$url = Request::fullUrl();
				$articleName = ucfirst($topicData->article_name);
				$articleDesc = $topicData->article_description;
				?>
              <a title="Print" onclick="javascript:printContent('blog-detail-print');" id="mail_button" class="btn btn-default btn-small mail_button"><i class="fa fa-print"></i></a>&nbsp;&nbsp; <a title="Mail" href="mailto:?subject=<?php echo $articleName; ?>&amp;body=<?php echo Str::limit(strip_tags($articleDesc),400); ?> Read This Article: {{$url}}" id="print_button" class="btn btn-default btn-small print_button"><i class="fa fa-envelope"></i></a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-sm-8 col-md-9 col-lg-9">
        <div class="boder-right">
          <div class="folder-main-blog" <?php /* id="blog-detail" */?>>
            <div class="row topic-results">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <h5 class="title">{!!ucfirst($topicData->article_name)!!}</h5>
              </div>
            </div>
            <div class="folder-decription">
              <h5 class="blog-slug"> @if(!empty($topicData->user_id)) @if($topicData->user_id!==1){{'By: '.ucfirst($topicData->username)}}@else{!!trans("<b>Posted By</b>: Administrator")!!}@endif @endif <span class="px-10"></span><span class="text-yellow"> {!! '<b>On</b>: '.date("F m, Y",strtotime($topicData->created_at)) !!}</span> </h5>
              <div class="folder-main-sec" id="blog-detail"> @if($topicData->image != '' && File::exists(PROJECT_ARTICLE_IMAGE_ROOT_PATH.$topicData->image))
                <figure class="floder-img"> <img  src="<?php echo PROJECT_ARTICLE_IMAGE_URL.'/'.$topicData->image ?>"> </figure>
                @endif
                <div class="description"> {!! $topicData->article_description !!} </div>
              </div>
            </div>
            
          </div>
          <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6 leave_a_comment_box">
              <div class="blog-bottom"> <span> <i class="fa fa-comment blog-icon-bottom"></i> <?php echo $topicData->total_comments; ?> </span> <span> <i class="fa fa-eye blog-icon-bottom"></i> <?php echo $topicData->viewed; ?> </span> </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6">
              <div class="share-this"> 
                <?php
					$url = Request::fullUrl();
					$facebook_share_link	=	"https://www.facebook.com/share.php?u=". $url;
					$twitter_share_link	=	"https://www.twitter.com/share?". $url;
					$google_share_link	=	"https://plus.google.com/share?url=". $url;
				?>
                <a onclick="window.open('<?php echo $facebook_share_link; ?>','name','width=600,height=400')"><i class="fa fa-facebook"></i></a> <a onclick="window.open('<?php echo $twitter_share_link; ?>','name','width=600,height=400')"><i class="fa fa-twitter"></i></a> <a onclick="window.open('<?php echo $google_share_link; ?>','name','width=600,height=400')"><i class="fa fa-google-plus"></i></a> </div>
            </div>
          </div>
          
          <!-- MObile Sidebar -->
          <div class="clearfix"></div>
          <div class="sidebar-mob">
            <div class="row">
              <div class="col-md-12">
                <div class="right-sidebar-sec"> 
                  <!-- <div class="search-box">
									<div class="form-group">
										<input name="keyword" data-url='{{route("Library.articleDetail","$topicData->slug")}}' type="search" class="form-control search-bar valid" placeholder="Search" value="<?php if(isset($keyword) && !empty($keyword)){ echo $keyword; }?>"/>
										<span><i class="fa fa-search" aria-hidden="true"></i></span>
									</div>
								</div> -->
                  <div class="clearfix"></div>
                  <div class="media-links">
                    <h5>media web links</h5>
                    @if(!$articleLinks->isEmpty())
                    <ul class="weblinks url-links">
                      @foreach($articleLinks as $articleLink)
                      <li><a href="{{ CustomHelper::addhttp($articleLink->url) }}" target="_blank">{{ $articleLink->url}}</a></li>
                      @endforeach
                    </ul>
                    @else
                    <div class="share-sec"> No Links Found </div>
                    @endif </div>
                  <div class="sidebar-bottom-sec">
                    <h5>Attachments</h5>
                    @if(!$attachments->isEmpty())
                    <ul class="weblinks url-links">
                      @foreach($attachments as $attachment)
                      <li><a href="{{PROJECT_ARTICLE_IMAGE_URL.$attachment->documents}}" target="_blank" download>{{$attachment->document_name}}</a></li>
                      @endforeach
                    </ul>
                    @else
                    <div class="share-sec"> No Attachment Found </div>
                    @endif </div>
                </div>
              </div>
            </div>
          </div>
          <!-- MObile Sidebar End-->
          <div class="clearfix"></div>
          <div class="folder-decription"> 
            <!-- ================================================ -->
            <div class="blog-comments mt-40">
				@if(($topicData->allow_comments == 1) && ($topicData->comment_end_date != '0000-00-00') && ($topicData->comment_end_date." 23:59:59" > date("Y-m-d H:i:s")))
				<div class="row">
					<div class="col-lg-12">
						<h3 class="text-blue mb-30">Leave a Comment </h3>
						@if(!empty(Auth::user()))
						{{ Form::open(['role' => 'form','route' => "Library.saveArticleComment",'id'=>'comment_form','method'=>'get']) }}
						{{Form::hidden('user_id',Auth::user()->id)}}
						{{Form::hidden('article_id',$topicData->id)}}
						<div class="comment-form clearfix">
						  <div class="section-field textarea"> {{ Form::textarea("description",'', ['class' => 'form-control input-message autoExpand','id' => 'description','placeholder'=>'Comment*','rows'=>2,'data-min-rows'=>'2']) }} <span class="help-inline" id="comment_description_error"></span> </div>
						  <a class="button pull-left mt-20" href="javascript:void(0);" onclick="saveComment();"> <span>Post comment</span> </a> </div>
						{{Form::close()}}
						@else
						<div class="comment-form clearfix">
						  <div class="alert alert-info"> <a href="{{ URL('login') }}">
							<h4>Please login to comment.</h4>
							</a> </div>
						</div>
						@endif 
					</div>
				</div>
				@endif
              <h3 class="text-blue mb-20">Comments </h3>
			  
              @if(!$comments->isEmpty())
              @foreach($comments as $key=>$articleComment)
              <div class="comments-1">
                <div class="comments-photo">
                  <figure> @if($articleComment->user_image != '' && File::exists(USER_PROFILE_IMAGE_ROOT_PATH.$articleComment->user_image)) <img class="img-responsive" src="<?php echo USER_PROFILE_IMAGE_URL.'/'.$articleComment->user_image ?>"> @else <img class="img-responsive" src="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg"> @endif </figure>
                </div>
                <div class="comments-info">
                  <h4 class="text-blue"> {{$articleComment->user_name}} <span> {{ date(Config::get("Reading.date_time_format"),strtotime($articleComment->created_at)) }}</span></h4>
					@if(($topicData->allow_comments == 1) && ($topicData->comment_end_date != '0000-00-00') && ($topicData->comment_end_date." 23:59:59" > date("Y-m-d H:i:s")))
						<div class="port-post-social pull-right"> <a href="javascript:void(0);" onclick='toggleOn("reply{{$key}}")'>Reply</a> </div>
					@endif
					@if( strlen($articleComment->message)  >= 340)
						<div class="message_div_{{ $articleComment->id }}" >
							 <p>{!! Str::limit(nl2br($articleComment->message),340) !!}</p>
							 <a href="javascript:void(0);" onclick="shwMessage( {{ $articleComment->id }});">Read More..</a>
						</div>
						<div class="show_div_{{ $articleComment->id }}" style="display:none;">
							<p>{!! nl2br($articleComment->message) !!}</p>
							<a href="javascript:void(0);" onclick="hideMessage( {{ $articleComment->id }});">Read Less</a>
						</div>
					@else
						<p>{!! nl2br($articleComment->message) !!} </p>
					@endif
					@if(!($articleComment->reply)->isEmpty())
						<a href="javascript:void(0);" class="total_replies" data-rel="{{ $articleComment->id }}">Number of Replies ({{ count($articleComment->reply) }})</a>
					@endif
                </div>
              </div>
			  @if(($topicData->allow_comments == 1) && ($topicData->comment_end_date != '0000-00-00') && ($topicData->comment_end_date." 23:59:59" > date("Y-m-d H:i:s")))
              <div class="comments-1 comments-2 mtb-20" id="reply{{$key}}" style="display:none;"> 
			  @if(!empty(Auth::user()))
                {{ Form::open(['role' => 'form','route' => "Library.saveArticleCommentReply",'id'=>'comment_reply_form_'.$key,'method'=>'get']) }}
                {{Form::hidden('user_id',Auth::user()->id)}}
                {{Form::hidden('article_id',$topicData->id)}}
                {{Form::hidden('comment_id',$articleComment->id)}}
                <h3 class="text-blue mb-30">Leave a Reply <a href="javascript:void(0);" onclick='toggleOff("reply{{$key}}")' class="cancel-reply">Cancel Reply</a></h3>
                <div class="comment-form clearfix">
                  <div class="section-field textarea"> {{ Form::textarea("reply",'', ['class' => 'form-control input-message valid autoExpand','id' => 'reply','placeholder'=>'Reply*','rows'=>2,'data-min-rows'=>2]) }} <span class="help-inline" id="reply_description_error"></span> </div>
                  <a class="button pull-left mt-20" href="javascript:void(0);" onclick="saveCommentReply({{$key}});"> <span>Post reply</span> </a> </div>
                {{Form::close()}}
                @else
                <div class="comments-1 comments-2 mtb-20">
					<div class="alert alert-info"> <a href="{{url('/login')}}">
                    <h4>Please login to reply.</h4>
                    </a> </div>
                </div>
                @endif 
				</div>
				@endif
				<div class="show_reply_{{ $articleComment->id }}" style="display:none;">
				@if(!empty($articleComment->reply))
				  @foreach($articleComment->reply as $k=>$reply)
				  <div class="comments-1 comments-2">
					<div class="comments-photo">
					  <figure> @if($reply->user_image != '' && File::exists(USER_PROFILE_IMAGE_ROOT_PATH.$reply->user_image)) <img class="img-responsive" src="<?php echo USER_PROFILE_IMAGE_URL.'/'.$reply->user_image ?>"> @else <img class="img-responsive" src="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg"> @endif </figure>
					</div>
					<div class="comments-info">
					  <h4 class="text-blue">{{$reply->user_name}} <span> {{ date(Config::get("Reading.date_time_format"),strtotime($reply->created_at)) }}</span></h4>
						@if( strlen($reply->message)  >= 300)
							<div class="message_div_{{ $reply->id }}" >
								 <p>{!! Str::limit(nl2br($reply->message),300) !!}</p>
								 <a href="javascript:void(0);" onclick="shwMessage( {{ $reply->id }});">Read More..</a>
							</div>
							<div class="show_div_{{ $reply->id }}" style="display:none;">
								<p>{!! nl2br($reply->message) !!}</p>
								<a href="javascript:void(0);" onclick="hideMessage( {{ $reply->id }});">Read Less</a>
							</div>
						@else
							<p>{!! nl2br($reply->message) !!} </p>
						@endif
					  <hr />
					</div>
				  </div>
				  @endforeach
				@endif
				</div>
              @endforeach
              @else
              <tr>
                <td colspan="3"><center>
                    No Record Found
                  </center></td>
              </tr>
              @endif
				@if(($topicData->allow_comments == 1) && ($topicData->comment_end_date != '0000-00-00') && ($topicData->comment_end_date." 23:59:59" > date("Y-m-d H:i:s")))
					<a class="button2 mt-20 leave_comment_option" href="javascript:void(0);"> <span>Leave a comment</span> </a>
				@endif
             </div>
          </div>
        </div>
      </div>
      <div class="col-sm-4 col-md-3 col-lg-3 sidebar-desk">
        <div class="right-sidebar-sec">
          <div class="clearfix"></div>
          <div class="media-links">
            <h5>media web links</h5>
            @if(!$articleLinks->isEmpty())
            <ul class="weblinks url-links">
              @foreach($articleLinks as $articleLink)
              <li><a href="{{ CustomHelper::addhttp($articleLink->url) }}" target="_blank">{{ $articleLink->url}}</a></li>
              @endforeach
            </ul>
            @else
            <div class="share-sec"> No Links Found </div>
            @endif </div>
          <div class="sidebar-bottom-sec">
            <h5>Attachments</h5>
            @if(!$attachments->isEmpty())
            <ul class="weblinks url-links">
              @foreach($attachments as $attachment)
              <li><a href="{{PROJECT_ARTICLE_IMAGE_URL.$attachment->documents}}" target="_blank" download>{{$attachment->document_name}}</a></li>
              @endforeach
            </ul>
            @else
            <div class="share-sec"> No Attachment Found </div>
            @endif </div>
        </div>
      </div>
    </div>
  </div>
  @include('front.elements.footer') </div>
  
   <div class="folder-main-blog" id="blog-detail-print" style="display:none">
	<div class="row topic-results">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<h5 class="title">{!!ucfirst($topicData->article_name)!!}</h5>
	  </div>
	</div>
	<div class="folder-decription">
	  <h5 class="blog-slug"> @if(!empty($topicData->user_id)) @if($topicData->user_id!==1){{'By: '.ucfirst($topicData->username)}}@else{!!trans("<b>Posted By</b>: Administrator")!!}@endif @endif <span class="px-10"></span><span class="text-yellow"> {!! '<b>On</b>: '.date("F m, Y",strtotime($topicData->created_at)) !!}</span> </h5>
	  <div class="folder-main-sec" id="blog-detail"> @if($topicData->image != '' && File::exists(PROJECT_ARTICLE_IMAGE_ROOT_PATH.$topicData->image))
		<figure class="floder-img"> <img  src="<?php echo PROJECT_ARTICLE_IMAGE_URL.'/'.$topicData->image ?>"> </figure>
		@endif
		<div class="description"> {!! $topicData->article_description !!} </div>
		
	  </div>
	</div>
	<footer id="footer">
		<div class="row">
			<div class="col-md-6 pull-left" > <img src="{{ WEBSITE_IMG_URL }}logo-inner.png" alt="CMeShine"><?php echo WEBSITE_URL; ?></div>
			<div class="col-md-6 pull-right" style="color:#000;">CMeShine © 2018 | All Rights Reserved.</div>
		</div>
	</footer>
	<div>
	</div>
  </div>
<script>
	$(document)
    .on('focus.autoExpand', 'textarea.autoExpand', function(){
        var savedValue = this.value;
        this.value = '';
        this.baseScrollHeight = this.scrollHeight;
        this.value = savedValue;
    })
    .on('input.autoExpand', 'textarea.autoExpand', function(){
        var minRows = this.getAttribute('data-min-rows')|0, rows;
        this.rows = minRows;
        rows = Math.ceil((this.scrollHeight - this.baseScrollHeight) / 16);
        this.rows = minRows + rows;
    });
	
	
	$(".leave_comment_option").click(function(){
		$('html, body').animate({
			scrollTop: $(".leave_a_comment_box").offset().top
		}, 200);
	});
	
    function toggleOn(id) {
        var state = document.getElementById(id).style.display;
        if (state == 'none') {
                document.getElementById(id).style.display = 'block';
        }
    }
    function toggleOff(id) {
        var state = document.getElementById(id).style.display;
		if (state == 'block') {
			document.getElementById(id).style.display = 'none';
		} 
    }
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
	
	$('.search-bar').on('keydown', function (evt) {
		setTimeout(function(){
		//e.preventDefault();    
		var searchTxtBox = $('.search-bar');    
		//searchTxtBox.val(searchTxtBox.val().replace(/(\s+)/,"(<[^>]+>)*$1(<[^>]+>)*"));    
		var text = $('.title');    
		var textarea = $('.description');    
		var enew = '';  
		var etextnew = '';  
		var keyCode = 	evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;  
		
		//if(keyCode == 13)
		//{
			if (searchTxtBox.val() != '') {    

				etextnew = text.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				text.html(etextnew);  
				enew = textarea.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				textarea.html(enew);        
					
				var query = new RegExp("("+searchTxtBox.val()+")", "gim");    
				newtextarea= textarea.html().replace(query, '<span class="title-highlight">$1</span>');    
				newtextarea= newtextarea.replace(/(<span class="title-highlight">[^<>]*)((<[^>]+>)+)([^<>]*<\/span>)/,'</span><span class="title-highlight">');    

				newtext= text.html().replace(query, '<span class="title-highlight">$1</span>');    
				newtext= newtext.replace(/(<span class="title-highlight">[^<>]*)((<[^>]+>)+)([^<>]*<\/span>)/,'<span class="title-highlight"><span>');    

				textarea.html(newtextarea);     
				text.html(newtext);     

			}
			else {
				enew 		= textarea.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				etextnew 	= text.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				textarea.html(enew);     
				text.html(etextnew);     
			}   
		//} 
		},100);
	});     
	function saveComment() {
		var $inputs 				= 	$('#comment_form :input.valid');
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG','PDF','pdf'];
		var image_validation		=	'<?php echo __('Please upload a valid image. Valid extensions are jpg, jpeg, png, jpeg, pdf')?>';
		var error  =	0;	
		
		$inputs.each(function() {
			if($(this).attr('type') == 'file' ){
				var value 			=	 $(this).val();
				if(value != ''){
					var file 			=	 value.toLowerCase();
					var extension 		= 	 file.substring(file.lastIndexOf('.') + 1);
					if($.inArray(extension, allowedExtensions) == -1) {
						error	=	1;
						$(this).next().addClass('error');
						$(this).next().html(image_validation);
					}else{
						$(this).next().html('');
						$(this).next().removeClass('error');
					}
				}
			}else if($(this).val() ==''){
				if($(this).attr('name')=='description'){
					error	=	1;
					$("#comment_description_error").addClass('error');
					$("#comment_description_error").html('This field is required.');
				}else if($(this).attr('name') == 'youtube_url' ){
					error	=	0;
					$(this).next().html('');
					$(this).next().removeClass('error');
				}else{
					error	=	1;
					$(this).next().addClass('error');
					$(this).next().html('This field is required.');
				}
			}else{
				if($(this).attr('name')=='description'){
					$("#comment_description_error").removeClass('error');
					$("#comment_description_error").html('');
				}else{
					$(this).next().html('');
					$(this).next().removeClass('error');
				}
			}
		});
		
		if(error == 0){
			$('#loader_img').show();
			$('.help-inline').html('');
			$('.help-inline').removeClass('error');
			var formData  = $('#comment_form')[0];
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: '{{ URL("save-article-comment") }}',
				type:'POST',
				data: new FormData(formData), 
				contentType: false,    
				cache: false,       
				processData:false,
				success: function(r){
					error_array 	= 	JSON.stringify(r);
					datas			=	JSON.parse(error_array);
					if(datas['success'] == true) {
						window.location.href	=	'{{ route("Library.articleDetail","$topicData->slug") }}';
					}else {
						$.each(datas['errors'],function(index,html){
							if(index=='description'){
								$("#comment_description_error").addClass('error');
								$("#comment_description_error").html(html);
							}else if(index == 'image'){
								$("#image_error").addClass('error');
								$("#image_error").html(html);
							}else{
								$("#comment_form input[name = "+index+"]").next().addClass('error');
								$("#comment_form input[name = "+index+"]").next().html(html);
							}
						});
					}
					$('#loader_img').hide();
				}
			});
		}
	}
	
	function saveCommentReply(Id){
		var $inputs 				= 	$('#comment_reply_form_'+Id+' :input.valid');
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG','PDF','pdf'];
		var image_validation		=	'<?php echo __('Please upload a valid image. Valid extensions are jpg, jpeg, png, jpeg, pdf')?>';
		var error  =	0;
		$inputs.each(function() {
			if($(this).attr('type') == 'file' ){
				var value 			=	 $(this).val();
				if(value != ''){
					var file 			=	 value.toLowerCase();
					var extension 		= 	 file.substring(file.lastIndexOf('.') + 1);
					if($.inArray(extension, allowedExtensions) == -1) {
						error	=	1;
						$(this).next().addClass('error');
						$(this).next().html(image_validation);
					}else{
						$(this).next().html('');
						$(this).next().removeClass('error');
					}
				}
			}else if($(this).val() ==''){
				if($(this).attr('name')=='reply'){
					error	=	1;
					$("#comment_reply_form_"+Id+" #reply_description_error").addClass('error');
					$("#comment_reply_form_"+Id+" #reply_description_error").html('This field is required.');
				}else{
					error	=	1;
					$(this).next().addClass('error');
					$(this).next().html('This field is required.');
				}
			}else{
				if($(this).attr('name')=='reply'){
					$("#comment_reply_form_"+Id+" #reply_description_error").removeClass('error');
					$("#comment_reply_form_"+Id+" #reply_description_error").html('');
				}else{
					$(this).next().html('');
					$(this).next().removeClass('error');
				}
			}
		});
		
		if(error == 0){
			$('#loader_img').show();
			$('.help-inline').html('');
			$('.help-inline').removeClass('error');
			var formData  = $('#comment_reply_form_'+Id)[0];
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: '{{ URL("save-article-comment-reply") }}',
				type:'POST',
				data: new FormData(formData), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false,       // The content type used when sending data to the server.
				cache: false,             // To unable request pages to be cached
				processData:false,
				success: function(r){
					error_array 	= 	JSON.stringify(r);
					datas			=	JSON.parse(error_array);
					if(datas['success'] == true) {
						window.location.href	=	'{{ route("Library.articleDetail","$topicData->slug") }}';
					}else {
						$.each(datas['errors'],function(index,html){
							if(index=='reply'){
								$("comment_reply_form_"+Id+" #reply_description_error").addClass('error');
								$("comment_reply_form_"+Id+" #reply_description_error").html(html);
							}else if(index == 'image'){
								$("comment_reply_form_"+Id+" #image_error").addClass('error');
								$("comment_reply_form_"+Id+" #image_error").html(html);
							}else{
								$("comment_reply_form_"+Id+" input[name = "+index+"]").next().addClass('error');
								$("comment_reply_form_"+Id+" input[name = "+index+"]").next().html(html);
							}
						});
					}
					$('#loader_img').hide();
				}
			});
		}
	}
	
	 $('#comment_form').each(function() {
		$(this).find('input').keypress(function(e) {
           if(e.which == 10 || e.which == 13) {
				saveComment();
				return false;
            }
        });
	});
	
	 $('#comment_reply_form').each(function() {
		$(this).find('input').keypress(function(e) {
           if(e.which == 10 || e.which == 13) {
				//saveCommentReply();
				return false;
            }
        });
	});
	
    
	function printContent(id){
		 var data = document.getElementById(id).innerHTML;
		 var popupWindow = window.open('','printwin',
			  'left=100,top=100,width=400,height=400');
		 popupWindow.document.write('<HTML>\n<HEAD>\n');
		 popupWindow.document.write('<TITLE></TITLE>\n');
		 popupWindow.document.write('<URL></URL>\n');
		 popupWindow.document.write("<link href='<?php echo WEBSITE_CSS_URL; ?>print.css' media='print' rel='stylesheet' type='text/css' />\n");
		 popupWindow.document.write("<link href='<?php echo WEBSITE_CSS_URL; ?>admin/bootstrap.min.css' media='print' rel='stylesheet' type='text/css' />\n");
		 popupWindow.document.write("<link href='<?php echo WEBSITE_CSS_URL; ?>ecommerce.css' media='print' rel='stylesheet' type='text/css' />\n");
		 popupWindow.document.write("<link href='<?php echo WEBSITE_CSS_URL; ?>admin/font-awesome.css' media='print' rel='stylesheet' type='text/css' />\n");
		 popupWindow.document.write('<script>\n');
		/*  popupWindow.document.write('$("#print_button").css("display", "none");\n');
		 popupWindow.document.write('$("#mail_button").css("display", "none");\n'); */
		 popupWindow.document.write('$(".imagebutton").hide();\n');
		 popupWindow.document.write('function print_win(){\n');
		 popupWindow.document.write('\nwindow.print();\n');
		 popupWindow.document.write('\nwindow.close();\n');
	/* 	 popupWindow.document.write('$("#print_button").css("display", "block");\n');
		 popupWindow.document.write('$("#mail_button").css("display", "block");\n'); */
		 popupWindow.document.write('}\n');
		 popupWindow.document.write('<\/script>\n');
		 popupWindow.document.write('</HEAD>\n');
		 popupWindow.document.write('<BODY onload="print_win()">\n');
		 popupWindow.document.write(data);
		 popupWindow.document.write('</BODY>\n');
		 popupWindow.document.write('</HTML>\n');
		 popupWindow.document.close();
	}
	
	function shwMessage(id){
		$('.message_div_'+id).hide();
		$('.show_div_'+id).show();
	}
	function hideMessage(id){
		$('.message_div_'+id).show();
		$('.show_div_'+id).hide();
	}
	
	$(".total_replies").click(function(){
		var id = $(this).attr("data-rel");
		$(".show_reply_"+id).show();
	});
  </script> 
  <style>
  #header {visibility: hidden;}

	#footer{visibility: hidden;}

	@media print {
		#header, #footer {visibility: visible;}
	  }
  </style>
@stop