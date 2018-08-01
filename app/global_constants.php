<?php
/* Global constants for site */
define('FFMPEG_CONVERT_COMMAND', '');
define('DEV_APP', 'dev');

define("ADMIN_FOLDER", "admin/");
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', base_path());
define('APP_PATH', app_path());


define("IMAGE_CONVERT_COMMAND", ""); 
define('WEBSITE_URL', url('/').'/');
define('WEBSITE_JS_URL', WEBSITE_URL . 'js/');
define('WEBSITE_CSS_URL', WEBSITE_URL . 'css/');
define('WEBSITE_IMG_URL', WEBSITE_URL . 'img/');
define('WEBSITE_LOGO_URL', WEBSITE_URL . 'img/logo.png');
define('WEBSITE_UPLOADS_ROOT_PATH', ROOT . DS . 'uploads' .DS );
define('WEBSITE_UPLOADS_URL', WEBSITE_URL . 'uploads/');
define('FRONT_VIEW_URL', WEBSITE_URL . 'resources/views/front/');

define('WEBSITE_ADMIN_URL', WEBSITE_URL.ADMIN_FOLDER );
define('WEBSITE_ADMIN_IMG_URL', WEBSITE_ADMIN_URL . 'img/');
define('WEBSITE_ADMIN_JS_URL', WEBSITE_ADMIN_URL . 'js/');
define('WEBSITE_ADMIN_FONT_URL', WEBSITE_ADMIN_URL . 'fonts/');
define('WEBSITE_ADMIN_CSS_URL', WEBSITE_ADMIN_URL . 'css/');

define('SETTING_FILE_PATH', APP_PATH . DS . 'settings.php');
define('MENU_FILE_PATH', APP_PATH . DS . 'menus.php');

define('CK_EDITOR_URL', WEBSITE_UPLOADS_URL . 'ckeditor_images/');
define('CK_EDITOR_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH . 'ckeditor_images' . DS);


define('SLIDER_URL', WEBSITE_UPLOADS_URL . 'slider/');
define('SLIDER_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'slider' . DS); 


define('BLOCK_URL', WEBSITE_UPLOADS_URL . 'block/');
define('BLOCK_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'block' . DS); 

define('BLOG_IMG_URL', WEBSITE_UPLOADS_URL . 'blog_images/');
define('BLOG_IMG_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'blog_images' . DS); 

define('TESTIMONIAL_URL', WEBSITE_UPLOADS_URL . 'testimonial_images/');
define('TESTIMONIAL_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'testimonial_images' . DS); 

define('HOWITWORK_URL', WEBSITE_UPLOADS_URL . 'how_it_works_images/');
define('HOWITWORK_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'how_it_works_images' . DS); 


define('BLOG_IMAGE_URL', WEBSITE_UPLOADS_URL . 'blog/');
define('BLOG_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'blog' . DS); 

define('USER_PROFILE_IMAGE_URL', WEBSITE_UPLOADS_URL . 'user_profile/');
define('USER_PROFILE_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'user_profile' . DS);

define('TASK_IMAGE_URL', WEBSITE_UPLOADS_URL . 'task/');
define('TASK_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'task' . DS);

define('MASTERS_IMAGE_URL', WEBSITE_UPLOADS_URL . 'masters/');
define('MASTERS_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'masters' . DS); 

define('POST_IMAGE_URL', WEBSITE_UPLOADS_URL . 'posts/');
define('POST_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'posts' . DS); 

define('QUESTION_IMAGE_URL', WEBSITE_UPLOADS_URL . 'questions/');
define('QUESTION_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'questions' . DS);

define('PRIZE_IMAGE_URL', WEBSITE_UPLOADS_URL . 'prize_images/');
define('PRIZE_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'prize_images' . DS);

define('PROJECT_ARTICLE_IMAGE_URL', WEBSITE_UPLOADS_URL . 'project_article/');
define('PROJECT_ARTICLE_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'project_article' . DS);

define('CHALLENGE_IMAGE_URL', WEBSITE_UPLOADS_URL . 'challenge/');
define('CHALLENGE_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'challenge' . DS);

define('HOME_CONTENT_IMAGE_URL', WEBSITE_UPLOADS_URL . 'home_content/');
define('HOME_CONTENT_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'home_content' . DS);

define('PROJECT_FOLDER_IMAGE_URL', WEBSITE_UPLOADS_URL . 'project_folder/');
define('PROJECT_FOLDER_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'project_folder' . DS);

define('TUTORIAL_IMAGE_URL', WEBSITE_UPLOADS_URL . 'tutorials/');
define('TUTORIAL_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'tutorials' . DS);

define('PRODUCTS_IMAGE_URL', WEBSITE_UPLOADS_URL . 'products/');
define('PRODUCTS_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'products' . DS); 

/**  System document url path **/
if (!defined('SYSTEM_IMAGE_URL')) {
    define('SYSTEM_IMAGE_URL', WEBSITE_UPLOADS_URL . 'system_images/');
}

/**  System document upload directory path **/
if (!defined('SYSTEM_IMAGE_DIRECTROY_PATH')){
    define('SYSTEM_IMAGE_DIRECTROY_PATH', WEBSITE_UPLOADS_ROOT_PATH . 'system_images' . DS);
}

$config	=	array();

define('ALLOWED_TAGS_XSS', '<a><strong><b><p><br><i><font><img><h1><h2><h3><h4><h5><h6><span><div><em><table><ul><li><section><thead><tbody><tr><td>');

define('ADMIN_ID', 1);
define('SUPER_ADMIN_ROLE_ID', 1);
define('FRONT_USER_ROLE_ID', 2);
define('DANCER_ROLE_ID', 3);
define('PARENT_ROLE_ID', 4);
define('STUDIO_ROLE_ID', 5);
define('FAN_ROLE_ID', 6);
define('SUB_ADMIN_ROLE_ID', 7);


Config::set("Site.currency", "$");
Config::set("Site.currencyCode", "USD");

Config::set("Site.defaultSponsor", "CMeShine");

Config::set('defaultLanguage', 'English');
Config::set('defaultLanguageCode', 'en');


Config::set('default_language.message', 'All the fields in English language are mandatory.');

Config::set('newsletter_template_constant',array('USER_NAME'=>'USER_NAME','TO_EMAIL'=>'TO_EMAIL','WEBSITE_URL'=>'WEBSITE_URL','UNSUBSCRIBE_LINK'=>'UNSUBSCRIBE_LINK'));

Config::set('user_type_list',array(PARENT_ROLE_ID=>'Parent',DANCER_ROLE_ID=>'Dancer',FAN_ROLE_ID=>'Fan',STUDIO_ROLE_ID=>'Studio'));

//////////////// extension 

define('IMAGE_EXTENSION','jpeg,jpg,png,gif,bmp');
define('PDF_EXTENSION','pdf');
define('DOC_EXTENSION','doc,xls');
define('VIDEO_EXTENSION','mpeg,avi,mp4,webm,flv,3gp,m4v,mkv,mov,moov');


define('DANCER_PAGE_IMAGE_ID', 2);
define('DANCER_LOGO_IMAGE_ID', 3);
define('SECTION_3_IMAGE_1_IMAGE_ID', 4);
define('SECTION_3_IMAGE_2_IMAGE_ID', 5);
define('SECTION_3_IMAGE_3_IMAGE_ID', 6);
define('SECTION_3_IMAGE_4_IMAGE_ID', 7);
define('SECTION_3_IMAGE_5_IMAGE_ID', 8);
define('ATTACHMENT_IMAGE_1', 9);
define('ATTACHMENT_IMAGE_2', 10);
define('ATTACHMENT_IMAGE_3', 11);
define('ATTACHMENT_IMAGE_4', 12);
define('ATTACHMENT_IMAGE_5', 13);
define('LOGO_IMAGE_ID', 14);
define('BG_1_IMAGE_ID', 15);
define('BG_2_IMAGE_ID', 16);
define('BG_3_IMAGE_ID', 17);
define('BG_4_IMAGE_ID', 18);
define('BG_5_IMAGE_ID', 19);
define('CMS_PAGE_IMAGE_ID', 21);
define('CONTACT_PAGE_IMAGE_ID', 24);
define('DASHBOARD_PAGE_IMAGE_ID', 25);
define('TEXT_ADMIN_ID',1);
define('TEXT_FRONT_USER_ID',2);
define('FRONT_USER',2);
define('IS_ACTIVE',1);

/**  Active Inactive global constant **/
define('ACTIVE',1);
define('INACTIVE',0);


define('USER_TYPE_PAGE','user_type_page');
define('DOB_PAGE','dob_page');
define('DANCER_PARENT_DETAIL_PAGE','dancer_parent_detail_page');
define('DANCER_DETAIL_PAGE','dancer_detail_page');
define('DANCER_ADDITIONAL_DETAIL_PAGE','dancer_additional_detail_page');
define('PARENT_DETAIL_PAGE','parent_detail_page');
define('PARENT_ADDITIONAL_DETAIL_PAGE','parent_additional_detail_page');
define('TEACHER_DETAIL_PAGE','teacher_detail_page');
define('FAN_PARENT_DETAIL_PAGE','fan_parent_detail_page');
define('FAN_DETAIL_PAGE','fan_detail_page');

define('IMAGE_INFO', '<div class="mws-form-message info">
	<a class="close pull-right" href="javascript:void(0);">&times;</a>
	<ul style="padding-left:12px">
		<li>Allowed file types are gif, jpeg, png, jpg.</li>
		<li>Large files may take some time to upload so please be patient and do not hit reload or your back button</li>
	</ul>
</div>');


Config::set('default_language.folder_code', 'eng');
Config::set('default_language.language_code', '1');
Config::set('default_language.name', 'English');


define('CBC_ENCRYPT_KEY', 'qJB0rGtIn5UB1xG03efyCpOskLsdIeoY=');
define('CBC_ENCRYPT_IV', '4e5Wa71fYoT7MFEX');


Config::set('no_of_questions',array(
	'5' => '5',
	'10'=> '10',
	'15'=> '15',
	'20'=> '20',
));	
Config::set('challenges_grade',array(
	'1' => '1',
	'2'=> '2',
	'3'=> '3',
	'4'=> '4',
	'5'=> '5',
	'6'=> '6',
	'7'=> '7',
	'8'=> '8',
	'9'=> '9',
	'10'=> '10',
	'11'=> '11',
	'12'=> '12',
));	

Config::set("Site.android_sever_api_key","AAAA-LuQcJk:APA91bE94H-4-rfPq4Jv04I9mVGuQ91mHe2r_mgf55eW3UUSvXhIG3tbn8ZqGE8m4dWtkXcCWr-qsPFhVmBNAulre7yGBSoJ-QbJM6ldwv1VGZev_ebAbErcd_RRL_cjpNiUht4IVk9N");

define("PROCESSING_ORDER",1);							
define("SHIPPED_ORDER",2);							
define("DELIVERED_ORDER",3);							
define("CANCEL_ORDER",4);	

define("CURRENCY",'$');					
define("BLOG_COMMENT",'blog_comment');		


