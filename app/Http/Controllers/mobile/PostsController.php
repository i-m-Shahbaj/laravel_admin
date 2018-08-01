<?php
namespace App\Http\Controllers\mobile;
use App\Http\Controllers\BaseController;
use App\Model\User;
use App\Model\Post;
use App\Model\PostLike;
use App\Model\PostComment;
use App\Model\PostImage;
use App\Model\FriendRequest;
use App\Model\ParentChild;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\ApiResponse;
use App\Model\PostCommentLike;
use App\Model\Notification;
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
class PostsController extends BaseController {
	
	public function save_posts(){
		$formData	=	Input::all();
		/* echo "<pre>";
		print_r($formData);die; */
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
					Input::all(),
					array(
						'device_type'			=> 'required',
						'device_id'				=> 'required',
						'user_id'			=> 'required',
						'message'			=> 'required',
						//'image' 			=> 'mimes:'.IMAGE_EXTENSION,
					)
				);
			if ($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$obj 					=  new Post;
				$obj->user_id			=  Input::get('user_id');	
				$obj->message			=  Input::get('message');	
				$obj->total_likes		=  0;	
				$obj->total_comments	=  0;	
				$obj->is_active			=  1;	
				$obj->save();
				
				if (Input::hasFile('post_images')){
					$images	=	Input::file("post_images");
					$i = 1;
					foreach ($images as $file){
						$PostImage = new PostImage();
						$extension  	= $file->getClientOriginalExtension();
						$file_name  	= $file->getClientOriginalName();
						$newFolder  	= strtoupper(date('M') . date('Y')) . '/';
						$folderPath 	= POST_IMAGE_ROOT_PATH . $newFolder;
						if (!File::exists($folderPath)) {
							File::makeDirectory($folderPath, $mode = 0777, true);
						}
						if(in_array($extension, array('gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG'))){
							$ticketImageName 	= time() . $i . '-post-image-.' . $extension;
							$image = $newFolder . $ticketImageName;
							if($file->move($folderPath, $ticketImageName)){
								$PostImage->image = $image;
							}
							$PostImage->type = 'image';
						}else{
							$time = time();
							$PostImage->type 	= 	'video';
							$fileName			=	$time.$i.'-post-video.'.$extension;
							$mp4_file 			= 	$time.$i.'-post-video.mp4';
							$webm_file 			= 	$time.$i.'-post-video.webm';
							$jpg_file 			= 	$time.$i.'-post-video.jpg';
							if($file->move($folderPath, $fileName)){
								$this->convertToMp4($folderPath . $fileName,$folderPath . $mp4_file ,1079, 559);
								$this->convertToWebm($folderPath . $fileName,$folderPath . $webm_file ,1079, 559);
								$this->generateThumbnail($folderPath .DS. $mp4_file,$folderPath .DS. $jpg_file ,1079, 559);
								$PostImage->image    =  $newFolder . $fileName;
							}
						}
						$i++;
						$PostImage->post_id = $obj->id;
						$PostImage->save();
					}
				}
				$post_details		=	$this->get_post_detail_by_id($obj->id);
				$response["status"]		=	"success";
				$response["message"]	=	"You have successfully added a post.";
				$response["data"]		=	$post_details;
			}	
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	public function get_all_posts(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
					Input::all(),
					array(
						'device_type'			=> 'required',
						'device_id'				=> 'required',
						'user_id'				=> 'required',
					)
				);
			if ($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id		=	Input::get('user_id');
				$post_lists		=	DB::table('posts')
										->where(function ($query) use($user_id){
											$query->orWhere("posts.user_id",1);
											$query->orWhere("posts.user_id",$user_id);
										})
										->where('posts.is_active',1)
										->where('posts.is_deleted',0)
										->select('posts.message','posts.id','posts.user_id','posts.created_at as date',DB::raw("(select users.full_name from users where id =posts.user_id) as full_name"),DB::raw("(select users.image from users where id =posts.user_id) as profile_image"),DB::raw("(select count(id) from post_likes where post_likes.post_id =posts.id) as total_likes"),DB::raw("(select count(id) from post_comments where post_comments.post_id =posts.id) as total_comments"),DB::raw("(select count(id) from post_likes where post_likes.post_id =posts.id AND post_likes.user_id=$user_id) as user_like"),DB::raw("null as likes"),DB::raw("null as last_comments"))
										->orderBy("created_at",'DESC')
										->get();
				$post_lists		=	json_decode(json_encode($post_lists)); 
				if(!empty($post_lists)){
					foreach($post_lists as &$post_details){
						$post_details->date		=	DB::table('posts')->where('id',$post_details->id)->select('created_at')->get();
						if(!empty($post_details->date)){
							foreach($post_details->date as $key=>$date){
								$post_details->date	=	Carbon::parse($date->created_at)->diffForHumans();
							}
						}
					}
					foreach($post_lists as &$user_image){
						$user_image->profile_image		=	DB::table('users')->where('id',$user_image->user_id)->select('image')->get();
						if(!empty($user_image->profile_image)){
							foreach($user_image->profile_image as $key=>$image){
								if(!empty($image->image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$image->image)){
									
									$image->image		=	USER_PROFILE_IMAGE_URL.$image->image;
								}else {
									$image->image		=	WEBSITE_IMG_URL.'usr_img.png';
								}
							}
						}
					}
					
					foreach($post_lists as &$post_image){
						$post_image->images		=	DB::table('post_images')->where('post_id',$post_image->id)->select('image','type')->get();
						if(!empty($post_image->images)){
							foreach($post_image->images as $key=>$image){
								if(!empty($image->type) && $image->type  == 'video'){
									$imageArray = explode('.',$image->image);
									$name		= isset($imageArray[0]) ? $imageArray[0] : '';
									$imageName  = $name.".jpg";
									if(!empty($imageName) && file_exists(POST_IMAGE_ROOT_PATH.$imageName)){
										$image->video_thumbnail		=	POST_IMAGE_URL.$imageName;
									}else{
										$image->video_thumbnail		=	WEBSITE_IMG_URL.'video-thumbnail.jpg';
									}
								}
								if(!empty($image->image) && file_exists(POST_IMAGE_ROOT_PATH.$image->image)){
									$image->image		=	POST_IMAGE_URL.$image->image;
								}else{
									$image->image		=	WEBSITE_IMG_URL.'no_image.jpg';
								}
							}
						}
					}
					
					$likeArr	=	[];
					foreach($post_lists as &$post_like){
						$like_types	=	DB::table('post_likes')->where('post_likes.post_id',$post_like->id)->groupBy('type')->pluck("type","type");
						if(!empty($like_types)){
							foreach($like_types as &$type){
								$post_like->likes[$type] = DB::table('post_likes')->where('post_likes.post_id',$post_like->id)->where('type',$type)->select('post_likes.id','post_likes.type','post_likes.user_id',DB::raw("(select users.full_name from users where id =post_likes.user_id) as full_name"),DB::raw("(select users.image from users where id =post_likes.user_id) as profile_image"))->get();
								if(!empty($post_like->likes[$type])){
									$likeArr	= $post_like->likes[$type];
									if(!empty($likeArr)){
										foreach($likeArr as $like){
											if(!empty($like->profile_image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$like->profile_image)){
												$like->profile_image		=	USER_PROFILE_IMAGE_URL.$like->profile_image;
											}else {
												$like->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
											}
										}
									}
								}
							}
						}
					}
					
					foreach($post_lists as &$post_comment){
						$post_comment->comments	=	DB::table('post_comments')->where('post_comments.post_id',$post_comment->id)->where('post_comments.parent_id','=',0)->select('post_comments.id','post_comments.comment as comment','post_comments.is_like as total_likes','post_comments.user_id',DB::raw("(select users.full_name from users where id =post_comments.user_id) as full_name"),DB::raw("(select users.image from users where id =post_comments.user_id) as profile_image"))->get();
						if(!empty($post_comment->comments)){
							foreach($post_comment->comments as &$comments){
								if(!empty($comments->profile_image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$comments->profile_image)){
									$comments->profile_image		=	USER_PROFILE_IMAGE_URL.$comments->profile_image;
								}else{
									$comments->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
								}
							}
						}
					}
					foreach($post_lists as &$post_comment){
						$post_comment->last_comments	=	DB::table('post_comments')->where('post_comments.post_id',$post_comment->id)->where('post_comments.parent_id','=',0)->select('post_comments.comment as comment','post_comments.created_at',DB::raw("(select users.full_name from users where id =post_comments.user_id) as full_name"),DB::raw("(select users.image from users where id =post_comments.user_id) as profile_image"))->orderBy("created_at","DESC")->first();
						if(!empty($post_comment->last_comments)){
							$last_comments		=	$post_comment->last_comments;
							if(!empty($last_comments->profile_image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$last_comments->profile_image)){
								$last_comments->profile_image		=	USER_PROFILE_IMAGE_URL.$last_comments->profile_image;
							}else{
								$last_comments->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
							}
							if(!empty($last_comments->created_at)){
								$last_comments->created_at			=	Carbon::parse($last_comments->created_at)->diffForHumans();
							}
						}
					}
				}
				
				if(!empty($post_lists)){
					$response["status"]			=	"success";
					$response["message"]		=	"Posts found successfully";
					$response["post"]			=	array($post_lists);
				}else{
					$response["status"]			=	"error";
					$response["message"]		=	"Posts not found.";
					$response["post"]	=	array();
				}
			}	
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["post"]		=	array();
		}
		return json_encode($response);
	}
	
	public function save_post_likes(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
					Input::all(),
					array(
						'device_type'		=> 'required',
						'device_id'			=> 'required',
						'post_id'			=> 'required',
						'user_id'			=> 'required',
						'type'				=> 'required',
					),
					array(
						'type.required'		=> 'Please enter type.',
					)
				);
			if ($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id	=	Input::get('user_id');
				$post_id	=	Input::get('post_id');
				$type		=	Input::get('type');
				//$postLike	=	DB::table('post_likes')->orWhere('user_id',Input::get('user_id'))->orWhere('post_id',Input::get('post_id'))->orWhere('type',Input::get('type'))->get();
				
				
				$postLike	=	DB::table('post_likes')->where("post_id",$post_id)->where("user_id",$user_id)->where("type",$type)
				->first();
				
				$userDetails	=	DB::table('users')->where('id',Input::get('user_id'))->select('full_name','image')->first();
				if(!empty($userDetails)){
					if(!empty($userDetails->image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$userDetails->image)){
						$userDetails->image		=	USER_PROFILE_IMAGE_URL.$userDetails->image;
					}else {
						$userDetails->image		=	WEBSITE_IMG_URL.'usr_img.png';
					}
				}
				if(!empty($postLike)){
					$response["status"]		=	"error";
					$response["message"]	=	"You have already liked this post.";
					$response["data"]		=	$userDetails;
				}else{
					$obj 					=  new PostLike;
					$obj->post_id			=  Input::get('post_id');	
					$obj->user_id			=  Input::get('user_id');	
					$obj->type				=  Input::get('type');	
					$obj->count				=  1;	
					$obj->save();
					
					$response["status"]		=	"success";
					$response["message"]	=	"You have successfully like a post.";
					$response["data"]		=	array();
					
					//Send Notification
					$json_data  = json_encode(array('post_id'=>$post_id));
					$postData  = DB::table("posts")->where("id",$post_id)->select("user_id")->first();
					$this->save_notification(Input::get('user_id'),$postData->user_id,'like_on_post',$json_data);
					
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
	
	public function unlike_on_post(){
		//~ $request	=	$this->decrypt($formData["request"]);
		//~ Input::replace($this->arrayStripTags($request));
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
					Input::all(),
					array(
						'device_type'			=> 'required',
						'device_id'				=> 'required',
						'post_id'				=> 'required',
						'user_id'				=> 'required',
					),
					array(
						'post_id.required'			=> 'Please enter post id.',
						'user_id.required'			=> 'Please enter user id.',
					)
				);
			if ($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$post_id		=	Input::get('post_id');
				$user_id		=	Input::get('user_id');
				
				$post_likes_id		=	DB::table('post_likes')->where('post_id',$post_id)->where('user_id',$user_id)->value('id');
				
				if(!empty($post_likes_id)){
					PostLike::find($post_likes_id)->delete();
					$response["status"]			=	"success";
					$response["message"]		=	"Post unlike successfully";
					$response["data"]			=	array();
				}else{
					$response["status"]			=	"error";
					$response["message"]		=	"No record found.";
					$response["data"]			=	array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	public function like_on_post(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
					Input::all(),
					array(
						'device_type'			=> 'required',
						'device_id'				=> 'required',
						'post_id'			=> 'required',
					),
					array(
						'post_id.required'			=> 'Please enter post id.',
					)
				);
			if ($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$post_id		=	Input::get('post_id');
				$post_likes		=	DB::table('post_likes')->where('post_id',$post_id)->count('id');
			
				if(!empty($post_likes)){
					$response["status"]			=	"success";
					$response["message"]		=	"Post likes found successfully";
					$response["data"]			=	['post_like'=>$post_likes];
				}else{
					$response["status"]			=	"error";
					$response["message"]		=	"No likes found.";
					$response["data"]			=	array();
				}
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	public function save_comments(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
					Input::all(),
					array(
						'device_type'			=> 'required',
						'device_id'				=> 'required',
						'user_id'			=> 'required',
						'post_id'			=> 'required',
						'comment'			=> 'required',
					),
					array(
						'user_id.required'			=> 'Please enter user id.',
						'post_id.required'			=> 'Please enter post id.',
						'comment.required'			=> 'Please enter comment.',
					)
				);
			if ($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$obj 					=  new PostComment;
				$obj->post_id			=  Input::get('post_id');	
				$obj->user_id			=  Input::get('user_id');	
				$obj->comment			=  Input::get('comment');	
				$obj->is_like			=  0;	
				$obj->save();
				
				//Send Notification
				$json_data  = json_encode(array('post_id'=>Input::get('post_id'),'comment_id'=>Input::get('comment_id')));
				$postData  = DB::table("posts")->where("id",Input::get('post_id'))->select("user_id")->first();
				$this->save_notification(Input::get('user_id'),$postData->user_id,'comment_on_post',$json_data);
				
				$post_comment_details		=	$this->get_post_comment_detail_by_id($obj->id);
				
				$response["status"]		=	"success";
				$response["message"]	=	"You have successfully comment a post.";
				$response["data"]		=	$post_comment_details;
			}
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	public function get_comment_on_post(){
		//~ $request	=	$this->decrypt($formData["request"]);
		//~ Input::replace($this->arrayStripTags($request));
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			if(!empty(Input::get('post_id'))){
				$validator 	=	Validator::make(
						Input::all(),
						array(
							'device_type'			=> 'required',
							'device_id'				=> 'required',
							'post_id'				=> 'required',
							'user_id'				=> 'required',
						),
						array(
							'post_id.required'			=> 'Please enter post id.',
							'user_id.required'			=> 'Please enter user id.',
						)
					);
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$post_id		=	Input::get('post_id');
					$user_id		=	Input::get('user_id');
					$post_comments	=	PostComment::with(array('getChildComments'=>function($query) use ($user_id){
											$query->select("id","post_id","parent_id","user_id","comment","created_at",
											DB::raw("(select users.full_name from users where id =post_comments.user_id) as full_name"),
											DB::raw("(select users.image from users where id =post_comments.user_id) as profile_image"),
											DB::raw("(select count(id) from post_comment_likes where post_comment_likes.comment_id =post_comments.id) as total_likes_on_comment"),
											DB::raw("(select count(id) from post_comment_likes where post_comment_likes.comment_id =post_comments.id AND post_comment_likes.user_id=$user_id) as like_by_auth_user"));
										}))
										->where('post_id',$post_id)->where("parent_id",0)
										->select(
											'post_comments.id',	
											'post_comments.post_id',	
											'post_comments.parent_id',	
											'post_comments.user_id',	
											'post_comments.comment',
											'post_comments.created_at',
											DB::raw("(select users.full_name from users where id =post_comments.user_id) as full_name"),
											DB::raw("(select users.image from users where id =post_comments.user_id) as profile_image"),
											DB::raw("(select count(id) from post_comment_likes where post_comment_likes.comment_id =post_comments.id) as total_likes_on_comment"),
											DB::raw("(select count(id) from post_comment_likes where post_comment_likes.comment_id =post_comments.id AND post_comment_likes.user_id=$user_id) as like_by_auth_user")
										)
										->get();
					
					$post_comments	=	json_decode(json_encode($post_comments));
					
					if(!empty($post_comments)){
						foreach($post_comments as &$data){
							$profile_image		=	DB::table('users')->where('id',$data->user_id)->select('image')->first();
							if(!empty($profile_image)){
								if(!empty($profile_image->image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$profile_image->image)){
									$data->profile_image		=	USER_PROFILE_IMAGE_URL.$profile_image->image;
								}else {
									$data->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
								}
							}
							$data->created_time		=		Carbon::parse($data->created_at)->diffForHumans();
							if(!empty($data->get_child_comments)){
								foreach($data->get_child_comments as &$childData){
									$profile_image		=	DB::table('users')->where('id',$childData->user_id)->select('image')->first();
									if(!empty($profile_image)){
										if(!empty($profile_image->image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$profile_image->image)){
											$childData->profile_image		=	USER_PROFILE_IMAGE_URL.$profile_image->image;
										}else {
											$childData->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
										}
									}
									$childData->created_time		=		Carbon::parse($data->created_at)->diffForHumans();
								}
							}
						}
					}
					
					if(!empty($post_comments)){
						$response["status"]			=	"success";
						$response["message"]		=	"Post comments found successfully";
						$response["data"]			=	$post_comments;
					}else{
						$response["status"]			=	"error";
						$response["message"]		=	"No comments found.";
						$response["data"]			=	array();
					}
				}
			}else{
				$response["status"]		=	"error";
				$response["message"]	=	"Invalid Request.";
				$response["data"]		=	array();
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	public function like_on_comment(){
		$formData	=	Input::all(); 
		$response	=	array();
		if(!empty($formData)){
				$validator 	=	Validator::make(
						Input::all(),
						array(
							'device_type'			=> 'required',
							'device_id'				=> 'required',
							'post_id'				=> 'required',
							'comment_id'			=> 'required',
							'user_id'				=> 'required',
						),
						array(
							'post_id.required'			=> 'Please enter post id.',
							'comment_id.required'		=> 'Please enter comment id.',
							'user_id.required'		=> 'Please enter user id.',
						)
					);
				if($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$post_id			=	Input::get('post_id');
					$comment_id			=	Input::get('comment_id');
					$user_id			=	Input::get('user_id');
					
					$likes_on_comments	=	DB::table('post_comment_likes')->where('post_id',$post_id)->where('comment_id',$comment_id)->where('user_id',$user_id)->first();
					if(empty($likes_on_comments)){
						$obj  				=	new PostCommentLike;
						$obj->post_id		=	$post_id;
						$obj->comment_id	=	$comment_id;
						$obj->user_id		=	$user_id;
						$obj->save();
						$response["status"]			=	"success";
						$response["message"]		=	"Like on comment";
						$response["data"]			=	[];
						//Send Notification
						$json_data  = json_encode(array('post_id'=>$post_id,'comment_id'=>$comment_id));
						$postData  = DB::table("post_comments")->where("id",$comment_id)->select("user_id")->first();
						$this->save_notification(Input::get('user_id'),$postData->user_id,'like_on_comment',$json_data);
					}else{
						DB::table('post_comment_likes')->where('post_id',$post_id)->where('comment_id',$comment_id)->where('user_id',$user_id)->delete();
						$response["status"]			=	"success";
						$response["message"]		=	"Unlike on comment";
						$response["data"]			=	[];
					}
					
					
				
				} 
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	public function reply_on_comment(){
		//~ $request	=	$this->decrypt($formData["request"]);
		//~ Input::replace($this->arrayStripTags($request));
		$formData	=	Input::all(); 
		$response	=	array();
		if(!empty($formData)){ 
				$validator 	=	Validator::make(
						Input::all(),
						array(
							'device_type'			=> 'required',
							'device_id'				=> 'required',
							'user_id'			=> 'required',
							'post_id'			=> 'required',
							'comment_id'		=> 'required',
							'comment'			=> 'required',
						),
						array(
							'user_id.required'			=> 'Please enter user id.',
							'post_id.required'			=> 'Please enter post id.',
							'comment_id.required'		=> 'Please enter comment id.',
							'comment.required'			=> 'Please enter comment.',
						)
					);
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$obj 					=  new PostComment;
					$obj->post_id			=  Input::get('post_id');	
					$obj->parent_id			=  Input::get('comment_id');	
					$obj->user_id			=  Input::get('user_id');	
					$obj->comment			=  Input::get('comment');	
					$obj->is_like			=  0;	
					$obj->save();
					//Send Notification
					$json_data  = json_encode(array('post_id'=>Input::get('post_id'),'comment_id'=>Input::get('comment_id')));
					$postData  = DB::table("post_comments")->where("id",Input::get('comment_id'))->select("user_id")->first();
					$this->save_notification(Input::get('user_id'),$postData->user_id,'reply_on_comment',$json_data);
					
					$post_comment_details		=	$this->get_post_comment_detail_by_id($obj->id);
					
					$response["status"]		=	"success";
					$response["message"]	=	"You have successfully comment on a post comment.";
					$response["data"]		=	$post_comment_details;
				}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	public function total_reply_on_comment(){
		//~ $request	=	$this->decrypt($formData["request"]);
		//~ Input::replace($this->arrayStripTags($request));
		$formData	=	Input::all(); 
		$response	=	array();
		if(!empty($formData)){ 
				$validator 	=	Validator::make(
						Input::all(),
						array(
							'device_type'			=> 'required',
							'device_id'				=> 'required',
							'post_id'			=> 'required',
							'comment_id'		=> 'required',
						),
						array(
							'post_id.required'			=> 'Please enter post id.',
							'comment_id.required'		=> 'Please enter comment id.',
						)
					);
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$post_id			=	Input::get('post_id');
					$comment_id			=	Input::get('comment_id');
					$reply_on_comment	=	DB::table('post_comments')->where('parent_id','!=',0)->where('post_id',$post_id)->where('is_active',1)->where('is_deleted',0)->where('parent_id',$comment_id)->get();
					$reply_on_comment	=	json_decode(json_encode($reply_on_comment));
					if(!empty($reply_on_comment)){
						$response["status"]			=	"success";
						$response["message"]		=	"Post reply on comments found successfully";
						$response["data"]			=	$reply_on_comment;
					}else{
						$response["status"]			=	"error";
						$response["message"]		=	"No reply found on comment.";
						$response["data"]			=	array();
					}
				}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	public function get_comment_thread(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			if(!empty(Input::get('post_id'))){
				$validator 	=	Validator::make(
						Input::all(),
						array(
							'device_type'			=> 'required',
							'device_id'				=> 'required',
							'post_id'				=> 'required',
							'user_id'				=> 'required',
							'comment_id'			=> 'required',
						),
						array(
							'post_id.required'			=> 'Please enter post id.',
							'user_id.required'			=> 'Please enter user id.',
							'comment_id.required'		=> 'Please enter comment id.',
						)
					);
				if ($validator->fails()){
					$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
				}else{
					$post_id		=	Input::get('post_id');
					$user_id		=	Input::get('user_id');
					$comment_id		=	Input::get('comment_id');
					$post_comments	=	PostComment::with(array('getChildComments'=>function($query) use ($user_id){
											$query->select("id","post_id","parent_id","user_id","comment","created_at",
											DB::raw("(select users.full_name from users where id =post_comments.user_id) as full_name"),
											DB::raw("(select users.image from users where id =post_comments.user_id) as profile_image"),
											DB::raw("(select count(id) from post_comment_likes where post_comment_likes.comment_id =post_comments.id) as total_likes_on_comment"),
											DB::raw("(select count(id) from post_comment_likes where post_comment_likes.comment_id =post_comments.id AND post_comment_likes.user_id=$user_id) as like_by_auth_user"));
										}))
										->where('post_id',$post_id)->where('id',$comment_id)->where("parent_id",0)
										->select(
											'post_comments.id',	
											'post_comments.post_id',	
											'post_comments.parent_id',	
											'post_comments.user_id',	
											'post_comments.comment',
											'post_comments.created_at',
											DB::raw("(select users.full_name from users where id =post_comments.user_id) as full_name"),
											DB::raw("(select users.image from users where id =post_comments.user_id) as profile_image"),
											DB::raw("(select count(id) from post_comment_likes where post_comment_likes.comment_id =post_comments.id) as total_likes_on_comment"),
											DB::raw("(select count(id) from post_comment_likes where post_comment_likes.comment_id =post_comments.id AND post_comment_likes.user_id=$user_id) as like_by_auth_user")
										)
										->first();
					
					$post_comments	=	json_decode(json_encode($post_comments));
					
					if(!empty($post_comments)){
						//foreach($post_comments as &$data){
							$profile_image		=	DB::table('users')->where('id',$post_comments->user_id)->select('image')->first();
							if(!empty($profile_image)){
								if(!empty($profile_image->image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$profile_image->image)){
									$post_comments->profile_image		=	USER_PROFILE_IMAGE_URL.$profile_image->image;
								}else {
									$post_comments->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
								}
							}
							$post_comments->created_time		=		Carbon::parse($post_comments->created_at)->diffForHumans();
							if(!empty($post_comments->get_child_comments)){
								foreach($post_comments->get_child_comments as &$childData){
									$profile_image		=	DB::table('users')->where('id',$childData->user_id)->select('image')->first();
									if(!empty($profile_image)){
										if(!empty($profile_image->image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$profile_image->image)){
											$childData->profile_image		=	USER_PROFILE_IMAGE_URL.$profile_image->image;
										}else {
											$childData->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
										}
									}
									$childData->created_time		=		Carbon::parse($childData->created_at)->diffForHumans();
								}
							}
						//}
					}
					
					if(!empty($post_comments)){
						$response["status"]			=	"success";
						$response["message"]		=	"Post comments found successfully";
						foreach($post_comments as $key=>$data){
							$response[$key]				=	$data;
						}
					}else{
						$response["status"]			=	"error";
						$response["message"]		=	"No comments found.";
						$response["data"]			=	array();
					}
				}
			}else{
				$response["status"]		=	"error";
				$response["message"]	=	"Invalid Request.";
				$response["data"]		=	array();
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		//return $this->encrypt($response);
		return json_encode($response);
	}
	
	/*
	public function send_friend_request($user_id,$reciever_id){
		if(!empty($user_id) && !empty($reciever_id)){
			$obj 				=		new FriendRequest;
			$obj->sender_id		=		$user_id;
			$obj->reciever_id	=		$reciever_id;
			$obj->is_accept		=		0;
			$obj->save();
			
			$requestId		=	$obj->id;
			$details		=	DB::table('friend_requests')->leftjoin('users','id','=','sender_id')->select('friend_requests.*','users.full_name')->where('is_active',1)->where('is_deleted',0)->where('id',$requestId)->get();
			
			$response["status"]		=	"success";
			$response["message"]	=	"Your request has been sent successfully.";
			$response["data"]		=	$details;
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);		
	}
	
	public function accept_friend_request($user_id,$sender_id){
		if(!empty($user_id) && !empty($sender_id)){
			$friendRequest		=		FriendRequest::find($user_id);
			$obj->sender_id		=		$sender_id;
			$obj->reciever_id	=		$user_id;
			$obj->is_accept		=		1;
			$obj->save();
			
			$requestId		=	$obj->id;
			$details		=	DB::table('friend_requests')->where('reciever_id',$user_id)->where('sender_id',$sender_id)->where('is_active',1)->where('is_deleted',0)->get();
			
			$response["status"]		=	"success";
			$response["message"]	=	"Friend request has been accept successfully.";
			$response["data"]		=	$details;
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);		
	}
	
	public function delete_friend_request($user_id,$sender_id){
		if(!empty($user_id) && !empty($sender_id)){
			$details		=	DB::table('friend_requests')->where('reciever_id',$user_id)->where('sender_id',$sender_id)->where('is_active',1)->where('is_deleted',0)->delete();
			$response["status"]		=	"success";
			$response["message"]	=	"Friend request has been delete successfully.";
			$response["data"]		=	array();
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);		
	}
	*/
	public function get_post_detail_by_id($post_id){
		$post_details			=	DB::table('posts') 
								->where('id',$post_id)
								->where('posts.is_active',1)
								->where('posts.is_deleted',0)
								->select('message','total_likes','total_comments','created_at as date',DB::raw("(select users.full_name from users where id =user_id) as full_name"),DB::raw("(select users.image from users where id =user_id) as profile_image"))
								->first();
								
			
		if(!empty($post_details)){
			$post_details->date		=	DB::table('posts')->where('id',$post_id)->select('created_at')->get();
			if(!empty($post_details->date)){
				foreach($post_details->date as $key=>$date){
					$post_details->date	=	Carbon::parse($date->created_at)->diffForHumans();
				}
			}
		}
		if(!empty($post_details)){
			$post_details->images		=	DB::table('post_images')->where('post_id',$post_id)->select('image','type')->get();
			if(!empty($post_details->images)){
				foreach($post_details->images as $key=>$image){
					if(!empty($image->type) && $image->type  == 'video'){
						$image->video_thumbnail		=	WEBSITE_IMG_URL.'video-thumbnail.jpg';
					}
					if(!empty($image->image) && file_exists(POST_IMAGE_ROOT_PATH.$image->image)){
						$image->image		=	POST_IMAGE_URL.$image->image;
					}else {
						$image->image		=	WEBSITE_IMG_URL.'no_image.jpg';
					}
				}
			}
			
			$like_types	=	DB::table('post_likes')->where('post_id',$post_id)->groupBy('type')->pluck("type","type");
			$like_types		=	json_decode(json_encode($like_types));
			if(!empty($like_types)){
				foreach($like_types as &$type){
					$likeArr = DB::table('post_likes')->where('post_likes.post_id',$post_like->id)->where('type',$type)->select('post_likes.id','post_likes.type','post_likes.user_id',DB::raw("(select users.full_name from users where id =post_likes.user_id) as full_name"),DB::raw("(select users.image from users where id =post_likes.user_id) as profile_image"))->get();
					if(!empty($likeArr)){
						$post_details->$type	= $likeArr;
						if(!empty($post_details->$type)){
							foreach($post_details->$type as $like){
								if(!empty($like->profile_image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$like->profile_image)){
									$like->profile_image		=	USER_PROFILE_IMAGE_URL.$like->profile_image;
								}else {
									$like->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
								}
							}
						}
					}
				}
			}
			
			$post_details->comments	=	DB::table('post_comments')->where('post_id',$post_id)->where('post_comments.parent_id','=',0)->select('post_comments.id','post_comments.comment as comment','post_comments.is_like as total_likes','post_comments.user_id',DB::raw("(select users.full_name from users where id =post_comments.user_id) as full_name"),DB::raw("(select users.image from users where id =post_comments.user_id) as profile_image"),DB::raw("(select count(id) from post_comments where id =post_comments.id) as total_comments"))->get();
			$post_details->comments		=	json_decode(json_encode($post_details->comments));
			if(!empty($post_details->comments)){
				foreach($post_details->comments as &$comments){
					if(!empty($comments->profile_image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$comments->profile_image)){
						$comments->profile_image		=	USER_PROFILE_IMAGE_URL.$likes->profile_image;
					}else {
						$comments->profile_image		=	WEBSITE_IMG_URL.'no_image.jpg';
					}
				}
			}
		}
		return $post_details;
	}
	
	public function get_post_comment_detail_by_id($comment_id){
		$post_comment_details			=	DB::table('post_comments')->where('id',$comment_id)->select('*')->first();
		
		return $post_comment_details;
	}
	
	public function wall(){
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
					'limit'				=> 'required',
					'page'				=> 'required',
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id = $formData['user_id'];
				$page    			=  	(Input::get('page')) ? Input::get('page')-1   : 0;
				$offset   			=  	$page*Input::get('limit');
				$limit   			=  	Input::get('limit');
				$deletedUsers = DB::table("users")->Orwhere('is_active',0)->Orwhere('is_deleted',1)->pluck('id','id');
				$dancers	 =  DB::table('friend_requests')
								->where('is_accept',1)
								->where(function ($query) use($user_id){
									$query->Orwhere("friend_requests.user_id",$user_id);
									$query->Orwhere("friend_requests.friend_id",$user_id);
								})
								->whereNotIn("friend_requests.user_id",$deletedUsers)
								->whereNotIn("friend_requests.friend_id",$deletedUsers)
								->select(
									DB::raw("IF((user_id != $user_id),(user_id),(friend_id)) as user_id")
								)
								->pluck('user_id','user_id')->toArray();
				
				$post_lists = array();
				$dancers		=	array_merge($dancers,array($user_id=>$user_id));
				$post_lists		=	DB::table('posts')
										->where(function ($query) use($dancers){
											$query->orWhereIn('posts.user_id',$dancers);
											$query->orWhere('posts.user_id',1);
										})
										->where('posts.is_active',1)
										->where('posts.is_deleted',0)
										->offset($offset)
										->limit($limit)
										->select('posts.message','posts.id','posts.user_id','posts.created_at as date',DB::raw("(select users.full_name from users where id =posts.user_id) as full_name"),DB::raw("(select users.image from users where id =posts.user_id) as profile_image"),DB::raw("(select count(id) from post_likes where post_likes.post_id =posts.id) as total_likes"),DB::raw("(select count(id) from post_comments where post_comments.post_id =posts.id) as total_comments"),DB::raw("(select count(id) from post_likes where post_likes.post_id =posts.id AND post_likes.user_id=$user_id) as user_like"),DB::raw("null as likes"),DB::raw("null as last_comments"))
										->orderBy("created_at",'DESC')
										->get();
				$post_lists		=	json_decode(json_encode($post_lists));
				$totalCount = count($post_lists);
				if(!empty($post_lists)){
					foreach($post_lists as &$post_details){
						$post_details->date		=	DB::table('posts')->where('id',$post_details->id)->select('created_at')->get();
						if(!empty($post_details->date)){
							foreach($post_details->date as $key=>$date){
								$post_details->date	=	Carbon::parse($date->created_at)->diffForHumans();
							}
						}
					}
					foreach($post_lists as &$user_image){
						$user_image->profile_image		=	DB::table('users')->where('id',$user_image->user_id)->select('image')->get();
						if(!empty($user_image->profile_image)){
							foreach($user_image->profile_image as $key=>$image){
								if(!empty($image->image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$image->image)){
									
									$image->image		=	USER_PROFILE_IMAGE_URL.$image->image;
								}else {
									$image->image		=	WEBSITE_IMG_URL.'usr_img.png';
								}
							}
						}
					}
					
					foreach($post_lists as &$post_image){
						$post_image->images		=	DB::table('post_images')->where('post_id',$post_image->id)->select('image','type')->get();
						if(!empty($post_image->images)){
							foreach($post_image->images as $key=>$image){
								if(!empty($image->type) && $image->type  == 'video'){
									$imageArray = explode('.',$image->image);
									$name		= isset($imageArray[0]) ? $imageArray[0] : '';
									$imageName  = $name.".jpg";
									if(!empty($imageName) && file_exists(POST_IMAGE_ROOT_PATH.$imageName)){
										$image->video_thumbnail		=	POST_IMAGE_URL.$imageName;
									}else{
										$image->video_thumbnail		=	WEBSITE_IMG_URL.'video-thumbnail.jpg';
									}
								}
								if(!empty($image->image) && file_exists(POST_IMAGE_ROOT_PATH.$image->image)){
									$image->image		=	POST_IMAGE_URL.$image->image;
								}else {
									$image->image		=	WEBSITE_IMG_URL.'no_image.jpg';
								}
							}
						}
					}
					
					$likeArr	=	[];
					foreach($post_lists as &$post_like){
						$like_types	=	DB::table('post_likes')->where('post_likes.post_id',$post_like->id)->groupBy('type')->pluck("type","type");
						if(!empty($like_types)){
							foreach($like_types as &$type){
								$post_like->likes[$type] = DB::table('post_likes')->where('post_likes.post_id',$post_like->id)->where('type',$type)->select('post_likes.id','post_likes.type','post_likes.user_id',DB::raw("(select users.full_name from users where id =post_likes.user_id) as full_name"),DB::raw("(select users.image from users where id =post_likes.user_id) as profile_image"))->get();
								if(!empty($post_like->likes[$type])){
									$likeArr	= $post_like->likes[$type];
									if(!empty($likeArr)){
										foreach($likeArr as $like){
											if(!empty($like->profile_image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$like->profile_image)){
												$like->profile_image		=	USER_PROFILE_IMAGE_URL.$like->profile_image;
											}else {
												$like->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
											}
										}
									}
								}
							}
						}
					}
					
					foreach($post_lists as &$post_comment){
						$post_comment->comments	=	DB::table('post_comments')->where('post_comments.post_id',$post_comment->id)->where('post_comments.parent_id','=',0)->select('post_comments.id','post_comments.comment as comment','post_comments.is_like as total_likes','post_comments.user_id',DB::raw("(select users.full_name from users where id =post_comments.user_id) as full_name"),DB::raw("(select users.image from users where id =post_comments.user_id) as profile_image"))->get();
						if(!empty($post_comment->comments)){
							foreach($post_comment->comments as &$comments){
								if(!empty($comments->profile_image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$comments->profile_image)){
									$comments->profile_image		=	USER_PROFILE_IMAGE_URL.$comments->profile_image;
								}else{
									$comments->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
								}
							}
						}
					}
					
					foreach($post_lists as &$post_comment){
						$post_comment->last_comments	=	DB::table('post_comments')->where('post_comments.post_id',$post_comment->id)->where('post_comments.parent_id','=',0)->select('post_comments.comment as comment','post_comments.created_at',DB::raw("(select users.full_name from users where id =post_comments.user_id) as full_name"),DB::raw("(select users.image from users where id =post_comments.user_id) as profile_image"))->orderBy("created_at","DESC")->first();
						if(!empty($post_comment->last_comments)){
							$last_comments		=	$post_comment->last_comments;
							if(!empty($last_comments->profile_image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$last_comments->profile_image)){
								$last_comments->profile_image		=	USER_PROFILE_IMAGE_URL.$last_comments->profile_image;
							}else{
								$last_comments->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
							}
							if(!empty($last_comments->created_at)){
								$last_comments->created_at		=		Carbon::parse($last_comments->created_at)->diffForHumans();
							}
						}
					}
				}
				$response["status"]				=	"success";
				$response["message"]			=	"Wall Post";
				$response["posts"]				=	$post_lists;
				$response["totalCount"]			=	$totalCount;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);	
	}
	
	public function fan_wall(){
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
					'limit'				=> 'required',
					'page'				=> 'required',
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id = $formData['user_id'];
				$page    			=  	(Input::get('page')) ? Input::get('page')-1   : 0;
				$offset   			=  	$page*Input::get('limit');
				$limit   			=  	Input::get('limit');
				$deletedUsers = DB::table("users")->Orwhere('is_active',0)->Orwhere('is_deleted',1)->pluck('id','id');
				$dancers	 =  DB::table('follow_requests')
								->where('is_accept',1)
								->where(function ($query) use($user_id){
									$query->Orwhere("follow_requests.user_id",$user_id);
									$query->Orwhere("follow_requests.friend_id",$user_id);
								})
								->whereNotIn("follow_requests.user_id",$deletedUsers)
								->whereNotIn("follow_requests.friend_id",$deletedUsers)
								->select(
									DB::raw("IF((user_id != $user_id),(user_id),(friend_id)) as user_id")
								)
								->pluck('user_id','user_id')->toArray();
				
				$post_lists = array();
				$dancers		=	array_merge($dancers,array($user_id=>$user_id));
				$post_lists		=	DB::table('posts')
										->where(function ($query) use($dancers){
											$query->orWhereIn('posts.user_id',$dancers);
											$query->orWhere('posts.user_id',1);
										})
										->where('posts.is_active',1)
										->where('posts.is_deleted',0)
										->offset($offset)
										->limit($limit)
										->select('posts.message','posts.id','posts.user_id','posts.created_at as date',DB::raw("(select users.full_name from users where id =posts.user_id) as full_name"),DB::raw("(select users.image from users where id =posts.user_id) as profile_image"),DB::raw("(select count(id) from post_likes where post_likes.post_id =posts.id) as total_likes"),DB::raw("(select count(id) from post_comments where post_comments.post_id =posts.id) as total_comments"),DB::raw("(select count(id) from post_likes where post_likes.post_id =posts.id AND post_likes.user_id=$user_id) as user_like"),DB::raw("null as likes"))
										->orderBy("created_at",'DESC')
										->get();
				$post_lists		=	json_decode(json_encode($post_lists));
				$totalCount = count($post_lists);
				if(!empty($post_lists)){
					foreach($post_lists as &$post_details){
						$post_details->date		=	DB::table('posts')->where('id',$post_details->id)->select('created_at')->get();
						if(!empty($post_details->date)){
							foreach($post_details->date as $key=>$date){
								$post_details->date	=	Carbon::parse($date->created_at)->diffForHumans();
							}
						}
					}
					foreach($post_lists as &$user_image){
						$user_image->profile_image		=	DB::table('users')->where('id',$user_image->user_id)->select('image')->get();
						if(!empty($user_image->profile_image)){
							foreach($user_image->profile_image as $key=>$image){
								if(!empty($image->image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$image->image)){
									
									$image->image		=	USER_PROFILE_IMAGE_URL.$image->image;
								}else {
									$image->image		=	WEBSITE_IMG_URL.'usr_img.png';
								}
							}
						}
					}
					
					foreach($post_lists as &$post_image){
						$post_image->images		=	DB::table('post_images')->where('post_id',$post_image->id)->select('image','type')->get();
						if(!empty($post_image->images)){
							foreach($post_image->images as $key=>$image){
								if(!empty($image->type) && $image->type  == 'video'){
									$imageArray = explode('.',$image->image);
									$name		= isset($imageArray[0]) ? $imageArray[0] : '';
									$imageName  = $name.".jpg";
									if(!empty($imageName) && file_exists(POST_IMAGE_ROOT_PATH.$imageName)){
										$image->video_thumbnail		=	POST_IMAGE_URL.$imageName;
									}else{
										$image->video_thumbnail		=	WEBSITE_IMG_URL.'video-thumbnail.jpg';
									}
								}
								if(!empty($image->image) && file_exists(POST_IMAGE_ROOT_PATH.$image->image)){
									$image->image		=	POST_IMAGE_URL.$image->image;
								}else {
									$image->image		=	WEBSITE_IMG_URL.'no_image.jpg';
								}
							}
						}
					}
					
					$likeArr	=	[];
					foreach($post_lists as &$post_like){
						$like_types	=	DB::table('post_likes')->where('post_likes.post_id',$post_like->id)->groupBy('type')->pluck("type","type");
						if(!empty($like_types)){
							foreach($like_types as &$type){
								$post_like->likes[$type] = DB::table('post_likes')->where('post_likes.post_id',$post_like->id)->where('type',$type)->select('post_likes.id','post_likes.type','post_likes.user_id',DB::raw("(select users.full_name from users where id =post_likes.user_id) as full_name"),DB::raw("(select users.image from users where id =post_likes.user_id) as profile_image"),DB::raw("null as last_comments"))->get();
								if(!empty($post_like->likes[$type])){
									$likeArr	= $post_like->likes[$type];
									if(!empty($likeArr)){
										foreach($likeArr as $like){
											if(!empty($like->profile_image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$like->profile_image)){
												$like->profile_image		=	USER_PROFILE_IMAGE_URL.$like->profile_image;
											}else {
												$like->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
											}
										}
									}
								}
							}
						}
					}
					
					foreach($post_lists as &$post_comment){
						$post_comment->comments	=	DB::table('post_comments')->where('post_comments.post_id',$post_comment->id)->where('post_comments.parent_id','=',0)->select('post_comments.id','post_comments.comment as comment','post_comments.is_like as total_likes','post_comments.user_id',DB::raw("(select users.full_name from users where id =post_comments.user_id) as full_name"),DB::raw("(select users.image from users where id =post_comments.user_id) as profile_image"))->get();
						if(!empty($post_comment->comments)){
							foreach($post_comment->comments as &$comments){
								if(!empty($comments->profile_image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$comments->profile_image)){
									$comments->profile_image		=	USER_PROFILE_IMAGE_URL.$comments->profile_image;
								}else{
									$comments->profile_image		=	WEBSITE_IMG_URL.'no_image.jpg';
								}
							}
						}
					}
					
					foreach($post_lists as &$post_comment){
						$post_comment->last_comments	=	DB::table('post_comments')->where('post_comments.post_id',$post_comment->id)->where('post_comments.parent_id','=',0)->select('post_comments.comment as comment','post_comments.created_at',DB::raw("(select users.full_name from users where id =post_comments.user_id) as full_name"),DB::raw("(select users.image from users where id =post_comments.user_id) as profile_image"))->orderBy("created_at","DESC")->first();
						if(!empty($post_comment->last_comments)){
							$last_comments		=	$post_comment->last_comments;
							if(!empty($last_comments->profile_image) && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$last_comments->profile_image)){
								$last_comments->profile_image		=	USER_PROFILE_IMAGE_URL.$last_comments->profile_image;
							}else{
								$last_comments->profile_image		=	WEBSITE_IMG_URL.'usr_img.png';
							}
							if(!empty($last_comments->created_at)){
								$last_comments->created_at		=		Carbon::parse($last_comments->created_at)->diffForHumans();
							}
						}
					}
				}
				$response["status"]				=	"success";
				$response["message"]			=	"Wall Post";
				$response["posts"]				=	$post_lists;
				$response["totalCount"]			=	$totalCount;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);	
	}
	
	public function notifications(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'		=> 'required',
					'device_id'			=> 'required',
					'user_id'			=> 'required',
					'limit'				=> 'required',
					'page'				=> 'required',
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$page    			=  	(Input::get('page')) ? Input::get('page')-1   : 0;
				$offset   			=  	$page*Input::get('limit');
				$limit   			=  	Input::get('limit');
				$notifications  = DB::table("notifications")->where("is_deleted",0)->where("receiver_id",Input::get("user_id"))
									->offset($offset)
									->limit($limit)
									->orderBy("created_at","DESC")
									->get();
				$data = array();
				if(!empty($notifications)){
					foreach($notifications as $key=>$noti){
						$messageData = $this->get_notification_message($noti->sender_id,$noti->receiver_id,$noti->type ,$noti->jsondata);
						$data[$key]['message']  	= $messageData['message'];
						$data[$key]['title']  		= $messageData['title'];
						$data[$key]['image']  		= $messageData['image'];
						$data[$key]['sender_id']  	= $noti->sender_id;
						$data[$key]['receiver_id']  = $noti->receiver_id;
						$data[$key]['type']  		= $noti->type;
						$data[$key]['jsondata']  	= $noti->jsondata;
						if(!empty($noti->created_at)){
							$data[$key]['date'] =	Carbon::parse($noti->created_at)->diffForHumans();
						}
						Notification::where('id',$noti->id)->update(array('is_read'=>1));
					}
				}
				$response["status"]		=	"success";
				$response["message"]	=	"Notifications.";
				$response["data"]		=   $data;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);	
	}
	
	public function get_unread_notification_count(){
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
				$page    			=  	(Input::get('page')) ? Input::get('page')-1   : 0;
				$offset   			=  	$page*Input::get('limit');
				$limit   			=  	Input::get('limit');
				$notifications  = DB::table("notifications")->where("is_deleted",0)->where("is_read",0)->where("receiver_id",Input::get("user_id"))->count();
				$response["status"]		=	"success";
				$response["message"]	=	"Total Notifications Count";
				$response["data"]		=   $notifications;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
}
