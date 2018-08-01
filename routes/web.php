<?php
DB::enableQueryLog() ;
include(app_path().'/global_constants.php');
include(app_path().'/settings.php');
require_once(APP_PATH.'/libraries/CustomHelper.php');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
	################################################################# Front Routing start here ###################################################
	Route::get('/base/uploder','BaseController@saveCkeditorImages');
	Route::post('/base/uploder','BaseController@saveCkeditorImages'); 
	Route::get('/', 'UsersController@index');
	Route::group(array('middleware' => 'App\Http\Middleware\GuestFront','namespace'=>'front'), function(){
		Route::get('/', 'UsersController@index');
		Route::get('home-page', 'UsersController@homePageIndex');
		Route::get('/login',array('as'=>'Home.login','uses'=>'UsersController@loginview'));
		Route::get('/signup',array('as'=>'Home.signup','uses'=>'UsersController@signupView'));
		Route::get('/signup-invites/{slug}',array('as'=>'Home.signup-invites','uses'=>'HomeController@signupInvites'));
		Route::post('/save-signup-invitee',array('as'=>'Home.signup_page_invitee','uses'=>'HomeController@saveSignupInvitee'));
		Route::get('/logout', 'UsersController@logout');
		//account related
		Route::get('account-verification/{validate_string}',array('as'=>'Home.verify','uses'=>'UsersController@Verify'));
		Route::get('send-verifylink-again/{validate_string}',array('as'=>'Home.verify','uses'=>'UsersController@sendVerifylinkAgain'));
		Route::post('/login',array('as'=>'Home.login_page','uses'=>'UsersController@login'));
		Route::post('/signup',array('as'=>'User.signup','uses'=>'UsersController@signup'));
		Route::get('/forgot-password',array('as'=>'User.view_forgot_password','uses'=>'UsersController@view_forgot_password'));
		Route::post('/forgot-password',array('as'=>'User.forgot_password','uses'=>'UsersController@forgot_password'));
		Route::get('reset-password/{validate_string}',array('as'=>'Home.reset_password','uses'=>'UsersController@resetPassword'));
		Route::post('reset-password',array('as'=>'Home.save_reset_password','uses'=>'UsersController@saveResetPassword'));
		
		Route::get('change-password/{validate_string}',array('as'=>'Home.changepassword','uses'=>'HomeController@changePassword'));
		Route::post('change-password',array('as'=>'Home.save_change_password','uses'=>'HomeController@saveChangePassword'));
		Route::get('update-email/{validate_string}',array('as'=>'Home.update-email','uses'=>'GlobalusersController@updateEmailId'));
		
		//Faqs
		//Route::get('/faqs',array('as'=>'User.faqs','uses'=>'UsersController@faq'));
		//Route::get('/faqs-inner/{slug}',array('as'=>'Home.faqs-inner','uses'=>'UsersController@faqs_inner'));
		//Route::get('/faqs-view/{slug}',array('as'=>'Home.faqs-view','uses'=>'UsersController@faqs_view'));
		//Route::get('/faqs-search-result',array('as'=>'Home.faqs-search-result','uses'=>'UsersController@faqs_search_result'));
		//Route::get('/about-us',array('as'=>'User.about','uses'=>'PagesController@aboutUs'));
		//Route::get('/privacy-policy',array('as'=>'User.about','uses'=>'PagesController@privacyPolicy'));
		//Route::get('/term-and-conditions',array('as'=>'User.about','uses'=>'PagesController@termAndConditions'));
		Route::post('subscribe',array('as'=>'User.subscribe','uses'=>'UsersController@subscribe'));
		Route::get('/contact-us',array('as'=>'User.contact','uses'=>'PagesController@contactUs'));
		Route::post('/contact-us',array('as'=>'User.contact','uses'=>'PagesController@contactUs'));
		Route::post('/check-email',array('as'=>'User.index','uses'=>'UsersController@findEmail'));
		Route::get('/pages/{slug}','PagesController@showCms');
		Route::get('/my-stage',array('as'=>'Page.mystage','uses'=>'PagesController@my_stage'));
		Route::get('/faq','PagesController@faq');
		
		Route::get('/products',array('as'=>'Product.list','uses'=>'ProductsController@products'));
	//	Route::get('/product/product-detail/{slug}',array('as'=>'Product.productDetail','uses'=>'ProductsController@productDetail'));
		Route::post('product/get-product',array('as'=>'Product.getProduct','uses'=>'ProductsController@getProduct'));
		Route::post('get-size-detail',array('as'=>'Product.productSizeDetail','uses'=>'ProductsController@productSizeDetail'));
		Route::get('/product-detail/{slug}',array('as'=>'Product.detail','uses'=>'ProductsController@productDetail'));
		Route::post('add-to-cart',array('as'=>'Product.product_detail','uses'=>'ProductsController@addToCart'));
		Route::get('/shopping-cart','ProductsController@shoppingCart');
		Route::get('/checkout','ProductsController@checkout');
		
		Route::get('/blog',array('as'=>'Library.index','uses'=>'LibraryController@library'));
		Route::get('/blog-categories',array('as'=>'Library.allFolders','uses'=>'LibraryController@all_folders'));
		Route::get('/blog-categories/{slug}',array('as'=>'Library.folderArticle','uses'=>'LibraryController@folder_articles'));
		Route::any('/search-library',array('as'=>'Library.folderArticleList','uses'=>'LibraryController@searchLibrary'));
		Route::any('/search-library-article',array('as'=>'Library.folderArticleData','uses'=>'LibraryController@searchLibraryArticle'));
		Route::get('/library4/{id}','LibraryController@library4');
		Route::get('/article-detail/{slug}',array('as'=>'Library.articleDetail','uses'=>'LibraryController@article_detail'));
		Route::get('/librarydetail/{id}/{user_id}',array('as'=>'Library.librarydetail','uses'=>'LibraryController@librarydetail'));
		
		Route::post('/comment_detail',array('as'=>'Library.projectArticleComment','uses'=>'LibraryController@projectArticleComment'));
		Route::post('save_comment',array('as'=>'Library.saveComment','uses'=>'LibraryController@saveArticalComment'));
		Route::post('save_comment_reply',array('as'=>'Library.saveCommentReply','uses'=>'LibraryController@saveArticalCommentReply'));

		Route::post('like-unlike-article',array('as'=>'Library.likeUnlikeArticle','uses'=>'LibraryController@likeUnlikeArticle'));
		Route::post('helpful-nothelpful-article',array('as'=>'Library.helpfulNothelpfulArticle','uses'=>'LibraryController@helpfulNothelpfulArticle'));
		Route::get('export-article-to-pdf','LibraryController@ExportArticleToPdf');
		Route::post('like-unlike-article-comment',array('as'=>'Library.likeUnlikeArticleComment','uses'=>'LibraryController@likeUnlikeArticleComment'));
		
		Route::post('save-article-comment',array('as'=>'Library.saveArticleComment','uses'=>'LibraryController@saveArticleComment'));
		Route::post('save-article-comment-reply',array('as'=>'Library.saveArticleCommentReply','uses'=>'LibraryController@saveArticleCommentReply'));
		
	});
	
	Route::group(array('middleware' => 'App\Http\Middleware\GuestFront','namespace'=>'Auth'), function() {
		Route::get('login-with-social/{social_type}','AuthController@redirectToProvider');
		Route::get('handle-provider-callback/{social_type}','AuthController@handleProviderCallback');
		
	});
	
	Route::group(array('middleware' => 'App\Http\Middleware\AuthFront','namespace'=>'front'), function() {
		Route::get('dashboard','GlobalusersController@dashboard');
		Route::get('/edit-profile','DashboardController@editProfile');
		Route::post('/edit-profile',array('as'=>'Dashboard.editProfile','uses'=>'DashboardController@updateProfile'));
		Route::get('change-password',array('as'=>'Dashboard.changePassword','uses'=>'DashboardController@changePassword'));
		Route::post('savechangepassword',array('as'=>'Dashboard.saveChangePassword','uses'=>'DashboardController@saveChangePassword'));
		
		Route::get('/leaderboard',array('as'=>'Contact.leaderboard','uses'=>'HomeController@leaderboard'));
		Route::get('leaderboard/index',array('as'=>'Contact.leaderboard','uses'=>'HomeController@index'));
		Route::get('leaderboard/get-data-leaderboard',array('as'=>'Contact.leaderboard','uses'=>'HomeController@getDataleaderboard'));
		Route::post('leaderboard/add-data-leaderboard',array('as'=>'Contact.leaderboard','uses'=>'HomeController@addData'));
		Route::get('leaderboard/home',array('as'=>'Contact.leaderboard','uses'=>'HomeController@home'));
		Route::get('leaderboard/Upcoming',array('as'=>'Contact.leaderboard','uses'=>'HomeController@Upcoming'));
		Route::get('leaderboard/Players',array('as'=>'Contact.leaderboard','uses'=>'HomeController@Players'));
		Route::get('leaderboard/MatchDetails',array('as'=>'Contact.leaderboard','uses'=>'HomeController@MatchDetails'));
		Route::get('leaderboard/PlayersDetails/{id}',array('as'=>'Contact.leaderboard','uses'=>'HomeController@PlayersDetails'));
		Route::get('leaderboard/Old',array('as'=>'Contact.leaderboard','uses'=>'HomeController@Old'));
		Route::get('leaderboard/Gallery',array('as'=>'Contact.leaderboard','uses'=>'HomeController@Gallery'));

		//  Route::get('/library',array('as'=>'User.library','uses'=>'LibraryController@library1'));
		//	Route::get('/library/{project}/{main_folder}/{sub_folder}/{article}',array('as'=>'User.library','uses'=>'LibraryController@projectLibrary'));
		
	});
	#############Front Routing start here #######

	##### Admin Routing start here ###################
	Route::group(array('prefix' => 'cmeshinepanel'), function(){
		Route::group(array('middleware' => 'App\Http\Middleware\GuestAdmin','namespace'=>'admin'), function() {
			Route::get('',array('as'=>'login.index','uses'=>'AdminLoginController@login'));
			Route::any('/login',array('as'=>'login.index','uses'=>'AdminLoginController@login'));
			Route::get('forget_password',array('as'=>'login.forgetPassword','uses'=>'AdminLoginController@forgetPassword'));
			Route::get('reset_password/{validstring}',array('as'=>'login.resetPassword','uses'=>'AdminLoginController@resetPassword'));
			Route::post('send_password',array('as'=>'login.sendPassword','uses'=>'AdminLoginController@sendPassword'));
			Route::post('save_password',array('as'=>'login.resetPasswordSave','uses'=>'AdminLoginController@resetPasswordSave'));
		});
		
		Route::group(array('middleware' => 'App\Http\Middleware\AuthAdmin','namespace'=>'admin'), function() {
			Route::get('/logout',array('as'=>'home.logout','uses'=>'AdminLoginController@logout'));
			Route::get('dashboard',array('as'=>'dashboard.showdashboard','uses'=>'AdminDashBoardController@showdashboard'));
			Route::get('/myaccount',array('as'=>'dashboard.myaccount','uses'=>'AdminDashBoardController@myaccount'));
			Route::post('/myaccount',array('as'=>'dashboard.myaccountUpdate','uses'=>'AdminDashBoardController@myaccountUpdate'));
			
			Route::get('/change-password',array('as'=>'dashboard.changePassword','uses'=>'AdminDashBoardController@change_password'));
			Route::post('/changed-password',array('as'=>'dashboard.changedPassword','uses'=>'AdminDashBoardController@changedPassword'));
			
			Route::get('/notifications',array('as'=>'dashboard.notifications','uses'=>'AdminDashboardController@notifications'));
			Route::post('/get-notification','AdminDashboardController@getNotifications');
			Route::post('/get-all-notification','AdminDashboardController@getAllNotifications');
			
			/** settings routing**/
			Route::any('/settings',array('as'=>'settings.listSetting','uses'=>'SettingsController@listSetting'));
			Route::get('/settings/add-setting',array('as'=>'settings.add','uses'=>'SettingsController@addSetting'));
			Route::post('/settings/add-setting',array('as'=>'settings.add','uses'=>'SettingsController@saveSetting'));
			Route::get('/settings/edit-setting/{id}',array('as'=>'settings.edit','uses'=>'SettingsController@editSetting'));
			Route::post('/settings/edit-setting/{id}',array('as'=>'settings.edit','uses'=>'SettingsController@updateSetting'));
			Route::get('/settings/prefix/{slug}',array('as'=>'settings.prefix','uses'=>'SettingsController@prefix'));
			Route::post('/settings/prefix/{slug}',array('as'=>'settings.prefix','uses'=>'SettingsController@updatePrefix'));
			Route::get('/settings/delete-setting/{id}',array('as'=>'settings.delete','uses'=>'SettingsController@deleteSetting'));
			/** settings routing**/
			
			/** cms-manager routing**/
			Route::any('/cms-manager',array('as'=>'Cms.index','uses'=>'CmspagesController@listCms'));
			Route::get('cms-manager/add-cms',array('as'=>'Cms.add','uses'=>'CmspagesController@addCms'));
			Route::post('cms-manager/add-cms',array('as'=>'Cms.add','uses'=>'CmspagesController@saveCms'));
			Route::get('cms-manager/edit-cms/{id}',array('as'=>'Cms.edit','uses'=>'CmspagesController@editCms'));
			Route::post('cms-manager/edit-cms/{id}',array('as'=>'Cms.edit','uses'=>'CmspagesController@updateCms'));
			Route::any('cms-manager/delete-cms/{id}',array('as'=>'Cms.delete','uses'=>'CmspagesController@deleteCms'));
			Route::get('cms-manager/update-status/{id}/{status}',array('as'=>'Cms.updateStatus','uses'=>'CmspagesController@updateCmsStatus'));
			/** cms-manager routing**/
			
			
			/** email-manager routing**/
			Route::get('/email-manager',array('as'=>'EmailTemplate.index','uses'=>'EmailtemplateController@listTemplate'));
			Route::get('/email-manager/add-template',array('as'=>'EmailTemplate.add','uses'=>'EmailtemplateController@addTemplate'));
			Route::post('/email-manager/add-template',array('as'=>'EmailTemplate.add','uses'=>'EmailtemplateController@saveTemplate'));
			Route::get('/email-manager/edit-template/{id}',array('as'=>'EmailTemplate.edit','uses'=>'EmailtemplateController@editTemplate'));
			Route::post('/email-manager/edit-template/{id}',array('as'=>'EmailTemplate.edit','uses'=>'EmailtemplateController@updateTemplate'));
			Route::post('/email-manager/get-constant',array('as'=>'EmailTemplate.getConstant','uses'=>'EmailtemplateController@getConstant'));
			
			### Email Logs Manager routing
			Route::get('/email-logs',array('as'=>'EmailLogs.listEmail','uses'=>'EmailLogsController@listEmail'));
			Route::any('/email-logs/email_details/{id}',array('as'=>'EmailLogs.popup','uses'=>'EmailLogsController@EmailDetail'));
			/** email-manager routing**/
			
			
			/** Dropdown manager  module  routing start here **/
			Route::get('dropdown-manager/add-dropdown/{type}',array('as'=>'DropDown.add','uses'=>'DropDownController@addDropDown'));
			Route::post('dropdown-manager/add-dropdown/{type}',array('as'=>'DropDown.add','uses'=>'DropDownController@saveDropDown'));
			Route::get('dropdown-manager/edit-dropdown/{id}/{type}',array('as'=>'DropDown.edit','uses'=>'DropDownController@editDropDown'));
			Route::post('dropdown-manager/edit-dropdown/{id}/{type}',array('as'=>'DropDown.edit','uses'=>'DropDownController@updateDropDown'));
			Route::get('dropdown-manager/update-dropdown/{id}/{status}/{type}',array('as'=>'DropDown.status','uses'=>'DropDownController@updateDropDownStatus'));
			Route::get('dropdown-manager/delete-dropdown/{id}/{type}',array('as'=>'DropDown.delete','uses'=>'DropDownController@deleteDropDown'));
			Route::delete('dropdown-manager/delete-dropdown/{id}/{type}',array('as'=>'DropDown.delete','uses'=>'DropDownController@deleteDropDown'));
			Route::get('/dropdown-manager/{type}',array('as'=>'DropDown.listDropDown','uses'=>'DropDownController@listDropDown'));
			Route::get('/dropdown-manager/{type}/{isimage}',array('as'=>'DropDown.listDropDown','uses'=>'DropDownController@listDropDown'));
			Route::post('/dropdown-manager/{type}',array('as'=>'DropDown.listDropDown','uses'=>'DropDownController@listDropDown'));
			/** Dropdown manager  module  routing start here **/
			
			
			##Block manager  module  routing start here
			Route::get('/block-manager',array('as'=>'Block.index','uses'=>'BlockController@listBlock'));
			Route::get('block-manager/add-block',array('as'=>'Block.add','uses'=>'BlockController@addBlock'));
			Route::post('block-manager/add-block',array('as'=>'Block.save','uses'=>'BlockController@saveBlock'));
			Route::get('block-manager/edit-block/{id}',array('as'=>'Block.edit','uses'=>'BlockController@editBlock'));
			Route::post('block-manager/edit-block/{id}',array('as'=>'Block.edit','uses'=>'BlockController@updateBlock'));
			Route::get('block-manager/update-status/{id}/{status}',array('as'=>'Block.status','uses'=>'BlockController@updateBlockStatus'));
			Route::any('block-manager/delete-block/{id}',array('as'=>'Block.delete','uses'=>'BlockController@deleteBlock'));		
			Route::post('block-manager/multiple-action',array('as'=>'Block.Multipleaction','uses'=>'BlockController@performMultipleAction'));
			
			Route::any('block-manager/change_order',array('as'=>'Block.changeOrder','uses'=>'BlockController@changeBlockOrder'));
			##Block manager  module  routing end here
			
			### contact manager routing
			Route::any('/contact-manager',array('as'=>'Contact.index','uses'=>'ContactsController@listContact'));
			Route::get('contact-manager/view-contact/{id}',array('as'=>'Contact.view','uses'=>'ContactsController@viewContact'));
			Route::delete('contact-manager/delete-contact/{id}',array('as'=>'Contact.delete','uses'=>'ContactsController@deleteContact'));
			Route::any('/contact-manager/reply-to-user/{id}',array('as'=>'Contact.reply','uses'=>'ContactsController@replyToUser'));
			### contact manager routing
			
			###faq  module  routing
			Route::get('/faqs-manager',array('as'=>'Faq.listFaq','uses'=>'FaqsController@listFaq'));
			Route::post('/faqs-manager',array('as'=>'Faq.add','uses'=>'FaqsController@listFaq'));
			Route::get('faqs-manager/add-faqs',array('as'=>'Faq.add','uses'=>'FaqsController@addFaq'));
			Route::post('faqs-manager/add-faqs',array('as'=>'Faq.add','uses'=>'FaqsController@saveFaq'));
			Route::get('faqs-manager/edit-faqs/{id}',array('as'=>'Faq.edit','uses'=>'FaqsController@editFaq'));
			Route::post('faqs-manager/edit-faqs/{id}',array('as'=>'Faq.edit','uses'=>'FaqsController@updateFaq'));
			Route::get('faqs-manager/update-status/{id}/{status}',array('as'=>'Faq.status','uses'=>'FaqsController@updateFaqStatus'));
			Route::any('faqs-manager/delete-faqs/{id}',array('as'=>'Faq.delete','uses'=>'FaqsController@deleteFaq'));
			Route::get('faqs-manager/view-faqs/{id}',array('as'=>'Faq.view','uses'=>'FaqsController@viewFaq'));
			Route::post('faqs-manager/multiple-action',array('as'=>'Faq.action','uses'=>'FaqsController@performMultipleAction'));
			###faq  module  routing
			
			##System Doc routing start here
			Route::get('/system-doc-manager',array('as'=>'SystemDoc.index','uses'=>'SystemDocController@listDoc'));
			Route::post('/system-doc-manager',array('as'=>'SystemDoc.index','uses'=>'SystemDocController@listDoc'));
			Route::get('system-doc-manager/add-doc',array('as'=>'SystemDoc.add','uses'=>'SystemDocController@addDoc'));
			Route::post('system-doc-manager/add-doc',array('as'=>'SystemDoc.add','uses'=>'SystemDocController@saveDoc'));
			Route::get('system-doc-manager/edit-doc/{id}',array('as'=>'SystemDoc.edit','uses'=>'SystemDocController@editDoc'));
			Route::post('system-doc-manager/edit-doc/{id}',array('as'=>'SystemDoc.edit','uses'=>'SystemDocController@updateDoc'));
			Route::get('system-doc-manager/update-status/{id}/{status}',array('as'=>'SystemDoc.status','uses'=>'SystemDocController@updateDocStatus'));
			Route::any('system-doc-manager/delete-doc/{id}',array('as'=>'SystemDoc.delete','uses'=>'SystemDocController@deleteDoc'));		
			Route::post('system-doc-manager/multiple-action',array('as'=>'SystemDoc.action','uses'=>'SystemDocController@performMultipleAction'));
			##System Doc routing end here
			
			##Seo routing start here
			Route::get('/no-cms-manager',array('as'=>'NoCms.index','uses'=>'NoCmsController@listDoc'));
			Route::post('/no-cms-manager',array('as'=>'NoCms.index','uses'=>'NoCmsController@listDoc'));
			Route::get('no-cms-manager/add-doc',array('as'=>'NoCms.add','uses'=>'NoCmsController@addDoc'));
			Route::post('no-cms-manager/add-doc',array('as'=>'NoCms.add','uses'=>'NoCmsController@saveDoc'));
			Route::get('no-cms-manager/edit-doc/{id}',array('as'=>'NoCms.edit','uses'=>'NoCmsController@editDoc'));
			Route::post('no-cms-manager/edit-doc/{id}',array('as'=>'NoCms.edit','uses'=>'NoCmsController@updateDoc'));
			Route::get('no-cms-manager/update-status/{id}/{status}',array('as'=>'NoCms.status','uses'=>'NoCmsController@updateDocStatus'));
			Route::any('no-cms-manager/delete-doc/{id}',array('as'=>'NoCms.delete','uses'=>'NoCmsController@deleteDoc'));		
			Route::post('no-cms-manager/multiple-action',array('as'=>'NoCms.action','uses'=>'NoCmsController@performMultipleAction'));
			##Seo routing end here
			
			###slider manager routing
			Route::get('/slider-manager',array('as'=>'Slider.index','uses'=>'SlidersController@listSlider'));
			Route::get('slider-manager/add-slider',array('as'=>'Slider.add','uses'=>'SlidersController@addSlider'));
			Route::post('slider-manager/add-slider',array('as'=>'Slider.save','uses'=>'SlidersController@saveSlider'));
			Route::get('slider-manager/edit-slider/{id}',array('as'=>'Slider.edit','uses'=>'SlidersController@editSlider'));
			Route::post('slider-manager/edit-slider',array('as'=>'Slider.update','uses'=>'SlidersController@updateSlider'));
			Route::any('slider-manager/delete-slider/{id}',array('as'=>'Slider.delete','uses'=>'SlidersController@deleteSlider'));
			Route::get('slider-manager/update-status/{id}/{status}',array('as'=>'Slider.status','uses'=>'SlidersController@updateSliderStatus'));
			Route::any('slider-manager/change_order',array('as'=>'Slider.change_order','uses'=>'SlidersController@changeSliderOrder'));
			Route::post('slider-manager/multiple-action',array('as'=>'Slider.Multipleaction','uses'=>'SlidersController@performMultipleAction'));
			###slider manager routing
			
			# users routing start here //
			Route::get('/users',array('as'=>'User.index','uses'=>'UsersController@listUsers'));
			Route::get('users/view-user/{id}',array('as'=>'User.view','uses'=>'UsersController@viewUser'));
			Route::get('users/update-status/{id}/{status}',array('as'=>'User.status','uses'=>'UsersController@updateUserStatus'));
			Route::any('users/delete-user/{id}',array('as'=>'User.delete','uses'=>'UsersController@deleteUser'));
			Route::get('users/verify-user/{id}',array('as'=>'User.verifiedUser','uses'=>'UsersController@verifiedUser'));
			Route::get('users/add-user',array('as'=>'User.add','uses'=>'UsersController@addUser'));
			Route::post('users/get-user-add-data',array('as'=>'User.getUserAddData','uses'=>'UsersController@getUserAddData'));
			Route::post('users/get-user-edit-data',array('as'=>'User.getUserEditData','uses'=>'UsersController@getUserEditData'));
			Route::post('users/add-user',array('as'=>'User.add','uses'=>'UsersController@saveUser'));	
			Route::get('users/edit-user/{id}',array('as'=>'User.edit','uses'=>'UsersController@editUser'));
			Route::post('users/edit-user',array('as'=>'User.update','uses'=>'UsersController@updateUser'));	
			Route::any('users/send-credential/{id}',array('as'=>'User.sendCredential','uses'=>'UsersController@sendCredential'));	
			Route::any('users/send-profile-verify/{id}',array('as'=>'User.sendProfileVerify','uses'=>'UsersController@sendProfileVerify'));
			Route::post('users/add-more-dancer',array('as'=>'User.addmoreDancer','uses'=>'UsersController@addmoreDancer'));	
			Route::post('users/remove-dancer',array('as'=>'User.removeDancer','uses'=>'UsersController@removeDancer'));	
			Route::post('users/deactivate-user',array('as'=>'User.deactivateUsers','uses'=>'UsersController@deactivateUsers'));	
			/** change password**/
			Route::get('users/change-password/{id}',array('as'=>'User.changePassword','uses'=>'UsersController@ChangePassword'));
			Route::post('users/change-password/{id}',array('as'=>'User.changePassword','uses'=>'UsersController@ChangedPassword'));
			# users routing start here //

			## sub admin routing start here 
			Route::get('/sub-admin',array('as'=>'SubAdmin.index','uses'=>'SubAdminUsersController@listUsers'));
			Route::get('sub-admin/add-user',array('as'=>'SubAdmin.add','uses'=>'SubAdminUsersController@addUser'));
			Route::post('sub-admin/add-user',array('as'=>'SubAdmin.add','uses'=>'SubAdminUsersController@saveUser'));	
			Route::get('sub-admin/edit-user/{id}',array('as'=>'SubAdmin.edit','uses'=>'SubAdminUsersController@editUser'));
			Route::post('sub-admin/edit-user',array('as'=>'SubAdmin.update','uses'=>'SubAdminUsersController@updateUser'));	
			Route::get('sub-admin/update-status/{id}/{status}',array('as'=>'SubAdmin.status','uses'=>'SubAdminUsersController@updateUserStatus'));
			Route::any('sub-admin/delete-user/{id}',array('as'=>'SubAdmin.delete','uses'=>'SubAdminUsersController@deleteUser'));
			## sub admin routing end here 

			//Get State
			Route::post('get-state-list',array('as'=>'User.getStateList','uses'=>'UsersController@getStateList'));
			Route::any('get-city-list',array('as'=>'User.getCityList','uses'=>'UsersController@getCityList'));
			
			Route::post('get-league-state-list',array('as'=>'User.getLeagueStateList','uses'=>'UsersController@getLeagueStateList'));
			Route::any('get-league-city-list',array('as'=>'User.getLeagueCityList','uses'=>'UsersController@getLeagueCityList'));
			
			Route::post('get-studio-state-list',array('as'=>'User.getStudioStateList','uses'=>'UsersController@getStudioStateList'));
			Route::any('get-studio-city-list',array('as'=>'User.getStudioCityList','uses'=>'UsersController@getStudioCityList'));
			
			// language routing
			Route::get('language',array('as'=>'Language.index','uses'=>'LanguageController@listLanguage'));
			Route::get('language/add-language',array('as'=>'Language.add','uses'=>'LanguageController@addLanguage'));
			Route::post('language/save-language',array('as'=>'Language.save','uses'=>'LanguageController@saveLanguage'));
			Route::any('language/delete-language/{id}',array('as'=>'Language.delete','uses'=>'LanguageController@deleteLanguage'));
			Route::get('language/update-status/{id}/{status}',array('as'=>'Language.status','uses'=>'LanguageController@updateLanguageStatus'));
			Route::any('language/default/{id}/{langCode}/{folderCode}',array('as'=>'Language.update_default','uses'=>'LanguageController@updateDefaultLanguage'));
			Route::any('language/multiple-action',array('as'=>'Language.Multipleaction','uses'=>'LanguageController@performMultipleAction'));
			
			
			### Language setting start //
			Route::get('/language-settings',array('as'=>'LanguageSetting.index','uses'=>'LanguageSettingsController@listLanguageSetting'));
			Route::get('/language-settings/add-setting',array('as'=>'LanguageSetting.add','uses'=>'LanguageSettingsController@addLanguageSetting'));
			Route::post('/language-settings/add-setting',array('as'=>'LanguageSetting.save','uses'=>'LanguageSettingsController@saveLanguageSetting'));
			Route::get('/language-settings/edit-setting/{id}',array('as'=>'LanguageSetting.edit','uses'=>'LanguageSettingsController@editLanguageSetting'));
			Route::post('/language-settings/edit-setting/{id}',array('as'=>'LanguageSetting.update','uses'=>'LanguageSettingsController@updateLanguageSetting'));		
			
			
			##blog manager  module  routing start here
			Route::get('/blog-manager',array('as'=>'Blog.index','uses'=>'BlogController@listBlog'));
			Route::get('blog-manager/add-blog',array('as'=>'Blog.add','uses'=>'BlogController@addBlog'));
			Route::post('blog-manager/add-blog',array('as'=>'Blog.save','uses'=>'BlogController@saveBlog'));
			Route::get('blog-manager/edit-blog/{id}',array('as'=>'Blog.edit','uses'=>'BlogController@editBlog'));
			Route::post('blog-manager/edit-blog/{id}',array('as'=>'Blog.update','uses'=>'BlogController@updateBlog'));
			Route::get('blog-manager/update-status/{id}/{status}',array('as'=>'Blog.status','uses'=>'BlogController@updateBlogStatus'));
			Route::any('blog-manager/delete-blog/{id}',array('as'=>'Blog.delete','uses'=>'BlogController@deleteBlog'));		
			Route::post('blog-manager/multiple-action',array('as'=>'Blog.Multipleaction','uses'=>'BlogController@performMultipleAction'));
			
			Route::any('blog-manager/change_order',array('as'=>'Blog.change_order','uses'=>'BlogController@changeBlogOrder'));
			##blog manager  module  routing end here
			
			##Testimonial manager routing
			Route::any('/testimonial-manager',array('as'=>'Testimonial.index','uses'=>'TestimonialController@listTestimonial'));
			Route::get('testimonial-manager/add-testimonial',array('as'=>'Testimonial.add','uses'=>'TestimonialController@addTestimonial'));
			Route::post('testimonial-manager/add-testimonial',array('as'=>'Testimonial.save','uses'=>'TestimonialController@saveTestimonial'));
			Route::get('testimonial-manager/edit-testimonial/{id}',array('as'=>'Testimonial.edit','uses'=>'TestimonialController@editTestimonial'));
			Route::post('testimonial-manager/edit-testimonial/{id}',array('as'=>'Testimonial.update','uses'=>'TestimonialController@updateTestimonial'));
			Route::get('testimonial-manager/update-status/{id}/{status}',array('as'=>'Testimonial.status','uses'=>'TestimonialController@updateTestimonialStatus'));
			Route::get('testimonial-manager/delete-testimonial/{id}',array('as'=>'Testimonial.delete','uses'=>'TestimonialController@deleteTestimonial'));
			Route::delete('testimonial-manager/delete-testimonial/{id}',array('as'=>'Testimonial.delete','TestimonialController@deleteTestimonial'));
			Route::get('testimonial-manager/mark-highlight/{id}/{status}','TestimonialController@markHighlight');
			Route::any('testimonial-manager/change_order',array('as'=>'Testimonial.change_order','uses'=>'TestimonialController@changeBlockOrder'));
			##Testimonial manager routing
			/*
			### project library manager routing
			Route::get('/project-library-manager',array('as'=>'ProjectLibrary.index','uses'=>'ProjectLibrariesController@listProjectLibrary'));
			Route::get('project-library-manager/add-project-library',array('as'=>'ProjectLibrary.add','uses'=>'ProjectLibrariesController@addProjectLibrary'));
			Route::post('project-library-manager/add-project-library',array('as'=>'ProjectLibrary.save','uses'=>'ProjectLibrariesController@saveProjectLibrary'));
			Route::get('/project-library-manager/edit-project-library/{id}',array('as'=>'ProjectLibrary.edit','uses'=>'ProjectLibrariesController@editProjectLibrary'));
			Route::post('/project-library-manager/update-project-library/{id}',array('as'=>'ProjectLibrary.update','uses'=>'ProjectLibrariesController@updateProjectLibrary'));
			Route::get('project-library-manager/view-project-library/{id}',array('as'=>'ProjectLibrary.view','uses'=>'ProjectLibrariesController@viewProjectLibrary'));
			Route::delete('project-library-manager/delete-project-library/{id}',array('as'=>'ProjectLibrary.delete','uses'=>'ProjectLibrariesController@deleteProjectLibrary'));
			Route::get('project-library-manager/update-status/{id}/{status}',array('as'=>'ProjectLibrary.status','uses'=>'ProjectLibrariesController@updateProjectLibraryStatus'));
			### project library manager routing
			
			### project Folder manager routing
			Route::any('/project-folder-manager/{Pid}',array('as'=>'ProjectFolder.index','uses'=>'ProjectFoldersController@listProjectFolder'));
			Route::get('/project-folder-manager/add-folder/{Pid}',array('as'=>'ProjectFolder.add','uses'=>'ProjectFoldersController@addProjectFolder'));
			Route::post('/project-folder-manager/save-folder/{Pid}',array('as'=>'ProjectFolder.save','uses'=>'ProjectFoldersController@saveProjectFolder'));
			Route::get('/project-folder-manager/edit-folder/{Pid}/{id}',array('as'=>'ProjectFolder.edit','uses'=>'ProjectFoldersController@editProjectFolder'));
			Route::post('/project-folder-manager/update-folder/{Pid}/{id}',array('as'=>'ProjectFolder.update','uses'=>'ProjectFoldersController@updateProjectFolder'));
			Route::get('/project-folder-manager/view-folder/{Pid}/{id}',array('as'=>'ProjectFolder.view','uses'=>'ProjectFoldersController@viewProjectFolder'));
			Route::get('/project-folder-manager/update-status/{id}/{status}',array('as'=>'ProjectFolder.status','uses'=>'ProjectFoldersController@updateProjectFolderStatus'));
			### project Article manager routing

			Route::get('/project-folder-article/{folder_id}/{id}',array('as'=>'ProjectFolderArticle.index','uses'=>'ProjectFolderArticleController@listProjectFolderArticle'));
			Route::get('/project-folder-article/add-article/{folder_id}/{id}',array('as'=>'ProjectFolderArticle.add','uses'=>'ProjectFolderArticleController@addProjectFolderArticle'));
			Route::post('/project-folder-article/save-article/{folder_id}/{article_id}',array('as'=>'ProjectFolderArticle.save','uses'=>'ProjectFolderArticleController@saveProjectFolderArticle'));
			Route::get('/project-folder-article/edit-article/{folder_id}/{article_id}/{id}',array('as'=>'ProjectFolderArticle.edit','uses'=>'ProjectFolderArticleController@editProjectFolderArticle'));
			Route::post('/project-folder-article/update-article/{folder_id}/{article_id}/{id}',array('as'=>'ProjectFolderArticle.update','uses'=>'ProjectFolderArticleController@updateProjectFolderArticle'));
			Route::get('/project-folder-article/view-article/{folder_id}/{article_id}/{id}',array('as'=>'ProjectFolderArticle.view','uses'=>'ProjectFolderArticleController@viewProjectFolderArticle'));
			Route::get('/project-folder-article/update-status/{id}/{status}',array('as'=>'ProjectFolderArticle.status','uses'=>'ProjectFolderArticleController@updateProjectFolderStatusArticle')); 
			Route::post('project-folder-article/add-more-article-documents',array('as'=>'ProjectFolderArticle.addMoreDocument','uses'=>'ProjectFolderArticleController@addMoreArticleDocument'));
			Route::post('project-folder-article/delete-article-documents',array('as'=>'ProjectFolderArticle.deleteProjectDocument','uses'=>'ProjectFolderArticleController@deleteProjectDocument'));
			Route::post('project-folder-article/add-more-article-link',array('as'=>'ProjectFolderArticle.addMoreDocumentLink','uses'=>'ProjectFolderArticleController@addMoreArticleDocumentLink'));
			Route::post('project-folder-article/comment-reply-data',array('as'=>'ProjectFolderArticle.commentReplyData','uses'=>'ProjectFolderArticleController@commentReplyData'));
			*/
			### project Folder manager routing
			Route::any('/blog/categories',array('as'=>'ProjectFolder.index','uses'=>'ProjectFoldersController@listProjectFolder'));
			Route::get('/blog/categories/add-categories',array('as'=>'ProjectFolder.add','uses'=>'ProjectFoldersController@addProjectFolder'));
			Route::post('/blog/categories/save-categories',array('as'=>'ProjectFolder.save','uses'=>'ProjectFoldersController@saveProjectFolder'));
			Route::get('/blog/categories/edit-categories/{id}',array('as'=>'ProjectFolder.edit','uses'=>'ProjectFoldersController@editProjectFolder'));
			Route::post('/blog/categories/update-categories/{id}',array('as'=>'ProjectFolder.update','uses'=>'ProjectFoldersController@updateProjectFolder'));
			Route::get('/blog/categories/view-categories/{id}',array('as'=>'ProjectFolder.view','uses'=>'ProjectFoldersController@viewProjectFolder'));
			Route::get('/blog/categories/update-status/{id}/{status}',array('as'=>'ProjectFolder.status','uses'=>'ProjectFoldersController@updateProjectFolderStatus'));
			Route::post('blog/categories/get-article-categories',array('as'=>'ProjectFolder.getArticleCategories','uses'=>'ProjectFoldersController@getArticleCategories'));
			Route::post('/blog/categories/delete-featured-image',array('as'=>'ProjectFolder.deleteFeaturedImage','uses'=>'ProjectFoldersController@deleteFeaturedImage'));
			Route::post('/blog/categories/update-order',array('as'=>'ProjectFolder.updateOrder','uses'=>'ProjectFoldersController@updateOrder'));
			### project Article manager routing

			Route::get('/blog/content',array('as'=>'ProjectFolderArticle.conetentIndex','uses'=>'ProjectFolderArticleController@conetentIndex'));
			//Route::get('/project-folder-article/{folder_id}',array('as'=>'ProjectFolderArticle.index','uses'=>'ProjectFolderArticleController@listProjectFolderArticle'));
			Route::get('/blog/content/add',array('as'=>'ProjectFolderArticle.add','uses'=>'ProjectFolderArticleController@addProjectFolderArticle'));
			Route::post('/blog/content/save-article',array('as'=>'ProjectFolderArticle.save','uses'=>'ProjectFolderArticleController@saveProjectFolderArticle'));
			Route::get('/blog/content/edit-article/{folder_id}/{article_id}',array('as'=>'ProjectFolderArticle.edit','uses'=>'ProjectFolderArticleController@editProjectFolderArticle'));
			Route::post('/blog/content/update-article/{folder_id}/{article_id}',array('as'=>'ProjectFolderArticle.update','uses'=>'ProjectFolderArticleController@updateProjectFolderArticle'));
			Route::get('/blog/content/view-article/{folder_id}/{article_id}',array('as'=>'ProjectFolderArticle.view','uses'=>'ProjectFolderArticleController@viewProjectFolderArticle'));
			Route::get('/blog/content/update-status/{id}/{status}',array('as'=>'ProjectFolderArticle.status','uses'=>'ProjectFolderArticleController@updateProjectFolderStatusArticle')); 
			Route::post('blog/content/add-more-article-documents',array('as'=>'ProjectFolderArticle.addMoreDocument','uses'=>'ProjectFolderArticleController@addMoreArticleDocument'));
			Route::post('blog/content/delete-article-documents',array('as'=>'ProjectFolderArticle.deleteProjectDocument','uses'=>'ProjectFolderArticleController@deleteProjectDocument'));
			Route::post('blog/content/add-more-article-link',array('as'=>'ProjectFolderArticle.addMoreDocumentLink','uses'=>'ProjectFolderArticleController@addMoreArticleDocumentLink'));
			Route::post('blog/content/comment-reply-data',array('as'=>'ProjectFolderArticle.commentReplyData','uses'=>'ProjectFolderArticleController@commentReplyData'));
			Route::get('blog/content/check-this-out/{id}/{checkthisout}',array('as'=>'ProjectFolderArticle.checkThisOut','uses'=>'ProjectFolderArticleController@updateCheckThisOut'));
			
			
			
			/*Questions routing start here*/
			Route::get('/questions',array('as'=>'Question.index','uses'=>'QuestionsController@listQuestions'));
			Route::get('questions/add_questions',array('as'=>'Question.add','uses'=>'QuestionsController@addQuestion'));
			Route::post('questions/save_questions',array('as'=>'Question.save','uses'=>'QuestionsController@saveQuestion'));
			Route::get('questions/edit_questions/{id}',array('as'=>'Question.edit','uses'=>'QuestionsController@editQuestion'));
			Route::post('questions/update_questions/{id}',array('as'=>'Question.update','uses'=>'QuestionsController@updateQuestion'));
			Route::get('questions/delete_questions/{id}',array('as'=>'Question.delete','uses'=>'QuestionsController@deleteQuestion'));
			Route::get('questions/update-status/{id}/{status}',array('as'=>'Question.status','uses'=>'QuestionsController@updateQuestionStatus'));
			Route::get('questions/view_questions/{id}',array('as'=>'Question.view','uses'=>'QuestionsController@viewQuestion'));
			Route::post('questions/add-more-answer',array('as'=>'Question.addMoreAnswer','uses'=>'QuestionsController@addAnswer'));
			Route::post('questions/delete-form-question-option',array('as'=>'Question.deleteFromQuestionOption','uses'=>'QuestionsController@deleteQuestionOption'));
			
			# challenges routing start here //
			Route::get('/challenges',array('as'=>'Challenge.index','uses'=>'ChallengesController@listChallenges'));
			Route::get('challenges/add-challenge',array('as'=>'Challenge.add','uses'=>'ChallengesController@addChallenge'));
			Route::post('challenges/add-challenge',array('as'=>'Challenge.add','uses'=>'ChallengesController@saveChallenge'));	
			Route::get('challenges/edit-challenge/{id}',array('as'=>'Challenge.edit','uses'=>'ChallengesController@editChallenge'));
			Route::post('challenges/edit-challenge',array('as'=>'Challenge.update','uses'=>'ChallengesController@updateChallenge'));
			Route::post('challenges/add-more-prize',array('as'=>'Challenge.addMorePrize','uses'=>'ChallengesController@addPrize'));
			Route::get('challenges/view-challenge/{id}',array('as'=>'Challenge.view','uses'=>'ChallengesController@viewChallenge'));
			Route::get('challenges/update-status/{id}/{status}',array('as'=>'Challenge.status','uses'=>'ChallengesController@updateChallengeStatus'));
			Route::any('challenges/delete-challenge/{id}',array('as'=>'Challenge.delete','uses'=>'ChallengesController@deleteChallenge'));
			Route::post('challenges/delete-challenge-prize',array('as'=>'Challenge.deletePrize','uses'=>'ChallengesController@deleteChallengePrize'));
			
			
			Route::get('/dance-star-post',array('as'=>'DanceStarPost.index','uses'=>'DanceStarPostController@listDanceStarPost'));
			Route::get('/dance-star-post/add-post',array('as'=>'DanceStarPost.add','uses'=>'DanceStarPostController@addDanceStarPost'));
			Route::post('/dance-star-post/save-post',array('as'=>'DanceStarPost.save','uses'=>'DanceStarPostController@saveDanceStarPost'));
			Route::get('/dance-star-post/edit-post/{id}',array('as'=>'DanceStarPost.edit','uses'=>'DanceStarPostController@editDanceStarPost'));
			Route::post('/dance-star-post/update-post/{id}',array('as'=>'DanceStarPost.update','uses'=>'DanceStarPostController@updateDanceStarPost'));
			Route::get('/dance-star-post/view-post/{id}',array('as'=>'DanceStarPost.view','uses'=>'DanceStarPostController@viewDanceStarPost'));
			Route::get('/dance-star-post/update-status/{id}/{status}',array('as'=>'DanceStarPost.status','uses'=>'DanceStarPostController@updatePostStatus')); 
			Route::post('dance-star-post/add-more-post-documents',array('as'=>'DanceStarPost.addMoreDocument','uses'=>'DanceStarPostController@addMorePostDocument'));
			Route::post('dance-star-post/delete-post-documents',array('as'=>'DanceStarPost.deletePostDocument','uses'=>'DanceStarPostController@deletePostDocument'));
			Route::post('dance-star-post/add-more-post-link',array('as'=>'DanceStarPost.addMoreDocumentLink','uses'=>'DanceStarPostController@addMorePostDocumentLink'));
		
			Route::get('/events',array('as'=>'Event.index','uses'=>'EventController@listEvent'));
			Route::get('/events/add-event',array('as'=>'Event.add','uses'=>'EventController@addEvent'));
			Route::post('/events/save-event',array('as'=>'Event.save','uses'=>'EventController@saveEvent'));
			Route::get('/events/edit-event/{id}',array('as'=>'Event.edit','uses'=>'EventController@editEvent'));
			Route::post('/events/update-event/{id}',array('as'=>'Event.update','uses'=>'EventController@updateEvent'));
			Route::get('/events/view-event/{id}',array('as'=>'Event.view','uses'=>'EventController@viewEvent'));
			Route::get('/events/update-status/{id}/{status}',array('as'=>'Event.status','uses'=>'EventController@updateEventStatus')); 
			Route::post('events/add-more-event-documents',array('as'=>'Event.addMoreEventDocument','uses'=>'EventController@addMoreEventDocument'));
			Route::post('events/delete-event-documents',array('as'=>'Event.deleteEventDocument','uses'=>'EventController@deleteEventDocument'));
			Route::post('events/add-more-event-link',array('as'=>'Event.addMoreEventDocumentLink','uses'=>'EventController@addMoreEventDocumentLink'));
		
			Route::get('/newsfeed',array('as'=>'Newsfeed.index','uses'=>'NewsfeedController@listNewsfeed'));
			Route::get('/newsfeed/add-newsfeed',array('as'=>'Newsfeed.add','uses'=>'NewsfeedController@addNewsfeed'));
			Route::post('/newsfeed/save-newsfeed',array('as'=>'Newsfeed.save','uses'=>'NewsfeedController@saveNewsfeed'));
			Route::get('/newsfeed/edit-newsfeed/{id}',array('as'=>'Newsfeed.edit','uses'=>'NewsfeedController@editNewsfeed'));
			Route::post('/newsfeed/update-newsfeed/{id}',array('as'=>'Newsfeed.update','uses'=>'NewsfeedController@updateNewsfeed'));
			Route::get('/newsfeed/view-newsfeed/{id}',array('as'=>'Newsfeed.view','uses'=>'NewsfeedController@viewNewsfeed'));
			Route::get('/newsfeed/update-status/{id}/{status}',array('as'=>'Newsfeed.status','uses'=>'NewsfeedController@updateNewsfeedStatus')); 
			
		
			Route::get('/home-content',array('as'=>'HomeContent.index','uses'=>'HomeContentsController@listHomeContent'));
			Route::get('/home-content/add-home-content',array('as'=>'HomeContent.add','uses'=>'HomeContentsController@addHomeContent'));
			Route::post('/home-content/save-home-content',array('as'=>'HomeContent.save','uses'=>'HomeContentsController@saveHomeContent'));
			Route::get('/home-content/edit-home-content/{id}',array('as'=>'HomeContent.edit','uses'=>'HomeContentsController@editHomeContent'));
			Route::post('/home-content/update-home-content/{id}',array('as'=>'HomeContent.update','uses'=>'HomeContentsController@updateHomeContent'));
			Route::get('/home-content/view-home-content/{id}',array('as'=>'HomeContent.view','uses'=>'HomeContentsController@viewHomeContent'));
			Route::get('/home-content/update-status/{id}/{status}',array('as'=>'HomeContent.status','uses'=>'HomeContentsController@updateHomeContentStatus')); 
			
			##Tutorial manager  module  routing start here
			Route::get('/tutorials',array('as'=>'Tutorial.index','uses'=>'TutorialController@listTutorial'));
			Route::get('tutorials/add-tutorial',array('as'=>'Tutorial.add','uses'=>'TutorialController@addTutorial'));
			Route::post('tutorials/add-tutorial',array('as'=>'Tutorial.save','uses'=>'TutorialController@saveTutorial'));
			Route::get('tutorials/edit-tutorial/{id}',array('as'=>'Tutorial.edit','uses'=>'TutorialController@editTutorial'));
			Route::post('tutorials/edit-tutorial/{id}',array('as'=>'Tutorial.edit','uses'=>'TutorialController@updateTutorial'));
			Route::get('tutorials/update-status/{id}/{status}',array('as'=>'Tutorial.status','uses'=>'TutorialController@updateTutorialStatus'));
			Route::any('tutorials/delete-tutorial/{id}',array('as'=>'Tutorial.delete','uses'=>'TutorialController@deleteTutorial'));		
			Route::post('tutorials/multiple-action',array('as'=>'Tutorial.Multipleaction','uses'=>'TutorialController@performMultipleAction'));
			
			Route::any('tutorials/change_order',array('as'=>'Tutorial.changeOrder','uses'=>'TutorialController@changeTutorialOrder'));
			##Tutorial manager  module  routing end here
			
			###Product management routing
			Route::get('/product-manager',array('as'=>'Product.index','uses'=>'ProductsController@listProducts'));
			Route::get('/product-manager/export','ProductsController@export_all_products');
			Route::get('/product-manager/export-filtered','ProductsController@export_filter_products');
			Route::post('/product-manager/remove-image',array('as'=>'Product.remove','uses'=>'ProductsController@removeImage'));
			Route::get('product-manager/add-product',array('as'=>'Product.add','uses'=>'ProductsController@addProduct'));
			Route::post('product-manager/add-product',array('as'=>'Product.save','uses'=>'ProductsController@saveProduct'));
			Route::get('product-manager/edit-product/{id}',array('as'=>'Product.edit','uses'=>'ProductsController@editProduct'));
			Route::post('product-manager/edit-product/{id}',array('as'=>'Product.update','uses'=>'ProductsController@updateProduct'));
			Route::get('product-manager/view-product/{id}',array('as'=>'Product.view','uses'=>'ProductsController@viewProduct'));
			Route::get('product-manager/update-status/{id}/{status}',array('as'=>'Product.status','uses'=>'ProductsController@updateProductStatus'));
			Route::get('product-manager/update-featured-status/{id}/{status}',array('as'=>'Product.updateProductFeaturedStatus','uses'=>'ProductsController@updateProductFeaturedStatus'));
			Route::any('product-manager/delete-product/{id}',array('as'=>'Product.delete','uses'=>'ProductsController@deleteProduct'));
			Route::post('product-manager/multiple-action',array('as'=>'Product.Multipleaction','uses'=>'ProductsController@performMultipleAction'));
			
			Route::post('product-manager/add-more-product',array('as'=>'Product.addMoreProduct','uses'=>'ProductsController@addMoreProduct'));
			Route::post('/product-manager/remove-product-attribute',array('as'=>'Product.removeAttributes','uses'=>'ProductsController@removeproductAttributes'));
			/** Product finish here**/;
			
		});
	});
	################################################################# Admin Routing end here###################################################
