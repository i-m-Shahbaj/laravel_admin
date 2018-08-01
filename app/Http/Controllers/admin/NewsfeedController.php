<?php
/**
 * Newsfeed Controller
 */
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Newsfeed;
use App\Model\User;
use App\Model\EventBooking;
use App\Model\AdminUser;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

class NewsfeedController extends BaseController {
/**
* $model Contact. 
*/	
	public $model	=	'Newsfeed';
/**
* Function for __construct
*
* @param null
*
* @return model name
*/	
	public function __construct() {
		View::share('modelName',$this->model);
	}
/**
* Function for display all Newsfeed
*
* @param null
*
* @return view page.
*/
	public function ListNewsfeed(){
		$DB						=	Newsfeed::query();
		$DB1					=	Newsfeed::query();
		$DB2					=	Newsfeed::query();
		$DB3					=	Newsfeed::query();
		$DB4					=	Newsfeed::query();
		$DB5					=	Newsfeed::query();
		$DB6					=	Newsfeed::query();
		$DB7					=	Newsfeed::query();
		$DB8					=	Newsfeed::query();
		$DB9					=	Newsfeed::query();
		$searchVariable			=	array(); 
		$inputGet				=	Input::get();
		if((Input::get() && isset($inputGet['display'])) || isset($inputGet['page']) ){
			$searchData				=	Input::get();
			unset($searchData['display']);
			unset($searchData['_token']);
			if(isset($searchData['order'])){
				unset($searchData['order']);
			}
			if(isset($searchData['sortBy'])){
				unset($searchData['sortBy']);
			}
			if(isset($searchData['page'])){
				unset($searchData['page']);
			}
			$date_from	=	'';
			$date_to	=	'';
			foreach($searchData as $fieldName => $fieldValue){
				if(!empty($fieldValue) || $fieldValue == 0){
					$DB->where("$fieldName",'like','%'.$fieldValue.'%');
					$searchVariable			=	array_merge($searchVariable,array($fieldName => $fieldValue));
				}
			}
		}
		$sortBy 					= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'newsfeeds.updated_at';
	    $order  					= 	(Input::get('order')) ? Input::get('order')   : 'newsfeeds.DESC';
		
		$result 					= 	$DB->leftjoin('users','users.id','=','newsfeeds.user_id')
										->select('newsfeeds.*','users.full_name as user_name')
										->where('newsfeeds.is_deleted',0)
										->orderBy($sortBy, $order)
										->paginate(Config::get("Reading.records_per_page"));
								
		$filterNewsfeeds				= 	$DB1->leftjoin('users','users.id','=','newsfeeds.user_id')
										->select('newsfeeds.*','users.full_name as user_name')
										->where('newsfeeds.is_deleted',0)
										->orderBy($sortBy, $order)
										->get();
		Session::put("filter_newsfeed_records",$filterNewsfeeds);
		
		
		$event_organiser_list		=	DB::table('users')->leftjoin('newsfeeds','newsfeeds.user_id','=','users.id')->pluck('users.full_name','newsfeeds.user_id')->toArray();
		
		$complete_string			=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string				=	http_build_query($complete_string);
		$result->appends(Input::all())->render();
		return  View::make("admin.$this->model.index",compact('result','searchVariable','sortBy','order','query_string','event_organiser_list','event_category_list','date_from','date_to','total_newsfeeds','total_free_newsfeeds','total_paid_newsfeeds','event_income'));
	}//end ListEvent
/*Function for export filtered orders*/
	
	public function export_filter_events(){ 
		$data				=	Session::get('filter_event_records');
		$thead[] = array('Event Name','Event Organiser Name','Event Location','Event Category','Event Type','Event Amount','Description','Event Start Date','Event End Date');
		 if(!empty($data)){
			foreach($data as $result){
				$name						=	!empty($result->name)?$result->name:'';
				$user_name					=	!empty($result->user_name)?$result->user_name:'';
				$location					=	ucfirst(!empty($result->location)?$result->location:'');
				$category_name				=	!empty($result->category_name)?$result->category_name:'';
				$event_type					=	ucfirst(!empty($result->event_type)?$result->event_type:'');
				$price						=	!empty($result->price)?$result->price:'';
				$description				=	!empty($result->description)?$result->description:'';
				$start_datetime				=	!empty($result->start_datetime)?$result->start_datetime:'';
				$end_datetime				=	!empty($result->end_datetime)?$result->end_datetime:'';
				
				
				$thead[] 			= array($name,$user_name,$location,$category_name,$event_type,'$'.$price,$description,$start_datetime,$end_datetime);
			}
			
					$this->get_csv($thead,'export_event_reports');
					session::forget('result');
			}else{
				Session::flash('flash_notice', 'Sorry no report found.'); 
				return Redirect::back();
		} 
		
	}//end export_filter_events()
	
/*Function for export all events*/	
	public function export_all_events(){
		$DB 					= 	Event::query();
		$all_events				= 	$DB->leftjoin('users','users.id','=','events.user_id')
										->leftjoin('dropdown_managers','dropdown_managers.id','=','events.category_id')
										->select('events.*','users.full_name as user_name','dropdown_managers.name as category_name')
										->where('events.is_deleted',0)
										->get();						
		$thead[] = array('Event Name','Event Organiser Name','Event Location','Event Category','Event Type','Event Amount','Description','Event Start Date','Event End Date');
		 if(!empty($all_events)){
			foreach($all_events as $result){
				$name						=	!empty($result->name)?$result->name:'';
				$user_name					=	!empty($result->user_name)?$result->user_name:'';
				$location					=	ucfirst(!empty($result->location)?$result->location:'');
				$category_name				=	!empty($result->category_name)?$result->category_name:'';
				$event_type					=	ucfirst(!empty($result->event_type)?$result->event_type:'');
				$price						=	!empty($result->price)?$result->price:'';
				$description				=	!empty($result->description)?$result->description:'';
				$start_datetime				=	!empty($result->start_datetime)?$result->start_datetime:'';
				$end_datetime				=	!empty($result->end_datetime)?$result->end_datetime:'';
				
				$thead[] 			= array($name,$user_name,$location,$category_name,$event_type,'$'.$price,$description,$start_datetime,$end_datetime);
			 }
			
				$this->get_csv($thead,'export_event_reports');
				session::forget('result');
			}else{
				Session::flash('flash_notice', 'Sorry no report found.'); 
				return Redirect::back();
		} 
		
	}// end export_all_orders()	

/**
* Function for add events
*
* @param null
*
* @return view page. 
*/
	public function addNewsfeed(){
		//$listCategories	=	(array) DB::select("CALL GetDropDownCategory('event-category')");
		$listCategory = array();
		if(!empty($listCategories)){
			foreach($listCategories as $listCat){
				$listCategory[$listCat->id] = $listCat->name;
			}
		}
		$ListUser	= DB::table('users')->where('is_deleted',0)->where('is_active',1)->where('is_verified',1)->where('user_role_id','!=',SUPER_ADMIN_ROLE_ID)->pluck('full_name','id')->toArray();
		return  View::make("admin.$this->model.add",compact('listCategory','ListUser'));
	}//end addNewsfeed
/**
* Function for save added Event
*
* @param null
*
* @return redirect page. 
*/
	public function saveNewsfeed(){
	Input::replace($this->arrayStripTags(Input::all()));
		$thisData			=	Input::all();
		$userId			=	!empty($thisData['user_id'])? $thisData['user_id']:ADMIN_ID;
		if(!empty($thisData)){
			$validator 					=	Validator::make(
				Input::all(),
				array(
					'name'				=> 'required',
					'description'		=> 'required',
				)
			);
			if ($validator->fails()){
				 return Redirect::back()->withErrors($validator)->withInput();
			}else{
				DB::beginTransaction();
				$obj 					=  new Newsfeed;
				$Name					=  Input::get('name');
				$obj->user_id			=  $userId;
				$obj->name				=  Input::get('name');
				$obj->description		=  Input::get('description');
				$obj->slug	 			=  $this->getSlug($Name, 'name','Newsfeed');
				$obj->is_active	 		=  1;
				$obj->save();
				DB::commit();
				return Redirect::route($this->model.".index");
			}
		}
	}//end saveEvent
/**
* Function for edit events
*
* @param null
*
* @return view page. 
*/
	public function editNewsfeed($id = 0){
		$eventDetail =  Newsfeed::find($id);
		if(empty($eventDetail)) {
			return Redirect::to('admin/newsfeed');
		}
		return View::make("admin.$this->model.edit",compact('eventDetail','listCategory','ListUser'));
	}// end editEvent
/**
* Function for update events
*
* @param null
*
* @return view page. 
*/
	public function updateNewsfeed($id){
		Input::replace($this->arrayStripTags(Input::all()));
		$thisData			=	Input::all();
		$userId			=	!empty($thisData['user_id'])? $thisData['user_id']:ADMIN_ID;
			if(!empty($thisData)){
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'name'				=> 'required',
						'description'		=> 'required',
					)
				);
			if ($validator->fails()){
				 return Redirect::back()->withErrors($validator)->withInput();
			}else{ 
					$obj 					=  Newsfeed::find($id);
					$obj->user_id			=  $userId;
					$obj->name				=  Input::get('name');
					$obj->description		=  Input::get('description');
					$obj->save();
				return Redirect::route($this->model.".index");
			}
		}
	}//end update event-manager

 /**
 * Function for change is_active of Newsfeed
 *
 * @param $Id as id of Newsfeed
 * @param $Newsfeed is_active as is_active of Newsfeed
 *
 * @return redirect page. 
 */	
	public function updateEventStatus($Id = 0, $Status = 0){
		Newsfeed::where('id', '=', $Id)->update(array('is_active' => $Status));
		Session::flash('flash_notice', trans("Status changed successfully.")); 
		return Redirect::back();
	} // end updateEventStatus()
	
/**
	 * Function for change approved status of Newsfeed 
	 *
	 * @param $Id as id of Newsfeed
	 * @param $Eventis_active as is_active of Newsfeed
	 *
	 * @return redirect page. 
	 */	
	public function updateApprovedNewsfeedStatus($Id = 0, $Status = 0){
			Newsfeed::where('id', '=', $Id)->update(array('is_approved' => $Status));
			Session::flash('flash_notice',trans("Status changed successfully."));
			return Redirect::back();
	} // end updateApprovedEventStatus()	 
/**
/**
* Function for mark a event as deleted 
*
* @param $userId as id of event
*
* @return redirect page. 
*/
	public function deleteNewsfeed($Id=0){
		$userDetails	=	Newsfeed::find($Id); 
		if(empty($userDetails)) {
			return Redirect::to('admin/event-manager');
		}
		if($Id){
			$eventModel		=	Newsfeed::where('id',$Id)->update(array('is_deleted'=>1));
		}
		return Redirect::back();
	}// end deleteNewsfeed
/**
/**
* Function for event booking
*
* @param $userId as id of event
*
* @return redirect page. 
*/
	public function eventBooking($Id=0){
		$DB 			= 	EventBooking::query();
		$eventDeatil  = 	$DB->where('event_id','=',$Id)
							->leftJoin('events','events.id','=','event_booking.event_id')
							->leftJoin('users','users.id','=','event_booking.user_id')
							->select('event_booking.*','events.name as event_name','events.location as event_location','users.full_name as user_name')
							->get()->toArray();
		return View::make("admin.$this->model.booking",compact('eventDeatil'));
	}
}// end EventController class
