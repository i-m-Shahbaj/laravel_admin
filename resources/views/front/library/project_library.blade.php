@extends('front.layouts.default')
@section('content')

<style>
	html { overflow:auto};
</style>
<div class="cms-page-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="article_detail cm_content">
					<div class="col-md-12 col-sm-12">
						<div class="form-group ">
							knowledge Base
						</div>
					</div>
					<br/>
				</div>

				@if(!empty($libraryData))
					@foreach($libraryData as $LibraryProjects)
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="heading_{{$LibraryProjects->id}}">
									<h4 class="panel-title">
										<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{$LibraryProjects->id}}" aria-expanded="true" aria-controls="collapse_{{$LibraryProjects->id}}">
											<i class="more-less glyphicon glyphicon-plus"></i>
											<a href="javascript::void(0)">{{$LibraryProjects->project_name}} </a>
										</a>
									</h4>
								</div>
								<div id="collapse_{{$LibraryProjects->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_{{$LibraryProjects->id}}">
									<div class="panel-body">
										 <ul>
											@if(!empty($LibraryProjects->project_folder))
												@foreach($LibraryProjects->project_folder as $projectMainFolder)
													<li>
														<i class="fa fa-folder"></i><a href="">{{ $projectMainFolder->name }} </a>
														<ul>
															@if(!empty($LibraryProjects->project_sub_folder))
																@foreach($LibraryProjects->project_sub_folder as $projectSubFolder)
																	@if($projectSubFolder->parent_id == $projectMainFolder->id)
																		<li><i class="fa fa-folder"></i><a href="">{{ $projectSubFolder->name }} </a>
																			<ul>
																				@if(!empty($LibraryProjects->project_articles))
																					@foreach($LibraryProjects->project_articles as $projectArticles)
																						@if($projectArticles->project_folder_id == $projectSubFolder->id)
																							<li><i class="fa fa-file"></i><a href="{{URL('library/'.$LibraryProjects->slug.'/'.$projectMainFolder->slug.'/'.$projectSubFolder->slug.'/'.$projectArticles->slug)}}" >{{ $projectArticles->article_name }} </a></li>
																						@endif
																					@endforeach
																				@endif
																			</ul>
																		</li>
																	@endif
																@endforeach
															@endif
														</ul>
													</li>
												@endforeach
											@endif
										</ul>
									</div>
								</div>
							</div>
						</div>
				@endforeach
			@endif
      </div>
		<div class="col-sm-9 ">
			<div class="article_detail cm_content">
				<div class="col-md-2 col-sm-2">
					<div class="form-group ">
						Keyword Search:
					</div>
				</div>
				<div class="col-md-5 col-sm-5">
					<div class="form-group ">
						{{ Form::text('keyword',((isset($searchVariable['keyword'])) ? $searchVariable['keyword'] : ''), ['class' => 'form-control search_box','id'=>'search']) }}
					</div>
				</div>
				<div class="col-md-2 col-sm-2">
					<div class="form-group ">
						<button class="btn btn-primary" id="btn"><i class='fa fa-search '></i> {{ trans('messages.search.text') }}</button>
					</div>
				</div>
				@if(!empty($article_id))
					<div class="col-md-1 col-sm-1">
						<div class="small-4 columns">
						  <div class="wrapper">
							<?php
								$url = Request::fullUrl();
								$facebook_share_link	=	"http://www.facebook.com/share.php?u=". $url;
								$twitter_share_link		=	"http://www.twitter.com/share?". $url;
							?>
						    <a href="javascript:void(0);" >Share</a>
							<a class="shre_btns" onclick="window.open('<?php echo $facebook_share_link; ?>','name','width=600,height=400')"><i class="fa fa-facebook"></i></a>
							<a class="shre_btns" onclick="window.open('<?php echo $twitter_share_link; ?>','name','width=600,height=400')"><i class="fa fa-twitter"></i></a>

							<a class="shre_btns" href="mailto:?subject=I wanted you to see this article&amp;body= Read This Article: {{$url}}">Email</a>
							<a class="shre_btns" href="{{URL::to('export-article-to-pdf')}}">Print</a>
						  </div>
						</div>
					</div>
					<div class="col-md-1 col-sm-1">
						 <a href="javascript:void(0);" class="go-to-comment">Comment</a>
					</div>
				@endif
				<br/>
			</div>
		
			@if(!empty($article_id))
				<div class="artical_data">
					<div id="print_article" class="">
					  <h1>{{$articleData->article_name}}</h1>
					  <hr/>
						  <div class="col-sm-12">
							  <div class="col-sm-6" >Project : #{{$articleData->project_number}}</div>
								<div class="col-sm-6"><div class="pull-right">Created On: {{  date(Config::get("Reading.date_time_format"),strtotime($articleData->created_at))}}</div></div>
								
						  </div>
						  
						  <br/>
						  <div class="col-sm-12">
								<div class="col-sm-6" >Article Id : #{{$articleData->id}}</div>
								<div class="col-sm-6"><div class="pull-right">Updated On: {{ date(Config::get("Reading.date_time_format"),strtotime($articleData->updated_at))}}</div></div>
						  </div>
						  <br/>
						  <div class="col-sm-12">
								<div class="col-sm-6" >Authored By :{{ $articleData->project_author}}</div>
						  </div>
					  
					  <hr/>
					  <hr/>
					  <div class="search_article">{!!$articleData->article_description!!}</div> 
					</div>
					<div class="container"  ng-controller="Main">
					  <div class="content">
						<a href="javascript:void(0);" ng-click="doVote({{$article_id}})">
							<i class="glyphicon" ng-class="(like.userVotes == 1) ? 'fa fa-thumbs-up' : 'fa fa-thumbs-o-up'"></i>
						  <span ng-model="like.votes"></span>
						</a>
					  </div>
					</div>	
					<br/>
					
					<div class="helpful_nothelpful_div">
						@if(!empty($helpfulNotHelpfulArticle))
							@if($helpfulNotHelpfulArticle->value == 1)
								Was this article helpful?
								<i class="fa fa-thumbs-up"></i> Helpful
							@elseif($helpfulNotHelpfulArticle->value == 2)
								Was this article helpful?
								<i class="fa fa-thumbs-down"></i>Not Helpful	
							@endif
						@else
							<div class="helpful_nothelpful">
							Was this article helpful?	
							<i class="fa fa-thumbs-up helpful_nothelpful_article" title="Yes" aria-hidden="true" data-rel="1" data-articleid="{{$article_id}}"></i>
							&nbsp;&nbsp;&nbsp;
							<i class="fa fa-thumbs-down helpful_nothelpful_article" title="No" aria-hidden="true" data-rel="2" data-articleid="{{$article_id}}"></i>
							</div>
						@endif
					</div>
					<div>
						 <div class="col-sm-6 ">
							<h3 class="mst_viewed"><b>Media & Web Links</b></h3>
							@if(!empty($articleWebLinks))
								@foreach($articleWebLinks as $articleWebLink)
								<?php $url = "https://".$articleWebLink->url;  ?>
									<h4 class="articl_detail"><a href="{{ $url }}" target="_blank"class="artical_nme" >{{ date(Config::get("Reading.date_format"),strtotime($articleWebLink->created_at)).' : '.$articleWebLink->url }}</a></h4>
								@endforeach
							@endif
						 </div>
						 <div class="col-sm-6 ">
							<h3 class="mst_viewed"><b>File Attachments</b></h3>
							@if(!empty($articleFiles))
								@foreach($articleFiles as $articleFile)
								<?php $doc_full_path		=	PROJECT_ARTICLE_IMAGE_URL.$articleFile->documents;?>
									<h5 class="articl_detail"><a href="{{$doc_full_path}}" download class="artical_nme" >{{ date(Config::get("Reading.date_format"),strtotime($articleFile->created_at)).' : '.$articleFile->document_name }}</a></h5>
								@endforeach
							@endif
						 </div>
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-12 footer_div">
						 <div class="col-sm-6 ">
							<h4 class="articl_detail">Last Updated On: {{ date(Config::get("Reading.date_time_format"),strtotime($articleData->updated_at))}}</h4>
							<h4 class="articl_detail">Revision #: {{ $articleData->revision}}</h4>
						 </div>
						 <div class="col-sm-6 ">
							<h4 class="articl_detail"># of Views: {{ $articleData->viewed}}</h4>
							<h4 class="articl_detail"># of Likes:{{ $articleData->total_likes}}</h4>
							<h4 class="articl_detail"># of Comments:{{ $articleData->total_comments}}</h4>
						 </div>
					</div>
					<div class="clearfix"></div>
					<div class="box">
						<div class="box-body ">
							<table class="table table-hover" id="my_comment">
								<thead>
									<tr>
										<th width="15%">
											Comments(and Replies)
										</th>
										<th width="15%">
											Replies/Views
										</th>
										
										<th width="25%">
											Last Post By
										</th>
									</tr>
								</thead>
								@if(!$articleComments->isEmpty())
									@foreach($articleComments as $articleComment)
										<tr>
											<td>
												<a href="javascript:void(0);" onclick="get_comment_data({{ $articleComment->id }})">{{strip_tags(Str::limit($articleComment->message,35)) }}</a>
											</td>
											<td>
												Replies:{{ $articleComment->total_comment_reply}}<br/>
												Views:{{ $articleComment->viewed}}
											</td>
											<td>
												{{$articleComment->user_name}}<br/>
												{{ date(Config::get("Reading.date_time_format"),strtotime($articleComment->created_at)) }}

											</td>
										</tr>
									@endforeach
								@else
									<tr>
										<td colspan="3"><center>No Record Found</center></td>
									</tr>
								@endif	
							</table>
						</div>
					</div>
					<div class="append_comment"></div>
					<div class="forum_lv_cmnts">
						<h2>{{{ trans("Leave A Comment") }}} </h2>
						<div class="row">
							@if(!empty(Auth::user()))
								{{ Form::open(['role'=>'form', 'url'=>'save-forum', 'id'=>'save_comment'])}}
								{{ Form::hidden("article_id",$article_id, ["id"=>"article_id"]) }}
								<div class="col-sm-10">
									<div class="lv_frM_cmnt_rw">
										 {{ Form::textarea("message", null, ['class'=>"form-control message" , 'id'=>"message",'rows'=>'5','cols'=>'5', 'placeholder'=> trans("Enter Comment") ]) }}
										 <span class="help-inline" id="message_error"></span>
									</div>
								</div>
								
								<div class="col-sm-12">
									<div class="lv_frM_cmnt_rw">
										<input type="button" class="btn btn-primary" value="{{{ trans('Submit Comment') }}}" onclick="saveComment();" >
										
									</div>
								</div>
								{{ Form::close() }}
							@endif
						</div>
					</div>
				</div>
			@else
				<div class="article_wlcm_page">
				<h1 class="welcm"><b>Welcome To The Library</b></h1>
				<hr/>
					<div>
						 <div class="col-sm-6 ">
							<h3 class="mst_viewed"><b>Most Viewed</b></h3>
							@if(!empty($mostViewedArticles))
								@foreach($mostViewedArticles as $mostViewedArticle)
									<h4 class="articl_detail"><i class="fa fa-file file_color"></i><a href="{{URL('library/'.$mostViewedArticle->project_slug.'/'.$mostViewedArticle->main_folder_slug.'/'.$mostViewedArticle->sub_folder_slug.'/'.$mostViewedArticle->slug)}}" class="artical_nme" onclick="get_article_data({{$mostViewedArticle->id}})">{{ $mostViewedArticle->article_name}}</a></h4>
									 <p class="article_des"> {!! strip_tags(Str::limit($mostViewedArticle->article_description,150)) !!}</p>
								@endforeach
							@endif
						 </div>
						 <div class="col-sm-6 ">
							<h3 class="mst_viewed"><b>Recently Added</b></h3>
							@if(!empty($recentAddedArticles))
								@foreach($recentAddedArticles as $recentAddedArticle)
									<h4 class="articl_detail"><i class="fa fa-file file_color"></i><a href="{{URL('library/'.$recentAddedArticle->project_slug.'/'.$recentAddedArticle->main_folder_slug.'/'.$recentAddedArticle->sub_folder_slug.'/'.$recentAddedArticle->slug)}}" class="artical_nme" onclick="get_article_data({{$recentAddedArticle->id}})">{{ $recentAddedArticle->article_name}}</a></h4>
									
									<p class="article_des">{!! strip_tags(Str::limit($recentAddedArticle->article_description, 150)) !!}</p>
								@endforeach
							@endif
						 </div>
					</div>
				</div>
			@endif	
      </div>
    </div>
</div>


@include('front.elements.footer')
</div>
<?php
	if(Auth::user()->image !='' && File::exists(USER_PROFILE_IMAGE_ROOT_PATH.Auth::user()->image)){
		$imageurl							=	WEBSITE_URL.'image.php?width=30px&height=30px&border-radius=8px&cropratio=1.01:1&image='.USER_PROFILE_IMAGE_URL.'/'.Auth::user()->image;
		
		$login_user_first_word			=	"<img src='".$imageurl."' />";
	}else {
		$imageurl						=	WEBSITE_URL.'image.php?width=30px&height=30px&border-radius=8px&cropratio=1.01:1&image='.WEBSITE_IMG_URL.'/'."no_image.jpg";
		$login_user_first_word			=	"<img src='".$imageurl."' />";
	}
	
?>
<script>
var likeUnlikeCount = '<?php echo $likeUnlikeArticle; ?>'
var myApp = angular.module('liveApp', [])
.controller('Main', ['$scope','$http', function($scope, $http) {
    $scope.like = {};
    $scope.like.votes = likeUnlikeCount;
    $scope.like.userVotes = likeUnlikeCount;
    $scope.doVote = function(article_id) {
      if ($scope.like.userVotes == 1) {
        $http({
			headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
			method  : 'POST',
			url     : '{{ URL("like-unlike-article") }}',
			params: {'value':2, 'article_id':article_id,"_token": "{{ csrf_token() }}"},
		})
		.success(function(data) {
			delete $scope.like.userVotes;
			$scope.like.votes--;
			$('#loader_img').hide();
		});
      }else{
        $http({
			headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
			method  : 'POST',
			url     : '{{ URL("like-unlike-article") }}',
			params: {'value':1, 'article_id':article_id,"_token": "{{ csrf_token() }}"},
		})
		.success(function(data) {
			$scope.like.userVotes = 1;
			$scope.like.votes++;
			$('#loader_img').hide();
		});
      }
    }
  }]);



	$('a.go-to-comment').click(function() { $('html, body').animate({scrollTop: $(".forum_lv_cmnts").offset().top},700); return false; });
	function toggleIcon(e) {
		$(e.target)
			.prev('.panel-heading')
			.find(".more-less")
			.toggleClass('glyphicon-plus glyphicon-minus');
	}
	$('.panel-group').on('hidden.bs.collapse', toggleIcon);
	$('.panel-group').on('shown.bs.collapse', toggleIcon);

	$(".reply_btn").on("click",function(){
		$(".cmnt_rply").show();
	});
	function saveComment() {
		var formData  = $('#save_comment')[0];
		var	message	=	$('.message').val();
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$.ajax({
			url: '{{ URL("save_comment") }}',
			type:'post',
			//data: $('#signup_form').serialize(),
			data: new FormData(formData), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,
			success: function(r){
				error_array 	= 	JSON.stringify(r);
				data			=	JSON.parse(error_array);
				if(data['success'] == 1) {
					var	username	=	'<?php echo Auth::user()->username ?>';
					var	datetime	=	'<?php echo date(Config::get("Reading.date_time_format")) ?>';
					var myComment	='<tr><td><a href="javascript:void(0);" onclick="get_comment_data('+data["comnt_id"]+')">'+message.substring(0,35)+'</a></td><td>Replies:0<br/>Views:0</td><td>'+username+'<br/>'+datetime+'</td></tr>';
					//var myComment	=	'<li><div class="cmnts_bx"><div class="nam_icons">'+login_user_first_word+'</div><div class="nam_icons_txt"><div class="cmnt_nm"><label>'+username+'</label><div class="cmnt_dt">'+datetime+'</div></div><div class="cmnt_txt"><p class="cmnt_msg">'+message+'</p></div></div></div></li>';
					$('#my_comment tr:last').after(myComment);
					$('.message').val(" ");
					show_message("Your comment has been posted successfully.",'success')
				}
				else {
					$.each(data['errors'],function(index,html){
						if($.trim(index)  == 'message'){
							$("#message_error").addClass('error');
							$("#message_error").html(html);
						}
					});
				}
				$('#loader_img').hide();
			}
		});
	}
	 $('#save_comment').each(function() {
		$(this).find('input').keypress(function(e) {
		   if(e.which == 10 || e.which == 13) {
				saveComment();
				return false;
			}
		});
	});

	
	 $('.like_unlike_article').click(function() {
		var value 		= ($(this).data('rel'));
		var article_id 	= ($(this).data('articleid'));
		$.ajax({
			url: '{{ URL("like-unlike-article") }}',
			type:'post',
			data: {'value':value, 'article_id':article_id,"_token": "{{ csrf_token() }}"},
			success: function(response){
				
				if(response == 1) {
					var like	=	"<i class='fa fa-thumbs-up like_unlike_article' title='Unlike' aria-hidden='true' data-rel='1' data-articleid="+article_id+"></i>"
					$(".like_unlike_article").hide();
					$("#liked_article").html(like);
				}else{
					var like	=	"<i class='fa fa-thumbs-o-up like_unlike_article' title='Like' aria-hidden='true' data-rel='1' data-articleid="+article_id+"></i>"
					$(".like_unlike_article").hide();
					$("#liked_article").html(like);
				}
				$('#loader_img').hide();
			}
		});
	});
	
	 $('.helpful_nothelpful_article').click(function() {
		var value 		= ($(this).data('rel'));
		var article_id 	= ($(this).data('articleid'));
		$.ajax({
			url: '{{ URL("helpful-nothelpful-article") }}',
			type:'post',
			data: {'value':value, 'article_id':article_id,"_token": "{{ csrf_token() }}"},
			success: function(response){
				
				if(response == 1) {
					var	helpful	=	"Was this article helpful?<i class='fa fa-thumbs-up'></i>Helpful"
					$(".helpful_nothelpful").hide();
					$(".helpful_nothelpful_div").html(helpful);
				}else{
					var	nothelpful	=	"Was this article helpful?<i class='fa fa-thumbs-down'></i>Not Helpful"
					$(".helpful_nothelpful").hide();
					$(".helpful_nothelpful_div").html(nothelpful);
				}
				$('#loader_img').hide();
			}
		});
	});

	function get_comment_data(comment_id){
		$.ajax({
			headers: {
			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '{{ URL("/comment_detail") }}',
			type: 'post',
			data:{'comment_id':comment_id},
			success:function(response){ 
				$(".append_comment").html(response);
			}
		});
	}
	
	function saveReply(comment_id) {
		var	comment_reply	=	$('#reply').val();
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$.ajax({
			
			url: '{{ URL("save_comment_reply") }}',
			type:'post',
			data: {'comment_id':comment_id,'reply':comment_reply,"_token": "{{ csrf_token() }}"},
			success: function(r){
				error_array 	= 	JSON.stringify(r);
				data			=	JSON.parse(error_array);
				if(data['success'] == 1) {
					var	username	=	'<?php echo Auth::user()->full_name ?>';
					var	datetime	=	'<?php echo date(Config::get("Reading.date_time_format")) ?>';
					var cmnt_reply	=	"<div class='col-sm-3'></div><div class='col-sm-9 alert alert-info'><div class='col-sm-8'><b>"+username+"</b></div><div class='col-sm-4'><b>"+datetime+"</b></div><div class='col-sm-12'>"+comment_reply+"</div></div>";
					$('.comment_reply').append(cmnt_reply);
					$('.comment_reply').val(" ");
					$('.cmnt_rply').hide();
					show_message("Your comment reply has been posted successfully.",'success');
				}
				else {
					$.each(data['errors'],function(index,html){
						if($.trim(index)  == 'reply'){
							$("#reply_error").addClass('error');
							$("#reply_error").html(html);
						}
					});
				}
				$('#loader_img').hide();
			}
		});
	}

 function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data) 
    {
        var mywindow = window.open('', 'Equipment', 'height=1400,width=1400');
        mywindow.document.write('<html><head><title>Equipment</title>');
        /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }
	
	
	 function printContent(id){
		 var data = document.getElementById(id).innerHTML;
		 var popupWindow = window.open('','printwin',
			  'left=100,top=100,width=400,height=400');
		 popupWindow.document.write('<HTML>\n<HEAD>\n');
		 popupWindow.document.write('<TITLE></TITLE>\n');
		 popupWindow.document.write('<URL></URL>\n');
		 popupWindow.document.write("<link href='<?php echo WEBSITE_CSS_URL; ?>print.css' media='print' rel='stylesheet' type='text/css' />\n");
		 popupWindow.document.write('<script>\n');
		 popupWindow.document.write('function print_win(){\n');
		 popupWindow.document.write('\nwindow.print();\n');
		 popupWindow.document.write('\nwindow.close();\n');
		 popupWindow.document.write('}\n');
		 popupWindow.document.write('<\/script>\n');
		 popupWindow.document.write('</HEAD>\n');
		 popupWindow.document.write('<BODY onload="print_win()">\n');
		 popupWindow.document.write(data);
		 popupWindow.document.write('</BODY>\n');
		 popupWindow.document.write('</HTML>\n');
		 popupWindow.document.close();
	  }

//search
  $('#btn').bind('click', function (e) {    
		e.preventDefault();    
		var searchTxtBox = $('#search');     
		var textarea = $('.search_article');    
		var enew = '';    
		if (searchTxtBox.val() != '') {    

			enew = textarea.html().replace(/(<mark>|<\/mark>)/igm, "");    
			textarea.html(enew);     
				
			var query = new RegExp("("+searchTxtBox.val()+")", "gim");    
			newtext= textarea.html().replace(query, "<mark>$1</mark>");    
			newtext= newtext.replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/,"</mark><mark>");    

			textarea.html(newtext);     

		}    
		else {    
			enew = textarea.html().replace(/(<mark>|<\/mark>)/igm, "");    
			textarea.html(enew);     
		}    
	});
  $('#search').keypress(function (e) {  
		var searchTxtBox = $('#search');    
		searchTxtBox.val(searchTxtBox.val());    
		var textarea = $('.search_article');    
		var enew = '';    
		if (searchTxtBox.val() != '') {    

			enew = textarea.html().replace(/(<mark>|<\/mark>)/igm, "");    
			textarea.html(enew);     
				
			var query = new RegExp("("+searchTxtBox.val()+")", "gim");    
			newtext= textarea.html().replace(query, "<mark>$1</mark>");    
			newtext= newtext.replace("</mark><mark>");    

			textarea.html(newtext);     

		}    
		else {    
			enew = textarea.html().replace(/(<mark>|<\/mark>)/igm, "");    
			textarea.html(enew);     
		}    
	});



	
	$(document).on('click','.like_unlike_article',function(){
		var value 		= ($(this).data('rel'));
		var comment_id 	= ($(this).data('articleid'));
		$.ajax({
			url: '{{ URL("like-unlike-article-comment") }}',
			type:'post',
			data: {'value':value, 'comment_id':comment_id,"_token": "{{ csrf_token() }}"},
			success: function(response){
				if(response == 1) {
					var like	=	"<i class='fa fa-thumbs-up like_unlike_article' title='Unlike' aria-hidden='true' data-rel='2' data-articleid="+comment_id+"></i>"
					$(".like_unlike_article").hide();
					$("#liked_article").html(like);
				}else{
					var like	=	"<i class='fa fa-thumbs-o-up like_unlike_article' title='Like' aria-hidden='true' data-rel='1' data-articleid="+comment_id+"></i>"
					$(".like_unlike_article").hide();
					$("#liked_article").html(like);
				}
				$('#loader_img').hide();
			}
		});
	});
</script>
<style>

    .panel-group .panel {
        border-radius: 0;
        box-shadow: none;
        border-color: #EEEEEE;
    }

    .panel-default > .panel-heading {
        padding: 0;
        border-radius: 0;
        color: #212121;
        background-color: #FAFAFA;
        border-color: #EEEEEE;
    }

    .panel-title {
        font-size: 20px;
    }

    .panel-title > a {
        display: block;
        padding: 10px;
        text-decoration: none;
    }

    .more-less {
        float: right;
        color: #212121;
    }
	.search_box {
		height: 27px !important;
	}
	.article_detail {
		height: 65px;
		background-color: #C0C0C0;
		padding-top: 18px;
	}
	.col-sm-9 {
		padding-left: 0px;
		padding-right: 0px;
	}
	.cms-page-wrapper {
		margin: 85px 0 66px;
	}
	.welcm{
	    margin-top: 5px;	
	    margin-bottom:0px;	
	}
	hr{
		 margin-top: 5px;
		 margin-bottom:2px;
	}
	.mst_viewed{
		background-color: #F6F6F6;
		padding: 10px;
	}
	.artical_nme {
		margin-left: 20px;
		font-weight: bold;
		font-size: 20px;
	}
	.file_color {
		color: #E0E0E0;
	}
	
	.cms-page-wrapper p {
		 margin:0 0 0 35px; 
		line-height: 28px;
	}
	.articl_detail{
		 margin-top: 5px;
		 margin-bottom:2px;
	}
	.lv_frM_cmnt_rw{
		margin:5px;
	}
	ul {
		list-style: none;
		margin: 0px;
	}
	.cmnt_nm {
		float: left;
		font-size: 16px;
		color: #535353;
		text-transform: uppercase;
		font-weight: 600;
		margin-right: 20px;
	}
	.cmnt_txt {
		float: left;
		width: 100%;
		margin: 10px 0;
	}
	.cmnt_scsn_dv {
    float: left;
    width: 100%;
}
.cmnts_dv {
    float: left;
    width: 100%;
}
.nam_icons {
    background: none;
}
.nam_icons {
    background: #58bae5;
    font-size: 18px;
    text-align: center;
    color: #fff;
    width: 30px;
    padding: 1px;
    border-radius: 50%;
    float: left;
    height: 30px;
    margin: 0 20px 0 0;
}

.shre_btns {
  display: none;
}

.wrapper:hover .shre_btns {
  display: inline-block;
}
.col-sm-12.footer_div {
    background-color: #C0C0C0;
    padding: 6px;
    margin: 12px;
}
.comment_header{
	background-color: #c4005b;
    color: #ffffff;
    padding: 7px;
    font-weight: bold;
    margin: 3px;
    font-size: 18px;
}
 .col-sm-6 {
    min-height: 1px;
    padding-left: 0px !important;
    padding-right: 0px!important;
}
.comnt_author{
	 padding-top: 5px!important;
    padding-bottom: 5px!important;
}
.highlighted {
  background-color: yellow;
}
</style>

@stop
