@extends('front.layouts.default')
@section('content')
<style>
html { overflow:auto};
</style>
<link rel="stylesheet" type="text/css" href="<?php echo WEBSITE_CSS_URL; ?>owl.carousel.min.css">
{{ HTML::script('js/owl.carousel.min.js') }}
<div id="pagepiling">
  <div class="my_page_wrapper">
		<div class="container">
			<ol class="breadcrumb">
				<li><a href="{{ URL::to("/") }}">Home</a></li>
				<li><a href="{{ route('Product.list') }}">Product</a></li>
				<li class="active">Product Detail</li>
			</ol>
			<div class="product_details_wrapper">
				<div class="row">
					<div class="slider-wrapper">
						<div class="card">
							<div class="row">
								<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
									<ul class="list-unstyled slide-data-ul">
										<?php $image = ''; ?>
										@if($productDetail->main_image != '' && File::exists(PRODUCTS_IMAGE_ROOT_PATH.$productDetail->main_image)) 
											<?php $image = WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1&image='.PRODUCTS_IMAGE_URL.'/'.$productDetail->main_image; ?>
										@endif
										<?php if(!empty($image)){ ?>
										<li class="data-item active" data-slide="0">
											<div style="background-image: url('<?php echo $image; ?>');"></div>
										</li>
										<?php } ?>
										<?php if(!empty($productImages)){
										if(!empty($image)){
											$dataSlide = 1;
										}else{
											$dataSlide = 0;
										}
										?>
										<?php foreach($productImages as $simage){ ?>
										<?php $image = ''; ?>
										@if($simage->image != '' && File::exists(PRODUCTS_IMAGE_ROOT_PATH.$simage->image)) 
											<?php $image = WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1&image='.PRODUCTS_IMAGE_URL.'/'.$simage->image; ?>
										@endif
										<?php if(!empty($image)){ ?>
										<li class="data-item" data-slide="{{ $dataSlide }}">
											<div style="background-image: url('<?php echo $image; ?>');"></div>
										</li>
										<?php
										$dataSlide++;
										} ?>
										<?php }
										} ?>
									</ul>
								</div>
									
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
									<div class="product_owl owl-carousel owl-theme">
										<?php $image = ''; ?>
										@if($productDetail->main_image != '' && File::exists(PRODUCTS_IMAGE_ROOT_PATH.$productDetail->main_image)) 
											<?php $image = WEBSITE_URL.'image.php?width=595px&height=500px&cropratio=1&image='.PRODUCTS_IMAGE_URL.'/'.$productDetail->main_image; ?>
										@endif
										<?php if(!empty($image)){ ?>
											<img src="<?php echo $image; ?>" alt="image">
										<?php } ?>
										<?php if(!empty($productImages)){
										?>
										<?php foreach($productImages as $simage){ ?>
										<?php $image = ''; ?>
										@if($simage->image != '' && File::exists(PRODUCTS_IMAGE_ROOT_PATH.$simage->image)) 
											<?php $image = WEBSITE_URL.'image.php?width=595px&height=500px&cropratio=1&image='.PRODUCTS_IMAGE_URL.'/'.$simage->image; ?>
										@endif
										<?php if(!empty($image)){ ?>
											<img src="<?php echo $image; ?>" alt="image">
										<?php
										} ?>
										<?php }
										} ?>
		                            </div>

								</div>
								<div class="col-sm-4 col-md-4 col-lg-4">
									{{ Form::open(['role'=>'form', 'url'=>'add-to-cart', 'type'=>'file', 'id'=>'product_detail'])}}
										{{ Form::hidden("id",$productDetail->id,['class' => '']) }}
										{{ Form::hidden("name",$productDetail->name,['class' => '']) }}
										{{ Form::hidden("quantity",1,['class' => '']) }}
										{{ Form::hidden("price",$productDetail->price,['class' => '']) }}
										<h4 class="chkoutheading">{{ $productDetail->name }}</h4>
										<div class="mt-10 mb-10">
											<label class="ft-18"><span>{{ CURRENCY.number_format($productDetail->price,2) }}</span></label>
										</div>
									
										<?php if(!empty($product_sizes)){ ?>
										<div class="mb-10">
											<label class="ft-18 mb-10">Select Size</label>
											<div class="radio__group">
												@foreach($product_sizes as $key => $size)
												<div class="radio__button">
													<input <?php echo ($key == 0) ? 'checked':''; ?> type="radio" id="size{{ $size->id }}" name="size" value="{{ $size->id }}">
													<label data-icon="S" for="size{{ $size->id }}"><p>{{ $size->name }}</p></label>
												</div>
												@endforeach
											</div>
										</div>
										<?php } ?>
										
										<div class="place-order-button">
											<a onclick="addToCart()" id="add_cart">Add to cart</a>
										</div>
									{{form::close()}}
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="row mx-0 mt-20">
						<div class="col-sm-12 col-md-12 col-lg-12 px-0">
							<div class="mb-25">
								<b>Product Detail</b>
								<p>{!! $productDetail->product_description !!}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function (){
			//Declare Carousel jquery object
			var Carousel = $('.product_owl');
			//Carousel initialization
			Carousel.owlCarousel({
				loop:true,
				margin:0,
				navSpeed:500,
				nav:false,
				items:1,
				autoplay:false,
				autoplayTimeout:3000,
				autoplayHoverPause:true,
				responsive: {
					0:{
					  items: 1
					},
					480:{
					  items: 1
					},
					768:{
					  items: 1
					}
				}
				
			});
			$('.slider-wrapper .data-item').click(function(){
				var cur_item=$(this).attr('data-slide');       
				Carousel.trigger('to.owl.carousel', cur_item);
			});
			$('[data-slide=0]').addClass('active'); 

			Carousel.on('changed.owl.carousel', function(event) {
				$('.data-item').removeClass('active');
				$('[data-slide='+ event.page.index +']').addClass('active');
			});
			
			
		});
		function addToCart() {
				var error			=	0;
				var formData  = $('#product_detail')[0];
				$('.help-inline').html('');
				$('.help-inline').removeClass('error');
				$('#loader_img').show();
				$.ajax({
					url: '{{ URL("add-to-cart") }}',
					type:'post',
					data: new FormData(formData),
					contentType: false,
					cache: false,     
					processData:false,
					success: function(response){
						show_message("{{{ trans('Product Added To Cart Successfully') }}}","success");
							//location.reload(true);
						$('#loader_img').hide();
					}
				});
				
			}
	</script>
@include('front.elements.footer')
</div>
@stop
