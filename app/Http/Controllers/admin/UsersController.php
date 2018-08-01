<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\User;
use App\Model\ParentChild;
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
 
class UsersController extends BaseController {
	
	public $model	=	'User';
	
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
	public function listUsers(){
		$DB 					= 	AdminUser::query();
		$searchVariable			=	array(); 
		$inputGet				=	Input::get(); 
		/* seacrching on the basis of username and email */ 
		if ((Input::get())) {
			///print_r($inputGet);die;
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
									->where('users.id','<>',ADMIN_ID)
									->where('is_deleted',0)
									->select('users.*')
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
	public function addUser(){
		$old_country 		= 	Input::old('country');
		if(empty($old_country)){
			$countryCode	=	Input::old('country');
		}
		$countryList		=	DB::table('countries')->where('status',1)->orderBy('name','ASC')->pluck('name','id');
		return  View::make('admin.'.$this->model.'.add',compact('countryList'));
	}//end addCompany()
	
/**
* Function for getUserAddData
*
* @param null
*
* @return view page. 
*/	
	public function getUserAddData(){
		$user_type 		= 	Input::get('user_type');
		$countryList		=	DB::table('countries')->where('status',1)->orderBy('name','ASC')->pluck('name','id');
		if(empty($user_type)){
			return Redirect::back();
		}else if($user_type==DANCER_ROLE_ID){
			return  View::make('admin.'.$this->model.'.add_user_dancer',compact('countryList'));
		}else if($user_type==PARENT_ROLE_ID){
			return  View::make('admin.'.$this->model.'.add_user_parent',compact('countryList'));
		}else if($user_type==STUDIO_ROLE_ID){
			return  View::make('admin.'.$this->model.'.add_user_teacher',compact('countryList'));
		}else if($user_type==FAN_ROLE_ID){
			return  View::make('admin.'.$this->model.'.add_user_fan',compact('countryList'));
		}
		
	}//end getUserAddData()
	
/**
* Function for getUserEditData
*
* @param null
*
* @return view page. 
*/	
	public function getUserEditData(){
		$user_type 		= 	Input::get('user_type');
		$userId 		= 	Input::get('user_id');
		if($userId){
			$userDetails			=	AdminUser::find($userId); 
			if(empty($userDetails)) {
				return Redirect::route($this->model.'.index');
			}
			if($userDetails){
				$userParentDetails	=	DB::table('parent_childs')->where('is_active',0)->where('is_deleted',0)->where('parent_id',$userId)->get();
			}else{
				$userParentDetails	=	array();
			}
			$countryList		=	DB::table('countries')->where('status',1)->orderBy('name','ASC')->pluck('name','id');
			if($userDetails->country){
				$stateList			=	DB::table('states')->where('country_id',$userDetails->country)->where('country_id','!=',0)->orderBy('name','ASC')->pluck('name','id');
			}else{
				$stateList			=	[];
			}
			if($userDetails->league_country){
				$leagueStateList			=	DB::table('states')->where('country_id',$userDetails->league_country)->where('country_id','!=',0)->orderBy('name','ASC')->pluck('name','id');
			}else{
				$leagueStateList			=	[];
			}
			if($userDetails->studio_country){
				$studioStateList			=	DB::table('states')->where('country_id',$userDetails->studio_country)->where('country_id','!=',0)->orderBy('name','ASC')->pluck('name','id');
			}else{
				$studioStateList			=	[];
			}
			if($userDetails->state){
				$cityList			=	DB::table('cities')->where('state_id',$userDetails->state)->where('state_id','!=',0)->orderBy('name','ASC')->pluck('name','id');
			}else{
				$cityList			=	[];
			}
			if($userDetails->league_state){
				$leagueCityList			=	DB::table('cities')->where('state_id',$userDetails->league_state)->where('state_id','!=',0)->orderBy('name','ASC')->pluck('name','id');
			}else{
				$leagueCityList			=	[];
			}
			if($userDetails->studio_state){
				$studioCityList			=	DB::table('cities')->where('state_id',$userDetails->studio_state)->where('state_id','!=',0)->orderBy('name','ASC')->pluck('name','id');
			}else{
				$studioCityList			=	[];
			}
		}
		if(empty($user_type)){
			return Redirect::back();
		}else if($user_type==DANCER_ROLE_ID){
			return  View::make('admin.'.$this->model.'.edit_user_dancer',compact('userDetails','countryList','stateList','leagueStateList','studioStateList','cityList','leagueCityList','studioCityList'));
		}else if($user_type==PARENT_ROLE_ID){
			return  View::make('admin.'.$this->model.'.edit_user_parent',compact('userDetails','userParentDetails','countryList','stateList','leagueStateList','studioStateList','cityList','leagueCityList','studioCityList'));
		}else if($user_type==STUDIO_ROLE_ID){
			return  View::make('admin.'.$this->model.'.edit_user_teacher',compact('userDetails','countryList','stateList','leagueStateList','studioStateList','cityList','leagueCityList','studioCityList'));
		}else if($user_type==FAN_ROLE_ID){
			return  View::make('admin.'.$this->model.'.edit_user_fan',compact('userDetails','countryList','stateList','leagueStateList','studioStateList','cityList','leagueCityList','studioCityList'));
		}
		
	}//end getUserEditData()
/**
* Function for save added users
*
* @param null
*
* @return view page. 
*/	
	public function saveUser(){
		Input::replace($this->arrayStripTags(Input::all()));
		$formData						=	Input::all();
		if(!empty($formData)){
			if(Input::get('user_type')==DANCER_ROLE_ID){
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'user_type'				=> 'required',
						'first_name'			=> 'required',
						'last_name'			    => 'required',
						'email' 				=> 'required|email|unique:users',
						'username' 				=> 'required|unique:users',
						'password'				=> 'required|min:8',
						'confirm_password'  	=> 'required|min:8|same:password', 
						//'phone_number' 			=> 'required',
						//'phone_number' 			=> 'required|min:10|max:10|numeric',
						'country' 			    => 'required',
						'state' 			    => 'required',
						'city' 			    	=> 'required',
						'gender' 			    => 'required',
						'date' 			    	=> 'required',
						'image' 				=> 'mimes:'.IMAGE_EXTENSION,
						'league_name' 			=> "required_if:attend_dance_team,Yes",
						'league_country' 		=> "required_if:attend_dance_team,Yes",
						'league_state' 			=> "required_if:attend_dance_team,Yes",
						'league_city' 			=> "required_if:attend_dance_team,Yes",
						'studio_name' 			=> "required_if:attend_dance_studio,Yes",
						'studio_country' 		=> "required_if:attend_dance_studio,Yes",
						'studio_state' 			=> "required_if:attend_dance_studio,Yes",
						'studio_city' 			=> "required_if:attend_dance_studio,Yes",
						
					),
					array(
						'date.required'					=>	"The date of birth field is required.",
						'league_name.required_if'		=>	"The league name field is required.",
						'league_country.required_if'	=>	"The country field is required.",
						'league_state.required_if'		=>	"The state field is required.",
						'league_city.required_if'		=>	"The city field is required.",
						'studio_name.required_if'		=>	"The studio name field is required.",
						'studio_country.required_if'	=>	"The country field is required.",
						'studio_state.required_if'		=>	"The state field is required.",
						'studio_city.required_if'		=>	"The city field is required.",
					)
				);
			}else if(Input::get('user_type')==PARENT_ROLE_ID){
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'user_type'				=> 'required',
						'first_name'			=> 'required',
						'last_name'			    => 'required',
						'email' 				=> 'required|email|unique:users',
						'username' 				=> 'required|unique:users',
						'relationship' 			=> 'required',
						'password'				=> 'required|min:8',
						'confirm_password'  	=> 'required|min:8|same:password', 
						//'phone_number' 			=> 'required',
						//'phone_number' 			=> 'required|min:10|max:10|numeric',
						'country' 			    => 'required',
						'state' 			    => 'required',
						'city' 			    	=> 'required',
						'gender' 			    => 'required',
						'date' 			    	=> 'required',
						'image' 				=> 'mimes:'.IMAGE_EXTENSION,
						
					),
					array(
						'date.required'					=>	"The date of birth field is required."
						
					)
				);
			}else if(Input::get('user_type')==STUDIO_ROLE_ID){
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'user_type'							=> 'required',
						'full_name'							=> 'required',
						'email' 							=> 'required|email|unique:users',
						'phone_number' 						=> 'required|numeric|digits:10,10',
						'username' 			   				=> 'required',
						'date' 			    				=> 'required',
						'password'							=> 'required|min:8',
						'confirm_password'  				=> 'required|min:8|same:password', 
						'country' 			   				=> 'required',
						'state' 			   				=> 'required',
						'city' 			    				=> 'required',
						'address' 			   			 	=> 'required',
						'website_address' 					=> 'required',
						'zip_code' 			    			=> 'required',
						'how_many_dancers_train_monthly' 	=> 'required|numeric',
						'image' 							=> 'mimes:'.IMAGE_EXTENSION,
						
					),
					array(
						'date.required'					=>	"The date of birth field is required."
						
					)
				);
			}else if(Input::get('user_type')==FAN_ROLE_ID){
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'user_type'				=> 'required',
						'first_name'			=> 'required',
						'last_name'				=> 'required',
						'email' 				=> 'required|email|unique:users',
						'date' 			    	=> 'required',
						'password'				=> 'required|min:8',
						'confirm_password'  	=> 'required|min:8|same:password', 
						'username' 			    => 'required',
						'country' 			    => 'required',
						'state' 			    => 'required',
						'city' 			    	=> 'required',
						'gender' 			    => 'required',
						'image' 				=> 'mimes:'.IMAGE_EXTENSION,
						
					),
					array(
						'date.required'					=>	"The date of birth field is required."
						
					)
				);
			}else{
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'user_type'				=> 'required',
						'first_name'			=> 'required',
						'last_name'			    => 'required',
						'email' 				=> 'required|email|unique:users',
						'username' 				=> 'required|unique:users',
						'password'				=> 'required|min:8',
						'confirm_password'  	=> 'required|min:8|same:password', 
						//'phone_number' 			=> 'required',
						//'phone_number' 			=> 'required|min:10|max:10|numeric',
						'country' 			    => 'required',
						'state' 			    => 'required',
						'city' 			    => 'required',
						'gender' 			    => 'required',
						'date' 			    	=> 'required',
						'image' 				=> 'mimes:'.IMAGE_EXTENSION,
						'league_name' 			=> "required_if:attend_dance_team,Yes",
						'league_country' 		=> "required_if:attend_dance_team,Yes",
						'league_state' 			=> "required_if:attend_dance_team,Yes",
						'league_city' 			=> "required_if:attend_dance_team,Yes",
						'studio_name' 			=> "required_if:attend_dance_studio,Yes",
						'studio_country' 		=> "required_if:attend_dance_studio,Yes",
						'studio_state' 			=> "required_if:attend_dance_studio,Yes",
						'studio_city' 			=> "required_if:attend_dance_studio,Yes",
						
					),
					array(
						'date.required'					=>	"The date of birth field is required.",
						'league_name.required_if'		=>	"The league name field is required.",
						'league_country.required_if'	=>	"The country field is required.",
						'league_state.required_if'		=>	"The state field is required.",
						'league_city.required_if'		=>	"The city field is required.",
						'studio_name.required_if'		=>	"The studio name field is required.",
						'studio_country.required_if'	=>	"The country field is required.",
						'studio_state.required_if'		=>	"The state field is required.",
						'studio_city.required_if'		=>	"The city field is required.",
					)
				);
				
			}
			if(!empty(Input::get('password'))){
				$password 					= 	Input::get('password');
			}
			if(!empty(Input::get('password'))){
				if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password) && preg_match('#[\W]#', $password)) {
					$correctPassword		=	Hash::make($password);
				}else{
					$errors 				=	$validator->messages();
					$errors->add('password', trans("messages.user_management.password_help_message"));
					$response							=	array(
						'success' 						=> 	0,
						'errors' 						=> 	$errors
					);
					return Response::json($response); 
					die;
				}
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
				$userRoleId				=  FRONT_USER ;
				$fullName				=  !empty(Input::get('first_name')) ? (Input::get('first_name')).' '.(Input::get('last_name')) : Input::get('full_name');
				$obj 					=  new User;
				$validateString			=  md5(time() . Input::get('email'));
				$obj->validate_string	=  $validateString;				
				$obj->full_name 		=  $fullName;
				$obj->email 			=  Input::get('email');
				$obj->username 			=  !empty(Input::get('username'))?Input::get('username'):'';
				$obj->slug	 			=  $this->getSlug($fullName,'full_name','User');
				$obj->password	 		=  !empty(Input::get('username'))?Hash::make(Input::get('password')):'';
				$obj->user_role_id		=  (Input::get('user_type'));
				
				$obj->address			=  !empty(Input::get('address'))?Input::get('address'):'';
				$obj->country			=  !empty(Input::get('country'))?Input::get('country'):'';
				$obj->state				=  !empty(Input::get('state'))?Input::get('state'):'';
				$obj->city				=  !empty(Input::get('city'))?Input::get('city'):'';
				$obj->gender			=  !empty(Input::get('gender'))?Input::get('gender'):'';
				$obj->date_of_birth				=  !empty(Input::get('date'))?Input::get('date'):'';
				$obj->first_name		=  !empty(Input::get('first_name'))?Input::get('first_name'):'';
				$obj->last_name			=  !empty(Input::get('last_name'))?Input::get('last_name'):'';
				//$obj->latitude			=  Input::get('latitude');
				//$obj->longitude			=  Input::get('longitude');
				$obj->phone_number		=  !empty(Input::get('phone_number'))?Input::get('phone_number'):'';
				$obj->attend_dance_team		=  !empty(Input::get('attend_dance_team'))?Input::get('attend_dance_team'):'';
				if($obj->attend_dance_team == 1){
					$obj->league_name			=  !empty(Input::get('league_name'))?Input::get('league_name'):'';
					$obj->league_country		=  !empty(Input::get('league_country'))?Input::get('league_country'):'';
					$obj->league_state			=  !empty(Input::get('league_state'))?Input::get('league_state'):'';
					$obj->league_city			=  !empty(Input::get('league_city'))?Input::get('league_city'):'';
				}
				
				$obj->attend_dance_studio	=  !empty(Input::get('attend_dance_studio'))?Input::get('attend_dance_studio'):'';
				if($obj->attend_dance_studio == 1){
					$obj->studio_name			=  !empty(Input::get('studio_name'))?Input::get('studio_name'):'';
					$obj->studio_country		=  !empty(Input::get('studio_country'))?Input::get('studio_country'):'';
					$obj->studio_state			=  !empty(Input::get('studio_state'))?Input::get('studio_state'):'';
					$obj->studio_city			=  !empty(Input::get('studio_city'))?Input::get('studio_city'):'';
				}
				$obj->relationship			=  !empty(Input::get('relationship'))?Input::get('relationship'):'';
				$obj->website_address		=  !empty(Input::get('website_address'))?Input::get('website_address'):'';
				$obj->zip_code				=  !empty(Input::get('zip_code'))?Input::get('zip_code'):'';
				$obj->how_many_dancers_train_monthly	=  !empty(Input::get('how_many_dancers_train_monthly'))?Input::get('how_many_dancers_train_monthly'):'';
				$obj->is_verified		=  1; 
				$obj->is_active			=  1; 
				if(input::hasFile('profile_image')){
					$extension 			=	 Input::file('profile_image')->getClientOriginalExtension();
					$fileName			=	time().'-user-image.'.$extension;
					if(Input::file('profile_image')->move(USER_PROFILE_IMAGE_ROOT_PATH, $fileName)){
						$obj->image		=	$fileName;
					}
				}
				$obj->save();
				$userId					=	$obj->id;	
				if(isset($formData['dancer']) && !empty($formData['dancer'])){
					foreach ($formData['dancer'] as $dancerResult) { 
						if(!empty($dancerResult['first_name'])){
							
							$modelDancer            			= new ParentChild();
							$modelDancer->parent_id 			= $userId;
							$modelDancer->first_name   			= $dancerResult['first_name'];
							$modelDancer->last_name    			= $dancerResult['last_name'];
							$modelDancer->email   				= $dancerResult['email'];
							$modelDancer->gender   				= $dancerResult['gender'];
							$modelDancer->country   			= $dancerResult['country'];
							$modelDancer->state   				= $dancerResult['state'];
							$modelDancer->city  				= $dancerResult['city'];
							$modelDancer->date   				= $dancerResult['date'];
							$modelDancer->send_notification  	= $dancerResult['send_notification'];
							if(!empty($modelDancer->email) && $modelDancer->send_notification=='Yes'){
								//mail email and password to new registered user
								$settingsEmail 			=	Config::get('Site.email');
								$full_name				= 	($modelDancer->first_name.' '.$modelDancer->last_name); 
								$email					= 	$modelDancer->email;
								$route_url     			= 	route('Home.login');
								$click_link   			=   $route_url;
								$emailActions			= 	EmailAction::where('action','=','user_child_notification')->get()->toArray();
								$emailTemplates			= 	EmailTemplate::where('action','=','user_child_notification')->get(array('name','subject','action','body'))->toArray();
								$cons 					= 	explode(',',$emailActions[0]['options']);
								$constants 				= 	array();
								foreach($cons as $key => $val){
									$constants[] 		= 	'{'.$val.'}';
								} 
								$subject 				= 	$emailTemplates[0]['subject'];
								$rep_Array 				= 	array($full_name,$click_link,$route_url); 
								$messageBody			= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
								$mail					= 	$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);	
							}
							$modelDancer->save(); 
						}
					}
				}	
				if(!$userId) {
					DB::rollback();
					Session::flash('error', trans("Something went wrong.")); 
					return Redirect::back()->withInput();
				}				
				$encId					=	md5(time() . Input::get('email'));
				//mail email and password to new registered user
				$settingsEmail 			=	Config::get('Site.email');
				$full_name				= 	$obj->full_name; 
				$email					= 	$obj->email;
				$password				= 	Input::get('password');
				$route_url     			= 	route('Home.login');
				$click_link   			=   $route_url;
				$emailActions			= 	EmailAction::where('action','=','user_registration')->get()->toArray();
				$emailTemplates			= 	EmailTemplate::where('action','=','user_registration')->get(array('name','subject','action','body'))->toArray();
				$cons 					= 	explode(',',$emailActions[0]['options']);
				$constants 				= 	array();
				foreach($cons as $key => $val){
					$constants[] 		= 	'{'.$val.'}';
				} 
				$subject 				= 	$emailTemplates[0]['subject'];
				$rep_Array 				= 	array($full_name,$email,$password,$click_link,$route_url); 
				$messageBody			= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				$mail					= 	$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);	
				
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
	public function viewUser($userId = 0){
		$userDetails	=	DB::table('users')
							->where('users.id','=',$userId)
							->first(); 
		if(empty($userDetails)) {
			return Redirect::route($this->model.'.index');
		}
		if($userDetails->user_role_id==PARENT_ROLE_ID){
			$dancerDetails 	=	DB::table('parent_childs')
									->where('parent_id','=',$userId)
									->where('is_active',0)
									->select('parent_childs.*',
										DB::raw('(SELECT name FROM countries where countries.id=parent_childs.country AND status = 1) as country_name'),
										DB::raw('(SELECT name FROM states where states.id=parent_childs.state AND status = 1) as state_name'),
										DB::raw('(SELECT name FROM cities where cities.id=parent_childs.city AND status = 1) as city_name'))
									->get(); 
		}
		#### Getting country name ###
		$countryName	=	DB::table('countries')
							->where('id','=',$userDetails->country)
							->where('status',1)
							->value('name'); 
		if(!empty($userDetails->league_country)){					
			$leagueCountryName	=	DB::table('countries')
								->where('id','=',$userDetails->league_country)
								->where('status',1)
								->value('name'); 
		}
		if(!empty($userDetails->studio_country)){					
			$studioCountryName	=	DB::table('countries')
								->where('id','=',$userDetails->studio_country)
								->where('status',1)
								->value('name'); 
		}
		$stateName		=	DB::table('states')
							->where('id','=',$userDetails->state)
							->where('status',1)
							->value('name'); 
		if(!empty($userDetails->studio_state)){					
			$studioStateName	=	DB::table('states')
								->where('id','=',$userDetails->studio_state)
								->where('status',1)
								->value('name'); 
		}
		if(!empty($userDetails->league_state)){					
			$leagueStateName	=	DB::table('states')
								->where('id','=',$userDetails->league_state)
								->where('status',1)
								->value('name'); 
		}
		$cityName		=	DB::table('cities')
							->where('id','=',$userDetails->city)
							->where('status',1)
							->value('name'); 
		if(!empty($userDetails->league_city)){					
			$leagueCityName	=	DB::table('cities')
								->where('id','=',$userDetails->league_city)
								->where('status',1)
								->value('name'); 
		}
		if(!empty($userDetails->studio_city)){					
			$studioCityName	=	DB::table('cities')
								->where('id','=',$userDetails->studio_city)
								->where('status',1)
								->value('name'); 
		}
		return View::make('admin.'.$this->model.'.view', compact('userDetails','countryName','leagueCountryName','studioCountryName','stateName','leagueStateName','studioStateName','cityName','leagueCityName','studioCityName','dancerDetails'));
	} // end viewUser()
/**
* Function for display page for edit user
*
* @param $userId as id of user
*
* @return view page. 
*/
	public function editUser($userId = 0){
		$userDetails			=	AdminUser::find($userId); 
		//echo "<pre>";print_r($userDetails);die;
		if(empty($userDetails)) {
			return Redirect::route($this->model.'.index');
		}
		if($userId){
			$userDetails		=	AdminUser::find($userId);
			if($userDetails){
				$userParentDetails	=	DB::table('parent_childs')->where('is_active',0)->where('is_deleted',0)->where('parent_id',$userDetails->id)->get();
			}else{
				$userParentDetails	=	array();
			}
			$countryList		=	DB::table('countries')->where('status',1)->orderBy('name','ASC')->pluck('name','id');
			if($userDetails->country){
				$stateList			=	DB::table('states')->where('country_id',$userDetails->country)->where('country_id','!=',0)->orderBy('name','ASC')->pluck('name','id');
			}else{
				$stateList			=	[];
			}
			if($userDetails->league_country){
				$leagueStateList			=	DB::table('states')->where('country_id',$userDetails->league_country)->where('country_id','!=',0)->orderBy('name','ASC')->pluck('name','id');
			}else{
				$leagueStateList			=	[];
			}
			if($userDetails->studio_country){
				$studioStateList			=	DB::table('states')->where('country_id',$userDetails->studio_country)->where('country_id','!=',0)->orderBy('name','ASC')->pluck('name','id');
			}else{
				$studioStateList			=	[];
			}
			if($userDetails->state){
				$cityList			=	DB::table('cities')->where('state_id',$userDetails->state)->where('state_id','!=',0)->orderBy('name','ASC')->pluck('name','id');
			}else{
				$cityList			=	[];
			}
			if($userDetails->league_state){
				$leagueCityList			=	DB::table('cities')->where('state_id',$userDetails->league_state)->where('state_id','!=',0)->orderBy('name','ASC')->pluck('name','id');
			}else{
				$leagueCityList			=	[];
			}
			if($userDetails->studio_state){
				$studioCityList			=	DB::table('cities')->where('state_id',$userDetails->studio_state)->where('state_id','!=',0)->orderBy('name','ASC')->pluck('name','id');
			}else{
				$studioCityList			=	[];
			}
			//print_r($leagueStateList);die;
			return View::make('admin.'.$this->model.'.edit', compact('userDetails','userParentDetails','countryList','stateList','leagueStateList','studioStateList','cityList','leagueCityList','studioCityList'));
		}
	} // end editUser()
/**
* Function for update user detail
*
* @param $userId as id of user
*
* @return redirect page. 
*/
	public function updateUser(){	
		Input::replace($this->arrayStripTags(Input::all()));
		$thisData						=	Input::all(); 
		//echo "<pre>";print_r($thisData);die;
		$userId							=	Input::get('id');
		if(Input::get('user_type')==DANCER_ROLE_ID){
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'user_type'				=> 'required',
						'first_name'			=> 'required',
						'last_name'			    => 'required',
						'email' 				=> 'required|email',
						'username' 				=> 'required',
						'password'				=> 'min:8',
						'confirm_password'  	=> 'min:8|same:password', 
						//'phone_number' 			=> 'required',
						//'phone_number' 			=> 'required|min:10|max:10|numeric',
						'country' 			    => 'required',
						'state' 			    => 'required',
						'city' 			    => 'required',
						'gender' 			    => 'required',
						'date' 			    	=> 'required',
						'image' 				=> 'mimes:'.IMAGE_EXTENSION,
						'league_name' 			=> "required_if:attend_dance_team,Yes",
						'league_country' 		=> "required_if:attend_dance_team,Yes",
						'league_state' 			=> "required_if:attend_dance_team,Yes",
						'league_city' 			=> "required_if:attend_dance_team,Yes",
						'studio_name' 			=> "required_if:attend_dance_studio,Yes",
						'studio_country' 		=> "required_if:attend_dance_studio,Yes",
						'studio_state' 			=> "required_if:attend_dance_studio,Yes",
						'studio_city' 			=> "required_if:attend_dance_studio,Yes",
						
					),
					array(
						'date.required'					=>	"The date of birth field is required.",
						'league_name.required_if'		=>	"The league name field is required.",
						'league_country.required_if'	=>	"The country field is required.",
						'league_state.required_if'		=>	"The state field is required.",
						'league_city.required_if'		=>	"The city field is required.",
						'studio_name.required_if'		=>	"The studio name field is required.",
						'studio_country.required_if'	=>	"The country field is required.",
						'studio_state.required_if'		=>	"The state field is required.",
						'studio_city.required_if'		=>	"The city field is required.",
					)
				);
			}else if(Input::get('user_type')==PARENT_ROLE_ID){
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'user_type'				=> 'required',
						'first_name'			=> 'required',
						'last_name'			    => 'required',
						'email' 				=> 'required|email',
						'username' 				=> 'required',
						'relationship' 			=> 'required',
						'password'				=> 'min:8',
						'confirm_password'  	=> 'min:8|same:password', 
						//'phone_number' 			=> 'required',
						//'phone_number' 			=> 'required|min:10|max:10|numeric',
						'country' 			    => 'required',
						'state' 			    => 'required',
						'city' 			    => 'required',
						'gender' 			    => 'required',
						'date' 			    	=> 'required',
						'image' 				=> 'mimes:'.IMAGE_EXTENSION,
						
					),
					array(
						'date.required'					=>	"The date of birth field is required."
					)
				);
			}else if(Input::get('user_type')==STUDIO_ROLE_ID){
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'user_type'				=> 'required',
						'full_name'				=> 'required',
						'email' 				=> 'required|email',
						'phone_number' 			=> 'required|numeric',
						'username' 			    => 'required',
						'password'				=> 'min:8',
						'confirm_password'  	=> 'min:8|same:password',
						'country' 			    => 'required',
						'state' 			    => 'required',
						'city' 			    	=> 'required',
						'address' 			    => 'required',
						'website_address' 		=> 'required',
						'zip_code' 			    => 'required',
						'how_many_dancers_train_monthly' 	=> 'required|numeric',
						'image' 				=> 'mimes:'.IMAGE_EXTENSION,
						
					),
					array(
						'date.required'					=>	"The date of birth field is required."
					)
				);
			}else if(Input::get('user_type')==FAN_ROLE_ID){
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'user_type'				=> 'required',
						'first_name'			=> 'required',
						'last_name'				=> 'required',
						'email' 				=> 'required|email',
						'username' 			    => 'required',
						'country' 			    => 'required',
						'state' 			    => 'required',
						'city' 			    	=> 'required',
						'gender' 			    => 'required',
						'date' 			    	=> 'required',
						'image' 				=> 'mimes:'.IMAGE_EXTENSION,
						
					),
					array(
						'date.required'					=>	"The date of birth field is required."
					)
				);
			}
			if(!empty(Input::get('password'))){
				$password 					= 	Input::get('password');
			}
			if(!empty(Input::get('password'))){
				if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password) && preg_match('#[\W]#', $password)) {
					$correctPassword		=	Hash::make($password);
				}else{
					$errors 				=	$validator->messages();
					$errors->add('password', trans("messages.user_management.password_help_message"));
					$response							=	array(
						'success' 						=> 	0,
						'errors' 						=> 	$errors
					);
					return Response::json($response); 
					die;
				}
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
			$obj	 					=  	User::find($userId);
			$fullName					=	Input::get('first_name').' '.Input::get('last_name');
			$obj->full_name 			=   !empty(Input::get('first_name'))?ucwords($fullName):Input::get('full_name');
			$obj->email 				=  	Input::get('email');
			$get_password				=	!empty(Input::get('password'))?Input::get('password'):'';
			$get_confirm_password		=	Input::get('password_confirmation');
			if(!empty($get_password) && !empty($get_confirm_password) && $get_password == $get_confirm_password){
				$obj->password	 		=  Hash::make(Input::get('password'));
			} 
			$obj->username			=  (Input::get('username'));
			$obj->user_role_id		=  (Input::get('user_type'));
			$obj->address			=  Input::get('address');
			$obj->country			=  Input::get('country');
			$obj->state				=  Input::get('state');
			$obj->city				=  Input::get('city');
			$obj->gender			=  Input::get('gender');
			$obj->date_of_birth		=  Input::get('date');
			$obj->first_name		=  Input::get('first_name');
			$obj->last_name			=  Input::get('last_name');
			$obj->phone_number		=  !empty(Input::get('phone_number'))?Input::get('phone_number'):'';
			$obj->attend_dance_team		=  !empty(Input::get('attend_dance_team'))?Input::get('attend_dance_team'):'';
			$obj->league_name			=  !empty(Input::get('league_name'))?Input::get('league_name'):'';
			$obj->league_country		=  !empty(Input::get('league_country'))?Input::get('league_country'):'';
			$obj->league_state			=  !empty(Input::get('league_state'))?Input::get('league_state'):'';
			$obj->league_city			=  !empty(Input::get('league_city'))?Input::get('league_city'):'';
			$obj->attend_dance_studio	=  !empty(Input::get('attend_dance_studio'))?Input::get('attend_dance_studio'):'';
			$obj->studio_name			=  !empty(Input::get('studio_name'))?Input::get('studio_name'):'';
			$obj->studio_country		=  !empty(Input::get('studio_country'))?Input::get('studio_country'):'';
			$obj->studio_state			=  !empty(Input::get('studio_state'))?Input::get('studio_state'):'';
			$obj->studio_city			=  !empty(Input::get('studio_city'))?Input::get('studio_city'):'';
			$obj->relationship			=  !empty(Input::get('relationship'))?Input::get('relationship'):'';
			$obj->website_address		=  !empty(Input::get('website_address'))?Input::get('website_address'):'';
			$obj->zip_code				=  !empty(Input::get('zip_code'))?Input::get('zip_code'):'';
			$obj->how_many_dancers_train_monthly	=  !empty(Input::get('how_many_dancers_train_monthly'))?Input::get('how_many_dancers_train_monthly'):'';
					
			if(!empty(Input::hasFile('profile_image'))){
				$extension 				=	 Input::file('profile_image')->getClientOriginalExtension();
				$fileName				=	time().'-user-image.'.$extension;
				if(Input::file('profile_image')->move(USER_PROFILE_IMAGE_ROOT_PATH, $fileName)){
					$obj->image			=	$fileName;
				}
				$image 					=	AdminUser::where('id',$userId)->pluck('image');
				@unlink(USER_PROFILE_IMAGE_ROOT_PATH.$image);
			}
			$obj->save();
			$userId					=	$obj->id;	
			if(isset($thisData['dancer']) && !empty($thisData['dancer'])){
				foreach ($thisData['dancer'] as $dancerResult) { 
					if(!empty($dancerResult['first_name'])){
						if(isset($dancerResult['id'])){
							$parentChildId						=	$dancerResult['id'];
							$modelDancer            			= ParentChild::find($parentChildId);
						}else{
							$modelDancer            			= New ParentChild;
							if(!empty($dancerResult['email']) && $dancerResult['send_notification']=='Yes'){
								//mail email and password to new dancer registered
								$settingsEmail 			=	Config::get('Site.email');
								$full_name				= 	($dancerResult['first_name'].' '.$dancerResult['last_name']); 
								$email					= 	$dancerResult['email'];
								$route_url     			= 	route('Home.login');
								$click_link   			=   $route_url;
								$emailActions			= 	EmailAction::where('action','=','user_child_notification')->get()->toArray();
								$emailTemplates			= 	EmailTemplate::where('action','=','user_child_notification')->get(array('name','subject','action','body'))->toArray();
								$cons 					= 	explode(',',$emailActions[0]['options']);
								$constants 				= 	array();
								foreach($cons as $key => $val){
									$constants[] 		= 	'{'.$val.'}';
								} 
								$subject 				= 	$emailTemplates[0]['subject'];
								$rep_Array 				= 	array($full_name,$click_link,$route_url); 
								$messageBody			= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
								$mail					= 	$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);	
							}
						}
							$modelDancer->parent_id   			= $userId;
							$modelDancer->first_name   			= $dancerResult['first_name'];
							$modelDancer->last_name    			= $dancerResult['last_name'];
							$modelDancer->email   				= $dancerResult['email'];
							$modelDancer->gender   				= $dancerResult['gender'];
							$modelDancer->country   			= $dancerResult['country'];
							$modelDancer->state   				= $dancerResult['state'];
							$modelDancer->city  				= $dancerResult['city'];
							$modelDancer->date   				= $dancerResult['date'];
							$modelDancer->send_notification  	= $dancerResult['send_notification'];
							$modelDancer->save();  
					}
				}
			}	
			if(!$userId) {
				DB::rollback();
				Session::flash('error', trans("Something went wrong.")); 
				return Redirect::back()->withInput();
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
	public function addmoreDancer(){
		$counter 			=	input::get('counter');
		
		$countryList		=	DB::table('countries')->where('status',1)->orderBy('name','ASC')->pluck('name','id');
		return  View::make('admin.'.$this->model.'.add_more_dancer',compact('counter','id',"countryList"));
	} // end deleteUser()
	
	
/**
* Function for mark a user as deleted 
*
* @param $userId as id of user
*
* @return redirect page. 
*/
	public function removeDancer(){
		$id 			=	input::get('id');
		
		if($id){
			ParentChild::where('id',$id)->update(array('is_deleted'=>1));
			$response		=	array('success'	=> 	1);
			return Response::json($response); 
			die;
		}else{
			$response		=	array('success'	=> 	0);
			return Response::json($response); 
			die;
		}
	} // end deleteUser()
	
/**
* Function for mark a user as deleted 
*
* @param $userId as id of user
*
* @return redirect page. 
*/
	public function deleteUser($userId = 0){
		$userDetails	=	AdminUser::find($userId); 
		if(empty($userDetails)) {
			return Redirect::route($this->model.'.index');
		}
		if($userId){	
			$email =	"delete_".$userId."_".$userDetails->email;
			$userModel					=	AdminUser::where('id',$userId)->update(array('email'=>$email,'is_deleted'=>1));
			Session::flash('flash_notice',trans("User removed successfully")); 
		}
		return Redirect::route($this->model.'.index');
	} // end deleteUser()
/**
* Function for update user status
*
* @param $userId as id of user
* @param $userStatus as status of user
*
* @return redirect page. 
*/
	public function updateUserStatus($userId = 0, $userStatus = 0){
		if($userStatus == 0	){
			$statusMessage	=	trans("User deactivated successfully");
		}else{
			$statusMessage	=	trans("User activated successfully");
		}
		$this->_update_all_status('users',$userId,$userStatus);
		/* DB::beginTransaction();
		$user_details					=		AdminUser::where('id', '=', $userId)->update(array('is_active' => $userStatus));	
		$this->_update_all_status('faqs',$Id,$Status);
		if(!$user_details) {
			DB::rollback();
			Session::flash('error', trans("Something went wrong.")); 
			return Redirect::back()->withInput();
		}
		DB::commit(); */
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::route($this->model.'.index');
	} // end updateUserStatus()
/**
* Function for verify user
*
* @param $userId as id of user
*
* @return redirect page. 
*/
	public function verifiedUser($userId = 0){
		DB::beginTransaction();
		$user_details		=		AdminUser::where('id', '=', $userId)->update(array('is_verified' => 1));
		if(!$user_details) {
			DB::rollback();
			Session::flash('error', trans("Something went wrong.")); 
			return Redirect::back()->withInput();
		}
		DB::commit();
		Session::flash('flash_notice', 'User status updated successfully.'); 
		return Redirect::route($this->model.'.index');
	} // end verifiedUser()
	
		
/**
* Function for sendProfileVerify to user
*
* @param $id as id of users
*
* @return redirect page. 
*/
	public function sendProfileVerify($id){
		$obj			=	AdminUser::find($id);
		$settingsEmail 	= 	Config::get('Site.email');
		$full_name		= 	$obj->full_name; 
		$email			= 	$obj->email;
		$obj->is_verified	=	1;
		$obj->save();
		$route_url      =	route('Home.login');
		$click_link   	=   $route_url;
		$emailActions	= 	EmailAction::where('action','=','send_profile_verify')->get()->toArray();
		$emailTemplates	= 	EmailTemplate::where('action','=','send_profile_verify')->get(array('name','subject','action','body'))->toArray();
		$cons 			= 	explode(',',$emailActions[0]['options']);
		$constants 		= 	array();
		foreach($cons as $key => $val){
			$constants[] = '{'.$val.'}';
		} 
		$subject 		= 	$emailTemplates[0]['subject'];
		$rep_Array 		= 	array($full_name,$email,$click_link,$route_url); 
		$messageBody	= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
		$mail			= 	$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
		Session::flash('flash_notice', trans("Profile verified successfully"));
		return Redirect::back();
	} // end sendProfileVerify()
	
/**
* Function for delete,active,deactive user
*
* @param $userId as id of users
*
* @return redirect page. 
*/
 	public function performMultipleAction($userId = 0){
		if(Request::ajax()){
			$actionType = ((Input::get('type'))) ? Input::get('type') : '';
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'active'){
					AdminUser::whereIn('id', Input::get('ids'))->update(array('is_active' => 1));
				}
				elseif($actionType	==	'inactive'){
					AdminUser::whereIn('id', Input::get('ids'))->update(array('is_active' => 0));
				}
				elseif($actionType	==	'verified'){
					AdminUser::whereIn('id', Input::get('ids'))->update(array('is_verified' => 1));
				}
				elseif($actionType	==	'notverified'){
					AdminUser::whereIn('id', Input::get('ids'))->update(array('is_verified' => 0));
				}
				elseif($actionType	==	'delete'){
					AdminUser::whereIn('id', Input::get('ids'))->update(array('is_deleted' => 1));
				}
				Session::flash('flash_notice', trans("messages.user_management.action_performed_message")); 
			}
		}
	}//end performMultipleAction()
/**
* Function for send credential to user
*
* @param $id as id of users
*
* @return redirect page. 
*/
	public function sendCredential($id){
		$obj			=	AdminUser::find($id);
		$settingsEmail 	= 	Config::get('Site.email');
		$full_name		= 	$obj->full_name; 
		$email			= 	$obj->email;
		$password		=	substr(uniqid(rand(10,1000),false),rand(0,10),8);
		$obj->password	=	Hash::make($password);
		$obj->is_verified	=	1;
		$obj->save();
		$route_url      =	route('Home.login');
		$click_link   	=   $route_url;
		$emailActions	= 	EmailAction::where('action','=','send_login_credentials')->get()->toArray();
		$emailTemplates	= 	EmailTemplate::where('action','=','send_login_credentials')->get(array('name','subject','action','body'))->toArray();
		$cons 			= 	explode(',',$emailActions[0]['options']);
		$constants 		= 	array();
		foreach($cons as $key => $val){
			$constants[] = '{'.$val.'}';
		} 
		$subject 		= 	$emailTemplates[0]['subject'];
		$rep_Array 		= 	array($full_name,$email,$password,$click_link,$route_url); 
		$messageBody	= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
		$mail			= 	$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
		Session::flash('flash_notice', trans("Login credientials send successfully"));
		return Redirect::back();
	}	
	public function ChangePassword($userId = 0) {
		if($userId){
			$userDetail		=	User::where('id',$userId)->first();
			$change_password_validate_string	= 	md5($userDetail->email);
			User::where('id',$userId)->update(array('forgot_password_validate_string'=>$change_password_validate_string));
			$settingsEmail 	= 	Config::get('Site.email');
			$full_name		= 	$userDetail->full_name; 
			$email			= 	$userDetail->email;
			$password		=	substr(uniqid(rand(10,1000),false),rand(0,10),8);
			$userDetail->password	=	Hash::make($password);
			$userDetail->save();
			$route_url      =	route('Home.changepassword',$change_password_validate_string);
			$click_link   	=   $route_url;
			$emailActions	= 	EmailAction::where('action','=','send_change_password_link')->get()->toArray();
			$emailTemplates	= 	EmailTemplate::where('action','=','send_change_password_link')->get(array('name','subject','action','body'))->toArray();
			$cons 			= 	explode(',',$emailActions[0]['options']);
			$constants 		= 	array();
			foreach($cons as $key => $val){
				$constants[] = '{'.$val.'}';
			} 
			$subject 		= 	$emailTemplates[0]['subject'];
			$rep_Array 		= 	array($full_name,$click_link); 
			$messageBody	= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
			$mail			= 	$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
			Session::flash('flash_notice', trans("change password link send successfully"));
			return Redirect::back();
		}
	}
	
	public function ChangedPassword($userId = 0){	
		$thisData				=	Input::all(); 
		$validator = Validator::make(
			Input::all(),
			array(
					'password'			=> 'required|min:8',
					'confirm_password'  => 'required|min:8|same:password', 
				)
			);
		if ($validator->fails()){
			return Redirect::route('dashboard.changePassword',$userId)
				->withErrors($validator)->withInput();
		}else{
			$obj	 				=	User::find($userId);
			$obj->password	 		= 	Hash::make(Input::get('password'));
			$obj->save();
			return Redirect::route($this->model.'.index')->with('success',trans("Password has been changed successfully"));
		}
	}


		/* get states */	
	public function getStateList(){
		$countryId 			= 	Input::get('country_id');
		if(!empty($countryId)){
			$state_lists		=	DB::table('states')->where('country_id','!=',0)->where('status',1)->where('country_id',$countryId)->pluck('name','id')->toArray();
		
			if(!empty($state_lists)){
				$err				=	array();
				$err['state_list']	=	$state_lists;
				return Response::json($err); 
				die;
			}else{
				$err				=	array();
				$err['state_list']	=	'';
				return Response::json($err); 
				die;
			}
		}else{
			$err				=	array();
			$err['state_list']	=	'';
			return Response::json($err); 
			die;
		}
	}
	
	/* get cities */	
	public function getCityList(){ 
		$stateId 		= Input::get('state_id');
		if(!empty($stateId)){
			$city_lists 	= DB::table('cities')->where('state_id','!=',0)->where('status',1)->where('state_id',$stateId)->pluck('name','id')->toArray();
		
			if(!empty($city_lists)){
				$err				=	array();
				$err['city_list']	=	$city_lists;
				return Response::json($err); 
				die;
			}else{
				$err				=	array();
				$err['city_list']	=	'';
				return Response::json($err); 
				die;
			}
		}else{
			$err				=	array();
			$err['city_list']	=	'';
			return Response::json($err); 
			die;
		}
	}

		/* get states */	
	public function getLeagueStateList(){
		$countryId 			= 	Input::get('country_id');
		$stateId   			= 	Input::get('state_id');
		$state_lists		=	DB::table('states')->where('country_id','!=',0)->where('status',1)->where('country_id',$countryId)->pluck('name','id')->toArray();
		//echo '<pre>'; print_r($state_lists);die;
		return  View::make('admin.'.$this->model.'.league_state_list',compact('state_lists','stateId'));
	}
	
	/* get cities */	
	public function getLeagueCityList(){ 
		$stateId 		= Input::get('state_id');
		$cityId  		= Input::get('city_id');
		$city_lists 	= DB::table('cities')->where('state_id','!=',0)->where('status',1)->where('state_id',$stateId)->pluck('name','id')->toArray();
	
		return  View::make('admin.'.$this->model.'.league_city_list',compact('city_lists','cityId'));
	}
		/* get states */	
	public function getStudioStateList(){
		$countryId 			= 	Input::get('country_id');
		$stateId   			= 	Input::get('state_id');
		$state_lists		=	DB::table('states')->where('country_id','!=',0)->where('status',1)->where('country_id',$countryId)->pluck('name','id')->toArray();
		//echo '<pre>'; print_r($state_lists);die;
		return  View::make('admin.'.$this->model.'.studio_state_list',compact('state_lists','stateId'));
	}
	
	/* get cities */	
	public function getStudioCityList(){ 
		$stateId 		= Input::get('state_id');
		$cityId  		= Input::get('city_id');
		$city_lists 	= DB::table('cities')->where('state_id','!=',0)->where('status',1)->where('state_id',$stateId)->pluck('name','id')->toArray();
	
		return  View::make('admin.'.$this->model.'.studio_city_list',compact('city_lists','cityId'));
	}

	
	public function deactivateUsers(){
		$formData	=	Input::all();
		if(!empty($formData)){
			if($formData['type'] == 'deactivate_user'){
				foreach($formData["userIds"] as $userId){
					AdminUser::where('id', '=', $userId)->update(array('is_active' => 0));
				}
				$statusMessage	=	trans("User has been deactivated successfully");
			}elseif($formData['type'] == 'delete_user'){
				foreach($formData["userIds"] as $userId){
					$userDetails	=	AdminUser::find($userId); 
					$email 		=	"delete_".$userId."_".$userDetails->email;
					$username	=	"delete_".$userId."_".$userDetails->username;
					$userModel	=	AdminUser::where('id',$userId)->update(array('email'=>$email,'username'=>$username,'is_deleted'=>1)); 
				}
				$statusMessage	=	trans("User has been deleted successfully");
			}
			
		}
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::back();
	}
}//end UsersController
