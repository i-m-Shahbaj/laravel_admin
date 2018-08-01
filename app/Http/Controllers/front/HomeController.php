<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\BaseController;
use App\Model\Task;
use App\Model\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Response,Session,URL,View,Validator,App;

class HomeController extends BaseController
{

    public function index(){
		return view('front.Contact.index');
	}

    public function leaderboardData(){
		return view('front.Contact.home');
	}
	
    public function home(){
		return view('front.Contact.home');
	}
	
    public function Upcoming(){
		return view('front.Contact.upcoming');
	}
	
    public function Players(){
		return view('front.Contact.players');
	}
	
    public function leaderboard() {
    	return view('front.Contact.leaderboard');
    }
	
    public function MatchDetails(){
    	return view('front.Contact.match_details');
    }
    
    public function PlayersDetails(){
    	return view('front.Contact.player_details');
    }
    
    public function Gallery(){
    	return view('front.Contact.gallery');
    }
    
    public function Old(){
    	return view('front.Contact.old');
    }
    
    public function getDataleaderboard(){
		$users	= 	DB::select(DB::raw("SELECT 'id','img',(SELECT full_name FROM users where id=tasks.user_id GROUP BY tasks.user_id) as full_name,
												(SELECT image FROM users where id=tasks.user_id) as image ,
												(SELECT SUM(score) FROM tasks where user_id=tasks.user_id) as recent ,
												(SELECT SUM(score) FROM tasks where user_id=tasks.user_id) as alltime 
										from tasks"));
    	//print_r($users);die;
		//~ while($row=$sqlquery){
			//~ $lstoutput[] = $row;
		//~ }
		echo json_encode($users);
    }
	
	public function addData(){
		//$alldata = Input::all();
		$alldata = json_decode(file_get_contents("php://input"));
		$count = count($alldata); 
		$output_res = array('error' => false);
		print_r($alldata);die;
		if(!empty($alldata)){
			$obj 					= 	new Task;
			$obj->user_id 			= 	Auth::user()->id;
			$obj->name 				= 	$alldata->full_name;
			$obj->description 		= 	$alldata->description;
			if(!empty($alldata->img)){
				$image 					=	$alldata->img;
				$extension 				=	$image->getClientOriginalExtension();
				$fileName				=	time().'-user-image.'.$extension;
				if($image->move(TASK_IMAGE_ROOT_PATH, $fileName)){
					$obj->image			=	$fileName;
				}
			}
			$obj->save();
			if($obj){
				$output_res['comments'] = "($count) Items added successfully";
			}else{
				$output_res['error'] = true;
				$output_res['comments'] = "Cannot add Items";
			}
		}
		echo json_encode($output_res);
	}
	
    public function formValidationPost()
    {
    	$formData	=	Input::all();
		Validator::extend('custom_password', function($attribute, $value, $parameters) {
			if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value)) {
				return true;
			} else {
				return false;
			}
		});
			
		$validator = Validator::make(
			Input::all(),
			array(
				'first_name'					=> 'required',
				'last_name'						=> 'required',
				'email' 						=> 'required|email|unique:users',
				'mobileno' 						=> 'required|numeric',
				'password'						=> 'required|min:8|custom_password',
				'confirm_password'  			=> 'required|same:password', 
			),
			array(
				"password.custom_password"		=>	trans("Password must have be a combination of numeric and alphabets."),
				"password.required"				=>	trans("Please enter password."),
				"confirm_password.required"		=>	trans("Please enter confirm password."),
				"confirm_password.same"			=>	trans("Password and confirm password must match."),
				"password.min"					=>	trans("Password must have minimum of 8 characters."),
				"last_name.required"			=>	trans("Please enter last name."),
				"first_name.required"			=>	trans("Please enter first name."),
				"email.required"				=>	trans("Please enter email."),
				"mobileno.required"				=>	trans("Please enter mobile number."),
				"email.unique"					=>	trans("This email already exist.")
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
			$obj 								=  new User;
			$validateString						=  md5(time() . Input::get('email'));
			$obj->validate_string				=  $validateString;					
			$obj->first_name 					=  Input::get('first_name');
			$obj->last_name 					=  Input::get('last_name');
			$obj->full_name 					=  Input::get('first_name')." ".Input::get('last_name');
			$obj->email 						=  Input::get('email');
			$obj->username 						=  Input::get('email');
			$obj->slug	 						=  $this->getSlug(Input::get('first_name')." ".Input::get('last_name'),'full_name','User');
			$obj->password	 					=  Hash::make(Input::get('password'));
			$obj->user_role_id					=  3;
			$obj->is_verified					=  0; 
			$obj->is_active						=  1; 
			$obj->save();
			$userId								=	$obj->id;				
			print_r($obj);die;
			$obj->save(); 
			
			
			$userId		=	User::where("id",$userId)->pluck("id");
			//Auth::loginUsingId($userId);
			$route_url    = URL::to('send-verifylink-again/'.$obj->validate_string);
			$verification_url = "<a href='".$route_url."'>Click here</a>";
			Session::flash('login_message',  trans("Account has been registered. Verification email has been sent to your email address. ".$verification_url." to resend email.")); 
			$response	=	array(
				'success' 	=>	'1',
				'login_message' 	=>	trans("Account has been registered. Verification email has been sent to your email address.")
			); 
			return  Response::json($response); 
			die;	
		}
    }
}
