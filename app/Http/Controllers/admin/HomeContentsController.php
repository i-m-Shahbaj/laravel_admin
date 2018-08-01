<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\HomeContent;
use App\Model\User;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
* AdminDashBoard Controller
*
* Add your methods in the class below
*
* This file will render views\admin\dashboard
*/
class HomeContentsController extends BaseController {
		
	public $model	=	'HomeContent';
	
	public function __construct() {
		View::share('modelName',$this->model);
	}
/**
* Function for display admin dashboard
*
* @param null
*
* @return view page. 
*/
	public function listHomeContent(){
		$homeContents		=	DB::table('home_contents')->where('is_active',1)->where('is_deleted',0)->get();
		return  View::make('admin.'.$this->model.'.index',compact('homeContents'));
	}
	
	public function saveHomeContent() {
		$thisData		=	Input::all();
		//pr($thisData);die;
		$formData		=	$thisData['data'];
		
		//~ if(!empty($formData)){
			
				//~ $validator = Validator::make(
					//~ $formData,
					//~ array(
						//~ 'type'			=> 'required',
						//~ 'description'	=> 'required',
						//~ 'image'			=> 'mimes:'.IMAGE_EXTENSION,
					//~ )
				//~ );
			//~ if ($validator->fails()){
				 //~ //return Redirect::back()->withErrors($validator)->withInput();
				//~ $errors 				=	$validator->messages();
				//~ $response							=	array(
					//~ 'success' 						=> 	0,
					//~ 'errors' 						=> 	$errors
				//~ );
				//~ return Response::json($response); 
				//~ die;
			//~ }else{ 
				foreach($formData as $data){
					$homecontentId	=	HomeContent::where('type',$data['type'])->value('id');
					if(!empty($homecontentId)){
						$obj 							=  HomeContent::find($homecontentId);
					}else{
						$obj 							=  new HomeContent;
					}
					$obj->type 						=  !empty($data['type'])?$data['type']:'';
					$obj->content 					=  !empty($data['description'])?$data['description']:'';
					if(isset($data['image']) && !empty($data['image'])){
						$extension 	=	$data['image']->getClientOriginalExtension();
						$fileName	=	time().'-home-content-image.'.$extension;
						
						$newFolder     	= 	strtoupper(date('M'). date('Y'))."/";
						$folderPath		=	HOME_CONTENT_IMAGE_ROOT_PATH.$newFolder;
						if(!File::exists($folderPath)) {
							File::makeDirectory($folderPath, $mode = 0777,true);
						}
						if($data['image']->move($folderPath, $fileName)){
							if(!empty($homecontentId)){
								$image 			=	HomeContent::where('id',$homecontentId)->pluck('image');
								@unlink(HOME_CONTENT_IMAGE_ROOT_PATH.$image);
							}
							$obj->image	=	$newFolder.$fileName;
						}
					}
					//pr($obj);die;
					$obj->save();
				}
				//Session::flash('success',trans("Question has been added successfully."));
				//return Redirect::route("$this->model.index");.
				//$errors 				=	$validator->messages();
				$response							=	array(
					'success' 						=> 	1,
					'errors' 						=> 	array()
				);
				return Response::json($response); 
				die;
			//}
		//}
	}//end saveQuestion()

} //end AdminDashBoardController()
