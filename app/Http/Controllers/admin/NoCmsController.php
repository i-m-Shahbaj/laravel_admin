<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\NoCms;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
* NoCmsController Controller
*
* Add your methods in the class below
*
* This file will render views from views/admin/NoCms
*/
	class NoCmsController extends BaseController {
		
		
	public $model	=	'NoCms';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
/**
* Function for display all Document 
*
* @param null
*
* @return view page. 
*/
	public function listDoc(){	
		$DB							=	NoCms::query();
		$searchVariable				=	array(); 
		$inputGet					=	Input::get();
		if ((Input::get())) {
			///print_r($inputGet);die;
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
					$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
				}
			}
		}
		$sortBy 					= (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    $order  					= (Input::get('order')) ? Input::get('order')   : 'DESC';
		$result 					= $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		
		$complete_string			=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string				=	http_build_query($complete_string);
		$result->appends(Input::all())->render();
		return  View::make('admin.'.$this->model.'.index',compact('result','searchVariable','sortBy','order','query_string'));
	}// end listBlock()
/**
* Function for display page  for add new seo
*
* @param null
*
* @return view page. 
*/
	public function addDoc(){
		return  View::make('admin.'.$this->model.'.add');
	} //end addBlock()
/**
* Function for save document
*
* @param null
*
* @return redirect page. 
*/
	function saveDoc(){
		Input::replace($this->arrayStripTags(Input::all()));
		$thisData				=	Input::all();
		$validator 				= 	Validator::make(
			$thisData,
			array(
				'page_id' 		=> 'required',
				'page_name' 	=> 'required',
				'title' 		=> 'required',
				'meta_description' => 'required',
				'meta_keywords' => 'required',
				
			)
		);
		if ($validator->fails()){	
			return Redirect::back()
			->withErrors($validator)->withInput();
		}else{
			$doc 					= 	new NoCms;
			$doc->page_id    		= 	Input::get('page_id');
			$doc->page_name    		= 	Input::get('page_name');
			$doc->title    			= 	Input::get('title');
			$doc->meta_description  = 	Input::get('meta_description');
			$doc->meta_keywords    	= 	Input::get('meta_keywords');
			$doc->save();
			Session::flash('flash_notice', trans("Seo page added successfully")); 
			return Redirect::route($this->model.'.index');
		}
	}//end saveBlock()
/**
* Function for display page  for edit seo
*
* @param $Id ad id 
*
* @return view page. 
*/	
	public function editDoc($Id){
		$docs				=	NoCms::find($Id);
		if(empty($docs)) {
			return Redirect::route($this->model.'.index');
		}
		return  View::make('admin.'.$this->model.'.edit',array('doc' => $docs));
	}// end editBlock()
/**
* Function for update seo 
*
* @param $Id ad id of seo 
*
* @return redirect page. 
*/
	function updateDoc($Id){
		Input::replace($this->arrayStripTags(Input::all()));
		$this_data				=	Input::all();
		$doc 					= 	NoCms:: find($Id);
		$validator = Validator::make(
			$this_data,
			array(
				'page_id' 		=> 'required',
				'page_name' 	=> 'required',
				'title' 		=> 'required',
				'meta_description' => 'required',
				'meta_keywords' => 'required',
				
			)
		);
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{	
			$doc->page_id    		= 	Input::get('page_id');
			$doc->page_name    		= 	Input::get('page_name');
			$doc->title    			= 	Input::get('title');
			$doc->meta_description  = 	Input::get('meta_description');
			$doc->meta_keywords    	= 	Input::get('meta_keywords');
			$doc->save();
			Session::flash('flash_notice',  trans("Seo page updated successfully")); 
			return Redirect::route($this->model.'.index');
		}
	}// end updateNoCms()
/**
* Function for update seo  status
*
* @param $Id as id of seo 
* @param $Status as status of seo 
*
* @return redirect page. 
*/	
	public function updateDocStatus($Id = 0, $Status = 0){
		/* $model					=	NoCms::find($Id);
		$model->is_active		=	$Status;
		$model->save(); */
		if($Status == 0	){
			$statusMessage	=	trans("Seo page deactivated successfully");
		}else{
			$statusMessage	=	trans("Seo page activated successfully");
		}
		$this->_update_all_status('seos',$Id,$Status);
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::route($this->model.'.index');
	}// end updateNoCmsStatus()
/**
* Function for delete seo 
*
* @param $Id as id of seo 
*
* @return redirect page. 
*/	
	public function deleteDoc($Id = 0){
		if($Id){
			$doc				=	NoCms::find($Id) ;
			if(!empty($doc)){
				$this->_delete_table_entry('seos',$Id,'id');
			}
			/* $doc->delete();	 */
		}
		Session::flash('flash_notice',trans("Seo page removed successfully"));  
		return Redirect::route($this->model.'.index');
	}// end deleteNoCms()
/**
* Function for delete multiple seo
*
* @param null
*
* @return view page. 
*/
	public function performMultipleAction(){
		if(Request::ajax()){
			$actionType 		=	((Input::get('type'))) ? Input::get('type') : '';
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'delete'){
					NoCms::whereIn('id', Input::get('ids'))->delete();
					Session::flash('flash_notice',trans("messages.management.doc_all_delete_msg")); 
				}
			}
		}
	}//end performMultipleAction()
}// end BlockController	
