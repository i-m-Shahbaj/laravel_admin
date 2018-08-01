<?php
namespace App\Model; 
use Eloquent,DB;
/**
 * PostComment Model
 */
 
class PostComment extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'post_comments';
	
	protected $fillable = ['user_id', 'post_id', 'comment', 'is_like'];
 
    public function post(){
        return $this->belongsTo(Post::class, 'post_id');
    }
 
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
	
	public function getChildComments(){
        return $this->hasMany(PostComment::class, 'parent_id');
    }
} // end PostComment class
