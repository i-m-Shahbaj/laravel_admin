<?php
namespace App\Model; 
use Eloquent,DB;
/**
 * PostCommentLike Model
 */
 
class PostCommentLike extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'post_comment_likes';
	
	protected $fillable = [];
 
} // end PostCommentLike class
