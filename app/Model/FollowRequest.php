<?php
namespace App\Model; 
use Eloquent;
/**
 * FollowRequest Model
 */
 
class FollowRequest extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'follow_requests';
	
	protected $fillable = ['user_id', 'name', 'description'];
 
} // end FollowRequest class
