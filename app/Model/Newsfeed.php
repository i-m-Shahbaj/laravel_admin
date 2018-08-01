<?php
namespace App\Model; 
use Eloquent;
/**
 * Newsfeed Model
 */
 
class Newsfeed extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'newsfeeds';
	
	protected $fillable = ['user_id', 'name', 'description'];
 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} // end Newsfeed class
