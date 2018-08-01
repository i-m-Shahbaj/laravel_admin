<?php
namespace App\Model; 
use Eloquent;
/**
 * Group Model
 */
 
class Group extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'groups';
	
	protected $fillable = ['user_id', 'name'];
 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} // end Task class
