<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\ProjectLibrary;
use App\Model\ProjectFolder;
use App\Model\ProjectFolderArticle;
use App\Model\ProjectFolderArticleLink;
use App\Model\EmailTemplate;
use App\Model\EmailAction;
use App\Model\ProjectFolderArticleDocument;
use App\Model\ProjectArticleComment;
use App\Model\ProjectArticleCommentReply;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
* Contacts Controller
*
* Add your methods in the class below
*
* This file will render views from views/admin/contact
*/
 
class ProjectFolderArticleController extends BaseController {
/**
* $model Contact. 
*/	
	public $model	=	'ProjectFolderArticle';
/**
* Function for __construct
*
* @param null
*
* @return model name
*/	
	public function __construct() {
		View::share('modelName',$this->model);
	}

/**
* Function for display list of  all contact
*
* @param null
*
* @return view page. 
*/
public function conetentIndex(){
		
	$DB 								= 	ProjectFolder::query();
	$searchVariable						=	array(); 
	$inputGet							=	Input::get();
	if ((Input::get())) {
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
				$searchVariable			=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
	}
	$sortBy 							= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
	$order  							= 	(Input::get('order')) ? Input::get('order')   : 'DESC';
	$model 								= 	$DB
											->select('project_folders.*',DB::raw("(select COUNT(id) from project_folder_articles where project_folder_id=project_folders.id AND is_deleted=0 LIMIT 1) as total_articles"))
											->orderBy($sortBy, $order)
											->paginate(Config::get("Reading.records_per_page"));
	
	$complete_string		=	Input::query();
	unset($complete_string["sortBy"]);
	unset($complete_string["order"]);
	$query_string			=	http_build_query($complete_string);
	$model->appends(Input::all())->render();
	
	return  View::make("admin.$this->model.content",compact('model' ,'searchVariable','sortBy','order','query_string'));
} // end conetentIndex()

/**
* Function for display list of  all contact
*
* @param null
*
* @return view page. 
*/



	public function listProjectFolderArticle($folder_id=""){
		$DB 								= 	ProjectFolderArticle::query();
		$searchVariable						=	array(); 
		$inputGet							=	Input::get();
		if ((Input::get())) {
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
					$searchVariable			=	array_merge($searchVariable,array($fieldName => $fieldValue));
				}
			}
		}
		$sortBy 							= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
	    $order  							= 	(Input::get('order')) ? Input::get('order')   : 'DESC';
		$model 								= 	$DB->where('project_folder_id','=',$folder_id)->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$project_folder_id	=	$folder_id;
		
		$complete_string		=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$model->appends(Input::all())->render();
		return  View::make("admin.$this->model.index",compact('model' ,'searchVariable','sortBy','order','query_string','project_folder_id'));
	} // end listProjectFolderArticle()
	
	public function addMoreArticleDocument(){
		$document_count			=	Input::get('total_count');
		return  View::make("admin.$this->model.add_more_document",compact('document_count'));
	} // end addMoreArticleDocument()

	public function addMoreArticleDocumentLink(){
		$document_link			=	Input::get('total_count');
		return  View::make("admin.$this->model.add_more_document_link",compact('document_link'));
	} // end addMoreArticleDocumentLink()

/**
* Function for display ProjectFolder detail
*
@param $modelId as id of ProjectFolder
*
* @return view page. 
*/
	public function viewProjectFolderArticle($folder_id=0,$modelId = 0){
		$project_folder_id		=	$folder_id;
		if($modelId){
			$model				=	ProjectFolderArticle::where('id' ,$modelId)->where('project_folder_id',$folder_id)->select('project_folder_articles.*',DB::raw('(SELECT name from project_folders where id=project_folder_articles.project_folder_id) as folder_name'))->first();
			$articleLink		=	ProjectFolderArticleLink::where('article_id' ,$modelId)->get();
			$form_documents		=	ProjectFolderArticleDocument::where('article_id' ,$modelId)->get();
			$count_documents	=	ProjectFolderArticleDocument::where('article_id' ,$modelId)->count();
			$comments			=	ProjectArticleComment::where('article_id' ,$modelId)->select('project_article_comments.*',DB::raw('(SELECT full_name from users where id=project_article_comments.user_id) as username'))->get();
			$commentReply		=	ProjectArticleComment::where('parent_id' ,$modelId)->get();
			if(empty($model)) {
				return Redirect::route($this->model.'.index',array("$folder_id"));
			}
			return  View::make("admin.$this->model.view", compact('model','modelId','comments','project_folder_id','articleLink','form_documents','count_documents'));
		} 
	} // end viewProjectFolderArticle()

	public function commentReplyData(){
		$comment_id		=	Input::get('comment_id');	
		$DB				=	ProjectArticleComment::query();
		$commentData	=	$DB->where("id",$comment_id)->select("project_article_comments.*",DB::raw("(select full_name from users where id=project_article_comments.user_id)as user_full_name"))->first();
		
		return View::make("admin.$this->model.project_article_comments",compact('commentData'));
	}
/**
* Function for display page  for add new ProjectFolder
*
* @param null
*
* @return view page. 
*/
	public function addProjectFolderArticle($folder_id=0){
		$categoriesList		=	DB::table("project_folders")->where(['is_active'=>1,'is_deleted'=>0])->orderBy("category_order","ASC")->pluck('name','id')->toArray();
	 
		return View::make("admin.$this->model.add",compact('project_folder_id','listfolders','categoriesList'));
	}//end addProjectFolder()
	
/**
* Function for save added SiteUpdates page
*
* @param null
*
* @return redirect page. 
*/
	function saveProjectFolderArticle($folder_id=0){
			Input::replace($this->arrayStripTags(Input::all()));
			$thisData					=	Input::all();
			$validator 		= 	Validator::make(
				Input::all(),
				array(	
					'project_folder_id'		=>  'required',
					'article_name' 			=>  'required',
					'article_description' 	=>  'required',
					'comment_end_date' 		=>  'required_if:allow_comments,==,1',
					'image'					=> 'mimes:'.IMAGE_EXTENSION,
				),
				array(
					'article_name' 			=>  trans('Please enter name.'),
					'article_description' 	=>  trans('Please enter description.'),
					'comment_end_date.required_if' 	=>  trans('Please enter comment end date.'),
				)
			);
		
			if ($validator->fails()){
				return Redirect::back()
					->withErrors($validator)->withInput();
			}else{
				$model 						= 	new ProjectFolderArticle;
				$folderArticleName			=	Input::get('article_name');
				$model->slug	 			=   $this->getSlug($folderArticleName,'article_name','ProjectFolderArticle');
				$model->project_folder_id   =	Input::get('project_folder_id');
				$model->user_id		    	= 	Auth::user()->id;
				$model->article_name   		= 	Input::get('article_name');
				$model->article_description = 	Input::get('article_description');
				$model->is_check_this_out 	= 	!empty(Input::get('is_check_this_out')) ? Input::get('is_check_this_out') : 0;
				$model->allow_comments		= 	!empty(Input::get('allow_comments')) ? Input::get('allow_comments') : 0;
				if(!empty(Input::get('allow_comments'))){
					$model->comment_end_date = 	Input::get('comment_end_date');
				}
				
				if(input::hasFile('image')){
					$extension 	=	 Input::file('image')->getClientOriginalExtension();
					$fileName	=	time().'-article-image.'.$extension;
					
					$newFolder     	= 	strtoupper(date('M'). date('Y'))."/";
					$folderPath		=	PROJECT_ARTICLE_IMAGE_ROOT_PATH.$newFolder;
					if(!File::exists($folderPath)) {
						File::makeDirectory($folderPath, $mode = 0777,true);
					}
					if(Input::file('image')->move($folderPath, $fileName)){
						$model->image	=	$newFolder.$fileName;
					}
				}
				
				$model->save();
			}

			if(!empty($thisData['formdocument'])) {
			$i	=	0;
				foreach($thisData['formdocument'] as $form_documents) {
					if(!empty($form_documents['documents'])){
						$obj1 								=  	new ProjectFolderArticleDocument;
						$obj1->article_id 					=  	$model->id;
						$formdocuments						=	$form_documents["documents"];
						if($formdocuments){
							$extension 						=	$formdocuments->getClientOriginalExtension();
							$image_name			 			=	$formdocuments->getClientOriginalName();
	
							$fileName						=	$i.time().'-article-document.'.$extension;
							$newFolder     					= 	strtoupper(date('M'). date('Y'))."/";
							$folderPath						=	PROJECT_ARTICLE_IMAGE_ROOT_PATH.$newFolder;
							if(!File::exists($folderPath)) {
								File::makeDirectory($folderPath, $mode = 0777,true);
							}
							if($formdocuments->move($folderPath, $fileName)){
								$obj1->documents			=	$newFolder."/".$fileName;
								$obj1->document_name			=	pathinfo($image_name, PATHINFO_FILENAME).".".$extension;
							}
						}
						$obj1->save();
					}
				$i++;
				}
			}
			if(!empty($thisData['formlink'])) {
				foreach($thisData['formlink'] as $form_links) {
					if(!empty($form_links['url'])){
						$obj2 								=  	new ProjectFolderArticleLink;
						$obj2->article_id 					=  	$model->id;
						$obj2->url 							=  	$form_links["url"];
						$obj2->save();
					}
				}
			}
			Session::flash('flash_notice',  trans("Article added successfully"));  
			return Redirect::route("$this->model.conetentIndex");
		}// end saveProjectFolderArticle()
/**
* Function for display page  for edit ProjectFolder page
*
* @param $modelId as id of ProjectFolder page
*
* @return view page. 
*/	
	public function editProjectFolderArticle($folder_id=0,$modelId){
		$model					=	ProjectFolderArticle::findorFail($modelId);
		$project_folder_id		=	$folder_id;
		if(empty($model)) {
			return route("$this->model.index","$folder_id");
		}
		$form_documents			=	DB::table('project_folder_article_documents')->where("article_id",$modelId)->get();
		$form_links				=	DB::table('project_folder_article_links')->where("article_id",$modelId)->get();
		//print_r($form_documents);die;
		return  View::make("admin.$this->model.edit",compact('model','project_folder_id','form_documents','form_links'));
	}// end editProjectFolderArticle()
/**
* Function for update ProjectFolder 
*
* @param $modelId as id of ProjectFolder 
*
* @return redirect page. 
*/
	function updateProjectFolderArticle($folder_id=0,$modelId){
		Input::replace($this->arrayStripTags(Input::all()));
		$this_data				=	Input::all();
		//echo '<pre>';print_r($this_data);die;
		$validator 				= 	Validator::make(
			Input::all(),
			array(	
				'article_name' 			=>  'required',
				'article_description' 	=>  'required',
				'comment_end_date' 		=>  'required_if:allow_comments,==,1',
				'image'					=> 'mimes:'.IMAGE_EXTENSION,
			),
			array(
				'article_name' 			=>  trans('Please enter name.'),
				'article_description' 	=>  trans('Please enter description.'),
				'comment_end_date.required_if' 	=>  trans('Please enter comment end date.'),
			)
		);
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model 						= 	ProjectFolderArticle::find($modelId);
			$model->project_folder_id   = 	$folder_id;
			$model->user_id		    	= 	Auth::user()->id;
			$model->article_name   		= 	Input::get('article_name');
			$model->article_description = 	Input::get('article_description');
			$model->is_check_this_out 	= 	!empty(Input::get('is_check_this_out')) ? Input::get('is_check_this_out') : 0;
			$model->allow_comments		= 	!empty(Input::get('allow_comments')) ? Input::get('allow_comments') : 0;
			if(!empty(Input::get('allow_comments'))){
				$model->comment_end_date = 	Input::get('comment_end_date');
			}
			$model->revision 		 	+= 	1;
			if(input::hasFile('image')){
				$extension 	=	 Input::file('image')->getClientOriginalExtension();
				$fileName	=	time().'-article-image.'.$extension;
				
				$newFolder     	= 	strtoupper(date('M'). date('Y'))."/";
				$folderPath		=	PROJECT_ARTICLE_IMAGE_ROOT_PATH.$newFolder;
				if(!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777,true);
				}
				if(Input::file('image')->move($folderPath, $fileName)){
					$model->image	=	$newFolder.$fileName;
				}
			}
			$model->save();
		}

		$article_id = $model->id;
		if(!empty($this_data['formdocument'])){
			$i	=	0;
			foreach($this_data['formdocument'] as $form_documents){
				if( !empty($form_documents['documents'])){
					if(!empty($form_documents['article_document_id'])) {
						$obj1 							=  	ProjectFolderArticleDocument::find($form_documents['article_document_id']);
					}else {
						$obj1 							=  	new ProjectFolderArticleDocument;
					}
					$obj1->article_id 					=  	$article_id;
					
					$formdocuments						=	$form_documents["documents"];
					if($formdocuments){
						$extension 						=	$formdocuments->getClientOriginalExtension();
						$image_name			 			=	$formdocuments->getClientOriginalName();
						$fileName						=	$i.time().'-vehicle-document.'.$extension;
						$newFolder     					= 	strtoupper(date('M'). date('Y'))."/";
						$folderPath						=	PROJECT_ARTICLE_IMAGE_ROOT_PATH.$newFolder;
						if(!File::exists($folderPath)) {
							File::makeDirectory($folderPath, $mode = 0777,true);
						}
						if($formdocuments->move($folderPath, $fileName)){
							$obj1->documents				=	$newFolder."/".$fileName;
							$obj1->document_name			=	pathinfo($image_name, PATHINFO_FILENAME).".".$extension;
						}
					}
					$obj1->save();
				}
			$i++;
			}
		}
		if(!empty($this_data['formlink'])){
			foreach($this_data['formlink'] as $form_links){
				if(!empty($form_links['url'])){
					if(!empty($form_links['article_link_id'])) {
						$obj2 							=  	ProjectFolderArticleLink::find($form_links['article_link_id']);
					}else {
						$obj2 							=  	new ProjectFolderArticleLink;
					}
					$obj2->article_id 					=  	$article_id;
					$obj2->url 							=  	$form_links["url"];
					$obj2->save();
				}
			}
		}
		Session::flash('flash_notice',  trans("Article updated successfully"));
		return Redirect::route("$this->model.conetentIndex");
	}// end updateProjectFolderArticle()
	
	/**
* Function for delete ProjectFolder 
*
* @param $modelId as id of ProjectFolder 
*
* @return redirect page. 
*/	
	public function deleteProjectFolder($modelId = 0){
		$SiteUpdatesdel	=	ProjectFolder::find($modelId); 
		if(empty($SiteUpdatesdel)) {
			return Redirect::route("$this->model.conetentIndex");
		}
		$userModel		=	ProjectFolder::where('id',$modelId)->delete();
		Session::flash('flash_notice',trans("Project Folder removed successfully")); 
		return Redirect::route("$this->model.conetentIndex");
	} // end deleteProjectFolder()
	
/**
* Function for update ProjectFolderArticle status
*
* @param $userId as id of ProjectFolderArticle
* @param $userStatus as status of ProjectFolderArticle
*
* @return redirect page. 
*/
	public function updateProjectFolderStatusArticle($Id = 0, $Status = 0){
		if($Status == 0	){
			$statusMessage	=	trans("Project folder article deactivated successfully");
		}else{
			$statusMessage	=	trans("Project folder article activated successfully");
		}
		$this->_update_all_status('project_folder_articles',$Id,$Status);
	
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::back();
	} // end updateProjectFolderStatusArticle()

	public function deleteProjectDocument() {
		$article_document_id			=	Input::get('id');
		DB::table('project_folder_article_documents')->where('id',$article_document_id)->delete();
		return Redirect::back();
	}
	
/**
* Function for update ProjectFolderArticle checkThisOut
*
* @param $userId as id of ProjectFolderArticle
* @param $userStatus as status of ProjectFolderArticle
*
* @return redirect page. 
*/
	public function updateCheckThisOut($Id = 0, $Status = 0){
		if($Status == 0	){
			$statusMessage	=	trans("Added to check this out successfully");
		}else{
			$statusMessage	=	trans("Remove from check this out successfully");
		}
		//$this->_update_all_status('project_folder_articels',$Id,$Status);
		DB::table("project_folder_articles")->where("id",$Id)->update(array("is_check_this_out"=>$Status));
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::back();
	} // end updateCheckThisOut()
	
}// end ProjectLibrariesController
