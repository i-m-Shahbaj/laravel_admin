<?php
require_once(APP_PATH.'/mpdf/mpdf.php');
$mpdf = new Mpdf('utf-8', array(279.4, 215.90));
	$form_documents			.= '<div id="print_article" class="search_article"><h1>'.$articleData->article_name.'</h1><hr/><div class="col-sm-12">
							  <div class="col-sm-6" width="50%" style="float:left;">Project : #'.$articleData->project_number.'</div>
								<div class="col-sm-6"  width="50%" style="float:left;"><div class="pull-right">Created On: '.  (date(Config::get("Reading.date_time_format"),strtotime($articleData->created_at))).'</div></div></div>  
						  <div class="col-sm-12">
								<div class="col-sm-6" width="50%" style="float:left;">Article Id : #'.$articleData->id.'</div>
								<div class="col-sm-6"  width="50%" style="float:left;"><div class="pull-right">Updated On: '.(date(Config::get("Reading.date_time_format"),strtotime($articleData->updated_at))).'</div></div>
						  </div>
						  
						  <div class="col-sm-12">
								<div class="col-sm-6" >Authored By :'. $articleData->project_author.'</div>
						  </div><hr/>'.$articleData->article_description.'
					</div>';

$mpdf->WriteHTML($form_documents,2);
$mpdf->Output($articleData->article_name.".pdf","D");
die;
