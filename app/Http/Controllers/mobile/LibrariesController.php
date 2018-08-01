<?php
namespace App\Http\Controllers\mobile;
use App\Http\Controllers\BaseController;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use App\Model\ProjectFolder;
use App\Model\ProjectFolderArticle;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,App;
use Carbon\Carbon;

/**
* Libraries Controller
*
* Add your methods in the class below
*
* This file use for call api
*/
class LibrariesController extends BaseController {
	
	public function library_folders(){
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
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id = Input::get("user_id");
				$folders 				= 	ProjectFolder::where("is_active",1)->where("is_deleted",0)->orderBy("category_order","ASC")->select("id","slug","name","image","description",DB::raw("(select count(id) from project_folder_articles where project_folder_id=project_folders.id AND is_active=1 AND is_deleted=0 AND id NOT IN(select article_id from read_articles where category_id=project_folders.id AND user_id = $user_id)) as new_articles"))->get();
				if(!empty($folders)){
					foreach($folders as &$folder){
						if($folder->image != "" && File::exists(PROJECT_FOLDER_IMAGE_ROOT_PATH.$folder->image)){
							$folder->image = PROJECT_FOLDER_IMAGE_URL.$folder->image;
						}else{
							$folder->image = WEBSITE_IMG_URL.'no_image.jpg';
						}
					}
				}
				$response["status"]		=	"success";
				$response["message"]	=	"Library Folders.";
				$response["data"]		=	$folders;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}	
	
	public function folder_detail($slug=null){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'user_id'			=> 'required',
									'slug'				=> 'required',
									'page'				=> 'required',
									'limit'				=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$slug			=	Input::get('slug');
				$page    		=  	(Input::get('page')) ? Input::get('page')-1   : 0;
				$offset			=	$page*Input::get('limit');
				$limit			=	Input::get('limit');
				$user_id		=	Input::get('user_id');
				$DB 			= 	ProjectFolder::query();	
				$data			=	array();
				$folders		=	DB::table('project_folders')->where("is_active",1)->where("is_deleted",0)->select('name')->orderBy("category_order","ASC")->get();
				$data['folderData']		=	$DB->where("slug",$slug)->select('id','name','image','description',DB::raw("(select count(id) from project_folder_articles where project_folder_id=project_folders.id AND is_active=1 AND is_deleted=0 AND id NOT IN(select article_id from read_articles where category_id=project_folders.id AND user_id = $user_id)) as new_articles"))->first();
				if(!empty($data['folderData'])){
					$folderData		=	$data['folderData'];
					if($folderData->image != "" && File::exists(PROJECT_FOLDER_IMAGE_ROOT_PATH.$folderData->image)){
						$folderData->image = PROJECT_FOLDER_IMAGE_URL.$folderData->image;
					}else{
						$folderData->image = WEBSITE_IMG_URL.'no_image.jpg';
					}
				}
				$folder_id		=	$data['folderData']->id;
				if(!empty($folder_id)){
					$data['articleData']	=	DB::table('project_folder_articles')
													->where('project_folder_id',$folder_id)
													->where("is_active",1)->where("is_deleted",0)
													->offset($offset)
													->limit($limit)
													->orderby('id','DESC')
													->get();
					$data['totalCount'] = 0;
					if(!empty($data['articleData'])){
						$articleData		=	$data['articleData'];
						foreach($articleData as &$article){
							if($article->image != "" && File::exists(PROJECT_ARTICLE_IMAGE_ROOT_PATH.$article->image)){
								$article->image = PROJECT_ARTICLE_IMAGE_URL.$article->image;
							}else{
								$article->image = WEBSITE_IMG_URL.'no_image.jpg';
							}
						}
						$data['totalCount'] = count($articleData);
					}
				}
				
				$response["status"]		=	"success";
				$response["message"]	=	"Library Folders.";
				$response["data"]		=	$data;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}	
	
	public function topic_detail($slug=null){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'slug'				=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$slug			=	Input::get('slug');
				$DB 			= 	ProjectFolderArticle::query();	
				$articleData		=	DB::table('project_folder_articles')->where("slug",$slug)->select('id','article_name','article_description','image',DB::raw("(SELECT name from project_folders where id=project_folder_articles.project_folder_id LIMIT 1) as folder_name"),DB::raw("(SELECT description from project_folders where id=project_folder_articles.project_folder_id LIMIT 1) as folder_description"))->first();
				if(!empty($articleData)){
					if($articleData->image != "" && File::exists(PROJECT_ARTICLE_IMAGE_ROOT_PATH.$articleData->image)){
						$articleData->image = PROJECT_ARTICLE_IMAGE_URL.$articleData->image;
					}else{
						$articleData->image = WEBSITE_IMG_URL.'no_image.jpg';
					}
				}
				$response["status"]		=	"success";
				$response["message"]	=	"Library topic.";
				$response["data"]		=	$articleData;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}	
	
	public function most_viewed_topics(){
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
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$mostViewedArticles	=	DB::table("project_folder_articles")->where("project_folder_articles.is_active",1)->where("project_folder_articles.is_deleted",0)->select('project_folder_articles.id','project_folder_articles.article_name','project_folder_articles.article_description')->limit(5)->orderBy("viewed","DESC")->get();
				
				$response["status"]		=	"success";
				$response["message"]	=	"Most Viewed Topics.";
				$response["data"]		=	$mostViewedArticles;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function recently_added_topics(){
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
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$recentAddedArticles	=	DB::table("project_folder_articles")->where("project_folder_articles.is_active",1)->where("project_folder_articles.is_deleted",0)->select('project_folder_articles.id','project_folder_articles.article_name','project_folder_articles.article_description')->orderBy("id","DESC")->limit(5)->get();
				
				$response["status"]		=	"success";
				$response["message"]	=	"Recently added Topics.";
				$response["data"]		=	$recentAddedArticles;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function get_newsfeed(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'		=> 'required',
					'device_id'			=> 'required',
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$newsfeeds  = DB::table("newsfeeds")->where("is_deleted",0)->where("is_active",1)->select("name","description","created_At","id")->orderBy("id","DESC")->limit(5)->get();
				$response["status"]		=	"success";
				$response["message"]	=	"Newsfeeds";
				$response["data"]		=   $newsfeeds;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function sell_all_newsfeeds(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'		=> 'required',
					'device_id'			=> 'required',
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$newsfeeds  = DB::table("newsfeeds")->where("is_deleted",0)->where("is_active",1)->select("name","description","created_At","id")->orderBy("id","DESC")->get();
				$response["status"]		=	"success";
				$response["message"]	=	"Newsfeeds";
				$response["data"]		=   $newsfeeds;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function newsfeed_detail(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
				Input::all(),
				array(
					'device_type'		=> 'required',
					'device_id'			=> 'required',
					'id'				=> 'required',
				)
			);
			if ($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$newsfeed  = DB::table("newsfeeds")->where("is_deleted",0)->where("is_active",1)->where("id",Input::get("id"))->select("name","description","created_At","id")->first();
				$response["status"]		=	"success";
				$response["message"]	=	"Newsfeed Detail";
				$response["data"]		=   $newsfeed;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
	
	public function check_this_out_topics(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$checkThisOutTopics	=	DB::table("project_folder_articles")->where("project_folder_articles.is_check_this_out",1)->where("project_folder_articles.is_deleted",0)->select('project_folder_articles.id','project_folder_articles.article_name','project_folder_articles.article_description')->orderBy("viewed","DESC")->get();
				if(!($checkThisOutTopics)->isEmpty()){
					$response["status"]		=	"success";
					$response["message"]	=	"Check this out topics.";
					$response["data"]		=	$checkThisOutTopics;
				}else{
					$response["status"]		=	"error";
					$response["message"]	=	"No record found.";
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
	
	public function search_library(){
		$formData	=	Input::all();
		$response	=	array();
		$totalArticleSearch		=	0;
		$result	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
								Input::all(),
								array(
									'device_type'		=> 'required',
									'device_id'			=> 'required',
									'keyword'			=> 'required',
								)
							);
			if($validator->fails()){
				$response			=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$keyword	=	Input::get("keyword");
				$DB 		= 	ProjectFolderArticle::query();
				$DB->orWhere('project_folder_articles.article_name', "LIKE","%".$keyword."%");		
				$DB->orWhere('project_folders.name', "LIKE","%".$keyword."%");		
				$DB->orWhere('project_folders.description', "LIKE","%".$keyword."%");		
				$categoryList	=	$DB
									->leftjoin('project_folders','project_folders.id','=','project_folder_articles.project_folder_id')
										->groupBy('project_folder_articles.slug','project_folder_articles.user_id','project_folder_articles.id','project_folders.name')
										->orderBy('project_folders.name')
										->pluck('project_folders.name as folder_name','project_folders.id as id')->toArray();
				
				$result			=	array();
				if(!empty($categoryList)){
					$key = 0;
					foreach($categoryList as &$list){
						$articles 		= 	ProjectFolderArticle::leftjoin('project_folders','project_folders.id','=','project_folder_articles.project_folder_id')
												->orWhere('project_folders.name',"LIKE","%".$list."%")
												->orWhere('project_folder_articles.article_name', "LIKE","%".$keyword."%")
												->groupBy('project_folder_articles.slug','project_folder_articles.user_id','project_folder_articles.id')
												->select("project_folder_articles.*",'project_folders.name as folder_name','project_folders.description as folder_description','project_folders.slug as folder_slug',DB::raw("(SELECT full_name from users where id=project_folder_articles.user_id) as username"))
												->get();
						
						if(!empty($articles)){
							foreach($articles as &$article){
								if(!empty($article->article_name)){
									$highlighted_span					=	'<span style="color:red;background-color:yellow;">'.$keyword.'</span>';
									$article->article_name			=	str_ireplace($keyword,$highlighted_span,$article->article_name);
								}
								if(!empty($article->article_description)){
									$highlighted_span					=	'<span style="color:red;background-color:yellow;">'.$keyword.'</span>';
									$article->article_description	=	str_ireplace($keyword,$highlighted_span,$article->article_description);
								}
								if($article->image != "" && File::exists(PROJECT_ARTICLE_IMAGE_ROOT_PATH.$article->image)){
									$article->image = PROJECT_ARTICLE_IMAGE_URL.$article->image;
								}else{
									$article->image = '';
								}
							}
						}
						
						$result[$key]['folderDetail'] = array('name'=>$list,'articles'=>$articles);
						$totalArticleSearch		+=	count($articles);
						$key++;
					}
				}
				$response["status"]					=	"success";
				$response["message"]				=	"Library Records.";
				$response["result"]					=	$result;
				$response["totalArticleSearch"]		=	$totalArticleSearch;
			}
		}else{
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}	
}
