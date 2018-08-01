@extends('front.layouts.default')
@section('content')
<style>
.help_page_mn_hd {
    font-size: 30px;
    border-bottom: 2px solid #333;
    padding: 0px 0px 10px;
    margin: 10px 0px 15px;
    color: #313131;
    text-transform: uppercase;
}

.faq_blk {
    border: 1px solid #ddd;
    margin: 0px 0px 10px;
}

.faq_question {
    padding: 15px;
    font-size: 18px;
    color: #313131;
    cursor: pointer;
}

.faq_answer {
    padding: 15px;
    border-top: 1px solid #ddd;
    background: #f1f3f6;
    color: #666;
    font-size: 15px;
    display: none;
    line-height: 25px;
}

.faq_question i {
    margin-right: 10px;
    float: left;
    line-height: 20px;
    margin-top: 5px;
}

.faq_question span {
    float: left;
    width: 95%;
}

.show_answer .faq_question i::before {
    content: "\f068";
}

.help_page_mn_cntnt {}
.slct_ctgry {
    float: left;
    width: 100%;
    padding: 10px 10px;
}
.slct_ctgry ul.nav.nav-tabs {
    border: none;
}

.slct_ctgry ul.nav.nav-tabs li.active {
    float: none;
    display: block;
}
.help_page_mn_cntnt {
    margin: 0 0 40px 0;
    float: left;
    width: 100%;
}
.slct_ctgry ul.nav.nav-tabs li {
    display: block;
    width: 100%;
    border: none;
    border-bottom: 1px solid #ddd;
    float: none;
    text-align: left;
    margin: 0 0 0px 0;
}

.slct_ctgry ul.nav.nav-tabs li.active a {
    border: none;
    border-bottom: 1px solid #951143;
    background: #c41f5e;
    border-radius: 0;
    color: #fff;
    line-height: normal;
    padding: 10px;
}

.slct_ctgry ul.nav.nav-tabs li a {
    margin: 0;
    font-size: 15px;
    text-transform: uppercase;
    line-height: normal;
    padding: 10px 10px;
    border-radius: 0;
    border: none;
}

.slct_ctgry ul.nav.nav-tabs li a:hover {
    background: #222;
    color: #fff;
    border-radius: 0;
    border: none;
    border-bottom: 1px solid #31668e;
}
</style>
<div class="cms-page-wrapper">
	<div class="container">
	<div class="row">
			<div class="col-md-12">
				<div class="page-title-wrapper">
					<h1 class="page-title">FAQ</h1>
				</div>
			</div>
		</div>
	<div class="prvcy_plc_dv_cntnr" >
		<div class="container">
		<div class="help_page_mn_cntnt">
			<div class="col-md-3 col-sm-12">
				<div class="slct_ctgry">
					@if(!empty($faqCategoryResult))
					<ul class="nav nav-tabs">
						<?php $i = 1; ?>
						@foreach($faqCategoryResult as $faqCategoryKey => $faqCategory)
							<li class="<?php echo ($i == 1) ? 'active' : ''; ?>">
								<a data-toggle="tab" href="#{{ $faqCategoryKey }}">{{ $faqCategory }}</a>
							</li>
						<?php $i++; ?>
						@endforeach
					</ul>
					@endif
				</div>
			</div>
			@if(!empty($result))
				<div class="col-md-9 col-sm-12">
					<div class="tab-content" >
					<?php $j = 1; ?> 
					@foreach($result as $key=> $value)
						<div id="{{{ $value['category_id'] }}}" class="tab-pane fade <?php echo ($j == 1) ? 'in active' : ''; ?>">
							<div class="help_page_mn_hd">{{ $value['category_name'] }}</div>
							@if(!empty($value['data']))
							@foreach($value['data'] as $datakey=>$datavalue)
								<div class="faq_list">
									<div class="faq_blk" id="accordion">
										<div class="faq_question">
											<i class="fa fa-plus"></i> <span> {{ $datavalue['question'] }}  </span>
											<div class="clearfix"></div>
										</div>
										<div class="faq_answer"> {{ $datavalue['answer'] }} </div>
									</div>
								</div>
							@endforeach
							@endif
						</div>
					<?php $j++; ?>
					@endforeach
					</div>
				</div>
			@endif
		</div>
		</div>
	</div>
</div>

   @include('front.elements.footer')
</div>
 <script>
    $(document).ready(function() {
		
		$("#accordion .faq_question").click(function() {
		 if($(this).next().is(":visible")){
			$(this).next().slideUp("slow");
			//$(this).find(".plusminus").text('+');
		} else {
			$("#accordion .faq_answer").slideUp("slow");
			$(this).next().slideToggle("slow");
			//$(this).children(".plusminus").text('-');
		}
	});
	});
</script>
@stop
