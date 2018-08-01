@extends('admin.layouts.default')
@section('content')

{{ HTML::script('js/admin/plugins/ckeditor/ckeditor.js') }}
<!-- CKeditor ends-->

<section class="content-header">
	<h1>
		Edit Testimonial
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">{{ trans("messages.$modelName.table_heading_index") }}</a></li>
		<li class="active">Edit Testimonial</li>
	</ol>
</section>	
<section class="content"> 
	<div class="row pad">
		<div class="col-md-6">	
			@if(count($languages) > 1)
				<div  class="default_language_color">
					{{ Config::get('default_language.message') }}
				</div>
				<div class="wizard-nav wizard-nav-horizontal">
					<ul class="nav nav-tabs">
						@foreach($languages as $value)
							<?php $i = $value->id; ?>
							<li class=" {{ ($i ==  $language_code )?'active':'' }}">
								<a data-toggle="tab" href="#{{ $i }}div">
									{{ $value -> title }}
								</a>
							</li>
						@endforeach
					</ul>
				</div>
			@endif
			{{ Form::open(['role' => 'form','url' =>  route("$modelName.edit","$model->id"),'class' => 'mws-form', 'files' => true]) }}
			
			<div class="form-group <?php echo ($errors->first('image')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					{!! HTML::decode( Form::label('image', trans("Client Image").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<a class='tooltipHelp' title="" data-html="true" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;">
						<i class="fa fa-question-circle fa-2x"> </i>
					</a>
					<div class="mws-form-item">
						{{ Form::file('image') }}
						@if($model->image != '' && File::exists(TESTIMONIAL_ROOT_PATH.$model->image))
							<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo TESTIMONIAL_URL.$model->image; ?>">
								<div class="usermgmt_image">
									<img class="img-circle" src="<?php echo WEBSITE_URL.'image.php?width=150px&height=150px&image='.TESTIMONIAL_URL.'/'.$model->image ?>">
								</div>
							</a>
						@else
							<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg">
								<div class="usermgmt_image">
									<img class="img-circle" src="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg">
								</div>
							</a>
						@endif
						<div class="error-message help-inline">
							<?php echo $errors->first('image'); ?>
						</div>
					</div>
				</div>
			</div>	
			<div class="form-group <?php echo ($errors->first('order')?'has-error':''); ?>">
				<div class="mws-form-row ">
					{!! HTML::decode( Form::label('order', trans("Order").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text("order",$model->testimonial_order, ['class' => 'form-control small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('order'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group <?php echo ($errors->first('location')?'has-error':''); ?>">
				<div class="mws-form-row ">
					{!! HTML::decode( Form::label('location', trans("Location").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text("location",$model->location, ['class' => 'form-control small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('location'); ?>
						</div>
					</div>
				</div>
			</div>
			@if(count($languages) > 1)
				<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
					<hr class ="hrLine"/>
					<b>{{ trans("messages.system_management.language_field") }}</b>
				</div>
			@endif
			<div class="mws-panel-body no-padding tab-content"> 
				@foreach($languages as $value)
					<?php
						$i = $value->id ;
					?>
					<div id="{{ $i }}div" class="tab-pane {{ ($i ==  $language_code )?'active':'' }} ">
						<div class="mws-form-inline">
							<div class="form-group <?php  if($value -> id == 1) {  echo ($errors->first('client_name')) ? 'has-error' : '';} ?>">
								@if($value -> id == 1)
									{!! HTML::decode( Form::label('client_name',trans("Client Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
								@else
									{!! HTML::decode( Form::label('client_name',trans("Client Name").'<span class="requireRed">	 </span>', ['class' => 'mws-form-label'])) !!}
								@endif
								<div class="mws-form-item">
									{{ Form::text("data[$i][client_name]",isset($multiLanguage[$i]['client_name'])?$multiLanguage[$i]['client_name']:'', ['class' => 'form-control']) }}
									<div class="error-message help-inline">
										<?php echo ($i ==  $language_code ) ? $errors->first('client_name') : ''; ?>
									</div>
								</div>
							</div>
							<div class="form-group <?php  if($value -> id == 1) {  echo ($errors->first('comment')) ? 'has-error' : '';} ?>">
								@if($value -> id == 1)
									{!! HTML::decode( Form::label($i.'_body',trans("Client Comment").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
								@else
									{!! HTML::decode( Form::label($i.'_body',trans("Client Comment").'<span class="requireRed">  </span>', ['class' => 'mws-form-label'])) !!}
								@endif
								<div class="mws-form-item">
									{{ Form::textarea("data[$i][comment]",isset($multiLanguage[$i]['comment'])?$multiLanguage[$i]['comment']:'', ['class' => 'form-control','id' => 'comment'.$i]) }}
									<div class="error-message help-inline">
										<?php echo ($i ==  $language_code ) ? $errors->first('comment') : ''; ?>
									</div>
								</div>
								<script type="text/javascript">
									/* For CKeditor */
									CKEDITOR.replace( <?php echo 'comment'.$i; ?>,
									{
										height: 150,
										width: 485,
										filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
										filebrowserImageWindowWidth : '640',
										filebrowserImageWindowHeight : '480',
										enterMode : CKEDITOR.ENTER_BR
									});
								</script>
							</div>
						</div>
					</div>
				<?php // $i++ ; ?>
				@endforeach
				<div class="mws-button-row">
					<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
					<a href='{{ route("$modelName.edit","$model->id")}}' class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear')  }}</a>
					<a href="{{route($modelName.'.index')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
				</div>
			</div>
			{{ Form::close() }} 
		</div>
	</div>
</section>
@stop
