@extends('front.layouts.default')
@section('content')
<style>
	html { overflow:auto}
	body { color: white;
    background: rgba(0, 0, 0, 0) url("<?php echo SYSTEM_IMAGE_URL.$systemImage; ?>") no-repeat center center;
    background-size: cover; background-attachment:fixed;}
</style> 
<div id="pagepiling">
	<div class="cms-wrapper">
		<div class="container">
	<div class="row">
			<div class="col-md-12">
				<div class="page-title-wrapper">
					<h1 class="page-title"><?php echo $result['title']; ?></h1>
				</div>
			</div>
		</div>
 		<div class="row">
			<div class="col-md-12">
				<p>
					<?php echo $result['body'];?>
				</p>
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

		}).blur(function(){
			$(this).parents('.post_compose').removeClass("active");
			$('.compose_overlay').removeClass('active');
		})

	}); 
</script>
@stop
