<?php
namespace App\Http\Controllers\mobile;
use App\Http\Controllers\BaseController;
use App\Model\User;
use App\Model\FollowRequest;
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
class FansController extends BaseController {
	
	//Search Dancer api
	public function search_dancers(){
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
			
				$result	 = 	$friends
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
						$friendRequest = DB::table('follow_requests')
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
							$friend->friend_request_id = '';
							$friend->friend_status = '';
						}
					}
					$totalCount = count($result);
				}
				$response["status"]				=	"success";
				$response["message"]			=	"Dancer Records";
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

	public function send_follow_request(){
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
				$obj 					=	new FollowRequest;
				$obj->user_id			=	Input::get('user_id');
				$obj->friend_id			=	Input::get('friend_id');
				$obj->is_accept			=	0;
				$obj->save();
				$lastInsertID = $obj->id;
				$response["status"]		=	"success";
				$response["message"]	=	"Follow Request Sent.";
				$response["data"]		=	['friend_request_id'=>$lastInsertID];
				//Send Notification
				$json_data  = json_encode(array('follow_request_id'=>$lastInsertID));
				$this->save_notification(Input::get('user_id'),Input::get('friend_id'),'send_follow_request',$json_data);
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function accept_reject_follow_request(){
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
				$result = DB::table('follow_requests')->where('id',Input::get('id'))->first();
				if(!empty($result)){
					if(Input::get("type") == 'accept'){
						$obj 					=	FollowRequest::find(Input::get('id'));
						$obj->is_accept			=	1;
						$obj->save();
						$response["message"]	=	"Follow Request accepted.";
						$response["status"]		=	"accepted";
						//Send Notification
						$json_data  = json_encode(array('follow_request_id'=>Input::get('id')));
						$this->save_notification($result->friend_id,$result->user_id,'accepted_follow_request',$json_data);
					}else{
						$obj 					=	FollowRequest::find(Input::get('id'));
						$obj->delete();
						$response["message"]	=	"Follow Request Rejected.";
						$response["status"]		=	"rejected";
						//Send Notification
						$json_data  = json_encode(array('follow_request_id'=>Input::get('id')));
						$this->save_notification($result->friend_id,$result->user_id,'rejected_follow_request',$json_data);
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
	
	public function cancel_follow_request(){
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
				$result = FollowRequest::where('id',Input::get('id'))->first();
				if(!empty($result)){
					FollowRequest::where('id',Input::get('id'))->delete();
					$response["message"]	=	"Follow Request Cancelled By Fan.";
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
	
	public function my_followings(){
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
				$daners	 =  DB::table('follow_requests')
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
				$response["status"]				=	"success";
				$response["message"]			=	"My Followings";
				$response["dancers"]			=	$daners;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function my_fan_requests(){
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
				$user_id	 = 	$formData['user_id'];
				$dancers	 =  DB::table('follow_requests')
								->leftJoin("users", 'users.id', '=', 'follow_requests.friend_id')
								->where('users.is_deleted',0)
								->where('users.is_active',1)
								->where('follow_requests.is_accept',0)
								->where(function ($query) use($user_id){
									$query->Orwhere("follow_requests.user_id",$user_id);
									$query->Orwhere("follow_requests.friend_id",$user_id);
								})
								->select('follow_requests.id','follow_requests.user_id','follow_requests.friend_id','follow_requests.is_accept','follow_requests.created_at',
									DB::raw("IF((user_id != $user_id),(SELECT full_name FROM users WHERE id = follow_requests.user_id LIMIT 1),(SELECT full_name FROM users WHERE id = follow_requests.friend_id LIMIT 1)) as full_name"),
									DB::raw("IF((user_id != $user_id),(SELECT image FROM users WHERE id = follow_requests.user_id LIMIT 1),(SELECT image FROM users WHERE id = follow_requests.friend_id LIMIT 1)) as image")
								)
								->orderBy('follow_requests.created_at','DESC')
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
						$friend->datetime	=	Carbon::parse($friend->created_at)->diffForHumans();
					}							
				}
				$response["status"]				=	"success";
				$response["message"]			=	"Friend Requests";
				$response["dancers"]			=	$dancers;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function my_following_count(){
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
				$user_id 	= 	$formData['user_id'];
				$dancers	=  	DB::table('follow_requests')
								->where('follow_requests.is_accept',1)
								->leftJoin("users", 'users.id', '=', 'follow_requests.friend_id')
								->leftJoin("users as uuser", 'uuser.id', '=', 'follow_requests.user_id')
								->where('users.is_deleted',0)
								->where('users.is_active',1)
								->where('uuser.is_deleted',0)
								->where('uuser.is_active',1)
								->where(function ($query) use($user_id){
									$query->Orwhere("follow_requests.user_id",$user_id);
									$query->Orwhere("follow_requests.friend_id",$user_id);
								})
								->select('follow_requests.id')
								->count();
								
				$response["status"]				=	"success";
				$response["message"]			=	"My Following Count";
				$response["dancers"]			=	$dancers;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
}
