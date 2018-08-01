@extends('admin.layouts.default')

@section('content')
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::script('js/admin/chosen/chosen.jquery.min.js') }}
{{ HTML::style('css/admin/chosen.min.css') }}
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
.chosen-container-single .chosen-single{
	padding: 5px 5px 5px 8px;
    height: 35px;
}
</style>
<script type="text/javascript"> 
	$(document).ready(function(){
		 $(".chosen-select").chosen({width: "100%"});
	}); 
</script>
<!-- CKeditor start here-->
{{ HTML::script('js/admin/plugins/ckeditor/ckeditor.js') }}
<style>
	.table>tbody>tr>td{
		border-top:none;
	}
</style>
{{ HTML::script('js/admin/jquery-ui-1.9.2.min.js') }}
{{ HTML::script('js/admin/jquery-ui-timepicker.min.js') }}
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
<script>
jQuery(document).ready(function(){
	 $( "#comment_end_date" ).datepicker({
		dateFormat 	: 'yy-mm-dd',
		changeMonth : true,
		changeYear 	: true,
		 minDate: 0,
		//yearRange	: '1950:2013',
	});
});
</script>
<section class="content-header">
	<h1>
		{{ trans("Add Article") }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{route('dashboard.showdashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="{{route('ProjectFolder.index')}}">Blog Management</a></li>
		<li><a href='{{route("$modelName.conetentIndex")}}'>{{ trans("Content") }}</a></li>
		<li class="active">{{ trans("Add Folder") }}</li>
	</ol>
</section>
<section class="content"> 
	<div class="row pad"> 
		<div class="col-md-12">
			<a class="btn btn-primary pull-right" href="{{URL::previous()}}">Back</a>
		</div> 
	</div>
	<div class="row pad">
		{{ Form::open(['role' => 'form','route' => "$modelName.save",'class' => 'mws-form','files'=>'true','id'=>'article_form']) }}
		<div class="col-md-6">
			<div class="form-group <?php  echo ($errors->first('project_folder_id')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					{!! HTML::decode( Form::label('project_folder_id',trans("Cateogry").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label '])) !!}
					<div class="mws-form-item">
						{{ Form::select("project_folder_id",[null=>'Select Category']+$categoriesList,'', ['class' => 'form-control chosen-select valid','id' => 'name']) }}
						<div class="error-message help-inline project_folder_id_error">
							<?php echo $errors->first('project_folder_id'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group <?php  echo ($errors->first('article_name')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					{!! HTML::decode( Form::label('article_name',trans("Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label '])) !!}
					<div class="mws-form-item">
						{{ Form::text("article_name",'', ['class' => 'form-control valid','id' => 'name']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('article_name'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group image_section">
				<div class="mws-form-row">
					{!!  HTML::decode( Form::label('image', trans("Featured Image"), ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::file('image','',['class' => 'form-control image valid','type'=>'file']) }}
						<div class="error-message help-inline" id="image_error">
							<?php echo $errors->first('image'); ?>
						</div>
						<div class="image_display" style="display:none;">
							<img id="blah" src="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>" width='100' height="100" />
						</div>
					</div>
				</div>
			</div>
			<div class="preview"></div>
			<div class="form-group <?php  echo ($errors->first('access_rule')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					{!! HTML::decode( Form::label('access_rule',trans("Access Rule").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label '])) !!}
					<div class="mws-form-item">
						{{ Form::select("access_rule",array(""=>'Select Rule','public'=>'Public','registered'=>'Registered','private'=>'Private'),false, ['class' => 'form-control valid','id' => 'access_rule']) }}
						<div class="error-message help-inline " id="access_rule_error">
							<?php echo $errors->first('access_rule'); ?>
						</div>
					</div>
				</div>
			</div>
			<div id="wrapper">
				<div id="item_div">
					<div class="items" id="item1">
						<p>Simple Navy Blue T-Shirt</p>
						<input type="hidden" id="item1_name" value="Simple Navy Blue T-Shirt">
						<input type="hidden" id="item1_price" value="$95">
					</div>

					<div class="items" id="item2">
						<p>Trendy T-Shirt With Back Design</p>
						<input type="hidden" id="item2_name" value="Trendy T-Shirt With Back Design">
						<input type="hidden" id="item2_price" value="$105">
					</div>
				  
					<div class="items" id="item3">
						<p>Two Color Half-Sleeves T-Shirt</p>
						<input type="hidden" id="item3_name" value="Two Color Half-Sleeves T-Shirt">
						<input type="hidden" id="item3_price" value="$120">
					</div>
				</div>

				<div id="cart_label_div">
				 <p id="cart_label" onclick="show_cart();"><?php //echo count($_SESSION['items']);?> Items In Your Cart</p>
				</div>

				<div id="mycart" style="height:400px;border:1px solid #000">
				<?php
				/* if($_SESSION['items'])
				 {
				  for($i=0;$i<count($_SESSION['items']);$i++)
				  {
				   $item_val=explode("+",$_SESSION['items'][$i]);
				   ?>
				   <div class='cart_items'>
					<img src='<?php echo $item_val[2];?>'>
					<p><?php echo $item_val[0];?></p>
					<p><?php echo $item_val[1];?></p>
					<input type='button' value='Remove Item' onclick='remove_item("<?php echo $_SESSION['items'][$i];?>");'>
				   </div>
				   <?php
				  }
				 }
				 else
				 {
				  echo "<p id='mycart_label'>Drop Items Here</p>";
				 }*/
				?>
				</div>

				</div>
		</div>
		
		<div class="col-md-12">	
			<div class="form-group">
				{!! HTML::decode( Form::label('article_description',trans("Description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
				<div class="mws-form-item">
					{{ Form::textarea("article_description",'', ['class' => 'form-control textarea_resize','id' => 'description',"rows"=>3,"cols"=>3]) }}
					{{ Form::hidden("ck_value",'', ['class' => 'valid','id' => 'ck_value']) }}
					<span class="error-message help-inline" id="article_description_error">
						<?php echo $errors->first('article_description'); ?>
					</span>
				</div>
				<script type="text/javascript">
				/* For CKEDITOR */
					
					CKEDITOR.replace( <?php echo 'description'; ?>,
					{
						height: 200,
						width: 1000,
						filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
						filebrowserImageWindowWidth : '640',
						filebrowserImageWindowHeight : '480',
						enterMode : CKEDITOR.ENTER_BR
					});	
				</script>
			</div>
			
			
			<div class="form-group <?php  echo ($errors->first('is_check_this_out')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					<div class="mws-form-item">
						{{ Form::checkbox("is_check_this_out",'1',false, ['class' => '','id' => 'is_check_this_out']) }} {!! HTML::decode( Form::label('is_check_this_out',trans("Add to Check this out"), ['class' => 'mws-form-label '])) !!}
						<div class="error-message help-inline">
							<?php echo $errors->first('is_check_this_out'); ?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="form-group <?php  echo ($errors->first('allow_comments')) ? 'has-error' : ''; ?>">
				<div class="mws-form-row">
					<div class="mws-form-item">
						{{ Form::checkbox("allow_comments",'1',false, ['class' => 'valid','id' => 'allow_comments']) }} 
						{!! HTML::decode( Form::label('allow_comments',trans("Allow comments"), ['class' => 'mws-form-label '])) !!}
						<div class="error-message help-inline">
							<?php echo $errors->first('allow_comments'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group comment_end_date <?php  echo ($errors->first('comment_end_date')) ? 'has-error' : ''; ?>" style="display:none;">
				<div class="mws-form-row  col-md-6">
					{!! HTML::decode( Form::label('comment_end_date',trans("Comment End Date"), ['class' => 'mws-form-label '])) !!}
					<div class="mws-form-item">
						{{ Form::text("comment_end_date",'',['class' => 'form-control','id' => 'comment_end_date','readonly'=>'readonly']) }} 
						<div class="error-message help-inline">
							<?php echo $errors->first('comment_end_date'); ?>
						</div>
					</div>
				</div>
				<br/>
				<br/>
				<br/>
			</div>
			
		</div>



		<?php
			$document_count		=	1;
		?>

		<div class="col-lg-12 col-md-12">
			<div class="col-md-8 col-sm-8" id="getQuantity" >
				<?php //pr($productDetail->getProductQuantity); ?>
				<div class="form-group">
					{!! HTML::decode( Form::label('video_file',trans("Upload Attachments (Video/File)").':', ['class' => 'mws-form-label'])) !!}<br/>
					<button style="float:right; margin-top:-3%;" type="button" onclick="add_more_form_documents()" class="btn btn-info"><i class="fa fa-plus"></i></button>
				</div>
				<div class="mws-panel-body plan_price"> 	
					<div class="row">
						<div class="col-md-12">
							<table class="table table-responsive table-bordered ">
								<tbody id="document_section">
									<tr>
										<td>
											<label>Video/File</label>
										</td>
										<td>
											<label></label>
										</td>
									</tr>
									<tr class="" id="document_contant_{{$document_count}}" rel="{{$document_count}}">
						
										<td width="30;">
											{{ Form::file("formdocument[$document_count][documents]",['class'=>"document_upload validate_"."$document_count"])}}
											<div class="error-message help-inline"></div>
										</td>
										<td width="25%;">
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php
			$document_link		=	1;
		?>
		<div class="col-lg-12 col-md-12">
			<div class="col-md-8 col-sm-8" id="getQuantity" >
				<div class="form-group">
					{!! HTML::decode( Form::label('video_file',trans("Add Web Links").':', ['class' => 'mws-form-label'])) !!}<br/>
					<button style="float:right; margin-top:-3%;" type="button" onclick="add_more_form_links()" class="btn btn-info"><i class="fa fa-plus"></i></button>
				</div>
				<div class="mws-panel-body plan_price"> 	
					<div class="row">
						<div class="col-md-12">
							<table class="table table-responsive table-bordered ">
								<tbody id="document_link_section">
									<tr>
										<td>
											<label>Media & Web Link</label>
										</td>
										<td>
											<label></label>
										</td>
									</tr>
									<tr class="" id="document_link_contant_{{$document_link}}" rel="{{$document_link}}">
						
										<td width="30;">
											{{ Form::text("formlink[$document_link][url]","",['class'=>"form-control document_link validate_"."$document_link","placeholder"=>"Enter Url"])}}
											<div class="error-message help-inline"></div>
										</td>
										<td width="25%;">
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-6">	
			<div class="mws-button-row">
			<input type="button" value="{{ trans('messages.user_management.save') }}" onclick="add_articles();" class="btn btn-danger">
				
				<a href='{{route("$modelName.add")}}' class="btn btn-primary reset_form"><i class=\"icon-refresh\"></i> {{ trans('Clear')  }}</a>
				
				<a href='{{route("$modelName.conetentIndex")}}' class="btn btn-info"><i class=\"icon-refresh\"></i> {{ trans('Cancel')  }}</a>
			</div>
		</div>
		{{ Form::close() }} 
		</div>
	</div>
</section>
<script>
	function add_articles() {
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$('.form-group').parent().removeClass('has-error');
		$('.mws-form-item').removeClass('has-error');
		$("#ck_value").val(CKEDITOR.instances['description'].getData());
		var formData  = $('#article_form')[0];
		var $inputs 				= 	$('#article_form :input.valid');
		var $documents 				= 	$('#article_form :input.document_upload');
		var $documentLinks 				= 	$('#article_form :input.document_link');
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG','MP4','MP4V'];
		var image_validation		=	'<?php echo __('Please upload a valid image. Valid extensions are jpg, jpeg, png, jpeg, mp4, mp4v')?>';
		var error  =	0;
		$inputs.each(function(){
			if($(this).attr('name') == 'allow_comments'){
				if($(this).prop("checked")){
					if($("#comment_end_date").val() == ""){
						$("#comment_end_date").next().addClass("error");
						$("#comment_end_date").next().html('Please select comment end date.');
					}
				}else{
					$("#comment_end_date").next().html('');
					$("#comment_end_date").next().removeClass('error');
				}
			}else if($(this).val() ==''){
					error	=	1;
					if($(this).attr('name')=='article_description'){
						$("#article_description_error").addClass('error');
						$("#article_description_error").html('This field is required.');
					}if($(this).attr('name')=='access_rule'){
						$("#access_rule_error").addClass('error');
						$("#access_rule_error").html('This field is required.');
					}else if($(this).attr('name')=='ck_value'){
						$("#article_description_error").addClass('error');
						$("#article_description_error").html('This field is required.');
					}if($(this).attr("name") == "project_folder_id"){
						$(".project_folder_id_error").addClass('error');
						$(".project_folder_id_error").html('This field is required.');
					}else{
						$(this).next().addClass('error');
						$(this).next().html('This field is required.');
					}
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});
		$documents.each(function() { 
			if($(this).attr('name') == 'allow_comments'){
				if($(this).prop("checked")){
					if($("#comment_end_date").val() == ""){
						error = 1;
					}
				}
			}else if($(this).val() ==''){
					error	=	1;
					$(this).next().addClass('error');
					$(this).next().html('This field is required.');
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});
		$documentLinks.each(function() { 
			if($(this).val() !=''){
				url	=	$(this).val();
				url_validate = /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
				if(!url_validate.test(url)){
					error	=	1;
					$(this).next().addClass('error');
					$(this).next().html('Please enter valid url.');
				}
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});
		if(error == 0){
			$("#article_form").submit();
		}
	}
	
	function add_more_form_documents() {
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$('.mws-form-item').removeClass('has-error');
		total_count			=	$('#document_section').find("tr").last().attr("rel");
		var $inputs 				= 	$('#document_section :input.validate_'+total_count);
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG','mp4','mp4v'];
		var image_validation		=	'<?php echo __('Please upload a valid image. Valid extensions are jpg, jpeg, png, jpeg, mp4')?>';
		var error  =	0;	
		$inputs.each(function() {
			if($(this).val() ==''){
				error	=	1;
				$(this).next().addClass('error');
				$(this).next().html('This field is required.');
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});
		if(error == 0){
			if(typeof total_count !== "undefined") {
				total_count		=	parseInt(total_count)+1;
			}else {
				total_count		=	1;
			}
			$('#loader_img').show();
			$.ajax({
				headers: {
					 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
				url: '{{ route($modelName.".addMoreDocument") }}',
				type: 'POST',
				data: { total_count: total_count},
				success: function(response) {
					$('#document_section').append(response);
					$('#loader_img').hide();
				}
			});
		}
	}
	function add_more_form_links() {
		$('.help-inline').html('');
		$('.help-inline').removeClass('error');
		$('.form-group').removeClass('has-error');
		$('.mws-form-item').removeClass('has-error');
		total_count			=	$('#document_link_section').find("tr").last().attr("rel");
		var $inputs 				= 	$('#document_link_section :input.validate_'+total_count);
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG','mp4','mp4v'];
		var image_validation		=	'<?php echo __('Please upload a valid image. Valid extensions are jpg, jpeg, png, jpeg, mp4')?>';
		var error  =	0;	
		$inputs.each(function() {
			var url = $(this).val();
			if(url ==''){
				error	=	1;
				$(this).next().addClass('error');
				$(this).next().html('This field is required.');
			}else if(url != ''){
				url_validate = /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
				if(!url_validate.test(url)){
					error	=	1;
					$(this).next().addClass('error');
					$(this).next().html('Please enter valid url.');
				}
			}else{
				$(this).next().html('');
				$(this).next().removeClass('error');
			}
		});
		if(error == 0){
			if(typeof total_count !== "undefined") {
				total_count		=	parseInt(total_count)+1;
			}else {
				total_count		=	1;
			}
			$('#loader_img').show();
			
			$.ajax({
				headers: {
					 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
				url: '{{ route($modelName.".addMoreDocumentLink") }}',
				type: 'POST',
				data: { total_count: total_count},
				success: function(response) {
					$('#document_link_section').append(response);
					$('#loader_img').hide();
				}
			});
		}
	}
	function delete_document(row_counter) {
		bootbox.confirm("Are you sure want to delete this ?",
		function(result){
			if(result){	
				$('#document_contant_'+row_counter).remove();
			}
		});
	}
	function delete_document_link(row_counter) {
		bootbox.confirm("Are you sure want to delete this ?",
		function(result){
			if(result){	
				$('#document_link_contant_'+row_counter).remove();
			}
		});
	}
	
	
	function readURL(input){
		if (input.files && input.files[0]){
			var reader = new FileReader();
			reader.onload = function(e){
				$('.image_display').css("display", "block");
				$('#blah').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	$("#image").change(function() {
		readURL(this);
	});
	
	$(document).ready(function(){
		if($("#allow_comments").prop("checked") == true){
			$(".comment_end_date").show();
		}else{
			$(".comment_end_date").hide();
		}
	});
	
	$("#allow_comments").click(function(){
		if($(this).prop("checked") == true){
			$(".comment_end_date").show();
		}else{
			$(".comment_end_date").hide();
		}
	});
	
	$("#access_rule").change(function(){
		var rule = $("#access_rule option:selected").val();
		if(rule == 'private'){
			
		}
	});
	
	$(document).ready(function(){
		$(".items").draggable({ 
			//containment: 'document',
			//opacity: 0.6,
			//revert: 'invalid',
			//helper: 'clone',
			//zIndex: 100
		});
		
		$("#mycart").droppable({
			drop:function(e, ui){
				alert(12);
				var param = $(ui.draggable).attr('id');
				cart(param);
			}
		});
	});

function cart(id){
	var ele=document.getElementById(id);
	var img_src=ele.getElementsByTagName("img")[0].src;
	var name=document.getElementById(id+"_name").value;
	var price=document.getElementById(id+"_price").value;
	var response	= 	"<div class='cart_items'><p><?php //echo $item_val[0];?></p><input type='button' value='Remove Item'></div>";
	document.getElementById("mycart").append(response);
}
</script>
@stop
