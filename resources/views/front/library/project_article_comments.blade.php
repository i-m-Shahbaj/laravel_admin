
<div class="">
	<div class="col-sm-12 comment_header">Comment</div>
	<div class="col-sm-12">
		<div class="col-sm-6 comnt_author"> <b>{{$commentData->user_full_name}}</b></div>
		<div class="col-sm-6 comnt_author"><div class="pull-right">{{ date(Config::get("Reading.date_time_format"),strtotime($commentData->created_at)) }}</div></div>
	</div>
  <hr/>
  <div class="col-sm-12 cmnt_data">
   {!!$commentData->message!!}
   </div>
   <div class="comment_reply">
	@if(!empty($commentData->comment_reply))
		@foreach($commentData->comment_reply as $comment_reply)
			
		   <div class="col-sm-3"></div>
		   <div class="col-sm-9 alert alert-info">
			   <div class="col-sm-8"><b>{{ $comment_reply->cmnt_rply_user_name}}</b></div>
			   <div class="col-sm-4 ">
					<b>{{ date(Config::get("Reading.date_time_format"),strtotime($comment_reply->created_at)) }}</b>
			  </div>
			   <div class="col-sm-12 ">
				{{$comment_reply->reply}}
			   </div>
		  </div>
	   @endforeach
	@endif
	</div>
	<div class="col-sm-3"></div>
	<div class="col-sm-9">
		<div class="cmnt_rply" style="display:none;">
			 {{ Form::textarea("reply", null, ['class'=>"form-control comment_reply","id"=>"reply",'rows'=>'2','cols'=>'5', 'placeholder'=> trans("Enter Comment") ]) }}
			 <span class="help-inline" id="reply_error"></span>
			 <input type="button" class="btn btn-primary pull-right" value="{{{ trans('Reply') }}}" onclick="saveReply({{$commentData->id}});"  >
		</div>
	</div>
	
	<div class="col-sm-12 mst_viewed">
		<div id="liked_article">
			@if($likeUnlikeArticleComment == 0)
				<i class="fa fa-thumbs-o-up like_unlike_article" title="Like" aria-hidden="true" data-rel="1" data-articleid="{{$comment_id}}"></i>
			@else
				<span ><i class="fa fa-thumbs-up like_unlike_article" title="Unlike" aria-hidden="true" data-rel="2" data-articleid="{{$comment_id}}"></i></span>
			@endif
		</div>
		<div class="pull-right reply_btn"><i class="fa fa-reply"><a href="javascript:void(0);">Reply</a></i></div></div>
	</div>

<script>
	$(".reply_btn").on("click",function(){
		$(".cmnt_rply").show();
	});

</script>



