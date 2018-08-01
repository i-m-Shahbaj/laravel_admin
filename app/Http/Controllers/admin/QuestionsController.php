<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\User;
use App\Model\Question;
use App\Model\QuestionOption;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
use App\PHPExcel\PHPExcel\IOFactory1;
use App\PHPExcel\PHPExcel\PHPExcel_Cell;

/**
 * QuestionController
 *
 * Add your methods in the class below
 *
 * This file will render views from views/Questions
 */
 
class QuestionsController extends BaseController {
	public $model	=	'Question';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
 /**
 * Function for display list of all question
 *
 * @param null
 *
 * @return view page. 
 */
	public function listQuestions(){
		
		$DB = Question::query();
		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		if (Input::get()) {
			$searchData	=	Input::get();
			unset($searchData['display']);
			unset($searchData['_token']);
			
			if(isset($searchData['order'])){
				unset($searchData['order']);
			}
			if(isset($searchData['sortBy'])){
				unset($searchData['sortBy']);
			}
			
			if(isset($searchData['page'])){
				unset($searchData['page']);
			}
			if(!empty($searchData['question_category_id'])){
				$fieldValue	=	$searchData['question_category_id'];	
				$DB->whereRaw('FIND_IN_SET(\''.$fieldValue .'\',questions.question_category_id)');
			}
			foreach($searchData as $fieldName => $fieldValue){
				if(!empty($fieldValue) || $fieldValue == 0){
					$DB->where("$fieldName",'like','%'.$fieldValue.'%');
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		
		
		$sortBy 	= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
	    $order  	= 	(Input::get('order')) ? Input::get('order')   : 'DESC';
		
		$result 	= 	$DB->orderBy($sortBy, $order)
									->select("questions.*",DB::raw("(select GROUP_CONCAT(name SEPARATOR ', ') from dropdown_managers WHERE FIND_IN_SET(id,questions.question_category_id)) as category_name"))
								->paginate(Config::get("Reading.records_per_page"));
		
		
		$complete_string		=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$result->appends(Input::all())->render();

		Session::put('question_lists_records', $result);
		
		$questionCategory	=	DB::table("dropdown_managers")->where("dropdown_type","challenge-categories")->where("is_active",1)->pluck("name","id")->toArray();
		
		return  View::make("admin.$this->model.index", compact('result','searchVariable','sortBy','order','query_string','company_lists','questionCategory'));
	}// end listQuestions()

	
/**
 * Function for add questions
 *
 * @param null
 *
 * @return view page. 
 */	
	public function addQuestion(){
		
		$questionCategory	=	DB::table("dropdown_managers")->where("dropdown_type","challenge-categories")->where("is_active",1)->pluck("name","id")->toArray();
		//echo '</pre>'; print_r($questionCategory);die;
		return  View::make("admin.$this->model.add",compact("questionCategory",'criteria_list'));
	}//end addQuestion()
	
/**
 * Function for save question
 *
 * @param null
 *
 * @return view page. 
 */	
	public function saveQuestion() {
		$formData	=	Input::all();//pr($formData);die;
		Validator::extend('greater_than_field', function($attribute, $value, $parameters, $validator) {
		  $min_field = $parameters[0];
		  $data = $validator->getData();
		  $min_value = $data[$min_field];
		  return $value > $min_value;
		});   

		Validator::replacer('greater_than_field', function($message, $attribute, $rule, $parameters) {
		  return str_replace(':field', $parameters[0], $message);
		});
		if(!empty($formData)){
			if( Input::get('question_grade_level')==0){
				$validator = Validator::make(
					Input::all(),
					array(
						'formanswer'	=> 'required|array',
						'question_category_id'	=> 'required|array',
						'question_grade_level'	=> 'required',
						'question'				=> 'required',
						'question_image'		=> 'mimes:'.IMAGE_EXTENSION,
						'minimum_age' 			=> "required|integer|min:1",
						'maximum_age' 			=> "required|integer|greater_than_field:minimum_age",
					),
					array(
						'maximum_age.greater_than_field'	=>	"The maximum age must be greater than minimum age.",
					)
				);
			}else{
				$validator = Validator::make(
					Input::all(),
					array(
						'formanswer'	=> 'required|array',
						'question_category_id'	=> 'required|array',
						'question_grade_level'	=> 'required',
						'question'				=> 'required',
						'question_image'		=> 'mimes:'.IMAGE_EXTENSION,
					),
					array(
						'maximum_age.greater_than_field'	=>	"The maximum age must be greater than minimum age.",
					)
				);
			}
			if ($validator->fails()){
				 //return Redirect::back()->withErrors($validator)->withInput();
				$errors 				=	$validator->messages();
				$response							=	array(
					'success' 						=> 	0,
					'errors' 						=> 	$errors
				);
				return Response::json($response); 
				die;
			}else{ 
				$obj 								=  new Question;
				$obj->question 						=   Input::get('question');
				$obj->question_category_id 			=  implode(",",Input::get('question_category_id')); 
				$obj->question_grade_level			=  Input::get('question_grade_level');
				if($obj->question_grade_level == 0){
					$obj->minimum_age			=  !empty(Input::get('minimum_age'))?Input::get('minimum_age'):'';
					$obj->maximum_age			=  !empty(Input::get('maximum_age'))?Input::get('maximum_age'):'';
				}else{
					$obj->minimum_age			=  '';
					$obj->maximum_age			=  '';
				}
				
				
				if(input::hasFile('question_image')){
					$extension 	=	 Input::file('question_image')->getClientOriginalExtension();
					$fileName	=	time().'-question-image.'.$extension;
					
					$newFolder     	= 	strtoupper(date('M'). date('Y'))."/";
					$folderPath		=	QUESTION_IMAGE_ROOT_PATH.$newFolder;
					if(!File::exists($folderPath)) {
						File::makeDirectory($folderPath, $mode = 0777,true);
					}
					if(Input::file('question_image')->move($folderPath, $fileName)){
						$obj->question_image	=	$newFolder.$fileName;
					}
				}
			
				$obj->save();
				
				if(!empty($formData['formanswer'])) {
					foreach($formData['formanswer'] as $key=>$form_answers) {
						if(is_numeric($key)){
							$obj1 					=  	new QuestionOption;
							$obj1->question_id 		=  	$obj->id;
							$obj1->question_option	=  isset($form_answers['answer']) ? $form_answers['answer'] : '';
							if(isset($formData['formanswer']['is_answer'])){
								if($formData['formanswer']['is_answer'] == $key){
									$obj1->is_answer    	=	1;
								}else{
									$obj1->is_answer    	=	0;
								}
							}
							$obj1->save();
						}
					}
				}
				//Session::flash('success',trans("Question has been added successfully."));
				//return Redirect::route("$this->model.index");.
				$errors 				=	$validator->messages();
				$response							=	array(
					'success' 						=> 	1,
					'errors' 						=> 	$errors
				);
				return Response::json($response); 
				die;
			}
		}
	}//end saveQuestion()
	
/**
 * Function for display page for edit
 *
 * @param $id as id of question
 *
 * @return view page. 
 */
	public function editQuestion($id = 0){
		
		if($id){
			$details		=	Question::find($id);
			$option_details	=	DB::table('question_options')->where('question_id','=',$id)->get();
			$questionCategory	=	DB::table("dropdown_managers")->where("dropdown_type","challenge-categories")->where("is_active",1)->pluck("name","id")->toArray();
			return View::make("admin.$this->model.edit", compact('details','option_details','questionCategory'));
		}
	}//end editQuestion()

 /**
 * Function for update questions detail
 *
 * @param $id as id
 *
 * @return redirect page. 
 */
	public function updateQuestion($id = 0){
		
		$thisData				=	Input::all(); 
		$formData				=	Input::all();
		Validator::extend('greater_than_field', function($attribute, $value, $parameters, $validator) {
		  $min_field = $parameters[0];
		  $data = $validator->getData();
		  $min_value = $data[$min_field];
		  return $value > $min_value;
		});   

		Validator::replacer('greater_than_field', function($message, $attribute, $rule, $parameters) {
		  return str_replace(':field', $parameters[0], $message);
		});
		if( Input::get('question_grade_level')==0){
			$validator = Validator::make(
				Input::all(),
				array(
					'formanswer'	=> 'required|array',
					'question_category_id'	=> 'required|array',
					'question_grade_level'	=> 'required',
					'question'				=> 'required',
					'question_image'		=> 'mimes:'.IMAGE_EXTENSION,
					'minimum_age' 			=> "required|integer|min:1",
					'maximum_age' 			=> "required|integer|greater_than_field:minimum_age",
				),
				array(
					'maximum_age.greater_than_field'	=>	"The maximum age must be greater than minimum age.",
				)
			);
		}else{
			$validator = Validator::make(
				Input::all(),
				array(
					'formanswer'	=> 'required|array',
					'question_category_id'	=> 'required|array',
					'question_grade_level'	=> 'required',
					'question'				=> 'required',
					'question_image'		=> 'mimes:'.IMAGE_EXTENSION,
				),
				array(
					'maximum_age.greater_than_field'	=>	"The maximum age must be greater than minimum age.",
				)
			);
		}
		if ($validator->fails()){	
			//return Redirect::back()->withErrors($validator)->withInput();
			$errors 				=	$validator->messages();
			$response							=	array(
				'success' 						=> 	0,
				'errors' 						=> 	$errors
			);
			return Response::json($response); 
			die;
		}else{
			//pr(Input::all());die;
			$obj 						=  Question::find($id);
			$obj->question 				=  	Input::get('question');
			$obj->question_category_id 			=  implode(",",Input::get('question_category_id')); 
			$obj->question_grade_level			=  Input::get('question_grade_level');
			if($obj->question_grade_level == 0){
				$obj->minimum_age			=  !empty(Input::get('minimum_age'))?Input::get('minimum_age'):'';
				$obj->maximum_age			=  !empty(Input::get('maximum_age'))?Input::get('maximum_age'):'';
			}else{
				$obj->minimum_age			=  '';
				$obj->maximum_age			=  '';
			}
			
			if(input::hasFile('question_image')){
				$extension 	=	 Input::file('question_image')->getClientOriginalExtension();
				$fileName	=	time().'-question-image.'.$extension;
				
				$newFolder     	= 	strtoupper(date('M'). date('Y'))."/";
				$folderPath		=	QUESTION_IMAGE_ROOT_PATH.$newFolder;
				if(!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777,true);
				}
				if(Input::file('question_image')->move($folderPath, $fileName)){
					$obj->question_image	=	$newFolder.$fileName;
				}
			}
			
			$obj->save();
			
			$question_option	=	array();
			
			if(!empty($formData['formanswer'])) {
					foreach($formData['formanswer'] as $key=>$form_answers) {
						if(is_numeric($key)){
							if(!empty($form_answers['question_option_id'])) {
								$obj1 							=  	QuestionOption::find($form_answers['question_option_id']);
							}else {
								$obj1 							=  	new QuestionOption;
							}
							$obj1->question_id 		=  	$obj->id;
							$obj1->question_option	=  isset($form_answers['answer']) ? $form_answers['answer'] : '';
							if(isset($formData['formanswer']['is_answer'])){
								// "sdfsd";
								if($formData['formanswer']['is_answer'] == $key){
									$obj1->is_answer    	=	1;
								}else{
									$obj1->is_answer    	=	0;
								}
							}
							$obj1->save();
						}
						$question_option[] = $obj1->id;
					}
					
				}
				DB::table('question_options')->where("question_id",$id)->whereNotIn('id',$question_option)->delete();
			
			//Session::flash('success',trans("Question has been updated successfully."));
			//return Redirect::route("$this->model.index");
			$errors 				=	$validator->messages();
			$response							=	array(
				'success' 						=> 	1,
				'errors' 						=> 	$errors
			);
			return Response::json($response); 
			die;
		}
	}//end updateQuestion()

/**
 * Function for display page for edit
 *
 * @param $id as id of question
 *
 * @return view page. 
 */
	public function viewQuestion($questionId = 0){
	
		
		if($questionId){	
			$DB 					= 	Question::query();
			$questionDetails		=	$DB->with("question_option")->where('questions.id',$questionId)->select('questions.*')->first();
			if(!empty($questionDetails) && !empty($questionDetails->question_category_id)){
				$categoryNames = DB::table('dropdown_managers')->whereIn('id',@explode(",",$questionDetails->question_category_id))->select(DB::raw("(GROUP_CONCAT(name SEPARATOR ', ')) as category_name"))->first();
				$questionDetails->category_name = $categoryNames->category_name;
			}else{
				$questionDetails->category_name = '';
			}
			return View::make("admin.$this->model.view", compact('questionDetails'));
		}
	}//end editQuestion()
	
 /**
 * Function for delete question
 *
 * @param $userId as id of user
 *
 * @return redirect page. 
 */
	public function deleteQuestion($id = ""){
		if($id){
			Question::where('id',$id)->delete();
			DB::table('question_options')->where('question_id','=',$id)->delete();
			Session::flash('flash_notice',trans("Question has been deleted successfully"));
		}
		
		return Redirect::route("$this->model.index");
	}//end deleteQuestion()
	
/**
 * Function for update user status
 *
 * @param $id as id of user
 * @param $Status as status of user
 *
 * @return redirect page. 
 */
	public function updateQuestionStatus($id = 0, $Status = 0){
		if($Status == 0	){
			$statusMessage	=	trans("Question has been deactivated successfully");
		}else{
			$statusMessage	=	trans("Question has been activated successfully");
		}
		
		Question::where('id', '=', $id)->update(array('is_active' => $Status));
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::route("$this->model.index");
	} // end updateQuestionStatus()



	public function addAnswer() {
		$answer_count			=	Input::get('total_count');
		return  View::make("admin.$this->model.add_more_answer",compact('answer_count'));
	}

	public function deleteQuestionOption() {
		$id				=	 Input::get('question_option_id');
		QuestionOption::where('id',$id)->delete();
		die;
	}
	
}//end QuestionsController
