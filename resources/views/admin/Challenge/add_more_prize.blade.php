
<tr class="" id="prize_contant_{{$prize_count}}" rel="{{$prize_count}}">
	<td width="25%;">
		{{ Form::text("prize[$prize_count][prize_name]","",['class'=>"form-control validate_"."$prize_count","placeholder"=>"Prize Name"])}}
		<div class="error-message help-inline"></div>
	</td>
	<td width="30%;">
		{{ Form::textarea(
			 "prize[$prize_count][prize_description]",'',
			 ['class' => "form-control validate_"."$prize_count","placeholder"=>"Prize Description","cols"=>"3","rows"=>"4"]
			) 
		}}
		<div class="error-message help-inline"></div>
	</td>
	<td width="4%;">
		{{ Form::file("prize[$prize_count][image]",['class'=>"document_upload valid validate_"."$prize_count"])}}
		<div class="error-message help-inline"></div>
	</td>
	<td width="25%;">
		<a title="Delete" onclick="delete_prize({{$prize_count}});" href="javascript:void(0);" class="btn btn-danger btn-small"><span class="ti-trash"></span></a>
	</td>
</tr>
