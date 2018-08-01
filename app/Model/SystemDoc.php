<?php 
namespace App\Model; 
use Eloquent,Config;

/**
 * SystemDoc Model
 */
 
class SystemDoc extends Eloquent  {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'system_documents';
	
	/**
	* Function for get image
	*
	* @param null
	* 
	* @return query
	*/
	public function getSystemImage($id = 0){
		$image	=	SystemDoc::where('id', '=',$id)->select('name')->first();
		return isset($image->name) ? $image->name  : '';
	}//end getSystemImage()
	
	public function getAllSystemImages($ids){
		$images	=	SystemDoc::whereIn('id',$ids)->pluck('name','id');
		
		return isset($images) ? $images  : '';
	}//end getSystemImage()
	
}// end SystemDoc class
