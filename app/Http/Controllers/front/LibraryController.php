<?php
/**
 * UsersController
 */
namespace App\Http\Controllers\front;
use App\Http\Controllers\BaseController;
use App\Model\User;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\ProjectLibrary;
use App\Model\ProjectFolder;
use App\Model\ProjectFolderArticle;
use App\Model\ProjectArticleComment;
use App\Model\ProjectArticleCommentReply;
use App\Model\LikeUnlikeArticle;
use App\Model\LikeUnlikeArticleComment;
use App\Model\SystemDoc;
use App\Model\Notification;
use App\Model\ProjectArticleHelpfulNothelpful;
use App\Model\ReadArticle;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,App;

class LibraryController extends BaseController {

/** 
 * Function to projectLibrary
 *
 * @param null
 * 
 * @return view page
 */	
	public function projectLibrary($project_slug=null,$main_folder_slug=null,$sub_folder_slug=null,$article_slug=null){
		$DB 			= 	ProjectLibrary::query();	
		$libraryData	=	$DB->where("is_active",1)->with("project_folder")->with("project_sub_folder")->with("project_articles")->get();
		
		$mostViewedArticles	=	DB::table("project_folder_articles")->where("project_folder_articles.is_active",1)->where("project_folder_articles.is_deleted",0)->leftJoin("project_folders","project_folder_articles.project_folder_id","=","project_folders.id")->select('project_folder_articles.*','project_folders.parent_id as main_folder_id','project_folders.slug as sub_folder_slug',DB::raw("(select slug from project_libraries where id=project_folder_articles.project_id)as project_slug"),DB::raw("(select slug from project_folders where id=main_folder_id )as main_folder_slug"))->limit(5)->orderBy("viewed","DESC")->get();
		
		$recentAddedArticles	=	DB::table("project_folder_articles")->where("project_folder_articles.is_active",1)->where("project_folder_articles.is_deleted",0)->leftJoin("project_folders","project_folder_articles.project_folder_id","=","project_folders.id")->select('project_folder_articles.*','project_folders.parent_id as main_folder_id','project_folders.slug as sub_folder_slug',DB::raw("(select slug from project_libraries where id=project_folder_articles.project_id)as project_slug"),DB::raw("(select slug from project_folders where id=main_folder_id )as main_folder_slug"))->orderBy("id","DESC")->limit(5)->get();
			
			//echo '<pre>';print_r($recentAddedArticles);die;
		$likeUnlikeArticle			=	0;
		if(!empty($article_slug)){
			$article_id			=	DB::table("project_folder_articles")->where("slug",$article_slug)->value("id");
			$obj 				=  ProjectFolderArticle::find($article_id);
			$obj->viewed  += 1;
			$obj->save();
			$articleData		=	DB::table("project_folder_articles")->where("id",$article_id)->select("project_folder_articles.*",DB::raw("(select project_number from project_libraries where id=project_folder_articles.project_id)as project_number"),DB::raw("(select author from project_libraries where id=project_folder_articles.project_id)as project_author"),DB::raw("(select COUNT(id) from project_article_comments where article_id=project_folder_articles.id)as total_comments"),DB::raw("(select COUNT(id) from like_unlike_articles where article_id=project_folder_articles.id AND value=1)as total_likes"))->first();
			$articleWebLinks	=	DB::table("project_folder_article_links")->where("article_id",$article_id)->get();
			$articleFiles		=	DB::table("project_folder_article_documents")->where("article_id",$article_id)->get();
			
			$articleComments	=	DB::table("project_article_comments")->where("article_id",$article_id)->select("project_article_comments.*",DB::raw("(select full_name from users where id=project_article_comments.user_id)as user_name"),DB::raw("(select 	image from users where id=project_article_comments.user_id)as user_image"),DB::raw("(select COUNT(id) from project_article_comments where parent_id=project_article_comments.id)as total_comment_reply"))->get();

			$likeUnlikeArticle			=	DB::table("like_unlike_articles")->where("user_id",Auth::user()->id)->where("article_id",$article_id)->count();
			$helpfulNotHelpfulArticle	=	DB::table("project_article_helpful_nothelpful")->where("user_id",Auth::user()->id)->where("article_id",$article_id)->first();

			Session::put("article_detail",$articleData);
			//echo '<pre>';print_r($articleComments);die;
		}
		//print_r($mostViewedArticles);die;
		return View::make('front.library.project_library' , compact('libraryData','mostViewedArticles','recentAddedArticles','article_id','articleData','articleComments','articleWebLinks','articleFiles','project_slug','main_folder_slug','sub_folder_slug','article_slug','likeUnlikeArticle','helpfulNotHelpfulArticle'));
	}// end projectLibrary()

/** 
 * Function to projectLibraryArticles
 *
 * @param null
 * 
 * @return view page
 */	
	public function saveArticalComment(){
		Input::replace($this->arrayStripTags(Input::all()));
		$formData			=	Input::all();
		$user_id		 	=   Auth::user()->id;
		if(!empty($formData)){
			$validator = Validator::make(
				Input::all(),
				array(
					'message'	=> 'required',
				),
				array(
					'message.required'		=>	trans("Please enter comment"),
				)
			);
			if ($validator->fails()){
				$response	=	array(
					'success' 	=> false,
					'errors' 	=> $validator->errors()
				);
				return Response::json($response); 
				die;
			}else{
				$obj 						= 	new ProjectArticleComment();
				$obj->user_id		 		=  	Input::get('user_id');
				$obj->article_id			=  	Input::get('article_id');
				$obj->message				=  	Input::get('message');
				$obj->save();
				$comment_id	=	$obj->id;
				$blog_id	=	$obj->article_id;
				
				if(!empty($comment_id)){
					//Save Notification
					$jsonNotData				=	json_encode(array('blog_id'=>$blog_id,'comment_id'=>$comment_id));
					$notiObj					=	new Notification;
					$notiObj->sender_id			=	Auth::user()->id;
					$notiObj->receiver_id		=	'1';
					$notiObj->jsondata			=	$jsonNotData;
					$notiObj->type				=	BLOG_COMMENT;
					$notiObj->is_read			=	'0';
					$notiObj->is_deleted		=	'0';
					$notiObj->save();
				}
				$response	=	array(
					'success' 	=>	'1',
					'comnt_id' 	=>	$comment_id
				); 
				//Session::flash('flash_notice', trans("messages.forum.comment_posted_successfully"));
				return  Response::json($response); 
				die;
			}
		}

	}// end projectLibraryArticles()

/** 
 * Function to projectArticleComment
 *
 * @param null
 * 
 * @return view page
 */	
	public function projectArticleComment(){
		$comment_id		=	Input::get('comment_id');
		if(!empty($comment_id)){
			$obj 				=  ProjectArticleComment::find($comment_id);
			$obj->viewed  += 1;
			$obj->save();
		}		
		$DB				=	ProjectArticleComment::query();
		$commentData	=	$DB->where("id",$comment_id)->with("comment_reply")->select("project_article_comments.*",DB::raw("(select full_name from users where id=project_article_comments.user_id)as user_full_name"))->first();
		
		$likeUnlikeArticleComment	=	DB::table("like_unlike_article_comments")->where("user_id",Auth::user()->id)->where("comment_id",$comment_id)->count();
		return View::make('front.library.project_article_comments',compact('commentData','likeUnlikeArticleComment','comment_id'));
	}// end projectArticleComment()
/** 
 * Function to ExportArticleToPdf
 *
 * @param null
 * 
 * @return view page
 */	
	public function ExportArticleToPdf(){
		$articleData	=	Session::get("article_detail");
		return View::make('front.library.project_article_pdf',compact('articleData'));
	}// end ExportArticleToPdf()
	
/** 
 * Function to projectLibraryArticles
 *
 * @param null
 * 
 * @return view page
 */	
	public function saveArticleComment(){
		Input::replace($this->arrayStripTags(Input::all()));
		$formData	=	Input::all();
		//pr($formData);die;
		if(!empty($formData)){
			$validator = Validator::make(
				Input::all(),
				array(
					'description' 					=> 	'required'
				),array(
					"description.required"			=>	trans("Please enter description."),
				)
			);
			if($validator->fails()){
				$response	=	array(
					'success' 	=> false,
					'errors' 	=> $validator->errors()
				);
				return Response::json($response); 
				die;
			}else{
				$obj 					=  new ProjectArticleComment;
				$obj->user_id	 		= Input::get('user_id');  
				$obj->article_id 		= Input::get('article_id');  
				$obj->message 			= Input::get('description');
				
				$obj->save();
				$comment_id		=	$obj->id;
				$blog_id		=	Input::get('article_id');  
				
				//Save Notification
				if(!empty($comment_id)){
					$jsonNotData				=	json_encode(array('blog_id'=>$blog_id,'comment_id'=>$comment_id));
					$notiObj					=	new Notification;
					$notiObj->sender_id			=	Auth::user()->id;
					$notiObj->receiver_id		=	'1';
					$notiObj->jsondata			=	$jsonNotData;
					$notiObj->type				=	BLOG_COMMENT;
					$notiObj->is_read			=	'0';
					$notiObj->is_deleted		=	'0';
					$notiObj->save();
				}
			
			$response	=	array(
					'success' 	=> true,
				);
				Session::flash('success', trans("Comment on article added successfully")); 
				return Response::json($response); 
				die;
			}
		}
	}
	public function saveArticleCommentReply(){
		$formData			=	Input::all();
		//$user_id		 	=   Auth::user()->id;
		if(!empty($formData)){
			$validator = Validator::make(
				Input::all(),
				array(
					'reply'	=> 'required',
				),
				array(
					'reply.required'		=>	trans("This field is required."),
				)
			);
			if ($validator->fails()){
				$response	=	array(
					'success' 	=> false,
					'errors' 	=> $validator->errors()
				);
				return Response::json($response); 
				die;
			}else{
				$obj 						= 	new ProjectArticleComment();
				$obj->user_id		 		=  	Input::get('user_id');
				$obj->article_id			=  	Input::get('article_id');
				$obj->parent_id				=  	Input::get('comment_id');
				$obj->message				=  	Input::get('reply');
				$obj->save();
				$response	=	array(
					'success' 	=>	'1',
					'errors' 	=>	 trans("")
				); 
				//Session::flash('flash_notice', trans("messages.forum.comment_posted_successfully"));
				return  Response::json($response); 
				die;
			}
		}

	}// end projectLibraryArticles()
	
	public function saveArticalCommentReply(){
		$formData			=	Input::all();
		$user_id		 	=   Auth::user()->id;
		if(!empty($formData)){
			$validator = Validator::make(
				Input::all(),
				array(
					'reply'	=> 'required',
				),
				array(
					'reply.required'		=>	trans("This field is required."),
				)
			);
			if ($validator->fails()){
				$response	=	array(
					'success' 	=> false,
					'errors' 	=> $validator->errors()
				);
				return Response::json($response); 
				die;
			}else{
				$obj 						= 	new ProjectArticleCommentReply();
				$obj->user_id		 		=  	Input::get('user_id');
				$obj->comment_id			=  	Input::get('comment_id');
				$obj->reply					=  	Input::get('reply');
				$obj->save();
				$response	=	array(
					'success' 	=>	'1',
					'errors' 	=>	 trans("")
				); 
				//Session::flash('flash_notice', trans("messages.forum.comment_posted_successfully"));
				return  Response::json($response); 
				die;
			}
		}

	}// end projectLibraryArticles()

/** 
 * Function to projectLibraryArticles
 *
 * @param null
 * 
 * @return view page
 */	
	public function likeUnlikeArticleComment(){
		$formData			=	Input::all();
		$user_id		 	=   Auth::user()->id;
		if(!empty($formData)){
			if($formData['value'] == 1){
				$obj 						= 	new LikeUnlikeArticleComment();
				$obj->user_id		 		=  	Auth::user()->id;
				$obj->comment_id			=  	Input::get('comment_id');
				$obj->value					=  	Input::get('value');
				$obj->save();
				echo 1;
			}else{
				DB::table("like_unlike_article_comments")->where("user_id",$user_id)->where("comment_id",Input::get('comment_id'))->delete();
				echo 2;
			}
		}
		
	}// end projectLibraryArticles()
/** 
 * Function to projectLibraryArticles
 *
 * @param null
 * 
 * @return view page
 */	
	public function likeUnlikeArticle(){
		$formData			=	Input::all();
		$user_id		 	=   Auth::user()->id;
		if(!empty($formData)){
			if($formData['value'] == 1){
				$obj 						= 	new LikeUnlikeArticle();
				$obj->user_id		 		=  	Auth::user()->id;
				$obj->article_id			=  	Input::get('article_id');
				$obj->value					=  	Input::get('value');
				$obj->save();
				echo 1;
			}else{
				DB::table("like_unlike_articles")->where("user_id",$user_id)->where("article_id",Input::get('article_id'))->delete();
				echo 2;
			}
		}
		
	}// end projectLibraryArticles()
	
/** 
 * Function to helpfulNothelpfulArticle
 *
 * @param null
 * 
 * @return view page
 */	
	public function helpfulNothelpfulArticle(){
		$formData			=	Input::all();
		$user_id		 	=   Auth::user()->id;
		
		if(!empty($formData)){
			$obj 						= 	new ProjectArticleHelpfulNothelpful();
			$obj->user_id		 		=  	Auth::user()->id;
			$obj->article_id			=  	Input::get('article_id');
			$obj->value					=  	Input::get('value');
			$obj->save();
		}
		echo $formData['value'];
	}// end helpfulNothelpfulArticle()
	
	public function library(){
		$user_id			=	(!empty(Auth::user()->id)) ? Auth::user()->id : 0;
		$projectFolders		=	DB::table("project_folders")->where("is_active",1)->where("is_deleted",0)->orderBy("category_order","ASC")->limit(4)->select("project_folders.*",DB::raw("(select count(id) from project_folder_articles where project_folder_id=project_folders.id AND is_active=1 AND is_deleted=0 AND id NOT IN(select article_id from read_articles where category_id=project_folders.id AND user_id = $user_id)) as new_articles"))->get();
		$mostViewedArticles		=	DB::table("project_folder_articles")->where("project_folder_articles.is_active",1)->where("project_folder_articles.is_deleted",0)->select('project_folder_articles.id','project_folder_articles.slug','project_folder_articles.article_name','project_folder_articles.article_description')->limit(5)->orderBy("viewed","DESC")->get();
		
		$recentAddedArticles	=	DB::table("project_folder_articles")->where("project_folder_articles.is_active",1)->where("project_folder_articles.is_deleted",0)->select('project_folder_articles.id','project_folder_articles.slug','project_folder_articles.article_name','project_folder_articles.article_description')->orderBy("id","DESC")->limit(5)->get();
		
		$checkThisOutTopics	=	DB::table("project_folder_articles")->where("project_folder_articles.is_check_this_out",1)->where("project_folder_articles.is_deleted",0)->select('project_folder_articles.id','project_folder_articles.slug','project_folder_articles.article_name','project_folder_articles.article_description')->orderBy("viewed","DESC")->limit(5)->get();
		
		//$newsFeeds				=	DB::table("newsfeeds")->where("newsfeeds.is_active",1)->where("newsfeeds.is_deleted",0)->select("newsfeeds.*")->orderBy("id","DESC")->limit(5)->get();
		return View::make('front.library.library',compact('mostViewedArticles','recentAddedArticles',/* 'newsFeeds', */'projectFolders','checkThisOutTopics'));
	}
	
	public function all_folders(){
		$user_id			=	(!empty(Auth::user()->id)) ? Auth::user()->id : 0;
		$projectFolders			=	DB::table("project_folders")
										->where("is_active",1)
										->where("is_deleted",0)
										->orderBy("category_order","ASC")
										->select("project_folders.*",DB::raw("(select count(id) from project_folder_articles where project_folder_id=project_folders.id AND is_active=1 AND is_deleted=0 AND id NOT IN(select article_id from read_articles where category_id=project_folders.id AND user_id = $user_id)) as new_articles"))
										->get();
		$folders		=	DB::table('project_folders')->where("is_active",1)->where("is_deleted",0)->select('name','slug','id')->orderBy("category_order","ASC")->get();
		return View::make('front.library.all_folders',compact('projectFolders','folders'));
	}
	
	public function folder_articles($slug=null){
		$DB 			= 	ProjectFolder::query();	
		$folderData		=	$DB->where("slug",$slug)->first();
		if(empty($folderData)){
			return Redirect::to("blog");
		}
		$folders		=	DB::table('project_folders')->where("is_active",1)->where("is_deleted",0)->select('name','slug','id')->orderBy("category_order","ASC")->get();
		$articleData	=	DB::table('project_folder_articles')->where('project_folder_id',$folderData->id)->where("is_active",1)->where("is_deleted",0)->orderby('id','DESC')->get();
		
		return View::make('front.library.folder_articles',compact('folderData','articleData','folders'));
	}
	
	public function searchLibrary(){
		$DB = ProjectFolderArticle::query();
		$keyword		=	'';
		$check			=	'';
		$search			=	Input::all();
		$folders		=	DB::table('project_folders')->where("is_active",1)->where("is_deleted",0)->select('name')->orderBy("category_order","ASC")->get();
		$searchData		=	Input::all();
		$folderArticle	=	array();
		$totalArticleSearch		=	0;
		$searchVariable	=	array(); 
		$result	=	array();
		if(!empty($searchData)){
			foreach($searchData as $fieldName => $fieldValue){
				if(isset($searchData['keyword']) && !empty($searchData['keyword'])){
					$keyword			=	$searchData['keyword']; 
					$DB->orWhere('project_folder_articles.article_name', "LIKE","%".$keyword."%");		
					$DB->orWhere('project_folder_articles.article_description', "LIKE","%".$keyword."%");		
					$searchVariable		=	array_merge($searchVariable,array($fieldName=> $fieldValue));
				}
				if(isset($searchData['check']) && !empty($searchData['check'])){
					$check	=	$searchData['check'];
					$DB->OrWhereIn('project_folders.name', $check);
					$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
			if((isset($searchData['keyword']) && !empty($searchData['keyword'])) || (isset($searchData['check']) && !empty($searchData['check']))){
				$categoryList	=	$DB
									->leftjoin('project_folders','project_folders.id','=','project_folder_articles.project_folder_id')
										->groupBy('project_folder_articles.slug','project_folder_articles.user_id','project_folder_articles.id','project_folders.name')
										->orderBy('project_folders.name')
										->pluck('project_folders.name as folder_name','project_folders.id as id')->toArray();
				
				$result			=	array();
				if(!empty($categoryList)){
					foreach($categoryList as &$list){
						$result[$list] 		= 	ProjectFolderArticle::leftjoin('project_folders','project_folders.id','=','project_folder_articles.project_folder_id')
												->orWhere('project_folders.name',"LIKE","%".$list."%")
												->orWhere('project_folder_articles.article_name', "LIKE","%".$keyword."%")
												->orWhere('project_folder_articles.article_description', "LIKE","%".$keyword."%")
												->groupBy('project_folder_articles.slug','project_folder_articles.user_id','project_folder_articles.id')
												->select("project_folder_articles.*",'project_folders.name as folder_name','project_folders.slug as folder_slug',DB::raw("(SELECT full_name from users where id=project_folder_articles.user_id) as username"))
												->get();
					
					}
					$totalArticleSearch		=	count($result[$list]);
				}
			}
		}
		return View::make('front.library.search_library',compact('folders','result','searchVariable','search','totalArticleSearch'));
	}
	
	public function article_detail($slug=''){
		$formdata	=	Input::all();
		$DB = ProjectFolderArticle::query();
		$topicData	=	DB::table('project_folder_articles')->where('slug',$slug)->where("is_active",1)->where("is_deleted",0)->select('project_folder_articles.*',DB::raw("(SELECT full_name FROM users where id=project_folder_articles.user_id) as username"),DB::raw("(SELECT image FROM users where id=project_folder_articles.user_id) as profile_image"),DB::raw("(SELECT count(id) FROM project_article_comments where article_id=project_folder_articles.id) as total_comments"))->first();
		if(empty($topicData)){
			return Redirect::to("blog");
		}
		$obj 				=  ProjectFolderArticle::find($topicData->id);
		$obj->viewed  += 1;
		$obj->save();
		
		$user_id = !empty(Auth::user()) ? Auth::user()->id : 0;
		
		if(!empty($user_id)){
			$readArticle = DB::table("read_articles")->where("user_id",$user_id)->where("article_id",$topicData->id)->where("category_id",$topicData->project_folder_id)->first();
			if(empty($readArticle)){
				$rObj = new ReadArticle();
				$rObj->user_id = $user_id;
				$rObj->category_id = $topicData->project_folder_id;
				$rObj->article_id = $topicData->id;
				$rObj->save();
			}
		}
		
		$folderData		=	DB::select(DB::raw("SELECT slug,name FROM project_folders where id=$topicData->project_folder_id"));
		$folderData		=	$folderData[0];
		$comments		=	DB::table("project_article_comments")
								->where('article_id',$topicData->id)
								->where('is_active',1)
								->where('parent_id',0)
								->select("project_article_comments.*",DB::raw("(select full_name from users where id=project_article_comments.user_id)as user_name"),DB::raw("(select image from users where id=project_article_comments.user_id)as user_image"),DB::raw("(select COUNT(id) from project_article_comments where parent_id=project_article_comments.id)as total_comment_reply"))
								->orderBy("created_at","DESC")
								->get();
								
		if(!empty($comments)){
			foreach($comments as $key=>$comment){
				$commentReply	=	DB::table("project_article_comments")
								->where('parent_id',$comment->id)
								->where('is_active',1)
								->where('parent_id','!=',0)
								->select("project_article_comments.*",DB::raw("(select full_name from users where id=project_article_comments.user_id)as user_name"),DB::raw("(select image from users where id=project_article_comments.user_id)as user_image"))
								->orderBy("created_at","DESC")
								->get();
				
				$comments[$key]->reply	=	$commentReply;
			}
		}
		$attachments	=	DB::table('project_folder_article_documents')->where('article_id',$topicData->id)->get();
		$articleLinks	=	DB::table('project_folder_article_links')->where('article_id',$topicData->id)->get();
		
		return View::make('front.library.library4',compact("topicData","attachments","articleLinks","comments","folderData","keyword"));
	}
	
	public function librarydetail($id='',$user_id = ''){
		$topicData	=	DB::table('project_folder_articles')->where('id',$id)->where("is_active",1)->where("is_deleted",0)->select('project_folder_articles.*',DB::raw("(SELECT full_name FROM users where id=project_folder_articles.user_id) as username"),DB::raw("(SELECT image FROM users where id=project_folder_articles.user_id) as profile_image"),DB::raw("(SELECT count(id) FROM project_article_comments where article_id=project_folder_articles.id) as total_comments"))->first();
		if(empty($topicData)){
			return Redirect::to("blog");
		}
		
		$obj 		  =  ProjectFolderArticle::find($topicData->id);
		$obj->viewed  += 1;
		$obj->save();
		
		if(!empty($user_id)){
			$readArticle = DB::table("read_articles")->where("user_id",$user_id)->where("article_id",$topicData->id)->where("category_id",$topicData->project_folder_id)->first();
			if(empty($readArticle)){
				$rObj = new ReadArticle();
				$rObj->user_id 		= $user_id;
				$rObj->category_id 	= $topicData->project_folder_id;
				$rObj->article_id 	= $topicData->id;
				$rObj->save();
			}
		}
		
		$folderData		=	DB::select(DB::raw("SELECT slug,name FROM project_folders where id=$topicData->project_folder_id"));
		$folderData		=	$folderData[0];
		$comments		=	DB::table("project_article_comments")
								->where('article_id',$topicData->id)
								->where('is_active',1)
								->where('parent_id',0)
								->select("project_article_comments.*",DB::raw("(select full_name from users where id=project_article_comments.user_id)as user_name"),DB::raw("(select image from users where id=project_article_comments.user_id)as user_image"),DB::raw("(select COUNT(id) from project_article_comments where parent_id=project_article_comments.id)as total_comment_reply"))
								->orderBy("created_at","DESC")
								->get();
								
		if(!empty($comments)){
			foreach($comments as $key=>$comment){
				$commentReply	=	DB::table("project_article_comments")
									->where('parent_id',$comment->id)
									->where('is_active',1)
									->where('parent_id','!=',0)
									->select("project_article_comments.*",DB::raw("(select full_name from users where id=project_article_comments.user_id)as user_name"),DB::raw("(select image from users where id=project_article_comments.user_id)as user_image"))
									->orderBy("created_at","DESC")
									->get();
				
				$comments[$key]->reply	=	$commentReply;
			}
		}
			
		$attachments	=	DB::table('project_folder_article_documents')->where('article_id',$topicData->id)->get();
		$articleLinks	=	DB::table('project_folder_article_links')->where('article_id',$topicData->id)->get();
		
		return View::make('front.library.librarydetail',compact("topicData","attachments","articleLinks","comments","user_id"));
	}
}// end UsersController class
