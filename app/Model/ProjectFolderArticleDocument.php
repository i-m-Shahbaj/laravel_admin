<?php
namespace App\Model; 
use Eloquent;
/**
 * ProjectFolderArticleDocument Model
 */
 
class ProjectFolderArticleDocument extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'project_folder_article_documents';
	
	protected $fillable = ['id', 'article_name'];
 
    
} // end ProjectFolderArticleDocument class
