@extends('front.layouts.default')
@section('content')

<div id="pagepiling">
	<div class="my_page_wrapper">
		<div class="container">

			<ol class="breadcrumb">
				<li><a href="#">Home</a></li>
				<li class="active">Shipping</li>
			</ol>

			<div class="shipping_wrapper">
				<div class="row">
					<div class="shipping-form">
						<div class="card">
							<h4 class="chkoutheading mb-5">Shopping Address</h4>
							<label>Be sure to click "Deliver to this address" when you've finished.</label>

							<form class="form-horizontal" action="/action_page.php">
								<div class="form-group">
									<label class="control-label col-sm-2" >Full Name:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control"  placeholder="Enter full name">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" >Mobile Number:</label>
									<div class="col-sm-10"> 
										<input type="text" class="form-control"  placeholder="Enter mobile number">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" >Pincode:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control"  placeholder="Enter Pincode">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" >Flat House No., Bulding, Company, Appartment:</label>
									<div class="col-sm-10"> 
										<input type="text" class="form-control"  placeholder="Enter Flat House No., Bulding, Company, Appartment">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" >Landmark:</label>
									<div class="col-sm-10">
										<input type="text" class="form-control"  placeholder="Enter landmark">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" >Town/City:</label>
									<div class="col-sm-10"> 
										<input type="text" class="form-control"  placeholder="Enter town/city">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2" >State:</label>
									<div class="col-sm-10"> 
										<input type="text" class="form-control"  placeholder="Enter state">
									</div>
								</div>
							</form>
						</div>
					</div>
					<?php $total=0;?>
					<div class="pament-method">
						<div class="card">
							<h4 class="chkoutheading">Payment</h4>
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
												
											</div>
											<div class="single-price">
												$ {{$cartProduct['quantity'] * $cartProduct['size']}}
												<?php $total = $total + $cartProduct['quantity'] * $cartProduct['size'] ?>
											</div>
										</div>
								@endforeach
							@endif
							<!--<div class="product-details">
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
									
								</div>
								<div class="single-price">
									$10000
								</div>
							</div>

							<div class="product-details">
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
									
								</div>
								<div class="single-price">
									$10000
								</div>
							</div> -->

							<div class="d-row pt-20">
								<div class="col-8 text-center">
									<b>Total</b>
								</div>
								<div class="col-4">
									<b>{{$total}}</b>
								</div>
							</div>

							<hr>

							<a href="#!" class="paypal-demo-btn">
								<img src="https://vignette.wikia.nocookie.net/tdp4/images/2/2a/PayPal.png/revision/latest?cb=20140831014052">
							</a>

							<div class="row">
								<div class="col-sm-12 com-md-12 col-lg-12">
									<div class="place-order-button">
										<a href="#!"> Deliver to this address</a>
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

