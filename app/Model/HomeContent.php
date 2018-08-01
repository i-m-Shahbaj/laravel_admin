<?php
namespace App\Model; 
use Eloquent;
/**
 * HomeContent Model
 */
 
class HomeContent extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
protected $table = 'home_contents';

	public function getHomePageContents() {
        $result = HomeContent::where('is_active',IS_ACTIVE)->orderBy('id','ASC')->get()->toArray();
		return $result;
    }// end getHomePageContents()
    
    /**
	 * Function for get HomeContent 
	 *
	 * @param $type as $type
	 * 
	 * @return query
	 */
	public function getallHomeContent(){
		$lang			=	App::getLocale();
		$allHomeContents = DB::select( DB::raw("SELECT * FROM home_contents"));
		
		$homecontents = array();
		if(!empty($allHomeContents)){
			foreach($allHomeContents as $homecontent){
				$homecontents[$homecontent->block]['description'] 	 = $homecontent->description;
				$homecontents[$homecontent->block]['image'] 		 = $homecontent->image;
				$homecontents[$homecontent->block]['type'] 	 		= $homecontent->type;
			}
		}
		return $homecontents;
	}//end getBlock()
} // end HomeContent class
