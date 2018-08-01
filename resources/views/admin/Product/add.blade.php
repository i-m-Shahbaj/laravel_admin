@extends('admin.layouts.default')
@section('content')
<!-- CKeditor start here-->
{{ HTML::style('css/admin/chosen.min.css') }}
{{ HTML::script('js/admin/chosen/chosen.jquery.min.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor ends-->

<section class="content-header">
	<h1>
		{{trans("Add New Product") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href='{{route("$modelName.index")}}'>{{ trans("Product Management") }}</a></li>
		<li class="active"> {{trans("Add New Product") }}</li>
	</ol>
</section>
<section class="content">
<div class="row pad"> 
	<div class="col-md-12">
		<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
	</div> 
</div>
	<div class="row pad">
		<div class="col-md-12">
			@if(count($languages) > 1)
			<div  class="default_language_color">
				{{ Config::get('default_language.message') }}
			</div>
			<div class="wizard-nav wizard-nav-horizontal">
				<ul class="nav nav-tabs">
					<?php $i = 1 ; ?>
					@foreach($languages as $value)
						<li class=" {{ ($i ==  $language_code )?'active':'' }}">
							<a data-toggle="tab" href="#{{ $i }}div">
							{{ $value -> title }}
							</a>
						</li>
						<?php $i++; ?>
					@endforeach
				</ul>
			</div>
			@endif
		</div>
	</div>
	{{ Form::open(['role' => 'form','route' => "$modelName.save",'class' => 'mws-form', 'files' => true]) }}
	
	<div class="row pad">
		<div class="col-md-6">
			<div class="form-group <?php echo ($errors->first('category_id')?'has-error':''); ?>">
				<div class="mws-form-row">
					{!! HTML::decode( Form::label('category_id', trans("Category Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::select(
						'category_id',
						[null => 'Please Select Product Category'] + $listCategory,
						'',
						['id' => '','class'=>'form-control chosen-select',]
						) 
						}}
						<div class="error-message help-inline">
							<?php echo $errors->first('category_id'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group <?php echo ($errors->first('price')?'has-error':''); ?>">
				<div class="mws-form-row ">
					{!! HTML::decode( Form::label('price', trans("Price").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
					<div class="input-group">
					<span class="input-group-addon" style="height:20px;">{{trans("$")}}</span>
						{{ Form::text("price",'', ['class' => 'form-control small']) }}
						
					</div>
						<div class="error-message help-inline">
							<?php echo $errors->first('price'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row pad">
		<div class="col-md-6">
			<div class="form-group <?php echo $errors->first('name')?'has-error':''; ?>">
				<div class="mws-form-row ">
					{!! HTML::decode( Form::label('name', trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text("name",'', ['class' => 'form-control small','id' => 'name']) }}
						<span class="error-message help-inline">
						<?php echo $errors->first('name'); ?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group <?php echo ($errors->first('product_size')?'has-error':''); ?>">
					{!! HTML::decode( Form::label('product_size', trans("Size").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">

							{{ Form::select(
								'product_size[]',
							     $listSize,
								'',
								['id' => 'product_size_id','class'=>'form-control','multiple'=>'multiple']
								) 
								}}
							<div class="error-message help-inline">
								<?php echo $errors->first('product_size'); ?>
							</div>
					</div>
			</div>
		</div>
	</div>
	<div class="row pad">
		
		<div class="col-md-6">
			<div class="form-group <?php echo ($errors->first('main_image')?'has-error':''); ?>">
				<div class="mws-form-row ">
					{!! HTML::decode( Form::label('main_image', trans("Product Image").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::file('main_image',["accept"=>"image/*"]) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('main_image'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group <?php echo ($errors->first('image')?'has-error':''); ?>">
				<div class="mws-form-row ">
					{!! HTML::decode( Form::label('image', trans("Secondary Images").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::file('image[]',['multiple' => 'multiple',"accept"=>"image/*"]) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('image'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row pad">	
			<div class="col-md-6">
					<div class="form-group <?php echo $errors->first('product_description')?'has-error':''; ?>">
						<div class="mws-form-row ">
							{!! HTML::decode( Form::label('product_description', trans("Product Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							<div class="mws-form-item">
								{{ Form::textarea("product_description",'', ['class' => 'small','id' => 'product_description']) }}
								<span class="error-message help-inline">
								<?php echo $errors->first('product_description'); ?>
								</span>
							</div>
							<script type="text/javascript">
								/* CKEDITOR fro product_description */
								CKEDITOR.replace( <?php echo 'product_description'; ?>,
								{
									height: 350,
									width: 507,
									filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
									filebrowserImageWindowWidth : '640',
									filebrowserImageWindowHeight : '480',
									enterMode : CKEDITOR.ENTER_BR
								});
									
							</script>
						</div>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<input type="submit" value="{{ trans('messages.global.save') }}" class="btn btn-danger" onclick="return checkboxCheck()" />
				<a href="{{ route($modelName.'.add')}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('messages.global.reset') }}</a>
				<a href="{{URL::to('cmeshinepanel/product-manager')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
			</div>
			{{ Form::close() }} 
		</div>
	</div>
</section>
<script>
$(".chosen-select").chosen({width:'100%'});
/*Function for add more products details*/	
	function acco_add_more_size(id){
		
		$('#loader_img').show();
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		var get_last_id			=	$(".lastsizedetailsrow"+id).find('tr').last().attr('rel');
		var counter  	 		=  parseInt(get_last_id) + 1;
		$.ajax({
			url:'{{ URL("admin/product-manager/add-more-product") }}',
			'type':'post',
			data:{'counter':counter,'id':id},
			success:function(response){
				$('#loader_img').hide();
				$('.lastsizedetailsrow'+id).find('tr').last().after(response);
				$(".chosen-select").chosen({width:'100%'});
			}
		});
	}

	
	
function delete_product_row(row_id){
	bootbox.confirm("Are you sure want to remove this ?",
	function(result){
		if(result){
			$('.delete_add_more_accor'+row_id).remove();
		}
	});
}

$('#product_color_id').chosen({ width: '100%'});
$('#product_size_id').chosen({ width: '100%' });
</script>
<style>
.default{
		height:30px !important;
	}
.mycontainer .chzn-container-multi .chzn-choices {
    
}
#custom-label ul {
    list-style: outside none none;
}
.centerFlex {
  align-items: center;
  display: flex;
  justify-content: center;
}
</style>
@stop

