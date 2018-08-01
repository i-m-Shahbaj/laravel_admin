<?php 
namespace App\Model; 
use App\Model\ProductDescription; 
use Eloquent,App,DB,Config;

/**
 * Plan Model
 */
class Product extends Eloquent{
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'products';
	
/**
* Function used for get product detail for mens
*
* @param $limit as limit
* 
* @return string
*/	
	public function getProductDetail($limit, $offset = 0,$category="",$price = "",$product_sort="",$size=""){
		$searchCategory 	= "";
		$searchColor 		= "";
		$searchPriceFrom 	= "";
		$searchPriceTo 		= "";
		$searchSize 		= "";
		$DB				=	Product::query();
		if(!empty($category)){
			$searchCategory  = json_decode($category);
			if(!empty($searchCategory)){
				$DB->whereIn("category_id",$searchCategory);
			}
		}
		if(!empty($size)){
			$searchSize  = json_decode($size);
			$idsArray   = array();
			if(!empty($searchSize)){
				foreach($searchSize as $col){
					$idsArray[] = DB::table("products")->where("is_deleted",0)->where("is_active",1)->whereRaw('FIND_IN_SET(\''.$col  .'\',products.product_size)')->pluck("id")->toArray();
				}
			}
			$listIds = array();
			if(!empty($idsArray)){
				foreach($idsArray as $listId){
					if(!empty($listId)){
						foreach($listId as $id){
							$listIds[$id] = $id;
						}
					}
				}
			}
			$DB->whereIn("id",$listIds);
		}
		if(!empty($price)){
			$priceData 			= explode("_",$price);
			$searchPriceFrom  	= $priceData[0];
			$searchPriceTo  	= $priceData[1];
			if($searchPriceTo == 'more'){
				$DB->where("price",">=",$searchPriceFrom);
			}else{
				$DB->where("price",">=",$searchPriceFrom)->where("price","<=",$searchPriceTo);
			}
		}
		if(!empty($product_sort)){
			if($product_sort == 'all_product'){
				$DB->where('is_deleted',0)
					->where('is_active',1)->orderBy('created_at','DESC');
			}elseif($product_sort == 'low_to_high'){
				$DB->orderBy("price",'ASC');
			}elseif($product_sort == 'high_to_low'){
				$DB->orderBy("price",'DESC');
			}elseif($product_sort == 'newest'){
				$DB->orderBy('created_at','DESC');	
			}
		}else{
			$DB->orderBy('created_at','DESC');
		}
		
		$productDetail 	= 	$DB->where('is_deleted',0)
								->where('is_active',1)
								->limit($limit)
								->offset($offset)
								->select("products.*")
								->get();
		return $productDetail;
	} //end getProductDetail()
	
/**
* Function used for get Total products for mens
* 
* @param null
* 
* @return string
*/		
	public function getTotalProducts(){
		$DB				=	Product::query();
		
		$productArray 	= 	$DB->orderBy('updated_at','DESC')
								->where('is_deleted',0)
								->where('is_active',1)
								->pluck('id','id')->toArray();
		return count($productArray);
	}//end getTotalProducts()
	
/**
* Function for get detail of product
*
* @param $slug as slug
* 
* @return array
*/
	public function productDetail($slug ){
		$lang			= 	App::getLocale();
		$productDesc 	= 	DB::table('products')
								->where('is_deleted',0)
								->where('is_active',1)
								->where('slug',$slug)
								->first();
		return $productDesc;
	}// end productDetail()

/**
* Function for get detail of product images
*
* @param $slug as slug
* 
* @return array
*/
	public function productImageDetail($slug ){
		$productImage		= DB::table('products')
								->leftJoin('product_images','product_images.product_id','=','products.id')
								->where('is_deleted',0)
								->select('product_images.image')
								->where('is_active',1)
								->where('slug',$slug)
								->get();
		return $productImage;
	}// end productImageDetail()
}
