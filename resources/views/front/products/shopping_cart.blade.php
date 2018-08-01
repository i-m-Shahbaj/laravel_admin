
@extends('front.layouts.default')
@section('content')

<div id="pagepiling">
	<div class="my_page_wrapper">
		<div class="container">

			<ol class="breadcrumb">
				<li><a href="#">Home</a></li>
				<li class="active">Checkout</li>
			</ol>

			<div class="checkout_wrapper">
				<div class="row">
					<div class="shopping-bag">

						<div class="card">
							
							<h4 class="chkoutheading">My Shopping Bag 
							@if(!empty($cart_products))
								({{ count($cart_products) }}  Item)
							@endif
							</h4>
							 @if(!empty($cart_products))
									@foreach($cart_products as $cartProduct)
										<div class="product-details">
											<div class="single-image">
												<figure>
													<img src="http://cmeshine.dev2.obtech.inet/uploads/project_article//JUN2018//01529392934-article-document.jpg" alt="">
												</figure>
											</div>
											<div class="single-dtails">
												<div>
													<label>{{ $cartProduct['name'] }}</label>
												</div>

												<ul class="list-unstyled">
													<li>
														Size : {{$cartProduct['size']}}
													</li>
													<li>
														Qty : {{$cartProduct['quantity']}}
													</li>
												</ul>

												<div class="remove-product">
													<a href="#!">Remove</a>
												</div>
												
											</div>
											<div class="single-price">
												$ {{$cartProduct['quantity'] * $cartProduct['size']}}
											</div>
										</div>
								@endforeach
								@else
									Your shopping cart is empty
							@endif
						<!--	<div class="product-details">
								<div class="single-image">
									<figure>
										<img src="http://cmeshine.dev2.obtech.inet/uploads/project_article//JUN2018//01529392934-article-document.jpg" alt="">
									</figure>
								</div>
								<div class="single-dtails">
									<div>
										<label>Product Name</label>
									</div>

									<ul class="list-unstyled">
										<li>
											Size : M
										</li>
										<li>
											Qty : 1
										</li>
									</ul>

									<div class="remove-product">
										<a href="#!">Remove</a>
									</div>
									
								</div>
								<div class="single-price">
									$ 10000
								</div>
							</div> -->

						</div>
						
					</div>

					<div class="price-details">

						<div class="card">
							<h4 class="chkoutheading">Total : $ 10000</h4>

							<div class="product-billing">
								<div class="main-price">
									<label>Price Details</label>
								</div>
								<div class="row mt-10 mb-10">
									<div class="col-sm-6 col-md-6 col-lg-6">
										Bag Total
									</div>
									<div class="col-sm-6 col-md-6 col-lg-6">
										$ 10000
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6 col-md-6 col-lg-6">
										Delivery
									</div>
									<div class="col-sm-6 col-md-6 col-lg-6">
										Free
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-sm-6 col-md-6 col-lg-6">
										<b>Total Order</b>
									</div>
									<div class="col-sm-6 col-md-6 col-lg-6">
										<b>$ 1000</b>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-sm-12 com-md-12 col-lg-12">
										<div class="place-order-button">
											<a href="#!"> Place Order</a>
										</div>
										
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>

		</div>
	</div>
@include('front.elements.footer')
</div>
@stop
