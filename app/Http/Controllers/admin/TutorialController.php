<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Tutorial;
use App\Model\Language;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
* TutorialController Controller
*
* Add your methods in the class below
*
*/
	class TutorialController extends BaseController {

	public $model	=	'Tutorial';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
/**
* Function for display all Tutorial 
*
* @param null
*
* @return view page. 
*/
	public function listTutorial(){
	
		$DB					=	Tutorial::query();
		$searchVariable		=	array(); 
		$inputGet			=	Input::get();
		
		if (Input::get()) {
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
	}// end listTutorial()
/**
* Function for display page  for add new Tutorial  
*
* @param null
*
* @return view page. 
*/
	public function addTutorial(){
		$languages			=	DB::select("CALL GetAcitveLanguages(1)");
		$language_code		=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.add",compact('languages' ,'language_code'));
	} //end addTutorial()
/**
* Function for save added Tutorial page
*
* @param null
*
* @return redirect page. 
*/
	function saveTutorial(){
		Input::replace($this->arrayStripTags(Input::all()));
		$thisData					=	Input::all();
		
		$validator 					= 	Validator::make(
			array(
				'image' 			=>	Input::file('image'),
				'youtube_url' 		=> 	Input::get('youtube_url'),
				'description' 		=>  Input::get('description'),
				'tutorial_order' 	=> 	Input::get('order'),
			),
			array(
				'image' 			=>  'nullable|mimes:'.IMAGE_EXTENSION,
				//'image' 			=>  'filled',
				'youtube_url' 		=>  'required|url',
				'description' 		=>  'required',
				'tutorial_order' 			=>  'required|numeric|unique:tutorials,tutorial_order',
			)
		);
		
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			$model 					= 	new Tutorial;
			if(!empty(Input::hasFile('image'))){
				if(Input::hasFile('image')){
					$extension 			=	Input::file('image')->getClientOriginalExtension();
					$fileName			=	time().'-tutorial-image.'.$extension;
					if(Input::file('image')->move(TUTORIAL_IMAGE_ROOT_PATH, $fileName)){
						$model->image   =  	$fileName;
					}
				}
			}
			$model->youtube_url    	= 	Input::get('youtube_url');
			$model->tutorial_order 	= 	Input::get('order');
			$model->description   	= 	Input::get('description');
			$model->save();
			
			Session::flash('flash_notice',  trans("Tutorial added successfully"));  
			return Redirect::route("$this->model.index");
		}
	}//end saveTutorial()
/**
* Function for display page  for edit Tutorial page
*
* @param $modelId as id of Tutorial page
*
* @return view page. 
*/	
	public function editTutorial($modelId){
		$model					=	Tutorial::findorFail($modelId);
		if(empty($model)) {
			return Redirect::to('admin/tutorials');
		}
		return  View::make("admin.$this->model.edit",compact('model'));
	}// end editTutorial()
/**
* Function for update Tutorial 
*
* @param $modelId as id of Tutorial 
*
* @return redirect page. 
*/
	function updateTutorial($modelId){
		Input::replace($this->arrayStripTags(Input::all()));
		$this_data				=	Input::all();
		$model 					= 	Tutorial:: findorFail($modelId);
		$validator 					= 	Validator::make(
			array(
				'image' 			=>	Input::file('image'),
				'youtube_url' 		=> 	Input::get('youtube_url'),
				'description' 		=>  Input::get('description'),
				'tutorial_order' 	=> 	Input::get('order'),
			),
			array(
				'image' 			=>  'nullable|mimes:'.IMAGE_EXTENSION,
				//'image' 			=>  'filled',
				'youtube_url' 		=>  'required|url',
				'description' 		=>  'required',
				'tutorial_order' 			=>  'required|numeric|unique:tutorials,tutorial_order',
			)
		);
		
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			if(Input::hasFile('image')){
				$extension 		=	 Input::file('image')->getClientOriginalExtension();
				$fileName		=	time().'-tutorial-image.'.$extension;
				if(Input::file('image')->move(TUTORIAL_IMAGE_ROOT_PATH, $fileName)){
					$image 			=	Tutorial::where('id',$modelId)->pluck('image');
					@unlink(TUTORIAL_IMAGE_ROOT_PATH.$image);
			
					$model->image =  $fileName;
				}
			}
			$model->youtube_url    	= 	Input::get('youtube_url');
			$model->tutorial_order 	= 	Input::get('order');
			$model->description   	= 	Input::get('description');
			$model->save();
			
			Session::flash('flash_notice',  trans("Tutorial updated successfully"));
			return Redirect::route("$this->model.index");
		}
	}// end updateTutorial()
/**
* Function for update Tutorial  status
*
* @param $modelId as id of Tutorial 
* @param $modelStatus as status of Tutorial 
*
* @return redirect page. 
*/	
	public function updateTutorialStatus($modelId = 0, $modelStatus = 0){
		/* Tutorial::where('id', '=', $modelId)->update(array('status' => $modelStatus)); */
		if($modelStatus == 0	){
			$statusMessage	=	trans("Tutorial deactivated successfully");
		}else{
			$statusMessage	=	trans("Tutorial activated successfully");
		}
		$this->_update_all_status('tutorials',$modelId,$modelStatus);
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::route("$this->model.index");
	}// end updateTutorialStatus()
/**
* Function for delete Tutorial 
*
* @param $modelId as id of Tutorial 
*
* @return redirect page. 
*/	
	public function deleteTutorial($modelId = 0){
		if($modelId){
			$image 			=	Tutorial::where('id',$modelId)->pluck('image');
			@unlink(TUTORIAL_IMAGE_ROOT_PATH.$image);
			$this->_delete_table_entry('tutorials',$modelId,'id');
			Session::flash('flash_notice',trans("Tutorial removed successfully")); 
		}
		return Redirect::route("$this->model.index");
	} // end deleteTutorial()
/**
* Function for delete multiple Tutorial
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
	public function changeTutorialOrder(){
		$order_by			=	Input::get('order_by'); 
		$id					=	Input::get('current_id');
		$sliderOrder		=	Tutorial::where('id',$id)->pluck('tutorial_order');
		$validator 			= 	Validator::make(
					Input::all(),
					array(
						'order_by' 		=> 'required|numeric|unique:tutorials,tutorial_order,'.$id,
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
			Tutorial::where('id',$id)->update(
						array(
							'tutorial_order' => $order_by,
						)
					);
					
			$response		=	array(
					'success' => 1,
					'order_by' => $order_by,
			);
			return Response::json($response); die;		
		}
	}//end changeSliderOrder()
}// end TutorialController
