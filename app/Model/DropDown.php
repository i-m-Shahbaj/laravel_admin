<?php 
namespace App\Model; 
use Eloquent,Session;

/**
 * DropDown Model
*/
 
class DropDown extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	*/
 
	protected $table = 'dropdown_managers';
 
	/** 
	 * Function to get all faq that are belongs to faq category()
	 *
	 * @param null
	 * 
	 * @return query 
	*/
 	public  function faq()
    { 
        return $this->hasMany('App\Model\Faq','category_id')
			->select('id','category_id','is_active')
			->where('is_active',1)
			->with('description');
    } //end faq()

	/** 
	 * Function to get all description accoding to language
	 *
	 * @param null
	 * 
	 * @return query 
	*/
	public function description(){
		$currentLanguageId	=	Session::get('currentLanguageId');
		return $this->hasOne('App\Model\DropDownDescription','parent_id')->select('parent_id','name')->where('language_id' , $currentLanguageId);
	}//end description()
	
    /** 
	 * Function to get_dropdown_by_type
	 *
	 * @param $dropdownType
	 * 
	 * @return query 
	*/
	public function get_dropdown_by_type($dropdownType = null){
		$result = DropDown::where('dropdown_type',$dropdownType)->where('is_active',IS_ACTIVE)->orderBy('id','ASC')->lists('name','slug')->toArray();
		return $result;
	}//end get_dropdown_by_type()
	
	/** 
	 * Function to get_dropdown_by_slug
	 *
	 * @param $dropdownType
	 * 
	 * @return query 
	*/
	public function get_dropdown_by_slug($slug = null){
		$result = DropDown::where('slug',$slug)->where('is_active',IS_ACTIVE)->first();
		return $result;
	}//end get_dropdown_by_slug()
	
	/** 
	 * Function to get_dropdown_by_type_with_select
	 *
	 * @param $dropdownType
	 * 
	 * @return query 
	*/
	public function get_dropdown_by_type_with_select($dropdownType = null,$field1 = null,$field2 = null){
		$result = DropDown::where('dropdown_type',$dropdownType)->where('is_active',IS_ACTIVE)->orderBy('id','ASC')->lists($field1,$field2)->toArray();
		return $result;
	}//end get_dropdown_by_type_with_select()
	
	/** 
	 * Function to get_dropdown_in_toarray
	 *
	 * @param $dropdownType
	 * 
	 * @return query 
	*/
	public function get_dropdown_in_toarray($dropdownType = null,$field = null){
		$result = DropDown::where('dropdown_type',$dropdownType)->where('is_active',IS_ACTIVE)->orderBy('name','ASC')->select($field)->get()->toArray();
		$job_data 									= 	array();
		if(!empty($result)){
			foreach($result as $key=>$value){
				$k_data 							= 	explode(',',$value['name']);
				foreach($k_data as $k_datas){
					if($k_datas != ''){
						$job_data[] 				= 	$k_datas;
					}
				}
			}
		}
		
		return json_encode($job_data);
	}//end get_dropdown_in_toarray()	
}// end DropDown class
