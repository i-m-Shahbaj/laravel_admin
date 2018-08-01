<?php
namespace App\Http\Controllers\mobile;
use App\Http\Controllers\BaseController;
use App\Model\User;
use App\Model\Tutorial;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\ApiResponse;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,App;
use Carbon\Carbon;

/**
* Users Controller
*
* Add your methods in the class below
*
* This file use for call api
*/
class TutorialsController extends BaseController {
	
	public function get_all_tutorials(){
		$formData	=	Input::all();
		$response	=	array();
		if(!empty($formData)){
			$validator 	=	Validator::make(
					Input::all(),
					array(
						'device_type'			=> 'required',
						'device_id'				=> 'required',
					)
				);
			if ($validator->fails()){
				$response				=	$this->change_error_msg_layout($validator->errors()->getMessages());
			}else{
				$user_id		=	Input::get('user_id');
				$tutorials		=	DB::table('tutorials')
										->where('tutorials.is_active',1)
										->where('tutorials.is_deleted',0)
										->select('image','youtube_url','description')
										->orderBy("tutorial_order",'ASC')
										->get();
				$tutorials		=	json_decode(json_encode($tutorials)); 
				if(!empty($tutorials)){
					
					foreach($tutorials as &$image){
						if(!empty($image->image) && file_exists(TUTORIAL_IMAGE_ROOT_PATH.$image->image)){
							$image->image		=	TUTORIAL_IMAGE_URL.$image->image;
						}else {
							$image->image		=	WEBSITE_IMG_URL.'no_image.jpg';
						}
					}
					
				}	
				
				if(!empty($tutorials)){
					$response["status"]			=	"success";
					$response["message"]		=	"Tutorials found successfully";
					$response["post"]			=	$tutorials;
				}else{
					$response["status"]			=	"error";
					$response["message"]		=	"Tutorials not found.";
					$response["post"]	=	array();
				}
			}	
		}else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["post"]		=	array();
		}
		return json_encode($response);
	}
	
}
