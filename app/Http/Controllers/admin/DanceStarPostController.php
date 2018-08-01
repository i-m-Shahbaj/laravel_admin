<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\DanceStarPost;
use App\Model\PostImage;
use App\Model\EmailTemplate;
use App\Model\EmailAction;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
* Contacts Controller
*
* Add your methods in the class below
*
* This file will render views from views/admin/contact
*/
 
class DanceStarPostController extends BaseController {
/**
* $model Contact. 
*/	
	public $model	=	'DanceStarPost';
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
	public function listDanceStarPost(){ 
		$DB 								= 	DanceStarPost::query();
		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		if (Input::get()) {
			$searchData	=	Input::get();
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
				if(!empty($fieldValue && $fieldName=='username')){
					$DB->where('users.full_name','like','%'.$fieldValue.'%');
				}
				else if(!empty($fieldValue) || $fieldValue == 0){
					$DB->where("$fieldName",'like','%'.$fieldValue.'%');
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		
		
		$sortBy 	= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
	    $order  	= 	(Input::get('order')) ? Input::get('order')   : 'DESC';
	    $user_id	=	Auth::user()->id;
		$model 								= 	$DB->where('user_id',$user_id)
													->leftjoin('users','users.id','=','posts.user_id')
													->select('posts.*','users.full_name as username')
													->orderBy($sortBy, $order)
													->paginate(Config::get("Reading.records_per_page"));
		
		$complete_string		=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$model->appends(Input::all())->render();

		return  View::make("admin.$this->model.index",compact('model' ,'searchVariable','sortBy','order','query_string'));
	} // end listContact()
	
	public function addMorePostDocument(){ 
		$document_count			=	Input::get('total_count');
		return  View::make("admin.$this->model.add_more_document",compact('document_count'));
	} // end addMorePostDocument()

	
	public function addMorePostDocumentLink(){ 
		$document_link			=	Input::get('total_count');
		return  View::make("admin.$this->model.add_more_document_link",compact('document_link'));
	} // end addMorePostDocumentLink()

/**
* Function for display ProjectFolder detail
*
@param $modelId as id of ProjectFolder
*
* @return view page. 
*/
	public function viewDanceStarPost($modelId = 0){
		if($modelId){
			$model	=	DanceStarPost::where('id' ,$modelId)->select('posts.*',DB::raw('(SELECT full_name from users where id=posts.user_id) as username'))->first();
			$form_documents	=	PostImage::where('post_id' ,$modelId)->get();
			if(empty($model)) {
				return Redirect::route($this->model.'.index');
			}
			return  View::make("admin.$this->model.view", compact('model','modelId','form_documents'));
		} 
	} // end viewDanceStarPost()

/**
* Function for display page  for add new ProjectFolder  
*
* @param null
*
* @return view page. 
*/
	public function addDanceStarPost(){
		return  View::make("admin.$this->model.add",compact('listfolders'));
	} //end addDanceStarPost()
/**
* Function for save added SiteUpdates page
*
* @param null
*
* @return redirect page. 
*/
	function saveDanceStarPost(){
		Input::replace($this->arrayStripTags(Input::all()));
		$thisData					=	Input::all();
		$validator 		= 	Validator::make(
			Input::all(),
			array(	
				'message' 			=>  'required',
			),
			array(
				'message' 			=>  trans('Please enter message.'),
			)
		);
		
			if ($validator->fails()){
				return Redirect::back()
					->withErrors($validator)->withInput();
			}else{
				$model 						= 	new DanceStarPost;
				//$folderArticleName			=	Input::get('article_name');
				//$model->slug	 			=   $this->getSlug($folderArticleName,'article_name','DanceStarPost');
				$model->user_id				=   Auth::user()->id;	
				$model->message				=   Input::get('message');	
				$model->save();
			}

			if(!empty($thisData['formdocument'])) {
			$i	=	0;
				foreach($thisData['formdocument'] as $form_documents) {
					if(!empty($form_documents['documents'])){
						$obj1 								=  	new PostImage;
						$obj1->post_id 						=  	$model->id;
						$formdocuments						=	$form_documents["documents"];
						if($formdocuments){
							$extension  	= $formdocuments->getClientOriginalExtension();
							$file_name  	= $formdocuments->getClientOriginalName();
							$newFolder  	= strtoupper(date('M') . date('Y')) . '/';
							$folderPath 	= POST_IMAGE_ROOT_PATH . $newFolder;
							if (!File::exists($folderPath)) {
								File::makeDirectory($folderPath, $mode = 0777, true);
							}
							$ticketImageName 	= time() . $i . '-post.' . $extension;
							$image = $newFolder . $ticketImageName;
							if ($formdocuments->move($folderPath, $ticketImageName)) {
								$obj1->image = $image;
							}
							if (in_array($extension, array('gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG'))){
								$obj1->type = 'image';
							}else{
								$obj1->type = 'video';
							}
							$i++;
						}
						$obj1->save();
					}
				}
			}
			Session::flash('flash_notice',  trans("DanceStar Post added successfully"));  
			return Redirect::route("$this->model.index");
		}// end saveDanceStarPost()
/**
* Function for display page  for edit ProjectFolder page
*
* @param $modelId as id of ProjectFolder page
*
* @return view page. 
*/	
	public function editDanceStarPost($modelId){
		$model					=	DanceStarPost::findorFail($modelId);
		$form_documents			=	PostImage::where('post_id',$modelId)->get();
		if(empty($model)) {
			return route("$this->model.index");
		}
		//print_r($form_documents);die;
		return  View::make("admin.$this->model.edit",compact('model','form_documents'));
	}// end editDanceStarPost()
/**
* Function for update ProjectFolder 
*
* @param $modelId as id of ProjectFolder 
*
* @return redirect page. 
*/
	function updateDanceStarPost($modelId){
		Input::replace($this->arrayStripTags(Input::all()));
		$this_data				=	Input::all();
		//echo '<pre>';print_r($this_data);die;
		$validator 				= 	Validator::make(
			Input::all(),
			array(	
				'message' 			=>  'required',
			),
			array(
				'message' 			=>  trans('Please enter message.'),
			)
		);
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model 						= 	DanceStarPost::find($modelId);
			$model->message				=   Input::get('message');	
			$model->save();
		}

		$post_id = $model->id;
		if(!empty($this_data['formdocument'])) {
			$i	=	0;
			foreach($this_data['formdocument'] as $form_documents) {
				if( !empty($form_documents['documents'])){
					if(!empty($form_documents['post_document_id'])) {
						$obj1 							=  	PostImage::find($form_documents['post_document_id']);
					}else {
						$obj1 							=  	new PostImage;
					}
					$obj1->post_id 					=  	$post_id;
					
					$formdocuments						=	$form_documents["documents"];
					if($formdocuments){
						$extension  	= $formdocuments->getClientOriginalExtension();
						$file_name  	= $formdocuments->getClientOriginalName();
						$newFolder  	= strtoupper(date('M') . date('Y')) . '/';
						$folderPath 	= POST_IMAGE_ROOT_PATH . $newFolder;
						if (!File::exists($folderPath)) {
							File::makeDirectory($folderPath, $mode = 0777, true);
						}
						$ticketImageName 	= time() . $i . '-post.' . $extension;
						$image = $newFolder . $ticketImageName;
						if ($formdocuments->move($folderPath, $ticketImageName)) {
							$obj1->image = $image;
						}
						$i++;
					}
					$obj1->save();
				}
			}
		}
		Session::flash('flash_notice',  trans("DanceStar post updated successfully"));
		return Redirect::route("$this->model.index");
	}// end updateDanceStarPost()
	
	/**
* Function for delete ProjectFolder 
*
* @param $modelId as id of ProjectFolder 
*
* @return redirect page. 
*/	
	public function deletePost($modelId = 0){
		$SiteUpdatesdel	=	ProjectFolder::find($modelId); 
		if(empty($SiteUpdatesdel)) {
			return Redirect::route("$this->model.index");
		}
		$userModel		=	ProjectFolder::where('id',$modelId)->delete();
		Session::flash('flash_notice',trans("Project Folder removed successfully")); 
		return Redirect::route("$this->model.index");
	} // end deletePost()
	
/**
* Function for update DanceStarPost status
*
* @param $userId as id of DanceStarPost
* @param $userStatus as status of DanceStarPost
*
* @return redirect page. 
*/
	public function updatePostStatus($Id = 0, $Status = 0){
		if($Status == 0	){
			$statusMessage	=	trans("Project folder article deactivated successfully");
		}else{
			$statusMessage	=	trans("Project folder article activated successfully");
		}
		$this->_update_all_status('project_folder_articals',$Id,$Status);
	
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::back();
	} // end updatePostStatus()

	public function deletePostDocument() {
		$document_id			=	Input::get('id');
		DB::table('post_images')->where('id',$document_id)->delete();
		return Redirect::back();
	}

}// end ProjectLibrariesController
