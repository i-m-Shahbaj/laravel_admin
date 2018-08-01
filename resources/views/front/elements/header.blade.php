<?php $segment = Request::segment(1); 
?>
@if($segment == '')
	<header class="<?php echo ($segment == '') ? 'homepage_header' : ''; ?>">
@elseif($segment == 'home-page')
	<header class="<?php echo ($segment == 'home-page') ? 'home2_header' : ''; ?>">
@else
	<header class="innerpage-header">
@endif
<div class="logo">
	<?php $logo	=	CustomHelper::get_system_image('logo-image'); ?>
	@if(!empty($logo->name) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$logo->name))
		<a href="{{ url('/') }}" class="main-logo"><img src="<?php echo SYSTEM_IMAGE_URL.$logo->name; ?>" alt="<?php echo Config::get("Site.title"); ?>" /></a>
	@else
		<a href="{{ url('/') }}"  class="main-logo"><img src="<?php echo WEBSITE_IMG_URL.'logo.png'; ?>" alt="<?php echo Config::get("Site.title"); ?>" /></a>
	@endif
     <a href="{{ url('/') }}" class="innerpage-logo"><img src="<?php echo WEBSITE_IMG_URL.'logo-inner.png'; ?>" alt="<?php echo Config::get("Site.title"); ?>" /></a>
</div>
<?php 
$segment1 = Request::segment(1);
if($segment1 != '' && in_array($segment1,array('blog','search-library','article-detail'))){?>
<div class="page-title-header">Blog</div>
<?php }elseif($segment1 == 'blog-categories'){ ?>
<div class="page-title-header">Blog Categories</div>
<?php } ?>
<!-- Crat Button -->
<div class="cart-btn">
	<a href="{{ url('/shopping-cart') }}">
		<i class="fa fa-shopping-cart"></i>
		<span>4</span>
	</a>
</div>
	<div class="login-btns"> 
		@if(empty(Auth::user()))
			<a href="{{ url('login') }}">Login</a> 
		<!--	<a href="{{ url('signup') }}">Signup</a> -->
<!--
			<a href="javascript:void();" class="sign-up">Sign Up</a> 
-->
		@else
            <div class="user-logged-in">{{ trans('Hi, ') }} 
             @if(!empty(Auth::user())) {{ Auth::user()->full_name }} @endif 
            </div>
            <a href="{{URL::to('logout')}}">Logout</a>
        @endif
    </div>
    <nav class="navbar navbar-default">
    <div class="container-fluid"> 
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Menu</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      </div>
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
			<?php /*?><li><a class="" href="{{ url('/') }}">Home</a></li><?php */?>
          <li><a class="<?php if(basename($_SERVER['REQUEST_URI'])=='about-us'){ echo "active"; } ?>" href="{{ url('pages/about-us') }}" data-toggle="tooltip" data-placement="bottom" title="About Us"><img src="<?php echo WEBSITE_IMG_URL.'about.png'; ?>" alt="" class="home-nav-icon" /> 
          <img src="<?php echo WEBSITE_IMG_URL.'white-icon/about.png'; ?>" alt="" class="innerpage-nav-icon about" /></a></li>
		  @if(!empty(Auth::user()))
          <?php /* <li><a class="" href="#">Challenges</a></li> */?>
		  @endif
           @if(!empty(Auth::user()))
          <li><a class="" href="{{ route('Page.mystage') }}" data-toggle="tooltip" data-placement="bottom" title="My Stage"><img src="<?php echo WEBSITE_IMG_URL.'stage.png'; ?>" alt=""  class="home-nav-icon"/> 
          <img src="<?php echo WEBSITE_IMG_URL.'white-icon/stage.png'; ?>" alt="" class="innerpage-nav-icon" />
          </a></li>
          @else
          <li><a class="" href="{{ url('/pages/stages') }}"  data-toggle="tooltip" data-placement="bottom" title="Stage"><img src="<?php echo WEBSITE_IMG_URL.'stage.png'; ?>" alt=""  class="home-nav-icon"/> 
          <img src="<?php echo WEBSITE_IMG_URL.'white-icon/stage.png'; ?>" alt="" class="innerpage-nav-icon" /></a></li>
          @endif
          <li><a class="" href="{{ url('/pages/leaderboard') }}"  data-toggle="tooltip" data-placement="bottom" title="Leader Board"><img src="<?php echo WEBSITE_IMG_URL.'leaderboard.png'; ?>" alt="" class="home-nav-icon"/>
          <img src="<?php echo WEBSITE_IMG_URL.'white-icon/leaderboard.png'; ?>" alt="" class="innerpage-nav-icon" /></a></li>
          <li><a class="<?php if(basename($_SERVER['REQUEST_URI'])=='contact-us'){ echo "active"; } ?>" href="{{ url('contact-us') }}"  data-toggle="tooltip" data-placement="bottom" title="Contact Us"><img src="<?php echo WEBSITE_IMG_URL.'contact.png'; ?>" alt=""  class="home-nav-icon"/> <img src="<?php echo WEBSITE_IMG_URL.'white-icon/contact.png'; ?>" alt="" class="innerpage-nav-icon" /></a></li>
          <li><a class="" href="{{ url('/blog') }}" data-toggle="tooltip" data-placement="bottom" title="Blog"><img src="<?php echo WEBSITE_IMG_URL.'blog.png'; ?>" alt=""  class="home-nav-icon"/>  <img src="<?php echo WEBSITE_IMG_URL.'white-icon/blog.png'; ?>" alt="" class="innerpage-nav-icon blog" /></a></li>
          <li><a class="" href="{{ route('Product.list') }}" data-toggle="tooltip" data-placement="bottom" title="Products"><img src="<?php echo WEBSITE_IMG_URL.'contact.png'; ?>" alt=""  class="home-nav-icon"/>  <img src="<?php echo WEBSITE_IMG_URL.'white-icon/contact.png'; ?>" alt="" class="innerpage-nav-icon blog" /></a></li>
         </ul>         
      </div>
      <!-- /.navbar-collapse --> 
    </div>
    <!-- /.container-fluid --> 
  </nav>
</header>

