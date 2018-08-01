<?php 
namespace App\Model; 
use Eloquent;

/**
 * BlockDescription Model
 */
 
class BlockDescription extends Eloquent {

	public $timestamps = false;
	
	/**
	 * The database table used by the model.
	 *
	 */
	protected $table = 'block_descriptions';
	
/**
 * This function use for get all detail for a particular block
 *
 * @params null
 *
 * @return void
 */
	public static function getBlockDetail($page_name = "",$block_name = ""){
		$lang			=	App::getLocale();
		$block_details	=	DB::select(DB::raw("SELECT *,(SELECT image FROM blocks WHERE id = block_descriptions.parent_id) as image FROM block_descriptions WHERE parent_id =(select id from blocks where is_active = 1 and page='$page_name' and block='$block_name') AND language_id = (select id from languages WHERE languages.lang_code = '$lang')"));
		return (!empty($block_details[0])) ? $block_details[0] : array();
	}
	
}// end BlockDescription class
