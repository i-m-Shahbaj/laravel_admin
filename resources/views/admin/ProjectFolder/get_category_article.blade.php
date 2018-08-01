<h4>Category Detail</h4>
<table class="table table-hover">
<tbody>
	<?php
	if(!empty($category)){
	?>
	<tr>
		<td data-th='{{ trans("messages.$modelName.name") }}'>{{ $category->name }}</td>
		<td data-th='{{ trans("messages.$modelName.name") }}'>
			@if($category->image != '' && File::exists(PROJECT_FOLDER_IMAGE_ROOT_PATH.$category->image))
				<?php $image				=	PROJECT_FOLDER_IMAGE_URL.$category->image; ?>
				<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo $image; ?>">
					<img src="<?php echo WEBSITE_URL.'image.php?height=100px&width=100pxcropratio=1:1&image='.$image; ?>">
				</a>
			@else
				<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>">
					<img src="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>" width='100' height="100" />
				</a>
			@endif
		</td>
		<td data-th='{{ trans("messages.$modelName.name") }}'>{{ $category->total_articles }}</td>
		<td data-th='{{ trans("messages.$modelName.subject") }}'>
			{{ date(Config::get('Reading.date_format'),strtotime($category->created_at)) }} </td>
		<td data-th='{{ trans("messages.$modelName.subject") }}'> 
			@if($category->is_active)
				<label class="label label-success">Activated</label>
			@else
				<label class="label label-warning">Deactivated</label>
			@endif	
		</td>
		<?php
		/*
		<td data-th='{{ trans("messages.$modelName.action") }}'>
			@if($category->is_active == 1)
				<a  title="Click To Deactivate" href="{{route($modelName.'.status',array($category->id,0))}}" class="btn btn-success btn-small status_any_item"><span class="fa fa-ban"></span>
				</a>
			@else
				<a title="Click To Activate" href="{{route($modelName.'.status',array($category->id,1))}}" class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
				</a> 
			@endif
<!--
			<a href='{{ route("$modelName.view",array("$category->id"))}}' class="btn btn-info" title="View"> <i class="fa fa-eye"></i> </a>
-->
			
			<a href='{{ route("$modelName.edit",array("$category->id"))}}' class="btn btn-info " title="Edit"> <i class="fa fa-pencil"></i> </a>
				<a href='{{ route("ProjectFolderArticle.index",array($category->id))}}' class="btn btn-primary " title="Add Article"> <i class="fa fa-plus"></i> </a>
		</td>
		*/
		?>
	</tr>
	<?php
		}else{
	?>
		<tr>
			<td class="alignCenterClass" colspan="5" >{{ trans("messages.user_management.no_record_found_message") }}</td>
		</tr>
	<?php
		}
	?> 
	</tbody>
</table>

<h4>Articles</h4>
<table class="table table-hover">
<tbody>
				<?php
				if(!$articles->isEmpty()){
				foreach($articles as $result){?>
				<tr>
					<td data-th='{{ trans("Name") }}'>{{ $result->article_name }}</td>
					<td>
						@if($result->image != '' && File::exists(PROJECT_ARTICLE_IMAGE_ROOT_PATH.$result->image))
							<?php
								$image				=	PROJECT_ARTICLE_IMAGE_URL.$result->image;
							?>
							<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo $image; ?>">
								<img src="<?php echo WEBSITE_URL.'image.php?height=100px&width=100px&cropratio=1:1&image='.$image; ?>">
							</a>
						@else
							<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>">
								<img src="<?php echo WEBSITE_IMG_URL.'no_image.jpg'; ?>" width='100' height="100" />
							</a>
						@endif
					</td>
					<td data-th='{{ trans("Date") }}'> {{ $result->created_at }} </td>
					<td data-th='{{ trans("Status") }}'> 
						@if($result->is_active)
							<label class="label label-success">Activated</label>
						@else
							<label class="label label-warning">Deactivated</label>
						@endif	
					</td>
					<td data-th='{{ trans("Action") }}'>
						@if($result->is_active == 1)
							<a  title="Click To Deactivate" href="{{route('ProjectFolderArticle.status',array($result->id,0))}}" class="btn btn-success btn-small status_any_item"><span class="fa fa-ban"></span>
							</a>
						@else
							<a title="Click To Activate" href="{{route('ProjectFolderArticle.status',array($result->id,1))}}" class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
							</a> 
						@endif
						<a href='{{ route("ProjectFolderArticle.view",array("$project_folder_id","$result->id"))}}' class="btn btn-info" title="View"> <i class="fa fa-eye"></i> </a>
						<a href='{{ route("ProjectFolderArticle.edit",array("$project_folder_id","$result->id"))}}' class="btn btn-success " title="Edit"> <i class="fa fa-pencil"></i> </a>
						@if($result->is_check_this_out == 1)
							<a  title="Remove From Check This Out" href="{{route('ProjectFolderArticle.checkThisOut',array($result->id,0))}}" class="btn btn-danger btn-small status_any_item"><i class="fa fa-sign-in"></i>
							</a>
						@endif	
						@if($result->is_check_this_out == 0)
							<a title="Add To Check This Out" href="{{route('ProjectFolderArticle.checkThisOut',array($result->id,1))}}" class="btn btn-success btn-small status_any_item"><i class="fa fa-sign-in"></i>
							</a> 
						@endif
						
					</td>
				</tr>
				<?php
				}
					}else{
				?>
					<tr>
						<td class="alignCenterClass" colspan="5" >{{ trans("messages.user_management.no_record_found_message") }}</td>
					</tr>
				<?php
					}
				?> 
				</tbody>	
</table>