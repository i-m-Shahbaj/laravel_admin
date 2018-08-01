<?php
namespace App\Http\Controllers\Auth;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
use App\Http\Controllers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Model\User,Illuminate\Routing\Controller;
use Socialite;

class AuthController extends Controller
{
   
	public function getSlug($title, $modelName,$limit = 30){
		$slug 		= 	 substr(\Str::slug($title),0 ,$limit);
		$Model		=	"\App\Model\\$modelName";
		$slugCount 	=  count($Model::where('slug', 'regexp', "/^{$slug}(-[0-9]*)?$/i")->get());
		return ($slugCount > 0) ? $slug."-".$slugCount : $slug;
	}//end getSlug()

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
	 /**
	 * Redirect the user to the facebook authentication page.
	 *
	 * @return Response
	 */
	public function redirectToProvider($provider)
    {
		
		if(Session::has('SocialUserRole')){
			Session::forget('SocialUserRole');
		}
		
		$userRoleId			=	$provider;
		Session::put('SocialUserRole',$userRoleId);
		switch ($provider) {
			case 'facebook':
				  return Socialite::driver($provider)->fields(['first_name', 'last_name', 'email', 'gender', 'verified','birthday','address'])->redirect();
				break;
			case 'twitter':
				 return Socialite::driver($provider)->redirect();
				break;
			case 'google':
				 return Socialite::driver($provider)->redirect();
				break;
		}
    }

    /**
     * Obtain the user information from facebook.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {	
		
		$errorFacebook	=	Input::get('error');
		$errorTwitter	=	Input::get('denied');
		if($errorFacebook!='' || $errorTwitter!=''){
			//return Redirect::to('/');
		}

		$provider		=	Session::get('SocialUserRole');	
        $user 			=	Socialite::driver($provider)->user();
		$socialField	=	$provider.'_id'; 
		$first_name 	= 	'';
		$last_name 		= 	'';
		$email 			= 	'';
		$gender 		= 	'';
		
		switch($provider){
			case 'facebook':
				$full_name					=	(!empty($user->name)) ? explode(" ",$user->name) : "";
				$first_name 				= 	(!empty($full_name[0])) ? $full_name[0] : "";
				$last_name 					= 	(!empty($full_name[1])) ? $full_name[1] : "";
				$email 						= 	isset($user->email) ? $user->email  : '';
				$socialId 					= 	isset($user->id) ?  $user->id : ''; 
				$gender 					= 	isset($user->gender) ? $user->gender  : ''; 
				$profilePic 				= 	$user->avatar_original;
				break;
			case 'twitter':
				$name 							= 	explode(" ",$user->name,2);
				$first_name						=	isset($name[0]) ? $name[0] :'';
				$last_name						=	isset($name[1]) ? $name[1] :'';
				$socialId 						= 	$user->id;
				$profilePic 					= 	(!empty($user->avatar_original)) ? $user->avatar_original : ""; 
				break;
			case 'google':
				$first_name 				= 	isset($user->user['name']['givenName']) ? $user->user['name']['givenName'] : '';
				$last_name 					= 	isset($user->user['name']['familyName']) ? $user->user['name']['familyName'] : '';
				$socialId 					= 	$user->id; 
				$profilePic 				= 	$user->avatar_original; 
		}
		
		if($email!=''){		
			$emailCount	=	User::where('email',$email)->where("is_deleted",0)->count();
			if($emailCount>0){
				User::where('email',$email)->update(array("$socialField"=>$socialId));
			}
		}
		/* print_r($socialId);die; */
		$user = DB::table("users")->where($socialField,$socialId)->where("is_deleted",0)->first();
		if(!empty($user)){
			if($user->is_active == 0){
				$yourURL	=	URL::to('/');
				Session::flash("error",trans("Your account is deactivated. Please contact to admin."));
			}elseif($user->is_verified == 0){
				$yourURL	=	URL::to('/');
				Session::flash("error",trans("Your account is not verified. Please contact to admin."));
			}else{
				$userId		=	User::where($socialField,$socialId)->where("is_deleted",0)->where("is_active",1)->where("is_verified",1)->pluck("id");
				Auth::loginUsingId($userId);	
				$yourURL	=	URL::to('/');
			}
		}else{
			$yourURL	=	URL::to('/');
			Session::flash("error",trans("User not exist"));
		}
		echo ("<script>location.href='$yourURL'</script>");
    }
 
}
