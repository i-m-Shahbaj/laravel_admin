@extends('admin.layouts.default')
@section('content')
<!-- CKeditor start here-->
{{ HTML::style('css/admin/chosen.min.css') }}
{{ HTML::script('js/admin/chosen/chosen.jquery.min.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor ends-->
<section class="content-header">
	<h1>
		{{trans("Edit Product") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href='{{route("$modelName.index")}}'>{{ trans("Product Management") }}</a></li>
		<li class="active"> {{trans("Edit Product") }}</li>
	</ol>
</section>
<section class="content">
<div class="row pad"> 
	<div class="col-md-12">
		<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
	</div> 
</div>
	{{ Form::open(['role' => 'form','url' => 'cmeshinepanel/product-manager/edit-product/'.$productDetail->id,'class' => 'mws-form', 'files' => true]) }}
	<div class="row pad">
		<div class="col-md-6">
			<div class="form-group <?php echo ($errors->first('category_id')?'has-error':''); ?>">
				<div class="mws-form-row">
					{!! HTML::decode( Form::label('category_id', trans("Category Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::select(
						'category_id',
						[null => 'Please Select Product Category'] + $listCategory,
						$productDetail->category_id,
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
						{{ Form::text("price",isset($productDetail->price) ? $productDetail->price :'', ['class' => 'form-control small']) }}
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
			<div class="form-group <?php echo ($errors->first('name')?'has-error':''); ?>">
				<div class="mws-form-row ">
					{!! HTML::decode( Form::label('name', trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text("name",isset($productDetail->name)?$productDetail->name:'', ['class' => 'form-control small','id' => 'name']) }}
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
					<?php $selectedSizes = explode(',', $productDetail->product_size); ?>
						{{ Form::select(
							'product_size[]',
							[null => ''] + $listSize,
							$selectedSizes,
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
			<div class="form-group">
				{!! HTML::decode( Form::label('main_image', trans("Product Image").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<span class='tooltipHelp' title="" data-html="true" data-toggle="tooltip"  data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;">
				<i class="fa fa-question-circle fa-2x"> </i>
				</span>

				<div class="mws-form-item">
					{{ Form::file('main_image',["accept"=>"image/*"]) }}
					<br />
					<?php 
						$oldImage	=	Input::old('main_image');
						
						$image		=	isset($oldImage) ? $oldImage : $productDetail->main_image;
						
						?>
				</div>
				@if($image != '' && File::exists(PRODUCTS_IMAGE_ROOT_PATH.$image)) 
				<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo PRODUCTS_IMAGE_URL.$productDetail->main_image; ?>">
					<div class="usermgmt_image">
						<img  src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1:1&image='.PRODUCTS_IMAGE_URL.'/'.$productDetail->main_image ?>">
						
						
					</div>
				</a>
				@endif
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<div class="mws-form-row ">
					{!! HTML::decode( Form::label('image', trans("Secondary Images").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
					<span class='tooltipHelp' title="" data-html="true" data-toggle="tooltip"  data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;">
				<i class="fa fa-question-circle fa-2x"> </i>
				</span>
					<div class="mws-form-item">
						{{ Form::file('image[]',['multiple' => 'multiple',"accept"=>"image/*"]) }}

					</div>
						@if(!empty($imageDetails))
			@foreach($imageDetails as $imagedetail)
				<div>
					<div style="float:left">
					<a href="javascript:void(null)" id="<?php echo $imagedetail->id ?>" class="delete_image"><i class="fa fa-times" aria-hidden="true"> </i></a>
					@if($image != '' && File::exists(PRODUCTS_IMAGE_ROOT_PATH.$image))  
						<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo PRODUCTS_IMAGE_URL.$imagedetail->image; ?>">
							
							<div class="usermgmt_image" id="other_image_<?php echo $imagedetail->id ?>">
								<img id="delete_image_attr" src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1:1&image='.PRODUCTS_IMAGE_URL.'/'.$imagedetail->image ?>" style="margin-left:5px" class="">
							</div>
						</a>
						@endif
					</div>
				</div>
			@endforeach
			@endif
				</div>
				
			</div>
		</div>
	</div>
	<div class="row pad">	
		<div class="col-md-6">			
			<div class="form-group <?php echo ($errors->first('product_description')?'has-error':''); ?>">
				<div class="mws-form-row ">
					{!! HTML::decode( Form::label('product_description', trans("Product Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::textarea("product_description",isset($productDetail->product_description)?$productDetail->product_description:'', ['class' => 'small','id' => 'product_description']) }}
						<span class="error-message help-inline">
						<?php echo $errors->first('product_description'); ?>
						</span>
					</div>
					<script type="text/javascript">
						/* CKEDITOR fro product_description */
						CKEDITOR.replace( <?php echo 'product_description'; ?>,
						{
							height: 150,
							width: 507,
							filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
							filebrowserImageWindowWidth : '240',
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
		</div>
			{{ Form::close() }} 
		</div>
	</div>
</section>
<script>
$(".chosen-select").chosen({width:'100%'});
/*Function for add more products details*/
	var empty_msg						=	'This field is required';
	function acco_add_more_size(id){
		
		var error  =	0;
		//~ $('.valid').each(function() { 
			//~ if($(this).val().trim() == '' ){
				//~ $(this).next().html(empty_msg);
				//~ error	=	1;
			//~ }
		//~ });
		if(error == 0){
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
	}
	
	$(document).on('click', '.delete_image', function(e){
		var id = this.id; 
		e.stopImmediatePropagation();
		url = $(this).attr('href');
		bootbox.confirm("Are you sure you want to delete this ?",
		function(result){
			if(result){
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					url: "{{  URL::route('Product.remove') }}",
					type: "POST",
					data: {id: id},
					success: function(response){
						$("#other_image_"+id).remove();
						$('#'+id).remove();
					}
				});
			}
		});
		e.preventDefault();
	});
	
	
	function delete_product_row(row_id){
		bootbox.confirm("Are you sure want to remove this ?",
		function(result){
			if(result){
				$('.delete_add_more_accor'+row_id).remove();
			}
		});
	}
$('#product_color_id').chosen();
$('#product_size_id').chosen();

function del_productAtt(id){
	bootbox.confirm("Are you sure you want to delete this ?",
	function(result){
		if(result){
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: "{{  URL::route('Product.removeAttributes') }}",
				type: "POST",
				data: {id: id},
				//dataType: 'json',
				contentType: false, // The content type used when sending data to the server.
				cache: false, // To unable request pages to be cached
				processData:false,
				success: function(response){
					$(".add_more_product_attributes"+id).remove();
				}
			});
		}
	});
}

</script>
<style>
.default{
		height:30px !important;
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

