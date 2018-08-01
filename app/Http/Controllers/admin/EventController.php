<?php
/**
 * Event Controller
 */
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Event;
use App\Model\User;
use App\Model\EventBooking;
use App\Model\AdminUser;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator;

class EventController extends BaseController {
/**
* $model Contact. 
*/	
	public $model	=	'Event';
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
* Function for display all event
*
* @param null
*
* @return view page.
*/
	public function ListEvent(){
		$DB						=	Event::query();
		$DB1					=	Event::query();
		$DB2					=	Event::query();
		$DB3					=	Event::query();
		$DB4					=	Event::query();
		$DB5					=	Event::query();
		$DB6					=	Event::query();
		$DB7					=	Event::query();
		$DB8					=	Event::query();
		$DB9					=	Event::query();
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
			
			if((!empty($searchData['date_to']) && !empty($searchData['date_from']))){
				$date_from	=	$searchData['date_from'];
				$date_to	=	$searchData['date_to'];
					$DB->where("events.start_datetime" , '>=' ,$date_from.' 00:00:00');
					$DB->where("events.end_datetime", "<",$date_to.' 23:59:59');
					$DB1->where("events.start_datetime" , '>=' ,$date_from.' 00:00:00');
					$DB1->where("events.end_datetime", "<",$date_to.' 23:59:59');
					$DB2->where("events.end_datetime", "<",$date_to.' 23:59:59');
					$DB2->where("events.end_datetime", "<",$date_to.' 23:59:59');
					$DB3->where("events.end_datetime", "<",$date_to.' 23:59:59');
					$DB3->where("events.end_datetime", "<",$date_to.' 23:59:59');
					$DB4->where("events.end_datetime", "<",$date_to.' 23:59:59');
					$DB4->where("events.end_datetime", "<",$date_to.' 23:59:59');
				
					
			}else if(!empty($searchData['date_to'])){
				$date_to	=	$searchData['date_to'];
				$DB->whereBetween('events.end_datetime',	[$date_to." 00:00:00", $date_to." 23:59:59"]); 
				$DB1->whereBetween('events.end_datetime', [$date_to." 00:00:00", $date_to." 23:59:59"]); 
				$DB2->whereBetween('events.end_datetime', [$date_to." 00:00:00", $date_to." 23:59:59"]); 
				$DB3->whereBetween('events.end_datetime', [$date_to." 00:00:00", $date_to." 23:59:59"]); 
				$DB4->whereBetween('events.end_datetime', [$date_to." 00:00:00", $date_to." 23:59:59"]); 
				$DB5->whereBetween('events.end_datetime', [$date_to." 00:00:00", $date_to." 23:59:59"]); 
				$DB6->whereBetween('events.end_datetime', [$date_to." 00:00:00", $date_to." 23:59:59"]); 
				$DB7->whereBetween('events.end_datetime', [$date_to." 00:00:00", $date_to." 23:59:59"]); 
				$DB8->whereBetween('events.end_datetime', [$date_to." 00:00:00", $date_to." 23:59:59"]); 
				$DB9->whereBetween('events.end_datetime', [$date_to." 00:00:00", $date_to." 23:59:59"]); 
			}else if(!empty($searchData['date_from'])){
				$date_from	=	$searchData['date_from'];
				$DB->whereBetween('events.start_datetime',	[$date_from." 00:00:00", $date_from." 23:59:59"]); 
				$DB1->whereBetween('events.start_datetime', [$date_from." 00:00:00", $date_from." 23:59:59"]); 
				$DB2->whereBetween('events.start_datetime', [$date_from." 00:00:00", $date_from." 23:59:59"]); 
				$DB3->whereBetween('events.start_datetime', [$date_from." 00:00:00", $date_from." 23:59:59"]); 
				$DB4->whereBetween('events.start_datetime', [$date_from." 00:00:00", $date_from." 23:59:59"]); 
				$DB5->whereBetween('events.start_datetime', [$date_from." 00:00:00", $date_from." 23:59:59"]); 
				$DB6->whereBetween('events.start_datetime', [$date_from." 00:00:00", $date_from." 23:59:59"]);
				$DB7->whereBetween('events.start_datetime', [$date_from." 00:00:00", $date_from." 23:59:59"]);
				$DB8->whereBetween('events.start_datetime', [$date_from." 00:00:00", $date_from." 23:59:59"]);
				$DB9->whereBetween('events.start_datetime', [$date_from." 00:00:00", $date_from." 23:59:59"]);
			}
			unset($searchData['date_to']);
			unset($searchData['date_from']);
			
			foreach($searchData as $fieldName => $fieldValue){
				if(!empty($fieldValue)){
					$DB->where("events.$fieldName",'like','%'.$fieldValue.'%');
					$DB1->where("events.$fieldName",'like','%'.$fieldValue.'%');
					$DB2->where("events.$fieldName",'like','%'.$fieldValue.'%');
					$DB3->where("events.$fieldName",'like','%'.$fieldValue.'%');
					$DB4->where("events.$fieldName",'like','%'.$fieldValue.'%');
					$DB5->where("events.$fieldName",'like','%'.$fieldValue.'%');
					$DB6->where("events.$fieldName",'like','%'.$fieldValue.'%');
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		$sortBy 					= 	(Input::get('sortBy')) ? Input::get('sortBy') : 'events.updated_at';
	    $order  					= 	(Input::get('order')) ? Input::get('order')   : 'events.DESC';
		
		$result 					= 	$DB->leftjoin('users','users.id','=','events.user_id')
										->select('events.*','users.full_name as user_name')
										->where('events.is_deleted',0)
										->orderBy($sortBy, $order)
										->paginate(Config::get("Reading.records_per_page"));
								
		$filterEvents				= 	$DB1->leftjoin('users','users.id','=','events.user_id')
										->select('events.*','users.full_name as user_name')
										->where('events.is_deleted',0)
										->orderBy($sortBy, $order)
										->get();
		Session::put("filter_event_records",$filterEvents);
		
		
		$event_organiser_list		=	DB::table('users')->leftjoin('events','events.user_id','=','users.id')->pluck('users.full_name','events.user_id')->toArray();
		
		$complete_string			=	Input::query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string				=	http_build_query($complete_string);
		$result->appends(Input::all())->render();
		return  View::make("admin.$this->model.index",compact('result','searchVariable','sortBy','order','query_string','event_organiser_list','event_category_list','date_from','date_to','total_events','total_free_events','total_paid_events','event_income'));
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
	public function addEvent(){
		//$listCategories	=	(array) DB::select("CALL GetDropDownCategory('event-category')");
		$listCategory = array();
		if(!empty($listCategories)){
			foreach($listCategories as $listCat){
				$listCategory[$listCat->id] = $listCat->name;
			}
		}
		$ListUser	= DB::table('users')->where('is_deleted',0)->where('is_active',1)->where('is_verified',1)->where('user_role_id','!=',SUPER_ADMIN_ROLE_ID)->pluck('full_name','id')->toArray();
		return  View::make("admin.$this->model.add",compact('listCategory','ListUser'));
	}//end addEvent
/**
* Function for save added Event
*
* @param null
*
* @return redirect page. 
*/
	public function saveEvent(){
	Input::replace($this->arrayStripTags(Input::all()));
		$thisData			=	Input::all();
		$userId			=	!empty($thisData['user_id'])? $thisData['user_id']:ADMIN_ID;
		if(!empty($thisData)){
			$validator 					=	Validator::make(
				Input::all(),
				array(
					'name'				=> 'required',
					'location'			=> 'required',
					'description'		=> 'required',
					'category_id' 		=> 'required',
					'event_type'		=> 'required',
					'start_datetime'	=> 'required',
					'end_datetime'		=> 'required',
				)
			);
			if($thisData['event_type'] == "paid"){ 
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'price'				=> 'required',
						'currency'			=> 'required',
						'name'				=> 'required',
						'location'			=> 'required',
						'description'		=> 'required',
						'category_id' 		=> 'required',
						'event_type'		=> 'required',
						'start_datetime'	=> 'required',
						'end_datetime'		=> 'required',
					)
				);
			}
			if ($validator->fails()){
				 return Redirect::back()->withErrors($validator)->withInput();
			}else{
				DB::beginTransaction();
				$obj 					=  new Event;
				$Name					=  Input::get('name');
				$obj->category_id		=  Input::get('category_id');
				$obj->user_id			=  $userId;
				$obj->name				=  Input::get('name');
				$obj->location			=  Input::get('location');
				$obj->start_datetime	=  Input::get('start_datetime');
				$obj->end_datetime		=  Input::get('end_datetime');
				$obj->description		=  Input::get('description');
				$obj->event_type		=  Input::get('event_type');
				$obj->price    			=  Input::get('price');
				$obj->currency    		=  Input::get('currency');
				$obj->slug	 			=  $this->getSlug($Name, 'name','Event');
				$obj->is_active	 		=  1;
				$obj->is_approved	 	=  1;
				$obj->save();
				DB::commit();
				return Redirect::back();
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
	public function editEvent($id = 0){
		$eventDetail =  Event::find($id);
		if(empty($eventDetail)) {
			return Redirect::to('admin/event-manager');
		}
		$listCategories	=	(array) DB::select("CALL GetDropDownCategory('event-category')");
		$listCategory = array();
		if(!empty($listCategories)){
			foreach($listCategories as $listCat){
				$listCategory[$listCat->id] = $listCat->name;
			}
		}
		$ListUser	= DB::table('users')->where('is_deleted',0)->where('is_active',1)->where('is_verified',1)->where('user_role_id','!=',SUPER_ADMIN_ROLE_ID)-> lists('full_name','id');
		return View::make("admin.$this->model.edit",compact('eventDetail','listCategory','ListUser'));
	}// end editEvent
/**
* Function for update events
*
* @param null
*
* @return view page. 
*/
	public function updateEvent($id){
		Input::replace($this->arrayStripTags(Input::all()));
		$thisData			=	Input::all();
		$userId			=	!empty($thisData['user_id'])? $thisData['user_id']:ADMIN_ID;
			if(!empty($thisData)){
				$validator 					=	Validator::make(
					Input::all(),
					array(
						'name'				=> 'required',
						'location'			=> 'required',
						'description'		=> 'required',
						'category_id' 		=> 'required',
						'event_type'		=> 'required',
						'start_datetime'	=> 'required',
						'end_datetime'		=> 'required',
					)
				);
				if(!empty($thisData['event_type'])){
					if($thisData['event_type'] == "paid"){ 
						$validator 					=	Validator::make(
							Input::all(),
							array(
								'price'				=> 'required',
								'name'				=> 'required',
								'location'			=> 'required',
								'description'		=> 'required',
								'category_id' 		=> 'required',
								'event_type'		=> 'required',
								'start_datetime'	=> 'required',
								'end_datetime'		=> 'required',
								'currency'			=> 'required',
							)
						);
					}
				}
			if ($validator->fails()){
				 return Redirect::back()->withErrors($validator)->withInput();
			}else{ 
				DB::beginTransaction();
					$obj 					=  Event::find($id);
					$obj->category_id		=  Input::get('category_id');
					$obj->user_id			=  $userId;
					$obj->name				=  Input::get('name');
					$obj->location			=  Input::get('location');
					$obj->description		=  Input::get('description');
					$obj->start_datetime	=  Input::get('start_datetime');
					$obj->end_datetime		=  Input::get('end_datetime');
					$obj->event_type		=  Input::get('event_type');
					$obj->price    			=  Input::get('price');
					$obj->currency    		=  Input::get('currency');
					$obj->save();
				DB::commit();
				return Redirect::back();
			}
		}
	}//end update event-manager

 /**
 * Function for change is_active of Event
 *
 * @param $Id as id of Event
 * @param $Event is_active as is_active of Event
 *
 * @return redirect page. 
 */	
	public function updateEventStatus($Id = 0, $Status = 0){
		Event::where('id', '=', $Id)->update(array('is_active' => $Status));
		Session::flash('flash_notice', trans("Status changed successfully.")); 
		return Redirect::back();
	} // end updateEventStatus()
	
/**
	 * Function for change approved status of Event 
	 *
	 * @param $Id as id of Event
	 * @param $Eventis_active as is_active of Event
	 *
	 * @return redirect page. 
	 */	
	public function updateApprovedEventStatus($Id = 0, $Status = 0){
			Event::where('id', '=', $Id)->update(array('is_approved' => $Status));
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
	public function deleteEvent($Id=0){
		$userDetails	=	Event::find($Id); 
		if(empty($userDetails)) {
			return Redirect::to('admin/event-manager');
		}
		if($Id){
			$eventModel		=	Event::where('id',$Id)->update(array('is_deleted'=>1));
		}
		return Redirect::back();
	}// end deleteEvent
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
