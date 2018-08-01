<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\User;
use App\Model\ResellerRequest;
use App\Model\Ticket;
use App\Model\Booking;
use App\Model\Notification;
use App\Model\ProjectFolderArticle;
use App\Model\ProjectArticleComment;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
* AdminDashBoard Controller
*
* Add your methods in the class below
*
* This file will render views\admin\dashboard
*/
	class AdminDashBoardController extends BaseController {
		
	public $model	=	'dashboard';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
/**
* Function for display admin dashboard
*
* @param null
*
* @return view page. 
*/
	public function showdashboard(){
		$userCount 						=	DB::table('users')
											->where('user_role_id','!=',SUPER_ADMIN_ROLE_ID)
											->where('is_deleted','!=',1)
											->count();
											
		$TotalActiveUser 				=	DB::table('users')
											->where('user_role_id','!=',SUPER_ADMIN_ROLE_ID)
											->where('is_deleted','!=',1)
											->where('is_active','=',1)
											->count();
											
		$TotalInactiveUser 				=	DB::table('users')
											->where('user_role_id','!=',SUPER_ADMIN_ROLE_ID)
											->where('is_active','=',0)
											->count();

		$studioCount 					=	DB::table('users')->where('user_role_id','=',STUDIO_ROLE_ID)->where('is_deleted','!=',1)->count();
		$fanCount 						=	DB::table('users')->where('user_role_id','=',FAN_ROLE_ID)->where('is_deleted','!=',1)->count();
		$parentCount 					=	DB::table('users')->where('user_role_id','=',PARENT_ROLE_ID)->where('is_deleted','!=',1)->count();
		$dancerCount 					=	DB::table('users')->where('user_role_id','=',DANCER_ROLE_ID)->where('is_deleted','!=',1)->count();
		$popularLibary 					=	DB::table('project_folder_articles')->where('is_active',1)->where('is_deleted',0)->select('article_name')->orderBy('viewed','DESC')->limit(5)->get();  
		$jobsCount 						=	1;
		$cadidateCount 					=	1;
		
		//User Graph Data
		$month							=	date('m');
		$year							=	date('Y');
		for ($i = 0; $i < 12; $i++) {
			$months[] 					=	date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));
		}
		$months							=	array_reverse($months);
		$num							=	0;
		$allUsers						=	array();
		//Active Users
		$thisMothUsers					=	0;
		foreach($months as $month){
			$month_start_date			=	date('Y-m-01 00:00:00', strtotime($month));
			$month_end_date				=	date('Y-m-t 23:59:59', strtotime($month));
			$allUsers[$num]['month']	=	$month;
			$allUsers[$num]['users']	=	DB::table('users')->where('created_at','>=',$month_start_date)->where('created_at','<=',$month_end_date)->where('is_deleted','!=',1)->count();
			if($month_start_date == date( 'Y-m-01 00:00:00', strtotime( 'first day of ' . date( 'F Y')))){
				$thisMothUsers	=	$allUsers[$num]['users'];
			}	
			$num ++;
		}
	 
		return  View::make('admin.'.$this->model.'.dashboard',compact('userCount','jobsCount','cadidateCount','allUsers','adminUserCount','TotalActiveUser','TotalInactiveUser','studioCount','fanCount','parentCount','dancerCount','popularLibary'));
	}
/**
* Function for display admin account detail
*
* @param null
*
* @return view page. 
*/
	public function myaccount(){
		return  View::make('admin.'.$this->model.'.myaccount');
	}// end myaccount()
/**
* Function for change_password
*
* @param null
*
* @return view page. 
*/	
	public function change_password(){
		return  View::make('admin.'.$this->model.'.change_password');
	}// end myaccount()
/**
* Function for update admin account update
*
* @param null
*
* @return redirect page. 
*/
	public function myaccountUpdate(){
		$thisData				=	Input::all(); 
		Input::replace($this->arrayStripTags($thisData));
		$old_password     		= 	Input::get('old_password');
        $password        		= 	Input::get('new_password');
        $confirm_password 		= 	Input::get('confirm_password');
		$ValidationRule = array(
            'first_name' 		=> 'required',
            'last_name' 		=> 'required',
            'email' 			=> 'required|email',
			'image' 			=> 'mimes:'.IMAGE_EXTENSION,
        );
        $validator 				= 	Validator::make(Input::all(), $ValidationRule);
		if ($validator->fails()){	
			return Redirect::route($this->model.'.myaccount')
				->withErrors($validator)->withInput();
		}else{
			$user 				= 	User::find(Auth::user()->id);
			$user->first_name 	= 	Input::get('first_name'); 
			$user->last_name 	= 	Input::get('last_name'); 
			$user->email	 	= 	Input::get('email');
			if(input::hasFile('image')){
				$extension 				=	 Input::file('image')->getClientOriginalExtension();
				$fileName				=	time().'-user-image.'.$extension;
				$newFolder     			= 	strtoupper(date('M'). date('Y')).DS;
				$folderPath				=	USER_PROFILE_IMAGE_ROOT_PATH.$newFolder; 
				$userImage   			= 	$fileName;
				if(!File::exists($folderPath)){
					File::makeDirectory($folderPath, $mode = 0777,true);
				}
				if(Input::file('image')->move($folderPath, $fileName)){
					$user->image			=	$newFolder.$fileName;
				}
			}
			if($user->save()) {
				return Redirect::route($this->model.'.myaccount')
					->with('success', 'Information updated successfully.');
			}
		}
	}// end myaccountUpdate()
/**
* Function for changedPassword
*
* @param null
*
* @return redirect page. 
*/	
	public function changedPassword(){
		$thisData				=	Input::all(); 
		Input::replace($this->arrayStripTags($thisData));
		$old_password    		= 	Input::get('old_password');
        $password         		= 	Input::get('new_password');
        $confirm_password 		= 	Input::get('confirm_password');
		Validator::extend('custom_password', function($attribute, $value, $parameters) {
			if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
				return true;
			} else {
				return false;
			}
		});
		$rules        		  	= 	array(
			'old_password' 		=>	'required',
			'new_password'		=>	'required|min:8|custom_password',
			'confirm_password'  =>	'required|same:new_password'
		);
		$validator 				= 	Validator::make(Input::all(), $rules,
		array(
			"new_password.custom_password"	=>	"Password must have combination of numeric, alphabet and special characters.",
		));
		if ($validator->fails()){	
			return Redirect::route($this->model.'.changePassword')
				->withErrors($validator)->withInput();
		}else{
			$user 				= User::find(Auth::user()->id);
			$old_password 		= Input::get('old_password'); 
			$password 			= Input::get('new_password');
			$confirm_password 	= Input::get('confirm_password');
			if($old_password !=''){
				if(!Hash::check($old_password, $user->getAuthPassword())){
					/* return Redirect::intended('change-password')
						->with('error', 'Your old password is incorrect.');
						 */
					Session::flash('error',trans("Your old password is incorrect."));
					return Redirect::route($this->model.'.changePassword');
				}
			}
			if(!empty($old_password) && !empty($password ) && !empty($confirm_password )){
				if(Hash::check($old_password, $user->getAuthPassword())){
					$user->password = Hash::make($password);
				// save the new password
					if($user->save()) {
						Session::flash('success',trans("Password changed successfully."));
						return Redirect::route($this->model.'.changePassword');
					}
				} else {
					/* return Redirect::intended('change-password')
						->with('error', 'Your old password is incorrect.'); */
					Session::flash('error',trans("Your old password is incorrect."));
					return Redirect::route($this->model.'.changePassword');
				}
			}else{
				$user->username = $username;
				if($user->save()) {
					Session::flash('success',trans("Password changed successfully."));
					return Redirect::route($this->model.'.changePassword');
					/* return Redirect::intended('change-password')
						->with('success', 'Password changed successfully.'); */
				}
			}
		}
	}// end myaccountUpdate()
/* 
* For User Listing Demo 
*/
	public function usersListing(){
		return View::make('admin.user.user');
	}
	
	public function notifications(){
		$DB 		= 	Notification::query();
		$result 		= 	$DB
							->leftJoin('users','users.id','=','notifications.sender_id')
							->select('users.full_name','notifications.*',DB::raw("null as blog_name"),DB::raw("null as comment_name"),DB::raw("null as project_folder_id"))
							->where('notifications.type','blog_comment')
							->orderBy('notifications.id','DESC')
							->paginate(Config::get("Reading.records_per_page"));
		if(!($result)->isEmpty()){
			foreach($result as $record){
				if(isset($record->jsondata) && !empty($record->jsondata)){
					$data		=	json_decode($record->jsondata);
					if(!empty($data)){
						$DB1 		= 	ProjectFolderArticle::query();
						$DB2 		= 	ProjectArticleComment::query();
						if(isset($data->blog_id)){
							$record->blog_name				=	$DB1->where('id',$data->blog_id)->value('article_name');
							$record->project_folder_id		=	$DB1->where('id',$data->blog_id)->value('project_folder_id');
						}
						if(isset($data->comment_id)){
							$record->comment_name			=	$DB2->where('id',$data->comment_id)->value('message');
						}
					}
				}
			}
		}
		DB::table('notifications')->update(array('is_read'=>1));
		return  View::make("admin.dashboard.notifications", compact('result'));
	}//end notifications()
	
	public function getNotifications(){
		$notifications = DB::table('notifications')
							->where('notifications.is_read',0)
							->leftJoin('users','users.id','=','notifications.sender_id')
							->where('notifications.type','blog_comment')
							->orderBy('notifications.id',"DESC")
							->select('users.full_name')
							->limit(5)
							->get();
		if(!($notifications)->isEmpty()){
			foreach($notifications as $record){
				if(isset($record->jsondata) && !empty($record->jsondata)){
					$data		=	json_decode($record->jsondata);
					if(!empty($data)){
						$DB1 		= 	ProjectFolderArticle::query();
						$DB2 		= 	ProjectArticleComment::query();
						if(isset($data->blog_id)){
							$record->blog_name		=	$DB1->where('id',$data->blog_id)->value('article_name');
						}
						if(isset($data->comment_id)){
							$record->comment_name		=	$DB2->where('id',$data->comment_id)->value('message');
						}
					}
				}
			}
		}
		DB::table('notifications')->update(array('is_read'=>1));
		return View::make('admin.dashboard.get_notification',compact('notifications'));
	}
	
	public function getAllNotifications(){
		$notifications		=	$this->adminGetNotifications();
		$countNotifications = count($notifications);
		return $countNotifications;
		die;
	}
} //end AdminDashBoardController()
