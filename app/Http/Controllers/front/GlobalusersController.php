<?php
/**
 * Globalusers Controller
 */
namespace App\Http\Controllers\front;
use App\Http\Controllers\BaseController;
use App\Model\EmailAction;
use App\Model\User;
use App\Model\SystemDoc;
use App,Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

class GlobalusersController extends BaseController {

/** 
 * Function to display dashboard
 *
 * @param null
 * 
 * @return view page
 */
	public function dashboard(){
		$systemImageObj	=	new SystemDoc();
		$systemImage	=	$systemImageObj->getSystemImage(DASHBOARD_PAGE_IMAGE_ID);
		return View::make('front.globaluser.dashboard',compact("upcoming_transactions","systemImage"));
	} //end dashboard()
	
	
	public function saveChangePassword(){
		Input::replace($this->arrayStripTags(Input::all()));
		$formData			=	Input::all();
		$login_user		 	= 	Auth::user();
		$model_id		 	=   $login_user->id;
		$old_password    	= 	Input::get('old_password');
        $password         	= 	Input::get('new_password');
		Validator::extend('custom_password', function($attribute, $value, $parameters) {
			if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value)/*  && preg_match('#[\W]#', $value) */) {
				return true;
			} else {
				return false;
			}
		});
		$rules    = 	array(
			'old_password' 		=>	'required',
			'new_password'		=>	'required|min:8|custom_password',
			'confirm_password'  =>	'required|same:new_password', 
		);
		
		$validator 				= 	Validator::make(Input::all(), $rules,
		array(
			"old_password.required"			=>	trans("Old password is required."),
			"new_password.required"			=>	trans("New password is required."),
			"new_password.min"				=>	trans("New password must be at least 8 characters."),
			"new_password.custom_password"	=>	trans("Password must have be a combination of numeric and alphabets."),
			"confirm_password.required"		=>	trans("Confirm password field is required."),
			"confirm_password.same"			=>	trans("Confirm password does not match with new password."),
		));
		if($validator->fails()){
			$errors 				=	$validator->messages();
		}
		if($validator->fails()){
			$response	=	array(
				'success' 	=> false,
				'errors' 	=> $errors
			);
			return Response::json($response); 
			die;
		}else{
			$obj 					=  User::find($model_id);
			$old_password 			= Input::get('old_password'); 
			$password 				= Input::get('new_password');
			if(Hash::check($old_password, $obj->getAuthPassword())){
				$obj->password = Hash::make($password);
				if($obj->save()){
					$data					=	array();
					$data['success']			=	true;
					Session::flash('flash_notice', trans("Password changed successfully.")); 
					return Response::json($data); 
					die;
				}
			}else{
				$err							=	array();
				$err['success']					=	false;
				$err['errors']['old_password']	=	trans("Old password is incorrect.");
				return Response::json($err); 
				die;
			}
		}
	}	
	
	
}//end Class()
