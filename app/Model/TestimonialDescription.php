<?php 
namespace App\Model; 
use Eloquent,DB,App;

/**
 * TestimonialDescription Model
 */
 
class TestimonialDescription extends Eloquent {

/**
 * The database table used by the model.
 *
 * @var string
 */
	protected $table = 'testimonial_descriptions';
	
	
/**
 * This function use for list all testimonials 
 *
 * @params null
 *
 * @return void
 */
	function testimonial_lists(){
		$lang			=	App::getLocale();
		$testimonials	=	DB::select( DB::raw("SELECT *,(SELECT image FROM testimonials WHERE id = testimonial_descriptions.parent_id) as image FROM testimonial_descriptions WHERE parent_id IN (select id from testimonials where is_active = 1) AND language_id = (select id from languages WHERE languages.lang_code = '$lang')"));
		
		return $testimonials;
	}
	
}// end TestimonialDescription class
