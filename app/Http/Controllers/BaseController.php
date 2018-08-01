<?php
namespace App\Http\Controllers;
use App\Model\Notification;
use App\Model\UserPrivacySetting;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,Str,App;
/**
* Base Controller
*
* Add your methods in the class below
*
* This is the base controller called everytime on every request
*/
class BaseController extends Controller {
	public function __construct() {
		
	}// end function __construct()
/**
* Setup the layout used by the controller.
*
* @return layout
*/
	protected function setupLayout(){
		if(Request::segment(1) != 'admin'){
			
		}
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}//end setupLayout()
/** 
* Function to make slug according model from any certain field
*
* @param title     as value of field
* @param modelName as section model name
* @param limit 	as limit of characters
* 
* @return string
*/	
	public function getSlug($title, $fieldName,$modelName,$limit = 30){
		//~ $slug 		= 	 substr(Str::slug($title),0 ,$limit);
		//~ $Model		=	 "\App\Model\\$modelName";
		//~ $slugCount 	=    count($Model::where($fieldName, 'regexp', "/^{$slug}(-[0-9]*)?$/i")->get());
		//~ return ($slugCount > 0) ? $slug."-".$slugCount : $slug;

		$slug 			= 	 substr(Str::slug($title),0 ,$limit);
		$Model			=	 "\App\Model\\$modelName";
		$slugCount 		=    $Model::where($fieldName,$title)->count();
		
		if($slugCount == 0){
			$slug 		= 	 substr(Str::slug($title),0 ,$limit);
		}else{
			$slug 		= 	 $slug."-".$slugCount;
		}
		return $slug;
	}//end getSlug()
	
	
	public function adminGetNotifications(){
		return $notifications = DB::table('notifications')
								->where('notifications.is_read',0)
								->leftJoin('users','users.id','=','notifications.sender_id')
								->where('notifications.type','blog_comment')
								->orderBy('notifications.id',"DESC")
								->select('users.full_name','notifications.*')
								->limit(5)->get();
								
	}
	
/** 
* Function to make slug without model name from any certain field
*
* @param title     as value of field
* @param tableName as table name
* @param limit 	as limit of characters
* 
* @return string
*/	
	public function getSlugWithoutModel($title, $fieldName='' ,$tableName,$limit = 30){ 	
		$slug 		=	substr(Str::slug($title),0 ,$limit);
		$slug 		=	Str::slug($title);
		$DB 		= 	DB::table($tableName);
		$slugCount 	= 	count( $DB->whereRaw("$fieldName REGEXP '^{$slug}(-[0-9]*)?$'")->get() );
		return ($slugCount > 0) ? $slug."-".$slugCount: $slug;
	}//end getSlugWithoutModel()
/** 
* Function to search result in database
*
* @param data  as form data array
*
* @return query string
*/		
	public function search($data){
		unset($data['display']);
		unset($data['_token']);
		$ret	=	'';
		if(!empty($data )){
			foreach($data as $fieldName => $fieldValue){
				$ret	.=	"where('$fieldName', 'LIKE',  '%' . $fieldValue . '%')";
			}
			return $ret;
		}
	}//end search()
/** 
* Function to send email form website
*
* @param string $to            as to address
* @param string $fullName      as full name of receiver
* @param string $subject       as subject
* @param string $messageBody   as message body
*
* @return void
*/
	public function sendMail($to,$fullName,$subject,$messageBody, $from = '',$files = false,$path='',$attachmentName='') {
		$data				=	array();
		$data['to']			=	$to;
		$data['from']		=	(!empty($from) ? $from : Config::get("Site.email"));
		$data['fullName']	=	$fullName;
		$data['subject']	=	$subject;
		$data['filepath']	=	$path;
		$data['attachmentName']	=	$attachmentName;
		if($files===false){
			Mail::send('emails.template', array('messageBody'=> $messageBody), function($message) use ($data){
				$message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject']);
			});	
		}else{
			if($attachmentName!=''){
				Mail::send('emails.template', array('messageBody'=> $messageBody), function($message) use ($data){
					$message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject'])->attach($data['filepath'],array('as'=>$data['attachmentName']));
				});
			}else{
				Mail::send('emails.template', array('messageBody'=> $messageBody), function($message) use ($data){
					$message->to($data['to'], $data['fullName'])->from($data['from'])->subject($data['subject'])->attach($data['filepath']);
				});
			}
		}
		
		DB::table('email_logs')->insert(
			array(
				'email_to'	 => $data['to'],
				'email_from' => $data['from'],
				'subject'	 => $data['subject'],
				'message'	 =>	$messageBody,
				'created_at' => DB::raw('NOW()')
			)
		);
	}
	
	public  function arrayStripTags($array){
		$result			=	array();
		foreach ($array as $key => $value) {
			// Don't allow tags on key either, maybe useful for dynamic forms.
			$key = strip_tags($key,ALLOWED_TAGS_XSS);
	 
			// If the value is an array, we will just recurse back into the
			// function to keep stripping the tags out of the array,
			// otherwise we will set the stripped value.
			if (is_array($value)) {
				$result[$key] = $this->arrayStripTags($value);
			} else {
				// I am using strip_tags(), you may use htmlentities(),
				// also I am doing trim() here, you may remove it, if you wish.
				$result[$key] = trim(strip_tags($value,ALLOWED_TAGS_XSS));
			}
		}
		
		return $result;
		
	}
	
	public function saveCkeditorImages() {
		if(isset($_GET['CKEditorFuncNum'])){
			$image_url				=	"";
			$msg					=	"";
			// Will be returned empty if no problems
			$callback = ($_GET['CKEditorFuncNum']);        // Tells CKeditor which function you are executing
			$image_details 				= 	getimagesize($_FILES['upload']["tmp_name"]);
			$image_mime_type			=	(isset($image_details["mime"]) && !empty($image_details["mime"])) ? $image_details["mime"] : "";
			if($image_mime_type	==	'image/jpeg' || $image_mime_type == 'image/jpg' || $image_mime_type == 'image/gif' || $image_mime_type == 'image/png'){
				$ext					=	$this->getExtension($_FILES['upload']['name']);
				$fileName				=	"ck_editor_".time().".".$ext;
				$upload_path			=	CK_EDITOR_ROOT_PATH;
				if(move_uploaded_file($_FILES['upload']['tmp_name'],$upload_path.$fileName)){
					$image_url 			= 	CK_EDITOR_URL. $fileName;    
				}
			}else{
				$msg =  'error : Please select a valid image. valid extension are jpeg, jpg, gif, png';
			}
			$output = '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$callback.', "'.$image_url .'","'.$msg.'");</script>';
			echo $output;
			exit;
		}
	}
	
	function getExtension($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; }
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		$ext = strtolower($ext);
		return $ext;
	}
	
		
/** 
 * Function to convert video in mp4
 *
 * param source target width and height  
 */ 
	public function convertToMp4($source, $target, $width, $height){
		 $commandString = FFMPEG_CONVERT_COMMAND.'ffmpeg -i '.$source.' -vcodec libx264 -r 30 -ar 44100 -s '.$width.'x'.$height.' -async 1 -f mp4 '.$target.'';
		$command = shell_exec($commandString);
		return true;
	}//end convertToMp4()
	
/** 
 * Function to convert video in webm
 *
 * param source target width and height  
 */ 	
	public function convertToWebm($source, $target, $width, $height){
	    //$commandString  =	FFMPEG_CONVERT_COMMAND."ffmpeg -i ".$source." -r 30 -ar 44100 -vf scale=".$width.":".$height." -f webm ".$target." - 2>".SLIDER_ROOT_PATH."error.log";
		
		$commandString  = FFMPEG_CONVERT_COMMAND.'ffmpeg -i '.$source.' -vf scale='.$width.':'.$height.' '.$target." - 2>".SLIDER_ROOT_PATH."error.log";
		
		//$commandString 	= FFMPEG_CONVERT_COMMAND.'ffmpeg -i '.$source.' -vcodec libvpx -acodec libvorbis -qscale 1 -r 30 -ar 44100 -vf scale='.$width.':'.$height.' -async 1 -f webm '.$target." - 2>".SLIDER_ROOT_PATH."error.log";
		
		$command = shell_exec($commandString);
		return true;
	}//end convertToWebm()
	
/** 
 * Function to generate thumbnails from video
 *
 * param source target width and height  
 */ 	
	public function generateThumbnail($source, $target, $width='', $height=''){
		$commandString = FFMPEG_CONVERT_COMMAND.'ffmpeg -i '.$source.' -an -y -f mjpeg -ss 00:00:01 -s '.$width.'x'.$height.' '.$target;
		$command = shell_exec($commandString);
	}//end generateThumbnail()
	
/** 
 * Function to _update_all_status
 *
 * param source tableName,id,status,fieldName
 */	
	public function _update_all_status($tableName = null,$id = 0,$status= 0,$fieldName = 'is_active'){
		DB::beginTransaction();
		$response			=	DB::statement("CALL UpdateAllTableStatus('$tableName',$id,$status)");
		if(!$response) {
			DB::rollback();
			Session::flash('error', trans("messages.msg.error.something_went_wrong")); 
			return Redirect::back();
		}
		DB::commit();
	}
	
/** 
 * Function to _delete_table_entry
 *
 * param source tableName,id,fieldName
 */
	public function _delete_table_entry($tableName = null,$id = 0,$fieldName = null){
		DB::beginTransaction();
		$response			=	DB::statement("CALL DeleteAllTableDataById('$tableName',$id,'$fieldName')");
		if(!$response) {
			DB::rollback();
			Session::flash('error', trans("messages.msg.error.something_went_wrong")); 
			return Redirect::back();
		}
		DB::commit();
	}// end _delete_table_entry()
	
	function display_latest_tweets($twitter_user_id,$limit = 10){
		$oauth_access_token 			=	config::get("twitter_configration.access_token");
		$oauth_access_token_secret 		=	config::get("twitter_configration.access_token_secret");
		$consumer_key 					=	config::get("twitter_configration.consumer_key");
		$consumer_secret 				=	config::get("twitter_configration.consumer_secret");
		 
		// we are going to use "user_timeline"
		$twitter_timeline 				=	"user_timeline";
		 
		// specify number of tweets to be shown and twitter username
		// for example, we want to show 20 of Taylor Swift's twitter posts
		
		$request 						=	array(
			'count' => $limit,
			'screen_name' => $twitter_user_id
		);
		
		$oauth							=	array(
			'oauth_consumer_key' 		=>	$consumer_key,
			'oauth_nonce'				=>	time(),
			'oauth_signature_method'	=>	'HMAC-SHA1',
			'oauth_token'				=>	$oauth_access_token,
			'oauth_timestamp'			=>	time(),
			'oauth_version'				=>	'1.0'
		);
		 
		// combine request and oauth in one array
		$oauth							=	array_merge($oauth, $request);
		 
		// make base string
		$baseURI						=	"https://api.twitter.com/1.1/statuses/$twitter_timeline.json";
		$method							=	"GET";
		$params							=	$oauth;
		 
		$r 								=	array();
		ksort($params);
		foreach($params as $key=>$value){
			$r[] 						=	"$key=" . rawurlencode($value);
		}
		$base_info						=	$method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
		$composite_key					=	rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
		 
		// get oauth signature
		$oauth_signature 				=	base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
		$oauth['oauth_signature']		=	$oauth_signature;
		$r								=	'Authorization: OAuth ';
		$values 						=	array();
		foreach($oauth as $key=>$value){
			$values[] 					=	"$key=\"" . rawurlencode($value) . "\"";
		}
		$r 								.=	implode(', ', $values);
		 
		// get auth header
		$header 						=	array($r, 'Expect:');
		
		// set cURL options
		$options 						=	array(
			CURLOPT_HTTPHEADER 			=>	$header,
			CURLOPT_HEADER 				=>	false,
			CURLOPT_URL 				=>	"https://api.twitter.com/1.1/statuses/$twitter_timeline.json?". http_build_query($request),
			CURLOPT_RETURNTRANSFER 		=>	true,
			CURLOPT_SSL_VERIFYPEER 		=>	true
		);
		// retrieve the twitter feed
		$feed 							=	curl_init();
		curl_setopt_array($feed, $options);
		$json 							=	curl_exec($feed);
		curl_close($feed);
		// decode json format tweets
		$tweets							=	json_decode($json, true);
		
		return $tweets;
	}
	
	function time_elapsed_string($ptime)
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
		
	public function getBrowser(){
		$u_agent = $_SERVER['HTTP_USER_AGENT']; 
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
		$ub = '';
		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
		
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Internet Explorer'; 
			$ub = "MSIE"; 
		} 
		elseif(preg_match('/Firefox/i',$u_agent)) 
		{ 
			$bname = 'Mozilla Firefox'; 
			$ub = "Firefox"; 
		} 
		elseif(preg_match('/Chrome/i',$u_agent)) 
		{ 
			$bname = 'Google Chrome'; 
			$ub = "Chrome"; 
		} 
		elseif(preg_match('/Safari/i',$u_agent)) 
		{ 
			$bname = 'Apple Safari'; 
			$ub = "Safari"; 
		} 
		elseif(preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Opera'; 
			$ub = "Opera"; 
		} 
		elseif(preg_match('/Netscape/i',$u_agent)) 
		{ 
			$bname = 'Netscape'; 
			$ub = "Netscape"; 
		} 
		
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
		
		$i = count($matches['browser']);
		if ($i != 1) {
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
		
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
		
		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	}

	public function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
	
	public function get_client_location(){
		$PublicIP = $this->get_client_ip();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://freegeoip.net/json/$PublicIP");
		curl_setopt($ch, CURLOPT_HEADER, false);
		//curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$head = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($httpCode == 200){
			if(!empty($head)){
				$result1 = json_decode($head,true);
				return !empty($result1['country_code']) ? $result1['country_code'] : '';
			}
		}
		return ""; 
	}

	public function encrypt($data = ""){
		$password	=	CBC_ENCRYPT_KEY;
		$method		=	'aes-256-cbc';
		$iv			=	CBC_ENCRYPT_IV;

		// Must be exact 32 chars (256 bit)
		$password = substr(hash('sha256', $password, true), 0, 32);
		// IV must be exact 16 chars (128 bit)
		
		$encrypted = base64_encode(openssl_encrypt(json_encode($data), $method, $password, OPENSSL_RAW_DATA, $iv));
		
		/** save api response **/
		$obj1 						=  new ApiResponse;
		$obj1->transaction_type 	=  "SEND";
		$obj1->response 			=  json_encode($data);
		$obj1->time_stamp 			=  time();
		$obj1->save();
		/** save api response **/
		echo $encrypted;die;
	}	
		
	public function decrypt($data = ""){
		$password	=	CBC_ENCRYPT_KEY;
		$method		=	'aes-256-cbc';
		$iv			=	CBC_ENCRYPT_IV;
		// Must be exact 32 chars (256 bit)
		$password = substr(hash('sha256', $password, true), 0, 32);
		// IV must be exact 16 chars (128 bit)
		
		$decrypted = openssl_decrypt(base64_decode($data), $method, $password, OPENSSL_RAW_DATA, $iv);
		
		
		$receive_data	=	json_decode($decrypted,true);
		
		/** save api response **/
		$obj1 						=  new ApiResponse;
		$obj1->transaction_type 	=  "RECEIVE";
		$obj1->response 			=  json_encode($receive_data);
		$obj1->time_stamp 			=  (!empty($receive_data["time_stamp"])) ? $receive_data["time_stamp"]  : "";
		$obj1->save();
		/** save api response **/
		
		return json_decode($decrypted,true);
	}	
	
	public function change_error_msg_layout($errors = array()){
		$response				=	array();
		$response["status"]		=	"error";
		if(!empty($errors)){
			$error_msg				=	"";
			foreach($errors as $errormsg){
				$error_msg1			=	(!empty($errormsg[0])) ? $errormsg[0] : "";
				$error_msg			.=	$error_msg1.", ";
			}
			$response["message"]	=	trim($error_msg,", ");			
		}else {
			$response["message"]	=	"";			
		}
		$response["data"]			=	array();
		return $response;
	}
	
	public function random_number(){
		return rand(0000000000,9999999999);
	}
	
	public function createQRCode($qr_sring = '') {
		$data						=	 $qr_sring;
		$size						=	"200";
		$encoding					=	"UTF-8";
		$errorCorrectionLevel		=	"L";
		$marginInRows				=	"0";
		$QRLink = "https://chart.googleapis.com/chart?cht=qr&chs=".$size."x".$size."&chl=" . $data .  "&choe=" . $encoding . "&chld=" . $errorCorrectionLevel . "|" . $marginInRows; 
		return $QRLink;
		die;
	}
	
	public function save_notification($sender_id = '',$receiver_id = '',$type = '',$jsondata = ''){
		if($sender_id != $receiver_id){
			$obj 				= 	new Notification;
			$obj->sender_id 	= 	$sender_id;
			$obj->receiver_id 	= 	$receiver_id;
			$obj->type	 		= 	$type;
			$obj->jsondata 		= 	$jsondata;
			$obj->save();
			
			$messageData = $this->get_notification_message($sender_id,$receiver_id,$type ,$jsondata);
			$data 				= 	json_decode($jsondata);
			$notification_title = 	$messageData['title'];
			$message 			= 	$messageData['message'];
			$image 				= 	$messageData['image'];
			
			$userDetail = DB::table("users")->where("id",$receiver_id)->select('device_type','device_id')->first();
			$deviceToken	=	$userDetail->device_id;
			$device_type	=	$userDetail->device_type;
			$this->send_push_notification($deviceToken,$device_type,$message,$type,$data,$notification_title);
		}
	}
	
	public function get_notification_message($sender_id = 0,$receiver_id = 0,$type = '',$jsondata = ''){
		$senderData = DB::table('users')->where("id",$sender_id)->select("full_name")->first();
		$message = '';
		$title 	= '';
		$image 	= '';
		$userDetail = DB::table("users")->where("id",$sender_id)->select('image')->first();
		if(!empty($userDetail) && $userDetail->image != '' && file_exists(USER_PROFILE_IMAGE_ROOT_PATH.$userDetail->image)){
			$image		=	USER_PROFILE_IMAGE_URL.$userDetail->image;
		}else{
			$image		=	WEBSITE_IMG_URL.'usr_img.png';
		}
		if($type == 'send_friend_request'){
			$title 		= "Friend Request Received";
			$message 	= "<b>".$senderData->full_name."</b> has sent friend request.";
		}elseif($type == 'accepted_friend_request'){
			$title 		= "Friend Request Accepted";
			$message 	= "<b>".$senderData->full_name."</b> has accpeted friend request.";
		}elseif($type == 'rejected_friend_request'){
			$title 		= "Friend Request Rejected";
			$message 	= "<b>".$senderData->full_name."</b> has rejected friend request.";
		}elseif($type == 'send_follow_request'){
			$title 		= "Follow Request Received";
			$message 	= "<b>".$senderData->full_name."</b> has sent follow request.";
		}elseif($type == 'accepted_follow_request'){
			$title 		= "Friend Request Accepted";
			$message 	= "<b>".$senderData->full_name."</b> has accpeted follow request.";
		}elseif($type == 'rejected_follow_request'){
			$title 		= "Friend Request Rejected";
			$message 	= "<b>".$senderData->full_name."</b> has rejected follow request.";
		}elseif($type == 'like_on_post'){
			$title 		= "Like On Post";
			$message 	= "<b>".$senderData->full_name."</b> has liked your post.";
		}elseif($type == 'like_on_comment'){
			$title 		= "Like On Comment";
			$message 	= "<b>".$senderData->full_name."</b> has liked your comment.";
		}elseif($type == 'reply_on_comment'){
			$title 		= "Reply On Comment";
			$message 	= "<b>".$senderData->full_name."</b> has replied on your comment.";
		}elseif($type == 'comment_on_post'){
			$title 		= "Comment On Post";
			$message 	= "<b>".$senderData->full_name."</b> has commented on your post.";
		}
		return array(
			'message'	=>	$message,
			'title'		=>	$title,
			'image'		=>	$image,
		);
	}
	
	public function send_push_notification($deviceToken = "",$device_type = "",$message = "",$notification_type = "",$data = array(),$notification_title = ""){
		if($device_type == "android"){
			$server_key		=	Config::get("Site.android_sever_api_key");
			
			$registrationIds = array($deviceToken); 
			$msg = array (
				'message'	=> $message,
				'title'		=> $notification_title, 
				'vibrate'	=> 1,
				'sound'		=> 1, 
				'response_data'		=> base64_encode(json_encode($data)), 
				'notification_type'	=> $notification_type, 
			);
			$fields = array (
				'registration_ids' => $registrationIds,
				'data'	=> $msg
			);
			
			$headers = array (
				'Authorization: key=' . $server_key,
				'Content-Type: application/json'
			);
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields) );
			$result = curl_exec($ch);
			curl_close( $ch );
		}
		if($device_type == "iphone"){
			$badge 	= 0;
			$sound 	= 'chime';
			$pass 	= "123456";
			$body = array();
			$body['aps'] 								= array('alert' => $message);
			$body['aps']['url'] 						= '';
			$body['aps']['message'] 					= $message;
			$body['aps']['title'] 						= $notification_title;
			$body['aps']['type'] 						= $notification_type;
			$body['aps']['response'] 					= base64_encode(json_encode($data));
			if ($badge)
				$body['aps']['badge'] 					= $badge;
			if ($sound)
				$body['aps']['sound'] 					= $sound;
			/* End of Configurable Items */

			$ctx = stream_context_create();
			if(DEV_APP == 'dev'){
				stream_context_set_option($ctx, 'ssl', 'local_cert',app_path()."/ck_cmeshinedev.pem");
			}else{
				stream_context_set_option($ctx, 'ssl', 'local_cert',app_path()."/ck_cmeshinelive.pem");
			}
			// assume the private key passphase was removed.
			 stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
			$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
			if (!$fp) {
				return;
			} else {
				//return;
			}
			$payload = json_encode($body);
			// request one 
			$msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ',  '',$deviceToken)) . pack("n",strlen($payload)) . $payload;
			fwrite($fp, $msg);
			fclose($fp);
		}
	}
	
	public function save_notification_seting_on_signup($user_id='',$user_role_id = ''){
		$obj 							= 	new UserPrivacySetting;
		$obj->user_id					=	$user_id;
		$obj->user_role_id				=	$user_role_id;
		$obj->notification				=	1;
		$obj->posts						=	1;
		$obj->disable_sharing_content	=	1;
		$obj->update_datetime			=	date("Y-m-d H:i:s");
		$obj->save();
	}
}// end BaseController class
