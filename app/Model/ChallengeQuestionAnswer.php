<?php
namespace App\Model; 
use Eloquent;
/**
 * ChallengeQuestionAnswer Model
 */
class ChallengeQuestionAnswer extends Eloquent{
	
	 protected $table = 'challenge_question_answers';

	function question_option() {
		 return $this->hasMany('App\Model\QuestionOption','question_id','id')->select('question_options.*')->orderBy("id","ASC");
	}
}//end Class
