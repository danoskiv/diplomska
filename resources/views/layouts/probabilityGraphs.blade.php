<div class="container">
	<form method="POST" action="/weatherData/2" id="probabilityForm">
		<input name="_token" type="hidden" value="{{ csrf_token() }}">
		<div class="form-group row justify-content-center">
		    <label for="stationLabel" class="col-sm-2 col-form-label col-form-label-lg text-right">Station</label>
		    <div class="col-sm-3">
		    	<select id="stationLabel" class="form-control form-control-lg" name="stationInput" required>
		    		@foreach($stations as $station)
		    			@if($station->status == 'ACTIVE')
		    				<option value="{{ $station->recId }}">{{ $station->description }}</option>
		    			@else
		    				<option value="{{ $station->recId }}" disabled>{{ $station->description }}</option>
		    			@endif
		    		@endforeach
		    	</select>
		    </div>
		    <label for="parameterLabel" class="col-sm-2 col-form-label col-form-label-lg text-right">Parameter</label>
	    	<div class="col-sm-2">
	    		<select id="parameterLabel" class="form-control form-control-lg" name="parameterInput">
		    		@foreach($parameters as $parameter)
		    				<option value="{{ $parameter->parameterID }}">{{ $parameter->parameterName }}</option>
		    		@endforeach
		    		<option value="3">AQI</option>
		    	</select>
	    	</div>
		</div>
	    <div class="form-group row justify-content-center">
	    	<label for="allParms" class="col-sm-3 col-form-label col-form-label-lg text-right">Independent parameter</label>
			    <div class="col-sm-3">
			    	<select id="allParms" class="form-control form-control-lg" name="parmInput">
			    		@foreach($allParms as $parameter)
			    				<option value="{{ $parameter->parameterID }}">{{ $parameter->parameterName }}</option>
			    		@endforeach
			    	</select>
			    </div>
		</div>
		<div class="form-group row justify-content-center">
			<h3 class="col-sm-12 text-center">Select timespan or season</h3>
		</div>
	    <div class="form-group row justify-content-center">
	    	<label for="fromDateProbability" class="col-sm-2 col-form-label col-form-label-lg text-right">From date</label>
			<input type="date" id="fromDateProbability" class="col-sm-2" name="fromDateInput">
			<label for="toDateProbability" class="col-sm-2 col-form-label col-form-label-lg text-right">To date</label>
			<input type="date" id="toDateProbability" class="col-sm-2" name="toDateInput">
		</div>
		<div class="form-group row justify-content-center">
			<label for="seasonProbability" class="col-sm-2 col-form-label col-form-label-lg text-right">Season</label>
			<div class="col-sm-3">
		    	<select id="seasonProbability" class="form-control form-control-lg" name="periodInput" onchange="disableProbability()">
		    		<option value="5" selected="selected">None</option>
		    		<option value="1">Winter</option>
		    		<option value="2">Spring</option>
		    		<option value="3">Summer</option>
		    		<option value="4">Autumn</option>
		    	</select>
		    </div>
		    <label for="seasonYear" class="col-sm-2 col-form-label col-form-label-lg text-right">Year</label>
			<div class="col-sm-3">
		    	<select id="seasonYear" class="form-control form-control-lg" name="yearInput" onchange="disableTimespan()">
		    		<option value="2017">2017</option>
		    		<option value="2018" selected="selected">2018</option>
		    		<option value="2019">2019</option>
		    	</select>
		    </div>
		</div>
	    <div class="form-group row justify-content-center">
	    	<button type="submit" class="btn btn-primary btn-lg">Submit</button>
	    </div>
	</form>
</div>

<script>
	var period1 = document.getElementById("seasonProbability");
	function disableProbability() {
		if(period1 && period1.options[period1.selectedIndex].value != 5)
		{
			document.getElementById("fromDateProbability").disabled = true;
			document.getElementById("toDateProbability").disabled = true;
		}
		else
		{
			document.getElementById("fromDateProbability").disabled = false;
			document.getElementById("toDateProbability").disabled = false;
		}
	}

	const today2 = (function() {
	    const now = new Date();
	    const month = (now.getMonth() + 1).toString().padStart(2, '0');
	    const day = now.getDate().toString().padStart(2, '0');
	    return `${now.getFullYear()}-${month}-${day}`;
	})();

	const weekBefore2 = (function() {
	    const now = new Date();
	    const month = (now.getMonth() + 1).toString().padStart(2, '0');
	    const day = (now.getDate() - 7).toString().padStart(2, '0');
	    return `${now.getFullYear()}-${month}-${day}`;
	})();

	document.getElementById("fromDateProbability").value = weekBefore2;
	document.getElementById("toDateProbability").value = today2;
</script>