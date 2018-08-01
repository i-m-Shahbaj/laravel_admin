<?php 
namespace App\Model; 
use Eloquent;
use App,DB;

/**
 * AdminBlock Model
 */
 
class Block extends Eloquent  {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'blocks';
	
	/**
	 * hasMany bind function for  AdminBlockDescription model 
	 *
	 * @param null
	 * 
	 * @return query
	 */	
	public function description() {
        return $this->hasMany('App\Model\BlockDescription','parent_id');
    }// end description()
	
	/**
	 * get_all_blocks
	 *
	 * @var string
	 */
	public function get_all_blocks($page = null) {
        $result = Block::where('is_active',IS_ACTIVE)->where('page',$page)->orderBy('block_order','ASC')->get()->toArray();
		return $result;
    }// end get_all_blocks()
	
	/**
	 * Function for get block 
	 *
	 * @param $slug as $slug
	 * 
	 * @return query
	 */
	public function getAllBlock(){
		$lang			=	App::getLocale();
		$allBlocks = DB::select( DB::raw("SELECT * FROM blocks"));
		
		$blocks = array();
		if(!empty($allBlocks)){
			foreach($allBlocks as $block){
				$blocks[$block->block]['description'] 	 = $block->description;
				$blocks[$block->block]['image'] 		 = $block->image;
				$blocks[$block->block]['block_name'] 	 = $block->block_name;
			}
		}
		return $blocks;
	}//end getBlock()
}// end AdminBlock class
