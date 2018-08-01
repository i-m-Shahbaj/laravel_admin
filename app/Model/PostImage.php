<?php
namespace App\Model; 
use Eloquent;
/**
 * PostImage Model
 */
 
class PostImage extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'post_images';
	
	protected $fillable = [ 'post_id', 'image'];
 
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
 
} // end Task class
