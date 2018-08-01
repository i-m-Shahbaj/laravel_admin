


<tr class="" id="document_contant_{{$document_count}}" rel="{{$document_count}}">
							
	<td width="90;">
	{{ Form::file("formdocument[$document_count][documents]",['class'=>"document_upload document_count"."$document_count"])}}
	<div class="error-message help-inline"></div>
	</td>
	<td width="10%;">
		<a title="Delete" onclick="delete_document({{$document_count}});" href="javascript:void(0);" class="btn btn-danger btn-small"><span class="ti-trash"></span></a>
	</td>
</tr>
