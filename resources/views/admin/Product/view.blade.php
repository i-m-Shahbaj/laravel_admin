@extends('admin.layouts.default')
@section('content')
<section class="content-header">
	<h1>
		 {{ trans("Product Detail") }} 
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href='{{route("$modelName.index")}}'>{{ trans("Product Management") }}</a></li>
		<li class="active"> {{ trans("Product Detail") }}  </li>
	</ol>
</section>

<div class="box box-warning "> 
<div class="row pad"> 
	<div class="col-md-12">
		<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
	</div> 
</div>
	<div class="row pad">
		<div class="col-md-12 col-sm-12">	
			<table class="table table-striped">
				<thead>
					<tr class="bgcss">
						<th  width="30%" height="50%" class="txtFotnSize" colspan="2">PRODUCT INFORMATION</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th  width="30%" class="details text-right txtFotnSize txtFotnSize" >{{ trans("Image") }}</th>
						<td>
							<?php $image = isset($productDetail->main_image) ? $productDetail->main_image : ''; ?>
							@if($image != '' && File::exists(PRODUCTS_IMAGE_ROOT_PATH.$image))
								<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo  PRODUCTS_IMAGE_URL.$productDetail->main_image; ?>">
									<div class="blog_image">
										<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&image='.PRODUCTS_IMAGE_URL.'/'.$productDetail->main_image ?>">
									</div>
								</a>
							@endif
						</td>
					</tr>
					<tr>
						<th  width="30%" class="details text-right txtFotnSize txtFotnSize">{{ trans("Other Images") }}</th>
						<td>
								<?php 
					foreach($imageDetails as $imagedetail) { 
					$oldImage	=	Input::old('image');
					
					$image		=	isset($oldImage) ? $oldImage : $imagedetail->image;
					
					?>
			
				<div style="float:left">
					
					@if($image != '' && File::exists(PRODUCTS_IMAGE_ROOT_PATH.$image))  
						<a href="javascript:void(0)" id="<?php echo $imagedetail->id ?>" class="delete_image"><i class="fa fa-times" aria-hidden="true"> </i></a>
					<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo PRODUCTS_IMAGE_URL.$imagedetail->image; ?>">
					<div class="usermgmt_image" id="other_image_<?php echo $imagedetail->id ?>">
						<span style="float:left;"><img id="delete_image_attr" src="<?php echo WEBSITE_URL.'image.php?width=100px&height=100px&cropratio=1:1&image='.PRODUCTS_IMAGE_URL.$imagedetail->image ?>" style="margin-left:5px" class=""></span>
					</div>
					</a>
				</div>
			
				@endif
				<?php } ?>
						</td>
					</tr>
					
					<tr>
						<th  width="30%" class="details text-right txtFotnSize txtFotnSize">{{ trans("Name")}}</th>
						<td data-th='Name'>{{ isset($productDetail->name) ? $productDetail->name:'' }}</td>
					</tr>
					<tr>
						<th width="30%" class="details text-right txtFotnSize txtFotnSize">{{ trans("Category")}}</th>
						<td data-th='Category'>{{ isset($productDetail->category_id) ? $listCategory[$productDetail->category_id]:'' }}</td>
					</tr>
					<tr>
						<th width="30%" class="details text-right txtFotnSize txtFotnSize">{{ trans("Price") }}</th>
						<td data-th='Description'>{{ "$".$productDetail->price  }}</td>
					</tr>
					
					<tr>
						<th width="30%" class="details text-right txtFotnSize txtFotnSize">{{ trans("Product Size") }}</th>
						<td data-th='Description'><?php 
						$sizes = CustomHelper::getSizes($productDetail->product_size); ?>
						@if(!empty($sizes))
							@foreach($sizes as $size)
								<span>{{{ $size->name }}}</span></br>
							@endforeach
						@endif
						</td>
					</tr>
					<tr>
					<th width="30%" class="details text-right txtFotnSize txtFotnSize">{{ trans("Status") }}</th>
						<td>
						@if($productDetail->is_active == 1)
							<span class="label label-success" >{{ trans("messages.global.activated") }}</span>
						@else
							<span class="label label-warning" >{{ trans("messages.global.deactivated") }}</span>
						@endif
						@if($productDetail->is_featured == 1)
							<span class="label label-success" >{{ trans("Featured") }}</span>
						@endif
						</td>
					</tr>
					<tr>
						<th width="30%" class="details text-right txtFotnSize txtFotnSize">{{ trans("Product Description") }}</th>
						<td data-th='Category'>{!! $productDetail->product_description !!}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$(document).on('click', '.delete_image', function(e){ 
var id = this.id; 
	e.stopImmediatePropagation();
	url = $(this).attr('href');
	bootbox.confirm("Are you sure you want to delete this ?",
	function(result){
		if(result){
			$.ajax({
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
</script>
@stop
