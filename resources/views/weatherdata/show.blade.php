@extends('layouts.app')
@section('title', 'Weather Data')
@section('content')
	<div class="container">
		<p class="h4 text-center">Weather measurements for Skopje gathered in {{ $hour }}</p><br>
		<dl class="row h5">
			<dt class="col-sm-2">Pressure</dt>
		  	<dd class="col-sm-10"><strong>{{ $weather->pressure . " hPa"}}</strong></dd>
		  	<dt class="col-sm-2">Temperature</dt>
		  	<dd class="col-sm-10"><strong>{{ $weather->temperature  }} &#8451;</strong></dd>
		  	<dt class="col-sm-2">Humidity</dt>
		  	<dd class="col-sm-10"><strong>{{ $weather->humidity }} %</strong></dd>
		  	<dt class="col-sm-2">Wind speed</dt>
		  	<dd class="col-sm-10"><strong>{{ $weather->windSpeed }} m/s</strong></dd>
		  	<dt class="col-sm-2">Wind direction</dt>
		  	<dd class="col-sm-10 "><strong>{{ $weather->windDir }} &deg;(degrees)</strong></dd>
		  	<dt class="col-sm-2">Clouds</dt>
		  	<dd class="col-sm-10"><strong>{{ $weather->clouds }} %</strong></dd>
	  	</dl>
	</div>
	<hr>
	<h2 class="text-center">Select the station for which you want to see graphs</h2>

	<div class="container">
		<nav class="nav nav-pills nav-justified">
	  		<a data-toggle="pill" class="nav-item nav-link active" href="#home"><strong>Parameter graphs</strong></a>
	  		<a class="nav-item nav-link" data-toggle="pill" href="#menu1"><strong>Probability graphs</strong></a>
	  		<a class="nav-item nav-link" data-toggle="pill" href="#menu2"><strong>Timespan graphs</strong></a>
		</nav>

		<div class="tab-content">
	  		<div id="home" class="tab-pane fade in show active"><hr>
		    	@include('layouts.parameterGraphs')
		  	</div>
		  	<div id="menu1" class="tab-pane fade"><hr>
		    	@include('layouts.probabilityGraphs')
		  	</div>
		  	<div id="menu2" class="tab-pane fade"><hr>
		    	@include('layouts.timespanGraphs')
		  	</div>
		</div>
		<hr><br><br>
	</div>

	@php
		var_dump($tempValues);
	@endphp

	<div class="container">
	    <div class="row">
	        <div class="col-12">
	            <div class="card">
	                <div class="card-body">
	                    <canvas id="canvas123"></canvas>
	                </div>
	            </div>
	        </div>
	     </div>     
	</div>
	<script>
	var decision = {!! json_encode($decision) !!};
	if(decision == "1")
	{
		var ctx = document.getElementById("canvas123").getContext('2d');
		var gradientStroke = ctx.createLinearGradient(0, 0, 0, 450);
		gradientStroke.addColorStop(0, 'black');
		gradientStroke.addColorStop(0.5, 'grey');
		gradientStroke.addColorStop(1, 'green');
		var xAxisLabel = {!! json_encode($indParmName)!!};
		var myChart = new Chart(ctx, {
		    type: 'bar',
		    data: {
		        labels: {!! json_encode($temperatures) !!},
		        datasets: [{
		            label: {!! json_encode($paramName) !!} + ' value',
		            data: {!! json_encode($average) !!},
		            backgroundColor: gradientStroke,
		            borderColor: gradientStroke,
					pointBorderColor: gradientStroke,
					pointBackgroundColor: gradientStroke,
					pointHoverBackgroundColor: gradientStroke,
					pointHoverBorderColor: "#fff",
					pointBorderWidth: 8,
					pointHoverRadius: 8,
					pointHoverBorderWidth: 1,
					pointRadius: 3,
					fill: true,
					borderWidth: 4,
		        }]
		    },
		    options: {
		        scales: {
		            yAxes: [{
		                scaleLabel:{
		                    display: true,
		                    labelString: {!! json_encode($paramName) !!} + ' Value',
		                    fontColor: "#546372",
		                    fontSize: 16
		                }
		            }],
		            xAxes: [{
		                scaleLabel:{
		                    display: true,
		                    labelString: {!! json_encode($indParmName) !!} + " for period from " + {!! json_encode($fromDate) !!} + " to " + {!! json_encode($toDate) !!},
		                    fontColor: "#546372",
		                    fontSize: 16
		                }
		            }]
		        }
		    }
		});
	}
	else if(decision == "2")
	{
		var ctx = document.getElementById("canvas123").getContext('2d');
		var gradientStroke = ctx.createLinearGradient(0, 0, 0, 450);
		gradientStroke.addColorStop(0, 'red');
		// gradientStroke.addColorStop(0.5, 'yellow');
		gradientStroke.addColorStop(1, 'lightblue');
		var myChart = new Chart(ctx, {
		    type: 'line',
		    data: {
		        labels: {!! json_encode($temperatures) !!},
		        datasets: [{
		            label: 'P(' + {!! json_encode($paramName) !!} + ' > 50)',
		            data: {!! json_encode($average) !!},
		            backgroundColor: gradientStroke,
		            hoverBackgroundColor: gradientStroke,
		            borderColor: gradientStroke,
					pointBorderColor: gradientStroke,
					pointBackgroundColor: gradientStroke,
					pointHoverBackgroundColor: gradientStroke,
					pointHoverBorderColor: gradientStroke,
					pointBorderWidth: 8,
					pointHoverRadius: 8,
					pointHoverBorderWidth: 1,
					pointRadius: 3,
					borderWidth: 4,
					fill: false
		        }]
		    },
		    options: {
		        scales: {
		            yAxes: [{
		                scaleLabel:{
		                    display: true,
		                    labelString: 'P(' + {!! json_encode($paramName) !!} + ' > 50)',
		                    fontColor: "#546372",
		                    fontSize: 16
		                }
		            }],
		            xAxes: [{
		                scaleLabel:{
		                    display: true,
		                    labelString: {!! json_encode($indParmName) !!} + " for period from " + {!! json_encode($fromDate) !!} + " to " + {!! json_encode($toDate) !!},
		                    fontColor: "#546372",
		                    fontSize: 16
		                }
		            }]
		        }
		    }
		});
	}
	else if(decision == "3")
	{
		var ctx = document.getElementById("canvas123").getContext('2d');
		var gradientStroke = ctx.createLinearGradient(0, 0, 0, 450);
		gradientStroke.addColorStop(0, 'black');
		gradientStroke.addColorStop(0.6, 'grey');
		gradientStroke.addColorStop(1, 'green');
		var myChart = new Chart(ctx, {
		    type: 'bar',
		    data: {
		        labels: {!! json_encode($hours) !!},
		        datasets: [{
		            label: 'Average ' + {!! json_encode($paramName) !!} + ' value',
		            data: {!! json_encode($average) !!},
		            backgroundColor: gradientStroke,
		            hoverBackgroundColor: gradientStroke,
		            borderColor: gradientStroke,
					pointBorderColor: gradientStroke,
					pointBackgroundColor: gradientStroke,
					pointHoverBackgroundColor: gradientStroke,
					pointHoverBorderColor: gradientStroke,
					pointBorderWidth: 8,
					pointHoverRadius: 8,
					pointHoverBorderWidth: 1,
					pointRadius: 3,
					borderWidth: 4,
					fill: true
		        }]
		    },
		    options: {
		        scales: {
		            yAxes: [{
		                scaleLabel:{
		                    display: true,
		                    labelString: {!! json_encode($paramName) !!} + ' value',
		                    fontColor: "#546372",
		                    fontSize: 16
		                }
		            }],
		            xAxes: [{
		                scaleLabel:{
		                    display: true,
		                    labelString: 'Hour of the day from ' + {!! json_encode($fromDate) !!} + ' to ' + {!! json_encode($toDate) !!},
		                    fontColor: "#546372",
		                    fontSize: 16
		                }
		            }]
		        }
		    }
		});
		// var bars = myChart.datasets[0].bars;
		// var color = "green";
		// for(i = 0; i < bars.length; i++)
		// {
		// 	if(bars[i].value <= 100 && bars[i].value >= 51)
		// 		color = "yellow";
		// 	else if(bars[i].value <= 150 && bars[i].value >= 101)
		// 		color = "orange";
		// 	else if(bars[i].value <= 200 && bars[i].value >= 151)
		// 		color = "red";
		// 	else if(bars[i].value <= 300 && bars[i].value >= 201)
		// 		color = "purple";
		// 	else if(bars[i].value <= 500 && bars[i].value >= 301)
		// 		color = "maroon";
		// 	else
		// 		color = "green";

		// 	bars[i].fillColor = color;
		// }
		// myChart.update();
	}
	</script>
@endsection