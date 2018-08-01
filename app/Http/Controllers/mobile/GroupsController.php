<?php
namespace App\Http\Controllers\mobile;
use App\Http\Controllers\BaseController;
use App\Model\User;
use App\Model\Group;
use App\Model\GroupFan;
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
class GroupsController extends BaseController {
	
	public function add_group(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'user_id'			=> 'required',
									'name'				=> 'required|unique:groups,name'
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$obj 					=	new Group;
				$obj->user_id			=	Input::get('user_id');
				$obj->name				=	Input::get('name');
				$obj->save();
				$lastInsertID = $obj->id;
				$response["status"]		=	"success";
				$response["message"]	=	"Group has been created successfully.";
				$response["data"]		=	array();
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function edit_group(){
		$formData	=	Input::all();
		$id			=	!empty(Input::get('group_id'))?Input::get('group_id'):'0';
		$name		=	!empty(Input::get('name'))?Input::get('name'):'';
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'user_id'			=> 'required',
									'group_id'			=> 'required',
									'name'				=> "required|unique:groups,name,$id,id"
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$id						=	Input::get('group_id');
				$obj 					=	Group::find($id);
				$obj->user_id			=	Input::get('user_id');
				$obj->name				=	Input::get('name');
				$obj->save();
				$response["status"]		=	"success";
				$response["message"]	=	"Group has been updated successfully.";
				$response["data"]		=	array();
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function delete_group(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'		=> 'required',
					'device_id'			=> 'required',
					'user_id'			=> 'required',
					'group_id'			=> 'required',
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$id						=	Input::get('group_id');
				$group_exist			=	Group::find($id);
				if(!empty($group_exist)){
					Group::find($id)->delete();
					$response["status"]		=	"success";
					$response["message"]	=	"Group has been deleted successfully.";
					$response["data"]		=	array();
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"Group not deleted.";
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
	
	public function group_list(){
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
				$groups	 =  DB::table('groups')
								->where("groups.user_id",$user_id)
								->select(
									'id','name'
								)
								->orderBy('created_at','DESC')
								->get();
								
				$response["status"]				=	"success";
				$response["message"]			=	"My Groups";
				$response["groups"]			=	$groups;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function add_group_fans(){
		$formData	=	Input::all();
		$response	=	array();
		$user_id		=	Input::get("user_id");
		$group_id	=	Input::get("group_id");
		if(!empty($formData)){
			Validator::extend('fan_already_exists', function($attribute, $value, $parameters)
			{
				$fan_detail		=	DB::table("group_fans")->where("fan_id",$value)->where("group_id",$parameters[0])->first();
				if(empty($fan_detail)){
					return true;
				}else {
					return false;
				}
			});
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'		=> 'required',
					'device_id'			=> 'required',
					'user_id'			=> 'required',
					'fan_id'			=> "required|fan_already_exists:$group_id",
					'group_id'			=> 'required',
				),
				array(
					'fan_id.fan_already_exists'		=>	"Fan id already exists",
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id = $formData['user_id'];
				$obj 					=	new GroupFan;
				$obj->user_id			=	Input::get('user_id');
				$obj->fan_id			=	Input::get('fan_id');
				$obj->group_id			=	Input::get('group_id');
				$obj->save();
				
				$response["status"]				=	"success";
				$response["message"]			=	"Group fan added successfully";
				$response["groups"]				= array();
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function remove_group_fan(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'		=> 'required',
					'device_id'			=> 'required',
					'user_id'			=> 'required',
					'fan_id'			=> 'required',
					'group_id'			=> 'required',
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id = $formData['user_id'];
				$fan_id = $formData['fan_id'];
				$group_id = $formData['group_id'];
				$group_fan	=	GroupFan::where("group_id",$group_id)->where("fan_id",$fan_id)->get();
				if(!($group_fan)->isEmpty()){
					GroupFan::where("group_id",$group_id)->where("fan_id",$fan_id)->delete();
					
					$response["status"]				=	"success";
					$response["message"]			=	"Group fan deleted successfully";
					$response["groups"]				= array();
				}else{
					$response["status"]				=	"error";
					$response["message"]			=	"No record found.";
					$response["groups"]				= array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function view_group_fans(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'		=> 'required',
					'device_id'			=> 'required',
					'user_id'			=> 'required',
					'group_id'			=> 'required',
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id = Input::get("user_id");
				$group_id = Input::get("group_id");
				$deletedUsers = DB::table("users")->Orwhere('is_active',0)->Orwhere('is_deleted',1)->pluck('id','id');
				$GroupFan = DB::table("group_fans")
								->where("user_id",$user_id)
								->where("group_id",$group_id)
								->whereNotIn('fan_id',$deletedUsers)
								->select('group_fans.*',DB::raw("(SELECT name FROM groups where group_id=groups.id) as group_name"),DB::raw("(SELECT full_name FROM users where id=group_fans.fan_id) as fan_name"),DB::raw("(SELECT image FROM users where id=group_fans.fan_id) as image"))
								->get();
				
				if(!empty($GroupFan)){
					foreach($GroupFan as &$fan){
						if($fan->image != '' && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$fan->image)){
							$fan->image		=	USER_PROFILE_IMAGE_URL.$fan->image;
						}else {
							$fan->image		=	WEBSITE_IMG_URL.'usr_img.png';
						}
						unset($fan->user_id);
						unset($fan->friend_id);
					}
					//~ foreach($GroupFan as $group_fan){
						//~ $fans		=	DB::table('users')
											//~ ->where('id',$group_fan->fan_id)
											//~ ->where('is_active',1)
											//~ ->where('is_deleted',0)
											//~ ->select('id','full_name','image')
											//~ ->orderBy('created_at','DESC')
											//~ ->get();						
						//~ if(!empty($fans)){
							//~ foreach($fans as &$fan){
								//~ if($fan->image != '' && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$fan->image)){
									//~ $fan->image		=	USER_PROFILE_IMAGE_URL.$fan->image;
								//~ }else {
									//~ $fan->image		=	WEBSITE_IMG_URL.'usr_img.png';
								//~ }
								//~ unset($fan->user_id);
								//~ unset($fan->friend_id);
							//~ }
							//~ $group_fan->fans			=	$fans;
						//~ }
						
						//~ $group		=	DB::table("groups")->where("id",$group_id)->select('groups.name')->first();
						//~ if(!empty($group)){
							//~ $group_fan->group_name			=	$group;
						//~ }
						//~ unset($group_fan->user_id);
						//~ unset($group_fan->group_id);
						//~ unset($group_fan->fan_id);
						//~ unset($group_fan->created_at);
						//~ unset($group_fan->updated_at);
					//~ }							
					$response["status"]				=	"success";
					$response["message"]			=	"Group fan found successfully";
					$response["groups"]				=	$GroupFan;
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"No groups found.";
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
	
	public function get_group_list(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'user_id'			=> 'required',
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id = $formData['user_id'];
				$groups	 =  DB::table('groups')
								->where("groups.user_id",$user_id)
								->select("name","id")->get();
				if(!empty($groups)){
					$response["status"]				=	"success";
					$response["message"]			=	"Groups list found.";
					$response["groups"]			=	$groups;
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"No group found";
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
	
}
