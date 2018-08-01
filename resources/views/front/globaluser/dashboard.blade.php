@extends('front.layouts.default')
@section('content')
<style>
	html { overflow:auto}
	<?php /*body { color: white;
    background: rgba(0, 0, 0, 0) url("<?php echo SYSTEM_IMAGE_URL.$systemImage; ?>") no-repeat center center;
    background-size: cover; background-attachment:fixed;} */ ?>
</style>
<div class="clearfix"></div>
<div class="cms-page-wrapper">
	<div class="container">
	<div class="row">
      <div class="col-sm-12">
		  <h1 class="page-title">Welcome</h1>
      </div>
    </div>
    </div>
    
@include('front.elements.footer')
</div>
<!--cms-page-wrapper END-->
@stop
