@extends('front.layouts.default')
@section('content')
{{ HTML::script('js/jquery.sticky.js') }}
<style>
html { overflow:auto};
</style>
<div id="pagepiling" ng-controller="msgCtrl">
	<script>
	mainApp.controller('msgCtrl', ['$scope','$timeout','$http','$location', function($scope,$timeout,$http,$location) {
		$scope.showLoadmore = true;
		$scope.row = 0;
		$scope.rowperpage = <?php echo Config::get('Reading.records_per_page'); ?>;
		$scope.buttonText = "Load More";
		//$scope.weburl = $location.absUrl();
		$scope.getPosts = function(){
			$('#loader_product_img').show();	
			$http({
				method: 'post',
				url: '{{{ route("Product.getProduct") }}}',
				data: {row:$scope.row,rowperpage:$scope.rowperpage,category:'<?php echo !empty($category) ? json_encode($category) : ""; ?>',price:'<?php echo $price; ?>',product_sort:'<?php echo $product_sort; ?>',size:'<?php echo !empty($size) ? json_encode($size) : ""; ?>'}
			}).then(function successCallback(response) {
				console.log(response);
				if(response.data !='' ){
					$scope.row+=$scope.rowperpage;
					if($scope.productsData != undefined){
						$scope.buttonText = "Loading ...";
						setTimeout(function() {
							$scope.$apply(function(){
								angular.forEach(response.data,function(item) {
									$scope.productsData.push(item);
								});
								$scope.buttonText = "Load More";
							});
						},500);
					}else{
						$scope.productsData = response.data;
					}
				}else{
					$scope.showLoadmore = false;
				}
				$('#loader_product_img').hide();
			});
		}
		$scope.getPosts();
		$scope.geProductImage 	= function(image){
			if(image != null && image != ''){
				return '<?php echo WEBSITE_URL.'image.php?width=270px&height=220px&cropratio=1&image='.PRODUCTS_IMAGE_URL; ?>'+image;
			}else{
				return '<?php echo WEBSITE_URL.'image.php?width=270px&height=220px&cropratio=1&image='.WEBSITE_IMG_URL;?>admin/no_image.jpg';
			}
		}
	}]);
	</script>	
  <div class="my_page_wrapper">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1 class="page-title2">Products</h1>
        </div>
      </div>
    </div>
    <div class="container">
		{{ Form::open(['role' => 'form','class'=>'product_listing_search_form','url' => "products",'id'=>'search_product','method'=>'get']) }}
      <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="active">Products</li>
      </ol>
      <div class="row mx-0">
        <div class="search-sort">
          <div class="product_listing_search">
            <div class="row">
              <div class="col-sm-6 col-xs-6">
                  <div class="form-group">
                    <input type="text" class="form-control form_submit_button" placeholder="Search" name="keyword">
                    <i class="fa fa-search"></i> </div>
              </div>
              <div class="col-sm-6 col-xs-6">
                <div class="product_listing_sort form-group"> <span class="custom-dropdown big">
                  <select name="sort_by" class="form_submit_button">
                    <option value="">Sort By</option>
                    <option value="low_to_high">Price -- Low to High</option>
                    <option value="high_to_low">Price -- High to Low</option>
                    <option value="oldest">Oldest First</option>
                    <option value="newest">Newest First</option>
                  </select>
                  </span> 
				</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="listing_page_wrapper">
        <div class="filter-sidebar">
			{{ Form::open(['role' => 'form','url' => "products",'id'=>'search_product','method'=>'get']) }}
          <div class="stick-sidebar">
            <div class="sidebar-inner">
              <div class="filter-heading"> <span> Categories </span> </div>
			 @if(!empty($categories))
              <ul class="brand-list ">
				@foreach($categories as $catKey=>$catName)
				<?php $checked = ""; ?>
				<?php $activeClass = ""; ?>
				@if(!empty($searchData['category']) && in_array($catKey,$searchData['category']))
				<?php $activeClass = "active radio_price_btn"; ?>	
				<?php $checked = "checked"; ?>
				@endif
                <li>
					<label for="product{{$catKey}}" class="vertical-filters-label common-customCheckbox">
						<input type="checkbox" id="product{{$catKey}}" class="price_radio form_submit_button" value="{{ $catKey }}" name="category[]">
						{!! $catName !!}
						 <div class="common-checkboxIndicator <?php echo $checked; ?>"></div>
					</label>
                </li>
			   @endforeach
              </ul>
			@endif
            </div>
			@if(!empty($priceArray))
            <div class="sidebar-inner">
				<div class="filter-heading"> <span> Price </span> </div>
				<ul class="brand-list ">
					@foreach($priceArray as $key=>$price)
					<li>
					  <label class="vertical-filters-label common-customCheckbox">
							<input type="radio" value="{{ $key }}" name="price" class="form_submit_button">
							{!! $price !!}
							<div class="common-checkboxIndicator"></div>
						</label>
					</li>
					@endforeach
				</ul>
			</div>
			@endif
          </div>
        </div>
        <div class="main-product-listing">
			@if(!empty($productsDetail))
			  <ul class="inner-product-detail clearfix">
				<li ng-repeat="productDetail in productsData">
				  <div class="product_detail">
					<a href="{{ URL('product-detail/' )}}/<@ productDetail.slug @>">
					<div class="figure"> <img ng-src="<@ geProductImage(productDetail.main_image) @>" alt=""> </div>
					<div class="inner-detail">
					  <h5><@ productDetail.name @></h5>
					  <p><@ productDetail.description @></p>
					  <h4><@ productDetail.price|currency:'{{ CURRENCY }}':2 @></h4>
					</div>
					</a>
				  </div>
					<p ng-show="!productsData.length" class="text-center">No Record Found.</p>
				</li>
			  </ul>
            <div class="load-more-btn" style="display: flex;align-items: center;justify-content: center;">
				@if($totalProducts > count($productsDetail))
					<h1 class="btn-block load-more load_more_btn" ng-show="showLoadmore" ng-click='getPosts()'><@ buttonText @></h1>
					<input type="hidden" id="row" ng-model='row'>
				@endif
			</div>
		   @endif
        </div>
      </div>
	  {{ Form::close() }} 
    </div>
  </div>
<script>
$(document).ready(function () {
	$('.product_detail').matchHeight({
	property: 'min-height'
	});
});

$(document).ready(function(){
	if ($(window).width() >= 768) {
	$(".stick-sidebar").sticky({topSpacing:75,bottomSpacing:65});
	}
});
$(".form_submit_button").change(function(){
	$("#search_product").submit();
});
</script> 
@include('front.elements.footer') 
</div>
@stop 
