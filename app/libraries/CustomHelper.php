<?php
class CustomHelper {
/** 
* Function to get_system_image
*
* @param $slug
* 
* @return $systemImageContent(image name)
*/	
	public static function get_home_page_content($type = null){
		$homePageContent		=	array();
		if(!empty($type)){
			$homePageContent	=  DB::table('home_contents')->where('type',$type)->where('is_active',IS_ACTIVE)->select('image','description')->first();
		}
		return $homePageContent;
	}
	
	public static function get_system_image($slug = null){
		$systemImageContent		=	array();
		if(!empty($slug)){
			$systemImageContent	=  DB::table('system_documents')->where('slug',$slug)->where('is_active',IS_ACTIVE)->select('name')->first();
		}
		return $systemImageContent;
	}
/** 
* Function to get_cms_data
*
* @param $slug
* 
* @return $cmsContent(meta title, meta key, meta desc)
*/		
	public static function get_cms_data($slug = null){
		$cmsContent		=	array();
		if(!empty($slug)){
			$cmsContent	=  DB::table('cms_pages')->where('slug',$slug)->where('is_active',IS_ACTIVE)->select('meta_title','meta_description','meta_keywords','body')->first();
		}
		return $cmsContent;
	}
	
	//Get Sizes
	public static function getSizes($ids){
		$lang			=	App::getLocale();
		$sizes			=	DB::select( DB::raw("select id,name from dropdown_managers WHERE dropdown_managers.id IN($ids)"));
		return $sizes;
	}//end getSizes()
	
/** 
* Function to get_seo_data
*
* @param $slug
* 
* @return $seoContent(meta title, meta key, meta desc)
*/		
	public static function get_seo_data($pageId = null){
		$seoContent		=	array();
		if(!empty($pageId)){
			$seoContent	=  DB::table('seos')->where('page_id',$pageId)->select('page_name','meta_keywords','meta_description')->first();
		}
		return $seoContent;
	}
	
	public static function get_user_role_id($userId = null){
		$userData 	=  DB::table('users')->where('id',$userId)->orderBy('full_name','ASC')->first(); 
		return $userData;
	}
	
	public static function time_elapsed_string($ptime)
	{
		$etime = time('now') - $ptime;
		if ($etime < 1)
		{
			return '0 seconds';
		}

		$a = array( 365 * 24 * 60 * 60  =>  'year',
					 30 * 24 * 60 * 60  =>  'month',
						  24 * 60 * 60  =>  'day',
							   60 * 60  =>  'hour',
									60  =>  'minute',
									 1  =>  'second'
					);
		$a_plural = array( 'year'   => 'years',
						   'month'  => 'months',
						   'day'    => 'days',
						   'hour'   => 'hours',
						   'minute' => 'minutes',
						   'second' => 'seconds'
					);

		foreach ($a as $secs => $str)
		{
			$d = $etime / $secs;
			if ($d >= 1)
			{
				$r = round($d);
				return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
			}
		}
	}
	
	public static function getNotifications(){
		return $notifications = DB::table('notifications')
								->where('notifications.is_read',0)
								->leftJoin('users','users.id','=','notifications.sender_id')
								->where('notifications.type','blog_comment')
								->orderBy('notifications.id',"DESC")
								->select('users.full_name','notifications.*')
								->limit(5)->get();
	}
	
	public static function get_time($time){
			$latestTwitTime	=	strtotime($time);
			$TwitTime		=	CustomHelper::time_elapsed_string($latestTwitTime);
		return $TwitTime;
	}
	
	public static function addhttp($url = ""){
		if($url == ""){
			return "";
		}
		if(!preg_match("~^(?:f|ht)tps?://~i", $url)){
			$url = "http://" . $url;
		}
		return $url;
	}
}
