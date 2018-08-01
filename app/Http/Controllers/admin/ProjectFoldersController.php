<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\ProjectLibrary;
use App\Model\ProjectFolder;
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
 
class ProjectFoldersController extends BaseController {
/**
* $model Contact. 
*/	
	public $model	=	'ProjectFolder';
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
	public function listProjectFolder(){
		
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
		$sortBy 							= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'category_order';
	    $order  							= 	(Input::get('order')) ? Input::get('order')   : 'ASC';
		$model 								= 	$DB
												->select('project_folders.*',DB::raw("(select COUNT(id) from project_folder_articles where project_folder_id=project_folders.id AND is_deleted=0) as total_articles"))
												->orderBy($sortBy, $order)
												->paginate(Config::get("Reading.records_per_page"));
		
		$complete_string		=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$model->appends(Input::all())->render();
		return  View::make("admin.$this->model.index",compact('model' ,'searchVariable','sortBy','order','query_string'));
	} // end listContact()
/**
* Function for display ProjectFolder detail
*
@param $modelId as id of ProjectFolder
*
* @return view page. 
*/
	public function viewProjectFolder($modelId = 0){
		if($modelId){
			$model	=	ProjectFolder::where('id' ,$modelId)->select('project_folders.*')->first();
			if(empty($model)) {
				return Redirect::route($this->model.'.index');
			}
			return  View::make("admin.$this->model.view", compact('model','modelId'));
		} 
	} // end viewProjectFolder()

/**
* Function for display page  for add new ProjectFolder  
*
* @param null
*
* @return view page. 
*/
	public function addProjectFolder(){
		$listfolders	=	DB::table('project_folders')->pluck('name','id')->toArray();
		//print_r($listfolders);die;
		return  View::make("admin.$this->model.add",compact('listfolders'));
	} //end addProjectFolder()
/**
* Function for save added SiteUpdates page
*
* @param null
*
* @return redirect page. 
*/
	function saveProjectFolder(){
		Input::replace($this->arrayStripTags(Input::all()));
		$thisData					=	Input::all();
		$validator 				= 	Validator::make(
			array(
				//'type' 			=> 	Input::get('type'),
				'name' 			=> 	Input::get('name'),
				'description' 	=> 	Input::get('description'),
			),	
			array(	
				//'type' 			=>  'required',
				'name' 			=>  'required',
				'image'			=> 'mimes:'.IMAGE_EXTENSION,
			),
			array(
				//'type' 			=>  trans('Please select type.'),
				'name' 			=>  trans('Please enter name.'),
				'description' 	=>  trans('Please enter description.'),
			)
		);
		
		if ($validator->fails()){
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model 					= 	new ProjectFolder;
			$folderName				=	Input::get('name');
			$model->slug	 		=   $this->getSlug($folderName,'name','ProjectFolder');
			$model->user_id		    = 	Auth::user()->id;
			//$model->parent_id   	= 	Input::get('type');
			$model->name    		= 	Input::get('name');
			$model->description    	= 	!empty(Input::get('description'))?Input::get('description'):'';
			
			if(input::hasFile('image')){
				$extension 	=	 Input::file('image')->getClientOriginalExtension();
				$fileName	=	time().'-folder-image.'.$extension;
				
				$newFolder     	= 	strtoupper(date('M'). date('Y'))."/";
				$folderPath		=	PROJECT_FOLDER_IMAGE_ROOT_PATH.$newFolder;
				if(!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777,true);
				}
				if(Input::file('image')->move($folderPath, $fileName)){
					$model->image	=	$newFolder.$fileName;
				}
			}
			
			$model->save();
		} 
		Session::flash('flash_notice',  trans("Category added successfully"));  
		return Redirect::route("$this->model.index");
	}// end saveProjectFolder()
/**
* Function for display page  for edit ProjectFolder page
*
* @param $modelId as id of ProjectFolder page
*
* @return view page. 
*/	
	public function editProjectFolder($modelId){
		$model				=	ProjectFolder::findorFail($modelId);
		if(empty($model)) {
			return route("$this->model.index");
		}
		$listfolders	=	DB::table('project_folders')->where('id','!=',$modelId)->pluck('name','id')->toArray();
		return  View::make("admin.$this->model.edit",compact('model','listfolders'));
	}// end editProjectFolder()
/**
* Function for update ProjectFolder 
*
* @param $modelId as id of ProjectFolder 
*
* @return redirect page. 
*/
	function updateProjectFolder($modelId){
		Input::replace($this->arrayStripTags(Input::all()));
		$this_data				=	Input::all();
		$model 					= 	ProjectFolder:: findorFail($modelId);
		$validator 				= 	Validator::make(
			array(
				//'type' 			=> 	Input::get('type'),
				'name' 			=> 	Input::get('name'),
				'description' 	=> 	Input::get('description'),
			),	
			array(	
				//'type' 			=>  'required',
				'name' 			=>  'required',
				'image'			=> 'mimes:'.IMAGE_EXTENSION,
			),
			array(
				//'type' 			=>  trans('Please select type.'),
				'name' 			=>  trans('Please enter name.'),
				'description' 	=>  trans('Please enter description.'),
			)
		);
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model 					= 	ProjectFolder::find($modelId);
			//$model->parent_id   	= 	Input::get('type');
			$model->user_id		   	= 	Auth::user()->id;
			$model->name    		= 	Input::get('name');
			$model->description    	= 	!empty(Input::get('description'))?Input::get('description'):'';
			
			if(input::hasFile('image')){
				$extension 	=	 Input::file('image')->getClientOriginalExtension();
				$fileName	=	time().'-folder-image.'.$extension;
				
				$newFolder     	= 	strtoupper(date('M'). date('Y'))."/";
				$folderPath		=	PROJECT_FOLDER_IMAGE_ROOT_PATH.$newFolder;
				if(!File::exists($folderPath)) {
					File::makeDirectory($folderPath, $mode = 0777,true);
				}
				if(Input::file('image')->move($folderPath, $fileName)){
					$model->image	=	$newFolder.$fileName;
				}
			}
			
			$model->save();
		}
		Session::flash('flash_notice',  trans("Category updated successfully"));
		return Redirect::route("$this->model.index");
	}// end updateProjectFolder()
	
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
			return Redirect::route("$this->model.index");
		}
		$userModel		=	ProjectFolder::where('id',$modelId)->delete();
		Session::flash('flash_notice',trans("Category deleted successfully")); 
		return Redirect::route("$this->model.index");
	} // end deleteProjectFolder()
	
/**
* Function for update ProjectFolder status
*
* @param $userId as id of ProjectFolder
* @param $userStatus as status of ProjectFolder
*
* @return redirect page. 
*/
	public function updateProjectFolderStatus($Id = 0, $Status = 0){
		if($Status == 0	){
			$statusMessage	=	trans("Project deactivated successfully");
		}else{
			$statusMessage	=	trans("Project activated successfully");
		}
		$this->_update_all_status('project_folders',$Id,$Status);
		/* DB::beginTransaction();
		$user_details					=		AdminUser::where('id', '=', $userId)->update(array('is_active' => $userStatus));	
		$this->_update_all_status('faqs',$Id,$Status);
		if(!$user_details) {
			DB::rollback();
			Session::flash('error', trans("Something went wrong.")); 
			return Redirect::back()->withInput();
		}
		DB::commit(); */
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::back();
	} // end updateProjectFolderStatus()

	public function addMoreArticleDocument(){
		$document_count			=	Input::get('total_count');
		return  View::make("admin.$this->model.add_more_document",compact('document_count'));
	} // end updateProjectFolderStatus()
	
	public function getArticleCategories(){
		$id = Input::get("id");
		$category = DB::table("project_folders")->where("id",$id)->select('project_folders.*',DB::raw("(select COUNT(id) from project_folder_articles where project_folder_id=project_folders.id AND is_deleted=0) as total_articles"))->first();
		$articles = DB::table("project_folder_articles")->where("project_folder_id",$id)->get();
		$project_folder_id		=	$id;
		return  View::make("admin.$this->model.get_category_article",compact('id' ,'category','articles','project_folder_id'));
	}

	public function deleteFeaturedImage(){
		$id					=	Input::get('id');
		$imageData			=	ProjectFolder::find($id);
		 
		if(!empty($imageData)){
			@unlink(PROJECT_FOLDER_IMAGE_ROOT_PATH.$imageData->image);
			ProjectFolder::where('id',$id)->update(['image'=>null]);
			$response					=	array(
				'success' 				=> 	true,
			);
			return Response::json($response);
			die;
		}else{
			$response					=	array(
				'success' 				=> 	false,
			);
			return Response::json($response);
			die;
		}
	}
	
	public function updateOrder(){
		$pageIds = Input::get("page_id_array");
		for($i=0; $i<count($pageIds); $i++){
			ProjectFolder::where("id",$pageIds[$i])->update(array('category_order'=>$i+1));
		}
		echo 'Category Order has been updated'; 
		die;
	}
}// end ProjectLibrariesController
