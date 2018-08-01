<?php

namespace App\Http\Middleware;

use Closure;
Use Auth,Redirect,Input,Config,DB;

class MobileAuthenticated 
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
		return $next($request);
		
		/* $request_data	=	(!empty(Input::get("request"))) ? Input::get("request")  : "";
		$time_stamp		=	(!empty(Input::get("time_stamp"))) ? Input::get("time_stamp")  : "";
		if($request_data != "" && $time_stamp != ""){
			$request	=	$this->decrypt($request_data);
			$encrypt_time_stamp	=	(!empty($request["time_stamp"])) ? $request["time_stamp"] : "";
			if($encrypt_time_stamp != $time_stamp){
				$response				=	array();
				$response["status"]		=	"error";
				$response["message"]	=	"Invalid Request.";
				$response["data"]		=	array();
				echo  $this->encrypt($response);die;
			}else{
				$exists_record		=	DB::table("api_responses")->where("transaction_type","RECEIVE")->where("time_stamp",$time_stamp)->select("id")->first();
				if(empty($exists_record)){
					return $next($request);
				}else {
					$response				=	array();
					$response["status"]		=	"error";
					$response["message"]	=	"Invalid Request.";
					$response["data"]		=	array();
					echo  $this->encrypt($response);die;
				}
			}
		}else {
			$response				=	array();
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
			echo  $this->encrypt($response);die;
		} */
    }
	
	
	
	
	public function encrypt($data = ""){
		$password	=	CBC_ENCRYPT_KEY;
		$method		=	'aes-256-cbc';
		$iv			=	CBC_ENCRYPT_IV;

		// Must be exact 32 chars (256 bit)
		$password = substr(hash('sha256', $password, true), 0, 32);
		// IV must be exact 16 chars (128 bit)
		
		$encrypted = base64_encode(openssl_encrypt(json_encode($data), $method, $password, OPENSSL_RAW_DATA, $iv));
		return $encrypted;	
	}	
		
	public function decrypt($data = ""){
		$password	=	CBC_ENCRYPT_KEY;
		$method		=	'aes-256-cbc';
		$iv			=	CBC_ENCRYPT_IV;
		// Must be exact 32 chars (256 bit)
		$password = substr(hash('sha256', $password, true), 0, 32);
		// IV must be exact 16 chars (128 bit)
		
		$decrypted = openssl_decrypt(base64_decode($data), $method, $password, OPENSSL_RAW_DATA, $iv);
		return json_decode($decrypted,true);
	}
}
