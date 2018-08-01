@extends('front.layouts.default')
@section('content')
<style>
html { overflow:auto};
</style>
<div id="pagepiling">
  <div class="section cms-wrapper" id="section1">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1 class="page-title2">{{ trans("Blog Categories") }}</h1>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="breadcrumb-wrapper">
        <div class="row">
          <div class="col-sm-12 col-md-12">
            <ol class="breadcrumb mb-30">
              <li><a href="{{URL::to('dashboard')}}">Home</a></li>
              <li><a href="{{ route('Library.index') }}">Blog</a></li>
              <li class="active">{{ trans("Blog Categories") }}</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">  {{ Form::open(['role' => 'form','url' => "search-library",'id'=>'search_form','method'=>'get']) }}
		 @if(!empty($folders))
        <div class="col-sm-4 col-md-3 col-lg-3">
          <div class="custom-checkbox-sec"> 
			  @foreach($folders as $key=>$folderData)
				<div class="folder-search checkbox" data-name="{{$folderData->name}}" data-url="{{URL::to('search-library')}}">
				  <label>
					<input name="check[{{$key}}]" value="{{$folderData->name}}" class="form-control check check_{{$key}} valid_{{$key}}" data-id="{{$key}}" type="checkbox">
					<span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span> {{$folderData->name}} </label>
				</div>
				@endforeach 
            </div>
        </div>
        @endif
        <div class="col-sm-8 col-md-9 col-lg-9">
          <ul class="cm-folder-listing-all clearfix">
            @if(!empty($projectFolders))
            @foreach($projectFolders as $folderData)
            <li>
              <div class="cm-first-box">
                <figure class="img-box"> <a href="{{ route('Library.folderArticle',$folderData->slug) }}"> @if($folderData->image != '' && File::exists(PROJECT_FOLDER_IMAGE_ROOT_PATH.$folderData->image)) <img  src="<?php echo WEBSITE_URL.'image.php?500px&height=330px&cropratio=3:2&image='.PROJECT_FOLDER_IMAGE_URL.'/'.$folderData->image ?>"> @else <img src="<?php echo WEBSITE_IMG_URL ?>admin/no_image.jpg"> @endif </a> </figure>
                <div class="cm-topic-content">
                  <h4><a href="{{ route('Library.folderArticle',$folderData->slug) }}">{{ $folderData->name}}
					@if($folderData->new_articles != 0)
						({{ $folderData->new_articles }})
					@endif
				  </a></h4>
                </div>
              </div>
            </li>
            @endforeach
            @endif
          </ul>
        </div>{{ Form::close() }}
      </div>
    </div>
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
	 document.onkeydown=function(evt){
		 //evt.preventDefault();
		var keyCode = 	evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
		var URL		=	$(".search-bar").attr('data-url');
        if(keyCode == 13)
        {
			setTimeout(function(){ $("#search_form").submit(); }, 100);
			$("#search_form").submit();
			
			//~ var KeyWord = $(".search-bar").val();
			//~ var	check_id	=	$(".check").attr('data-id');	
			//~ //window.location.reload = URL+'/'+KeyWord;
			//~ $('#loader_img').show();
			//~ $.ajax({
				//~ headers: {
				 //~ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				//~ },
				//~ url: URL+'/'+KeyWord,
				//~ type: "POST",
				//~ data:{'keyword':KeyWord,'folder':'<?php echo !empty($folders) ? ($folders) : ''; ?>'},
				//~ success:function(data){
					//~ $('#loader_img').hide();
					//~ window.location.href = URL+'/'+KeyWord;
				//~ }
			//~ });
        }
	}
	$(".folder-search").on("click",function(){
		
		setTimeout(function(){ $("#search_form").submit(); }, 100);
		$("#search_form").submit();
		//~ var KeyWord = $.trim($(this).attr("data-name"));
		//~ var URL		=	$(this).attr('data-url');
		
		//~ $("#search_form").submit();
		//~ $.ajax({
			//~ headers: {
			 //~ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			//~ },
			//~ url: URL+'/'+KeyWord,
			//~ type: "POST",
			//~ data:{'keyword':KeyWord},
			//~ success:function(data){
				//~ $('#loader_img').hide();
				//~ window.location.href = URL+'/'+KeyWord;
			//~ }
		//~ });
	});
  </script> 
@stop 
