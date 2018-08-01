<?php
namespace App\Http\Controllers\mobile;
use App\Http\Controllers\BaseController;
use App\Model\User;
use App\Model\ParentChild;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\UserPrivacySetting;
use App\Model\BlockFriend;
use App\Model\ApiResponse;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,App;
use Carbon\Carbon;

/**
* Users Controller
*
* Add your methods in the class below
*
* This file use for call api
*/
class UsersController extends BaseController {

/**
* Function use for signup a user
*
* @param null
*
* @return response
*/
	public function signup(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			if(!empty(Input::get('user_type')) && Input::get('user_type')==DANCER_ROLE_ID){
				if(empty(Input::get('type'))){
					Validator::extend('without_spaces', function($attr, $value){
						return preg_match('/^\S*$/u', $value);
					});	
					
					/* Validator::extend('diff_username', function($attribute, $value, $parameters) {
						if(preg_match('/^[a-zA-Z0-9\p{L}\s]+$/u', $value)) {
							return true;
						} else {
							return false;
						}
					}); */
					$validator 					=	Validator::make(
						Input::all(),
						array(
							'first_name'			=> 'required',
							'last_name'			    => 'required',
							'email' 				=> 'required|email|unique:users',
							'country' 			    => 'required',
							'state' 			    => 'required',
							'city' 			    	=> 'required',
							'gender' 			    => 'required',
							'date' 			    	=> 'required',
							'profile_image' 		=> 'mimes:'.IMAGE_EXTENSION,
							'device_type'			=> 'required',
							'device_id'				=> 'required',
							'username' 				=> 'required|min:4|without_spaces|unique:users',
							'password'				=> 'required|min:8',
							'confirm_password'  	=> 'required|min:8|same:password', 
							'league_name' 			=> "required_if:attend_dance_team,1",
							'league_country' 		=> "required_if:attend_dance_team,1",
							'league_state' 			=> "required_if:attend_dance_team,1",
							'league_city' 			=> "required_if:attend_dance_team,1",
							'studio_name' 			=> "required_if:attend_dance_studio,1",
							'studio_country' 		=> "required_if:attend_dance_studio,1",
							'studio_state' 			=> "required_if:attend_dance_studio,1",
							'studio_city' 			=> "required_if:attend_dance_studio,1",
						),
						array(
							'league_name.required_if'		=>	"The league name field is required.",
							'league_country.required_if'	=>	"The country field is required.",
							'league_state.required_if'		=>	"The state field is required.",
							'league_city.required_if'		=>	"The city field is required.",
							'studio_name.required_if'		=>	"The studio name field is required.",
							'studio_country.required_if'	=>	"The country field is required.",
							'studio_state.required_if'		=>	"The state field is required.",
							'studio_city.required_if'		=>	"The city field is required.",
							"username.min"					=>	trans("The username must be at least 4 characters"),
							"username.required"				=>	trans("The username field is required"),
							"username.without_spaces"		=>	trans("Username field not allowing spaces"),
							//"username.diff_username"		=>	trans("Username should only be allowed to have Letters and Numbers."),
							"username.unique"				=>	trans("Username already exist.")
						)
					);
				}else{
					$validator 					=	Validator::make(
						Input::all(),
						array(
							'social_id'			=> 'required'
						)
					);
				}
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$obj 					=  new User;
					$status 	= 0;
					$userimage 	= 0;
					if(Input::get('type') == 'facebook' && !empty(Input::get('social_id'))){
						$user_details	=	DB::table('users')->where('facebook_id',Input::get('social_id'))->select('id')->first();
						if(!empty($user_details)){
							$response["status"]		=	"user_exist";
							$response["message"]	=	"User already registered.";
							$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
							$status 				= 	1;
						}else{
							$obj->facebook_id			=  Input::get('social_id');
							if(!empty(Input::get('social_profile'))){
								$userImage    				= 	@file_get_contents(Input::get('social_profile'));
								$userImageName     			= 	Input::get('social_id') ."_faceboook.jpg";
								$newFolder     				= 	strtoupper(date('M'). date('Y')).'/';
								$folderPath					=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder;
								
								// get profile image  from social url
								if(!File::exists($folderPath)) {
									File::makeDirectory($folderPath, $mode = 0777,true);
								}
								@file_put_contents($folderPath.$userImageName,$userImage);
								$obj->image = $newFolder.$userImageName;
								$userimage = 1;
							}
						}
					}elseif(Input::get('type') == 'google' && !empty(Input::get('social_id'))){
						$user_details	=	DB::table('users')->where('google_id',Input::get('social_id'))->select('id')->first();
						if(!empty($user_details)){
							$response["status"]		=	"user_exist";
							$response["message"]	=	"User already registered.";
							$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
							$status 				= 	1;
						}else{
							$obj->google_id			=  Input::get('social_id');	
							if(!empty(Input::get('social_profile'))){
								$userImage    				= 	@file_get_contents(Input::get('social_profile'));
								$userImageName     			= 	Input::get('social_id') ."_google.jpg";
								$newFolder     				= 	strtoupper(date('M'). date('Y')).'/';
								$folderPath					=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder;
								
								// get profile image  from social url
								if(!File::exists($folderPath)) {
									File::makeDirectory($folderPath, $mode = 0777,true);
								}
								@file_put_contents($folderPath.$userImageName,$userImage);
								$obj->image = $newFolder.$userImageName;
								$userimage = 1;
							}
						}
					}elseif(Input::get('type') == 'twitter' && !empty(Input::get('social_id'))){
						$user_details	=	DB::table('users')->where('twitter_id',Input::get('social_id'))->select('id')->first();
						if(!empty($user_details)){
							$response["status"]		=	"user_exist";
							$response["message"]	=	"User already registered.";
							$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
							$status 				= 	1;
						}else{
							$obj->twitter_id			=  Input::get('social_id');	
							if(!empty(Input::get('social_profile'))){
								$userImage    				= 	@file_get_contents(Input::get('social_profile'));
								$userImageName     			= 	Input::get('social_id') ."_twitter.jpg";
								$newFolder     				= 	strtoupper(date('M'). date('Y')).'/';
								$folderPath					=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder;
								
								// get profile image  from social url
								if(!File::exists($folderPath)) {
									File::makeDirectory($folderPath, $mode = 0777,true);
								}
								@file_put_contents($folderPath.$userImageName,$userImage);
								$obj->image = $newFolder.$userImageName;
								$userimage = 1;
							}
						}
					}
					if($status == 0){
						$fullName					=  !empty(Input::get('first_name')) ? (Input::get('first_name')).' '.(Input::get('last_name')) : Input::get('full_name');
						$validateString				=  md5(time() . Input::get('email'));
						$obj->device_type			=  Input::get('device_type');	
						$obj->device_id				=  Input::get('device_id');			
						$obj->validate_string		=  $validateString;				
						$obj->full_name 			=  $fullName;
						$obj->email 				=  Input::get('email');
						$obj->username 				=  !empty(Input::get('username'))?Input::get('username'):'';
						$obj->slug	 				=  $this->getSlug($fullName,'full_name','User');
						$obj->password	 			=  !empty(Input::get('username'))?Hash::make(Input::get('password')):'';
						$obj->user_role_id			=  (Input::get('user_type'));
						/* $obj->parent_detail			=   !empty(Input::get('parent_detail_email'))? (Input::get('parent_detail_email')):(Input::get('parent_detail_number')); */
						$obj->address				=  !empty(Input::get('address'))?Input::get('address'):'';
						$obj->country				=  !empty(Input::get('country'))?Input::get('country'):'';
						$obj->state					=  !empty(Input::get('state'))?Input::get('state'):'';
						$obj->city					=  !empty(Input::get('city'))?Input::get('city'):'';
						$obj->gender				=  !empty(Input::get('gender'))?Input::get('gender'):'';
						$obj->date_of_birth					=  !empty(Input::get('date'))?Input::get('date'):'';
						$obj->first_name			=  !empty(Input::get('first_name'))?Input::get('first_name'):'';
						$obj->last_name				=  !empty(Input::get('last_name'))?Input::get('last_name'):'';
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
						if(!empty(Input::get('type'))){
							$obj->is_verified		=  1; 
						}else{
							$obj->is_verified		=  0; 
						}
						$obj->is_active			=  1; 
						if(Input::hasFile('profile_image') && $userimage == 0){
							$extension 			=	 Input::file('profile_image')->getClientOriginalExtension();
							$fileName			=	time().'-user-image.'.$extension;
							if(Input::file('profile_image')->move(USER_PROFILE_IMAGE_ROOT_PATH, $fileName)){
								$obj->image		=	$fileName;
							}
						}
						
						$obj->save();
						$userId					=	$obj->id;
						if(!$userId){
							DB::rollback();
							$response["status"]		=	"error";
							$response["message"]	=	"Something went wrong.";
							$response["data"]		=	array();
						}	
						
						//save notication setting on signup
						$this->save_notification_seting_on_signup($userId,DANCER_ROLE_ID);
						
						$encId					=	md5(time() . Input::get('email'));
						if(empty(Input::get('type'))){
							//mail email and password to new registered user
							$settingsEmail 						= 	Config::get('Site.email');
							$full_name							= 	$obj->full_name; 
							$email								= 	$obj->email;
							$password							= 	'';
							$route_url      					= 	URL::to('account-verification/'.$validateString);
							$select_url    						= 	"<a href='".$route_url."'>Click here</a>";
							$emailActions						= 	EmailAction::where('action','=','account_verification')->get()->toArray();
							$emailTemplates						= 	EmailTemplate::where('action','=','account_verification')->get(array('name','subject','action','body'))->toArray();
							$cons 								= 	explode(',',$emailActions[0]['options']);
							$constants 							= 	array();
							foreach($cons as $key => $val){	
								$constants[] 					= 	'{'.$val.'}';
							}	
							$subject 							= 	$emailTemplates[0]['subject'];
							$rep_Array 							= 	array($full_name,$select_url,$route_url); 
							$messageBody						= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
							$mail								= 	$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
						}
						$user_details			=	$this->get_user_detail_by_id($obj->id);
						$response["status"]		=	"verify";
						$response["message"]	=	"You account has been registered successfully. Please check your inbox to verify your account.";
						$response["data"]		=	$user_details;
					}
				}
			}
			else if(!empty(Input::get('user_type')) && Input::get('user_type')==PARENT_ROLE_ID){
				
				if(empty(Input::get('type'))){
					Validator::extend('without_spaces', function($attr, $value){
						return preg_match('/^\S*$/u', $value);
					});	
					
					/* Validator::extend('diff_username', function($attribute, $value, $parameters) {
						if(preg_match('/^[a-zA-Z0-9\p{L}\s]+$/u', $value)) {
							return true;
						} else {
							return false;
						}
					}); */
					$validator 	=	Validator::make(
						Input::all(),
						array(
							'first_name'		=> 'required',
							'last_name'			=> 'required',
							'email' 			=> 'required|email|unique:users',
							'relationship' 		=> 'required',
							'country' 			=> 'required',
							'state' 			=> 'required',
							'city' 				=> 'required',
							'gender' 			=> 'required',
							'date' 			    => 'required',
							'profile_image' 	=> 'mimes:'.IMAGE_EXTENSION,
							'username' 			=> 'required|min:4|without_spaces|unique:users',
							'password'			=> 'required|min:8',
							'confirm_password'  => 'required|min:8|same:password',
							'device_type'		=> 'required',
							'device_id'			=> 'required'
						),
						array(
							"username.min"					=>	trans("The username must be at least 4 characters"),
							"username.required"				=>	trans("The username field is required"),
							"username.without_spaces"		=>	trans("Username field not allowing spaces"),
							//"username.diff_username"		=>	trans("Username should only be allowed to have Letters and Numbers."),
							"username.unique"				=>	trans("Username already exist.")
						)
					);
				}else{
					$validator 		=	Validator::make(
						Input::all(),
						array(
							'social_id'			=> 'required'
						)
					);
				}
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$errorStatus = 0;
					$checkExist = 0;
					if(isset($formData['dancer']) && !empty($formData['dancer'])){
						
						$dancerdata 	=	json_decode($formData['dancer'],true);	
						if(!empty($dancerdata)){
							Validator::extend('without_spaces', function($attr, $value){
								return preg_match('/^\S*$/u', $value);
							});	
							
							/* Validator::extend('diff_username', function($attribute, $value, $parameters) {
								if(preg_match('/^[a-zA-Z0-9\p{L}\s]+$/u', $value)) {
									return true;
								} else {
									return false;
								}
							}); */
							foreach($dancerdata as $key=>$dancer){
								$validator 					=	Validator::make(
									$dancer,
									array(
										'first_name'				=> 'required',
										'last_name'			    	=> 'required',
										'email' 					=> 'email|unique:users',
										'username' 					=> 'required|min:4|without_spaces|unique:users',
										'password'					=> 'required|min:8',
										'confirm_password'  		=> 'required|min:8|same:password', 
										'country' 			   	 	=> 'required',
										'state' 			   		=> 'required',
										'city' 			    		=> 'required',
										'gender' 			   		=> 'required',
										'date' 			    		=> 'required',
										'send_notification' 		=> 'required',
									),
									array(
										'first_name.required'				=>	"The first name field is required.",
										'last_name.required'				=>	"The last name field is required.",
										//'email.required'					=>	"The email field is required.",
										'email.email'						=>	"The email must be a valid email address.",
										'email.unique'						=>	"The email has already been taken.",
										'username.min'						=>	"The username must be at least 4 characters",
										'username.required'					=>	"The username field is required",
										'username.without_spaces'			=>	"Username field not allowing spaces",
										//'username.diff_username'			=>	"Username should only be allowed to have Letters and Numbers.",
										'username.unique'					=>	"Username already exist.",
										'password.required'					=>	"The password field is required.",
										'password.min'						=>	"The password field must be at least 8 characters",
										'confirm_password.required'			=>	"The confirm password field is required.",
										'confirm_password.min'				=>	"The confirm password field must be at least 8 characters",
										'confirm_password.same'				=>	"The confirm password and password must match.",
										'country.required'					=>	"The country field is required.",
										'state.required'					=>	"The state field is required.",
										'city.required'						=>	"The city field is required.",
										'gender.required'					=>	"The gender field is required.",
										'date.required'						=>	"The date of birth field is required.",
										'send_notification.required'		=>	"The send notification to dancer field is required."
										
									)
								);
								if ($validator->fails()){
									$response		=	$this->change_error_msg_layout($validator->errors()->getMessages());
									$errorStatus = 1;
								}
							}
						}
					}
					if($errorStatus == 0){	
						if(isset($formData['dancer']) && !empty($formData['dancer'])){
							$dancerdata 	=	json_decode($formData['dancer'],true);
							foreach ($dancerdata as $dancerResult) {
								if(!empty($dancerResult['email']) && !empty($dancerResult['username'])){
									$checkChildExist			=	DB::table('users')
																			->orWhere("email",$dancerResult['email'])
																			->orWhere("username",$dancerResult['username'])
																			->select("id")->first();
									if(!empty($checkChildExist)){
										$checkExist = 1;
										break;
									}
								}
							}
						}
						if($checkExist == 0){
							$obj 					=  new User;
							$status = 0;
							$userimage = 0;
							if(Input::get('type') == 'facebook' && !empty(Input::get('social_id'))){
								$user_details	=	DB::table('users')->where('facebook_id',Input::get('social_id'))->select('id')->first();
								if(!empty($user_details)){
									$response["status"]		=	"user_exist";
									$response["message"]	=	"User already registered.";
									$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
									$status 				= 	1;
								}else{
									$obj->facebook_id			=  Input::get('social_id');
									if(!empty(Input::get('social_profile'))){
										$userImage    				= 	@file_get_contents(Input::get('social_profile'));
										$userImageName     			= 	Input::get('social_id') ."_faceboook.jpg";
										$newFolder     				= 	strtoupper(date('M'). date('Y')).'/';
										$folderPath					=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder;
										
										// get profile image  from social url
										if(!File::exists($folderPath)) {
											File::makeDirectory($folderPath, $mode = 0777,true);
										}
										@file_put_contents($folderPath.$userImageName,$userImage);
										$obj->image = $newFolder.$userImageName;
										$userimage = 1;
									}
								}
							}elseif(Input::get('type') == 'google' && !empty(Input::get('social_id'))){
								$user_details	=	DB::table('users')->where('google_id',Input::get('social_id'))->select('id')->first();
								if(!empty($user_details)){
									$response["status"]		=	"user_exist";
									$response["message"]	=	"User already registered.";
									$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
									$status 				= 	1;
								}else{
									$obj->google_id			=  Input::get('social_id');	
									if(!empty(Input::get('social_profile'))){
										$userImage    				= 	@file_get_contents(Input::get('social_profile'));
										$userImageName     			= 	Input::get('social_id') ."_google.jpg";
										$newFolder     				= 	strtoupper(date('M'). date('Y')).'/';
										$folderPath					=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder;
										
										// get profile image  from social url
										if(!File::exists($folderPath)) {
											File::makeDirectory($folderPath, $mode = 0777,true);
										}
										@file_put_contents($folderPath.$userImageName,$userImage);
										$obj->image = $newFolder.$userImageName;
										$userimage = 1;
									}
								}
							}elseif(Input::get('type') == 'twitter' && !empty(Input::get('social_id'))){
								$user_details	=	DB::table('users')->where('twitter_id',Input::get('social_id'))->select('id')->first();
								if(!empty($user_details)){
									$response["status"]		=	"user_exist";
									$response["message"]	=	"User already registered.";
									$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
									$status 				= 	1;
								}else{
									$obj->twitter_id			=  Input::get('social_id');	
									if(!empty(Input::get('social_profile'))){
										$userImage    				= 	@file_get_contents(Input::get('social_profile'));
										$userImageName     			= 	Input::get('social_id') ."_twitter.jpg";
										$newFolder     				= 	strtoupper(date('M'). date('Y')).'/';
										$folderPath					=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder;
										
										// get profile image  from social url
										if(!File::exists($folderPath)) {
											File::makeDirectory($folderPath, $mode = 0777,true);
										}
										@file_put_contents($folderPath.$userImageName,$userImage);
										$obj->image = $newFolder.$userImageName;
										$userimage = 1;
									}
								}
							}
							if($status == 0){
								$fullName					=  !empty(Input::get('first_name')) ? (Input::get('first_name')).' '.(Input::get('last_name')) : Input::get('full_name');
								$validateString				=  md5(time() . Input::get('email'));
								$obj->device_type			=  Input::get('device_type');	
								$obj->device_id				=  Input::get('device_id');			
								$obj->validate_string		=  $validateString;				
								$obj->full_name 			=  $fullName;
								$obj->email 				=  !empty(Input::get('email')) ? Input::get('email') : '';
								$obj->username 				=  !empty(Input::get('username'))?Input::get('username'):'';
								$obj->slug	 				=  $this->getSlug($fullName,'full_name','User');
								$obj->password	 			=  !empty(Input::get('password'))?Hash::make(Input::get('password')):'';
								$obj->user_role_id			=  (Input::get('user_type'));
								$obj->address				=  !empty(Input::get('address'))?Input::get('address'):'';
								$obj->country				=  !empty(Input::get('country'))?Input::get('country'):'';
								$obj->state					=  !empty(Input::get('state'))?Input::get('state'):'';
								$obj->city					=  !empty(Input::get('city'))?Input::get('city'):'';
								$obj->gender				=  !empty(Input::get('gender'))?Input::get('gender'):'';
								$obj->date_of_birth			=  !empty(Input::get('date'))?Input::get('date'):'';
								$obj->first_name			=  !empty(Input::get('first_name'))?Input::get('first_name'):'';
								$obj->last_name				=  !empty(Input::get('last_name'))?Input::get('last_name'):'';
								if(!empty(Input::get('type'))){
									$obj->is_verified		=  1; 
								}else{
									$obj->is_verified		=  0; 
								}
								$obj->is_active				=  1; 
								if(input::hasFile('profile_image') && $userimage == 0){
									$extension 			=	 Input::file('profile_image')->getClientOriginalExtension();
									$fileName			=	time().'-user-image.'.$extension;
									if(Input::file('profile_image')->move(USER_PROFILE_IMAGE_ROOT_PATH, $fileName)){
										$obj->image		=	$fileName;
									}
								}
								$obj->save();
								$userId					=	$obj->id;	
								if(isset($formData['dancer']) && !empty($formData['dancer'])){
									$dancerdata 	=	json_decode($formData['dancer'],true);
									foreach ($dancerdata as $dancerResult) { 
										if(!empty($dancerResult['username']) && !empty($dancerResult['password'])){
											$modelDancer            			= new ParentChild();
											$modelDancer->parent_id 			= $userId;
											$modelDancer->first_name   			= $dancerResult['first_name'];
											$modelDancer->last_name    			= $dancerResult['last_name'];
											$modelDancer->email   				= !empty($dancerResult['email']) ? $dancerResult['email']: '';
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
												$route_url     			= 	URL::to('login');
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
											if(!empty($dancerResult['email']) && !empty($dancerResult['email'])){
												$checkChildExist			=	DB::table('users')
																				->orWhere("email",$dancerResult['email'])
																				->orWhere("username",$dancerResult['username'])
																				->select("id")->first();
											}else{
												$checkChildExist			=	DB::table('users')
																				->where("username",$dancerResult['username'])
																				->select("id")->first();
											}
											if(empty($checkChildExist)){
												$obj1 						= 	new User;			
												$validateString				=  md5(time() . $dancerResult['email']);	
												$obj1->validate_string		=  $validateString;		
												$fullName					=  $dancerResult['first_name']." ".$dancerResult['last_name'];	
												$obj1->first_name			=  $dancerResult['first_name'];
												$obj1->last_name			=  $dancerResult['last_name'];		
												$obj1->full_name 			=  $dancerResult['first_name']." ".$dancerResult['last_name'];
												$obj1->email 				= !empty($dancerResult['email']) ? $dancerResult['email']: '';
												$obj1->username 			=  !empty($dancerResult['username'])?$dancerResult['username']:'';
												$obj1->user_role_id 		=  DANCER_ROLE_ID;
												$obj1->parent_id 			=  $userId;
												$obj1->slug	 				=  $this->getSlug($fullName,'full_name','User');
												$obj1->password	 			=  !empty(Input::get('password'))?Hash::make(Input::get('password')):'';
												$obj1->user_role_id			=  (Input::get('user_type'));
												$obj1->address				=  !empty(Input::get('address'))?Input::get('address'):'';
												$obj1->country				=  !empty(Input::get('country'))?Input::get('country'):'';
												$obj1->state					=  !empty(Input::get('state'))?Input::get('state'):'';
												$obj1->city					=  !empty(Input::get('city'))?Input::get('city'):'';
												$obj1->gender				=  !empty(Input::get('gender'))?Input::get('gender'):'';
												$obj1->date_of_birth		=  !empty(Input::get('date'))?Input::get('date'):'';
												$obj1->is_verified			=  1; 
												$obj1->is_active			=  1; 
												$obj1->save();
												//mail email and password to new registered user
												/* $settingsEmail 						= 	Config::get('Site.email');
												$full_name							= 	$obj1->full_name; 
												$email								= 	$obj1->email;
												$password							= 	'';
												$route_url      					= 	URL::to('account-verification/'.$validateString);
												$select_url    						= 	"<a href='".$route_url."'>Click here</a>";
												$emailActions						= 	EmailAction::where('action','=','account_verification')->get()->toArray();
												$emailTemplates						= 	EmailTemplate::where('action','=','account_verification')->get(array('name','subject','action','body'))->toArray();
												$cons 								= 	explode(',',$emailActions[0]['options']);
												$constants 							= 	array();
												foreach($cons as $key => $val){
													$constants[] 					= 	'{'.$val.'}';
												}
												$subject 							= 	$emailTemplates[0]['subject'];
												$rep_Array 							= 	array($full_name,$select_url,$route_url); 
												$messageBody						= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
												$mail								= 	$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail); */
											}
										}
									}
								}	
								if(!$userId) {
									DB::rollback();
									$response["status"]		=	"error";
									$response["message"]	=	"Something went wrong.";
									$response["data"]		=	array();
								}				
								$encId					=	md5(time() . Input::get('email'));
								if(empty(Input::get('type'))){
									//mail email and password to new registered user
									$settingsEmail 						= 	Config::get('Site.email');
									$full_name							= 	$obj->full_name; 
									$email								= 	$obj->email;
									$password							= 	'';
									$route_url      					= 	URL::to('account-verification/'.$validateString);
									$select_url    						= 	"<a href='".$route_url."'>Click here</a>";
									$emailActions						= 	EmailAction::where('action','=','account_verification')->get()->toArray();
									$emailTemplates						= 	EmailTemplate::where('action','=','account_verification')->get(array('name','subject','action','body'))->toArray();
									$cons 								= 	explode(',',$emailActions[0]['options']);
									$constants 							= 	array();
									foreach($cons as $key => $val){	
										$constants[] 					= 	'{'.$val.'}';
									}	
									$subject 							= 	$emailTemplates[0]['subject'];
									$rep_Array 							= 	array($full_name,$select_url,$route_url); 
									$messageBody						= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
									$mail								= 	$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
								}
								$user_details		=	$this->get_user_detail_by_id($obj->id);
								$response["status"]		=	"verify";
								$response["message"]	=	"You account has been registered successfully. Please check your inbox to verify your account.";
								$response["data"]		=	$user_details;
							}
						}else{
							$response["status"]		=	"error";
							$response["message"]	=	"User already exist.";
							$response["data"]		=	array();
						}
					}
				}
			}
			else if(!empty(Input::get('user_type')) && Input::get('user_type')==STUDIO_ROLE_ID){
				if(empty(Input::get('type'))){
					Validator::extend('without_spaces', function($attr, $value){
						return preg_match('/^\S*$/u', $value);
					});	
					
					/* Validator::extend('diff_username', function($attribute, $value, $parameters) {
						if(preg_match('/^[a-zA-Z0-9\p{L}\s]+$/u', $value)) {
							return true;
						} else {
							return false;
						}
					}); */
					$validator 	=	Validator::make(
						Input::all(),
						array(
							'full_name'			=> 	'required',
							'address' 			=> 	'required',
							'phone_number' 		=> 	'required|numeric|min:10',
							'email' 			=> 	'required|email|unique:users',
							'country' 			=> 	'required',
							'state' 			=> 	'required',
							'city' 			    => 	'required',
							'zip_code' 			=> 	'required',
							'website_address' 	=> 	'required',
							'device_type'		=> 	'required',
							'device_id'			=> 	'required',
							'username' 			=> 'required|min:4|without_spaces|unique:users',
							'password'			=> 'required|min:8',
							'confirm_password'  => 'required|min:8|same:password', 
						),
						array(
							"username.min"					=>	trans("The username must be at least 4 characters"),
							"username.required"				=>	trans("The username field is required"),
							"username.without_spaces"		=>	trans("Username field not allowing spaces"),
							//"username.diff_username"		=>	trans("Username should only be allowed to have Letters and Numbers."),
							"username.unique"				=>	trans("Username already exist.")
						)
					);
				}else{
					$validator 					=	Validator::make(
						Input::all(),
						array(
							'social_id'			=> 'required'
						)
					);
				}
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$obj 					=  new User;
					$status = 0;
					$userimage = 0;
					if(Input::get('type') == 'facebook' && !empty(Input::get('social_id'))){
						$user_details	=	DB::table('users')->where('facebook_id',Input::get('social_id'))->select('id')->first();
						if(!empty($user_details)){
							$response["status"]		=	"user_exist";
							$response["message"]	=	"User already registered.";
							$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
							$status 				= 	1;
						}else{
							$obj->facebook_id			=  Input::get('social_id');
							if(!empty(Input::get('social_profile'))){
								$userImage    				= 	@file_get_contents(Input::get('social_profile'));
								$userImageName     			= 	Input::get('social_id') ."_faceboook.jpg";
								$newFolder     				= 	strtoupper(date('M'). date('Y')).'/';
								$folderPath					=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder;
								
								// get profile image  from social url
								if(!File::exists($folderPath)) {
									File::makeDirectory($folderPath, $mode = 0777,true);
								}
								@file_put_contents($folderPath.$userImageName,$userImage);
								$obj->image = $newFolder.$userImageName;
								$userimage = 1;
							}
						}
					}elseif(Input::get('type') == 'google' && !empty(Input::get('social_id'))){
						$user_details	=	DB::table('users')->where('google_id',Input::get('social_id'))->select('id')->first();
						if(!empty($user_details)){
							$response["status"]		=	"user_exist";
							$response["message"]	=	"User already registered.";
							$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
							$status 				= 	1;
						}else{
							$obj->google_id			=  Input::get('social_id');	
							if(!empty(Input::get('social_profile'))){
								$userImage    				= 	@file_get_contents(Input::get('social_profile'));
								$userImageName     			= 	Input::get('social_id') ."_google.jpg";
								$newFolder     				= 	strtoupper(date('M'). date('Y')).'/';
								$folderPath					=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder;
								
								// get profile image  from social url
								if(!File::exists($folderPath)) {
									File::makeDirectory($folderPath, $mode = 0777,true);
								}
								@file_put_contents($folderPath.$userImageName,$userImage);
								$obj->image = $newFolder.$userImageName;
								$userimage = 1;
							}
						}
					}elseif(Input::get('type') == 'twitter' && !empty(Input::get('social_id'))){
						$user_details	=	DB::table('users')->where('twitter_id',Input::get('social_id'))->select('id')->first();
						if(!empty($user_details)){
							$response["status"]		=	"user_exist";
							$response["message"]	=	"User already registered.";
							$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
							$status 				= 	1;
						}else{
							$obj->twitter_id			=  Input::get('social_id');	
							if(!empty(Input::get('social_profile'))){
								$userImage    				= 	@file_get_contents(Input::get('social_profile'));
								$userImageName     			= 	Input::get('social_id') ."_twitter.jpg";
								$newFolder     				= 	strtoupper(date('M'). date('Y')).'/';
								$folderPath					=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder;
								
								// get profile image  from social url
								if(!File::exists($folderPath)) {
									File::makeDirectory($folderPath, $mode = 0777,true);
								}
								@file_put_contents($folderPath.$userImageName,$userImage);
								$obj->image = $newFolder.$userImageName;
								$userimage = 1;
							}
						}
					}
					if($status == 0){
						$fullName				=  !empty(Input::get('first_name')) ? (Input::get('first_name')).' '.(Input::get('last_name')) : Input::get('full_name');
						
						$validateString			=  md5(time() . Input::get('email'));
						$obj->device_type		=  Input::get('device_type');	
						$obj->device_id			=  Input::get('device_id');			
						$obj->validate_string	=  $validateString;				
						$obj->full_name 		=  $fullName;
						$obj->email 			=  Input::get('email');
						$obj->username 			=  !empty(Input::get('username'))?Input::get('username'):'';
						$obj->slug	 			=  $this->getSlug($fullName,'full_name','User');
						$obj->password	 		=  !empty(Input::get('password'))?Hash::make(Input::get('password')):'';
						$obj->user_role_id		=  (Input::get('user_type'));
						$obj->address			=  !empty(Input::get('address'))?Input::get('address'):'';
						$obj->country			=  !empty(Input::get('country'))?Input::get('country'):'';
						$obj->state				=  !empty(Input::get('state'))?Input::get('state'):'';
						$obj->city				=  !empty(Input::get('city'))?Input::get('city'):'';
						$obj->date_of_birth		=  !empty(Input::get('date'))?Input::get('date'):'';
						$obj->first_name		=  !empty(Input::get('first_name'))?Input::get('first_name'):Input::get('full_name');
						$obj->last_name			=  !empty(Input::get('last_name'))?Input::get('last_name'):'';
						$obj->website_address	=  !empty(Input::get('website_address'))?Input::get('website_address'):'';
						$obj->zip_code			=  !empty(Input::get('zip_code'))?Input::get('zip_code'):'';
						$obj->how_many_dancers_train_monthly	=  !empty(Input::get('how_many_dancers_train_monthly'))?Input::get('how_many_dancers_train_monthly'):0;
						if(!empty(Input::get('type'))){
							$obj->is_verified		=  1; 
						}else{
							$obj->is_verified		=  0; 
						}
						$obj->is_active				=  1;
						if(input::hasFile('profile_image') && $userimage == 0){
							$extension 			=	 Input::file('profile_image')->getClientOriginalExtension();
							$fileName			=	time().'-user-image.'.$extension;
							if(Input::file('profile_image')->move(USER_PROFILE_IMAGE_ROOT_PATH, $fileName)){
								$obj->image		=	$fileName;
							}
						}
						$obj->save();
						$userId					=	$obj->id;	
						if(!$userId) {
							DB::rollback();
							$response["status"]		=	"error";
							$response["message"]	=	"Something went wrong.";
							$response["data"]		=	array();
						}	
						$encId					=	md5(time() . Input::get('email'));
						if(empty(Input::get('type'))){
							//mail email and password to new registered user
							$settingsEmail 						= 	Config::get('Site.email');
							$full_name							= 	$obj->full_name; 
							$email								= 	$obj->email;
							$password							= 	'';
							$route_url      					= 	URL::to('account-verification/'.$validateString);
							$select_url    						= 	"<a href='".$route_url."'>Click here</a>";
							$emailActions						= 	EmailAction::where('action','=','account_verification')->get()->toArray();
							$emailTemplates						= 	EmailTemplate::where('action','=','account_verification')->get(array('name','subject','action','body'))->toArray();
							$cons 								= 	explode(',',$emailActions[0]['options']);
							$constants 							= 	array();
							foreach($cons as $key => $val){	
								$constants[] 					= 	'{'.$val.'}';
							}	
							$subject 							= 	$emailTemplates[0]['subject'];
							$rep_Array 							= 	array($full_name,$select_url,$route_url); 
							$messageBody						= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
							$mail								= 	$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
						}
						$user_details		=	$this->get_user_detail_by_id($obj->id);
						$response["status"]		=	"verify";
						$response["message"]	=	"You account has been registered successfully. Please check your inbox to verify your account.";
						$response["data"]		=	$user_details;
					}
				}
			}
			else if(!empty(Input::get('user_type')) && Input::get('user_type')==FAN_ROLE_ID){
				if(empty(Input::get('type'))){
					Validator::extend('without_spaces', function($attr, $value){
						return preg_match('/^\S*$/u', $value);
					});	
					
					/* Validator::extend('diff_username', function($attribute, $value, $parameters) {
						if(preg_match('/^[a-zA-Z0-9\p{L}\s]+$/u', $value)) {
							return true;
						} else {
							return false;
						}
					}); */
					$validator 					=	Validator::make(
						Input::all(),
						array(
							'device_type'			=> 'required',
							'device_id'				=> 'required',
							'first_name'			=> 'required',
							'last_name'				=> 'required',
							'email' 				=> 'required|email|unique:users',
							'country' 			    => 'required',
							'state' 			    => 'required',
							'city' 			    	=> 'required',
							'gender' 			    => 'required',
							'date' 			    	=> 'required',
							'user_type'				=> 'required',
							'image' 				=> 'mimes:'.IMAGE_EXTENSION,
							'username' 				=> 'required|min:4|without_spaces|unique:users',
							'password'				=> 'required|min:8',
							'confirm_password'  	=> 'required|min:8|same:password', 
						),
						array(
							"username.min"					=>	trans("The username must be at least 4 characters"),
							"username.required"				=>	trans("The username field is required"),
							"username.without_spaces"		=>	trans("Username field not allowing spaces"),
							//"username.diff_username"		=>	trans("Username should only be allowed to have Letters and Numbers."),
							"username.unique"				=>	trans("Username already exist.")
						)
					);
				}else{
					$validator 					=	Validator::make(
						Input::all(),
						array(
							'social_id'			=> 'required'
						)
					);
				}
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$obj 					=  new User;
					$status = 0;
					$userimage = 0;
					if(Input::get('type') == 'facebook' && !empty(Input::get('social_id'))){
						$user_details	=	DB::table('users')->where('facebook_id',Input::get('social_id'))->select('id')->first();
						if(!empty($user_details)){
							$response["status"]		=	"user_exist";
							$response["message"]	=	"User already registered.";
							$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
							$status 				= 	1;
						}else{
							$obj->facebook_id			=  Input::get('social_id');
							if(!empty(Input::get('social_profile'))){
								$userImage    				= 	@file_get_contents(Input::get('social_profile'));
								$userImageName     			= 	Input::get('social_id') ."_faceboook.jpg";
								$newFolder     				= 	strtoupper(date('M'). date('Y')).'/';
								$folderPath					=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder;
								
								// get profile image  from social url
								if(!File::exists($folderPath)) {
									File::makeDirectory($folderPath, $mode = 0777,true);
								}
								@file_put_contents($folderPath.$userImageName,$userImage);
								$obj->image = $newFolder.$userImageName;
								$userimage = 1;
							}
						}
					}elseif(Input::get('type') == 'google' && !empty(Input::get('social_id'))){
						$user_details	=	DB::table('users')->where('google_id',Input::get('social_id'))->select('id')->first();
						if(!empty($user_details)){
							$response["status"]		=	"user_exist";
							$response["message"]	=	"User already registered.";
							$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
							$status 				= 	1;
						}else{
							$obj->google_id			=  Input::get('social_id');	
							if(!empty(Input::get('social_profile'))){
								$userImage    				= 	@file_get_contents(Input::get('social_profile'));
								$userImageName     			= 	Input::get('social_id') ."_google.jpg";
								$newFolder     				= 	strtoupper(date('M'). date('Y')).'/';
								$folderPath					=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder;
								
								// get profile image  from social url
								if(!File::exists($folderPath)) {
									File::makeDirectory($folderPath, $mode = 0777,true);
								}
								@file_put_contents($folderPath.$userImageName,$userImage);
								$obj->image = $newFolder.$userImageName;
								$userimage = 1;
							}
						}
					}elseif(Input::get('type') == 'twitter' && !empty(Input::get('social_id'))){
						$user_details	=	DB::table('users')->where('twitter_id',Input::get('social_id'))->select('id')->first();
						if(!empty($user_details)){
							$response["status"]		=	"user_exist";
							$response["message"]	=	"User already registered.";
							$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
							$status 				= 	1;
						}else{
							$obj->twitter_id			=  Input::get('social_id');	
							if(!empty(Input::get('social_profile'))){
								$userImage    				= 	@file_get_contents(Input::get('social_profile'));
								$userImageName     			= 	Input::get('social_id') ."_twitter.jpg";
								$newFolder     				= 	strtoupper(date('M'). date('Y')).'/';
								$folderPath					=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder;
								
								// get profile image  from social url
								if(!File::exists($folderPath)) {
									File::makeDirectory($folderPath, $mode = 0777,true);
								}
								@file_put_contents($folderPath.$userImageName,$userImage);
								$obj->image = $newFolder.$userImageName;
								$userimage = 1;
							}
						}
					}
					if($status == 0){
						$fullName				=  !empty(Input::get('first_name')) ? (Input::get('first_name')).' '.(Input::get('last_name')) : Input::get('full_name');
						$validateString			=  md5(time() . Input::get('email'));
						$obj->device_type		=  Input::get('device_type');	
						$obj->device_id			=  Input::get('device_id');			
						$obj->validate_string	=  $validateString;				
						$obj->full_name 		=  $fullName;
						$obj->email 			=  Input::get('email');
						$obj->username 			=  !empty(Input::get('username'))?Input::get('username'):'';
						$obj->slug	 			=  $this->getSlug($fullName,'full_name','User');
						$obj->password	 		=  !empty(Input::get('password'))?Hash::make(Input::get('password')):'';
						$obj->user_role_id		=  (Input::get('user_type'));
						$obj->country			=  !empty(Input::get('country'))?Input::get('country'):'';
						$obj->state				=  !empty(Input::get('state'))?Input::get('state'):'';
						$obj->city				=  !empty(Input::get('city'))?Input::get('city'):'';
						$obj->gender			=  !empty(Input::get('gender'))?Input::get('gender'):'';
						$obj->date_of_birth		=  !empty(Input::get('date'))?Input::get('date'):'';
						$obj->first_name		=  !empty(Input::get('first_name'))?Input::get('first_name'):'';
						$obj->last_name			=  !empty(Input::get('last_name'))?Input::get('last_name'):'';
						if(!empty(Input::get('type'))){
							$obj->is_verified		=  1; 
						}else{
							$obj->is_verified		=  0; 
						}
						$obj->is_active				=  1;
						if(input::hasFile('profile_image') && $userimage == 0){
							$extension 			=	 Input::file('profile_image')->getClientOriginalExtension();
							$fileName			=	time().'-user-image.'.$extension;
							if(Input::file('profile_image')->move(USER_PROFILE_IMAGE_ROOT_PATH, $fileName)){
								$obj->image		=	$fileName;
							}
						}
						$obj->save();
						$userId					=	$obj->id;	
						
						if(!$userId) {
							DB::rollback();
							$response["status"]		=	"error";
							$response["message"]	=	"Something went wrong.";
							$response["data"]		=	array();
						}				
						$encId					=	md5(time() . Input::get('email'));
						if(empty(Input::get('type'))){
							//mail email and password to new registered user
							$settingsEmail 						= 	Config::get('Site.email');
							$full_name							= 	$obj->full_name; 
							$email								= 	$obj->email;
							$password							= 	'';
							$route_url      					= 	URL::to('account-verification/'.$validateString);
							$select_url    						= 	"<a href='".$route_url."'>Click here</a>";
							$emailActions						= 	EmailAction::where('action','=','account_verification')->get()->toArray();
							$emailTemplates						= 	EmailTemplate::where('action','=','account_verification')->get(array('name','subject','action','body'))->toArray();
							$cons 								= 	explode(',',$emailActions[0]['options']);
							$constants 							= 	array();
							foreach($cons as $key => $val){	
								$constants[] 					= 	'{'.$val.'}';
							}
							$subject 							= 	$emailTemplates[0]['subject'];
							$rep_Array 							= 	array($full_name,$select_url,$route_url); 
							$messageBody						= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
							$mail								= 	$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
						}
						$user_details			=	$this->get_user_detail_by_id($obj->id);
						if(!empty(Input::get('type'))){
							$response["status"]		=	"success";
							$response["message"]	=	"You account has been registered successfully";
							$response["data"]		=	$user_details;
						}else{
							$response["status"]		=	"verify";
							$response["message"]	=	"You account has been registered successfully. Please check your inbox to verify your account.";
							$response["data"]		=	$user_details;
						}
					}
				}
			}else{
				$response["status"]		=	"error";
				$response["message"]	=	"Invalid Request.";
				$response["data"]		=	array();
			}
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
/**
* Function use for login a user
*
* @param null
*
* @return response
*/
	public function login(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator = Validator::make(
				Input::all(),
				array(
					'device_type'		=> 'required',
					'device_id'			=> 'required',
					'password'			=> 'required',
					'email' 			=> 'required',
				),array(
					"email.required"				=>	trans("The username field is required"),
					"password.required"			=>	trans("The password field is required"),
				)
			);
			if($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{ 
				$username		=	Input::get('email');
				$userData		=	DB::table('users')->where(function ($query) use ($username) {
												$query->orWhere('username',$username);
												$query->orWhere('email',$username);
											})/* ->where('is_active',1)->where('is_verified',1) */->where('is_deleted',0)->first();
				if(!empty($userData)){
					$userData		=	json_decode(json_encode($userData,true),true);
					if($userData['is_active'] == 0) {
						$response["status"]		=	"active";
						$response["message"]	=	"Your account is inactive please contact to admin.";
						$response["data"]		=	array();
					}else if($userData['is_verified'] == 0) {
						$response["status"]		=	"verify";
						$response["message"]	=	"Your account is not verified. Please verify your account.";
						$response["data"]		=	array();
					}else{
						$userData = array(
							'email' 		=> $userData['email'],
							'password' 		=> Input::get('password'),
							'is_active' 	=> $userData['is_active'],
							'is_verified' 	=> $userData['is_verified'],
							'id' 			=> $userData['id'],
							'is_deleted' 	=> 0,
						);
						
						if(Auth::attempt($userData)){
							$user_details		=	$this->get_user_detail_by_id(Auth::user()->id); 
							DB::table("users")->where("id",Auth::user()->id)->update(array("device_type"=>Input::get('device_type'),"device_id"=>Input::get('device_id')));
							if(Auth::user()->user_role_id != SUPER_ADMIN_ROLE_ID){
								$response["status"]		=	"success";
								$response["message"]	=	"You are now logged in!";
								$response["data"]		=	$user_details;
							}else{
								$response["status"]		=	"error";
								$response["message"]	=	"Username or Password is incorrect.";
								$response["data"]		=	array();
							}
						}else{
							$response["status"]		=	"error";
							$response["message"]	=	"Username or Password is incorrect.";
							$response["data"]		=	array();
						}
					}
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Your account is not registered with CMEShine.";
					$response["data"]		=	array();
				}
			}
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
/**
* Function use for change password
*
* @param null
*
* @return response
*/
	public function change_password(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			//$request	=	$this->decrypt($formData["request"]);
			//Input::replace($this->arrayStripTags($request));
			/* 
			$old_password     = Input::get('old_password');
			$password         = Input::get('new_password');
			$confirm_password = Input::get('confirm_password');
			 */
			Validator::extend('custom_password', function($attribute, $value, $parameters) {
				if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
					return true;
				} else {
					return false;
				}
			});
			
			$validator = Validator::make(
				Input::all(),
				array(
					'user_id' 			=> 'required',
					'old_password' 		=> 'required',
					'new_password'		=> 'required|min:8|custom_password',
					'confirm_password'  => 'required|same:new_password'
				),
				array(
					'new_password.custom_password'	=>	trans("Password must contain at least 1 lower-case and capital letter, a number and symbol."),
				)
			);
			if ($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user 					= User::find(Input::get('user_id'));
				$old_password 			= Input::get('old_password'); 
				$password 				= Input::get('new_password');
				$confirm_password 		= Input::get('confirm_password');
	
				if(Hash::check($old_password, $user->getAuthPassword())){
					$user->password = Hash::make($password);
					if($user->save()) {
						$response["status"]		=	"success";
						$response["message"]	=	"Password changed successfully.";
						$response["data"]		=	array();
					}else {
						$response["status"]		=	"error";
						$response["message"]	=	"Something went wrong. Please try again.";
						$response["data"]		=	array();
					}
				} else {
					$response["status"]		=	"error";
					$response["message"]	=	"Your old password is incorrect.";
					$response["data"]		=	array();
				}
			}
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
/**
* Function use for forget password
*
* @param null
*
* @return response
*/
	public function forget_password(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			//$request	=	$this->decrypt($formData["request"]);
			//Input::replace($this->arrayStripTags($request));
			$messages = array(
				'email.required' 		=> trans('The email field is required.'),
				'email.email' 			=> trans('The email must be a valid email address.'),
			);
			$validator = Validator::make(
				Input::all(),
				array(
					'email' 			=> 'required|email',
				),$messages
			);
			if ($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$email		=	Input::get('email');   
				$userDetail	=	User::where('email',$email)/* ->where("is_email_verified",1)->where("user_role_id",FRONT_USER_ROLE_ID) */->first();
				if(!empty($userDetail)){
					if($userDetail->is_active == 1 ){
						$forgot_password_validate_string	= 	md5($userDetail->email);
						User::where('email',$email)->update(array('forgot_password_validate_string'=>$forgot_password_validate_string));
						
						$settingsEmail 		=  Config::get('Site.email');
						$email 				=  $userDetail->email;
						$full_name			=  $userDetail->full_name;  
						$route_url      	=  URL::to('reset-password/'.$forgot_password_validate_string);
						$varify_link   		=   $route_url;
						
						$emailActions		=	EmailAction::where('action','=','forgot_password')->get()->toArray();
						$emailTemplates		=	EmailTemplate::where('action','=','forgot_password')->get(array('name','subject','action','body'))->toArray();
						$cons = explode(',',$emailActions[0]['options']);
						$constants = array();
						
						foreach($cons as $key=>$val){
							$constants[] = '{'.$val.'}';
						}
						$subject 			=  $emailTemplates[0]['subject'];
						$rep_Array 			= 	array($full_name,$varify_link,$route_url); 
						$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
						$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);

						$response["status"]		=	"success";
						$response["message"]	=	"An email has been sent to your inbox. To reset your password please follow the steps mentioned in the email.";
						$response["data"]		=	array();		
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"Your account has been temporarily disabled. Please contact administrator to unlock.";
						$response["data"]		=	array();
					}	
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Your email is not registered with CMeShine.";
					$response["data"]		=	array();
				}
			}
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
			
/**
* Function use for get user detail
*
* @param null
*
* @return response
*/
	public function get_user_detail(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$request	=	$this->decrypt($formData["request"]);
			Input::replace($this->arrayStripTags($request));
			$validator = Validator::make(
				Input::all(),
				array(
					'user_id' 			=> 'required',
				)
			);
			if ($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{ 
				$user_details			=	$this->get_user_detail_by_id(Input::get("user_id"));
				if(!empty($user_details)){
					$response["status"]		=	"success";
					$response["message"]	=	"";
					$response["data"]		=	$user_details;
				}else {
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid user";
					$response["data"]		=	array();
				}
			}
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return $this->encrypt($response);
	}			
		
/**
* Function use for edit profile
*
* @param null
*
* @return response
*/
	
	public function logout(){
		$request	=	$this->decrypt($formData["request"]);
		Input::replace($this->arrayStripTags($request));
			Auth::logout();
			$response["status"]		=	"success";
			$response["message"]	=	"User logout successfully.";
			$response["data"]		=	array();
		return $this->encrypt($response);
	}
	
	public function checkEmailExist(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			//$request	=	$this->decrypt($formData["request"]);
			//Input::replace($this->arrayStripTags($request));
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'email' 				=> 'required|email|unique:users',
					)
				);
				
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					
					
					$response["status"]		=	"success";
					$response["message"]	=	"You have entered a valid email.";
					$response["data"]		=	array('email'=>Input::get('email'));
				}
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		
		return json_encode($response);
	}
	
	
	public function checkUsernameExist(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			//$request	=	$this->decrypt($formData["request"]);
			//Input::replace($this->arrayStripTags($request));
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'username' 				=> 'required|unique:users',
					)
				);
				
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					
					
					$response["status"]		=	"success";
					$response["message"]	=	"You have entered a valid username.";
					$response["data"]		=	array('username'=>Input::get('username'));
				}
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		
		return json_encode($response);
	}
	
	public function getCountryList(){
		
			//~ $request	=	$this->decrypt($formData["request"]);
			//~ Input::replace($this->arrayStripTags($request));
			$country_lists		=	DB::table('countries')->where('status',1)->select('name','id')->get();
		
			if(!empty($country_lists)){
				$response["status"]			=	"success";
				$response["message"]		=	"Country list found successfully";
				$response["country_list"]	=	$country_lists;
			}else{
				$response["status"]			=	"error";
				$response["message"]		=	"Country list not found.";
				$response["country_list"]	=	array();
			}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	public function getStateList($countryId = null){
			//~ $request	=	$this->decrypt($formData["request"]);
			//~ Input::replace($this->arrayStripTags($request));
			if(!empty($countryId)){
				$state_lists		=	DB::table('states')->where('country_id','!=',0)->where('status',1)->where('country_id',$countryId)->select('name','id')->get();
			
				if(!empty($state_lists)){
					$response["status"]			=	"success";
					$response["message"]		=	"State list found successfully.";
					$response['state_list']	=	$state_lists;
				}else{
					$response["status"]			=	"error";
					$response["message"]		=	"State list not found.";
					$response['state_list']	=	array();
				}
			}else{
				$response["status"]			=	"error";
				$response["message"]		=	"Please select Country.";
				$response['state_list']	=	array();
			}
		//return $this->encrypt($response);
		return json_encode($response);
	}
		
	public function getCityList($stateId = null){ 
			//~ $request	=	$this->decrypt($formData["request"]);
			//~ Input::replace($this->arrayStripTags($request));
			if(!empty($stateId)){
				$city_lists 	= DB::table('cities')->where('state_id','!=',0)->where('status',1)->where('state_id',$stateId)->select('name','id')->get();
			
				if(!empty($city_lists)){
					$response["status"]			=	"success";
					$response["message"]		=	"City list found successfully.";
					$response['city_list']	=	$city_lists;
				}else{
					$response["status"]			=	"error";
					$response["message"]		=	"City list not found.";
					$response['city_list']	=	'';
				}
			}else{
				$response["status"]			=	"error";
				$response["message"]		=	"Please select State.";
				$response['city_list']	=	'';
			}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	public function checkParentDetail(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			if(!empty($formData['type']) && $formData['type']=='email'){
				//$request	=	$this->decrypt($formData["request"]);
				//Input::replace($this->arrayStripTags($request));
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'email' 				=> 'required|email',
					)
				);
				
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$settingsEmail 			=	Config::get('Site.email');
					$full_name				= 	''; 
					$email					= 	Input::get('email');
					$route_url     			= 	URL::to('login');
					$click_link   			=   $route_url;
					$emailActions			= 	EmailAction::where('action','=','parent_get_notification')->get()->toArray();
					$emailTemplates			= 	EmailTemplate::where('action','=','parent_get_notification')->get(array('name','subject','action','body'))->toArray();
					$cons 					= 	explode(',',$emailActions[0]['options']);
					$constants 				= 	array();
					foreach($cons as $key => $val){
						$constants[] 		= 	'{'.$val.'}';
					} 
					$subject 				= 	$emailTemplates[0]['subject'];
					$rep_Array 				= 	array($email,$click_link,$route_url); 
					$messageBody			= 	str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
					$mail					= 	$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);	
					
					$response["status"]		=	"success";
					$response["message"]	=	"Email address added successfully, check your mail for more details.";
					$response["data"]		=	array('type'=>Input::get('type'),'email'=>Input::get('email'));
				}
			}else if(!empty($formData['type']) && $formData['type']=='mobile_number'){
				//$request	=	$this->decrypt($formData["request"]);
				//Input::replace($this->arrayStripTags($request));
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'mobile_number' 				=> 'required|numeric',
					)
				);
				
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					
					
					$response["status"]		=	"success";
					$response["message"]	=	"Phone number added successfully, please check message for more details.";
					$response["data"]		=	array('type'=>Input::get('type'),'mobile_number'=>Input::get('mobile_number'));
				}
			}else{
				$response["status"]		=	"error";
				$response["message"]	=	"Please enter input type.";
				$response["data"]		=	array();
			}
			
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		
		return json_encode($response);
	}
	
	public function get_user_detail_by_id($user_id){
		$user_details			=	DB::table('users')->where('id',$user_id)->select('*','users.country as country_id','users.state as state_id','users.city as city_id',DB::raw("(select name from countries where id=users.country and status=1) as country"),DB::raw("(select name from states where id=users.state and status=1) as state"),DB::raw("(select name from cities where id=users.city and status=1) as city"),DB::raw("(select name from countries where id=users.league_country and status=1) as league_country_name"),DB::raw("(select name from states where id=users.league_state and status=1) as league_state_name"),DB::raw("(select name from cities where id=users.league_city and status=1) as league_city_name"),DB::raw("(select name from countries where id=users.studio_country and status=1) as studio_country_name"),DB::raw("(select name from states where id=users.studio_state and status=1) as studio_state_name"),DB::raw("(select name from cities where id=users.studio_city and status=1) as studio_city_name"))->first();
		if(!empty($user_details)){
			if($user_details->image != "" && File::exists(USER_PROFILE_IMAGE_ROOT_PATH.$user_details->image)){
				$user_details->image = USER_PROFILE_IMAGE_URL.$user_details->image;
			}else{
				$user_details->image = WEBSITE_IMG_URL.'usr_img.png';
			}
		}
		return $user_details;
	}
	
	public function get_user_profile_picture_by_id($user_id){
		$user_details			=	DB::table('users')->where('id',$user_id)->select('users.id','users.image as image')->first();
		if(!empty($user_details)){
			if($user_details->image != "" && File::exists(USER_PROFILE_IMAGE_ROOT_PATH.$user_details->image)){
				$user_details->image = USER_PROFILE_IMAGE_URL.$user_details->image;
			}else{
				$user_details->image = WEBSITE_IMG_URL.'usr_img.png';
			}
		}
		return $user_details;
	}
	
	public function check_socialid_exist(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'type' 		=> 'required',
					'social_id' => 'required',
				)
			);
			if ($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				if(Input::get('type') == 'facebook' && !empty(Input::get('social_id'))){
					$user_details	=	DB::table('users')->where('facebook_id',Input::get('social_id'))->select('id')->first();
					if(!empty($user_details)){
						$response["status"]		=	"success";
						$response["message"]	=	"User already registered.";
						$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"User not registered.";
						$response["data"]		=	array();
					}
				}elseif(Input::get('type') == 'google' && !empty(Input::get('social_id'))){
					$user_details	=	DB::table('users')->where('google_id',Input::get('social_id'))->select('id')->first();
					if(!empty($user_details)){
						$response["status"]		=	"success";
						$response["message"]	=	"User already registered.";
						$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"User not registered.";
						$response["data"]		=	array();
					}
				}elseif(Input::get('type') == 'twitter' && !empty(Input::get('social_id'))){
					$user_details	=	DB::table('users')->where('twitter_id',Input::get('social_id'))->select('id')->first();
					if(!empty($user_details)){
						$response["status"]		=	"success";
						$response["message"]	=	"User already registered.";
						$response["data"]		=	$this->get_user_detail_by_id($user_details->id);
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"User not registered.";
						$response["data"]		=	array();
					}
				}else {
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}
			}
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	public function cms_detail(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'slug' 		=> 'required',
				)
			);
			if ($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$slug = Input::get("slug");
				$lang			=	App::getLocale();
				$cmsPagesDetail	=	DB::select( DB::raw("SELECT * FROM cms_page_descriptions WHERE foreign_key = (select id from cms_pages WHERE cms_pages.slug = '$slug') AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );
				if(empty($cmsPagesDetail)){
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}else{
					$result	=	array();
					foreach($cmsPagesDetail as $cms){
						$key	=	$cms->source_col_name;
						$value	=	$cms->source_col_description;
						$result[$cms->source_col_name]	=	$cms->source_col_description;
					}
					$response["status"]		=	"success";
					$response["message"]	=	"";
					$response["data"]		=	$result;
				}
			}
		}
		return json_encode($response);
	}

	public function edit_profile(){
		$id			=	Input::get('id');
		$formData	=	User::find($id);
		$response	=	array();
		if(!empty(Input::get('id')) && !empty($formData)){
			if(!empty(Input::get('user_type')) && Input::get('user_type')==DANCER_ROLE_ID){
				Validator::extend('without_spaces', function($attr, $value){
					return preg_match('/^\S*$/u', $value);
				});	
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'fullname'			=> 'required',
						//'last_name'			    => 'required',
						'email' 				=> "required|email|unique:users,email,$id,id,is_deleted,0",
						'country' 			=> 'required',
						'state' 			    => 'required',
						'city' 			    	=> 'required',
						'gender' 			    => 'required',
						'date_of_birth' 		=> 'required',
						//'profile_image' 		=> 'mimes:'.IMAGE_EXTENSION,
						'device_type'			=> 'required',
						'device_id'				=> 'required',
						//'username' 			=> "required|min:4|without_spaces|unique:users,username,$id,id,is_deleted,0",
						//'password'			=> 'required|min:8',
						//'confirm_password'  	=> 'required|min:8|same:password', 
						'attend_dance_team' 	=> "required",
						'league_name' 			=> "required_if:attend_dance_team,1",
						'league_country' 		=> "required_if:attend_dance_team,1",
						'league_state' 			=> "required_if:attend_dance_team,1",
						'league_city' 			=> "required_if:attend_dance_team,1",
						'attend_dance_studio' 	=> "required",
						'studio_name' 			=> "required_if:attend_dance_studio,1",
						'studio_country' 		=> "required_if:attend_dance_studio,1",
						'studio_state' 			=> "required_if:attend_dance_studio,1",
						'studio_city' 			=> "required_if:attend_dance_studio,1",
					),
					array(
						'league_name.required_if'		=>	"The league name field is required.",
						'league_country.required_if'	=>	"The country field is required.",
						'league_state.required_if'		=>	"The state field is required.",
						'league_city.required_if'		=>	"The city field is required.",
						'studio_name.required_if'		=>	"The studio name field is required.",
						'studio_country.required_if'	=>	"The country field is required.",
						'studio_state.required_if'		=>	"The state field is required.",
						'studio_city.required_if'		=>	"The city field is required.",
						"username.min"					=>	trans("The username must be at least 4 characters"),
						"username.required"				=>	trans("The username field is required"),
						"username.without_spaces"		=>	trans("Username field not allowing spaces"),
						//"username.diff_username"		=>	trans("Username should only be allowed to have Letters and Numbers."),
						"username.unique"				=>	trans("Username already exist.")
					)
				);
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$obj 					=  User::find($id);
					$fullName				=  !empty(Input::get('first_name')) ? (Input::get('first_name')).' '.(Input::get('last_name')) : Input::get('full_name');
					$obj->full_name 			=  !empty(Input::get('fullname'))?Input::get('fullname'):'';
					$obj->email 				=  Input::get('email');
					//$obj->username 				=  !empty(Input::get('username'))?Input::get('username'):'';
					//$obj->address				=  !empty(Input::get('address'))?Input::get('address'):'';
					$obj->country				=  !empty(Input::get('country'))?Input::get('country'):'';
					$obj->state					=  !empty(Input::get('state'))?Input::get('state'):'';
					$obj->city					=  !empty(Input::get('city'))?Input::get('city'):'';
					$obj->gender				=  !empty(Input::get('gender'))?Input::get('gender'):'';
					$obj->date_of_birth			=  !empty(Input::get('date_of_birth'))?Input::get('date_of_birth'):'';
					//$obj->first_name			=  !empty(Input::get('first_name'))?Input::get('first_name'):'';
					//$obj->last_name				=  !empty(Input::get('last_name'))?Input::get('last_name'):'';
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
					if(Input::hasFile('profile_image')){
						$extension 			=	 Input::file('profile_image')->getClientOriginalExtension();
						$fileName			=	time().'-user-image.'.$extension;
						if(Input::file('profile_image')->move(USER_PROFILE_IMAGE_ROOT_PATH, $fileName)){
							$obj->image		=	$fileName;
						}
					}
					
					$obj->save();
					$userId					=	$obj->id;
					if(!$userId){
						DB::rollback();
						$response["status"]		=	"error";
						$response["message"]	=	"Something went wrong.";
						$response["data"]		=	array();
					}	
					$user_details			=	$this->get_user_detail_by_id($obj->id);
					$response["status"]		=	"success";
					$response["message"]	=	"Your profile has been updated successfully.";
					$response["data"]		=	$user_details;
				}
			}
			else if(!empty(Input::get('user_type')) && Input::get('user_type')==PARENT_ROLE_ID){
				
				Validator::extend('without_spaces', function($attr, $value){
					return preg_match('/^\S*$/u', $value);
				});	
				$validator 	=	Validator::make(
					Input::all(),
					array(
						'first_name'		=> 'required',
						'last_name'			=> 'required',
						'email' 			=> "required|email|unique:users,email,$id,id,is_deleted,0",
						'relationship' 		=> 'required',
						'country' 			=> 'required',
						'state' 			=> 'required',
						'city' 				=> 'required',
						'gender' 			=> 'required',
						'date_of_birth' 	=> 'required',
						'profile_image' 	=> 'mimes:'.IMAGE_EXTENSION,
						'username' 			=> "required|min:4|without_spaces|unique:users,username,$id,id,is_deleted,0",
						'password'			=> 'required|min:8',
						'confirm_password'  => 'required|min:8|same:password',
						'device_type'		=> 'required',
						'device_id'			=> 'required'
					),
					array(
						"username.min"					=>	trans("The username must be at least 4 characters"),
						"username.required"				=>	trans("The username field is required"),
						"username.without_spaces"		=>	trans("Username field not allowing spaces"),
						//"username.diff_username"		=>	trans("Username should only be allowed to have Letters and Numbers."),
						"username.unique"				=>	trans("Username already exist.")
					)
				);
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$errorStatus = 0;
					$checkExist = 0;
					if(isset($formData['dancer']) && !empty($formData['dancer'])){
						$dancerdata 	=	json_decode($formData['dancer'],true);	
						if(!empty($dancerdata)){
							Validator::extend('without_spaces', function($attr, $value){
								return preg_match('/^\S*$/u', $value);
							});	
							foreach($dancerdata as $key=>$dancer){
								$validator 					=	Validator::make(
									$dancer,
									array(
										'first_name'				=> 'required',
										'last_name'			    	=> 'required',
										'email' 					=> "required|email|unique:users,email,$id,id,is_deleted,0",
										'username' 					=> "required|min:4|without_spaces|unique:users,username,$id,id,is_deleted,0",
										'password'					=> 'required|min:8',
										'confirm_password'  		=> 'required|min:8|same:password', 
										'country' 			   	 	=> 'required',
										'state' 			   		=> 'required',
										'city' 			    		=> 'required',
										'gender' 			   		=> 'required',
										'date' 			    		=> 'required',
										'send_notification' 		=> 'required',
									),
									array(
										'first_name.required'				=>	"The first name field is required.",
										'last_name.required'				=>	"The last name field is required.",
										'email.required'					=>	"The email field is required.",
										'email.email'						=>	"The email must be a valid email address.",
										'email.unique'						=>	"The email has already been taken.",
										'username.min'						=>	"The username must be at least 4 characters",
										'username.required'					=>	"The username field is required",
										'username.without_spaces'			=>	"Username field not allowing spaces",
										//'username.diff_username'			=>	"Username should only be allowed to have Letters and Numbers.",
										'username.unique'					=>	"Username already exist.",
										'password.required'					=>	"The password field is required.",
										'password.min'						=>	"The password field must be at least 8 characters",
										'confirm_password.required'			=>	"The confirm password field is required.",
										'confirm_password.min'				=>	"The confirm password field must be at least 8 characters",
										'confirm_password.same'				=>	"The confirm password and password must match.",
										'country.required'					=>	"The country field is required.",
										'state.required'					=>	"The state field is required.",
										'city.required'						=>	"The city field is required.",
										'gender.required'					=>	"The gender field is required.",
										'date.required'						=>	"The date of birth field is required.",
										'send_notification.required'		=>	"The send notification to dancer field is required."
										
									)
								);
								if ($validator->fails()){
									$response		=	$this->change_error_msg_layout($validator->errors()->getMessages());
									$errorStatus = 1;
								}
							}
						}
					}
					if($errorStatus == 0){	
						if(isset($formData['dancer']) && !empty($formData['dancer'])){
							$dancerdata 	=	json_decode($formData['dancer'],true);
							foreach ($dancerdata as $dancerResult) {
								if(!empty($dancerResult['email']) && !empty($dancerResult['username'])){
									$checkChildExist			=	DB::table('users')
																			->orWhere("email",$dancerResult['email'])
																			->orWhere("username",$dancerResult['username'])
																			->select("id")->first();
									if(!empty($checkChildExist)){
										$checkExist = 1;
										break;
									}
								}
							}
						}
						if($checkExist == 0){
							$obj 					=  User::find($id);
								$fullName					=  !empty(Input::get('first_name')) ? (Input::get('first_name')).' '.(Input::get('last_name')) : Input::get('full_name');
								$validateString				=  md5(time() . Input::get('email'));		
								$obj->full_name 			=  $fullName;
								$obj->email 				=  Input::get('email');
								$obj->username 				=  !empty(Input::get('username'))?Input::get('username'):'';
								$obj->address				=  !empty(Input::get('address'))?Input::get('address'):'';
								$obj->country				=  !empty(Input::get('country'))?Input::get('country'):'';
								$obj->state					=  !empty(Input::get('state'))?Input::get('state'):'';
								$obj->city					=  !empty(Input::get('city'))?Input::get('city'):'';
								$obj->gender				=  !empty(Input::get('gender'))?Input::get('gender'):'';
								$obj->date_of_birth			=  !empty(Input::get('date_of_birth'))?Input::get('date_of_birth'):'';
								$obj->first_name			=  !empty(Input::get('first_name'))?Input::get('first_name'):'';
								$obj->last_name				=  !empty(Input::get('last_name'))?Input::get('last_name'):'';
								
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
									$dancerdata 	=	json_decode($formData['dancer'],true);
									foreach ($dancerdata as $dancerResult) { 
										if(!empty($dancerResult['email']) && !empty($dancerResult['username']) && !empty($dancerResult['password'])){
											if($dancerResult['id']){
												$modelDancer            			= ParentChild::find($dancerResult['id']);
											}else{
												$modelDancer            			= new ParentChild();
											}
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
											if(!empty($dancerResult['id']) && !empty($modelDancer->email) && $modelDancer->send_notification=='Yes'){
												//mail email and password to new registered user
												$settingsEmail 			=	Config::get('Site.email');
												$full_name				= 	($modelDancer->first_name.' '.$modelDancer->last_name); 
												$email					= 	$modelDancer->email;
												$route_url     			= 	URL::to('login');
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
											$checkChildExist			=	DB::table('users')
																				->orWhere("email",$dancerResult['email'])
																				->orWhere("username",$dancerResult['username'])
																				->select("id")->first();
											if(empty($checkChildExist)){
												$obj1 						= 	new User;			
												$validateString				=  md5(time() . $dancerResult['email']);	
												$obj1->validate_string		=  $validateString;		
												$fullName					=  $dancerResult['first_name']." ".$dancerResult['last_name'];	
												$obj1->first_name			=  $dancerResult['first_name'];
												$obj1->last_name			=  $dancerResult['last_name'];		
												$obj1->full_name 			=  $dancerResult['first_name']." ".$dancerResult['last_name'];
												$obj1->email 				=  $dancerResult['email'];
												$obj1->username 			=  !empty($dancerResult['username'])?$dancerResult['username']:'';
												$obj1->address				=  !empty(Input::get('address'))?Input::get('address'):'';
												$obj1->country				=  !empty(Input::get('country'))?Input::get('country'):'';
												$obj1->state					=  !empty(Input::get('state'))?Input::get('state'):'';
												$obj1->city					=  !empty(Input::get('city'))?Input::get('city'):'';
												$obj1->gender				=  !empty(Input::get('gender'))?Input::get('gender'):'';
												$obj1->date_of_birth		=  !empty(Input::get('date_of_birth'))?Input::get('date_of_birth'):'';
												$obj1->save();
											}
										}
									}
								if(!$userId) {
									DB::rollback();
									$response["status"]		=	"error";
									$response["message"]	=	"Something went wrong.";
									$response["data"]		=	array();
								}				
								$user_details		=	$this->get_user_detail_by_id($obj->id);
								$response["status"]		=	"success";
								$response["message"]	=	"Your profile has been updated successfully.";
								$response["data"]		=	$user_details;
							}
						}else{
							$response["status"]		=	"error";
							$response["message"]	=	"profile not exist.";
							$response["data"]		=	array();
						}
					}
				}
			}
			else if(!empty(Input::get('user_type')) && Input::get('user_type')==STUDIO_ROLE_ID){
				Validator::extend('without_spaces', function($attr, $value){
					return preg_match('/^\S*$/u', $value);
				});	
				$validator 	=	Validator::make(
					Input::all(),
					array(
						'full_name'			=> 	'required',
						'address' 			=> 	'required',
						'phone_number' 		=> 	'required|numeric|min:10',
						'email' 			=> 	"required|email|unique:users,phone_number,,$id,id,is_deleted,0",
						'country' 			=> 	'required',
						'state' 			=> 	'required',
						'city' 			    => 	'required',
						'zip_code' 			=> 	'required',
						'website_address' 	=> 	'required',
						'device_type'		=> 	'required',
						'device_id'			=> 	'required',
						'username' 			=> "required|min:4|without_spaces|unique:users,username,$id,id,is_deleted,0",
						'password'			=> 'required|min:8',
						'confirm_password'  => 'required|min:8|same:password', 
					),
					array(
						"username.min"					=>	trans("The username must be at least 4 characters"),
						"username.required"				=>	trans("The username field is required"),
						"username.without_spaces"		=>	trans("Username field not allowing spaces"),
						//"username.diff_username"		=>	trans("Username should only be allowed to have Letters and Numbers."),
						"username.unique"				=>	trans("Username already exist.")
					)
				);
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$obj 					=  User::find($id);
					
					$fullName				=  !empty(Input::get('first_name')) ? (Input::get('first_name')).' '.(Input::get('last_name')) : Input::get('full_name');
					
					$obj->full_name 		=  $fullName;
					$obj->email 			=  Input::get('email');
					$obj->username 			=  !empty(Input::get('username'))?Input::get('username'):'';
					$obj->address			=  !empty(Input::get('address'))?Input::get('address'):'';
					$obj->country			=  !empty(Input::get('country'))?Input::get('country'):'';
					$obj->state				=  !empty(Input::get('state'))?Input::get('state'):'';
					$obj->city				=  !empty(Input::get('city'))?Input::get('city'):'';
					$obj->date_of_birth		=  !empty(Input::get('date_of_birth'))?Input::get('date_of_birth'):'';
					$obj->first_name		=  !empty(Input::get('first_name'))?Input::get('first_name'):Input::get('full_name');
					$obj->last_name			=  !empty(Input::get('last_name'))?Input::get('last_name'):'';
					$obj->website_address	=  !empty(Input::get('website_address'))?Input::get('website_address'):'';
					$obj->zip_code			=  !empty(Input::get('zip_code'))?Input::get('zip_code'):'';
					$obj->how_many_dancers_train_monthly	=  !empty(Input::get('how_many_dancers_train_monthly'))?Input::get('how_many_dancers_train_monthly'):0;
					if(input::hasFile('profile_image')){
						$extension 			=	 Input::file('profile_image')->getClientOriginalExtension();
						$fileName			=	time().'-user-image.'.$extension;
						if(Input::file('profile_image')->move(USER_PROFILE_IMAGE_ROOT_PATH, $fileName)){
							$obj->image		=	$fileName;
						}
					}
					$obj->save();
					$userId					=	$obj->id;	
					if(!$userId) {
						DB::rollback();
						$response["status"]		=	"error";
						$response["message"]	=	"Something went wrong.";
						$response["data"]		=	array();
					}	
					$user_details		=	$this->get_user_detail_by_id($obj->id);
					$response["status"]		=	"verify";
					$response["message"]	=	"Your profile has been updated successfully.";
					$response["data"]		=	$user_details;
				}
			}
			else if(!empty(Input::get('user_type')) && Input::get('user_type')==FAN_ROLE_ID){
				Validator::extend('without_spaces', function($attr, $value){
					return preg_match('/^\S*$/u', $value);
				});	
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'device_type'			=> 'required',
						'device_id'				=> 'required',
						'full_name'				=> 'required',
						//'last_name'				=> 'required',
						'email' 				=> "required|email|unique:users,email,$id,id,is_deleted,0",
						'country' 			    => 'required',
						'state' 			    => 'required',
						'city' 			    	=> 'required',
						'gender' 			    => 'required',
						'date_of_birth' 		=> 'required',
						'user_type'				=> 'required',
						//'image' 				=> 'mimes:'.IMAGE_EXTENSION,
						//'username' 				=> "required|min:4|without_spaces|unique:users,username,$id,id,is_deleted,0",
						//'password'				=> 'required|min:8',
						//'confirm_password'  	=> 'required|min:8|same:password', 
					),
					array(
						"username.min"					=>	trans("The username must be at least 4 characters"),
						"username.required"				=>	trans("The username field is required"),
						"username.without_spaces"		=>	trans("Username field not allowing spaces"),
						//"username.diff_username"		=>	trans("Username should only be allowed to have Letters and Numbers."),
						"username.unique"				=>	trans("Username already exist.")
					)
				);
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$obj 					=  User::find($id);
					
					$fullName				=  !empty(Input::get('first_name')) ? (Input::get('first_name')).' '.(Input::get('last_name')) : Input::get('full_name');
					$obj->full_name 		=  !empty(Input::get('full_name'))?Input::get('full_name'):'';
					$obj->email 			=  Input::get('email');
					//$obj->username 			=  !empty(Input::get('username'))?Input::get('username'):'';
					$obj->country			=  !empty(Input::get('country'))?Input::get('country'):'';
					$obj->state				=  !empty(Input::get('state'))?Input::get('state'):'';
					$obj->city				=  !empty(Input::get('city'))?Input::get('city'):'';
					$obj->gender			=  !empty(Input::get('gender'))?Input::get('gender'):'';
					$obj->date_of_birth		=  !empty(Input::get('date_of_birth'))?Input::get('date_of_birth'):'';
					//$obj->first_name		=  !empty(Input::get('first_name'))?Input::get('first_name'):'';
					//$obj->last_name			=  !empty(Input::get('last_name'))?Input::get('last_name'):'';
					if(input::hasFile('profile_image')){
						$extension 			=	 Input::file('profile_image')->getClientOriginalExtension();
						$fileName			=	time().'-user-image.'.$extension;
						if(Input::file('profile_image')->move(USER_PROFILE_IMAGE_ROOT_PATH, $fileName)){
							$obj->image		=	$fileName;
						}
					}
					$obj->save();
					$userId					=	$obj->id;	
					
					if(!$userId) {
						DB::rollback();
						$response["status"]		=	"error";
						$response["message"]	=	"Something went wrong.";
						$response["data"]		=	array();
					}				
					$user_details			=	$this->get_user_detail_by_id($obj->id);
					
					$response["status"]		=	"success";
					$response["message"]	=	"Your profile has been updated successfully.";
					$response["data"]		=	$user_details;
				}
			}else{
				$response["status"]		=	"error";
				$response["message"]	=	"Invalid Request.";
				$response["data"]		=	array();
			}
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	
	public function getUserProfile(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'			=> 	'required',
					'device_id'				=> 	'required',
					'login_user_id'			=> 	'required',
					'user_id'				=> 	'required'
				)
			);
			if($validator->fails()){
				$response		=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id 		= 	Input::get("user_id");
				$login_user_id 	= 	Input::get("login_user_id");
				$userDetail		=	$this->get_user_detail_by_id($user_id);
				if(!empty($userDetail)){
					$friendRequest	 =  DB::table('friend_requests')
											->where(function ($query) use($user_id,$login_user_id){
												$query->orWhere(function ($query) use($user_id,$login_user_id){
													$query->where("user_id",$user_id);
													$query->where("friend_id",$login_user_id);
												});
												$query->orWhere(function ($query) use($user_id,$login_user_id){
													$query->where("friend_id",$user_id);
													$query->where("user_id",$login_user_id);
												});
											})
											->select(
												'id','user_id','friend_id','is_accept',
												DB::raw("IF((user_id != $user_id),(SELECT full_name FROM users WHERE id = friend_requests.user_id LIMIT 1),(SELECT full_name FROM users WHERE id = friend_requests.friend_id LIMIT 1)) as full_name"),
												DB::raw("IF((user_id != $user_id),(SELECT image FROM users WHERE id = friend_requests.user_id LIMIT 1),(SELECT image FROM users WHERE id = friend_requests.friend_id LIMIT 1)) as image")
											)
											->first();
					$userDetail->friend_status 		= '';
					$userDetail->friend_request_id 	= '';
					if(!empty($friendRequest)){
						$userDetail->friend_request_id = $friendRequest->id;
						if($friendRequest->user_id == $login_user_id){
							$userDetail->friend_status =  ($friendRequest->is_accept == 0) ? 'pending':'accepted';
						}else{
							$userDetail->friend_status =  ($friendRequest->is_accept == 0) ? 'respond':'accepted';
						}
					}
					$response["status"]				=	"success";
					$response["message"]			=	"User Profile";
					$response["data"]				=	$userDetail;
					$response["friend_request"]		=	$friendRequest;
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
				}
			}
		}
		return json_encode($response);
	}
	
	public function getQRCode(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'			=> 	'required',
					'device_id'				=> 	'required',
					'username'				=> 	'required',
				)
			);
			if($validator->fails()){
				$response		=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$username 		= 	Input::get("username");
				$userDetail = DB::table("users")->where('username',$username)->where('is_active',1)->where('is_deleted',0)->first();
				if(!empty($userDetail)){
					$result = $this->createQRCode($username);
					$response["status"]		=	"success";
					$response["message"]	=	"QR code generated";
					$response["data"]		=	$result;
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"User does not exist.";
					$response["data"]		=	array();
				}
			}
		}
		return json_encode($response);
	}
	
	public function getUserProfileFromUsername(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'			=> 	'required',
					'device_id'				=> 	'required',
					'username'				=> 	'required',
					'user_id'				=> 	'required'
				)
			);
			if($validator->fails()){
				$response		=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$username 		= 	Input::get("username");
				$login_user_id 	= 	Input::get("user_id");
				$detail 		= 	DB::table("users")->where('username',$username)->where('is_active',1)->where('is_deleted',0)->select('id')->first();
				if(!empty($detail)){
					$userDetail		=	$this->get_user_detail_by_id($detail->id);
					if(!empty($userDetail)){
						$user_id = $userDetail->id;
						$friendRequest	 =  DB::table('friend_requests')
												->where(function ($query) use($user_id){
													$query->orWhere(function ($query) use($user_id){
														$query->where("user_id",$user_id);
													});
													$query->orWhere(function ($query) use($user_id){
														$query->where("friend_id",$user_id);
													});
												})
												->select(
													'id','user_id','friend_id','is_accept',
													DB::raw("IF((user_id != $user_id),(SELECT full_name FROM users WHERE id = friend_requests.user_id LIMIT 1),(SELECT full_name FROM users WHERE id = friend_requests.friend_id LIMIT 1)) as full_name"),
													DB::raw("IF((user_id != $user_id),(SELECT image FROM users WHERE id = friend_requests.user_id LIMIT 1),(SELECT image FROM users WHERE id = friend_requests.friend_id LIMIT 1)) as image")
												)
												->first();
						if(!empty($friendRequest)){
							$friendRequest->friend_request_id = $friendRequest->id;
							if($friendRequest->user_id == $login_user_id){
								$friendRequest->friend_status =  ($friendRequest->is_accept == 0) ? 'pending':'accepted';
							}else{
								$friendRequest->friend_status =  ($friendRequest->is_accept == 0) ? 'respond':'accepted';
							}
						}else{
							$userDetail->friend_status	=	'';
							$userDetail->friend_request_id	=	'';
						}
						$response["status"]				=	"success";
						$response["message"]			=	"User Profile";
						$response["data"]				=	$userDetail;
						$response["friend_request"]		=	$friendRequest;
					}else{
						$response["status"]		=	"error";
						$response["message"]	=	"User does not exist.";
						$response["data"]		=	array();
					}
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"User does not exist.";
					$response["data"]		=	array();
				}
			}
		}
		return json_encode($response);
	}
	
	public function saveUserProfilePicture(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'			=> 	'required',
					'device_id'				=> 	'required',
					'profile_image'			=> 	'required',
					'user_id'				=> 	'required'
				)
			);
			if($validator->fails()){
				$response		=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$username 		= 	Input::get("username");
				$login_user_id 	= 	Input::get("user_id");
				$detail 		= 	DB::table("users")->where('id',$login_user_id)->where('is_active',1)->where('is_deleted',0)->select('id')->first();
				if(!empty($detail)){
					$user_id 				= $detail->id;
					$obj 					=  User::find($user_id);
					if(input::hasFile('profile_image')){
						$extension 			=	 $formData['profile_image']->getClientOriginalExtension();
						$fileName			=	time().'-user-image.'.$extension;
						if($formData['profile_image']->move(USER_PROFILE_IMAGE_ROOT_PATH, $fileName)){
							$obj->image		=	$fileName;
						}
					}
					$obj->save();
					$userId			=	$obj->id;	
					$userDetail		=	$this->get_user_profile_picture_by_id($userId);
					$response["status"]				=	"success";
					$response["message"]			=	"Profile picture updated.";
					$response["data"]				=	$userDetail;
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"User does not exist.";
					$response["data"]		=	array();
				}
			}
		}
		return json_encode($response);
	}
	
	
	public function update_privacy_setting(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'			=> 	'required',
					'device_id'				=> 	'required',
					'user_id'				=> 	'required',
					'user_role_id'			=> 	'required',
					'notification_type'		=> 	'required',
					'notication_status'		=> 	'required'
				)
			);
			if($validator->fails()){
				$response		=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$type = Input::get("notification_type");
				$getSetting = DB::table("user_privacy_settings")->where("user_id",Input::get("user_id"))->where("user_role_id",Input::get("user_role_id"))->first();
				if(empty($getSetting)){
					$obj	=	new UserPrivacySetting;
				}else{
					$obj	=	UserPrivacySetting::find($getSetting->id);
				}
				$obj->user_id			=	Input::get('user_id');
				$obj->user_role_id		=	Input::get('user_role_id');
				if($type == "notification"){
					$obj->notification	=	Input::get("notication_status");
				}elseif($type == "posts"){
					$obj->posts				=	Input::get("notication_status");
					$obj->update_datetime	=	date("Y-m-d H:i:s");
				}elseif($type == "sharing_content"){
					$obj->disable_sharing_content	=	Input::get("notication_status");
				}
				$obj->save();
				$response["status"]				=	"success";
				$response["message"]			=	"Privacy settings updated successfully.";
				$response["data"]				=	'';
			}
		}
		return json_encode($response);
	}
	
	public function get_privacy_setting(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'			=> 	'required',
					'device_id'				=> 	'required',
					'user_id'				=> 	'required',
					'user_role_id'			=> 	'required'
				)
			);
			if($validator->fails()){
				$response		=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$getSetting = DB::table("user_privacy_settings")->where("user_id",Input::get("user_id"))->where("user_role_id",Input::get("user_role_id"))->select("notification","posts",'disable_sharing_content')->first();
				$response["status"]				=	"success";
				$response["message"]			=	"Privacy settings.";
				$response["data"]				=	$getSetting;
			}
		}
		return json_encode($response);
	}
	
	
	public function my_non_blocked_friends(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'		=> 'required',
					'device_id'			=> 'required',
					'user_id'			=> 'required',
					'user_role_id'		=> 'required',
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id 		= 	$formData['user_id'];
				$userRoleId 	= 	$formData['user_role_id'];
				$deletedUsers 	= 	DB::table("users")->Orwhere('is_active',0)->Orwhere('is_deleted',1)->pluck('id','id')->toArray();
				$blockUsers   	= 	DB::table("block_friends")->where('user_id',$user_id)->where('user_role_id',$userRoleId)->pluck('block_user_id','block_user_id')->toArray();
				$dancers	 =  DB::table('friend_requests')
								->where('is_accept',1)
								->where(function ($query) use($user_id){
									$query->Orwhere("friend_requests.user_id",$user_id);
									$query->Orwhere("friend_requests.friend_id",$user_id);
								})
								->whereNotIn("friend_requests.user_id",array_merge($blockUsers,$deletedUsers))
								->whereNotIn("friend_requests.friend_id",array_merge($blockUsers,$deletedUsers))
								->select(
									'id','user_id','friend_id',
									DB::raw("IF((user_id != $user_id),(SELECT full_name FROM users WHERE id = friend_requests.user_id LIMIT 1),(SELECT full_name FROM users WHERE id = friend_requests.friend_id LIMIT 1)) as full_name"),
									DB::raw("IF((user_id != $user_id),(SELECT image FROM users WHERE id = friend_requests.user_id LIMIT 1),(SELECT image FROM users WHERE id = friend_requests.friend_id LIMIT 1)) as image")
								)
								->orderBy('created_at','DESC')
								->get();
								
				if(!empty($dancers)){
					foreach($dancers as &$friend){
						if($formData['user_id'] != $friend->user_id){
							$friendId			=	$friend->user_id;
							$friend->user_id	=	$friend->friend_id;
							$friend->friend_id	=	$friendId;
						}
						if($friend->image != '' && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$friend->image)){
							$friend->image		=	USER_PROFILE_IMAGE_URL.$friend->image;
						}else {
							$friend->image		=	WEBSITE_IMG_URL.'usr_img.png';
						}
					}							
				}
				
				$fans	 =  DB::table('follow_requests')
								->where('is_accept',1)
								->where(function ($query) use($user_id){
									$query->Orwhere("follow_requests.user_id",$user_id);
									$query->Orwhere("follow_requests.friend_id",$user_id);
								})
								->whereNotIn("follow_requests.user_id",array_merge($blockUsers,$deletedUsers))
								->whereNotIn("follow_requests.friend_id",array_merge($blockUsers,$deletedUsers))
								->select(
									'id','user_id','friend_id',
									DB::raw("IF((user_id != $user_id),(SELECT full_name FROM users WHERE id = follow_requests.user_id LIMIT 1),(SELECT full_name FROM users WHERE id = follow_requests.friend_id LIMIT 1)) as full_name"),
									DB::raw("IF((user_id != $user_id),(SELECT image FROM users WHERE id = follow_requests.user_id LIMIT 1),(SELECT image FROM users WHERE id = follow_requests.friend_id LIMIT 1)) as image")
								)
								->orderBy('created_at','DESC')
								->get();
								
				if(!empty($fans)){
					foreach($fans as &$fan){
						if($formData['user_id'] != $fan->user_id){
							$friendId			=	$fan->user_id;
							$fan->user_id	=	$fan->friend_id;
							$fan->friend_id	=	$friendId;
						}
						if($fan->image != '' && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$fan->image)){
							$fan->image		=	USER_PROFILE_IMAGE_URL.$fan->image;
						}else{
							$fan->image		=	WEBSITE_IMG_URL.'usr_img.png';
						}
					}
				}
				$response["status"]				=	"success";
				$response["message"]			=	"My Friends";
				$response["dancers"]			=	$dancers;
				$response["fans"]				=	$fans;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function block_friend(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'				=> 	'required',
					'device_id'					=> 	'required',
					'user_id'					=> 	'required',
					'user_role_id'				=> 	'required',
					'block_user_id'				=> 	'required',
					'block_user_role_id'		=> 	'required'
				)
			);
			if($validator->fails()){
				$response		=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$getSetting = DB::table("block_friends")
								->where("user_id",Input::get("user_id"))
								->where("user_role_id",Input::get("user_role_id"))
								->where("block_user_id",Input::get("block_user_id"))
								->where("block_user_role_id",Input::get("block_user_role_id"))
								->first();
				if(empty($getSetting)){
					$obj  						= 	new BlockFriend;
					$obj->user_id 				= 	Input::get("user_id");
					$obj->user_role_id 			= 	Input::get("user_role_id");
					$obj->block_user_id 		= 	Input::get("block_user_id");
					$obj->block_user_role_id 	= 	Input::get("block_user_role_id");
					$obj->save();
				}
				$response["status"]				=	"success";
				$response["message"]			=	"Friend has been blocked successfully.";
				$response["data"]				=	'';
			}
		}
		return json_encode($response);
	}
	
	public function unblock_friend(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'				=> 	'required',
					'device_id'					=> 	'required',
					'user_id'					=> 	'required',
					'user_role_id'				=> 	'required',
					'block_user_id'				=> 	'required',
					'block_user_role_id'		=> 	'required'
				)
			);
			if($validator->fails()){
				$response		=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				DB::table("block_friends")
								->where("user_id",Input::get("user_id"))
								->where("user_role_id",Input::get("user_role_id"))
								->where("block_user_id",Input::get("block_user_id"))
								->where("block_user_role_id",Input::get("block_user_role_id"))
								->delete();
				
				$response["status"]				=	"success";
				$response["message"]			=	"Friend has been unblocked successfully.";
				$response["data"]				=	'';
			}
		}
		return json_encode($response);
	}
	
	public function blocked_friend_list(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'		=> 'required',
					'device_id'			=> 'required',
					'user_id'			=> 'required',
					'user_role_id'		=> 'required',
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id 		= 	$formData['user_id'];
				$userRoleId 	= 	$formData['user_role_id'];
				$friends	 =  DB::table('block_friends')
								->where("user_id",$user_id)
								->where("user_role_id",$userRoleId)
								->select("block_friends.*",DB::raw("(SELECT full_name FROM users WHERE users.id = block_friends.block_user_id) as full_name"),
										DB::raw("(SELECT image FROM users WHERE users.id = block_friends.block_user_id) as image"))
								->orderBy('full_name','ASC')
								->get();
								
				if(!empty($friends)){
					foreach($friends as &$friend){
						if($friend->image != '' && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$friend->image)){
							$friend->image		=	USER_PROFILE_IMAGE_URL.$friend->image;
						}else{
							$friend->image		=	WEBSITE_IMG_URL.'usr_img.png';
						}
					}							
				}
				
				$response["status"]				=	"success";
				$response["message"]			=	"My Blocked Friends";
				$response["dancers"]			=	$friends;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
}//end UsersController
