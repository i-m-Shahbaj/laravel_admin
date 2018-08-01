<?php
namespace App\Model;
use Eloquent;

use Illuminate\Notifications\Notifiable;

class Faq extends Eloquent
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public  function category(){
		return $this->belongsTo('App\Model\DropDown')->select('name','id');
	} //end category()
}	