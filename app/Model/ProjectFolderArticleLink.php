<?php
namespace App\Model; 
use Eloquent;
/**
 * ProjectFolderArticleLink Model
 */
 
class ProjectFolderArticleLink extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'project_folder_article_links';
	
	protected $fillable = ['id', 'article_name'];
 
    
} // end ProjectFolderArticleLink class
