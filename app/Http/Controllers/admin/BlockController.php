<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\BlockDescription;
use App\Model\Block;
use App\Model\Language;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
* BlockController Controller
*
* Add your methods in the class below
*
*/
	class BlockController extends BaseController {

	public $model	=	'Block';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
/**
* Function for display all Block 
*
* @param null
*
* @return view page. 
*/
	public function listBlock(){
	
		$DB					=	Block::query();
		$searchVariable		=	array(); 
		$inputGet			=	Input::get();
		
		if ((Input::get() && isset($inputGet['display'])) || isset($inputGet['page']) ) {
			$searchData	=	Input::get();
			unset($searchData['display']);
			unset($searchData['_token']);
			
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
		$sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    $order  = (Input::get('order')) ? Input::get('order')   : 'DESC';
		$model = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		
		$complete_string		=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$model->appends(Input::all())->render();
		
		return  View::make("admin.$this->model.index",compact('model','searchVariable','sortBy','order','query_string'));
	}// end listBlock()
/**
* Function for display page  for add new Block  
*
* @param null
*
* @return view page. 
*/
	public function addBlock(){
		$languages			=	DB::select("CALL GetAcitveLanguages(1)");
		$language_code		=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.add",compact('languages' ,'language_code'));
	} //end addBlock()
/**
* Function for save added Block page
*
* @param null
*
* @return redirect page. 
*/
	function saveBlock(){
		Input::replace($this->arrayStripTags(Input::all()));
		$thisData					=	Input::all();
		$language_code				=	Config::get('default_language.language_code');
		$dafaultLanguageArray		=	$thisData['data'][$language_code];
		
		//$dafaultLanguageArray		=	$thisData;
		$validator 					= 	Validator::make(
			array(
				'image' 			=>	Input::file('image'),
				'page_name' 		=> 	Input::get('page_name'),
				'block_name' 		=> 	Input::get('block_name'),
				'description' 		=> 	$dafaultLanguageArray['description'],
				'order' 			=> 	Input::get('order'),
			),
			array(
				'image' 			=>  'nullable|mimes:'.IMAGE_EXTENSION,
				//'image' 			=>  'filled',
				'page_name' 		=>  'required',
				'block_name' 		=>  'required',
				'description' 		=>  'required',
				'order' 			=>  'required|numeric|unique:blocks,block_order',
			)
		);
		
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model 					= 	new Block;
			if(!empty(Input::hasFile('image'))){
				if(Input::hasFile('image')){
					$extension 			=	Input::file('image')->getClientOriginalExtension();
					$fileName			=	time().'-block-image.'.$extension;
					if(Input::file('image')->move(BLOCK_ROOT_PATH, $fileName)){
						$model->image   =  	$fileName;
					}
				}
			}
			$model->page_name    	= 	Input::get('page_name');
			$model->block_name    	= 	Input::get('block_name');
			$model->block_order 	= 	Input::get('order');
			$model->page    		= 	$this->getSlug(Input::get('page_name'), 'page',$this->model);
			$model->block    		= 	$this->getSlug(Input::get('block_name'),'block',$this->model);
			$model->description   	= 	$dafaultLanguageArray['description'];
			$model->save();
			
			$modelId				=	$model->id;
			 foreach ($thisData['data'] as $language_id => $descriptionResult) {
				$modelDescription					=  new BlockDescription();
				$modelDescription->language_id		=	$language_id;
				$modelDescription->parent_id		=	$modelId;
				$modelDescription->description		=	$descriptionResult['description'];	
				$modelDescription->save();
			} 
			Session::flash('flash_notice',  trans("Block added successfully"));  
			return Redirect::route("$this->model.index");
		}
	}//end saveBlock()
/**
* Function for display page  for edit Block page
*
* @param $modelId as id of Block page
*
* @return view page. 
*/	
	public function editBlock($modelId){
		$model					=	Block::findorFail($modelId);
		if(empty($model)) {
			return Redirect::to('admin/block-manager');
		}
		$modelDescriptions		=	BlockDescription::where('parent_id','=',$modelId)->get();
		$multiLanguage			=	array();
		if(!empty($modelDescriptions)){
			foreach($modelDescriptions as $modelDescription) {
				$multiLanguage[$modelDescription->language_id]['description']	=	$modelDescription->description;
			}
		}
		$languages				=	DB::select("CALL GetAcitveLanguages(1)");
		$language_code			=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.edit",compact('languages','language_code','model','multiLanguage'));
	}// end editBlock()
/**
* Function for update Block 
*
* @param $modelId as id of Block 
*
* @return redirect page. 
*/
	function updateBlock($modelId){
		Input::replace($this->arrayStripTags(Input::all()));
		$this_data				=	Input::all();
		$model 					= 	Block:: findorFail($modelId);
		$activeLanguageCode		=	Config::get('default_language.language_code');
		$dafaultLanguageArray	=	$this_data['data'][$activeLanguageCode];
		//$dafaultLanguageArray	=	$this_data;
		$validator 				= 	Validator::make(
			array(
				'image' 		=> Input::file('image'),
				'page_name' 	=> Input::get('page_name'),
				'block_name' 	=> Input::get('block_name'),
				'description' 	=> $dafaultLanguageArray['description'],
				'order' 		=> Input::get('order'),
			),
			array(
				'image' 		=> 'nullable|mimes:'.IMAGE_EXTENSION,
				'page_name' 	=> 'required',
				'block_name' 	=> 'required',
				'description' 	=> 'required',
				'order' 		=> "required|numeric|unique:blocks,block_order,$modelId",
			)
		);
		
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			if(Input::hasFile('image')){
				$extension 		=	 Input::file('image')->getClientOriginalExtension();
				$fileName		=	time().'-block-image.'.$extension;
				if(Input::file('image')->move(BLOCK_ROOT_PATH, $fileName)){
					$image 			=	Block::where('id',$modelId)->pluck('image');
					@unlink(BLOCK_ROOT_PATH.$image);
			
					$model->image =  $fileName;
				}
			}
			$model->page_name   = Input::get('page_name');
			$model->block_name  = Input::get('block_name');
			$model->block_order = Input::get('order');
			$model->description = $dafaultLanguageArray['description'];
			$model->save();
			$this->_delete_table_entry('block_descriptions',$modelId,'parent_id');
			foreach ($this_data['data'] as $languageId => $descriptionResult) {
				$modelDescription				=  new BlockDescription();
				$modelDescription->language_id	=	$languageId;
				$modelDescription->parent_id	=	$modelId;
				$modelDescription->description	=	$descriptionResult['description'];	
				$modelDescription->save();
			} 
			Session::flash('flash_notice',  trans("Block updated successfully"));
			return Redirect::route("$this->model.index");
		}
	}// end updateBlock()
/**
* Function for update Block  status
*
* @param $modelId as id of Block 
* @param $modelStatus as status of Block 
*
* @return redirect page. 
*/	
	public function updateBlockStatus($modelId = 0, $modelStatus = 0){
		/* Block::where('id', '=', $modelId)->update(array('status' => $modelStatus)); */
		if($modelStatus == 0	){
			$statusMessage	=	trans("Block deactivated successfully");
		}else{
			$statusMessage	=	trans("Block activated successfully");
		}
		$this->_update_all_status('blocks',$modelId,$modelStatus);
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::route("$this->model.index");
	}// end updateBlockStatus()
/**
* Function for delete Block 
*
* @param $modelId as id of Block 
*
* @return redirect page. 
*/	
	public function deleteBlock($modelId = 0){
		if($modelId){
			$image 			=	Block::where('id',$modelId)->pluck('image');
			@unlink(BLOCK_ROOT_PATH.$image);
			$this->_delete_table_entry('blocks',$modelId,'id');
			$this->_delete_table_entry('block_descriptions',$modelId,'parent_id');
			Session::flash('flash_notice',trans("Block removed successfully")); 
		}
		return Redirect::route("$this->model.index");
	} // end deleteBlock()
/**
* Function for delete multiple Block
*
* @param null
*
* @return view page. 
*/
	public function performMultipleAction(){
		if(Request::ajax()){
			$actionType = ((Input::get('type'))) ? Input::get('type') : '';
			if(!empty($actionType) && !empty(Input::get('ids'))){
				if($actionType	==	'active'){
					Blog::whereIn('id', Input::get('ids'))->update(array('status' => ACTIVE));
				}
				elseif($actionType	==	'inactive'){
					Blog::whereIn('id', Input::get('ids'))->update(array('status' => 0));
				}
				elseif($actionType	==	'delete'){
					Blog::whereIn('id', Input::get('ids'))->delete();
				}
				Session::flash('success', trans("messages.global.action_performed_message")); 
			}
		}
	}//end performMultipleAction()
/**
* Function for update the orderby field
*
* @param null
*
* @return view page. 
*/
	public function changeBlockOrder(){
		$order_by			=	Input::get('order_by'); 
		$id					=	Input::get('current_id');
		$sliderOrder		=	Block::where('id',$id)->pluck('block_order');
		$validator 			= 	Validator::make(
					Input::all(),
					array(
						'order_by' 		=> 'required|numeric|unique:blocks,block_order,'.$id,
					)
		);
		$message			= 	$validator->messages()->toArray();
		if ($validator->fails()){	
			$response		=	array(
					'success' => false,
					'message'=> $message['order_by'],	
					
			);
			return Response::json($response); die;			
		}else{
			Block::where('id',$id)->update(
						array(
							'block_order' => $order_by,
						)
					);
					
			$response		=	array(
					'success' => 1,
					'order_by' => $order_by,
			);
			return Response::json($response); die;		
		}
	}//end changeSliderOrder()
}// end BlockController
