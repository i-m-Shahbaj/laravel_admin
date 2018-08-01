<?php
/**
 * UsersController
 */
namespace App\Http\Controllers\front;
use App\Http\Controllers\BaseController;
use App\Model\User;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\Challenge;
use App\Model\SystemDoc;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,App;

class ChallengesController extends BaseController {

		
	
/** 
 * Function to Challenges
 *
 * @param null
 * 
 * @return view page
 */	
	public function index(){
		$systemImageObj	=	new SystemDoc();
		$systemImage	=	$systemImageObj->getSystemImage(DASHBOARD_PAGE_IMAGE_ID);
		
		$DB 			= 	Challenge::query();	
		$result	=	$DB->get();

		//print_r($result);die;
		return View::make('front.Challenge.index' , compact('result','systemImage'));
	}// end index()
	
/** 
 * Function to Challenges
 *
 * @param null
 * 
 * @return view page
 */	
	public function question(){
		$systemImageObj	=	new SystemDoc();
		$systemImage	=	$systemImageObj->getSystemImage(DASHBOARD_PAGE_IMAGE_ID);
		
		$DB 			= 	Challenge::query();	
		$result	=	$DB->get();

		//print_r($result);die;
		return View::make('front.Challenge.question' , compact('result','systemImage'));
	}// end Challenge()

/** 
 * Function to Challenges
 *
 * @param null
 * 
 * @return view page
 */	
	public function saveChallenge(){
		Input::replace($this->arrayStripTags(Input::all()));
		$formData			=	Input::all();
		$user_id		 	=   Auth::user()->id;
		if(!empty($formData)){
			$validator = Validator::make(
				Input::all(),
				array(
					'message'	=> 'required',
				),
				array(
					'message.required'		=>	trans("Please Enter Message"),
				)
			);
			if ($validator->fails()){
				$response	=	array(
					'success' 	=> false,
					'errors' 	=> $validator->errors()
				);
				return Response::json($response); 
				die;
			}else{
				$obj 						= 	new ProjectArticleComment();
				$obj->user_id		 		=  	Auth::user()->id;
				$obj->article_id			=  	Input::get('article_id');
				$obj->message				=  	Input::get('message');
				$obj->save();
				$response	=	array(
					'success' 	=>	'1',
					'errors' 	=>	 trans("")
				); 
				//Session::flash('flash_notice', trans("messages.forum.comment_posted_successfully"));
				return  Response::json($response); 
				die;
			}
		}

	}// end ChallengeArticles()

}// end UsersController class
