<?php
namespace App\Model; 
use Eloquent;
/**
 * Event Model
 */
 
class Event extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'events';
	
	protected $fillable = ['user_id', 'name', 'description'];
 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} // end Event class
