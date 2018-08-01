<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\BaseController;
use App\Model\Product;
use App\Model\ProductDescription;
use App\Model\Category;
use App\Model\Language;
use App\Model\ProductImage;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\DropDown;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator,DateTime;

/**
 * Products Controller
 * Add your methods in the class below
 * This file will render views from views/Product
 */
class ProductsController extends BaseController {
    public $model = 'Product';
    public function __construct(){
        View::share('modelName', $this->model);
    }
/**
 * Function for display product list
 *
 * @param null
 *
 * @return view page.
 */
    public function listProducts(){
        $DB						=   Product::query();
        $DB1					=   Product::query();
        $DB2					=   Product::query();
        $DB3					=   Product::query();
        $DB4					=   Product::query();
        $DB5					=   Product::query();
        $DB6					=   Product::query();
        $searchVariable = array();
        $inputGet       = Input::get();
        if (Input::get()) {
            $searchData = Input::get();
            unset($searchData['display']);
            unset($searchData['_token']);
            if (isset($searchData['order'])) {
                unset($searchData['order']);
            }
            if (isset($searchData['sortBy'])) {
                unset($searchData['sortBy']);
            }
            if (isset($searchData['page'])) {
                unset($searchData['page']);
            }
			$date_from	=	'';
			$date_to	=	'';
			if((!empty($searchData['date_to']) && !empty($searchData['date_from']))){  
				$date_from	=	$searchData['date_from'];
				$date_to	=	$searchData['date_to'];
					$DB->whereBetween('products.created_at', [$date_from." 00:00:00", $date_to." 23:59:59"]);
					$DB2->whereBetween('products.created_at', [$date_from." 00:00:00", $date_to." 23:59:59"]);
					$DB3->whereBetween('products.created_at', [$date_from." 00:00:00", $date_to." 23:59:59"]);
					$DB4->whereBetween('products.created_at', [$date_from." 00:00:00", $date_to." 23:59:59"]);
					$DB5->whereBetween('products.created_at', [$date_from." 00:00:00", $date_to." 23:59:59"]);
					$DB6->whereBetween('products.created_at', [$date_from." 00:00:00", $date_to." 23:59:59"]);
			}else if(!empty($searchData['date_to'])){ 
				$date_to	=	$searchData['date_to'];
				$DB->whereBetween('products.created_at',	[$date_to." 00:00:00", $date_to." 23:59:59"]);
				$DB2->whereBetween('products.created_at', [$date_to." 00:00:00", $date_to." 23:59:59"]);
				$DB3->whereBetween('products.created_at', [$date_to." 00:00:00", $date_to." 23:59:59"]);
				$DB4->whereBetween('products.created_at', [$date_to." 00:00:00", $date_to." 23:59:59"]);
				$DB5->whereBetween('products.created_at', [$date_to." 00:00:00", $date_to." 23:59:59"]);
				$DB6->whereBetween('products.created_at', [$date_to." 00:00:00", $date_to." 23:59:59"]);
			}else if(!empty($searchData['date_from'])){ 
				$date_from	=	$searchData['date_from'];
				$DB->whereBetween('products.created_at',	[$date_from." 00:00:00", $date_from." 23:59:59"]);
				$DB2->whereBetween('products.created_at', [$date_from." 00:00:00", $date_from." 23:59:59"]);
				$DB3->whereBetween('products.created_at', [$date_from." 00:00:00", $date_from." 23:59:59"]);
				$DB4->whereBetween('products.created_at', [$date_from." 00:00:00", $date_from." 23:59:59"]);
				$DB5->whereBetween('products.created_at', [$date_from." 00:00:00", $date_from." 23:59:59"]);
				$DB6->whereBetween('products.created_at', [$date_from." 00:00:00", $date_from." 23:59:59"]);
			}
			unset($searchData['date_to']);
			unset($searchData['date_from']);
            foreach ($searchData as $fieldName => $fieldValue) {
				
                if (!empty($fieldValue || $fieldValue =="0")) {
						
						$DB->where("products.$fieldName", 'like', '%' . $fieldValue . '%');
						$DB2->where("products.$fieldName", 'like', '%' . $fieldValue . '%');
						$DB3->where("products.$fieldName", 'like', '%' . $fieldValue . '%');
						$DB4->where("products.$fieldName", 'like', '%' . $fieldValue . '%');
						$DB5->where("products.$fieldName", 'like', '%' . $fieldValue . '%');
                }
                $searchVariable = array_merge($searchVariable, array(
                    $fieldName => $fieldValue
                ));
            }
        }	
        $sortBy         			 = (Input::get('sortBy')) ? Input::get('sortBy') : 'products.updated_at';
        $order          			 = (Input::get('order')) ? Input::get('order') : 'DESC';
        $result         			 = $DB->where('products.is_deleted',0)
													->orderBy($sortBy, $order)
													->select("products.*")
													->paginate(Config::get("Reading.records_per_page"));
								
		$filterProducts		 		= $DB6->where('products.is_deleted',0)
													->orderBy($sortBy, $order)
													->select("products.*")
													->get();
		Session::put("filter_product_records",$filterProducts); 
        $complete_string = Input::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string = http_build_query($complete_string);
		
		// dropdown for product category
        $productCategory = (array) DB::select("CALL GetDropDownCategory('product-category')");
		$listCategory  =array();
		if(!empty($productCategory)) {
            foreach ($productCategory as $listCat) {
                $listCategory[$listCat->id] = $listCat->name;
            }
        }
        $result->appends(Input::all())->render();
		$start_date					= date("y-m-01", strtotime("-1 month")).' 00:00:00';
		$end_date					= date("y-m-31", strtotime("-1 month")).' 23:59:59';
		
	
		$last_month_products		=	$DB2->where('is_deleted',0)->whereBetween('created_at',[$start_date	,$end_date])->where('is_deleted',0)->count();
		$this_month 				=   date('Y-m-d', strtotime('last day of previous month'));
		$total_products	  			=	$DB3->where('products.is_deleted',0)->count();
		$this_month_products 		=	$DB4->where('products.created_at', '>', $this_month)->where('products.is_deleted', '=', 0)
											->count('products.id');
											
		$currentYearProducts		=	$DB5->whereBetween('created_at',[date("y-01-01").' 00:00:00',date("y-m-d").' 23:59:59'])->where('products.is_deleted',0)->count('products.id');
		//for color and size
		$sizeList	    = (array) DB::select("CALL GetDropDownCategory('product-size')");
		//$colorList		=	DB::table('colors')->where('is_active',1)->select('name','id')->get();
		$listColor		=	array();
		$listSize		=	array();
		if (!empty($colorList)) {
            foreach ($colorList as $listCat) {
                $listColor[$listCat->id] = $listCat->name;
            }
        }
		if (!empty($sizeList)) {
            foreach ($sizeList as $listCat) {
                $listSize[$listCat->id] = $listCat->name;
            }
        }		
        return View::make('admin.Product.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','listCategory',"date_from","date_to","total_products",'this_month_products','last_month_products','currentYearProducts','listSize','listColor'));
    }// end listProducts()
/*Function for export filtered Products*/
	
	public function export_filter_products(){ 
		$data				=	Session::get('filter_product_records');
		$thead[] = array('Product Name','Product Category','Product Brand','Short Description','Long Description','Commission','Commission Description','Price','Total Quantity','Remaining Quantity','Out Of Stock','Status');
		 if(!empty($data)){
			foreach($data as $result){
				$name					=	!empty($result->name)?$result->name:'';
				$category				=	!empty($result->category_id)?$result->category_id:'';
				$categoryName			=	DB::table('dropdown_managers')
												->where('id','=',$category)
												->select('dropdown_managers.name')
												->first();
			
				$description			=	!empty($result->short_description)?$result->short_description:'';
				$longDescription		=	!empty($result->long_description)?$result->long_description:'';
				$commission				=	!empty($result->commission)?$result->commission:'';
				$commissionDescription	=	!empty($result->commission_description)?$result->commission_description:'';
				$price					=	!empty($result->price)?$result->price:'';
				$quantity				=	!empty($result->quantity)?$result->quantity:'';
				$remainigQuantity		=	!empty($result->remaining_quantity)?$result->	remaining_quantity:'';
				if($result->out_of_stock == 1){
					$outOfStock		=	'Yes';
				}else{
					$outOfStock		=	'No';
				}
				if($result->is_active == 1){
					$status		=	'Activated';
				}else{
					$status		=	'Deactivated';
				}
					$thead[] 			= array($name,$categoryName->name,$description,$longDescription,$commission,$commissionDescription,'$'.$price,$quantity,$remainigQuantity,$outOfStock,$status);
			}
					$this->get_csv($thead,'export_product_reports');
					session::forget('result');
			}else{
				Session::flash('flash_notice', 'Sorry no report found.'); 
				return Redirect::to('cmeshinepanel/order');
		} 
	}//end export_filter_products()
	
/*Function for export all products*/	
	public function export_all_products(){ 
		$DB 				= 	Product::query();
		$all_products 		= 	 $DB->where('products.is_deleted',0)
								->select("products.*")
								->get();	
		$thead[] = array('Product Name','Product Category','Short Description','Long Description','Commission','Commission Description','Price','Total Quantity','Remaining Quantity','Out Of Stock','Status');
		 if(!empty($all_products)){
			foreach($all_products as $result){
				$name					=	!empty($result->name)?$result->name:'';
				$category				=	!empty($result->category_id)?$result->category_id:'';
				$categoryName			=	DB::table('dropdown_managers')
												->where('id','=',$category)
												->select('dropdown_managers.name')
												->first();
			
				$description			=	!empty($result->short_description)?$result->short_description:'';
				$longDescription		=	!empty($result->long_description)?$result->long_description:'';
				$commission				=	!empty($result->commission)?$result->commission:'';
				$commissionDescription	=	!empty($result->commission_description)?$result->commission_description:'';
				$price					=	!empty($result->price)?$result->price:'';
				$quantity				=	!empty($result->quantity)?$result->quantity:'';
				$remainigQuantity		=	!empty($result->remaining_quantity)?$result->	remaining_quantity:'';
				if($result->out_of_stock == 1){
					$outOfStock		=	'Yes';
				}else{
					$outOfStock		=	'No';
				}
				if($result->is_active == 1){
					$status		=	'Activated';
				}else{
					$status		=	'Deactivated';
				}
					$thead[] 			= array($name,$categoryName->name,$description,$longDescription,$commission,$commissionDescription,'$'.$price,$quantity,$remainigQuantity,$outOfStock,$status);
			}
					$this->get_csv($thead,'export_product_reports');
					session::forget('result');
			}else{
				Session::flash('flash_notice', 'Sorry no report found.'); 
				return Redirect::to('cmeshinepanel/order');
		} 
	}// end export_all_products()		

/**
 * Function for add product
 *
 * @param null
 *
 * @return view page.
 */
    public function addProduct(){
        $languages       = DB::select("CALL GetAcitveLanguages(1)");
        $language_code   = Config::get('default_language.language_code');
        // dropdown for product category
        $sizeList	    = (array) DB::select("CALL GetDropDownCategory('product-size')");
		//$categoryList	=	Category::where('parent_id',0)->where('is_active',1)->select('id','category_name')->get();
		//$colorList		=	DB::table('colors')->where('is_active',1)->select('name','id')->get();
		$listColor		=	array();
		$listSize		=	array();
		$listCate		=	array();
		 $listCategory  = array();

       
		if (!empty($colorList)) {
            foreach ($colorList as $listCat) {
                $listColor[$listCat->id] = $listCat->name;
            }
        }
		if (!empty($sizeList)) {
            foreach ($sizeList as $listCat) {
                $listSize[$listCat->id] = $listCat->name;
            }
        }
		$productCategory = (array) DB::select("CALL GetDropDownCategory('product-category')");
		$listCategory  =array();
		if(!empty($productCategory)) {
            foreach ($productCategory as $listCat) {
                $listCategory[$listCat->id] = $listCat->name;
            }
        }
        return View::make("admin.$this->model.add", compact('languages', 'language_code', 'listCategory','listColor','listSize'));
    }// end addProduct()
/**
 * Function for save product
 *
 * @param null
 *
 * @return redirec page.
 */
    public function saveProduct() {
        Input::replace($this->arrayStripTags(Input::all()));
        $thisData             = Input::all();
		$messages = [
						'category_id.required' => 	 'The product category field is required.',
					];
		$rules	  = [
						'main_image' 				=> 'image',
						'category_id' 				=> 'required',
						'product_description' 		=> 'required',
						'main_image' 				=> 'required|mimes:' . IMAGE_EXTENSION,
						'name' 						=> 'required',
						'product_size' 				=> 'required',
						'price' 					=> 'required|numeric|min:0'
				   ];
		$input	  =[
						'main_image' 				=> Input::file('main_image'),
						'category_id' 				=> Input::get('category_id'),
						'product_size' 				=> Input::get('product_size'),
						'price' 					=> Input::get('price'),
						'name' 						=> Input::get('name'),
						'product_description' 		=> Input::get('product_description'),
				   ];
        $validator            = Validator::make($input,$rules,$messages);
        
        if ($validator->fails()) { 
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $model = new Product;
            if (Input::hasFile('main_image')){
                $extension  = Input::file('main_image')->getClientOriginalExtension();
                $newFolder  = strtoupper(date('M') . date('Y')) . '/';
                $folderPath = PRODUCTS_IMAGE_ROOT_PATH . $newFolder;
                
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, $mode = 0777, true);
                }
                $productImageName = time() . '-product.' . $extension;
                $image            = $newFolder . $productImageName;
                if (Input::file('main_image')->move($folderPath, $productImageName)) {
                    $model->main_image = $image;
                }
            }
            $sizelData                		= 	implode(',', Input::get('product_size'));
            $Name                     		= 	Input::get('name');
            $model->name              		= 	Input::get('name');
            $model->category_id       		= 	Input::get('category_id');
            $model->product_size      		= 	$sizelData;
            $model->product_description 	= 	Input::get('product_description');
            $model->price             		= 	Input::get('price');
            $model->slug              		= 	$this->getSlug($Name, 'name', 'Product');
            $model->save();
            $modelId = $model->id;
            if (Input::hasFile('image')){
                $i = 1;
                foreach (Input::file("image") as $file) {
                    $modelProductImages = new ProductImage();
                    $extension  = $file->getClientOriginalExtension();
                    $newFolder  = strtoupper(date('M') . date('Y')) . '/';
                    $folderPath = PRODUCTS_IMAGE_ROOT_PATH . $newFolder;
                    if (!File::exists($folderPath)) {
                        File::makeDirectory($folderPath, $mode = 0777, true);
                    }
                    $productImageName = time() . $i . '-product.' . $extension;
                    $image = $newFolder . $productImageName;
                    if ($file->move($folderPath, $productImageName)) {
                        $modelProductImages->image = $image;
                    }
                    $i++;
                    $modelProductImages->product_id = $modelId;
                    $modelProductImages->save();
                }
                
            }   
            Session::flash('flash_notice', trans("Product added successfully"));
            return Redirect::route("$this->model.index");
        }
    } //end saveProduct()
/**
 * Function for update product page status
 *
 * @param $Id as id of product page
 *
 * @param $Status as status of product page
 *
 * @return redirect page. 
 */
    public function updateProductStatus($Id = 0, $Status = 0) {
        if ($Status == 0) {
            $statusMessage = trans("Product deactivated successfully.");
        }else {
            $statusMessage = trans("Product activated successfully.");
        }
        $this->_update_all_status('products', $Id, $Status);
        Session::flash('flash_notice', $statusMessage);
        return Redirect::to('cmeshinepanel/product-manager');
    }
	
	public function updateProductFeaturedStatus($Id = 0, $Status = 0) {
        if ($Status == 0) {
            $statusMessage = trans("Product remove from featured successfully.");
        }else {
            $statusMessage = trans("Product mark as featured successfully.");
        }
		DB::table("products")->where('id',$Id)->update(array("is_featured"=>$Status));
        Session::flash('flash_notice', $statusMessage);
        return Redirect::to('cmeshinepanel/product-manager');
    }
/**
 * Function for edit product
 *
 * @param null
 *
 * @return view page.
 */
    public function editProduct($modelId) {
        $productDetail 		= Product::where('products.id', '=',  $modelId)
								->first();
		//Product::find($modelId)->with('get_size_name')->first();
		//pr($productDetail);die;
		$imageDetails 		= ProductImage::where('product_id', $modelId)->select("image","id")->get();						  
		if (empty($productDetail)) {
            return Redirect::to('cmeshinepanel/product-manager');
        }
		// dropdown for product category
        $sizeList	    = (array) DB::select("CALL GetDropDownCategory('product-size')");
		//$categoryList	=	Category::where('parent_id',0)->where('is_active',1)->select('id','category_name')->get();
		//$colorList		=	DB::table('colors')->where('is_active',1)->select('name','id')->get();
		$listColor		=	array();
		$listSize		=	array();
		$listCate		=	array();
		$listCategory  = array();
		if (!empty($colorList)) {
            foreach ($colorList as $listCat) {
                $listColor[$listCat->id] = $listCat->name;
            }
        }
		if (!empty($sizeList)) {
            foreach ($sizeList as $listCat) {
                $listSize[$listCat->id] = $listCat->name;
            }
        }
		$productCategory = (array) DB::select("CALL GetDropDownCategory('product-category')");
		$listCategory  =array();
		if(!empty($productCategory)) {
            foreach ($productCategory as $listCat) {
                $listCategory[$listCat->id] = $listCat->name;
            }
        }
		
		$model = Product::findorFail($modelId);
        if (empty($model)) {
            return Redirect::to('cmeshinepanel/product-manager');
        }
        return View::make("admin.$this->model.edit", compact('model', 'listCategory', 'listColor', 'ingredientsCategory', 'productDetail', 'listSize', 'imageDetails'));
    }// end editBlock()
/**
 * Function for save edit product
 *
 * @param null
 *
 * @return redirec page.
 */
    public function updateProduct($modelId) {  
        Input::replace($this->arrayStripTags(Input::all()));
        $this_data = Input::all();
        
        $messages  = [
						'category_id.required' => 	 'The product category field is required.',
					 ];
        $validator = Validator::make(array(
            'main_image' 				=> Input::file('main_image'),
            'category_id' 				=> Input::get('category_id'),
            'product_size' 				=> Input::get('product_size'),
            'price' 					=> Input::get('price'),
            'name' 						=> Input::get('name'),
            'product_description' 		=> Input::get('product_description'),
        ), array(
            //'main_image' 				=> 'image',
            'category_id' 				=> 'required',
            'product_size' 				=> 'required',
           // 'main_image' 				=> 'mimes:' . IMAGE_EXTENSION,
            'name'						=> 'required',
            'product_description' 		=> 'required',
            'price' 					=> 'required|numeric|min:0',
        ), $messages
		);
   
        if ($validator->fails()) {  
            return Redirect::back()->withErrors($validator)->withInput();
        } else {  
            $model = Product::findorFail($modelId);
            if (Input::hasFile('main_image')) {
                $image = Product::where('id', $modelId)->pluck('main_image');
                @unlink(PRODUCTS_IMAGE_ROOT_PATH . $image);
                $extension  = Input::file('main_image')->getClientOriginalExtension();
                $newFolder  = strtoupper(date('M') . date('Y')) . '/';
                $folderPath = PRODUCTS_IMAGE_ROOT_PATH . $newFolder;
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, $mode = 0777, true);
                }
                $productImageName = time() . '-product.' . $extension;
                $image            = $newFolder . $productImageName;
                if (Input::file('main_image')->move($folderPath, $productImageName)) {
                   
				   $model->main_image = $image;
                }
            }
            $sizeData                 	= implode(',', Input::get('product_size'));
            $model->name              	= Input::get('name');
            $model->category_id      	= Input::get('category_id');
            $model->product_size      	= $sizeData;
            $model->price             	= Input::get('price');
            $model->product_description = Input::get('product_description');
            $model->save();
			
            if (Input::hasFile('image')) {
                $i                  = 1;
                $modelProductImages = new ProductImage();
                $image              = ProductImage::where('product_id', $modelId)->select("image")->get();
                foreach ($image as $files) {
                    @unlink(PRODUCTS_IMAGE_ROOT_PATH . $image);
                    
                }
                foreach (Input::file("image") as $file) {
                    $modelProductImages = new ProductImage();
                    $extension          = $file->getClientOriginalExtension();
                    $newFolder          = strtoupper(date('M') . date('Y')) . '/';
                    $folderPath         = PRODUCTS_IMAGE_ROOT_PATH . $newFolder;
                    if (!File::exists($folderPath)) {
                        File::makeDirectory($folderPath, $mode = 0777, true);
                    }
                    $productImageName = time() . $i . '-product.' . $extension;
                    $image = $newFolder . $productImageName;
                    if ($file->move($folderPath, $productImageName)) {
                        $modelProductImages->image = $image;
                    }
                    $i++;
                    $modelProductImages->product_id = $modelId;
                    $modelProductImages->save();
                }
            }
			Session::flash('flash_notice', trans("Product updated successfully"));
            return Redirect::route("$this->model.index");
        }
    }// end updateProduct
/**
 * Function for delete Product 
 *
 * @param $modelId as id of Product 
 *
 * @return redirect page. 
 */	
	public function deleteProduct($Id	=	0) {
		$userDetails	=	Product::find($Id); 
		if(empty($userDetails)) {
			return Redirect::to('cmeshinepanel/product-manager');
		}
		if($Id){
			Product::where('id',$Id)->update(array('is_deleted'=>1,'deleted_at'=>date("Y-m-d h:i:s")
			));
		}
		 Session::flash('flash_notice', trans("Product deleted successfully"));
		return Redirect::to('cmeshinepanel/product-manager');
	}// end deleteProduct()
/**
 * Function for remove image
 *
 * @param null 
 *
 * @return redirect page. 
 */	
	public function removeImage(){
		$id = Input::get('id');
		$image = ProductImage::where('id', $id)->pluck('image');
        @unlink(PRODUCTS_IMAGE_ROOT_PATH . $image);
		DB::table('product_images')->where('id', '=', $id)->delete();
		die;
	}// end removeImage()
/**
 * Function for display Product detail
 *
 * @param $modelId as id of blog
 *
 * @return view page. 
 */

    public function viewProduct($modelId = 0){
		
		$productDetail		=	Product::where('products.id', '=',  $modelId)
								->leftJoin('dropdown_managers as dropwonManager', 'products.category_id', '=', 'dropwonManager.id')
								->select("products.*", 'dropwonManager.name as category_name')
								->first();
		//pr($productDetail);die;
		$imageDetails 		= 	ProductImage::where('product_id', $modelId)->select("image","id")->get();		

		if(empty($productDetail)) {
			return Redirect::to('cmeshinepanel/product-manager');
		}
		 $sizeList	    = (array) DB::select("CALL GetDropDownCategory('product-size')");
		$productCategory = (array) DB::select("CALL GetDropDownCategory('product-category')");
		//$colorList		=	DB::table('colors')->where('is_active',1)->select('name','id')->get();
		$listColor		=	array();
		$listSize		=	array();
		$listCate		=	array();
		 $listCategory  = array();

       
		if (!empty($colorList)) {
            foreach ($colorList as $listCat) {
                $listColor[$listCat->id] = $listCat->name;
            }
        }
		if (!empty($sizeList)) {
            foreach ($sizeList as $listCat) {
                $listSize[$listCat->id] = $listCat->name;
            }
        }
		if (!empty($productCategory)) {
            foreach ($productCategory as $listCat) {
                $listCategory[$listCat->id] = $listCat->name;
            }
        }		
		return View::make("admin.$this->model.view", compact('productDetail','listCategory','imageDetails','listSize','listColor','productAttributes'));
	}// end viewProduct()
	
	public function addMoreProduct(){
		$counter 			=	input::get('counter');
		
		$id 				=	input::get('id');		
		$languages       	=   DB::select("CALL GetAcitveLanguages(1)");
        $language_code   	=   Config::get('default_language.language_code');
        // dropdown for product category
        $sizeList	   		=  (array) DB::select("CALL GetDropDownCategory('product-size')");
		$categoryList		=	Category::where('parent_id',0)->where('is_active',1)->select('id','category_name')->get();
		$colorList			=	DB::table('colors')->where('is_active',1)->select('name','id')->get();
		$listColor			=	array();
		$listSize			=	array();
		if (!empty($colorList)) {
            foreach ($colorList as $listCat) {
                $listColor[$listCat->id] = $listCat->name;
            }
        }
		if (!empty($sizeList)) {
            foreach ($sizeList as $listCat) {
                $listSize[$listCat->id] = $listCat->name;
            }
        }
		return  View::make('admin.Product.add_more_product',compact('counter','id','listColor','listSize'));
	 }
	 public function removeproductAttributes(){
		$id  = Input::get('id'); 
		DB::table('product_quantities')->where('id', '=', $id)->delete();
		echo "success";die;
	 }
	
}// end ProductsController()
