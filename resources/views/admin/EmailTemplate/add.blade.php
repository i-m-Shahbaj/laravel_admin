@extends('admin.layouts.default')

@section('content')

<!--- ckeditor js start  here -->
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!--- ckeditor js end  here -->

<section class="content-header">
	 <h1>
		{{ trans("messages.system_management.add_email_template") }}
		
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route($modelName.'.index')}}">Email Templates</a></li>
		<li class="active">Add Email Template</li>
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
			{{ Form::open(['role' => 'form','route' => $modelName.'.add','class' => 'mws-form']) }}
				<div class="form-group <?php echo ($errors->first('name')?'has-error':''); ?>">
					{!! HTML::decode( Form::label('name',trans("messages.system_management.name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text('name', null, ['class' => 'form-control']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('name'); ?>
						</div>
					</div>
				</div> 
				<div class="form-group <?php echo ($errors->first('subject')?'has-error':''); ?>">
					{!! HTML::decode( Form::label('subject',trans("messages.system_management.subject").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text('subject', null, ['class' => 'form-control']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('subject'); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('action', trans("messages.system_management.action"), ['class' => 'mws-form-label']) !!}
					<div class="mws-form-item">
						{{ Form::select('action', $Action_options,'', ['class' => 'form-control','onchange'=>'constant()']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('action'); ?>
						</div>
					</div>
				</div>
				<div class="form-group <?php echo ($errors->first('constants')?'has-error':''); ?>">
					<table class="table table-bordered table-responsive">
						<tr>
							<td colspan="2" >
								{!! HTML::decode( Form::label('constants',trans("messages.system_management.constants").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
							</td>
						</tr>
						<tr>
							<td>
								{{ Form::select('constants', array(),'', ['placeholder' => 'Select one','class' => 'form-control','id'=>'constants']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('constants'); ?>
								</div>
							</td>
							<td>
								<a onclick = "return InsertHTML()" href="javascript:void(0)" class="btn  btn-success no-ajax pull-right"><i class="icon-white "></i>{{  trans("messages.system_management.insert_variable") }} </a>
							</td>
						</tr>
						
					</table>
				</div>
				<div class="form-group <?php echo ($errors->first('body')?'has-error':''); ?>">
					{!! HTML::decode( Form::label('body',trans("messages.system_management.email_body").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::textarea("body",'', ['class' => 'form-control','id' => 'body']) }}
						<span class="error-message help-inline">
							<?php echo $errors->first('body'); ?>
						</span>
					</div>
					<script type="text/javascript">
					/* For CKEDITOR */
						CKEDITOR.replace( 'body',
						{
							height: 350,
							width: 507,
							filebrowserUploadUrl : '<?php echo URL::to('admin/base/uploder'); ?>',
							filebrowserImageWindowWidth : '640',
							filebrowserImageWindowHeight : '480',
							enterMode : CKEDITOR.ENTER_BR
						});
					</script>
				</div>
				<div class="mws-button-row">
					<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
					
					<a href="{{route($modelName.'.add')}}" class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear')  }}</a>
					
					<a href="{{route($modelName.'.index')}}" class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
				</div>
			{{ Form::close() }} 	
		</div>
	</div>
</div>
</section>
<?php  $constant = ''; ?>
<script type='text/javascript'>
var myText = '<?php  echo $constant; ?>';
	$(function(){
		constant();
	});
	/* this function used for  insert contant, when we click on  insert variable button */
    function InsertHTML() {
		
		var strUser = document.getElementById("constants").value;
		
		if(strUser != ''){
			var newStr = '{'+strUser+'}';
			var oEditor = CKEDITOR.instances["body"] ;
			oEditor.insertHtml(newStr) ;	
		}
    }
	/* this function used for get constant,define in email template*/
	function constant() {
		var constant = document.getElementById("action").value;
			$.ajax({
				url: "<?php echo route($modelName.'.getConstant')?>",
				type: "POST",
				data: { constant: constant},
				dataType: 'json',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(r){
					$('#constants').empty();
					$('#constants').append( '<option value="">-- Select One --</option>' );
					$.each(r, function(val, text) {
						var sel ='';
						if(myText == text)
						 {
						   sel ='selected="selected"';
						 }
						 
						$('#constants').append( '<option value="'+text+'" '+sel+'>'+text+'</option>');
					});	
			   }
			});
		return false; 
	}	
</script>
<style>
	.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
		font-size: 14px !important;
		padding: 0px !important;
	}
	.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
		vertical-align: top !important;
	}
	.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
		border: 0px !important;
	}
	.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
		border-top: 0px !important;
		padding: 0px !important;
	}
	.table-bordered {
		border: 0px !important;
	}
</style>
@stop
