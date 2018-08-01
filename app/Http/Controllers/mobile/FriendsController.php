<?php
namespace App\Http\Controllers\mobile;
use App\Http\Controllers\BaseController;
use App\Model\User;
use App\Model\FriendRequest;
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
class FriendsController extends BaseController {
	
	//Search Dancer api for dancers
	public function search_friends(){
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
					'page'				=> 'required',
					'limit'				=> 'required'
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$page    			=  	(Input::get('page')) ? Input::get('page')-1   : 0;
				$offset   			=  	$page*Input::get('limit');
				$limit   			=  	Input::get('limit');
				$keyword			=	Input::get('keyword');
				$friends			=	DB::table('users');
				$friends1			=	DB::table('users');
				if(!empty($keyword)){
					$friends->where('full_name','LIKE','%'.$keyword.'%');
					$friends1->where('full_name','LIKE','%'.$keyword.'%');
				}
			
				$result	 = 	$friends->where('id','!=',$formData['user_id'])
								->where('is_active',1)
								->where('is_deleted',0)
								->where('user_role_id',DANCER_ROLE_ID)
								->select('image','full_name','id')
								->offset($offset)
								->limit($limit)
								->orderBy('full_name','ASC')
								->get();
				$totalCount = 0;
				if(!empty($result)){
					foreach($result as &$friend){
						if($friend->image != '' && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$friend->image)){
							$friend->image		=	USER_PROFILE_IMAGE_URL.$friend->image;
						}else {
							$friend->image		=	WEBSITE_IMG_URL.'usr_img.png';
						}
						$user_id 	=	$formData['user_id'];
						$friend_id 	= 	$friend->id;
						$friendRequest = DB::table('friend_requests')
												->where(function ($query) use($friend_id,$user_id){
													$query->orWhere(function ($query) use($friend_id,$user_id){
														$query->where("user_id",$friend_id);
														$query->where("friend_id",$user_id);
													});
													$query->orWhere(function ($query) use($friend_id,$user_id){
														$query->where("friend_id",$friend_id);
														$query->where("user_id",$user_id);
													});
												})->first();
						
						if(!empty($friendRequest)){
							$friend->friend_request_id = $friendRequest->id;
							if($friendRequest->user_id == $user_id){
								if($friendRequest->is_accept == 0){
									$friend->friend_status = 'pending';
								}else{
									$friend->friend_status = 'accepted';
								}
							}else{
								if($friendRequest->is_accept == 0){
									$friend->friend_status = 'respond';
								}else{
									$friend->friend_status = 'accepted';
								}
							}
						}else{
							$friend->friend_status = '';
						}
					}
					$totalCount = count($result);
				}
				$response["status"]				=	"success";
				$response["message"]			=	"Search Dancer Records";
				$response["data"]				=	$result;
				$response["total_count"]		=	$totalCount;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	public function send_friend_request(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'user_id'			=> 'required',
									'friend_id'			=> 'required'
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$checkExist 		=		DB::table('friend_requests')->where("user_id",Input::get('user_id'))->where("friend_id",Input::get('friend_id'))->first();
				if(empty($checkExist)){
					$obj 					=	new FriendRequest;
					$obj->user_id			=	Input::get('user_id');
					$obj->friend_id			=	Input::get('friend_id');
					$obj->is_accept			=	0;
					$obj->save();
					$lastInsertID 			= 	$obj->id;
					$response["status"]		=	"success";
					$response["message"]	=	"Friend Request has Sent.";
					$response["data"]		=	['friend_request_id'=>$lastInsertID];
					//Send Notification
					$json_data  = json_encode(array('friend_request_id'=>$lastInsertID));
					$this->save_notification(Input::get('user_id'),Input::get('friend_id'),'send_friend_request',$json_data);
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Request Sent on ".date("Y-m-d",strtotime($checkExist->created_at))." at ".date("H:i a",strtotime($checkExist->created_at));
					$response["data"]		=	['friend_request_id'=>$checkExist->id];
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function get_all_friend_request(){
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
				
				$deletedUsers = DB::table("users")->Orwhere('is_active',0)->Orwhere('is_deleted',1)->pluck('id','id');
				
				$dancers	 =  DB::table('friend_requests')->where('friend_id',$formData['user_id'])
								->where('is_accept',0)
								->whereNotIn('user_id',$deletedUsers)
								->select('id','user_id','friend_id',DB::raw("(SELECT full_name FROM users WHERE id = friend_requests.user_id) as full_name"),DB::raw("(SELECT image FROM users WHERE id = friend_requests.user_id) as image"))
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
				
				$fans	 =  DB::table('follow_requests')->where('friend_id',$formData['user_id'])
								->where('is_accept',0)
								->whereNotIn('user_id',$deletedUsers)
								->select('id','user_id','friend_id',DB::raw("(SELECT full_name FROM users WHERE id = follow_requests.user_id) as full_name"),DB::raw("(SELECT image FROM users WHERE id = follow_requests.user_id) as image"))
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
						}else {
							$fan->image		=	WEBSITE_IMG_URL.'usr_img.png';
						}
					}
				}
				$response["status"]				=	"success";
				$response["message"]			=	"Friend Requests";
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
	
	public function accept_reject_friend_request(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
					Input::all(),
					array(
						'device_type'		=> 'required',
						'device_id'			=> 'required',
						'id'				=> 'required',
						'type'				=> 'required'
					)
				);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$result = DB::table('friend_requests')->where('id',Input::get('id'))->first();
				if(!empty($result)){
					if(Input::get("type") == 'accept'){
						$obj 					=	FriendRequest::find(Input::get('id'));
						$obj->is_accept			=	1;
						$obj->save();
						$response["message"]	=	"Friend Request accepted.";
						$response["status"]		=	"accepted";
						//Send Notification
						$json_data  = json_encode(array('friend_request_id'=>Input::get('id')));
						$this->save_notification($result->friend_id,$result->user_id,'accepted_friend_request',$json_data);
					}else{
						$obj 					=	FriendRequest::find(Input::get('id'));
						$obj->delete();
						$response["message"]	=	"Friend Request Rejected.";
						$response["status"]		=	"rejected";
						//Send Notification
						$json_data  = json_encode(array('friend_request_id'=>Input::get('id')));
						$this->save_notification($result->friend_id,$result->user_id,'rejected_friend_request',$json_data);
					}
					$response["data"]		=	[];
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
	
	public function cancel_friend_request(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
					Input::all(),
					array(
						'device_type'		=> 'required',
						'device_id'			=> 'required',
						'user_id'			=> 'required',
						'id'				=> 'required'
					)
				);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$result = FriendRequest::where('id',Input::get('id'))->first();
				if(!empty($result)){
					FriendRequest::where('id',Input::get('id'))->delete();
					$response["message"]	=	"Friend Request Cancelled.";
					$response["status"]		=	"success";
					$response["data"]		=	[];
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
	
	public function my_friends(){
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
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id = $formData['user_id'];
				$deletedUsers = DB::table("users")->Orwhere('is_active',0)->Orwhere('is_deleted',1)->pluck('id','id');
				$daners	 =  DB::table('friend_requests')
								->where('is_accept',1)
								->where(function ($query) use($user_id){
									$query->Orwhere("friend_requests.user_id",$user_id);
									$query->Orwhere("friend_requests.friend_id",$user_id);
								})
								->whereNotIn("friend_requests.user_id",$deletedUsers)
								->whereNotIn("friend_requests.friend_id",$deletedUsers)
								->select(
									'id','user_id','friend_id',
									DB::raw("IF((user_id != $user_id),(SELECT full_name FROM users WHERE id = friend_requests.user_id LIMIT 1),(SELECT full_name FROM users WHERE id = friend_requests.friend_id LIMIT 1)) as full_name"),
									DB::raw("IF((user_id != $user_id),(SELECT image FROM users WHERE id = friend_requests.user_id LIMIT 1),(SELECT image FROM users WHERE id = friend_requests.friend_id LIMIT 1)) as image")
								)
								->orderBy('created_at','DESC')
								->get();
								
				if(!empty($daners)){
					foreach($daners as &$friend){
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
								->whereNotIn("follow_requests.user_id",$deletedUsers)
								->whereNotIn("follow_requests.friend_id",$deletedUsers)
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
				$response["dancers"]			=	$daners;
				$response["fans"]				=	$fans;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	
	
}
