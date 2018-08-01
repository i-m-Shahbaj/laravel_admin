<?php
namespace App\Model; 
use Eloquent;
/**
 * ProjectLibrary Model
 */
 
class ProjectLibrary extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'project_libraries';
	
	protected $fillable = ['id', 'project_name'];

	function project_folder() {
		 return $this->hasMany('App\Model\ProjectFolder','project_id','id')->where("is_active",1)->select('project_folders.*')->orderBy("category_order","ASC");
	}
	
	function project_sub_folder() {
		 return $this->hasMany('App\Model\ProjectFolder','project_id','id')->where('parent_id','!=',0)->where("is_active",1)->select('project_folders.*')->orderBy("category_order","ASC");
	}
	
	function project_articles() {
		 return $this->hasMany('App\Model\ProjectFolderArticle','project_id','id')->where("is_active",1)->select('project_folder_articals.*')->orderBy("id","ASC");
	}
    
} // end ProjectLibrary class
