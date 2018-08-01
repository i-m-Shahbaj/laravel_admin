<!DOCTYPE html>
<html>
	<head>
		<title>{{{Config::get("Site.title")}}}</title>
		{{ HTML::script('js/admin/jquery.min.js') }}
		{{ HTML::style('css/admin/bootstrap.min.css') }}
		{{ HTML::style('css/admin/font-awesome.min.css') }}
		{{ HTML::style('css/admin/ionicons.min.css') }}
		{{ HTML::style('css/admin/morris/morris.css') }}
		{{ HTML::style('css/admin/jvectormap/jquery-jvectormap-1.2.2.css') }}
		{{ HTML::style('css/admin/daterangepicker/daterangepicker-bs3.css') }}
		{{ HTML::style('css/admin/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}
		{{ HTML::style('css/admin/themify-icons.css') }}
		{{ HTML::style('css/admin/AdminLTE.css') }}
		{{ HTML::style('css/admin/custom_admin.css') }}
		{{ HTML::style('css/admin/bootmodel.css') }}
		{{ HTML::script('css/admin/notification/jquery.toastmessage.js') }}
		{{ HTML::style('css/admin/notification/jquery.toastmessage.css') }}
		{{ HTML::script('js/admin/vendors/match-height/jquery.equalheights.js') }}
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<style>
			i.i_icon_class {
				background: #2c7be0;
				color: #fff;
				font-style: normal;
				border-radius: 50%;
				width: 20px;
				display: inline-block;
				text-align: center;
				vertical-align: top;
				line-height: 20px;
				font-size: 11px;
				margin: -8px 0px 0px -5px;
			}
			.notification_dropdown{
				margin-top:15px;
			}
			.notification_dropdown .dropdown-menu li.viewmore a {
				color: #fff;
			}
			developer.css:1100
			.notification_dropdown .dropdown-menu li a {
				color: #3a3737;
				font-size: 13px;
				line-height: 14px;
				padding: 8px 10px 8px 25px;
				white-space: normal;
				width: 100%;
			}
			.notification_dropdown .dropdown-menu li.viewmore {
				text-align: center;
			}
			.notification_dropdown .dropdown-menu li.viewmore {
				background-color: #022244;
				border-bottom-color: transparent;
				border-radius: 0 0 4px 4px;
			}
			.notification-body > li > a:hover {
				background-color: #fff;
				color: #f9f9f9;
			}
			.notification_dropdown .dropdown-menu > li > a.view_all_msg:hover, .dropdown-menu > li > a.view_all_msg:focus {
				background-color: #022244;
				color: #fff;
				text-decoration: none;
			}
		</style>
	</head>
	<body class="skin-black">
		<header class="header"> <a href="{{route('dashboard.showdashboard')}}" class="logo"> 
		  <!-- Add the class icon to your logo image or logo icon to add the margining --> 
			{{{Config::get("Site.title")}}}
		  </a> 
		  <!-- Header Navbar: style can be found in header.less -->
		  <nav class="navbar navbar-static-top" role="navigation"> 
			<!-- Sidebar toggle button--> 
			<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
			<div class="navbar-right">
			  <ul class="nav navbar-nav">
<?php /*				<script>
						setInterval(function(){
							$.ajax({
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								},
								url:"{{ URL('cmeshinepanel/get-all-notification') }}",
								type:'POST',
								success:function(data){
									if(data>0){
										$(".notification_counter_class").html(data);
									}
								}
							});
						}, 9000);
				</script>
				<?php $notifications  = CustomHelper::getNotifications(); 
					$countNotifications = count($notifications);	
				?>
				<li class="margin-right-15">
					<div class="notification_dropdown notification_dropdown_main_div notify_dropdown notify_dropdown_main_div">
						<a id="dLabel" class="notify-request" data-target="#" href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" onclick="updateNotificationCounter()">
							<i class="fa fa-bell"></i>
							@if($countNotifications > 0)
							<i class="notification_counter_class i_icon_class">
								{{ $countNotifications }}
							</i>
							@else
								<i class="notification_counter_class i_icon_class"></i>
							@endif
						</a>
						<ul class="dropdown-menu notification-body msg_drp_down_dv notifyBody" aria-labelledby="dLabel">
							@if(!empty($notifications))
								@foreach($notifications as $notification)
									<li>
										<a href="javascript:void(0)">
											<span class="message_icon">
												<b>{{ $notification->full_name }}</b>
											</span>
											<br>
											<div class="max-height-30-span">
												
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
								<a href="{{{ route('dashboard.notifications') }}}" class="view_all_msg">
									<?php echo trans('View all notifications'); ?>
								</a>
							</li>
						</ul>
					</div>	
				</li> */ ?>
				<li class="dropdown user user-menu"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"> <i class="glyphicon glyphicon-user"></i> <span>{{{ Auth::user()->email}}} <i class="caret"></i></span> </a>
				  <ul class="dropdown-menu">
					<!-- Menu Footer-->
					<li class="user-footer">
					  <div class="pull-left"><a class="btn btn-default btn-flat" href="{{route('dashboard.myaccount')}}">{{ trans("messages.dashboard.edit_profile") }} </a> </div>
					  <div class="pull-right"> <a class="btn btn-default btn-flat" href="{{route('home.logout')}}">{{ trans("messages.dashboard.logout") }} </a></div>
					</li>
				  </ul>
				</li>
			  </ul>
			</div>
		  </nav>
		</header>
		
		<!-- Start Main Wrapper -->
		<div class="wrapper row-offcanvas row-offcanvas-left">
			<?php 
				$segment2	=	Request::segment(1);
				$segment3	=	Request::segment(2); 
				$segment4	=	Request::segment(3); 
				$segment5	=	Request::segment(4); 
			?>
			<aside class="left-side sidebar-offcanvas"> 
				<section class="sidebar"> 
					<ul class='sidebar-menu'>
						<li class="{{ ($segment3 == 'dashboard') ? 'active' : '' }} "><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-home  {{ ($segment3 == 'dashboard') ? '' : '' }}"></i>{{ trans("messages.system_management.dashboard") }} </a></li>
						
						<li class="{{ ($segment3 == 'users') ? 'active' : '' }}">
							<a href="{{route('User.index')}}"><i class="fa fa-users "></i>{{ trans("messages.user_management.user_management") }} </a>
						</li>
						
						<li class="{{ ($segment3 == 'sub-admin') ? 'active' : '' }}">
							<a href="{{route('SubAdmin.index')}}"><i class="fa fa-users "></i>{{ trans("Sub Admin Management") }} </a>
						</li>
						 
						
						<li class="treeview {{ in_array($segment3 ,array('blog')) ? 'active in' : 'offer-reports' }}">
							<a href="javascript::void(0)"><i class="fa fa-book  {{ in_array($segment3 ,array('blog')) ? '' : '' }}"></i><i class="fa pull-right fa-angle-left"></i>{{ trans("Blog Management") }} </a>
							<ul class="treeview-menu {{ in_array($segment3 ,array('blog')) ? 'open' : 'closed' }}" style="treeview-menu {{ in_array($segment3 ,array('blog')) ? 'display:block;' : 'display:none;' }}">
								<li  @if($segment4 =='categories') class="active" @endif>
									<a href="{{route('ProjectFolder.index')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Cateogories") }} </a>
								</li>  
								
								<li @if($segment4=='content') class="active" @endif ><a href="{{route('ProjectFolderArticle.conetentIndex')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Content") }}</a></li>
								 
							</ul>
						</li>
						<li class="{{ ($segment3 == 'challenges') ? 'active' : '' }}">
							<a href="{{route('Challenge.index')}}"><i class="fa fa-hourglass-start"></i>
							{{ trans("Challenges") }} </a>
						</li>
						
						<li class="{{ ($segment3 == 'product-manager') ? 'active in' : '' }}">
							<a href="{{route('Product.index')}}">
								<i class="fa fa-shopping-cart {{ $segment2 == 'product-manager' ? 'fa-spin' : '' }}"></i>
								{{'Product Management'}} 
							</a>
						</li>
						
						<li class="{{ ($segment3 == 'questions') ? 'active' : '' }}">
							<a href="{{route('Question.index')}}"><i class="fa fa-language"></i>
							{{ trans("Question Management ") }} </a>
						</li>
						
						<li class="{{ ($segment3 == 'events') ? 'active' : '' }}">
							<a href="{{route('Event.index')}}"><i class="fa fa-calendar"></i>
							{{ trans("Event Management ") }} </a>
						</li>
						
						<li class="{{ ($segment3 == 'newsfeed') ? 'active' : '' }}">
							<a href="{{route('Newsfeed.index')}}"><i class="fa fa-newspaper-o"></i>
							{{ trans("Press Release") }} </a>
						</li>
						
					
						<li class="treeview {{ in_array($segment3 ,array('cms-manager','no-cms-manager','contact-manager','faqs-manager','system-doc-manager','testimonial-manager','how-it-work-manager','news-letter','site_updates','home-content')) ? 'active in' : 'offer-reports' }}">
							<a href="javascript::void(0)"><i class="fa fa-desktop  {{ in_array($segment3 ,array('cms-manager','no-cms-manager','contact-manager','faqs-manager','system-doc-manager','testimonial-manager','how-it-work-manager','news-letter','site_updates','home-content','tutorials')) ? '' : '' }}"></i><i class="fa pull-right fa-angle-left"></i>{{ trans("Page Management") }} </a>
							<ul class="treeview-menu {{ in_array($segment3 ,array('cms-manager','no-cms-manager','contact-manager','faqs-manager','system-doc-manager','testimonial-manager','how-it-work-manager','news-letter','site_updates','home-content','tutorials')) ? 'open' : 'closed' }}" style="treeview-menu {{ in_array($segment3 ,array('cms-manager')) ? 'display:block;' : 'display:none;' }}">
								<li  @if($segment3 =='cms-manager') class="active" @endif>
									<a href="{{route('Cms.index')}}"><i class='fa fa-angle-double-right'></i>{{ trans("messages.system_management.cms_pages") }} </a>
								</li>  
								
								<li @if($segment3=='system-doc-manager') class="active" @endif ><a href="{{route('SystemDoc.index')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Pages Images") }}</a></li>
								
								<!-- <li @if($segment3=='testimonial-manager') class="active" @endif><a href="{{route('Testimonial.index')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Testimonial")}}</a></li> -->
								
								<!--<li @if($segment3=='how-it-work-manager') class="active" @endif><a href="{{URL::to('admin/how-it-work-manager')}}"><i class='fa fa-angle-double-right'></i>{{ trans("How It Works")}}</a></li>-->
								
								<li @if($segment3=='faqs-manager') class="active" @endif><a href="{{route('Faq.listFaq')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Faq Manager")}}</a></li>
								
								<li @if($segment3=='contact-manager') class="active" @endif><a href="{{route('Contact.index')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Contact Management")}}</a></li>
								
								
								<li @if($segment3=='home-content') class="active" @endif><a href="{{route('HomeContent.index')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Home Page Content")}}</a></li> 
							</ul>
						</li>
					
						<li class="treeview {{ in_array($segment3 ,array('email-manager','email-logs','block-manager')) ? 'active in' : 'offer-reports' }}">
							<a href="javascript::void(0)"><i class="fa fa-desktop  {{ in_array($segment3 ,array('email-manager','email-logs','block-manager')) ? '' : '' }}"></i><i class="fa pull-right fa-angle-left"></i>{{ trans("messages.system_management.system_management") }} </a>
							<ul class="treeview-menu {{ in_array($segment3 ,array('email-manager','email-logs','block-manager')) ? 'open' : 'closed' }}" style="treeview-menu {{ in_array($segment3 ,array('cms-manager')) ? 'display:block;' : 'display:none;' }}">
								 
								<li @if($segment3 =='email-manager') class="active" @endif ><a href="{{route('EmailTemplate.index')}}"><i class='fa fa-angle-double-right'></i>{{ trans("messages.system_management.email_templates") }} </a></li>
								
								<li @if($segment3=='email-logs') class="active" @endif><a href="{{route('EmailLogs.listEmail')}}"><i class='fa fa-angle-double-right'></i>{{ trans("messages.system_management.email_logs") }} </a></li>
								
								<li @if($segment3=='block-manager') class="active" @endif><a href="{{route('Block.index')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Block management") }} </a></li>
								 
							</ul>
						</li>
						<li class="treeview {{ in_array($segment3 ,array('dropdown-manager')) ? 'active in' : 'feedback-category' }}">
							<a href="javascript::void(0)"><i class="fa fa-th  {{ in_array($segment3 ,array('dropdown-manager')) ? '' : '' }}"></i><i class="fa pull-right fa-angle-left"></i>{{ trans("messages.masters.masters") }}</a>
							<ul class="treeview-menu {{ in_array($segment3 ,array('dropdown-manager')) ? 'open' : 'closed' }}" style="treeview-menu {{ in_array($segment3 ,array('dropdown-manager')) ? 'display:block;' : 'display:none;' }}">
								<li  @if($segment4 =='faq-categories' || $segment5 =='faq-categories') class="active" @endif>
									<a href="{{route('DropDown.listDropDown','faq-categories')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Faq Categories") }} </a>
								</li>
			
<!--
								<li  @if($segment4 =='question-categories' || $segment5 =='question-categories') class="active" @endif>
									<a href="{{route('DropDown.listDropDown','question-categories')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Question Categories") }} </a>
								</li>
-->

								<li  @if($segment4 =='challenge-categories' || $segment5 =='challenge-categories') class="active" @endif>
									<a href="{{route('DropDown.listDropDown','challenge-categories')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Challenges Categories") }} </a>
								</li>
								<li  @if($segment3 =='dropdown-manager' && $segment4 == 'product-category') class="active" @endif>
									<a href="{{route('DropDown.listDropDown','product-category')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Product Category") }} </a>
								</li>
								<li  @if($segment3 =='dropdown-manager' && $segment4 == 'product-size') class="active" @endif>
									<a href="{{route('DropDown.listDropDown','product-size')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Size") }} </a>
								</li>
							</ul>
						</li>
						<!--<li class="{{ ($segment3 == 'language') ? 'active' : '' }}">
							<a href="{{URL::to('admin/language')}}"><i class="fa fa-language"></i>{{ trans("Languages") }} </a>
						</li>-->
						<li class="treeview {{ in_array($segment3 ,array('settings')) ? 'active in' : 'offer-reports' }}">
							<a href="javascript::void(0)"><i class="fa fa-cogs  {{ in_array($segment3 ,array('settings')) ? '' : '' }}"></i><i class="fa pull-right fa-angle-left"></i>{{ trans("messages.system_management.settings")  }} </a>
							<ul class="treeview-menu {{ in_array($segment3 ,array('settings')) ? 'open' : 'closed' }}" style="treeview-menu {{ in_array($segment3 ,array('settings')) ? 'display:block;' : 'display:none;' }}">
								<li  @if($segment3=='settings' && Request::segment(4)=='site') class="active" @endif>
									<a href="{{route('settings.prefix','site')}}"><i class='fa fa-angle-double-right'></i>{{ trans("messages.settings.site_setting") }} </a>
								</li>
								<li  @if($segment3=='settings' && Request::segment(4)=='Reading') class="active" @endif>
									<a href="{{route('settings.prefix','Reading')}}"><i class='fa fa-angle-double-right'></i>{{ trans("messages.settings.reading_setting") }} </a>
								</li>
								
								<li  @if($segment3=='settings' && Request::segment(4)=='Social') class="active" @endif>
									<a href="{{route('settings.prefix','Social')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Social Media") }} </a>
								</li>
								<li  @if($segment3=='settings' && Request::segment(4)=='Contact') class="active" @endif>
									<a href="{{route('settings.prefix','Contact')}}"><i class='fa fa-angle-double-right'></i>{{ trans("Contact Setting") }} </a>
								</li>
							</ul>
						</li>
						<li class="{{ ($segment3 == 'dance-star-post') ? 'active' : '' }}">
							<a href="{{route('DanceStarPost.index')}}"><i class="fa fa-tags "></i>{{ trans("DanceStar Post") }} </a>
						</li>
					</ul>
				</section>
			</aside>
			  <!-- Main Container Start -->
				<aside class="right-side"> 
						@if(Session::has('error'))
							<script type="text/javascript"> 
								$(document).ready(function(e){
									
									show_message("{{{ Session::get('error') }}}",'error');
								});
							</script>
						@endif
						
						@if(Session::has('success'))
							<script type="text/javascript"> 
								$(document).ready(function(e){
									show_message("{{{ Session::get('success') }}}",'success');
								});
							</script>
						@endif

						@if(Session::has('flash_notice'))
							<script type="text/javascript"> 
								$(document).ready(function(e){
									show_message("{{{ Session::get('flash_notice') }}}",'success');
								});
							</script>
						@endif
						
						@yield('content')
						
				</aside>
		</div>
		<?php echo Config::get("Site.copyright_text"); ?>
	</body>
</html>
{{ HTML::script('js/admin/bootbox.js') }}
{{ HTML::script('js/admin/core/mws.js') }}
{{ HTML::script('js/admin/core/themer.js') }}
{{ HTML::script('js/admin/bootstrap.js') }}
{{ HTML::script('js/admin/app.js') }}
{{ HTML::script('js/admin/plugins/fancybox/jquery.fancybox.js') }}
{{ HTML::style('js/admin/plugins/fancybox/jquery.fancybox.css') }}
{{ HTML::style('css/admin/bootmodel.css') }}
<script type="text/javascript">
	function updateNotificationCounter(){
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url:"{{ URL('cmeshinepanel/get-notification') }}",
			type:'POST',
			success:function(data){
				$(".notifyBody").html(data);
				$(".notify_dropdown").addClass('open');
				$(".notification_counter_class").html("");
			}
		});
	}

	function show_message(message,message_type) {
		$().toastmessage('showToast', {	
			text: message,
			sticky: false,
			position: 'top-right',
			type: message_type,
		});
	}
			
	$(function(){
		$('.fancybox').fancybox();
		$('.fancybox-buttons').fancybox({
			openEffect  : 'none',
			closeEffect : 'none',
			prevEffect : 'none',
			nextEffect : 'none',
		});
		
		$(document).on('click', '.delete_any_item', function(e){ 
			e.stopImmediatePropagation();
			url = $(this).attr('href');
			bootbox.confirm("Are you sure want to delete this ?",
			function(result){
				if(result){
					window.location.replace(url);
				}
			});
			e.preventDefault();
		});
		
		/**
		 * Function to change status
		 *
		 * @param null
		 *
		 * @return void
		 */
		$(document).on('click', '.status_any_item', function(e){ 
			e.stopImmediatePropagation();
			url = $(this).attr('href');
			bootbox.confirm("Are you sure want to change status ?",
			function(result){
				if(result){
					window.location.replace(url);
				}
			});
			e.preventDefault();
		});
		
		$(document).on('click', '.reset_form', function(e){ 
			e.stopImmediatePropagation();
			url = $(this).attr('href');
			bootbox.confirm("Are you sure want to clear form ?",
			function(result){
				if(result){
					window.location.replace(url);
				}
			});
			e.preventDefault();
		});
		
		$('.open').parent().addClass('active');
		$('.fancybox').fancybox();
		$('.fancybox-buttons').fancybox({
			openEffect  : 'none',
			closeEffect : 'none',
			prevEffect : 'none',
			nextEffect : 'none',
		});
	

		$('.skin-black .sidebar > .sidebar-menu > li > a').click(function(e) {
			if(!($(this).next().hasClass("open"))) { 
				$(".treeview-menu").addClass("closed");
				$(".treeview-menu").removeClass("open");
				$(".treeview-menu.open").slideUp();
				$('.skin-black .sidebar > .sidebar-menu > li').removeClass("active");
			  
				$(this).next().slideDown();
				$(this).next().addClass("open");  
				$(this).parent().addClass("active"); 
				 
			}else {  
				e.stopPropagation(); 
				return false;  
			}
		}); 
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

<style>
.chosen-container-single .chosen-single{
	height:34px !important;
	padding:3px 6px;
}
</style>
