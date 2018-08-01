<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\DropDown;
use App\Model\Language;
use App\Model\DropDownDescription;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
* DropDownController Controller
*
* Add your methods in the class below
*
* This file will render views from views/dropdown
*/
	class DropDownController extends BaseController {
		
		public $model	=	'DropDown';
		
		public function __construct() {
			View::share('modelName',$this->model);
		}
/**
* Function for display all DropDown    
*
* @param $type as category of dropdown 
*
* @return view page. 
*/
	public function listDropDown($type=''){
		if(empty($type)) {
			return route('dashboard.showdashboard');
		}
		$DB				=	DropDown::query()->where('dropdown_type',$type);
		$searchVariable	=	array(); 
		$inputGet		=	Input::get();
		//print_r($inputGet);die;
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
			/* echo  "<pre>";
			print_r($searchData);die; */
			foreach($searchData as $fieldName => $fieldValue){
				if(!empty($fieldValue) || $fieldValue==0){
					$DB->where("$fieldName",'like','%'.$fieldValue.'%');
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		
		$sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    $order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
		$result = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page")); 
		
		$complete_string		=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$result->appends(Input::all())->render();
		
		return  View::make('admin.'.$this->model.'.index',compact('result','searchVariable','sortBy','order','type','query_string'));
	}// end listDropDown()
/**
* Function for display page  for add new DropDown  
*
* @param $type as category of dropdown 
*
* @return view page. 
*/
	public function addDropDown($type=''){
		$languages			=	DB::select("CALL GetAcitveLanguages(1)");
		$default_language	=	Config::get('default_language');
		$language_code 		=   $default_language['language_code'];
		
		return  View::make('admin.'.$this->model.'.add',compact('languages' ,'language_code','type'));
	} //end addDropDown()
/**
* Function for save added DropDown page
*
* @param null
*
* @return redirect page. 
*/
	function saveDropDown($type=''){
		Input::replace($this->arrayStripTags(Input::all()));
		$thisData										=	Input::all();
		$default_language								=	Config::get('default_language');
		$language_code 									=   $default_language['language_code'];
		$dafaultLanguageArray							=	$thisData['data'][$language_code];
		$validator = Validator::make(
			array(
				'name' 			=>  $dafaultLanguageArray['name'],
				'dropdown_type'	=>	$type,
				
			),	
			array(
				'name' 			=> 'required',
				
			)
		);
		if ($validator->fails()){	
			return Redirect::route('DropDown.add',$type)
				->withErrors($validator)->withInput();
		}else{
			$dropdown = new DropDown;
			$dropdown->slug    							= 	$this->getSlugWithoutModel($type ,'slug', 'dropdown_managers');
			$dropdown->name    							= 	$dafaultLanguageArray['name'];
			$dropdown->dropdown_type    				= 	$type;
			$dropdown->save(); 
			$dropdownId									=	$dropdown->id;
			foreach ($thisData['data'] as $language_id => $value) {
				$modelDropDownDescription				=  new DropDownDescription();
				$modelDropDownDescription->language_id	=	$language_id;
				$modelDropDownDescription->parent_id	=	$dropdownId;
				$modelDropDownDescription->name			=	$value['name'];		
				$modelDropDownDescription->save();
			}
			Session::flash('flash_notice', trans(ucfirst($type).' added successfully')); 
			return Redirect::route('DropDown.listDropDown',$type);
		}
	}//end saveDropDown()
/**
* Function for display page  for edit DropDown page
*
* @param $Id ad id of DropDown 
* @param $type as category of dropdown 
*
* @return view page. 
*/	
	public function editDropDown($Id,$type){
		$dropdown				=	DropDown::find($Id);
		if(empty($dropdown)) {
			return Redirect::route('DropDown.listDropDown',$type);
		}
		$dropdownDescription	=	DropDownDescription::where('parent_id', '=',  $Id)->get();
		$multiLanguage		 	=	array();
		if(!empty($dropdownDescription)){
			foreach($dropdownDescription as $description) {
				$multiLanguage[$description->language_id]['name']			=	$description->name;				
			}
		}
		$languages				=	DB::select("CALL GetAcitveLanguages(1)");
		$default_language		=	Config::get('default_language');
		$language_code 			=   $default_language['language_code'];
		return  View::make('admin.'.$this->model.'.edit',array('languages' => $languages,'language_code' => $language_code,'dropdown' => $dropdown,'multiLanguage' => $multiLanguage,'type'=>$type));
	}// end editDropDown()
/**
* Function for update DropDown 
*
* @param $Id ad id of DropDown 
* @param $type as category of dropdown 
*
* @return redirect page. 
*/
	function updateDropDown($Id,$type=''){
		Input::replace($this->arrayStripTags(Input::all()));
		$this_data										=	Input::all();
		$dropdown 										= 	DropDown:: find($Id);
		$default_language								=	Config::get('default_language');
		$language_code 									=   $default_language['language_code'];
		$dafaultLanguageArray							=	$this_data['data'][$language_code];
		$validator 										= 	Validator::make(
			array(
				'name' 		=> $dafaultLanguageArray['name'],
				//'image' 		=> Input::file('image'),
			),
			array(
				'name' 		=> 'required',
				//'image' 		=> 'mimes:jpeg,jpg,png,gif',
			)
		);
		if ($validator->fails()){	
			return Redirect::route('DropDown.edit',$Id,$type)
				->withErrors($validator)->withInput();
		}else{
			$dropdown->name								= 	$dafaultLanguageArray['name'];
			/* if (Input::hasFile('image')){
				$extension 								=	 Input::file('image')->getClientOriginalExtension();
				$fileName								=	time().'-resource-image.'.$extension;
				if(Input::file('image')->move(MASTERS_IMAGE_ROOT_PATH, $fileName)){
					$dropdown->image					= 	$fileName;
				}
			} */
			$dropdown->save();
			$dropdownId		=	$dropdown->id;
			$dropdownId		=	$Id;
			DropDownDescription::where('parent_id', '=', $Id)->delete();
			foreach ($this_data['data'] as $language_id => $value) {
				$modelDropDownDescription				=  new DropDownDescription();
				$modelDropDownDescription->language_id	=	$language_id;
				$modelDropDownDescription->name			=	$value['name'];	
				$modelDropDownDescription->parent_id	=	$dropdownId;
				$modelDropDownDescription->save();					
			}
			Session::flash('flash_notice',trans(ucfirst($type)." updated successfully")); 
			return Redirect::route('DropDown.listDropDown',$type);
		}
	}// end updateDropDown()
/**
* Function for update DropDown  status
*
* @param $Id as id of DropDown 
* @param $Status as status of DropDown 
* @param $type as category of dropdown 
*
* @return redirect page. 
*/	
	public function updateDropDownStatus($Id = 0, $Status = 0,$type=''){
		if($Status == 0	){
			$statusMessage	=	trans(ucfirst($type)." deactivated successfully");
		}else{
			$statusMessage	=	trans(ucfirst($type)." activated successfully");
		}
		$this->_update_all_status('dropdown_managers',$Id,$Status);
		
		/* if($Status == 1){
			$message				=	trans("messages.master.master_activate_message");
		}else{
			$message				=	trans("messages.master.master_deactivate_message");
		}
		$model						=	DropDown::find($Id);
		$model->is_active			=	$Status;
		$model->save(); */
		Session::flash('flash_notice',$statusMessage); 
		return Redirect::route('DropDown.listDropDown',$type);
	}// end updateDropDownStatus()
/**
* Function for delete DropDown 
*
* @param $Id as id of DropDown 
* @param $type as category of dropdown 
*
* @return redirect page. 
*/	
	public function deleteDropDown($Id = 0,$type=''){
		$dropdown					=	DropDown::find($Id) ;
		//$dropdown->description()->delete();
		/* if($type=='faq'){
			$dropdown->faq()->delete();
		} */
		//$dropdown->delete();
		if(!empty($dropdown)){
			$this->_delete_table_entry('dropdown_managers',$Id,'id');
			$this->_delete_table_entry('dropdown_manager_descriptions',$Id,'parent_id');
			Session::flash('flash_notice', trans(ucfirst($type)." removed successfully"));  
		}else{
			Session::flash('error', trans("Invalid url"));  
		}
		return Redirect::route('DropDown.listDropDown',$type);
	}// end deleteDropDown()
/**
* Function for multiple delete
*
* @param $type as type of dropdown
*
* @return redirect page. 
*/
 	public function performMultipleAction($type = 0){
		if(Request::ajax()){
			$actionType 			= ((Input::get('type'))) ? Input::get('type') : '';
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'delete'){
					$dropdown		=	DropDown::whereIn('id', Input::get('ids'));
					$dropdown->description()->delete();
					/* if($type=='faq'){
						$dropdown->faq()->delete();
					} */
					$dropdown->delete();
				}
				Session::flash('flash_notice', trans("messages.user_management.action_performed_message")); 
			}
		}
	}//end performMultipleAction()
}// end DropDownController
