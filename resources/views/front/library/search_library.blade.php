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
<?php //echo time();die; ?>
<div id="pagepiling">
  <div class="section  cms-wrapper without-border">
	  <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1 class="page-title2">Search Result</h1>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="breadcrumb-wrapper">
        <div class="row">
          <div class="col-sm-6 col-md-6">
            <ol class="breadcrumb">
              <li><a href="{{URL::to('dashboard')}}">Home</a></li>
              <li><a href="{{ route('Library.index') }}">Blog</a></li>
              <li class="active"><a href="">Search result</a></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
		<div class="folder_article_head">
		  <div class="row">
			  <div class="col-sm-6 col-md-6">
			  </div>
			  <div class="col-sm-6 col-md-6">
				<div class="search-box-wrapper">
				  <div class="search-box search-icon">
					<div class="form-group">
					  <input name="keyword" data-url="{{route('Library.folderArticleList')}}" type="search" class="form-control search-bar" placeholder="Search" value="{{ isset($searchVariable['keyword'])?$searchVariable['keyword']:''}}" />
					  <span><i class="fa fa-search" aria-hidden="true"></i></span> </div>
				  </div>
				</div>
			  </div>
		  </div>
      </div>
    </div>
    <div class="container"> 
      <div class="row">
        <div class="col-sm-8 col-md-9 col-lg-9 search-data"> @if(!empty($result))
          <div class="total-results"> Search results.... {{ isset($totalArticleSearch)?$totalArticleSearch:'No' }} match found </div>
          @foreach($result as $folderkey=>$articles)
          @if(!empty($articles))
          <?php $totalArticles	= count($articles); ?>
          @if($totalArticles!=0)
          <div class="results-section">
            <h4><a href="javascript:void(0);">{{$folderkey}} 
              @if(!empty($articles))(<?php echo count($articles); ?> Found)@endif </a> </h4>
            @if(!empty($articles))
            @foreach($articles as $article)
            <div class="results-section-box"> @if($article['image'] != '' && File::exists(PROJECT_ARTICLE_IMAGE_ROOT_PATH.$article['image']))
              <figure> <a href="{{ route('Library.articleDetail',$article['slug']) }}"> <img  src="<?php echo PROJECT_ARTICLE_IMAGE_URL.'/'.$article['image'] ?>"> </a> </figure>
              @endif
              <div class="results-content <?php if($article['image'] != ''  && File::exists(PROJECT_ARTICLE_IMAGE_ROOT_PATH.$article['image'])){ echo 'has-image';}?>">
                <h5><a class="title" href="{{ route('Library.articleDetail',$article['slug']) }}">
                  <?php 
										$search = isset($searchVariable['keyword'])?$searchVariable['keyword']:'';
										if(!empty($search)){
											$highlighted	=	'<span class="title-highlight">'.$search.'</span>';
											$articleName 	= 	str_ireplace($search,$highlighted, $article['article_name']);    
										}else{
											$articleName 	= 	$article['article_name'];
										}
										?>
                  {!! $articleName !!}</a></h5>
                <h5 class="text-yellow">@if(!empty($article['user_id'])) @if($article['user_id']!==1){{'By: '.ucfirst($article['username'])}}@else{{trans("By: Administrator")}}@endif @endif, <span> <i class="fa fa-calendar-o"></i>&nbsp; <span class=" text-yellow"> {{ date("F m, Y",strtotime($article['created_at'])) }}</span></span></h5>
                <div class="description">
                  <?php 
										$search = isset($searchVariable['keyword'])?$searchVariable['keyword']:'';
										if(!empty($search)){
											$highlighted	=	'<span class="title-highlight">'.$search.'</span>';
											$article_description 	= 	str_ireplace($search,$highlighted, Str::limit(strip_tags($article['article_description']),450));    
										}else{
											$article_description 	= 	Str::limit(strip_tags($article['article_description']),450);
										}
										?>
                  {!! $article_description !!} </div>
              </div>
            </div>
            @endforeach
            @endif </div>
          @endif
          @endif
          @endforeach
          @else
          <div class="total-results"> Search results.... {{ 'No' }} match found </div>
          @endif </div>
        <div class="col-sm-4 col-md-3 col-lg-3"> @if(!empty($folders))
          <div class="custom-checkbox-sec"> @foreach($folders as $key=>$folderData)
            <?php $checked	=	''; ?>
            @if(!empty($searchVariable['check']) && array_key_exists($key,$searchVariable['check']))
            <?php $checked	=	$key; ?>
            @endif
            <div class="folder-search checkbox" data-name="{{$folderData->name}}" data-url="{{URL::to('search-library')}}">
              <label> {{ Form::checkbox("check[$key]", $folderData->name,($checked != "")? true: false, ['class' => "form-control"]) }} <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span> {{$folderData->name}} </label>
            </div>
            @endforeach </div>
          @endif </div>
      </div>
      {{ Form::close() }} </div>
  </div>
  @include('front.elements.footer') </div>
<script>
	$('.search-bar').on('keydown', function (evt) {    
		 //evt.preventDefault();
		var keyCode = 	evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
		var URL		=	$(".search-bar").attr('data-url');
		var searchTxtBox = $('.search-bar');    
		//searchTxtBox.val(searchTxtBox.val().replace(/(\s+)/,"(<[^>]+>)*$1(<[^>]+>)*"));    
		var text = $('.title');    
		var textarea = $('.description');    
		var enew = '';  
		var etextnew = '';  
		
		//if(keyCode == 13)
		//{
			if (searchTxtBox.val() != '') {    

				etextnew = text.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				text.html(etextnew);  
				enew = textarea.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				textarea.html(enew);        
					
				var query = new RegExp("("+searchTxtBox.val()+")", "gim");    
				newtextarea= textarea.html().replace(query, '<span class="title-highlight">$1</span>');    
				newtextarea= newtextarea.replace(/(<span class="title-highlight">[^<>]*)((<[^>]+>)+)([^<>]*<\/span>)/,'</span><span class="title-highlight">');    

				newtext= text.html().replace(query, '<span class="title-highlight">$1</span>');    
				newtext= newtext.replace(/(<span class="title-highlight">[^<>]*)((<[^>]+>)+)([^<>]*<\/span>)/,'<span class="title-highlight"><span>');    

				textarea.html(newtextarea);     
				text.html(newtext);     

			}
			else {
				enew 		= textarea.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				etextnew 	= text.html().replace(/(<span class="title-highlight">|<\/span>)/igm, "");    
				textarea.html(enew);     
				text.html(etextnew);     
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
</div>
