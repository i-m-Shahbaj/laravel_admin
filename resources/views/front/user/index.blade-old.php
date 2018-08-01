@extends('front.layouts.default')
@section('content')
<div id="pagepiling">
  <div class="section" id="section1">
    <div class="fullscreen_post_bg"> </div>
    <div class="section-content"> 
      <!--<figure class="logo-banner revealOnScroll animated fadeInDown"><img src="<?php echo WEBSITE_IMG_URL; ?>logo-banner.png" alt=""  /></figure>-->
      
      @if(empty(Auth::user()))
      @if(!empty($blocks['home-1']))
		<?php echo $blocks['home-1']['description']; ?>
      @endif
      <div class="started revealOnScroll animated fadeInDown">
		<a class="signup-link" href="{{ url('signup') }}">Get Started!</a> <a href="{{ url('login') }}">Login</a>
	  </div>
	  @if(!empty($blocks['home-1-description']))
		<?php echo $blocks['home-1-description']['description']; ?>
      @endif
      <div class="mobile-apps"> <a href="javascript:void(0);"><img src="<?php echo WEBSITE_IMG_URL; ?>app-store.png" alt="" /></a> <a href="javascript:void(0);"><img src="<?php echo WEBSITE_IMG_URL; ?>play-store.png" alt="" /></a> </div>
      @else
      <div class="btSuperTitle revealOnScroll animated flipInY">
	<span>BE AN INSPIRATION</span></div>
	  @endif
    </div>
    <div class="copyright-wrapper">
    <div class="footer-links"><a href="{{ URL('pages/privacy-policy') }}">Privacy Policy</a> <a href="{{ URL('pages/terms-and-conditions') }}">Terms & Conditions</a>
<a href="{{ URL('faq') }}">FAQ</a>
<a href="{{ URL('contact-us') }}">Support</a></div>
    <div class="copyright-text"><?php echo Config::get("Site.title"); ?> &copy; <?php echo date("Y");?> | All Rights Reserved.</div>
    </div>
  </div>
  <div class="section" id="section2">
    <div class="section-content">
      <div class="logo-wrapper ">
		@if(!empty($SystemImages[DANCER_PAGE_IMAGE_ID]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[DANCER_PAGE_IMAGE_ID]))
			<figure class="dancers-bg"><img src="<?php echo SYSTEM_IMAGE_URL.$SystemImages[DANCER_PAGE_IMAGE_ID]; ?>" alt=""  /></figure>
        @endif
        @if(!empty($SystemImages[DANCER_LOGO_IMAGE_ID]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[DANCER_LOGO_IMAGE_ID]))
			<figure class="logobanner"><img src="<?php echo SYSTEM_IMAGE_URL.$SystemImages[DANCER_LOGO_IMAGE_ID]; ?>" alt=""  /></figure>
        @endif
      </div>
      @if(!empty($blocks['home-2']))
		<?php echo $blocks['home-2']['description']; ?>
      @endif
    </div>
  </div>
  <div class="section" id="section3">
    <div class="section-content">
      @if(!empty($blocks['home-3']))
		<?php echo $blocks['home-3']['description']; ?>
      @endif
      <div class="gallery-wrapper scroll-in-animation fadeInLeft animated css-animation-show" data-animation="fadeInLeft">
		@if(!empty($SystemImages[SECTION_3_IMAGE_1_IMAGE_ID]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[SECTION_3_IMAGE_1_IMAGE_ID]))
			<figure><img src="<?php echo SYSTEM_IMAGE_URL.$SystemImages[SECTION_3_IMAGE_1_IMAGE_ID]; ?>" alt=""  class="rotate-style1"/></figure>
      	@endif
      	@if(!empty($SystemImages[SECTION_3_IMAGE_2_IMAGE_ID]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[SECTION_3_IMAGE_2_IMAGE_ID]))
			<figure><img src="<?php echo SYSTEM_IMAGE_URL.$SystemImages[SECTION_3_IMAGE_2_IMAGE_ID]; ?>" alt=""  class="rotate-style2"/></figure>
      	@endif
      	@if(!empty($SystemImages[SECTION_3_IMAGE_3_IMAGE_ID]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[SECTION_3_IMAGE_3_IMAGE_ID]))
			<figure><img src="<?php echo SYSTEM_IMAGE_URL.$SystemImages[SECTION_3_IMAGE_3_IMAGE_ID]; ?>" alt=""  class="rotate-style3"/></figure>
      	@endif
      	@if(!empty($SystemImages[SECTION_3_IMAGE_4_IMAGE_ID]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[SECTION_3_IMAGE_4_IMAGE_ID]))
			<figure><img src="<?php echo SYSTEM_IMAGE_URL.$SystemImages[SECTION_3_IMAGE_4_IMAGE_ID]; ?>" alt=""  class="rotate-style4"/></figure>
      	@endif
      	@if(!empty($SystemImages[SECTION_3_IMAGE_5_IMAGE_ID]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[SECTION_3_IMAGE_5_IMAGE_ID]))
			<figure><img src="<?php echo SYSTEM_IMAGE_URL.$SystemImages[SECTION_3_IMAGE_5_IMAGE_ID]; ?>" alt=""  class="rotate-style5"/></figure>
      	@endif
      </div>
     </div>
  </div>
  <div class="section" id="section4">
    <div class="section-content">
       @if(!empty($blocks['home-4']))
		<?php echo $blocks['home-4']['description']; ?>
      @endif
       <ul class="things-todo scroll-in-animation fadeInUp animated css-animation-show" data-animation="fadeInUp">
      	@if(!empty($SystemImages[ATTACHMENT_IMAGE_1]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[ATTACHMENT_IMAGE_1]))
             <li><span><img src="<?php echo SYSTEM_IMAGE_URL.$SystemImages[ATTACHMENT_IMAGE_1]; ?>" alt="" /></span> <strong>Text</strong></li>
        @endif
      	@if(!empty($SystemImages[ATTACHMENT_IMAGE_2]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[ATTACHMENT_IMAGE_2]))
             <li><span><img src="<?php echo SYSTEM_IMAGE_URL.$SystemImages[ATTACHMENT_IMAGE_2]; ?>" alt="" /></span> <strong>Camera</strong></li>
        @endif
      	@if(!empty($SystemImages[ATTACHMENT_IMAGE_3]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[ATTACHMENT_IMAGE_3]))
             <li><span><img src="<?php echo SYSTEM_IMAGE_URL.$SystemImages[ATTACHMENT_IMAGE_3]; ?>" alt="" /></span> <strong>Link</strong></li>
        @endif
      	@if(!empty($SystemImages[ATTACHMENT_IMAGE_4]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[ATTACHMENT_IMAGE_4]))
             <li><span><img src="<?php echo SYSTEM_IMAGE_URL.$SystemImages[ATTACHMENT_IMAGE_4]; ?>" alt="" /></span> <strong>Audio</strong></li>
        @endif
      	@if(!empty($SystemImages[ATTACHMENT_IMAGE_5]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[ATTACHMENT_IMAGE_5]))
             <li><span><img src="<?php echo SYSTEM_IMAGE_URL.$SystemImages[ATTACHMENT_IMAGE_5]; ?>" alt="" /></span> <strong>Video</strong></li>
        @endif
        </ul>
     </div>
  </div>
  
   
  <div class="section" id="section5">
   <div  class="fullscreen_post_bg_footer"> </div>
    <div class="section-content">
		 @if(empty(Auth::user()))
      @if(!empty($blocks['home-5']))
		<?php echo $blocks['home-5']['description']; ?>
      @endif
     
      <div class="started revealOnScroll animated fadeInDown"><a href="{{ url('signup') }}" class="signup-link">Get Started!</a> <a href="{{ url('login') }}">Login</a></div>
      
			<div class="clearfix"></div>
       <div class="mobile-apps bordered"><a href="javascript:void(0);"><img src="<?php echo WEBSITE_IMG_URL; ?>app-store.png" alt="" /></a> <a href="javascript:void(0);"><img src="<?php echo WEBSITE_IMG_URL; ?>play-store.png" alt="" /></a> </div>
       <ul class="socilaize scroll-in-animation fadeInUp animated css-animation-show" data-animation="fadeInUp">
		   <?php
				$facebookUrl	= 	'';
				$facebook_url		=	explode("://",Config::get('Social.facebook'));
				if(count($facebook_url)>1){
					$facebookUrl	= Config::get('Social.facebook');
				}else{
					$facebookUrl	= 'http://'.Config::get('Social.facebook');
				}
			?>
			@if(!empty($facebookUrl))
			<li> <a href="{{ $facebookUrl }}" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a> </li>
			@endif
			<?php
				$twitterUrl	= 	'';
				$twitter_url		=	explode("://",Config::get('Social.twitter'));
				if(count($twitter_url)>1){
					$twitterUrl	= Config::get('Social.twitter');
				}else{
					$twitterUrl	= 'http://'.Config::get('Social.twitter');
				}
			?>
			@if(!empty($twitterUrl))
			<li> <a href="{{ $twitterUrl }}" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a> </li>
			@endif
			<?php
				$linkedinUrl	= 	'';
				$linkedin_url		=	explode("://",Config::get('Social.linkedin'));
				if(count($linkedin_url)>1){
					$linkedinUrl	= Config::get('Social.linkedin');
				}else{
					$linkedinUrl	= 'http://'.Config::get('Social.linkedin');
				}
			?>
			@if(!empty($linkedinUrl))
			<li> <a href="{{ $linkedinUrl }}" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a> </li>
			@endif
			<?php
				$googleplusUrl	= 	'';
				$googleplus_url		=	explode("://",Config::get('Social.google'));
				if(count($googleplus_url)>1){
					$googleplusUrl	= Config::get('Social.google');
				}else{
					$googleplusUrl	= 'http://'.Config::get('Social.google');
				}
			?>
			@if(!empty($googleplusUrl))
			<li> <a href="{{ $googleplusUrl }}" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a> </li>
			@endif
			<?php
				$youtubeUrl	= 	'';
				$youtube_url		=	explode("://",Config::get('Social.youtube'));
				if(count($youtube_url)>1){
					$youtubeUrl	= Config::get('Social.youtube');
				}else{
					$youtubeUrl	= 'http://'.Config::get('Social.youtube');
				}
			?>
			@if(!empty($youtubeUrl))
			<li> <a href="{{ $youtubeUrl }}" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a> </li>
			@endif
			<?php
				$instagramUrl	= 	'';
				$instagram_url		=	explode("://",Config::get('Social.instagram'));
				if(count($instagram_url)>1){
					$instagramUrl	= Config::get('Social.instagram');
				}else{
					$instagramUrl	= 'http://'.Config::get('Social.instagram');
				}
			?>
			@if(!empty($instagramUrl))
			<li> <a href="{{ $instagramUrl }}" target="_blank"><i class="fa fa-instagram"></i></a> </li>
			@endif
			<?php
				$pinterestUrl	= 	'';
				$pinterest_url		=	explode("://",Config::get('Social.pinterest'));
				if(count($instagram_url)>1){
					$pinterestUrl	= Config::get('Social.pinterest');
				}else{
					$pinterestUrl	= 'http://'.Config::get('Social.pinterest');
				}
			?>
			@if(!empty($pinterestUrl))
			<li> <a href="{{ $pinterestUrl }}" target="_blank"><i class="fa fa-pinterest"></i></a> </li>
			@endif
       </ul>
		@else
			<h2>{{{ trans("OKAY, LET'S START NOW") }}}</h2>
        @endif
     </div>
    <div class="copyright-wrapper">
   <div class="footer-links"><a href="{{ URL('pages/privacy-policy') }}">Privacy Policy</a> <a href="{{ URL('pages/terms-and-conditions') }}">Terms & Conditions</a>
<a href="{{ URL('faq') }}">FAQ</a>
<a href="{{ URL('contact-us') }}">Support</a></div>
    <div class="copyright-text"><?php echo Config::get("Site.title"); ?> &copy; <?php echo date("Y");?> | All Rights Reserved.</div>
    </div>
  </div>
</div>
<script src="<?php echo WEBSITE_JS_URL; ?>jquery.pagepiling.js"></script> 
<script>
$(document).ready(function() {
	$('#pagepiling').pagepiling({
		menu: '#menu',
		anchors: ['page1', 'page2', 'page3', 'page4', 'page5'],
		sectionsColor: [' ', '#9648b3', '#5dc2ca', '#eb6ea6', ''],
		navigation: {
			'position': 'left',
			'tooltips': ['', '', '', '', '']
		},
		afterRender: function(){
			$('#pp-nav').addClass('custom');
		},
	});
});
</script>
<?php /* System Images */ ?>
  <script>
    (function($){
        $.randomImage = {
            defaults: {
                //you can change these defaults to your own preferences.
                path: '<?php echo SYSTEM_IMAGE_URL; ?>', //change this to the path of your images
                myImages: ["<?php echo $SystemImages[BG_1_IMAGE_ID];?>","<?php echo $SystemImages[BG_2_IMAGE_ID]; ?>","<?php echo $SystemImages[BG_3_IMAGE_ID];?>","<?php echo $SystemImages[BG_4_IMAGE_ID];?>","<?php echo $SystemImages[BG_5_IMAGE_ID]; ?>"] //put image names in this bracket. ex: 'harold.jpg', 'maude.jpg', 'etc'
            }
        };
        
        $.fn.extend({
            randomImage:function(config) {
                var config = $.extend({}, $.randomImage.defaults, config);
                
                return this.each(function() {
                    var imageNames = config.myImages,
                    //get size of array, randomize a number from this
                    // use this number as the array index
                    imageNamesSize = imageNames.length,
                    lotteryNumber = Math.floor(Math.random()*imageNamesSize),
                    winnerImage = imageNames[lotteryNumber],
                    fullPath = config.path + winnerImage;
                    
                    //put this image into DOM at class of randomImage
                    // alt tag will be image filename.
                    $(this).attr({
                        style: "background-image:url("+fullPath+")",
                        alt: winnerImage
                    });
                });
            },
			randomImageForFooter:function(config) {
                var config = $.extend({}, $.randomImage.defaults, config);
                
                return this.each(function() {
                    var imageNames = config.myImages,
                    //get size of array, randomize a number from this
                    // use this number as the array index
                    imageNamesSize = imageNames.length,
                    lotteryNumber = Math.floor(Math.random()*imageNamesSize),
                    winnerImage = imageNames[lotteryNumber],
                    fullPath = config.path + winnerImage;
                    
                    //put this image into DOM at class of randomImage
                    // alt tag will be image filename.
                    $(this).attr({
                        style: "background-image:url("+fullPath+")",
                        alt: winnerImage
                    });
                });
            }	
        });
    }(jQuery));
    
    $(document).ready(function(){
        $('.fullscreen_post_bg').randomImage();
		 $('.fullscreen_post_bg_footer').randomImageForFooter();
    });
    </script>
@stop
