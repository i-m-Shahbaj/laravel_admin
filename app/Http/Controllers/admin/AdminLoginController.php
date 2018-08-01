<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\User;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
* AdminLogin Controller
*
* Add your methods in the class below
*
* This file will render views\admin\login
*/
	class AdminLoginController extends BaseController {
		
	public $model	=	'login';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
/**
* Function for display admin  login page
*
* @param null
*
* @return view page. 
*/
	public function login(){
		Input::replace($this->arrayStripTags(Input::all()));
		if(Auth::check()){
			Return Redirect::route('dashboard.showdashboard');
		}
		if(Request::isMethod('post')){
			$formData	=	Input::all();
			if(!empty($formData)){
				if(Session::get('failed_attampt_login') >= 11) {
					$validator = Validator::make(
						Input::all(),
						array(
							'password'				=> 'required',
							'email' 			=> 'required',
							'captcha' 			=> 'required|captcha',
						),
						array(
							"captcha.captcha"	=>	"Captcha value does not match",
						)
					);
				}else {
					$validator = Validator::make(
						Input::all(),
						array(
							'password'				=> 'required',
							'email' 			=> 'required',
						)
					);
				}
				
				if ($validator->fails()){
					 return Redirect::back()->withErrors($validator)->withInput();
				}else{
					/*   $userData = array(
						'email' 		=> Input::get('email'),
						'password' 		=> Input::get('password'),
						'user_role_id' 	=> SUPER_ADMIN_ROLE_ID
					);  */ 
					$username		=	Input::get('email');
					$userData		=	DB::table('users')->where(function ($query) use ($username) {
											$query->orWhere('username',$username);
											$query->orWhere('email',$username);
										})->where('is_active',1)->where('is_verified',1)->where('is_deleted',0)->first();
					if(!empty($userData)){
						$userData		=	json_decode(json_encode($userData,true),true); 
						$userData = array(
							'email' 		=> $userData['email'],
							'password' 		=> Input::get('password'),
							'is_active' 	=> $userData['is_active'],
							'is_verified' 	=> $userData['is_verified'],
							'id' 			=> $userData['id'],
							'is_deleted' 	=> 0,
						);
						if (Auth::attempt($userData)){	
							Session::forget('failed_attampt_login');
							Session::flash('flash_notice', 'You are now logged in!');
							return Redirect::route('dashboard.showdashboard')->with('message','You are now logged in!');
						}else{
							if(Session::get('failed_attampt_login')) {
								$final_value			=	Session::get('failed_attampt_login')+1;
								Session::put('failed_attampt_login', $final_value);
							}else {
								Session::put('failed_attampt_login', 10);
							}
							Session::flash('error', 'Email/Username or Password is incorrect.');
							return Redirect::back() ->withInput();
						}
					}else{
						if(Session::get('failed_attampt_login')) {
							$final_value			=	Session::get('failed_attampt_login')+1;
							Session::put('failed_attampt_login', $final_value);
						}else {
							Session::put('failed_attampt_login', 10);
						}
						Session::flash('error', 'Email or Password is incorrect.');
						return Redirect::back() ->withInput();
					} 
				}
			}
		}else{
			return View::make('admin.'.$this->model.'.index');
		}
   }// end index()
/**
* Function for logout admin users
*
* @param null
*
* @return rerirect page. 
*/ 
	public function logout(){
		Auth::logout();
		Session::flash('flash_notice', 'You are now logged out!');
		return Redirect::route('home.logout')->with('message', 'You are now logged out!');
	}//endLogout()
/**
* Function is used to display forget password page
*
* @param null
*
* @return view page. 
*/	
	public function forgetPassword(){
		return View::make('admin.'.$this->model.'.forget_password');
	}// end forgetPassword()
/**
* Function is used for reset password
*
* @param $validate_string as validator string
*
* @return view page. 
*/		
	public function resetPassword($validate_string=null){
		Input::replace($this->arrayStripTags(Input::all()));
		if($validate_string!="" && $validate_string!=null){
			
			$userDetail	=	AdminUser::where('is_active','1')->where('forgot_password_validate_string',$validate_string)->first();
			
			if(!empty($userDetail)){
				return View::make('admin.login.reset_password' ,compact('validate_string'));
			}else{
				return Redirect::route('login.index')
						->with('error', trans('Sorry, you are using wrong link.'));
			}
			
		}else{
			return Redirect::route('login.index')->with('error', trans('Sorry, you are using wrong link.'));
		}
	}// end resetPassword()
/**
* Function is used to send email for forgot password process
*
* @param null
*
* @return url. 
*/		
	public function sendPassword(){
		Input::replace($this->arrayStripTags(Input::all()));
		$thisData				=	Input::all(); 
		Input::replace($this->arrayStripTags($thisData));
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
			return Redirect::back()
				->withErrors($validator)->withInput()->with(compact(''));
		}else{
			$email		=	Input::get('email');   
			$userDetail	=	AdminUser::where('email',$email)->where('user_role_id',SUPER_ADMIN_ROLE_ID)->first();
			if(!empty($userDetail)){
				if($userDetail->is_active == 1 ){
					if($userDetail->is_verified == 1 ){
					
						$forgot_password_validate_string	= 	md5($userDetail->email);
						AdminUser::where('email',$email)->update(array('forgot_password_validate_string'=>$forgot_password_validate_string));
						
						$settingsEmail 		=  Config::get('Site.email');
						$email 				=  $userDetail->email;
						$username			=  $userDetail->username;
						$full_name			=  $userDetail->full_name;  
						$route_url      	=  route('Home.reset_password',$forgot_password_validate_string);
						$varify_link   		=   $route_url;
						
						$emailActions		=	EmailAction::where('action','=','forgot_password')->get()->toArray();
						$emailTemplates		=	EmailTemplate::where('action','=','forgot_password')->get(array('name','subject','action','body'))->toArray();
						$cons = explode(',',$emailActions[0]['options']);
						$constants = array();
						
						foreach($cons as $key=>$val){
							$constants[] = '{'.$val.'}';
						}
						$subject 			=  $emailTemplates[0]['subject'];
						$rep_Array 			= array($username,$varify_link,$route_url); 
						$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
						
						$this->sendMail($email,$full_name,$subject,$messageBody,$settingsEmail);
						Session::flash('flash_notice', trans('An email has been sent to your inbox. To reset your password please follow the steps mentioned in the email.')); 
						return Redirect::route('login.index');	
					}else{
						return Redirect::route('login.forgetPassword')->with('error', trans('Your account has not been verified yet.'));
					}					
				}else{
					return Redirect::route('login.forgetPassword')->with('error', trans('Your account has been temporarily disabled. Please contact administrator to unlock.'));
				}	
			}else{
				return Redirect::route('login.index')->with('error', trans('Your email is not registered with '.config::get("Site.title")."."));
			}		
		}
	}// sendPassword()	
/**
* Function is used for save reset password
*
* @param $validate_string as validator string
*
* @return view page. 
*/		
	public function resetPasswordSave($validate_string=null){
		$thisData				=	Input::all(); 
		Input::replace($this->arrayStripTags($thisData));
		$newPassword		=	Input::get('new_password');
		$validate_string	=	Input::get('validate_string');
	
		$messages = array(
			'new_password.required' 				=> trans('The New Password field is required.'),
			'new_password_confirmation.required' 	=> trans('The confirm password field is required.'),
			'new_password.confirmed' 				=> trans('The confirm password must be match to new password.'),
			'new_password.min' 						=> trans('The password must be at least 8 characters.'),
			'new_password_confirmation.min' 		=> trans('The confirm password must be at least 8 characters.'),
			"new_password.custom_password"			=>	"Password must have combination of numeric, alphabet and special characters.",
		);
		
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
				'new_password'			=> 'required|min:8|custom_password',
				'new_password_confirmation' => 'required|same:new_password', 

			),$messages
		);
		if ($validator->fails()){	
			return Redirect::route('login.resetPassword'.$validate_string)
				->withErrors($validator)->withInput()->with(compact(''));
		}else{
			$userInfo = AdminUser::where('forgot_password_validate_string',$validate_string)->first();
		
			AdminUser::where('forgot_password_validate_string',$validate_string)
				->update(array(
						'password'							=>	Hash::make($newPassword),
						'forgot_password_validate_string'	=>	''
				));
			$settingsEmail 		= Config::get('Site.email');			
			$action				= "reset_password";
			
			$emailActions		=	EmailAction::where('action','=','reset_password')->get()->toArray();
			$emailTemplates		=	EmailTemplate::where('action','=','reset_password')->get(array('name','subject','action','body'))->toArray();
			$cons 				= 	explode(',',$emailActions[0]['options']);
			$constants 			= 	array();
			foreach($cons as $key=>$val){
				$constants[] = '{'.$val.'}';
			}
			
			$subject 			=  $emailTemplates[0]['subject'];
			$rep_Array 			= array($userInfo->full_name); 
			$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
									 
			$this->sendMail($userInfo->email,$userInfo->full_name,$subject,$messageBody,$settingsEmail);
			Session::flash('flash_notice', trans('Thank you for resetting your password. Please login to access your account.')); 
			
			return Redirect::route('login.index');	
		}
	}// end resetPasswordSave()
/**
* Function is used for Lock the screen 
*
* @param null
*
* @return view page. 
*/			
	/* public function LockScreen(){	
		Session::put('lock',1);
		$fullName	=	Auth::user()->full_name;
		return View::make('admin.layouts.lockscreen',compact('fullName'));
	}// end LockScreen() */
/**
* Function is used for Logged Out user
*
* @param null
*
* @return view page. 
*/			
	public function LoggedOut(){	
		Auth::logout();
		Session::flash('flash_notice', 'You are now logged out!');
		return Redirect::route('login.index')->with('message', 'You are now logged out!');
	} 
	//end LoggedOut()
}// end AdminLoginController
