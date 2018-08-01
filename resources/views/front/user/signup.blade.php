@extends('front.layouts.default')
@section('content')

<div id="pagepiling">
  <div class="section" id="section1">
	<div class="fullscreen_post_bg"> </div>
	<div class="section-content"> 
	  <!--<figure class="logo-banner revealOnScroll animated fadeInDown"><img src="<?php echo WEBSITE_IMG_URL; ?>logo-banner.png" alt=""  /></figure>-->
	  @if(!empty($blocks['signup-page']))
		<?php echo $blocks['signup-page']['description']; ?>
	  @endif
	  @if(!empty($blocks['signup-page-description']))
		<?php echo $blocks['signup-page-description']['description']; ?>
	  @endif
	  <div class="mobile-apps"> <a href="javascript:void(0);"><img src="<?php echo WEBSITE_IMG_URL; ?>app-store.png" alt="" /></a> <a href="javascript:void(0);"><img src="<?php echo WEBSITE_IMG_URL; ?>play-store.png" alt="" /></a> </div>
	</div>
	<div class="copyright-wrapper">
	<div class="footer-links"><a href="{{ URL('pages/privacy-policy') }}">Privacy Policy</a> <a href="{{ URL('pages/terms-and-conditions') }}">Terms & Conditions</a>
<a href="{{ URL('faq') }}">FAQ</a>
<a href="{{ URL('contact-us') }}">Support</a></div>
	<div class="copyright-text">CMEShine &copy; <?php echo date("Y"); ?> | All Rights Reserved.</div>
	</div>
  </div>
@include('front.elements.footer')
</div>
<script>
	
	function signup() {
		var formData  = $('#signup_form')[0];
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
			url: '{{ URL("signup") }}',
			type:'post',
			data: new FormData(formData), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,
			success: function(r){
				error_array 	= 	JSON.stringify(r);
				data			=	JSON.parse(error_array);
				if(data['success'] == 1) {
					document.getElementById("signup_form").reset();
					window.location.href	 =	"{{ URL('login') }}";
				}else if(data['success'] == 3) {
					document.getElementById("signup_form").reset();
					window.location.href  =	"{{ URL('login') }}";
				}
				else {
					$.each(data['errors'],function(index,html){
						$("input[name = "+index+"]").next().addClass('error');
						$("input[name = "+index+"]").next().html(html);
					});
				}
				$('#loader_img').hide();
			}
		});
	}
	
	 $('#signup_form').each(function() {
		$(this).find('input').keypress(function(e) {
           if(e.which == 10 || e.which == 13) {
				signup();
				return false;
            }
        });
	});
	
</script>
<script src="<?php echo WEBSITE_JS_URL; ?>jquery.pagepiling.js"></script> 
<script>
$(document).ready(function() {
	$('#pagepiling').pagepiling({
		menu: '#menu',
		anchors: ['page1', 'page2', 'page3', 'page4', 'page5'],
		sectionsColor: [' ', '#9648b3', '#5dc2ca', '#eb6ea6', ''],
		navigation: false,
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
