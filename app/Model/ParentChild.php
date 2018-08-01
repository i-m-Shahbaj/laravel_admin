<?php
namespace App\Model; 

use Eloquent;

class ParentChild extends Eloquent {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	protected $table = 'parent_childs';
	
	protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
	  /* Scope Function 
	 *
	 * @param null 
	 *
	 * return query
	 */
 
  
}
