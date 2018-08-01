<?php
/**
 * UsersController
 */
namespace App\Http\Controllers\front;
use App\Http\Controllers\BaseController;
use App\Model\User;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\ProjectLibrary;
use App\Model\ProjectFolder;
use App\Model\SystemDoc;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,App;



class PagesController extends BaseController {

		
	public function showCms($slug){
		$systemImageObj	=	new SystemDoc();
		$systemImage	=	$systemImageObj->getSystemImage(CMS_PAGE_IMAGE_ID); 
		$lang			=	App::getLocale();
		$cmsPagesDetail	=	DB::select( DB::raw("SELECT * FROM cms_page_descriptions WHERE foreign_key = (select id from cms_pages WHERE cms_pages.slug = '$slug') AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );
		
		if(empty($cmsPagesDetail)){
			return Redirect::to('/');
		}
		$result	=	array();
		foreach($cmsPagesDetail as $cms){
			$key	=	$cms->source_col_name;
			$value	=	$cms->source_col_description;
			$result[$cms->source_col_name]	=	$cms->source_col_description;
		}
		return View::make('front.cms.index' , compact('result','slug' ,'systemImage'));
	}//end showCms()
	
	public function contactUs(){
		$systemImageObj	=	new SystemDoc();
		$systemImage	=	$systemImageObj->getSystemImage(CONTACT_PAGE_IMAGE_ID); 
		
		Input::replace($this->arrayStripTags(Input::all()));
		$allData	=	Input::all();
		$lang		=	App::getLocale();
		if(!empty($allData)){
			$validator = Validator::make(
				$allData['data'],
				array(
					'name' 						=> 'required',
					'email' 					=> 'required|email',
					'subject' 					=> 'required',
					'message'  					=> 'required',
					'captcha' 					=> 'required|captcha',
				),
				array(
					'name.required' 			=> trans('This field is required.'),
					'email.required' 			=> trans('This field is required.'),
					'email.email' 				=> trans('Please enter valid email address.'),
					'subject.required'			=> trans('This field is required.'),
					'message.required' 			=> trans('This field is required.'),
					"captcha"					=> trans("This field is required."),
					"captcha.captcha"			=> trans("Captcha value does not match"),
				)
			);
			
			if ($validator->fails()){
				return Redirect::back()->withErrors($validator)->withInput();
			}else{
				$date = date("Y-m-d H:i:s");
				DB::table('contact_us')->insert(
					array(
						'name'			=> $allData['data']['name'],
						'email' 		=> $allData['data']['email'],
						'subject' 		=> $allData['data']['subject'],
						'message' 		=> $allData['data']['message'],
						'created_at' 	=> $date,
						'updated_at' 	=> $date,
					)
				);
				
				//send email to site admin with user information,to inform that user wants to contact
				$emailActions		=  EmailAction::where('action','=','contact_us')->get()->toArray();
				$emailTemplates		=  EmailTemplate::where('action','=','contact_us')->get()->toArray();
				$cons 				=  explode(',',$emailActions[0]['options']);
				$constants 			=  array();
				 
				foreach($cons as $key=>$val){
					$constants[] = '{'.$val.'}';
				}
				
				$name				=	 $allData['data']['name'];
				$email				=	 $allData['data']['email'];
				$message			=	 $allData['data']['message'];
				$subject_data		=	 $allData['data']['subject'];
				$subject 			=  $emailTemplates[0]['subject'];
				$rep_Array 			=  array($name,$email,$subject_data,$message); 
				$messageBody		=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				
				$settingsEmail = Config::get("Site.email");
				$contactEmail = Config::get("Contact.email");
				$this->sendMail($contactEmail,'Admin',$subject,$messageBody,$settingsEmail);
				Session::flash('flash_notice',  trans("Thank you for contacting us.")); 
				return Redirect::to("contact-us");
				die;
			}
		}
		return View::make("front.cms.contact_us",compact('systemImage'));
	}
	
/** 
 * Function to faq
 *
 * @param null
 * 
 * @return view page
 */	
	public function faq(){
		$systemImageObj	=	new SystemDoc();
		$systemImage	=	$systemImageObj->getSystemImage(CMS_PAGE_IMAGE_ID);
		$lang			=	App::getLocale();
		
		$faqCategories	=	DB::select( DB::raw("SELECT parent_id,name FROM dropdown_manager_descriptions WHERE parent_id IN(SELECT id FROM dropdown_managers WHERE dropdown_type = 'faq-categories' AND is_active = 1) AND language_id = (select id from languages WHERE languages.lang_code = '$lang')") );
		
		if(empty($faqCategories)){
			return Redirect::to('/');
		}
		
		$faqCategoryList = DB::table("dropdown_managers")->where('is_active',1)->where('dropdown_type','faq-categories')->pluck('id','id')->toArray();
		$faqCategoryList	=	implode(',',$faqCategoryList);
		
		$faqDetail		=	DB::select( DB::raw("SELECT question,answer,id,category_id, (SELECT name FROM dropdown_manager_descriptions WHERE parent_id = (SELECT id FROM dropdown_managers WHERE id = faq_descriptions.category_id) AND language_id = (select id from languages WHERE languages.lang_code = '$lang')) as category_name FROM faq_descriptions WHERE language_id = (select id from languages WHERE languages.lang_code = '$lang') AND parent_id IN ($faqCategoryList) order by category_id ASC "));
		 
		if(empty($faqDetail)){
			return Redirect::to('/');
		} 
		$result	=	array();
		
	  
		foreach($faqDetail as $faq){
			$question											=	$faq->question;
			$answer												=	$faq->answer;
			$id													=	$faq->id;
			$category_id										=	$faq->category_id;
			
			$result[$category_id]['category_id']				=	$faq->category_id;
			$result[$category_id]['category_name']				=	$faq->category_name;
			$result[$category_id]['data'][$id]['question']		=	$question;
			$result[$category_id]['data'][$id]['answer']		=	$answer;
		}
		
		$faqCategoryResult	=	array();
		foreach($faqCategories as $faqCategory){
			$faqCategoryID							=	$faqCategory->parent_id;
			$name									=	$faqCategory->name;
			$faqCategoryResult[$faqCategoryID]		=	$name;
		}
	  
	   
		return View::make('front.cms.faq' , compact('faqCategoryResult','result','systemImage'));
	}// end faq()
/** 
 * Function to projectLibrary
 *
 * @param null
 * 
 * @return view page
 */	
	public function projectLibrary(){
		$DB 			= 	ProjectLibrary::query();	
		$libraryData	=	$DB->with("project_folder")->with("project_sub_folder")->with("project_articles")->get();
		return View::make('front.cms.project_library' , compact('libraryData'));
	}// end projectLibrary()
	
	
/** 
 * Function to projectLibraryArticles
 *
 * @param null
 * 
 * @return view page
 */	
	public function projectLibraryArticles(){
		$article_id		=	Input::get('article_id');	
		$articleData	=	DB::table("project_folder_articals")->where("id",$article_id)->first();
		return View::make('front.cms.project_library_articles' , compact('articleData'));
	}// end projectLibraryArticles()
	
	public function my_stage(){
		return View::make("front.cms.my_stage");
	}
}// end UsersController class
