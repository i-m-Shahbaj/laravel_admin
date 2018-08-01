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
 
class SubAdminUsersController extends BaseController {
	
	public $model	=	'SubAdmin';
	
	public function __construct() {
		View::share('modelName','SubAdmin');
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
									->where('users.user_role_id',SUB_ADMIN_ROLE_ID)
									->where('is_deleted',0)
									->select('users.*')
									->orderBy($sortBy, $order) 
									->paginate(Config::get("Reading.records_per_page"));

		$complete_string		=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$result->appends(Input::all())->render(); 
		return  View::make('admin.'.$this->model.'.index', compact('result' ,'searchVariable','sortBy','order','query_string'));
	}// end listUsers()

/**
* Function for add users
*
* @param null
*
* @return view page. 
*/	
	public function addUser(){ 
		return  View::make('admin.'.$this->model.'.add',compact('countryList'));
	}//end addCompany()
	  
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
				$validator 					=	Validator::make(
					Input::all(),
					array( 
						'first_name'			=> 'required',
						'last_name'			    => 'required',
						'email' 				=> 'required|email|unique:users',
						'username' 				=> 'required|unique:users',
						'password'				=> 'required|min:8',
						'confirm_password'  	=> 'required|min:8|same:password', 
					),
					array( 
					)
				); 
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
				$userRoleId				=  SUB_ADMIN_ROLE_ID ;
				$fullName				=  !empty(Input::get('first_name')) ? (Input::get('first_name')).' '.(Input::get('last_name')) : Input::get('full_name');
				$obj 					=  new User;
				$validateString			=  md5(time() . Input::get('email'));
				$obj->validate_string	=  $validateString;				
				$obj->first_name 		= Input::get('first_name');
				$obj->last_name 		= Input::get('last_name');
				$obj->full_name 		=  $fullName;
				$obj->email 			=  Input::get('email');
				$obj->username 			=  !empty(Input::get('username'))?Input::get('username'):'';
				$obj->slug	 			=  $this->getSlug($fullName,'full_name','User');
				$obj->password	 		=  !empty(Input::get('username'))?Hash::make(Input::get('password')):'';
				$obj->user_role_id		=  $userRoleId;
				 
				$obj->is_verified		=  1; 
				$obj->is_active			=  1; 
				 
				$obj->save();
				  
				 			
				$encId					=	md5(time() . Input::get('email'));
				//mail email and password to new registered user
				$settingsEmail 			=	Config::get('Site.email');
				$full_name				= 	$obj->full_name; 
				$email					= 	$obj->email;
				$password				= 	Input::get('password');
				$route_url     			= 	route('login.index');
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
* Function for display page for edit user
*
* @param $userId as id of user
*
* @return view page. 
*/
	public function editUser($userId = 0){
		$userDetails			=	AdminUser::find($userId);  
		if(empty($userDetails)) {
			return Redirect::route($this->model.'.index');
		}
		if($userId){ 
			return View::make('admin.'.$this->model.'.edit', compact('userDetails'));
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
		if(!empty($thisData)){
			$validator 					=	Validator::make(
				Input::all(),
				array( 
					'first_name'			=> 'required',
					'last_name'			    => 'required',
					'email' 				=> "required|email|unique:users,email,$userId,id",
					'username' 				=> 'required',
					'password'				=> 'min:8',
					'confirm_password'  	=> 'min:8|same:password',   
				),
				array( 
				)
			);  
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
				$obj->first_name 			= Input::get('first_name');
				$obj->last_name 			= Input::get('last_name');
				$fullName					=	Input::get('first_name').' '.Input::get('last_name');
				$obj->full_name 			=   !empty(Input::get('first_name'))?ucwords($fullName):Input::get('full_name');
				$obj->email 				=  	Input::get('email');
				$get_password				=	!empty(Input::get('password'))?Input::get('password'):'';
				$get_confirm_password		=	Input::get('password_confirmation');
				if(!empty($get_password) && !empty($get_confirm_password) && $get_password == $get_confirm_password){
					$obj->password	 		=  Hash::make(Input::get('password'));
				} 
				$obj->username			=  (Input::get('username'));
				
				$obj->save();  
				$errors 				=	$validator->messages();
				$response							=	array(
					'success' 						=> 	1,
					'errors' 						=> 	$errors
				);
				return Response::json($response); 
				die;
			}
		}
	}// end updateUser()
  
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
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::route($this->model.'.index');
	} // end updateUserStatus()

}//end UsersController
