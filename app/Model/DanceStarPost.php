<?php
namespace App\Model; 
use Eloquent;
/**
 * DanceStarPost Model
 */
 
class DanceStarPost extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'posts';
	
	protected $fillable = ['user_id', 'name', 'description'];
 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} // end Task class
