<?php
/**
 * UsersController
 */
namespace App\Http\Controllers\front;
use App\Http\Controllers\BaseController;
use App\Model\User;
use App\Model\SystemDoc;
use App\Model\Block;
use App\Model\HomeContent;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,App;

class UsersController extends BaseController {
/** 
* Function to display website home page
*
* @param null
* 
* @return view page
*/
	public function index(){
		$systemImageObj	=	new SystemDoc();
		$SYSTEM_IMAGE_IDS =	array(BG_1_IMAGE_ID,BG_2_IMAGE_ID,BG_3_IMAGE_ID,BG_4_IMAGE_ID,BG_5_IMAGE_ID,LOGO_IMAGE_ID,DANCER_PAGE_IMAGE_ID,DANCER_LOGO_IMAGE_ID,SECTION_3_IMAGE_1_IMAGE_ID,SECTION_3_IMAGE_2_IMAGE_ID,SECTION_3_IMAGE_3_IMAGE_ID,SECTION_3_IMAGE_4_IMAGE_ID,SECTION_3_IMAGE_5_IMAGE_ID,ATTACHMENT_IMAGE_1,ATTACHMENT_IMAGE_2,ATTACHMENT_IMAGE_3,ATTACHMENT_IMAGE_4,ATTACHMENT_IMAGE_5);
		$SystemImages	=	$systemImageObj->getAllSystemImages($SYSTEM_IMAGE_IDS);
		$blockObj 		= 	new Block();
		$blocks  		=	$blockObj->getAllBlock();
		$homeObj		=	new HomeContent();
		$homeContents	=	$homeObj->getHomePageContents();
		$HomePageContents		=	array();
		foreach($homeContents as $homeContent){
			$type	=	$homeContent['type'];
			$HomePageContents[$type]['content'] 	 = $homeContent['content'];
			$HomePageContents[$type]['image'] 			 = $homeContent['image'];
			$HomePageContents[$type]['type'] 	 		 = $homeContent['type'];
		}
		
		return View::make('front.user.index', compact('SystemImages','blocks','HomePageContents'));
	}//end index()
	
/** 
* Function to display website home page
*
* @param null
* 
* @return view page
*/
	public function homePageIndex(){
		$systemImageObj	=	new SystemDoc();
		$SYSTEM_IMAGE_IDS =	array(BG_1_IMAGE_ID,BG_2_IMAGE_ID,BG_3_IMAGE_ID,BG_4_IMAGE_ID,BG_5_IMAGE_ID,LOGO_IMAGE_ID,DANCER_PAGE_IMAGE_ID,DANCER_LOGO_IMAGE_ID,SECTION_3_IMAGE_1_IMAGE_ID,SECTION_3_IMAGE_2_IMAGE_ID,SECTION_3_IMAGE_3_IMAGE_ID,SECTION_3_IMAGE_4_IMAGE_ID,SECTION_3_IMAGE_5_IMAGE_ID,ATTACHMENT_IMAGE_1,ATTACHMENT_IMAGE_2,ATTACHMENT_IMAGE_3,ATTACHMENT_IMAGE_4,ATTACHMENT_IMAGE_5);
		$SystemImages	=	$systemImageObj->getAllSystemImages($SYSTEM_IMAGE_IDS);
		$blockObj 		= 	new Block();
		$blocks  		=	$blockObj->getAllBlock();
		
		return View::make('front.user.home_page_index', compact('SystemImages','blocks'));
	}//end index()
	
/** 
* Function to display sign up page
*
* @param null
* 
* @return view page
*/
	public function signupView(){
		$systemImageObj	=	new SystemDoc();
		$SYSTEM_IMAGE_IDS	=	array(BG_1_IMAGE_ID,BG_2_IMAGE_ID,BG_3_IMAGE_ID,BG_4_IMAGE_ID,BG_5_IMAGE_ID,LOGO_IMAGE_ID,DANCER_PAGE_IMAGE_ID,DANCER_LOGO_IMAGE_ID,SECTION_3_IMAGE_1_IMAGE_ID,SECTION_3_IMAGE_2_IMAGE_ID,SECTION_3_IMAGE_3_IMAGE_ID,SECTION_3_IMAGE_4_IMAGE_ID,SECTION_3_IMAGE_5_IMAGE_ID,ATTACHMENT_IMAGE_1,ATTACHMENT_IMAGE_2,ATTACHMENT_IMAGE_3,ATTACHMENT_IMAGE_4,ATTACHMENT_IMAGE_5);
		$SystemImages	=	$systemImageObj->getAllSystemImages($SYSTEM_IMAGE_IDS);
		$blockObj 		= 	new Block();
		$blocks  		=	$blockObj->getAllBlock();
		if(Auth::user()){
			return Redirect::to('/');
		}
		return View::make('front.user.signup', compact('SystemImages','blocks'));
	}
	
/** 
* Function to save user signup information
*
* @param null
* 
* @return void
*/
	public function signup(){ 
		Input::replace($this->arrayStripTags(Input::all()));
		$formData					=	Input::all();
		Validator::extend('custom_password', function($attribute, $value, $parameters) {
			if(preg_match('/^(?=.*[a-z])(?=.*\d).{8,}$/', $value)) {
				return true;
			} else {
				return false;
			}
		});
		Validator::extend('diff_username', function($attribute, $value, $parameters) {
			if(preg_match('/^[a-zA-Z0-9\p{L}\s]+$/u', $value)) {
				return true;
			} else {
				return false;
			}
		});
		Validator::extend('without_spaces', function($attr, $value){
			return preg_match('/^\S*$/u', $value);
		});	
		Validator::extend('age', function($attr, $value){
			return preg_match("/^(?:[1-9]\d{2,}+|[2-9]\d|1[89])$", $value);
		});	
		Validator::extend('unique_validation', function($attribute,$value,$parameters){
			$secret	=	Config::get("Site.google_captcha_sitesecret");
			$response=$parameters[0];
			$verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
			$captcha_success=json_decode($verify);
			if ($captcha_success->success==false){
				return false;
			}else{
				return true;
			}
		});
		$response=Input::get('g-recaptcha-response');
		
		
		
		Validator::extend('without_spaces', function($attr, $value){
			return preg_match('/^\S*$/u', $value);
		});	
		
		Validator::extend('diff_username', function($attribute, $value, $parameters) {
			if(preg_match('/^[a-zA-Z0-9\p{L}\s]+$/u', $value)) {
				return true;
			} else {
				return false;
			}
		});
		
		
		$validator = Validator::make(
			Input::all(),
			array(
				'email' 					=> 	'required|email|unique:users',
				'full_name' 				=>	'required',
				'phone_number'					=> "required|numeric|min:10",
				'username' 					=>	'required|min:4|without_spaces|unique:users|diff_username',
				'password'					=> 	'required|min:8|custom_password',
				'confirm_password'  		=>	'required|same:password', 	
			),
			array(
				"username.min"					=>	trans("The username must be at least 4 characters"),
				"username.required"				=>	trans("The username field is required"),
				"username.without_spaces"		=>	trans("Username field not allowing spaces"),
				"username.diff_username"		=>	trans("Username should only be allowed to have Letters and Numbers."),
				"username.unique"				=>	trans("Username already exist."),
				'email.required' 							=>  trans('The email is required'),
				'email.email' 								=>  trans('The email must be valid'),
				'email.unique'								=>  trans('The email must be unique'),
				"password.min"								=>	trans("The password atleast eight characters. "),
				"password.required"							=>	trans("The password field is required"),
				"password.custom_password"					=>	trans("Password must have be a combination of numeric, alphabet and special characters."),
				"confirm_password.required"					=>	trans("The confirm password is required"),
				"confirm_password.same"						=>	trans("Password and confirm password does not match"),
			)
		);
		
		if($validator->fails()){
			$response				=	array(
				'success' 			=> 	false,
				'errors' 			=> 	$validator->errors()
			);
			return Response::json($response); 
			die;
		}else {
			$obj 					=  new User;
			$validateString			=  md5(time() . Input::get('email'));
			$obj->validate_string	=  $validateString;					
			$obj->username 			=  Input::get('username');
			$obj->full_name 		=  Input::get('full_name');
			$obj->phone_number 		=  Input::get('phone_number');
			$obj->email 			=  Input::get('email');
			$obj->slug	 			=  $this->getSlug(Input::get('full_name'),'full_name','User');
			$obj->password	 		=  Hash::make(Input::get('password'));
			$obj->user_role_id		=  FRONT_USER_ROLE_ID;
			$obj->is_verified		=  0; 
			$obj->is_active			=  1;
			$obj->is_approved		=  1; 
			$obj->save();
			$userId					=  $obj->id;
			
			//Send Verification Email
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
			
			Session::flash('flash_notice',  trans("Your account has registered successfully. Please check your email for verify your account."));
			$response		=	array(
				'success' 	=>	'1',
				'errors' 	=>	trans("Your account has registered successfully. Please check your email for verify your account.")
			);
			return Response::json($response); 
			die;	
		}
		//}
	}//end sign up
	
/** 
* Function to display sign up page
*
* @param null
* 
* @return view page
*/
	public function loginview(){
		if(Auth::user()){
			return Redirect::to('/');
		}
		return View::make('front.user.login');
	}
	
/** 
 * Function use for login user
 *
 * @param null
 * 
 * @return void
 */
	public function login() {
		//Input::replace($this->arrayStripTags(Input::all()));
		$formData	=	Input::all();
			$validator = Validator::make(
				Input::all(),
				array(
					'email' 						=> 'required',
					'password'						=> 'required',
				),array(
					"email.required"				=>	trans("The username field is required"),
					"password.required"			=>	trans("The password field is required"),
				)
			);
		if($validator->fails()){
			$response				=	array(
				'success' 			=> 	false,
				'errors' 			=> 	$validator->errors()
			);
			return Response::json($response); 
			die;
		}else {
			$username		=	Input::get('email');
			$userData		=	DB::table('users')->where(function ($query) use ($username) {
											$query->orWhere('username',$username);
											$query->orWhere('email',$username);
										})/* ->where('is_active',1)->where('is_verified',1) */->where('is_deleted',0)->first();
			if(!empty($userData)){
				$userData		=	json_decode(json_encode($userData,true),true);
				if($userData['is_active'] == 0) {
					$err				=	array();
					$err['success']		=	2;
					$err['message']		=	trans('Your account is inactive please contact to admin');
					Session::flash('error',  trans("Your account is inactive please contact to admin"));  
					return Response::json($err); 
				}else if($userData['is_verified'] == 0) {
					Session::flash('error',  trans("Your account is unverified please verified your account"));  
					$route_url    = URL::to('send-verifylink-again/'.$userData['validate_string']);
					$verification_url = "<a href='".$route_url."'>Click here</a>";
					$err				=	array();
					$err['success']		=	2;
					$err['message']		=	trans("Your account is unverified. Please verify your account ".$verification_url." .");
					return Response::json($err); 
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
						if(Auth::user()->user_role_id != SUPER_ADMIN_ROLE_ID){
							$err				=	array();
							$err['success']		=	1;
							$err['message']		=	"";
							return Response::json($err); 
							die;
						}else{
							Auth::logout();
							$err						=	array();
							$err['success']				=	2;
							$err['message']				=	trans("Email or password is incorrect.");
							return Response::json($err); 
							die;
						}
					}else{
						Session::flash('error',  trans("Username or password is incorrect"));  
						$err				=	array();
						$err['success']		=	2;
						$err['message']		=	trans('Username or password is incorrect');
						return Response::json($err); 
					}
				}
			}else{
				Session::flash('error',  trans("Your account is not registered with CMEShine."));  
				$err				=	array();
				$err['success']		=	2;
				$err['message']		=	trans('Your account is not registered with CMEShine.');
				return Response::json($err); 
			}
			die;
		}
	}

/** 
 * Function use for logout user
 *
 * @param null
 * 
 * @return void
 */
	public function logout(){
		//Session::forget('login');
		Auth::logout();
		//Session::flash('flash_notice', 'You are now logged out!'); 
		return Redirect::to('/');
	}

/** 
 * Function use for send a forgot password email to user
 *
 * @param null
 * 
 * @return void
 */
	public function view_forgot_password(){
		if(Auth::user()){
			return Redirect::to('/');
		}
		return View::make("front.user.forgot_password");
	}	
/** 
 * Function use for display forgot password page
 *
 * @param null
 * 
 * @return void
 */
	public function forgot_password(){
		$formData	=	Input::all();
		if(!empty($formData)){
			$validator 							= 	Validator::make(
				Input::all(),
				array(
					'forgot_email' 				=> 'required|email',
				)
			);
			
			if ($validator->fails()){		
				$response				=	array(
					'success' 			=> 	3,
					'errors' 			=> 	$validator->errors()
				);
				return Response::json($response); 
				die;
			}else{
				$email							=	Input::get('forgot_email');   
				$userDetail						=	User::where('email',$email)
														->where('is_active','=',1)
														->where('is_verified','=',1)
														->first();	
				if(!empty($userDetail)){
					$forgot_password_validate_string	= 	md5($userDetail->email);
					User::where('email',$email)->update(array('forgot_password_validate_string'=>$forgot_password_validate_string));
					
					$settingsEmail 				=  Config::get('Site.email');
					$email 						=  $userDetail->email;
					$username					=  $userDetail->full_name;
					$full_name					=  $userDetail->full_name;  
					$route_url      			=  URL::to('reset-password/'.$forgot_password_validate_string);
					$varify_link   				=   $route_url;
					$emailActions				=	EmailAction::where('action','=','forgot_password')->get();
					
					$emailTemplates				=	EmailTemplate::where('action','=','forgot_password')->get(array('name','subject','action','body'))->toArray();
					
					$cons 						= 	explode(',',$emailActions[0]['options']);
					$constants 					= 	array();
					foreach($cons as $key=>$val){
						$constants[] 			= '{'.$val.'}';
						
					}
					
					$subject 					=  $emailTemplates[0]['subject'];
					$rep_Array 					= 	array($username,$varify_link,$route_url); 
					$messageBody				=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
					//print_r($messageBody);die;
					$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
					Session::flash('flash_notice',  trans("You forgot password email has been send successfully."));  
					$err						=	array();
					$err['success']				=	1;
					$err['message']				=	'You forgot password email has been send successfully.';
					return Response::json($err); 
				}else{
					$response					=	array(
						'success' 				=> 	2,
						'message' 				=> 	"Your email is not registered with ".Config::get("Site.title")."."
					);
					return Response::json($response); 
					die;
				}		
			}
		}
	}
	
	
/** 
 * Function to verify user account
 *
 * @param $validateString for get user validate string
 * 
 * @return void
 */
	public function Verify($validateString = '') {
		if($validateString!="" && $validateString!=null){
			$userDetail				=	User::where('is_active','1')->where('validate_string',$validateString)->first();
			if(!empty($userDetail)){
				User::where('validate_string',$validateString)->update(array('validate_string'=>'',
				'is_verified'=>1));
				$emailActions		=  EmailAction::where('action','=','thanks_for_verify')->get()->first();
				$emailTemplates		=  EmailTemplate::where('action','=','thanks_for_verify')->get()->first();
				$cons 				=  explode(',',$emailActions['options']);
				$constants 			=  array();
				foreach($cons as $key=>$val){
					$constants[] 	= '{'.$val.'}';
				}
				$email				=	$userDetail->email;
				$full_name			=	$userDetail->full_name;
				$subject 			=  	$emailTemplates['subject'];
				$rep_Array 			=  	array($full_name);
				$messageBody		=  	str_replace($constants, $rep_Array, $emailTemplates['body']);
				$settingsEmail 		= 	Config::get("Site.email");
				$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
				return Redirect::to('/')
						->with('success', trans('Your account verified successfully'));
			}else{
				return Redirect::to('/')
						->with('error', trans('Sorry, you are using wrong link.'));
			}
		}else{
			return Redirect::to('/')->with('error', trans('Sorry, you are using wrong link.'));
		}
	}
		/** 
* Function use for reset passowrd
* @param null
* 
* @return void
*/	
	public function resetPassword($validateString ='' ){
		if($validateString!="" && $validateString!=null){
			$userDetail	=	User::where('is_active','1')->where('forgot_password_validate_string',$validateString)->first();
			if(!empty($userDetail)){
				return View::make('front.user.resetpassword',compact('validateString'));
			}else{
				return Redirect::to('/')
						->with('error', trans('Sorry, you are using wrong link.'));
			}
		}else{
			return Redirect::to('/')->with('error', trans('Sorry, you are using wrong link.'));
		}
	}//end resetPassword()
/** 
* Function use for save password
*
* @param null
* 
* @return void
*/
	public function saveResetPassword(){
		$newPassword		=	Input::get('new_password');
		$validate_string	=	Input::get('validate_string');
		Validator::extend('custom_password', function($attribute, $value, $parameters) {
			if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
				return true;
			} else {
				return false;
			}
		});
		$rules    = 	array(
			'new_password'		=>	'required|min:8|custom_password',
			'confirm_password'  => 	'required|same:new_password', 
		);
		$validator 				= 	Validator::make(Input::all(), $rules,
		array(
			"new_password.custom_password"	=>	"Password must have combination of numeric, alphabet and special characters.",
		));
		
		if ($validator->fails()){	
			$response	=	array(
				'success' 	=> false,
				'errors' 	=> $validator->errors()
			);
			return Response::json($response); 
			die;
		}else{
			$userInfo = User::where('forgot_password_validate_string',$validate_string)->first();
		
			User::where('forgot_password_validate_string',$validate_string)
				->update(array(
						'password'							=>	Hash::make($newPassword),
						'forgot_password_validate_string'	=>	''
				));
			$settingsEmail 		= 	Config::get('Site.email');			
			$action				= 	"reset_password";
			
			$emailActions		=	EmailAction::where('action','=','reset_password')->get()->first();
			$emailTemplates		=	EmailTemplate::where('action','=','reset_password')->get(array('name','subject','action','body'))->first();
			$cons 				= 	explode(',',$emailActions['options']);
			$constants 			= 	array();
			foreach($cons as $key=>$val){
				$constants[] 	= 	'{'.$val.'}';
			}
			
			$subject 			=  	$emailTemplates['subject'];
			$rep_Array 			= 	array($userInfo->full_name); 
			$messageBody		=  	str_replace($constants, $rep_Array, $emailTemplates['body']);
									 
			$this->sendMail($userInfo->email,$userInfo->full_name,$subject,$messageBody,$settingsEmail);
			$response	=	array(
				'success' 	=> true,
			);
			Session::flash('flash_notice', trans("Password Reset successfully")); 
			return Response::json($response); 
			die;
		}
	}//end saveResetPassword()
			/** 
 * Function to display signup page
 *
 * @param null
 * 
 * @return view page
 */
	public function sendVerifylinkAgain($validateString=''){
		if($validateString != null){
			$userDetail	=	DB::table('users')->where('validate_string',$validateString)->first();
			if(!empty($userDetail)){
				//mail email and password to new registered user
				$settingsEmail 						= Config::get('Site.email');
				$full_name							= $userDetail->full_name; 
				$email								= $userDetail->email;
				$password							='';
				$route_url      					= URL::to('account-verification/'.$validateString);
				$select_url    						= "<a href='".$route_url."'>Click here</a>";
				
				$emailActions						= EmailAction::where('action','=','account_verification')->get()->toArray();
				$emailTemplates						= EmailTemplate::where('action','=','account_verification')->get(array('name','subject','action','body'))->toArray();
				
				$cons 								= explode(',',$emailActions[0]['options']);
				$constants 							= array();
				
				foreach($cons as $key => $val){
					$constants[] 					= '{'.$val.'}';
				}
				
				$subject 							= $emailTemplates[0]['subject'];
				$rep_Array 							= array($full_name,$select_url,$route_url); 
				$messageBody						= str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				$mail								= $this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
				
				$route_url    = URL::to('send-verifylink-again/'.$validateString);
				
				$verification_url = "<a href='".$route_url."'>Click here</a>";
				//print_r($verification_url);die;
				return Redirect::to('login')->with("login_message",trans("A verification email has been sent to you. Please check your inbox.")); 
			}
		}
	}//end sendVerifylinkAgain
	
	
}// end UsersController class
