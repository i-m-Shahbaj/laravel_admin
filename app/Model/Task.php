<?php
namespace App\Model; 
use Eloquent;
/**
 * Task Model
 */
 
class Task extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'tasks';
	
	protected $fillable = ['user_id', 'name', 'description'];
 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} // end Task class
