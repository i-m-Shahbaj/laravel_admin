<?php
namespace App\Model; 
use Eloquent;
/**
 * PostLike Model
 */
 
class PostLike extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'post_likes';
	
	protected $fillable = ['user_id', 'post_id', 'name', 'description'];
 
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} // end Task class
