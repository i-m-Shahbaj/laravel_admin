<?php
namespace App\Model; 
use Eloquent;
/**
 * ProjectFolder Model
 */
 
class ProjectFolder extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'project_folders';
	
	protected $fillable = ['id', 'name'];

 	
	function project_articles() {
		 return $this->hasMany('App\Model\ProjectFolderArticle','project_folder_id','id')->where("is_active",1)->where("is_deleted",0)->select('project_folder_articles.*')->orderBy("id","DESC");
	}
    
} // end ProjectFolder class
