<?php
namespace App\Http\Controllers\mobile;
use App\Http\Controllers\BaseController;
use App\Model\Challenge;
use App\Model\ChallengeQuestion;
use App\Model\ChallengeQuestionAnswer;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,App;
use Carbon\Carbon;

/**
* Friends Controller
*
* Add your methods in the class below
*
* This file use for call api
*/
class ChallengesController extends BaseController {
	
	//Search Challenge api
	public function challenge_list(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'user_id'			=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id		=	Input::get('user_id');
				$userDetail		=	DB::table("users")->where("id",$user_id)->first();
				if(!empty($user_id) &&  !empty($userDetail)){
					$end_date			=	date('Y-m-d');
					$user_complete_question	=	DB::table('challenge_questions')
												->where('user_id',$user_id)
												->Orwhere('is_active',1)
												->Orwhere('is_deleted',0)
												->pluck('challenge_id','challenge_id');
					$dateOfBirth	    = 	$userDetail->date_of_birth;
					$today 				= 	date("Y-m-d");
					$diff 				= 	date_diff(date_create($dateOfBirth), date_create($today));
					$userAge			=	$diff->format('%y');
					
					$challenge_lists	=	DB::table('challenges')
												->where('is_active',1)
												->where('is_deleted',0)
												->whereNotIn('id',$user_complete_question)
												->where('start_date','<=',$end_date)
												->where('end_date','>=',$end_date)
												->where(function ($query) use($userAge){
													$query->Orwhere(function ($query){
															$query->where("assign_dancer",1);
														});
													$query->Orwhere(function ($query) use($userAge){
														$query->where("assign_dancer",0);
														$query->where("minimum_age",'<=',$userAge);
														$query->where("maximum_age",'>=',$userAge);
													});
												})
												->select('challenges.id','challenges.image as image','challenges.challenge_name','challenges.end_date')
												->get();
					if(!($challenge_lists)->isEmpty()){
						foreach($challenge_lists as $challenge_list){
							if($challenge_list->image != "" && File::exists(CHALLENGE_IMAGE_ROOT_PATH.$challenge_list->image)){
								$challenge_list->image = CHALLENGE_IMAGE_URL.$challenge_list->image;
							}else{
								$challenge_list->image = WEBSITE_IMG_URL.'quiz.jpg';
							}
						}
						$response["status"]			=	"success";
						$response["message"]		=	"Challenge list found successfully.";
						$response['challenge_list']	=	$challenge_lists;
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"No record found.";
						$response["data"]		=	array();
					}
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function challenge_detail(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'challenge_id'		=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$challenge_id		=	Input::get('challenge_id');
				if(!empty($challenge_id)){
					$end_date			=	date('Y-m-d');
					$challenge_list	=	DB::table('challenges')
												->where('id',$challenge_id)
												->where('is_active',1)
												->where('is_deleted',0)
												->where('start_date','<=',$end_date)
												->where('end_date','>=',$end_date)
												->select('challenges.challenge_name','challenges.image as image','challenges.description','challenges.end_date','challenges.term_condition')
												->first();
					if(!empty($challenge_list)){
						if(!empty($challenge_list)){
							if($challenge_list->image != "" && File::exists(CHALLENGE_IMAGE_ROOT_PATH.$challenge_list->image)){
								$challenge_list->image = CHALLENGE_IMAGE_URL.$challenge_list->image;
							}else{
								$challenge_list->image = WEBSITE_IMG_URL.'quiz.jpg';
							}
							$challenge_list->description 	= !empty($challenge_list->description)? $challenge_list->description :'';
							$challenge_list->term_condition = !empty($challenge_list->term_condition) ? $challenge_list->term_condition : '';
						}
						$response["status"]			=	"success";
						$response["message"]		=	"Challenge list found successfully.";
						$response['challenge_list']	=	$challenge_list;
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"No record found.";
						$response["data"]		=	array();
					}
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function question_list_of_challenge(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'challenge_id'		=> 'required',
									'user_id'			=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				
				$user_id		=	Input::get('user_id');
				$userDetail		=	DB::table("users")->where("id",$user_id)->first();
				$dateOfBirth	    = 	$userDetail->date_of_birth;
				$today 				= 	date("Y-m-d");
				$diff 				= 	date_diff(date_create($dateOfBirth), date_create($today));
				$userAge			=	$diff->format('%y');
				$challenge_id		=	Input::get('challenge_id');
				if(!empty($challenge_id)){
											
					$challageDetail	=	DB::table('challenges')
											->where('id',$challenge_id)
											->where('is_active',1)
											->where('is_deleted',0)
											->first();
					$category			=	$challageDetail->category;
					$no_of_questions	=	$challageDetail->no_of_questions;
					
					if(!empty($no_of_questions)){
						$question_lists	=	DB::table('questions')
												->where('is_active',1)
												->where('is_deleted',0)
												->where('question_category_id',$category)
												->orderByRaw('RAND()')
												->where(function ($query) use($userAge){
													$query->Orwhere(function ($query){
															$query->where("question_grade_level",1);
														});
													$query->Orwhere(function ($query) use($userAge){
														$query->where("question_grade_level",0);
														$query->where("minimum_age",'<=',$userAge);
														$query->where("maximum_age",'>=',$userAge);
													});
												})
												->select('id','question','question_image')
												->limit($no_of_questions)
												->get();
							
							
						if(!empty($question_lists)){
							
							foreach($question_lists as $question_list){
								$question_list->correct_answer	=	DB::table('question_options')
																		->where('question_id',$question_list->id)
																		->where('is_answer',1)
																		->value('id');
								if($question_list->question_image != "" && File::exists(QUESTION_IMAGE_ROOT_PATH.$question_list->question_image)){
									$question_list->question_image = QUESTION_IMAGE_URL.$question_list->question_image;
								}else{
									$question_list->question_image = '';
								}
								$question_list->question_option	=	DB::table('question_options')
																		->where('question_id',$question_list->id)
																		->select('id','question_option','is_answer')
																		->get();
							}
							$response["status"]			=	"success";
							$response["message"]		=	"Question list found successfully.";
							$response['question_list']	=	$question_lists;
						}else{
							$response["status"]		=	"error";
							$response["message"]	=	"No record found.";
							$response["data"]		=	array();
						}
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"Invalid Request.";
						$response["data"]		=	array();
					}
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function save_challenge_questions(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'			=> 'required',
									'device_id'				=> 'required',
									'user_id'				=> 'required',
									'challenge_id'			=> 'required',
									'no_of_questions'		=> 'required',
									'correct_questions'		=> 'required',
									'incorrect_questions'	=> 'required',
									'missed_questions'		=> 'required',
									'time_taken'			=> 'required',
									'question_options'		=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$question 								=	New ChallengeQuestion;
				$question->user_id						=	!empty(Input::get('user_id'))?Input::get('user_id'):'';
				$question->challenge_id					=	!empty(Input::get('challenge_id'))?Input::get('challenge_id'):'';
				$question->no_of_questions				=	!empty(Input::get('no_of_questions'))?Input::get('no_of_questions'):'';
				$question->correct_questions			=	!empty(Input::get('correct_questions'))?Input::get('correct_questions'):'';
				$question->incorrect_questions			=	!empty(Input::get('incorrect_questions'))?Input::get('incorrect_questions'):'';
				$question->missed_questions				=	!empty(Input::get('missed_questions'))?Input::get('missed_questions'):'';
				$question->time_taken					=	!empty(Input::get('time_taken'))?Input::get('time_taken'):'';
				$question->save();
				
				$question_id			=	$question->id;
				$question_options		=	json_decode(Input::get('question_options'));
				if(!empty($question_options)){
					foreach($question_options as $question_option){
						$answers							=	New ChallengeQuestionAnswer;
						$answers->user_id					=	!empty($question->user_id)?$question->user_id:'';
						$answers->challenge_id				=	!empty($question->challenge_id)?$question->challenge_id:'';
						$answers->challenge_question_id		=	!empty($question_id)?$question_id:'';
						$answers->question_id				=	$question_option->question_id;
						$answers->answer_id					=	!empty($question_option->answer_id)?$question_option->answer_id:0;
						$answers->save();
					}
				}
				
				$response["status"]			=	"success";
				$response["message"]		=	"Questions has been update successfully.";
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	//leaderboard Challenge api
	public function leaderboard_challenge_list(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'user_id'			=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id		=	Input::get('user_id');
				$userDetail		=	DB::table("users")->where("id",$user_id)->first();
				if(!empty($user_id) &&  !empty($userDetail)){
					$end_date			=	date('Y-m-d');
					$user_complete_question	=	DB::table('challenge_questions')
												->where('user_id',$user_id)
												->Orwhere('is_active',1)
												->Orwhere('is_deleted',0)
												->pluck('challenge_id','challenge_id');
					$dateOfBirth	    = 	$userDetail->date_of_birth;
					$today 				= 	date("Y-m-d");
					$diff 				= 	date_diff(date_create($dateOfBirth), date_create($today));
					$userAge			=	$diff->format('%y');
					
					$challenge_lists	=	DB::table('challenges')
												->where('is_active',1)
												->where('is_deleted',0)
												->whereIn('id',$user_complete_question)
												->where('start_date','<=',$end_date)
												->select('challenges.id','challenges.image as image','challenges.challenge_name')
												->get();
					if(!empty($challenge_lists)){
						foreach($challenge_lists as $challenge_list){
							if($challenge_list->image != "" && File::exists(CHALLENGE_IMAGE_ROOT_PATH.$challenge_list->image)){
								$challenge_list->image = CHALLENGE_IMAGE_URL.$challenge_list->image;
							}else{
								$challenge_list->image = WEBSITE_IMG_URL.'quiz.jpg';
							}
						}
						$response["status"]			=	"success";
						$response["message"]		=	"Leaderboard challenge list found successfully.";
						$response['challenge_list']	=	$challenge_lists;
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"No record found.";
						$response["data"]		=	array();
					}
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function leaderboard_score_list(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'challenge_id'		=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$challenge_id		=	Input::get('challenge_id');
				if(!empty($challenge_id)){
					$end_date			=	date('Y-m-d');	
					$challenge_list		=	DB::table('challenges')->where('id',$challenge_id)->first();
					if(!empty($challenge_list)){
						$limit		=	isset($challenge_list->laederboards)?$challenge_list->laederboards:'10';			
						$challenges	=	DB::table('challenge_questions')
										->leftjoin('challenges','challenges.id','=','challenge_questions.challenge_id')
										->where('challenge_questions.challenge_id',$challenge_id)
										->where('challenge_questions.is_active',1)
										->where('challenge_questions.is_deleted',0)
										->select('challenge_questions.correct_questions as total_correct_questions',			 'challenge_questions.time_taken as total_time_taken',
													DB::raw('(SELECT full_name FROM users where id=challenge_questions.user_id) as username'))
										->orderBy('challenge_questions.correct_questions','DESC')
										->orderBy('challenge_questions.time_taken','DESC')
										->limit($limit)
										->get();
						$response["status"]			=	"success";
						$response["message"]		=	"Leaderboard list found successfully.";
						$response["data"]			=	$challenges;
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"No record found.";
						$response["data"]		=	array();
					}
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function price_list(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'challenge_id'		=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$challenge_id		=	Input::get('challenge_id');
				if(!empty($challenge_id)){
					$price_list		=	DB::table('challenge_prizes')
											->where('challenge_id',$challenge_id)
											->select('id','prize_name','prize_description','image',DB::raw('(SELECT challenge_name  FROM challenges where id=challenge_prizes.challenge_id) as challenge_name'))
											->get();
											
					if(!empty($price_list)){			
						foreach($price_list as $ch_list){
							if($ch_list->image != "" && File::exists(PRIZE_IMAGE_ROOT_PATH.$ch_list->image)){
								$ch_list->image = PRIZE_IMAGE_URL.$ch_list->image;
							}else{
								$ch_list->image = WEBSITE_IMG_URL.'quiz.jpg';
							}
						}
						$response["status"]			=	"success";
						$response["message"]		=	"Prize list found successfully.";
						$response["price_list"]		=	$price_list;
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"No record found.";
						$response["data"]		=	array();
					}
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function challenge_detail_by_id(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'challenge_id'		=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$challenge_id		=	Input::get('challenge_id');
				if(!empty($challenge_id)){
					$challenge_detail		=	DB::table('challenges')->where('id',$challenge_id)->where('is_active',1)->where('is_deleted',0)->select('description')->first();
					if(!empty($challenge_detail)){			
						
						$response["status"]			=	"success";
						$response["message"]		=	"Challenge detail found successfully.";
						$response["detail"]			=	$challenge_detail;
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"No record found.";
						$response["data"]		=	array();
					}
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function challenge_instructions(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'challenge_id'		=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$challenge_id		=	Input::get('challenge_id');
				if(!empty($challenge_id)){
					$instructions		=	DB::table('challenges')->where('id',$challenge_id)->where('is_active',1)->where('is_deleted',0)->select('instruction')->first();
					if(!empty($instructions)){			
						
						$response["status"]			=	"success";
						$response["message"]		=	"Challenge instructions found successfully.";
						$response["instructions"]		=	$instructions;
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"No record found.";
						$response["data"]		=	array();
					}
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function challenge_term_conditions(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'challenge_id'		=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$challenge_id		=	Input::get('challenge_id');
				if(!empty($challenge_id)){
					$termConditions		=	DB::table('challenges')->where('id',$challenge_id)->where('is_active',1)->where('is_deleted',0)->select('term_condition')->first();
					if(!empty($termConditions)){	
						$response["status"]			=	"success";
						$response["message"]		=	"Challenge terms and conditions found successfully.";
						$response["term_condition"]		=	$termConditions;
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"No record found.";
						$response["data"]		=	array();
					}
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function challenge_join_now(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'challenge_id'		=> 'required',
									'user_id'			=> 'required',
									'prize_id'			=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id			=	!empty(Input::get('user_id'))?Input::get('user_id'):'';
				$challenge_id		=	!empty(Input::get('challenge_id'))?Input::get('challenge_id'):'';
				$prize_id			=	!empty(Input::get('prize_id'))?Input::get('prize_id'):'';
				if(!empty($challenge_id) && !empty($user_id) && !empty($prize_id)){
					$saveData					=	new ChallengeQuestion;
					$saveData->user_id			=	!empty(Input::get('user_id'))?Input::get('user_id'):0;
					$saveData->challenge_id		=	!empty(Input::get('challenge_id'))?Input::get('challenge_id'):0;
					$saveData->prize_id			=	!empty(Input::get('prize_id'))?Input::get('prize_id'):0;
					
					$saveData->save();
					$Id		=		$saveData->id;
					$questions	=	DB::table('challenge_questions')->where('id',$Id)->select('challenge_id','user_id','prize_id')->first();
					
					$response["status"]				=	"success";
					$response["message"]			=	"Challenge join successfully.";
					$response["data"]				=	array();
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	
}
