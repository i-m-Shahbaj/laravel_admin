<tr class="" id="document_link_contant_{{$document_link}}" rel="{{$document_link}}">							
	<td width="30;">
	{{ Form::text("formlink[$document_link][url]","",['class'=>"form-control document_link valid validate_"."$document_link","placeholder"=>"Enter Url"])}}
	<div class="error-message help-inline"></div>
	</td>
	<td width="25%;">
		<a title="Delete" onclick="delete_document_link({{$document_link}});" href="javascript:void(0);" class="btn btn-danger btn-small"><span class="ti-trash"></span></a>
	</td>
</tr>
