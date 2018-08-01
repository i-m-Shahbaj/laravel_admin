<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use App\Model\ProjectLibrary;
use App\Model\ProjectFolder;
use App\Model\ProjectFolderArticle;
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
 
	class ProjectLibrariesController extends BaseController {
/**
* $model Contact. 
*/	
	public $model	=	'ProjectLibrary';
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
	public function listProjectLibrary(){
		$DB 								= 	ProjectLibrary::query();
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
				if(!empty($fieldValue)){
					$DB->where("$fieldName",'like','%'.$fieldValue.'%');
					$searchVariable			=	array_merge($searchVariable,array($fieldName => $fieldValue));
				}
			}
		}
		$sortBy 							= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'created_at';
	    $order  							= 	(Input::get('order')) ? Input::get('order')   : 'DESC';
		$model 								= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		
		$complete_string		=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$model->appends(Input::all())->render();
		
		return  View::make("admin.$this->model.index",compact('model' ,'searchVariable','sortBy','order','query_string'));
	} // end listContact()
/**
* Function for display ProjectLibrary detail
*
@param $modelId as id of ProjectLibrary
*
* @return view page. 
*/
	public function viewProjectLibrary($modelId = 0){
		if($modelId){
			$model	=	ProjectLibrary::where('id' ,$modelId)->with("project_folder")->with("project_sub_folder")->with("project_articles")->get();
			if(empty($model)) {
				return Redirect::route($this->model.'.index');
			}
			//pr($model);die;
			$folders	=	ProjectFolder::where('project_id' ,$modelId)->get();
			$articles	=	ProjectFolderArticle::where('project_id' ,$modelId)->get();
			
			return  View::make("admin.$this->model.view", compact('model','modelId','folders','articles'));
		} 
	} // end viewProjectLibrary()

/**
* Function for display page  for add new ProjectLibrary  
*
* @param null
*
* @return view page. 
*/
	public function addProjectLibrary(){
		return  View::make("admin.$this->model.add");
	} //end addProjectLibrary()
/**
* Function for save added SiteUpdates page
*
* @param null
*
* @return redirect page. 
*/
	function saveProjectLibrary(){
		Input::replace($this->arrayStripTags(Input::all()));
		$thisData					=	Input::all();
		$validator 		= 	Validator::make(
			array(
				'project_name' 					=> 	Input::get('project_name'),
				'author' 						=> 	Input::get('author'),
				'author_group' 					=> 	Input::get('author_group'),
			),	
			array(	
				'project_name' 					=>  'required',
				'author' 						=>  'required',
				'author_group' 					=>  'required',
			),
			array(
				'project_name' 					=>  trans('Please enter subject.'),
				'author' 						=>  trans('Please enter author name.'),
				'author_group' 					=>  trans('Please enter authrized group name.'),
			)
		);
		
		if ($validator->fails()){
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model 						= 	new ProjectLibrary;
			$projectName				=	Input::get('project_name');
			$model->slug	 			=   $this->getSlug($projectName,'project_name','ProjectLibrary');
			$model->user_id		    	= 	Auth::user()->id;
			$model->project_name    	= 	Input::get('project_name');
			$model->project_number   	= 	$this->random_number();
			$model->author   			= 	Input::get('author');
			$model->author_group  		= 	Input::get('author_group');
			$model->save();
			} 
			Session::flash('flash_notice',  trans("Project Library added successfully"));  
			return Redirect::route("$this->model.index");
		}// end saveProjectLibrary()
/**
* Function for display page  for edit ProjectLibrary page
*
* @param $modelId as id of ProjectLibrary page
*
* @return view page. 
*/	
	public function editProjectLibrary($modelId){
		$model					=	ProjectLibrary::findorFail($modelId);
		if(empty($model)) {
			return Redirect::to('admin/site_updates');
		}
		return  View::make("admin.$this->model.edit",compact('model'));
	}// end editProjectLibrary()
/**
* Function for update ProjectLibrary 
*
* @param $modelId as id of ProjectLibrary 
*
* @return redirect page. 
*/
	function updateProjectLibrary($modelId){
		Input::replace($this->arrayStripTags(Input::all()));
		$this_data				=	Input::all();
		$model 					= 	ProjectLibrary:: findorFail($modelId);
		$validator 		= 	Validator::make(
			array(
				'project_name' 					=> 	Input::get('project_name'),
				'author' 						=> 	Input::get('author'),
				'author_group' 					=> 	Input::get('author_group'),
			),	
			array(	
				'project_name' 					=>  'required',
				'author' 						=>  'required',
				'author_group' 					=>  'required',
			),
			array(
				'project_name' 					=>  trans('Please enter subject.'),
				'author' 						=>  trans('Please enter author name.'),
				'author_group' 					=>  trans('Please enter authrized group name.'),
			)
		);
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model 						= 	ProjectLibrary::find($modelId);
			$model->project_name    	= 	Input::get('project_name');
			$model->author   			= 	Input::get('author');
			$model->user_id		    	= 	Auth::user()->id;
			$model->author_group  		= 	Input::get('author_group');
			$model->save();
		}
		Session::flash('flash_notice',  trans("Project Library updated successfully"));
		return Redirect::route("$this->model.index");
	}// end updateProjectLibrary()
	
	/**
* Function for delete ProjectLibrary 
*
* @param $modelId as id of ProjectLibrary 
*
* @return redirect page. 
*/	
	public function deleteProjectLibrary($modelId = 0){
		$SiteUpdatesdel	=	ProjectLibrary::find($modelId); 
		if(empty($SiteUpdatesdel)) {
			return Redirect::route("$this->model.index");
		}
		$userModel		=	ProjectLibrary::where('id',$modelId)->delete();
		Session::flash('flash_notice',trans("Project Library removed successfully")); 
		return Redirect::route("$this->model.index");
	} // end deleteProjectLibrary()
	
/**
* Function for update ProjectLibrary status
*
* @param $userId as id of ProjectLibrary
* @param $userStatus as status of ProjectLibrary
*
* @return redirect page. 
*/
	public function updateProjectLibraryStatus($Id = 0, $Status = 0){
		if($Status == 0	){
			$statusMessage	=	trans("Project deactivated successfully");
		}else{
			$statusMessage	=	trans("Project activated successfully");
		}
		$this->_update_all_status('project_libraries',$Id,$Status);
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
	} // end updateProjectLibraryStatus()

	
}// end ProjectLibrariesController
