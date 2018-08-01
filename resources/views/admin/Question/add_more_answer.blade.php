<tr id="answer_contant_{{$answer_count}}" rel="{{ $answer_count }}">
	
	<td width="80%">{{ Form::text("formanswer[$answer_count][answer]",'',['class' => 'form-control validate_'."$answer_count" ,"id"=>""] )}}
	<div class="error-message help-inline">
	</div>
	</td>
	
	<td>{{ Form::radio("formanswer[is_answer]",$answer_count,"",['class'=>'question_checked ']) }}
		<div class="error-message help-inline">
								</div>
	</td>
	<td width="20%;">
		<a title="Delete" onclick="delete_answer({{$answer_count}});" href="javascript:void(0);" class="btn btn-danger btn-small"><span class="ti-trash"></span></a>
	</td>
</tr>

