<?php
namespace App\Model; 
use Eloquent,Auth,DB;
/**
 * ProjectArticleComment Model
 */
 
class ProjectArticleComment extends Eloquent   {
	
	/**
	 * The database collection used by the model.
	 *
	 * @var string
	 */
 
	protected $table = 'project_article_comments';
	
	function comment_reply() {
		 return $this->hasMany('App\Model\ProjectArticleCommentReply','comment_id','id')->select('project_article_comment_reply.*',DB::raw("(select full_name from users where id=project_article_comment_reply.user_id)as cmnt_rply_user_name"))->orderBy("id","ASC");
	}
    
} // end ProjectArticleComment class
