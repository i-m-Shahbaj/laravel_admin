	@extends('front.layouts.default')
@section('content')
<div id="pagepiling">
   <?php /*?><div class="section" id="section1">
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
  </div><?php */?>
  
  
  <div class="section" id="section1">
    <div class="section-content">
        <div class="section1-bg">
        <div class="section-heading">Finally an awesome app  for dancers! <br />Organize and Streamline dance related tasks  ans share your love of dance in our <br /> completely private, secure and Trans“PARENT”  app & online community.
        </div>
        <img src="<?php echo WEBSITE_IMG_URL; ?>section-1-bg.png" alt="" class="section1-image"/>
        </div>
    </div>
  </div>
  
  
  <div class="section" id="section2">
    <div class="section-content">
  	   <div class="cmshine-bg">
        <img src="<?php echo WEBSITE_IMG_URL; ?>logo-white-large.png" alt="" />
        </div>
    	<div class="section-h3">Finally an <span>awesome app</span> for dancers! <br />Organize and Streamline <span>dance related tasks</span> ans share your love of dance in our <span>completely private, secure and Trans“PARENT”</span> app & online community.</div>
      
    </div>
  </div>
  
  
  <div class="section" id="section3">
    <div class="section-content">
    <div class="col-sm-6 col-md-6">      
    </div>
    
    <div class="col-sm-6 col-md-6">    
    <div class="panel-group" id="accordion">
            <div class="panel panel-default active">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><figure><img src="<?php echo WEBSITE_IMG_URL; ?>star.png" alt="" /></figure> DancerStar</a>
                </h4>
              </div>
              <div id="collapseOne" class="panel-collapse collapse in">
                <ul class="dance-list">
                    <li>Connect with & encourage other DanceStars in your private social network</li>
                    <li>Invite friends & family to be your “Fans” & see your posts</li>
                    <li>Organize & manage your dance schedule</li>
                    <li>Compete in cool dance trivia challanges</li>
				</ul>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"><figure><img src="<?php echo WEBSITE_IMG_URL; ?>dance-icon.png" alt="" /></figure> DancerParent</a>
                </h4>
              </div>
              <div id="collapseFour" class="panel-collapse collapse">
                <ul class="dance-list">
                    <li>Post your DanceStar’s accomplishments & oversee their private social network</li>
                    <li>Invite family & friends to view your posts</li>
                    <li>Organize & manage your DanceStar’s schedule</li>
                    <li>Explore the blog for cool dance-related info</li>
				</ul> 
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive"><figure><img src="<?php echo WEBSITE_IMG_URL; ?>dance-pro.png" alt="" /></figure> DancePro</a>
                </h4>
              </div>
              <div id="collapseFive" class="panel-collapse collapse">
                  <ul class="dance-list">
                   <li>Cultivate amazing, supportive community at your studio</li>
                    <li>Share your passion for dance</li>
                    <li>Streamline administrative tasks, including payments & scheduling</li>
                    <li>Communicate effectively with your studio families</li>
				</ul> 
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix"><figure><img src="<?php echo WEBSITE_IMG_URL; ?>fan-only-access.png" alt="" /></figure> Fan Only Access</a>
                </h4>
              </div>
              <div id="collapseSix" class="panel-collapse collapse">
                  <ul class="dance-list">
                   <li>Become “Fans” of your favorite DancerStars</li>
                    <li>View DanceStar’s & DanceParent’s posts</li>
                    <li>Encourage your favorite DanceStars on the social network</li>
				</ul>                 
                 
              </div>
            </div>
            
        </div>    
            
     
     </div>
    
    <!--
      @if(!empty($blocks['home-3']))
		<?php //echo $blocks['home-3']['description']; ?>
      @endif
      <div class="gallery-wrapper scroll-in-animation fadeInLeft animated css-animation-show" data-animation="fadeInLeft">
		@if(!empty($SystemImages[SECTION_3_IMAGE_1_IMAGE_ID]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[SECTION_3_IMAGE_1_IMAGE_ID]))
			<figure><img src="<?php //echo SYSTEM_IMAGE_URL.$SystemImages[SECTION_3_IMAGE_1_IMAGE_ID]; ?>" alt=""  class="rotate-style1"/></figure>
      	@endif
      	@if(!empty($SystemImages[SECTION_3_IMAGE_2_IMAGE_ID]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[SECTION_3_IMAGE_2_IMAGE_ID]))
			<figure><img src="<?php //echo SYSTEM_IMAGE_URL.$SystemImages[SECTION_3_IMAGE_2_IMAGE_ID]; ?>" alt=""  class="rotate-style2"/></figure>
      	@endif
      	@if(!empty($SystemImages[SECTION_3_IMAGE_3_IMAGE_ID]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[SECTION_3_IMAGE_3_IMAGE_ID]))
			<figure><img src="<?php //echo SYSTEM_IMAGE_URL.$SystemImages[SECTION_3_IMAGE_3_IMAGE_ID]; ?>" alt=""  class="rotate-style3"/></figure>
      	@endif
      	@if(!empty($SystemImages[SECTION_3_IMAGE_4_IMAGE_ID]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[SECTION_3_IMAGE_4_IMAGE_ID]))
			<figure><img src="<?php //echo SYSTEM_IMAGE_URL.$SystemImages[SECTION_3_IMAGE_4_IMAGE_ID]; ?>" alt=""  class="rotate-style4"/></figure>
      	@endif
      	@if(!empty($SystemImages[SECTION_3_IMAGE_5_IMAGE_ID]) && File::exists(SYSTEM_IMAGE_DIRECTROY_PATH.$SystemImages[SECTION_3_IMAGE_5_IMAGE_ID]))
			<figure><img src="<?php // echo SYSTEM_IMAGE_URL.$SystemImages[SECTION_3_IMAGE_5_IMAGE_ID]; ?>" alt=""  class="rotate-style5"/></figure>
      	@endif-->
        
        
      </div>
     
  </div>
  <div class="section" id="section4">
    <div class="section-content">
     <div class="section4-bg">
        <img src="<?php echo WEBSITE_IMG_URL; ?>section4-bg.png" alt="" />
     </div>
     <div class="section4-text-wrapper">
     <div class="section4-text">
        Totally Private Safe, Secure and Transparent Social Network
        <span>Just for dancers, their parents, fans and studios! <br />
        	  Ki ds under 13 must have parent’s permission to join</span>
     </div>
     </div>

        
    
      <!-- @if(!empty($blocks['home-4']))
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
        </ul>-->
     </div>
  </div>
  
  <div class="section" id="section5">
    <div class="section-content">
     <div class="section5-bg">
        <img src="<?php echo WEBSITE_IMG_URL; ?>section5-img.jpg" alt="" />
     </div>
     <div class="section5-text">
	     ENCOURAGE POSITIVE USE OF SOCIAL MEDIA
         <span>Use the CMeShine app and community to explore
social media together. Open up avenues of
communication about positive uses of social media
to create supportive community with dance as your vehicle.</span>
     </div>

        
    
      <!-- @if(!empty($blocks['home-4']))
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
        </ul>-->
     </div>
  </div>
  
  
   
  <div class="section" id="section6">
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
		anchors: ['page1', 'page2', 'page3', 'page4', 'page5', 'page6'],
		sectionsColor: ['#fff ', '#12b2af', '#fff', '#12b2af', '#fff', ' '],
		navigation: {
			'position': 'left',
			'tooltips': ['', '', '', '', '']
		},
			afterRender: function(){
			$('#pp-nav').addClass('custom');
			$(".home2_header .logo").css("opacity","0");
			$(".home2_header .logo").removeClass("logo_active");
		},
		onLeave: function(){
			if($("#pagepiling").find(".active").attr('id') != 'section1'){
				$(".home2_header .logo").css("opacity","1");
				$(".home2_header .logo").addClass("logo_active");
			}else{
				$(".home2_header .logo").css("opacity","0");
				$(".home2_header .logo").removeClass("logo_active");
			}
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
    
	(function() {
  
  $(".panel").on("show.bs.collapse hide.bs.collapse", function(e) {
    if (e.type=='show'){
      $(this).addClass('active');
    }else{
      $(this).removeClass('active');
    }
  });  

}).call(this);
});



    </script>
@stop

