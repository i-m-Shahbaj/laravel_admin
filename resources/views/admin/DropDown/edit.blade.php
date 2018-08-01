@extends('admin.layouts.default')

@section('content')

<section class="content-header">
	<h1>
		{{ 'Edit '.studly_case($type) }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><a href="{{route('DropDown.listDropDown',$type)}}">{{ ucwords(str_plural(str_replace("-"," ",$type))) }}</a></li>
		<li class="active">{{ 'Edit '.studly_case($type) }}</li>
	</ol>
</section>

<section class="content"> 
<div class="row pad"> 
	<div class="col-md-12">
		<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
	</div> 
</div>
	<div class="row pad">
		<div class="col-md-6">	
			@if(count($languages) > 1)
				<div  class="default_language_color">
					{{ Config::get('default_language.message') }}
				</div>
				<div class="wizard-nav wizard-nav-horizontal">
					<ul class="nav nav-tabs">
						@foreach($languages as $value)
							<?php $i = $value->id ; ?>
							<li class=" {{ ($i ==  $language_code )?'active':'' }}">
								<a data-toggle="tab" href="#{{ $i }}div">
									{{ $value -> title }}
								</a>
							</li>
						@endforeach
					</ul>
				</div>
			@endif
			
			{{ Form::open(['role' => 'form','route' => ['DropDown.edit',$dropdown->id,$type],'class' => 'mws-form','files' => true]) }}
				<?php /* @if(count($languages) > 1)
					<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
						<b>{{ trans("messages.system_management.language_field") }}</b>
					</div>
				@endif */ ?>
				<div class="mws-panel-body no-padding tab-content"> 
					@foreach($languages as $value)
					<?php $i = $value->id; ?>
						<div id="{{ $i }}div" class="tab-pane {{ ($i ==  $language_code )?'active':'' }} ">
							<div class="mws-form-inline">
								<div class="form-group <?php if($i == 1) {echo ($errors->first('name') ) ? 'has-error' : '';} ?>">
									@if($value -> id == 1)
										{!! HTML::decode( Form::label('name',trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
									@else
										{!! HTML::decode( Form::label('name',trans("Name").'<span class="requireRed"></span>', ['class' => 'mws-form-label'])) !!}
									@endif
									<div class="mws-form-item">
										{{ Form::text("data[$i][name]",isset($multiLanguage[$i]['name'])?$multiLanguage[$i]['name']:'', ['class' => 'form-control']) }}
										<div class="error-message help-inline">
											<?php echo ($i ==  $language_code ) ? $errors->first('name') : ''; ?>
										</div>
									</div>
								</div>
							</div>
						</div> 
					@endforeach
					<div class="mws-button-row">
						<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
						
						<a href="{{route('DropDown.edit',[$dropdown->id,$type])}}" class="btn btn-primary"><i class=\"icon-refresh\"></i> {{ trans('Reset')  }}</a>
						<a href="{{route('DropDown.listDropDown',$type)}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
					</div>
				</div>
			{{ Form::close() }} 
		</div>
	</div>
</section>
<style>
	.textarea_resize {
		resize: vertical;
	}
</style>
@stop
