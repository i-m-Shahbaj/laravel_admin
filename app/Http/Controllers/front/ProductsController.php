<?php
/**
 * UsersController
 */
namespace App\Http\Controllers\front;
use App\Http\Controllers\BaseController;
use App\Model\User;
use App\Model\Product;
use App\Model\SystemDoc;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Block;
use App\Model\UserTransactionHistory;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\AppliedVoucher;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Request,Response,Session,URL,View,Validator,App;


class ProductsController extends BaseController {

	public function products(){
		/* Session::put("dataArray","WORKING");
		pr(Session::get("dataArray"));die; */
		$cart_products				=	Session::get('cart_products');
		pr($cart_products);die;
		$productObj 			= 	new Product();
		$limit 					= 	Config::get('Reading.records_per_page');
		$offset 				= 	0;
		$type= '';
		$productsDetail			=	$productObj->getProductDetail($limit,$offset);
		$totalProducts  		= 	$productObj->getTotalProducts();
		$category 				= 	!empty(Input::get('category')) ? Input::get('category') : '';
		$color 					= 	!empty(Input::get('color')) ? Input::get('color') : '';
		$price 					= 	!empty(Input::get('price')) ? Input::get('price') : '';
		$product_sort 			= 	!empty(Input::get('product_sort')) ? Input::get('product_sort') : '';
		$size 					= 	!empty(Input::get('size')) ? Input::get('size') : '';
		$lang					=	App::getLocale();
		$resultCat				=	DB::select( DB::raw("SELECT name,(SELECT id FROM dropdown_managers WHERE id = dropdown_manager_descriptions.parent_id) as id FROM dropdown_manager_descriptions WHERE parent_id IN (select id from dropdown_managers where is_active = 1 AND dropdown_type = 'product-category') AND language_id = (select id from languages WHERE languages.lang_code = '$lang') order by name ASC"));
		$categories			=	array();
		if(!empty($resultCat)){
			foreach($resultCat  as $cat){
				$totalCatProduct = DB::table("products")->where('is_deleted',0)->where('is_active',1)->where("category_id",$cat->id)->count();
				if(!empty($totalCatProduct)){
					$categories[$cat->id]		=	$cat->name. " <span class='brand-num'>(".$totalCatProduct.")</span>";
				}else{
					$categories[$cat->id]		=	$cat->name;
				}
				
			}
		}
		$resultSize		=	DB::select( DB::raw("SELECT name,id FROM dropdown_managers where dropdown_type='product-size' AND  is_active=1"));
		$sizes			=	array();
		if(!empty($resultSize)){
			foreach($resultSize  as $col) {
				$sizes[$col->id]			=	$col->name;
			}
		}
		$searchData = Input::all();
		$productPrice = DB::table("products")->where('is_deleted',0)->where('is_active',1)->select(DB::raw("min(price) as min_price"),DB::raw("max(price) as max_price"))->first();
		$priceArray  = array();
		if(!empty($productPrice)){
			$min_price = $productPrice->min_price;
			$max_price = $productPrice->max_price;
			$diff = round($max_price/5);
			for($i = 1;$i<=5;$i++){
				if($i ==  1){
					$min = 0;
					$max = $min+$diff;
				}else{
					$min = $max;
					$max = $min+$diff;
				}
				$totalProduct = DB::table("products")->where('is_deleted',0)->where('is_active',1)->whereBetween("price",array($min,$max))->count();
				if(!empty($totalProduct)){
					$priceArray[$min."-".$max]  = CURRENCY.$min." to ".CURRENCY.$max. " <span class='brand-num'>(".$totalProduct.")</span>";
				}else{
					$priceArray[$min."-".$max]  = CURRENCY.$min." to ".CURRENCY.$max;
				}
			}
		}
		//pr($productsDetail);die;
		return View::make('front.products.products',compact('mensWearImage','productsDetail','totalProducts','price','color','category','categories','searchData','colors','slug','product_sort','size','sizes','priceArray'));
	}//end products()
	
	public function getProduct(){
		$productObj 		= 	new Product();
		$limit  			= 	Config::get('Reading.records_per_page');
		$thisdata			=	Input::all();
		$limit  			= 	$thisdata['rowperpage'];
		$offset 			= 	$thisdata['row'];
		$category 			= 	$thisdata['category'];
		$price 				= 	$thisdata['price'];
		$product_sort 		= 	$thisdata['product_sort'];
		$size 				= 	$thisdata['size'];
		$productsDetail1	=	$productObj->getProductDetail($limit,$offset);
		$productsDetail  	=	json_encode($productsDetail1);
		$totalProducts  	= 	$productObj->getTotalProducts();
		echo $productsDetail;die;
	}//end getProduct()
	
	public function productDetail($slug = ''){
		$productObj 				= 	new Product();
		$productDetail 			= 	$productObj->productDetail($slug);
		if(empty($productDetail)){
			return Redirect::back();
		}
		$productObj 				= 	new Product();
		$productImages				=	$productObj->productImageDetail($slug);
		$product_size				=   @explode(",",$productDetail->product_size);
		$product_sizes				=	DB::table('dropdown_managers')
											->whereIn('id',$product_size)
											->select("name","id")
											->get();
		/* pr($productFullDetail);
		pr($product_sizes);die; */
		//pr($productDetail); die;
		return View::make('front.products.product_detail',compact('productDetail','productImages','product_sizes'));
	}//end productDetail()
	
	/** 
 * Function to display sending product in cart
 *
 * @param null
 * 
 * @return view page
 */	
	public function addToCart(){
		$formData					=	Input::all();
		$login_user 				=   Auth::user();
		$cart_products				=	Session::get('cart_products');
		$count = 0;
		if(!empty($cart_products)){
			foreach($cart_products as  &$record){
				if((isset($formData['id']) && !empty($formData['id'])) && !empty($formData['size'])){
					if(($formData['id'] == $record['id']) && ($formData['size'] == $record['size'])){
						$record['quantity']	=	$record['quantity'] +1;
						$count = 1; 
					}
				}
			}
		}
		if($count == 0){
			$cart_products[]		=	$formData;
		}
		Session::put('cart_products',$cart_products);
		$cart_product['quantity']		=	count(Session::get('cart_products'));
		$cart_product['error']			=	0;
		return Response::json($cart_product);
		die;
	}
	//end addToCart()
	
	public function shoppingCart(){
		return View::make('front.products.shopping_cart');
	}//end shoppingCart()
	
	public function checkout(){
		$cart_products				=	Session::get('cart_products');
		return View::make('front.products.checkout',compact('cart_products'));
	}//end checkout()
	
}// end ProductsController class
