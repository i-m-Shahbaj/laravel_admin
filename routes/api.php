<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
//	Debugbar::disable();
    return $request->user();
});
Route::group(array('middleware' => 'App\Http\Middleware\MobileAuthenticated','namespace'=>'mobile'), function() {
	Route::post('login',array('uses'=>'UsersController@login'));
	Route::post('signup',array('uses'=>'UsersController@signup'));
	Route::post('forget_password',array('uses'=>'UsersController@forget_password'));
	Route::post('change_password',array('uses'=>'UsersController@change_password'));
	Route::post('get_user_detail',array('uses'=>'UsersController@get_user_detail'));
	Route::get('logout',array('uses'=>'UsersController@logout'));
	Route::post('check_email_exist',array('uses'=>'UsersController@checkEmailExist'));
	Route::post('check_username_exist',array('uses'=>'UsersController@checkUsernameExist'));
	Route::get('country',array('uses'=>'UsersController@getCountryList'));
	Route::get('state/{id}',array('uses'=>'UsersController@getStateList'));
	Route::get('city/{id}',array('uses'=>'UsersController@getCityList'));
	Route::post('check_parent_detail',array('uses'=>'UsersController@checkParentDetail'));
	Route::post('check_socialid_exist',array('uses'=>'UsersController@check_socialid_exist'));
	Route::post('cms_detail',array('uses'=>'UsersController@cms_detail'));
	Route::post('user_profile',array('uses'=>'UsersController@getUserProfile'));
	Route::post('qrcode',array('uses'=>'UsersController@getQRCode'));
	Route::post('userprofilebyusername',array('uses'=>'UsersController@getUserProfileFromUsername'));
	Route::post('change_profile_picture',array('uses'=>'UsersController@saveUserProfilePicture'));
	Route::post('edit-user',array('uses'=>'UsersController@edit_profile'));
	
	Route::post('get_all_posts',array('uses'=>'PostsController@get_all_posts'));
	Route::post('save_posts',array('uses'=>'PostsController@save_posts'));
	Route::post('like_on_post',array('uses'=>'PostsController@like_on_post'));
	Route::post('unlike_on_post',array('uses'=>'PostsController@unlike_on_post'));
	Route::post('save_post_likes',array('uses'=>'PostsController@save_post_likes'));
	Route::post('save_comments',array('uses'=>'PostsController@save_comments'));
	Route::post('get_comment_on_post',array('uses'=>'PostsController@get_comment_on_post'));
	Route::post('like_on_comment',array('uses'=>'PostsController@like_on_comment'));
	Route::post('reply_on_comment',array('uses'=>'PostsController@reply_on_comment'));
	Route::post('total_reply_on_comment',array('uses'=>'PostsController@total_reply_on_comment'));
	Route::post('get_comment_thread',array('uses'=>'PostsController@get_comment_thread'));
	Route::post('wall',array('uses'=>'PostsController@wall'));
	Route::post('fan_wall',array('uses'=>'PostsController@fan_wall'));
	Route::post('notifications',array('uses'=>'PostsController@notifications'));
	Route::post('get_unread_notification_count',array('uses'=>'PostsController@get_unread_notification_count'));
		
	//Friend Requests
	Route::post('search_friends',array('uses'=>'FriendsController@search_friends'));
	Route::post('send_friend_request',array('uses'=>'FriendsController@send_friend_request'));
	Route::post('get_all_friend_request',array('uses'=>'FriendsController@get_all_friend_request'));
	Route::post('accept_reject_friend_request',array('uses'=>'FriendsController@accept_reject_friend_request'));
	Route::post('cancel_friend_request',array('uses'=>'FriendsController@cancel_friend_request'));
	Route::post('my_friends',array('uses'=>'FriendsController@my_friends'));
	
	//Follow Requests
	Route::post('search_dancers',array('uses'=>'FansController@search_dancers'));
	Route::post('send_follow_request',array('uses'=>'FansController@send_follow_request'));
	Route::post('cancel_follow_request',array('uses'=>'FansController@cancel_follow_request'));
	Route::post('accept_reject_follow_request',array('uses'=>'FansController@accept_reject_follow_request'));
	Route::post('my_followings',array('uses'=>'FansController@my_followings'));
	Route::post('my_fan_requests',array('uses'=>'FansController@my_fan_requests'));
	Route::post('my_following_count',array('uses'=>'FansController@my_following_count'));
	
	//challenge
	Route::post('challenge_list',array('uses'=>'ChallengesController@challenge_list'));
	Route::post('challenge_detail',array('uses'=>'ChallengesController@challenge_detail'));
	Route::post('question_list_of_challenge',array('uses'=>'ChallengesController@question_list_of_challenge'));
	Route::post('save_challenge_questions',array('uses'=>'ChallengesController@save_challenge_questions'));
	Route::post('leaderboard_challenge_list',array('uses'=>'ChallengesController@leaderboard_challenge_list'));
	Route::post('leaderboard_score_list',array('uses'=>'ChallengesController@leaderboard_score_list'));
	Route::post('challenge_price_list',array('uses'=>'ChallengesController@price_list'));
	Route::post('challenge_detail_by_id',array('uses'=>'ChallengesController@challenge_detail_by_id'));
	Route::post('challenge_instructions',array('uses'=>'ChallengesController@challenge_instructions'));
	Route::post('challenge_term_conditions',array('uses'=>'ChallengesController@challenge_term_conditions'));
	Route::post('challenge_join_now',array('uses'=>'ChallengesController@challenge_join_now'));
	
	Route::post('library_folders',array('uses'=>'LibrariesController@library_folders'));
	Route::post('most_viewed_topics',array('uses'=>'LibrariesController@most_viewed_topics'));
	Route::post('recently-added-topics',array('uses'=>'LibrariesController@recently_added_topics'));
	Route::post('get_newsfeed',array('uses'=>'LibrariesController@get_newsfeed'));
	Route::post('folder-detail',array('uses'=>'LibrariesController@folder_detail'));
	Route::post('topic-detail',array('uses'=>'LibrariesController@topic_detail'));
	Route::post('sell-all-newsfeeds',array('uses'=>'LibrariesController@sell_all_newsfeeds'));
	Route::post('newsfeed-detail',array('uses'=>'LibrariesController@newsfeed_detail'));
	Route::post('check-this-out-topics',array('uses'=>'LibrariesController@check_this_out_topics'));
	Route::post('search-library',array('uses'=>'LibrariesController@search_library'));
	
	//group
	Route::post('group-list',array('uses'=>'GroupsController@group_list'));
	Route::post('add-group-fan',array('uses'=>'GroupsController@add_group_fans'));
	Route::post('group-fan-list',array('uses'=>'GroupsController@view_group_fans'));
	Route::post('remove-group-fan',array('uses'=>'GroupsController@remove_group_fan'));
	Route::post('add-group',array('uses'=>'GroupsController@add_group'));
	Route::post('edit-group',array('uses'=>'GroupsController@edit_group'));
	Route::post('delete-group',array('uses'=>'GroupsController@delete_group'));
	Route::post('get-group-list',array('uses'=>'GroupsController@get_group_list'));
	
	Route::post('get-privacy-settings',array('uses'=>'UsersController@get_privacy_setting'));
	Route::post('update-privacy-settings',array('uses'=>'UsersController@update_privacy_setting'));
	Route::post('my-non-blocked-friends',array('uses'=>'UsersController@my_non_blocked_friends'));
	Route::post('blocked-friend-list',array('uses'=>'UsersController@blocked_friend_list'));
	Route::post('block-friend',array('uses'=>'UsersController@block_friend'));
	Route::post('unblock-friend',array('uses'=>'UsersController@unblock_friend'));
	
	//tutorials
	Route::post('get-tutorials-list',array('uses'=>'TutorialsController@get_all_tutorials'));
	
});
