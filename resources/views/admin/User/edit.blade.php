@extends('admin.layouts.default')
@section('content')
<?php $googleApiKey	= Config::get('Site.api');?>
<?php /*<link href="{{ WEBSITE_CSS_URL }}bootstrap-formhelpers.css" rel="stylesheet"/>
<script src="{{ WEBSITE_JS_URL }}bootstrap-formhelpers-phone.format.js"></script>
<script src="{{ WEBSITE_JS_URL }}bootstrap-formhelpers-phone.js"></script>*/?>
<script src="{{ WEBSITE_JS_URL }}jquery.inputmask.bundle.js"></script>
<script src="{{ WEBSITE_JS_URL }}phone.js"></script>

{{ HTML::script('js/admin/jui/js/jquery-ui-1.9.2.min.js') }}
{{ HTML::script('js/admin/jui/js/timepicker/jquery-ui-timepicker.min.js') }}
{{ HTML::script('js/admin/chosen/chosen.jquery.min.js') }}
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::style('css/admin/chosen.min.css') }}
<?php $googleApiKey	= Config::get('Site.api');?>
<script src="{{ WEBSITE_JS_URL }}jquery.inputmask.bundle.js"></script>
<script src="{{ WEBSITE_JS_URL }}phone.js"></script>
<script type="text/javascript"> 
	
	$(document).ready(function(){
		$( ".dancer_date" ).datepicker({
			dateFormat 	: 'yy-mm-dd',
			changeMonth : true,
			changeYear 	: true,
			yearRange	: '-100y:c+nn',
		}); 
		 $(".chosen-select").chosen({width: "100%"}); 
	});
</script>
<section class="content-header">
	<h1>
		{{ trans("messages.user_management.edit_user") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">{{ trans("User Management") }}</a></li>
		<li class="active">{{ trans("messages.user_management.edit_user") }} </li>
	</ol>
</section>
<section class="content"> 
	<div class="row pad"> 
		<div class="col-md-12">
			<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
		</div> 
	</div>
	{{ Form::open(['role' => 'form','route' => $modelName.'.update','class' => 'mws-form','files'=>'true', 'id'=>'dancer_user_form']) }}
	{{ Form::hidden('id',isset($userDetails->id) ? $userDetails->id :'',['class' => '','id'=>'user_id']) }}
	<div class="row pad">
		<div class="mws-form-item">
			<div class="col-md-6">
				<div class="type_div form-group">
					<?php $userTypeList 	=	Config::get('user_type_list'); ?>
					{{ Form::select('user_type',$userTypeList,isset($userDetails->user_role_id) ? $userDetails->user_role_id :'',['class'=>'form-control chosen-select disabled_field','id'=>'user_type','placeholder'=>'Select User Type','disabled']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('user_type'); ?>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group"></div>
			</div>
			<div class="clearfix"></div>
			<div id="" class="row col-md-12">	
				<div class="user_info" id="user_info">	
				
				</div>
			</div>
		</div>
	</div>
	<div class="mws-button-row">
		<div class="input" >
			<input type="button" value="{{ trans('messages.user_management.save') }}" class="btn btn-danger" onclick="update_dancer_user_data();">
			<a href="{{route($modelName.'.edit',$userDetails->id)}}" class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans("Clear") }}</a>
			<a href="{{route($modelName.'.index')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans("Cancel") }}</a>
		</div>
	</div>
	{{ Form::close() }}
	
	<div id="loader_img"><center><img src="{{WEBSITE_IMG_URL}}loading.gif"></center></div>
</section>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=<?php echo $googleApiKey;?>&libraries=places"></script>
<script type="text/javascript">
function initialize() {
var input = document.getElementById('searchTextField');
var autocomplete = new google.maps.places.Autocomplete(input);
google.maps.event.addListener(autocomplete, 'place_changed', function () {
var place = autocomplete.getPlace();
//document.getElementById('city2').value = place.name;
document.getElementById('cityLat').value = place.geometry.location.lat();
document.getElementById('cityLng').value = place.geometry.location.lng();
//alert("This function is working!");
//alert(place.name);
// alert(place.address_components[0].long_name);

});
}
google.maps.event.addDomListener(window, 'load', initialize); 
</script>
<script type="text/javascript">
	
	$(document).ready(function(){
		var user_type	=	$("#user_type").val();
		var ID			=	$("#user_id").val();
		if(ID){
		$('#loader_img').show();
			$.ajax({
				headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: '{{ route($modelName.".getUserEditData") }}',
				type:'post',
				data:{'user_type':user_type,'user_id':ID},
				success:function(response){ 
					$(".user_info").html(response);
					$('#loader_img').hide();
					$('.user_info').trigger("chosen:updated");
				}
			});
		}
		
	$(document).on('change','.countries_id',function(){ 
		$('#loader_img').show();
		var Id				=	$(this).val();  
		var rel				=	$(this).attr('data-rel');
		if(Id && rel){
			$('.state_id_'+rel).empty('').trigger("chosen:updated");
			$('.state_id_'+rel).append($("<option/>", {value: '',text: 'State'})).trigger("chosen:updated");
			$.ajax({
				headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: '{{ route($modelName.".getStateList") }}',
				type:'post',
				data:{'country_id':Id},
				success:function(response){  
					error_array 		= 	JSON.stringify(response);
					data				=	JSON.parse(error_array);
					var state_list		=	data['state_list'];
					if(state_list != ''){
						$.each(state_list, function(key,value){
							$(".state_id_"+rel).append($("<option/>", {
								value: key,
								text: value
							})); 
							$(".state_id_"+rel).trigger("chosen:updated");
							$('#loader_img').hide();
						});
					}
				}
			});
		}
	});
		
	$(document).on('change','.state_id',function(){ 
		$('#loader_img').show();
		var Id1				=	$(this).val();  
		var rel1			=	$(this).attr('data-rel');
		if(Id1 && rel1){
			$('.city_id_'+rel1).empty('').trigger("chosen:updated");
			$('.city_id_'+rel1).append($("<option/>", {value: '',text: 'City'})).trigger("chosen:updated");
			$.ajax({
				headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: '{{ route($modelName.".getCityList") }}',
				type:'post',
				data:{'state_id':Id1},
				success:function(response){ 
					error_array 		= 	JSON.stringify(response);
					data				=	JSON.parse(error_array);
					var city_list		=	data['city_list'];
					if(city_list != ''){
						$.each(city_list, function(key,value){
							$(".city_id_"+rel1).append($("<option/>", {
								value: key,
								text: value
							})); 
							$(".city_id_"+rel1).trigger("chosen:updated");
							$('#loader_img').hide();
						});
					}
				}
			});
		} 
	});	
	});	
	
	function update_dancer_user_data() {
		var formData = $('#dancer_user_form')[0];
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$('.disabled_field').removeAttr('disabled');
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '{{ route($modelName.".update") }}',
			type:'post',
			data: new FormData(formData),
			dataType: 'json',
			contentType: false, // The content type used when sending data to the server.
			cache: false, // To unable request pages to be cached
			processData:false,
			success: function(r){
				error_array 	= 	JSON.stringify(r);
				data			=	JSON.parse(error_array);
				if(data['success'] == 1) {
					$('#dancer_user_form')[0].reset();
					window.location.href	 =	"{{ route($modelName.'.index') }}";
					show_message("User update successfully.",'success');
				}
				else {
					$('.disabled_field').attr('disabled','disabled');
					$.each(data['errors'],function(index,html){
						$("#"+index).parent().parent().addClass('has-error');
						$("#"+index+"_error").addClass('error');
						$("#"+index+"_error").html(html);
					});
				}
				$('#loader_img').hide();
			}
		});
	}
</script>

<style>
.chosen-container-single .chosen-single{
	padding: 5px 5px 5px 8px;
    height: 35px;
}
#loader_img {
    background-color: #000 !important;
    height: 100% !important;
    top: 0 !important;
    left: 0 !important;
    position: fixed !important;
    width: 100% !important;
    z-index: 99999 !important;
    opacity: 0.5 !important;
    display: none;
}
#loader_img img {
    width: 100px;
    height: 100px;
    position: absolute;
    top: 50%;
}
</style>

@stop
