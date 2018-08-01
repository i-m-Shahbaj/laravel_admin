<?php
namespace App\Model; 
use Eloquent;
/**
 * FriendRequest Model
 */
 
class FriendRequest extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'friend_requests';
	
	protected $fillable = ['user_id', 'name', 'description'];
 
} // end Task class
