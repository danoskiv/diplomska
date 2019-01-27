<!-- <div class="fixed-bottom alert alert-primary" role="alert">
		<h2 class="text-center">
			The data shown here is courtesy of <a style="text-decoration:none" href="http://openweather.com">OpenWeatherMap</a>.<br>
		</h2>
		<h3 class="text-center">
			It is taken by their open API and it is used in order to make conclusions about the final predictions.
		</h3>
	</div> -->

<hr><br><br>
	<h4 class="text-center alert alert-primary">(All graphs shown here are for the previous day - {{ $yesterday }})</h4>
	<h3 class="text-center">Currently showing graphs for sensor at {{ $name->description }}({{ $paramName->parameterName }})</h3>
	<div class="container">
		<h1 class="text-center"><u>OpenWeatherMap graphs</u></h1><br>

	    <div class="row text-center text-lg-left">
	        <div class="col-lg-4 col-md-4 col-xs-6">
		      <a href="{{ URL::to('plots/Humidity_' . $yesterday . '.png') }}" class="d-block mb-4" title="Humidity">
		        <img class="img-fluid img-thumbnail" src="{{ URL::to('plots/Humidity_' . $yesterday . '.png') }}" alt="" width="400" height="300">
		      </a>
		      <p class="text-center h4">Humidity graph</p>
		    </div>
		    <div class="col-lg-4 col-md-4 col-xs-6">
		      <a href="{{ URL::to('plots/Temperature_' . $yesterday . '.png') }}" class="d-block mb-4" title="Temperaturep">
		        <img class="img-fluid img-thumbnail" src="{{ URL::to('plots/Temperature_' . $yesterday . '.png') }}" alt="" width="400" height="300">
		      </a>
		      <p class="text-center h4">Temperature graph</p>
		    </div>
		    <div class="col-lg-4 col-md-4 col-xs-6">
		      <a href="{{ URL::to('plots/Pressure_' . $yesterday . '.png') }}" class="d-block mb-4" title="Pressure">
		        <img class="img-fluid img-thumbnail" src="{{ URL::to('plots/Pressure_' . $yesterday . '.png') }}" alt="" width="400" height="300">
		      </a>
		      <p class="text-center h4">Pressure graph</p>
		    </div>
		    <div class="col-lg-4 col-md-4 col-xs-6">
		      <a href="{{ URL::to('plots/WindSpeed' . $yesterday . '.png') }}" class="d-block mb-4" title="Wind Speed">
		        <img class="img-fluid img-thumbnail" src="{{ URL::to('plots/WindSpeed_' . $yesterday . '.png') }}" alt="" width="400" height="300">
		      </a>
		      <p class="text-center h4">Wind Speed graph</p>
		    </div>
		    <div class="col-lg-4 col-md-4 col-xs-6">
		      <a href="{{ URL::to('plots/WindDirection_' . $yesterday . '.png') }}" class="d-block mb-4" title="Wind Direction">
		        <img class="img-fluid img-thumbnail" src="{{ URL::to('plots/WindDirection_' . $yesterday . '.png') }}" alt="" width="400" height="300">
		      </a>
		      <p class="text-center h4">Wind Direction graph</p>
		    </div>
		    <div class="col-lg-4 col-md-4 col-xs-6">
		      <a href="{{ URL::to('plots/Clouds_' . $yesterday . '.png') }}" class="d-block mb-4" title="Clouds">
		        <img class="img-fluid img-thumbnail" src="{{ URL::to('plots/Clouds_' . $yesterday . '.png') }}" alt="" width="400" height="300">
		      </a>
		      <p class="text-center h4">Clouds graph</p>
		    </div>
		</div>
	</div>
	<br><br><br>
	<div class="container">

	    <h1 class="text-center"><u>SkopjePulse graphs</u></h1><br>

	    <div class="row text-center text-lg-left">

		    <div class="col-lg-4 col-md-4 col-xs-6">
		      <a href="{{ URL::to('plots/humidity_' . 2 . 1 . $yesterday .'.png') }}" class="d-block mb-4" title="Humidity">
		        <img class="img-fluid img-thumbnail" src="{{ URL::to('plots/humidity_' . 2 . 1 . $yesterday .'.png') }}" alt="no" width="400" height="300">
		      </a>
		      <p class="text-center h4">Humidity graph</p>
		    </div>
		    <div class="col-lg-4 col-md-4 col-xs-6">
		      <a href="{{ URL::to('plots/noise_' . 2 . 1 . $yesterday . 'png') }}" class="d-block mb-4" title="Noise">
		        <img class="img-fluid img-thumbnail" src="{{ URL::to('plots/noise_' . 2 . 1 . $yesterday . '.png') }}" alt="no" width="400" height="300">
		      </a>
			  <p class="text-center h4">Noise graph</p>
		    </div>
		    <div class="col-lg-4 col-md-4 col-xs-6">
		      <a href="{{ URL::to('plots/temperature_' . 2 . 1 . $yesterday . '.png') }}" class="d-block mb-4" title="Temperature">
		        <img class="img-fluid img-thumbnail" src="{{ URL::to('plots/temperature_' . 2 . 1 . $yesterday . '.png') }}" alt="no" width="400" height="300">
		      </a>
		      <p class="text-center h4">Temperature graph</p>
		    </div>
		</div>
	</div>