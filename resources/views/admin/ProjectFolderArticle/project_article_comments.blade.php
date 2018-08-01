<div class="row pad box box-warning ">
	<table class="" style="margin-top:5px;">
		<tbody>
			<tr>
				<th  width="30%" class="text-left txtFntSze" >{{$commentData->user_full_name}}</th>
				<th  width="30%" class="text-right txtFntSze" >{{ date(Config::get("Reading.date_time_format"),strtotime($commentData->created_at)) }}</th>
				
			</tr>
		</thead>
		<tbody>
			<tr>
				<td width="30%" class="text-left txtFntSze" colspan="2">{!!$commentData->message!!}</td>
			</tr>
			<tr>
			   <td width="100%" class="text-right txtFntSze" colspan="2" style="">
				@if(!empty($commentData->comment_reply))
					@foreach($commentData->comment_reply as $comment_reply)
						
					   <div class="col-sm-4"></div>
					   <div class="col-sm-8 box alert">
						   <div class="col-sm-8 text-left"><b>{{ $comment_reply->cmnt_rply_user_name}}</b></div>
						   <div class="col-sm-4 ">
								<b>{{ date(Config::get("Reading.date_time_format"),strtotime($comment_reply->created_at)) }}</b>
						  </div>
						   <div class="col-sm-12 text-left" style="float:left;">
							{{$comment_reply->reply}}
						   </div>
					  </div>
				   @endforeach
				@endif
				</td>
			</tr>
		</tbody>
	</table>
</div>
<style>

.comnt_author {
    padding-top: 5px !important;
    padding-bottom: 5px !important;
}
.comnt_author {
    padding-top: 5px !important;
    padding-bottom: 5px !important;
}
</style>
<script>
	$(".reply_btn").on("click",function(){
		$(".cmnt_rply").show();
	});
</script>



