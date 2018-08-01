<?php
namespace App\Model; 
use Eloquent;
/**
 * Question Model
 */
class Question extends Eloquent{
	
	 protected $table = 'questions';

	function question_option() {
		 return $this->hasMany('App\Model\QuestionOption','question_id','id')->select('question_options.*')->orderBy("id","ASC");
	}
}//end Class
