<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Challenge;
use App\Model\ChallengePrize;
use App\Model\EmailTemplate;
use App\Model\EmailAction;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

/**
* Users Controller
*
* Add your methods in the class below
*
* This file will render views from views/admin/usermgmt
*/
 
class ChallengesController extends BaseController {
	
	public $model	=	'Challenge';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
/**
* Function for display list of all users
*
* @param null
*
* @return view page. 
*/
	public function listChallenges(){
		$DB 					= 	Challenge::query();
		$DB1 					= 	Challenge::query();
		$searchVariable			=	array(); 
		$inputGet				=	Input::get(); 
		/* seacrching on the basis of username and email */ 
		if ((Input::get())) {
			$searchData			=	Input::get();
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
			if((!empty($searchData['from'])) && (!empty($searchData['to']))){
					$dateS = $searchData['from'];
					$dateE = $searchData['to'];
					$DB->whereBetween('challenges.start_date', [$dateS." 00:00:00", $dateE." 23:59:59"]); 
					$DB->whereBetween('challenges.end_date', [$dateS." 00:00:00", $dateE." 23:59:59"]); 
			}elseif(!empty($searchData['from'])){
					$dateS = $searchData['from'];
					$DB->whereBetween('challenges.start_date', [$dateS." 00:00:00", $dateS." 23:59:59"]); 
			}elseif(!empty($searchData['to'])){
					$dateE = $searchData['to'];
					$DB->whereBetween('challenges.end_date', [$dateE." 00:00:00", $dateE." 23:59:59"]); 
			}
			unset($searchData['from']);
			unset($searchData['to']);
			foreach($searchData as $fieldName => $fieldValue){
				if(!empty($fieldValue) || $fieldValue == 0){
					$DB->where("$fieldName",'like','%'.$fieldValue.'%');
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		$sortBy 				= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
	    $order  				= 	(Input::get('order')) ? Input::get('order')   : 'DESC';
		$result 				= 	$DB
									->select('challenges.*',DB::raw("(select GROUP_CONCAT(name SEPARATOR ', ') from dropdown_managers WHERE FIND_IN_SET(id,challenges.category)) as category_name"))
									->orderBy($sortBy, $order)
									//->get();
									->paginate(Config::get("Reading.records_per_page"));
		$complete_string		=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$result->appends(Input::all())->render();
		//echo '<pre>';print_r($result);die;
		return  View::make('admin.'.$this->model.'.index', compact('result' ,'searchVariable','sortBy','order','userType','query_string'));
	}// end listUsers()
/**
* Function for add users
*
* @param null
*
* @return view page. 
*/	
	public function addChallenge(){
		$challengesCategory	=	DB::table("dropdown_managers")->where("dropdown_type","challenge-categories")->where("is_active",1)->pluck("name","id")->toArray();
		return  View::make('admin.'.$this->model.'.add',compact('countryList','challengesCategory'));
	}//end addCompany()
	
	public function addPrize() {
		$prize_count			=	Input::get('total_count');
		return  View::make("admin.$this->model.add_more_prize",compact('prize_count'));
	}


/**
* Function for save added users
*
* @param null
*
* @return view page. 
*/	
	public function saveChallenge(){
		Input::replace($this->arrayStripTags(Input::all()));
		$formData						=	Input::all();
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
			if(Input::get("assign_dancer")==0){
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'sponsor_name'			=> 'required',
						'grade_level'			=> 'required',
						'challenge_name'		=> 'required',
						'start_date'			=> 'required',
						'end_date'			    => 'required',
						'no_of_questions'		=> 'required',
						'term_condition'		=> 'required',
						'instruction'			=> 'required',
						'description'			=> 'required',
						'assign_dancer'			=> 'required',
						'category'				=> 'required|array',
						'how_many_winners'	    => 'required|integer|min:1',
						'laederboards'			=> 'required|integer|min:1',
						'minimum_age' 			=> "required_if:assign_dancer,0|integer|min:1",
						'maximum_age' 			=> "required_if:assign_dancer,0|integer|greater_than_field:minimum_age",
						'image' 				=> 'mimes:'.IMAGE_EXTENSION,
						
					),
					array(
						'term_condition.required'		=>	"The Terms and Conditions field is required.",
						'minimum_age.required_if'		=>	"The minimum age field is required.",
						'maximum_age.required_if'		=>	"The maximum age field is required.",
						'laederboards.required'			=>	"The leaderboards field is required.",
						'how_many_winners.required'			=>	"The winners field is required.",
						'maximum_age.greater_than_field'	=>	"The maximum age must be greater than minimum age.",
						
					)
				);
			}else{
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'sponsor_name'			=> 'required',
						'grade_level'			=> 'required',
						'challenge_name'		=> 'required',
						'start_date'			=> 'required',
						'end_date'			    => 'required',
						'no_of_questions'		=> 'required',
						'description'			=> 'required',
						'term_condition'		=> 'required',
						'instruction'			=> 'required',
						'assign_dancer'			=> 'required',
						'assign_dancer'			=> 'required',
						'category'				=> 'required|array',
						'how_many_winners'	    => 'required|integer|min:1',
						'laederboards'			=> 'required|integer|min:1',
						'image' 				=> 'required|mimes:'.IMAGE_EXTENSION,
						
					),
					array(
						'term_condition.required'		=>	"The Terms and Conditions field is required.",
						'minimum_age.required_if'		=>	"The minimum age field is required.",
						'maximum_age.required_if'		=>	"The maximum age field is required.",
						'laederboards.required'			=>	"The leaderboards field is required.",
						'how_many_winners.required'			=>	"The winners field is required.",
						'maximum_age.greater_than_field'	=>	"The maximum age must be greater than minimum age.",
						
					)
				);
			}
			
			if ($validator->fails()){
				$errors 				=	$validator->messages();
				$response							=	array(
					'success' 						=> 	0,
					'errors' 						=> 	$errors
				);
				return Response::json($response); 
				die;
			}else{ 
				
				$obj 						=  new Challenge;
				$obj->sponsor_name 			=  Input::get('sponsor_name');
				$obj->challenge_name		=  !empty(Input::get('challenge_name'))?Input::get('challenge_name'):'';
				$obj->start_date			=  !empty(Input::get('start_date'))?Input::get('start_date'):'';
				$obj->end_date				=  !empty(Input::get('end_date'))?Input::get('end_date'):'';
				$obj->no_of_questions		=  !empty(Input::get('no_of_questions'))?Input::get('no_of_questions'):'';
				$obj->category				=  !empty(Input::get('category'))?implode(",",Input::get('category')):'';
				$obj->grade_level			=  !empty(Input::get('grade_level'))?Input::get('grade_level'):'';
				$obj->assign_dancer			=  !empty(Input::get('assign_dancer'))?Input::get('assign_dancer'):'';
				if($obj->assign_dancer == 0){
					$obj->minimum_age			=  !empty(Input::get('minimum_age'))?Input::get('minimum_age'):'';
					$obj->maximum_age			=  !empty(Input::get('maximum_age'))?Input::get('maximum_age'):'';
				}
				
				$obj->how_many_winners			=  !empty(Input::get('how_many_winners'))?Input::get('how_many_winners'):'';
				$obj->laederboards				=  !empty(Input::get('leaderboards'))?Input::get('leaderboards'):'';
				$obj->description				=  !empty(Input::get('description'))?Input::get('description'):'';
				$obj->term_condition			=  !empty(Input::get('term_condition'))?Input::get('term_condition'):'';
				$obj->instruction				=  !empty(Input::get('instruction'))?Input::get('instruction'):'';
				
				if(input::hasFile('image')){
					$challenge_image				=	!empty(Input::file('image'))?Input::file('image'):'';
					$extension 						=	$challenge_image->getClientOriginalExtension();
					$image_name			 			=	$challenge_image->getClientOriginalName();
					$fileName						=	time().'-challenge-document.'.$extension;
					$newFolder     					= 	strtoupper(date('M'). date('Y'))."/";
					$folderPath						=	CHALLENGE_IMAGE_ROOT_PATH.$newFolder;
					if(!File::exists($folderPath)) {
						File::makeDirectory($folderPath, $mode = 0777,true);
					}
					if($challenge_image->move($folderPath, $fileName)){
						$obj->image				=	$newFolder."/".$fileName;
						$obj->image_name			=	pathinfo($image_name, PATHINFO_FILENAME);
					}
				}
				$obj->save();
				$challengeId					=	$obj->id;	
				
				if(isset($formData['prize']) && !empty($formData['prize'])){
					foreach ($formData['prize'] as $challengePrize) {
						$model            				= new ChallengePrize();
						$model->challenge_id 				= $challengeId;
						$model->prize_name   				= $challengePrize['prize_name'];
						$model->prize_description    		= $challengePrize['prize_description'];
						$prizeImage							= !empty($challengePrize["image"])?$challengePrize["image"]:"";
						if($prizeImage){
							$extension 	=	 $prizeImage->getClientOriginalExtension();
							$fileName	=	time().'-prize-image.'.$extension;
							
							$newFolder     	= 	strtoupper(date('M'). date('Y'))."/";
							$folderPath		=	PRIZE_IMAGE_ROOT_PATH.$newFolder;
							if(!File::exists($folderPath)) {
								File::makeDirectory($folderPath, $mode = 0777,true);
							}
							if($prizeImage->move($folderPath, $fileName)){
								$model->image	=	$newFolder.$fileName;
							}
						}
						
						$model->save(); 
					}
				}						
				$errors 				=	$validator->messages();
				$response							=	array(
					'success' 						=> 	1,
					'errors' 						=> 	$errors
				);
				return Response::json($response); 
				die;
			}
		}
	}// saveUser()
/**
* Function for display user detail
*
* @param $userId 	as id of user
*
* @return view page. 
*/
	public function viewChallenge($challengeId = 0){
		$challengeDetails	=	DB::table('challenges')
								->where('challenges.id','=',$challengeId)
								->first(); 
		
		$challengePrizes	=	DB::table('challenge_prizes')
								->where('challenge_id','=',$challengeId)
								->get(); 
		if(empty($challengeDetails)) {
			return Redirect::route($this->model.'.index');
		}
		if(!empty($challengeDetails) && !empty($challengeDetails->category)){
			$categoryNames = DB::table('dropdown_managers')->whereIn('id',@explode(",",$challengeDetails->category))->select(DB::raw("(GROUP_CONCAT(name SEPARATOR ', ')) as category_name"))->first();
			$challengeDetails->category_name = $categoryNames->category_name;
		}else{
			$challengeDetails->category_name = '';
		}
		
		return View::make('admin.'.$this->model.'.view', compact('challengePrizes','challengeDetails','countryName','leagueCountryName','studioCountryName','stateName','leagueStateName','studioStateName','cityName','leagueCityName','studioCityName','dancerDetails'));
	} // end viewUser()
/**
* Function for display page for edit user
*
* @param $userId as id of user
*
* @return view page. 
*/
	public function editChallenge($challengeId = 0){
		$challengeDetails			=	Challenge::find($challengeId); 
		if(empty($challengeDetails)) {
			return Redirect::route($this->model.'.index');
		}
		if($challengeId){
			if($challengeDetails){
				$prizeDetails	=	DB::table('challenge_prizes')->where('challenge_id',$challengeDetails->id)->get();
			}else{
				$prizeDetails	=	array();
			}
			$challengesCategory	=	DB::table("dropdown_managers")->where("dropdown_type","challenge-categories")->where("is_active",1)->pluck("name","id")->toArray();
			return View::make('admin.'.$this->model.'.edit', compact('challengeDetails','prizeDetails','challengesCategory'));
		}
	} // end editUser()
/**
* Function for update user detail
*
* @param $userId as id of user
*
* @return redirect page. 
*/
	public function updateChallenge(){	
		Input::replace($this->arrayStripTags(Input::all()));
		$formData						=	Input::all(); 
		Validator::extend('greater_than_field', function($attribute, $value, $parameters, $validator) {
		  $min_field = $parameters[0];
		  $data = $validator->getData();
		  $min_value = $data[$min_field];
		  return $value > $min_value;
		});   
		
		//echo "<pre>";print_r($formData);die;
		Validator::replacer('greater_than_field', function($message, $attribute, $rule, $parameters) {
		  return str_replace(':field', $parameters[0], $message);
		});
		//echo "<pre>";print_r($thisData);die;
			$id							=	Input::get('id');
			if(Input::get("assign_dancer")==0){
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'sponsor_name'			=> 'required',
						'grade_level'			=> 'required',
						'challenge_name'		=> 'required',
						'start_date'			=> 'required',
						'end_date'			    => 'required',
						'no_of_questions'		=> 'required',
						'description'			=> 'required',
						'term_condition'		=> 'required',
						'instruction'			=> 'required',
						'assign_dancer'			=> 'required',
						'assign_dancer'			=> 'required',
						'category'				=> 'required|array',
						'how_many_winners'	    => 'required|integer|min:1',
						'laederboards'			=> 'required|integer|min:1',
						'minimum_age' 			=> "required_if:assign_dancer,0|integer|min:1",
						'maximum_age' 			=> "required_if:assign_dancer,0|integer|greater_than_field:minimum_age",
						'image' 				=> 'mimes:'.IMAGE_EXTENSION,
						
					),
					array(
						'term_condition.required'		=>	"The Terms and Conditions field is required.",
						'minimum_age.required_if'		=>	"The minimum age field is required.",
						'maximum_age.required_if'		=>	"The maximum age field is required.",
						'laederboards.required'			=>	"The leaderboards field is required.",
						'how_many_winners.required'			=>	"The winners field is required.",
						'maximum_age.greater_than_field'	=>	"The maximum age must be greater than minimum age.",
						
					)
				);
			}else{
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'sponsor_name'			=> 'required',
						'grade_level'			=> 'required',
						'challenge_name'		=> 'required',
						'start_date'			=> 'required',
						'end_date'			    => 'required',
						'no_of_questions'		=> 'required',
						'description'			=> 'required',
						'term_condition'		=> 'required',
						'instruction'			=> 'required',
						'assign_dancer'			=> 'required',
						'assign_dancer'			=> 'required',
						'category'				=> 'required|array',
						'how_many_winners'	    => 'required|integer|min:1',
						'laederboards'			=> 'required|integer|min:1',
						'image' 				=> 'mimes:'.IMAGE_EXTENSION,
						
					),
					array(
						'term_condition.required'		=>	"The Terms and Conditions field is required.",
						'minimum_age.required_if'		=>	"The minimum age field is required.",
						'maximum_age.required_if'		=>	"The maximum age field is required.",
						'laederboards.required'			=>	"The leaderboards field is required.",
						'how_many_winners.required'			=>	"The winners field is required.",
						'maximum_age.greater_than_field'	=>	"The maximum age must be greater than minimum age.",
						
					)
				);
			}
		if ($validator->fails()){	
			$errors 				=	$validator->messages();
			$response							=	array(
				'success' 						=> 	0,
				'errors' 						=> 	$errors
			);
			return Response::json($response); 
			die;
		}else{
			## Update user's information in users table ##
			$obj	 					=  	Challenge::find($id);
			$obj->sponsor_name 			=  Input::get('sponsor_name');
			$obj->challenge_name		=  !empty(Input::get('challenge_name'))?Input::get('challenge_name'):'';
			$obj->start_date			=  !empty(Input::get('start_date'))?Input::get('start_date'):'';
			$obj->end_date				=  !empty(Input::get('end_date'))?Input::get('end_date'):'';
			$obj->no_of_questions		=  !empty(Input::get('no_of_questions'))?Input::get('no_of_questions'):'';
			$obj->category				=  !empty(Input::get('category'))?implode(",",Input::get('category')):'';
			$obj->grade_level			=  !empty(Input::get('grade_level'))?Input::get('grade_level'):'';
			$obj->assign_dancer			=  !empty(Input::get('assign_dancer'))?Input::get('assign_dancer'):'';
			if($obj->assign_dancer == 0){
				$obj->minimum_age			=  !empty(Input::get('minimum_age'))?Input::get('minimum_age'):'';
				$obj->maximum_age			=  !empty(Input::get('maximum_age'))?Input::get('maximum_age'):'';
			}else{
				$obj->minimum_age			=  '';
				$obj->maximum_age			=  '';
			}
			
			$obj->how_many_winners			=  !empty(Input::get('how_many_winners'))?Input::get('how_many_winners'):'';
			$obj->laederboards				=  !empty(Input::get('laederboards'))?Input::get('laederboards'):'';
			$obj->description				=  !empty(Input::get('description'))?Input::get('description'):'';
			$obj->term_condition			=  !empty(Input::get('term_condition'))?Input::get('term_condition'):'';
			$obj->instruction				=  !empty(Input::get('instruction'))?Input::get('instruction'):'';
			
			if(input::hasFile('image')){
				$challenge_image				=	!empty(Input::file('image'))?Input::file('image'):'';
				$extension 						=	$challenge_image->getClientOriginalExtension();
				$image_name			 			=	$challenge_image->getClientOriginalName();
				$fileName						=	time().'-challenge-document.'.$extension;
				$newFolder     					= 	strtoupper(date('M'). date('Y'))."/";
				$folderPath						=	CHALLENGE_IMAGE_ROOT_PATH.$newFolder;
				if(!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777,true);
				}
				if($challenge_image->move($folderPath, $fileName)){
					$obj->image				=	$newFolder."/".$fileName;
					$obj->image_name			=	pathinfo($image_name, PATHINFO_FILENAME);
				}
			}
			$obj->save();
			$challengeId					=	$obj->id;	
			
				if(isset($formData['prize']) && !empty($formData['prize'])){
							$i	=	0;
					foreach ($formData['prize'] as $challengePrize) {
						if(!empty($challengePrize['prize_id'])) {
							$model 							=  	ChallengePrize::find($challengePrize['prize_id']);
						}else {
							$model 							=  	new ChallengePrize;
						}
						$model->challenge_id 				= $challengeId;
						$model->prize_name   				= $challengePrize['prize_name'];
						$model->prize_description    		= $challengePrize['prize_description'];
						$prizeImage							= !empty($challengePrize["image"])?$challengePrize["image"]:"";
						if(!empty($prizeImage)){
							$extension 				=	 $prizeImage->getClientOriginalExtension();
							$fileName				=	time().$i.'-prize-image.'.$extension;
							
							$newFolder     	= 	strtoupper(date('M'). date('Y'))."/";
							$folderPath		=	PRIZE_IMAGE_ROOT_PATH.$newFolder;
							if(!File::exists($folderPath)) {
								File::makeDirectory($folderPath, $mode = 0777,true);
							}
							if($prizeImage->move($folderPath, $fileName)){
								$model->image	=	$newFolder.$fileName;
							}
							$i++; 
						}
						$model->save(); 
					}
				}	
		
				
			$errors 				=	$validator->messages();
			$response							=	array(
				'success' 						=> 	1,
				'errors' 						=> 	$errors
			);
			return Response::json($response); 
			die;
		}
	}// end updateUser()

	
/**
* Function for mark a user as deleted 
*
* @param $userId as id of user
*
* @return redirect page. 
*/
	public function deleteChallenge($challengeId = 0){
		$challengeDetails	=	Challenge::find($challengeId); 
		if(empty($challengeDetails)) {
			return Redirect::back();
		}
		if($challengeId){		
			$userModel					=	Challenge::where('id',$challengeId)->delete();
			Session::flash('flash_notice',trans("Challenge removed successfully")); 
		}
		return Redirect::back();
	}
/**
* Function for mark a deleteChallengePrize as deleted 
*
* @param $userId as id of deleteChallengePrize
*
* @return redirect page. 
*/
	public function deleteChallengePrize(){
		$prizeId = input::get('id');
		$challengeDetails	=	ChallengePrize::find($prizeId); 
		if(empty($challengeDetails)) {
			$response							=	array(
				'success' 						=> 	2,
				'message' 						=> 	trans("Challenge prize not removed.")
			);
			return Response::json($response); 
			die;
		}
		if($prizeId){		
			ChallengePrize::where('id',$prizeId)->delete();
			Session::flash('flash_notice',trans("Challenge prize removed successfully")); 
		}
		$response							=	array(
			'success' 						=> 	1,
			'message' 						=> 	trans("Challenge removed successfully.")
		);
		return Response::json($response); 
		die;
	} // end deleteChallengePrize()
/**
* Function for update user status
*
* @param $userId as id of user
* @param $userStatus as status of user
*
* @return redirect page. 
*/
	public function updateChallengeStatus($Id = 0, $status = 0){
		if($status == 0	){
			$statusMessage	=	trans("Challenges deactivated successfully");
		}else{
			$statusMessage	=	trans("Challenges activated successfully");
		}
		$this->_update_all_status('challenges',$Id,$status);
		
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::route($this->model.'.index');
	} // end updateUserStatus()

	
}//end UsersController
