@extends('front.layouts.default')
@section('content')
<style>
	html { overflow:auto}
	body { color:white; background: rgba(0, 0, 0, 0) url("<?php echo SYSTEM_IMAGE_URL.$systemImage; ?>") no-repeat center center; 
  background-size: cover;  background-attachment:fixed;}
</style>
<div class="cms-page-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="page-title-wrapper">
					<h1 class="page-title">Contact Us</h1>
				</div>
			</div>
		</div>
       <div class="row">
        <div class="col-md-8 col-sm-7"> {{ Form::open(['role' => 'form','route' => "User.contact",'class' => 'contact-form equal-height-contact-page mws-form','id'=>'contact_form']) }}
          <h2>Send Message Us</h2>
          <div class="row">
            <div class="col-md-6 col-sm-12">
              <div class="form-group"> {{ Form::text("data[name]",'', ['placeholder'=>trans("Your Name"),'id'=>'name','class'=>'form-control']) }}
                <div class="error help-inline"> <?php echo $errors->first('name'); ?> </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-12">
              <div class="form-group"> {{ Form::text("data[email]",'', ['placeholder'=>trans("Email"),'id'=>'email','class'=>'form-control' ]) }}
                <div class="error help-inline"> <?php echo $errors->first('email'); ?> </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group"> {{ Form::text("data[subject]",'', ['placeholder'=>trans("Subject"),'id'=>'subject','class'=>'form-control' ]) }}
                <div class="error help-inline"> <?php echo $errors->first('subject'); ?> </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group"> {{ Form::textarea("data[message]",'', ['placeholder'=>trans("Message"),'id'=>'message','class'=>'form-control']) }}
                <div class="error help-inline"> <?php echo $errors->first('message'); ?> </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-captcha"> <?php echo captcha_img('flat') ?> {{ Form::text('data[captcha]', null, ['placeholder' => 'Captcha', 'id'=>'captcha','class' => 'form-control']) }} </div>
              <div class="error help-inline"> <?php echo $errors->first('captcha'); ?> </div>
            </div>
            <div class="col-md-12">
              <input type="submit" class="submit-button" value="Submit" >
            </div>
          </div>
          {{ Form::close() }} </div>
        <div class="col-md-4  col-sm-5">
          <div class="quick-contact equal-height-contact-page" style="background-color:white;">
            <div class="title">
              <h2>Quick Contact</h2>
              <p>If you have any questions simply use the following contact details.</p>
            </div>
            <ul class="contact-info">
              <li>
                <div class="icon-holder"> <i class="fa fa-map-marker"></i> </div>
                <div class="text-holder">
                  <h5><span>Address:</span><br>
                    {{ str_replace("\n","<br/>
                    ",Config::get('Contact.address')) }} </h5>
                </div>
              </li>
              <li>
                <div class="icon-holder"> <i class="fa fa-phone"></i> </div>
                <div class="text-holder">
                  <h5><span>Phone:</span><br>
                    {{{ Config::get('Contact.phone_number') }}} </h5>
                </div>
              </li>
              <li>
                <div class="icon-holder"> <i class="fa fa-fax"></i> </div>
                <div class="text-holder">
                  <h5><span>Fax:</span><br>
                    {{{ Config::get('Contact.fax') }}} </h5>
                </div>
              </li>
              <li>
                <div class="icon-holder"> <i class="fa  fa-envelope"></i> </div>
                <div class="text-holder">
                  <h5><span>Email:</span><br>
                    {{{ Config::get('Contact.email') }}}</h5>
                </div>
              </li>
              <li>
                <div class="icon-holder"> <i class="fa  fa-globe"></i> </div>
                <div class="text-holder">
                  <h5><span>Web:</span><br>
                    {{ WEBSITE_URL }}</h5>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
		</div>
	<div class="map-wrapper">
    <div class="location_map"> 
      <script src='https://maps.googleapis.com/maps/api/js?v=3.exp'></script>
         <div id='gmap_canvas' style='width:100%;'>
          <iframe src="{{ Config::get('Contact.map') }}" width="100%" height="500" frameborder="0" style="border:0" allowfullscreen></iframe>
       </div>
    </div>
    
  </div> <!--Map Wrapper END-->
   @include('front.elements.footer')
  </div> 
<style>

</style>
<script>
function resizeequalheight(){
			equalHeight($(".equal-height-contact-page"));
			}
			function equalHeight(group) {
		tallest = 0;
		group.height('');
		group.each(function() {
			thisHeight = $(this).height();
			if(thisHeight > tallest) {
				tallest = thisHeight;
			}
		});
		group.height(tallest);
		}
	$(function(){
		$(window).resize(function() {
		setTimeout('resizeequalheight()',250)
		});
		setTimeout('resizeequalheight()',250) 
	});
	</script>
@stop
