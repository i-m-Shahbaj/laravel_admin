<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Testimonial;
use App\Model\TestimonialDescription;
use App\Model\Language;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator;

/**
 * TestimonialController Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views/admin/testimonial
 */
 
class TestimonialController extends BaseController {
	public $model	=	'Testimonial';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
/**
 * Function for display all Testimonial    
 *
 * @param null
 *
 * @return view page. 
 */
	public function listTestimonial(){
		$DB						=	Testimonial::query();
		$searchVariable			=	array(); 
		$inputGet				=	Input::get();
		## Searching on the basis of comment ##
		if (Input::get()) {
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
		$sortBy 				= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'updated_at';
	    $order  				= 	(Input::get('order')) ? Input::get('order')   : 'DESC';
		$model 					= 	$DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string		=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$model->appends(Input::all())->render();
		return  View::make("admin.$this->model.index",compact('model','searchVariable','sortBy','order','query_string'));
	}// end listTestimonial()
	
/**
 * Function for display page  for add new Testimonial  
 *
 * @param null
 *
 * @return view page. 
 */
	public function addTestimonial(){
		$languages				=	DB::select("CALL GetAcitveLanguages(1)");
		$language_code			=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.add",compact('languages' ,'language_code'));
	} //end addTestimonial()
	
/**
 * Function for save added Testimonial page
 *
 * @param null
 *
 * @return redirect page. 
 */
	function saveTestimonial(){
		Input::replace($this->arrayStripTags(Input::all()));
		$thisData				=	Input::all();
		$default_language		=	Config::get('default_language');
		$language_code 			=   $default_language['language_code'];
		$dafaultLanguageArray	=	$thisData['data'][$language_code];
		$validator = Validator::make(
			array(
				'image' 		=> Input::file('image'),
				'client_name' 	=> $dafaultLanguageArray['client_name'],
				'comment' 		=> $dafaultLanguageArray['comment'],
				'order' 		=> 	Input::get('order'),
				'location' 		=> 	Input::get('location'),
			),
			array(
				'image' 		=> 'required|image',
				'client_name' 	=> 'required',
				'comment' 		=> 'required',
				'location' 		=> 'required',
				'order' 		=> 'required|numeric|unique:testimonials,testimonial_order',
			),
			array(
				'image.required' => 'The client image field is required.',
				'comment.required' => 'The client comment field is required.',
				'designation.required' => 'The profession field is required.',
			)
		);
		if ($validator->fails()){	
			return Redirect::back()
			->withErrors($validator)->withInput();
		}else{
			$model 											= 	new Testimonial;
			if(Input::hasFile('image')){
				$extension 									=	Input::file('image')->getClientOriginalExtension();
				$fileName									=	time().'testimonial-image.'.$extension;
				if(Input::file('image')->move(TESTIMONIAL_ROOT_PATH, $fileName)){
					$model->image  							=  $fileName;
				}
			}
			$model->client_name    							=	$dafaultLanguageArray['client_name'];
			$model->comment   								= 	$dafaultLanguageArray['comment'];
			$model->testimonial_order 						= 	Input::get('order');
			$model->location		 						= 	Input::get('location');
			$model->save();
			$modelId										=	$model->id;		
			foreach ($thisData['data'] as $language_id => $descriptionResult) {
				$modelDescription							=	new TestimonialDescription();
				$modelDescription->language_id				=	$language_id;
				$modelDescription->parent_id				=	$modelId;
				$modelDescription->client_name				=	$descriptionResult['client_name'];	
				$modelDescription->comment					=	$descriptionResult['comment'];	
				$modelDescription->save();
			}
			Session::flash('flash_notice',  trans("Testimonial added successfully"));  
			return Redirect::route("$this->model.index");
		}
	}//end saveTestimonial()
	
/**
 * Function for display page  for edit Testimonial page
 *
 * @param $Id as id of Testimonial 
 *
 * @return view page. 
 */	
	public function editTestimonial($modelId){
		$model					=	Testimonial::findorFail($modelId);
		if(empty($model)) {
			return Redirect::to('admin/testimonial-manager');
		}
		$modelDescriptions		=	TestimonialDescription::where('parent_id','=',$modelId)->get();
		
		$multiLanguage			=	array();
		if(!empty($modelDescriptions)){
			foreach($modelDescriptions as $modelDescription) {
				$multiLanguage[$modelDescription->language_id]['client_name']	=	$modelDescription->client_name;
				$multiLanguage[$modelDescription->language_id]['comment']		=	$modelDescription->comment;						
			}
		}
		$languages				=	DB::select("CALL GetAcitveLanguages(1)");
		$language_code			=	Config::get('default_language.language_code');
		return  View::make("admin.$this->model.edit",compact('languages','language_code','model','multiLanguage'));
	}// end editTestimonial()
	
/**
 * Function for update Testimonial 
 *
 * @param $Id ad id of Testimonial 
 *
 * @return redirect page. 
 */
	function updateTestimonial($modelId){
		Input::replace($this->arrayStripTags(Input::all()));
		$model 					= 	Testimonial:: findOrFail($modelId);
		$this_data				=	Input::all();
		$language_code			=	Config::get('default_language.language_code');
		$dafaultLanguageArray	=	$this_data['data'][$language_code];
		$validator = Validator::make(
			array(
				'client_name' 	=> $dafaultLanguageArray['client_name'],
				'comment' 		=> $dafaultLanguageArray['comment'],
				'order' 		=> Input::get('order'),
				'location' 		=> Input::get('location'),
			),
			array(
				'client_name' 	=> 'required',
				'comment' 		=> 'required',
				'location' 		=> 'required',
				'order' 		=> "required|numeric|unique:testimonials,testimonial_order,$modelId",
			),
			array(
				'comment.required' => 'The client comment field is required.'
			)
		);
		if ($validator->fails()){	
			return Redirect::back()
				->withErrors($validator)->withInput();
		}else{
			if(Input::hasFile('image')){
				$extension 		=	 Input::file('image')->getClientOriginalExtension();
				$fileName		=	time().'-testimonial-image.'.$extension;
				if(Input::file('image')->move(TESTIMONIAL_ROOT_PATH, $fileName)){
					$model->image =  $fileName;
				}
			}
			$model->client_name    					= 	$dafaultLanguageArray['client_name'];
			$model->comment   						= 	$dafaultLanguageArray['comment'];
			$model->testimonial_order 				= 	Input::get('order');
			$model->location 						= 	Input::get('location');
			$model->save();
			$modelId								=	$model->id;
			TestimonialDescription::where('parent_id', '=', $modelId)->delete();
			foreach ($this_data['data'] as $language_id => $descriptionResult){
				$modelDescription					=  new TestimonialDescription();
				$modelDescription->language_id		=	$language_id;
				$modelDescription->parent_id		=	$modelId;
				$modelDescription->client_name		=	$descriptionResult['client_name'];	
				$modelDescription->comment			=	$descriptionResult['comment'];
				$modelDescription->save();		
			}
			Session::flash('flash_notice',  trans("Testimonial updated successfully"));
			return Redirect::route("$this->model.index");
		}
	}// end updateTestimonial()
	
/**
 * Function for update Testimonial  status
 *
 * @param $Id as id of Testimonial 
 * @param $Status as status of Testimonial 
 *
 * @return redirect page. 
 */	
	public function updateTestimonialStatus($modelId = 0, $modelStatus = 0){
		if($modelStatus == 0	){
			$statusMessage	=	trans("Testimonial deactivated successfully");
		}else{
			$statusMessage	=	trans("Testimonial activated successfully");
		}
		$this->_update_all_status('testimonials',$modelId,$modelStatus);
		/* Testimonial::where('id', '=', $modelId)->update(array('is_active' => $modelStatus)); */
		Session::flash('flash_notice', $statusMessage); 
		return Redirect::back();
	}// end updateTestimonialStatus()
	
/**
 * Function for delete Testimonial 
 *
 * @param $Id as id of Testimonial 
 *
 * @return redirect page. 
 */	
	public function deleteTestimonial($modelId = 0){
		if($modelId){
			/* $model 					= 	Testimonial::findorFail($modelId);
			$model->description()->delete();
			$model->delete(); */
			
			$this->_delete_table_entry('testimonials',$modelId,'id');
			$this->_delete_table_entry('testimonial_descriptions',$modelId,'parent_id');
			
			Session::flash('flash_notice',trans("Testimonial removed successfully")); 
		}
		return Redirect::route("$this->model.index");
	}// end deleteTestimonial()
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
		$sliderOrder		=	Testimonial::where('id',$id)->pluck('testimonial_order');
		$validator 			= 	Validator::make(
					Input::all(),
					array(
						'order_by' 		=> 'required|numeric|unique:testimonials,testimonial_order,'.$id,
					),
					array(
						"order_by.required"=>'This order is required.',
						"order_by.numeric"=>'This order is numeric only.',
						"order_by.unique"=>'This order has been already taken.'
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
			Testimonial::where('id',$id)->update(
						array(
							'testimonial_order' => $order_by,
						)
					);
					
			$response		=	array(
					'success' => 1,
					'order_by' => $order_by,
			);
			return Response::json($response); die;		
		}
	}//end changeSliderOrder()
	
}// end TestimonialController class
