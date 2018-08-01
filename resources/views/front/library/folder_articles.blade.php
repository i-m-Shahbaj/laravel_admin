@extends('front.layouts.default')
@section('content')
<style>
html { overflow:auto};
.text-yellow{
	color: #ee951e;
}
h5.text-yellow {
	color: #999999;
	font-size: 12px;
	font-weight: normal;
	margin-top: 6px;
}
</style>
 {{ Form::open(['role' => 'form','url' => "search-library",'id'=>'search_form','method'=>'get']) }}
<div id="pagepiling">
  <div class="section cms-wrapper without-border" id="section1">
    <div class="container">
      <div class="breadcrumb-wrapper">
        <div class="row">
          <div class="col-sm-12 col-md-12">
            <ol class="breadcrumb">
              <li><a href="{{URL::to('dashboard')}}">Home</a></li>
              <li><a href="{{ route('Library.index') }}">Blog</a></li>
              <li class="active">{{$folderData->name}}</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
		<div class="folder_article_head">
			<div class="row">
				<div class="col-sm-6 col-md-6">
				  <h1 class="inner-folder-title">{{$folderData->name}}</h1>
				</div>
				<div class="col-sm-6 col-md-6">
					<div class="search-box-wrapper">
						<div class="search-box">
							<div class="form-group">
							  <input name="keyword" data-url="{{URL::to('search-library')}}" type="search" class="form-control search-bar valid" placeholder="Search"  value="<?php if(isset($keyword) && !empty($searchVariable['keyword'])){ echo $searchVariable['keyword']; }?>"/>
							  <span><i class="fa fa-search" aria-hidden="true"></i></span> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
    <div class="container">
      <?php /*?><div class="row">
			<div class="col-md-12">
				<div class="folder-section">
					<figure>
						@if($folderData->image != '' && File::exists(PROJECT_FOLDER_IMAGE_ROOT_PATH.$folderData->image))
							<img  src="<?php echo PROJECT_FOLDER_IMAGE_URL.'/'.$folderData->image ?>">
						@else
							<img src="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg">
						@endif
					</figure>
					<div class="folder-topic"><a href="#">{{$folderData->name}}</a></div>
				</div>
				
			</div>
		</div><?php */?>
      <div class="row"> 
        <div class="col-sm-4 col-md-3 col-lg-3 folder-sidebar">
			<div class="results-content">
				<div class="folder-description">
					{!! $folderData->description !!}
				</div>
			</div>
<!--@if(!empty($folders))
          <div class="custom-checkbox-sec"> 
			  @foreach($folders as $key=>$folderData)
				<div class="folder-search checkbox" data-name="{{$folderData->name}}" data-url="{{URL::to('search-library')}}">
				  <label>
					<input name="check[{{$key}}]" value="{{$folderData->name}}" class="form-control check check_{{$key}} valid_{{$key}}" data-id="{{$key}}" type="checkbox">
					<span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span> {{$folderData->name}} </label>
				</div>
				@endforeach 
            </div> @endif
-->
        </div>
       
        <div class="col-sm-8 col-md-9 col-lg-9">
          <div class="results-section"> @if(!empty($articleData))
            @foreach($articleData as $articleData)
            <div class="results-section-box"> 
				@if($articleData->image != '' && File::exists(PROJECT_ARTICLE_IMAGE_ROOT_PATH.$articleData->image))
              <figure class="img-box"> <a href="{{ route('Library.articleDetail',$articleData->slug) }}"> <img  src="<?php echo WEBSITE_URL.'image.php?width=500px&height=330px&cropratio=3:2&image='.PROJECT_ARTICLE_IMAGE_URL.'/'.$articleData->image ?>"> </a> </figure>
              <div class="results-content has-image">
                <h5><a class="title" href="{{ route('Library.articleDetail',$articleData->slug) }}"> {!! $articleData->article_name !!}</a></h5>
                <h5 class="text-yellow">@if(!empty($articleData->user_id)) @if($articleData->user_id!==1){{'By: '.ucfirst($articleData->username)}}@else{{trans("By: Administrator")}}@endif @endif, <span> <i class="fa fa-calendar-o"></i>&nbsp; <span class=" text-yellow"> {{ date("F m, Y",strtotime($articleData->created_at)) }}</span></span></h5>
                <div class="description">{!! Str::limit(strip_tags($articleData->article_description), 260) !!}</div>
              </div>
              @else
              <div class="results-content">
                <h5><a class="title" href="{{ route('Library.articleDetail',$articleData->slug) }}"> {!! $articleData->article_name !!}</a></h5>
                <h5 class="text-yellow">@if(!empty($articleData->user_id)) @if($articleData->user_id!==1){{'By: '.ucfirst($articleData->username)}}@else{{trans("By: Administrator")}}@endif @endif, <span> <i class="fa fa-calendar-o"></i>&nbsp; <span class=" text-yellow"> {{ date("F m, Y",strtotime($articleData->created_at)) }}</span></span></h5>
                <div class="description">{!! Str::limit(strip_tags($articleData->article_description), 260) !!}</div>
              </div>
              @endif </div>
            @endforeach
            @endif </div>
        </div>
      </div>
      {{ Form::close() }} </div>
    @include('front.elements.footer') </div>
</div>
<script>
$(document).ready(function () {
	$('.cm-first-box').matchHeight({
	property: 'min-height'
	});
});
</script> 
<script>
	$('.search-bar').on('keydown', function (evt) {    
		 //evt.preventDefault();
		var keyCode = 	evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
		var URL		=	$(".search-bar").attr('data-url');
		var searchTxtBox = $('.search-bar');    
		//searchTxtBox.val(searchTxtBox.val().replace(/(\s+)/,"(<[^>]+>)*$1(<[^>]+>)*"));    
		var text = $('.title');    
		var textarea = $('.description');    
		var description = $('.folder-description');    
		var enew = '';  
		var etextnew = '';  
		var edescriptionnew = '';  
		
		//if(keyCode == 13)
		//{
			if (searchTxtBox.val() != '') {    

				etextnew = text.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				text.html(etextnew);  
				enew = textarea.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				textarea.html(enew);        
				edescriptionnew = description.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				description.html(edescriptionnew);        
					
				var query = new RegExp("("+searchTxtBox.val()+")", "gim");    
				newtextarea= textarea.html().replace(query, '<span class="title-highlight">$1</span>');    
				newtextarea= newtextarea.replace(/(<span class="title-highlight">[^<>]*)((<[^>]+>)+)([^<>]*<\/span>)/,'</span><span class="title-highlight">');    

				newtext= text.html().replace(query, '<span class="title-highlight">$1</span>');    
				newtext= newtext.replace(/(<span class="title-highlight">[^<>]*)((<[^>]+>)+)([^<>]*<\/span>)/,'<span class="title-highlight"><span>');    

				newdescription= description.html().replace(query, '<span class="title-highlight">$1</span>');    
				newdescription= newdescription.replace(/(<span class="title-highlight">[^<>]*)((<[^>]+>)+)([^<>]*<\/span>)/,'<span class="title-highlight"><span>');    

				textarea.html(newtextarea);     
				text.html(newtext);     
				description.html(newdescription);     

			}
			else {
				enew 		= textarea.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				etextnew 	= text.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				edescriptionnew 	= description.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				textarea.html(enew);     
				text.html(etextnew);     
				description.html(edescriptionnew);     
			} 
        if(keyCode == 13)
        {
			setTimeout(function(){ $("#search_form").submit(); }, 100);
			$("#search_form").submit();
        }
	});
	$(".folder-search").on("click",function(){
		setTimeout(function(){ $("#search_form").submit(); }, 100);
		$("#search_form").submit();
	});
  </script> 
@stop 
