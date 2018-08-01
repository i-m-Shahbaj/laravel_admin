<?php
namespace App\Model; 
use Eloquent;
/**
 * ChallengeQuestion Model
 */
class ChallengeQuestion extends Eloquent{
	
	 protected $table = 'challenge_questions';

	function question_option() {
		 return $this->hasMany('App\Model\QuestionOption','question_id','id')->select('question_options.*')->orderBy("id","ASC");
	}
}//end Class
