@extends('admin.layouts.default')
@section('content')
{{ HTML::style('http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}

<div class="row pad" >
		<div class="col-md-4 col-sm-4 col-xs-12 user_section_div">
			<div class="info-box"> 
				<div class="info-box-content">
					<span class="info-box-text"><b>Studios</b></span> 
				</div> 
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>   
		<div class="col-md-4 col-sm-4 col-xs-12 user_section_div">
			<div class="info-box"> 
				<div class="info-box-content">
					<span class="info-box-text"><b>Participation</b></span> 
					<span class="info-box-text">Studios = {{$studioCount}}</span>
					<span class="info-box-text">Dancers = {{$fanCount}}</span>
					<span class="info-box-text">Parents = {{$parentCount}}</span>
					<span class="info-box-text">Fans = {{$dancerCount}}</span>
				</div> 
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div> 
		<div class="col-md-4 col-sm-4 col-xs-12 user_section_div">
			<div class="info-box"> 
				<div class="info-box-content">
					<span class="info-box-text"><b>Revenue Breakdown</b></span> 
				</div> 
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>    
		<div class="col-md-4 col-sm-4 col-xs-12 user_section_div">
			<div class="info-box"> 
				<div class="info-box-content">
					<span class="info-box-text"><b>Popular Library Records</b></span> 
					@if(!empty($popularLibary))
						@foreach($popularLibary as $key=>$record) 
							<span class="info-box-text">{{$key+1}} {{ 	isset($record->article_name)?$record->article_name:'' }}</span> 
						@endforeach
					@endif
				</div> 
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>    
		<div class="col-md-4 col-sm-4 col-xs-12 user_section_div">
			<div class="info-box"> 
				<div class="info-box-content">
					<span class="info-box-text"><b>Popular Dancer Posts</b></span> 
				</div> 
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>    
		<div class="col-md-4 col-sm-4 col-xs-12 user_section_div">
			<div class="info-box"> 
				<div class="info-box-content">
					<span class="info-box-text"><b>Popular App Feature</b></span> 
				</div> 
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
		</div>    
	</div> 

	<?php
	/*
	
	<div class="col-md-4 col-sm-4 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-green"><i class="icon icon ion-ios-people"></i></span>

		<div class="info-box-content">
		  <span class="info-box-text">Total Active Users</span>
		  <span class="info-box-number">{{ $TotalActiveUser }}</span>
			<a class="small-box-footer" href="{{route('User.index','display=&is_active=1&full_name=&email=')}}" >
				More Info   
			</a>
		</div>
		
		<!-- /.info-box-content -->
	  </div>
	  <!-- /.info-box -->
	</div>
	<div class="col-md-4 col-sm-4 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-blue"><i class="ion-ios-people"></i></span>
		<div class="info-box-content">
		  <span class="info-box-text">Total Inactive Users</span>
		  <span class="info-box-number">{{ $TotalInactiveUser }}</span>
			<a class="small-box-footer" href="{{route('User.index','display=&is_active=0&full_name=&email=')}}" >
				More Info   
			</a>
		</div>
		<!-- /.info-box-content -->
	  </div>
	  <!-- /.info-box -->
	</div>
	*/
	?>  
<div class="box box-warning ">
	<div class="row pad">
	<!-- Third Graph-->
		<div class="col-md-12 col-sm-12" >	
			<div class="box box-info">
				<div id="info1"></div>
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-exclamation-circle "></i>
						Users Summary</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse" type="button">
							<i class="fa fa-minus"></i>
						</button>
						<button class="btn btn-box-tool" data-widget="remove" type="button">
							<i class="fa fa-times"></i>
						</button>
					</div>
				</div>
				<div class="box-body" style="display: block;padding:15px;">  
					<div id="newBarChart">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{HTML::script('js/admin/d3/d3.v3.min.js') }}
{{HTML::script('js/admin/d3/d3.tip.v0.6.3.js') }}
{{ HTML::style('css/admin/d3/d3.css') }}
<style>
.info-box {
    min-height: 200px;
}
.info-box-content {
    margin-left: 0px; 
}
</style>
<script>
//Third Graph
//----------------------------------------------------------------
var margin = {top: 40, right: 20, bottom: 30, left: 40},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var formatPercent = d3.format("1.0");

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], .1);

var y = d3.scale.linear()
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    .tickFormat(formatPercent);

var tip = d3.tip()
  .attr('class', 'd3-tip')
  .offset([-10, 0])
  .html(function(d) {
    return "<strong>User:</strong> <span style='color:red'>" + d.frequency + "</span>";
  })

var svg = d3.select("#newBarChart").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

svg.call(tip);

// The new data variable.
var data = [
<?php
	if(!empty($allUsers)){
		foreach($allUsers as $allUserss){
			?>
			 {letter: "<?php echo $allUserss['month']?>", frequency: <?php echo $allUserss['users']?>},
			<?php
		}
	}
?>
];

// The following code was contained in the callback function.
x.domain(data.map(function(d) { return d.letter; }));
y.domain([0, d3.max(data, function(d) { return d.frequency; })]);

svg.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis);

svg.append("g")
    .attr("class", "y axis")
    .call(yAxis)
  .append("text")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", ".71em")
    .style("text-anchor", "end")
    .text("Frequency");

svg.selectAll(".bar")
    .data(data)
  .enter().append("rect")
    .attr("class", "bar")
    .attr("x", function(d) { return x(d.letter); })
    .attr("width", x.rangeBand())
    .attr("y", function(d) { return y(d.frequency); })
    .attr("height", function(d) { return height - y(d.frequency); })
    .on('mouseover', tip.show)
    .on('mouseout', tip.hide)

function type(d) {
  d.frequency = +d.frequency;
  return d;
}
</script>

@stop
