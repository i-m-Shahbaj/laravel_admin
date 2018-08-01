<?php
namespace App\Model; 
use Eloquent;
/**
 * ProjectFolderArticle Model
 */
 
class ProjectFolderArticle extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'project_folder_articles';
	
	protected $fillable = ['id', 'article_name'];
 
    
	function project_article_comments() {
		 return $this->hasMany('App\Model\ProjectArticleComment','article_id','id')->select('project_article_comments.*')->orderBy("id","DESC")->limit(10);
	}
} // end ProjectFolderArticle class
