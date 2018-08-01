<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\AdminUser;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;
/**
 * Ajaxdata Controller
 *
 * Add your methods in the class below
 *
 * These methods are used in ajax call
 */
 
class AjaxdataController extends BaseController {

	public function getCustomers(){
		if(Request::ajax() && Input::get()){
			$reseller_id		=	Input::get('reseller_id');
			$customer_id		=	Input::get('customer_id');
			$customers			=	DB::table('users')->where('user_role_id',CUSTOMER_ROLE_ID)->where('active',1)->where('parent_id',$reseller_id)->orderBy('full_name','ASC')->lists('full_name','id');
			if($reseller_id	!= 	"") {
				$list			=	'<select name="customer" id="customer" class="form-control valid">';
				$list			.=	'<option value="">Please Select Customer</option>';
				if(count($customers) > 0){
					foreach($customers as $k=>$v){
						if($customer_id == $k) {
							$list.= '<option selected="selected" value='.$k.'>'.$v.'</option>';
						}else {
							$list.= '<option value='.$k.'>'.$v.'</option>';
						}
					}
				}
				$list	.=	'</select>';
				echo $list;
				die;
			}else{
				echo '<select name="customer" id="customer" class="form-control valid"> <option value="">'.trans('Please Select Customer').'</option>';
				die;
			}
		}	
	}
	
	public function getServiceDetail(){
		if(Request::ajax() && Input::get()){
			$service_id				=	Input::get('service_id');
			$service_detail			=	DB::table('products')->where('id',$service_id)->first();
			//$service_detail			=	(array) $service_detail;
			echo json_encode($service_detail);
		}	
	}
	
	public function getResserDetail(){
		if(Request::ajax() && Input::get()){
			$reseller_id					=	Input::get('reseller_id');
			$reseller_details				=	DB::table('users')->where('id',$reseller_id)->first();
			echo json_encode($reseller_details);
		}	
	}
	
	public function getCustomerDetail(){
		if(Request::ajax() && Input::get()){
			$customer_id					=	Input::get('customer_id');
			$customer_id					=	DB::table('users')->where('id',$customer_id)->first();
			echo json_encode($customer_id);
		}	
	}
	
}
