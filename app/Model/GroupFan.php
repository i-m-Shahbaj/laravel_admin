<?php
namespace App\Model; 
use Eloquent;
/**
 * GroupFan Model
 */
 
class GroupFan extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'group_fans';
	
	protected $fillable = ['user_id','group_id','fan_id', 'name'];
 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
 
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
 
    public function fan()
    {
        return $this->belongsTo(Fan::class, 'fan_id');
    }
} // end Task class
