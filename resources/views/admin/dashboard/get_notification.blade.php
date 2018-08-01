@if(!empty($notifications))
	@foreach($notifications as $notification)
		<li>
			<a href="javascript:void(0)">
				<span class="message_icon">
					<b>{{ $notification->full_name }}</b>
				</span>
				<br>
				<div class="max-height-30-span">
					@if(!empty( $notification->blog_id))
						{{ $notification->blog_name }}
					@elseif(!empty( $notification->event_id))
						{{ $notification->event_name }}
					@elseif(!empty( $notification->ads_id))
						{{ $notification->ad_name }}
					@elseif(!empty( $notification->despute_id))
						{{ $notification->dispute_name }}
					@elseif(!empty( $notification->product_id))
						{{ $notification->product_name }}
					@elseif(!empty( $notification->forum_id))
						{{ $notification->forum_name }}
					@elseif(!empty( $notification->story_id))
						{{ Str::limit($notification->story_name,20) }}
					@elseif(!empty( $notification->withdraw_request_id))
						{{Config::get("Site.currencyCode")}} {{ $notification->withdraw_amount }}
					@elseif($notification->type == APPROVEL_REQUEST)
						{{ $notification->full_name }} Approvel Request
					@endif
				</div>
				<span class="pro_date">
					{{ $notification->created_at }}
				</span>
			</a>
		</li>
	@endforeach
@else
	<li>
		<center>
			<strong><?php echo trans('No new notifications found.'); ?></strong>
		</center>
	</li>
@endif
<li class="viewmore">
	<a href="{{{ URL('cmeshinepanel/notifications') }}}" class="view_all_msg">
		<?php echo trans('View all notifications'); ?>
	</a>
</li>
