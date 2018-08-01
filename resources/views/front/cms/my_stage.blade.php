@extends('front.layouts.default')
@section('content')
<style>
	html { overflow:auto}
	
</style>
<div id="pagepiling">
	<div class="cms-wrapper">
		<div class="container">

		<div class="facebook_wrapper">
			<div class="compose_overlay"></div>
			<div class="post_compose">
				<div class="post_compose_tabs">
					<div>
						<div class="compose_container">
							<div class="my-row">
								<div class="col-1 text-center">
									<a href="#!">
										<img src="../img/usr_img.png" alt="" class="compose_image">
									</a>
								</div>
								<div class="col-2">
									<textarea class="compose_input">Write something here ....</textarea> 
								</div>
							</div>
						</div>
					</div>
					<div class="helping-btns">
						<ul>
							<li>
								<a href="#!" class="image-video-upload">
									Photo/Video
								</a>
							</li>
							<li class="pull-right">
								<a href="#!" class="post-share">
									Share
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="fb-posts">
				<div class="my-row">
					<div class="col-1">
						<a href="#!">
							<img src="../img/usr_img.png" alt="" class="compose_image">
						</a>
					</div>
					<div class="col-2">
						<div>
							<span>
								<a href="#!">
									<b>Balogun Hammed Ademola</b>
								</a>
							</span>
							<span>
								shared a
							</span>
							<span>
								<a href="#!">link</a>
							</span>
							<span>to the group: </span>
							<span>
								<a href="#!">
									NIGERIAN IMPORTERS & FOREIGN SELLERS TRADING.
								</a>
							</span>
						</div>
						
						<div class="fb-post-date">
							<span>
								15 July at 05:46
							</span>
						</div>
					</div>
				</div>
				<div class="fb-post-content">
					<p>
						Hello,<br>
						Balotrade.com, the world B2B marketplace can help you sell all your products in USA markets and many other countries markets across the world, you may ask HOW?<br>
						First thing is to register on balotrade.com B2B marketplace and add all your products, it's 100% free no money require, then you start selling worldwide on a single platform, very simple to use give a try.
						<br>
						<br>
						Note you will also get your own company website on balotrade.com B2B marketplace platform for free....
					</p>
				</div>
				<div class="fb-post-image">
					<img src="../img/bg.png">
				</div>
				<div class="fb-post-count">
					<div>
						<div class="pull-left">
							<a href="#!" data-toggle="modal" data-target="#like-modal">
								<img src="../img/like-count.png" alt="">
								<span>2M</span>
							</a>
						</div>
						<div class="pull-right">
							<span>39K Comments</span>
							<span>1.1M Shares</span>
							<span>89M Views</span>
						</div>
					</div>
				</div>
				<div class="post-action">
					<ul>
						<li>
							<a href="#!" class="fb-post-action like">
								<img src="../img/like.png" alt="">
								like
							</a>
						</li>
						<li>
							<a href="#!" class="fb-post-action comment">
								<img src="../img/comment.png" alt="">
								Comment
							</a>
						</li>
						<li>
							<a href="#!" class="fb-post-action share">
								<img src="../img/share-count.png" alt="">
								Share
							</a>
						</li>
					</ul>
				</div>
				<div class="post-commnet">
					<div class="my-row">
						<div class="col-1 text-center">
							<a href="#!">
								<img src="../img/usr_img.png" alt="" class="compose_image">
							</a>
						</div>
						<div class="col-2">
							<input type="text" name="" placeholder="write a commnet....">
							<small>Press Enter to post.</small>
						</div>
					</div>
				</div>

				<div class="fb-comments">
					<div class="my-row">
						<div class="col-1 text-center">
							<a href="#!">
								<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/c160.38.479.479/s32x32/487972_429520640472192_224792943_n.jpg?_nc_cat=0&oh=89bc96a5be81c84a0d5cb7daabea1b00&oe=5C13AB88" alt="" class="compose_image">
							</a>
						</div>
						<div class="col-2">
							<div class="fb-comment-content">
								<p>
									<span><a href="#!">Sandeep Prajapati</a></span>
									<span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
									tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
									quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
									consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
									cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
									proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
								</p>
							</div>
							<div class="fb-comment-info">
								<ul>
									<li>
										<a href="#!">Like</a>
									</li>
									<li>
										<a href="#!">Reply</a>
									</li>
									<li>
										<a href="#!">Edit</a>
									</li>
								</ul>
							</div>

							<div class="fb-previous-replies">
								<a href="#!">
									View previous replies
								</a>
							</div>

						</div>
					</div>

					<div class="my-row">
						<div class="col-1 text-center">
							<a href="#!">
								<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p32x32/18813937_10155021266148580_58287202660520872_n.jpg?_nc_cat=0&oh=43426116d54812af3b492f2459aa5657&oe=5C0B3927" alt="" class="compose_image">
							</a>
						</div>
						<div class="col-2">
							<div class="fb-comment-content">
								<p>
									<span><a href="#!">Sandeep Prajapati</a></span>
									<span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
									tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
									quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
									consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
									cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
									proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
								</p>
							</div>
							<div class="fb-comment-info">
								<ul>
									<li>
										<a href="#!">Like</a>
									</li>
									<li>
										<a href="#!">Reply</a>
									</li>
									<li>
										<a href="#!">Edit</a>
									</li>
								</ul>
							</div>

							<!-- Comment Reply -->
							
							<div class="my-row">
								<div class="col-1 text-center">
									<a href="#!">
										<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p32x32/18813937_10155021266148580_58287202660520872_n.jpg?_nc_cat=0&oh=43426116d54812af3b492f2459aa5657&oe=5C0B3927" alt="" class="compose_image">
									</a>
								</div>
								<div class="col-2">
									<div class="fb-comment-content">
										<p>
											<span><a href="#!">Sandeep Prajapati</a></span>
											<span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
											tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
											quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
											consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
											cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
											proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
										</p>
									</div>
									<div class="fb-comment-info">
										<ul>
											<li>
												<a href="#!">Like</a>
											</li>
											<li>
												<a href="#!">Reply</a>
											</li>
											<li>
												<a href="#!">Edit</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>

					<a href="#!" class="fb-comments-more">
						<span>
							View more comments
						</span>
					</a>
				</div>
			</div>

			<div class="fb-posts">
				<div class="my-row">
					<div class="col-1">
						<a href="#!">
							<img src="../img/usr_img.png" alt="" class="compose_image">
						</a>
					</div>
					<div class="col-2">
						<div>
							<span>
								<a href="#!">
									<b>Balogun Hammed Ademola</b>
								</a>
							</span>
							<span>
								shared a
							</span>
							<span>
								<a href="#!">link</a>
							</span>
							<span>to the group: </span>
							<span>
								<a href="#!">
									NIGERIAN IMPORTERS & FOREIGN SELLERS TRADING.
								</a>
							</span>
						</div>
						
						<div class="fb-post-date">
							<span>
								15 July at 05:46
							</span>
						</div>
					</div>
				</div>
				<div class="fb-post-content">
					<p>
						Hello,<br>
						Balotrade.com, the world B2B marketplace can help you sell all your products in USA markets and many other countries markets across the world, you may ask HOW?<br>
						First thing is to register on balotrade.com B2B marketplace and add all your products, it's 100% free no money require, then you start selling worldwide on a single platform, very simple to use give a try.
						<br>
						<br>
						Note you will also get your own company website on balotrade.com B2B marketplace platform for free....
					</p>
				</div>
				<div class="fb-post-image">
					<div class="fb-grid">
						<div class="grid-row">
							<div class="grid-column" style="background-image:url(https://www.w3schools.com//w3images/rocks.jpg);">
							</div>
							<div class="grid-column" style="background-image: url(https://www.w3schools.com//w3images/wedding.jpg);">
							</div>
							<div class="grid-column" style="background-image: url(https://www.w3schools.com//w3images/wedding.jpg);">
							</div>
							<div class="grid-column" style="background-image: url(https://www.w3schools.com//w3images/rocks.jpg);">
							</div>
							<div class="grid-column" style="background-image: url(https://www.w3schools.com//w3images/wedding.jpg);">
							</div>
						</div>
					</div>
				</div>
				<div class="fb-post-count">
					<div>
						<div class="pull-left">
							<a href="#!" data-toggle="modal" data-target="#like-modal">
								<img src="../img/like-count.png" alt="">
								<span>2M</span>
							</a>
						</div>
						<div class="pull-right">
							<span>39K Comments</span>
							<span>1.1M Shares</span>
							<span>89M Views</span>
						</div>
					</div>
				</div>
				<div class="post-action">
					<ul>
						<li>
							<a href="#!" class="fb-post-action like">
								<img src="../img/like.png" alt="">
								like
							</a>
						</li>
						<li>
							<a href="#!" class="fb-post-action comment">
								<img src="../img/comment.png" alt="">
								Comment
							</a>
						</li>
						<li>
							<a href="#!" class="fb-post-action share">
								<img src="../img/share-count.png" alt="">
								Share
							</a>
						</li>
					</ul>
				</div>
				<div class="post-commnet">
					<div class="my-row">
						<div class="col-1 text-center">
							<a href="#!">
								<img src="../img/usr_img.png" alt="" class="compose_image">
							</a>
						</div>
						<div class="col-2">
							<input type="text" name="" placeholder="write a commnet....">
							<small>Press Enter to post.</small>
						</div>
					</div>
				</div>

				<div class="fb-comments">
					<div class="my-row">
						<div class="col-1 text-center">
							<a href="#!">
								<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/c160.38.479.479/s32x32/487972_429520640472192_224792943_n.jpg?_nc_cat=0&oh=89bc96a5be81c84a0d5cb7daabea1b00&oe=5C13AB88" alt="" class="compose_image">
							</a>
						</div>
						<div class="col-2">
							<div class="fb-comment-content">
								<p>
									<span><a href="#!">Sandeep Prajapati</a></span>
									<span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
									tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
									quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
									consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
									cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
									proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
								</p>
							</div>
							<div class="fb-comment-info">
								<ul>
									<li>
										<a href="#!">Like</a>
									</li>
									<li>
										<a href="#!">Reply</a>
									</li>
									<li>
										<a href="#!">Edit</a>
									</li>
								</ul>
							</div>

							<div class="fb-previous-replies">
								<a href="#!">
									View previous replies
								</a>
							</div>

						</div>
					</div>

					<div class="my-row">
						<div class="col-1 text-center">
							<a href="#!">
								<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p32x32/18813937_10155021266148580_58287202660520872_n.jpg?_nc_cat=0&oh=43426116d54812af3b492f2459aa5657&oe=5C0B3927" alt="" class="compose_image">
							</a>
						</div>
						<div class="col-2">
							<div class="fb-comment-content">
								<p>
									<span><a href="#!">Sandeep Prajapati</a></span>
									<span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
									tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
									quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
									consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
									cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
									proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
								</p>
							</div>
							<div class="fb-comment-info">
								<ul>
									<li>
										<a href="#!">Like</a>
									</li>
									<li>
										<a href="#!">Reply</a>
									</li>
									<li>
										<a href="#!">Edit</a>
									</li>
								</ul>
							</div>

							<!-- Comment Reply -->
							
							<div class="my-row">
								<div class="col-1 text-center">
									<a href="#!">
										<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p32x32/18813937_10155021266148580_58287202660520872_n.jpg?_nc_cat=0&oh=43426116d54812af3b492f2459aa5657&oe=5C0B3927" alt="" class="compose_image">
									</a>
								</div>
								<div class="col-2">
									<div class="fb-comment-content">
										<p>
											<span><a href="#!">Sandeep Prajapati</a></span>
											<span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
											tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
											quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
											consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
											cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
											proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
										</p>
									</div>
									<div class="fb-comment-info">
										<ul>
											<li>
												<a href="#!">Like</a>
											</li>
											<li>
												<a href="#!">Reply</a>
											</li>
											<li>
												<a href="#!">Edit</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>

					<a href="#!" class="fb-comments-more">
						<span>
							View more comments
						</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="like-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<span><img src="../img/like-count.png" alt=""></span>
				<span><b>Post Likes</b></span>
			</div>
			<div class="modal-body">
				<div class="my-row">
					<div class="col-1 text-center">
						<a href="#!">
							<img src="../img/usr_img.png" alt="" class="compose_image">
							<span>
								<img src="../img/like-count.png" alt="">
							</span>
						</a>
					</div>
					<div class="col-2">
						<a href="#!" class="like-model-name">
							Sandeep Prajapati
						</a>
					</div>
				</div>
				<div class="my-row">
					<div class="col-1 text-center">
						<a href="#!">
							<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p40x40/37668097_102590134008407_5327658204836921344_n.jpg?_nc_cat=0&oh=b5fcec3072a73c8b8c72f76ae3543b5e&oe=5BCBA826" alt="" class="compose_image">
							<span>
								<img src="../img/like-count.png" alt="">
							</span>
						</a>
					</div>
					<div class="col-2">
						<a href="#!" class="like-model-name">
							Samdaling Budala
						</a>
					</div>
				</div>
				<div class="my-row">
					<div class="col-1 text-center">
						<a href="#!">
							<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p40x40/37680027_102387464027843_7742474125012107264_n.jpg?_nc_cat=0&oh=4190da70749a73c593ea3267da29164f&oe=5BDEFC7B" alt="" class="compose_image">
							<span>
								<img src="../img/like-count.png" alt="">
							</span>
						</a>
					</div>
					<div class="col-2">
						<a href="#!" class="like-model-name">
							PapPya Gaikwad
						</a>
					</div>
				</div>
				<div class="my-row">
					<div class="col-1 text-center">
						<a href="#!">
							<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p40x40/37547941_100277710906088_3612265609892462592_n.jpg?_nc_cat=0&oh=b967aab897079ba4f386d5d66a8be911&oe=5BD4CC90" alt="" class="compose_image">
							<span>
								<img src="../img/like-count.png" alt="">
							</span>
						</a>
					</div>
					<div class="col-2">
						<a href="#!" class="like-model-name">
							Pankaj Gupta
						</a>
					</div>
				</div>
				<div class="my-row">
					<div class="col-1 text-center">
						<a href="#!">
							<img src="../img/usr_img.png" alt="" class="compose_image">
							<span>
								<img src="../img/like-count.png" alt="">
							</span>
						</a>
					</div>
					<div class="col-2">
						<a href="#!" class="like-model-name">
							Sandeep Prajapati
						</a>
					</div>
				</div>
				<div class="my-row">
					<div class="col-1 text-center">
						<a href="#!">
							<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p40x40/37668097_102590134008407_5327658204836921344_n.jpg?_nc_cat=0&oh=b5fcec3072a73c8b8c72f76ae3543b5e&oe=5BCBA826" alt="" class="compose_image">
							<span>
								<img src="../img/like-count.png" alt="">
							</span>
						</a>
					</div>
					<div class="col-2">
						<a href="#!" class="like-model-name">
							Samdaling Budala
						</a>
					</div>
				</div>
				<div class="my-row">
					<div class="col-1 text-center">
						<a href="#!">
							<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p40x40/37680027_102387464027843_7742474125012107264_n.jpg?_nc_cat=0&oh=4190da70749a73c593ea3267da29164f&oe=5BDEFC7B" alt="" class="compose_image">
							<span>
								<img src="../img/like-count.png" alt="">
							</span>
						</a>
					</div>
					<div class="col-2">
						<a href="#!" class="like-model-name">
							PapPya Gaikwad
						</a>
					</div>
				</div>
				<div class="my-row">
					<div class="col-1 text-center">
						<a href="#!">
							<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p40x40/37547941_100277710906088_3612265609892462592_n.jpg?_nc_cat=0&oh=b967aab897079ba4f386d5d66a8be911&oe=5BD4CC90" alt="" class="compose_image">
							<span>
								<img src="../img/like-count.png" alt="">
							</span>
						</a>
					</div>
					<div class="col-2">
						<a href="#!" class="like-model-name">
							Pankaj Gupta
						</a>
					</div>
				</div>
				<div class="my-row">
					<div class="col-1 text-center">
						<a href="#!">
							<img src="../img/usr_img.png" alt="" class="compose_image">
							<span>
								<img src="../img/like-count.png" alt="">
							</span>
						</a>
					</div>
					<div class="col-2">
						<a href="#!" class="like-model-name">
							Sandeep Prajapati
						</a>
					</div>
				</div>
				<div class="my-row">
					<div class="col-1 text-center">
						<a href="#!">
							<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p40x40/37668097_102590134008407_5327658204836921344_n.jpg?_nc_cat=0&oh=b5fcec3072a73c8b8c72f76ae3543b5e&oe=5BCBA826" alt="" class="compose_image">
							<span>
								<img src="../img/like-count.png" alt="">
							</span>
						</a>
					</div>
					<div class="col-2">
						<a href="#!" class="like-model-name">
							Samdaling Budala
						</a>
					</div>
				</div>
				<div class="my-row">
					<div class="col-1 text-center">
						<a href="#!">
							<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p40x40/37680027_102387464027843_7742474125012107264_n.jpg?_nc_cat=0&oh=4190da70749a73c593ea3267da29164f&oe=5BDEFC7B" alt="" class="compose_image">
							<span>
								<img src="../img/like-count.png" alt="">
							</span>
						</a>
					</div>
					<div class="col-2">
						<a href="#!" class="like-model-name">
							PapPya Gaikwad
						</a>
					</div>
				</div>
				<div class="my-row">
					<div class="col-1 text-center">
						<a href="#!">
							<img src="https://scontent.fdel2-2.fna.fbcdn.net/v/t1.0-1/p40x40/37547941_100277710906088_3612265609892462592_n.jpg?_nc_cat=0&oh=b967aab897079ba4f386d5d66a8be911&oe=5BD4CC90" alt="" class="compose_image">
							<span>
								<img src="../img/like-count.png" alt="">
							</span>
						</a>
					</div>
					<div class="col-2">
						<a href="#!" class="like-model-name">
							Pankaj Gupta
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	
   @include('front.elements.footer')
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		$(".compose_input").focus(function(){
			$(this).parents('.post_compose').addClass("active");
			$('.compose_overlay').addClass('active');
			$('html').css('overflow-y','hidden');

		}).blur(function(){
			$(this).parents('.post_compose').removeClass("active");
			$('.compose_overlay').removeClass('active');
			$('html').css('overflow-y','auto');
		})
	}); 
</script>
@stop
