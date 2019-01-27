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
	  		<a data-toggle="pill" class="nav-item nav-link active" href="#home" onclick="disableParameter()" onload="disableParameter()"><strong>Parameter graphs</strong></a>
	  		<a class="nav-item nav-link" data-toggle="pill" href="#menu1" onclick="disableProbability()"><strong>Probability graphs</strong></a>
	  		<a class="nav-item nav-link" data-toggle="pill" href="#menu2" onclick="disableTimespan()"><strong>Timespan graphs</strong></a>
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
@endsection