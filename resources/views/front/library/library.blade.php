@extends('front.layouts.default')
@section('content')
<style>
html{
	overflow: auto;
}
</style>
<div id="pagepiling">
<div class="section cms-wrapper" id="section1">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1 class="page-title2">Blog</h1>
      </div>
    </div>
  </div>
  <div class="container">
      <div class="breadcrumb-wrapper">
        <div class="row">
          <div class="col-sm-12 col-md-12">
           <ol class="breadcrumb mb-30">
              <li><a href="{{URL::to('dashboard')}}">Home</a></li>
              <li class="active">Blog</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  {{ Form::open(['role' => 'form','url' => "search-library",'id'=>'search_form','method'=>'get']) }}
   <div class="container">
      <div class="breadcrumb-wrapper">
        <div class="row">
           <div class="col-sm-6 col-md-6">
          </div>
           <div class="col-sm-6 col-md-6">
            <div class="search-box-wrapper">
              <div class="search-box">
                <div class="form-group">
                  <input name="keyword" data-url="{{URL::to('search-library')}}" type="search" class="form-control search-bar valid" placeholder="Search"  value="<?php if(isset($keyword) && !empty($searchVariable['keyword'])){ echo $searchVariable['keyword']; }?>"/>
                  <span><i class="fa fa-search" aria-hidden="true"></i></span> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{ Form::close() }} 
  <div class="container">
    <div class="row">
      <div class="col-sm-8 col-md-10 col-lg-10">
        <div class="cm-right-section">
          <div class="row">
            <div class="col-md-6 col-sm-6">
              <div class="most-viewed">
                <div class="heading"> check this out! 
                  <!-- <a href="javascript:void(0);">see all</a>--> 
                </div>
                <div class="content-height mCustomScrollbar">
                  <div class="bottom"> @if(!empty($checkThisOutTopics))
                    @foreach($checkThisOutTopics as $checkThisOutTopic)
                    <h5 class="topic-name"> <a href='{{ route("Library.articleDetail","$checkThisOutTopic->slug") }}'>{{ $checkThisOutTopic->article_name}}</a></h5>
                    <div class="post-text">
                      <p>{!! strip_tags(Str::limit($checkThisOutTopic->article_description, 150)) !!}</p>
                    </div>
                    @endforeach
                    @endif </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-6">
              <div class="most-viewed">
                <div class="heading"> Recently Added </div>
                <div class="content-height mCustomScrollbar">
                  <div class="bottom"> @if(!empty($recentAddedArticles))
                    @foreach($recentAddedArticles as $recentAddedArticle)
                    <h5 class="topic-name"><a href='{{ route("Library.articleDetail","$recentAddedArticle->slug") }}'>{{ $recentAddedArticle->article_name}}</a></h5>
                    <div class="post-text">
                      <p>{!! strip_tags(Str::limit($recentAddedArticle->article_description, 150)) !!}</p>
                    </div>
                    @endforeach
                    @endif </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="most-viewed">
                <div class="heading"> most viewed </div>
                <div class="content-height mCustomScrollbar">
                  <div class="bottom"> @if(!empty($mostViewedArticles))
                    @foreach($mostViewedArticles as $viewedArticles)
                    <h5 class="topic-name"><a href='{{ route("Library.articleDetail","$viewedArticles->slug") }}'>{{ $viewedArticles->article_name }}</a></h5>
                    <div class="post-text">
                      <p>{!! strip_tags(Str::limit($viewedArticles->article_description, 150)) !!}</p>
                    </div>
                    @endforeach
                    @endif </div>
                </div>
              </div>
            </div>
          </div>
          <?php /*?><div class="most-viewed">
			<div class="heading">
				<h3>news feed!</h3>
				<a href="javascript:void(0);">see all</a> 
			</div>
			<div class="content-height mCustomScrollbar">
				<div class="bottom">
					
					@if(!empty($newsFeeds))
						@foreach($newsFeeds as $newsFeed)
							<h5 class="topic-name">{{ $newsFeed->name }}</h5>
							<p>{!! strip_tags(Str::limit($newsFeed->description, 150)) !!}</p>
						@endforeach
					@endif
				</div>
			</div>
		</div><?php */?>
        </div>
      </div>
      <div class="col-sm-4 col-md-2 col-lg-2">
        <ul class="left-sidebar mCustomScrollbar">
          @if(!empty($projectFolders))
          @foreach($projectFolders as $folderData)
          <li>
            <div class="side-section"> <a href="{{ route('Library.folderArticle',$folderData->slug) }}">
              <figure> @if($folderData->image != '' && File::exists(PROJECT_FOLDER_IMAGE_ROOT_PATH.$folderData->image)) <img src="<?php echo PROJECT_FOLDER_IMAGE_URL.'/'.$folderData->image ?>"> @else <img src="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg"> @endif </figure>
              <div class="folder-name"> {{$folderData->name}} 
				@if($folderData->new_articles != 0)
					({{ $folderData->new_articles }})
				@endif
			  </div>
              </a> </div>
          </li>
          @endforeach
          @endif
        </ul>
        <a href="{{ route('Library.allFolders') }}" class="view-folder">See all</a> </div>
    </div>
  </div>
  @include('front.elements.footer') </div>
  <script>
	$('.search-bar').on('keydown', function (evt) {    
		 //evt.preventDefault();
		var keyCode = 	evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
		var URL		=	$(".search-bar").attr('data-url');
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
