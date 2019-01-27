<div class="container">
	<form method="POST" action="/weatherData/1">
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
	    	<label for="fromDateParameter" class="col-sm-2 col-form-label col-form-label-lg text-right">From date</label>
			<input type="date" id="fromDateParameter" class="col-sm-2" name="fromDateInput">
			<label for="toDateParameter" class="col-sm-2 col-form-label col-form-label-lg text-right">To date</label>
			<input type="date" id="toDateParameter" class="col-sm-2" name="toDateInput">
		</div>
		<div class="form-group row justify-content-center">
			<label for="seasonParameter" class="col-sm-2 col-form-label col-form-label-lg text-right">Season</label>
			<div class="col-sm-3">
		    	<select id="seasonParameter" class="form-control form-control-lg" name="periodInput" onchange="disableParameter()">
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
	var period3 = document.getElementById("seasonParameter");
	function disableParameter() {
		if(period3 && period3.options[period3.selectedIndex].value != 5)
		{
			document.getElementById("fromDateParameter").disabled = true;
			document.getElementById("toDateParameter").disabled = true;
		}
		else
		{
			document.getElementById("fromDateParameter").disabled = false;
			document.getElementById("toDateParameter").disabled = false;
		}
	}

	const today3 = (function() {
	    const now = new Date();
	    const month = (now.getMonth() + 1).toString().padStart(2, '0');
	    const day = now.getDate().toString().padStart(2, '0');
	    return `${now.getFullYear()}-${month}-${day}`;
	})();

	const weekBefore3 = (function() {
	    const now = new Date();
	    const month = (now.getMonth() + 1).toString().padStart(2, '0');
	    const day = (now.getDate() - 7).toString().padStart(2, '0');
	    return `${now.getFullYear()}-${month}-${day}`;
	})();

	document.getElementById("fromDateParameter").value = weekBefore3;
	document.getElementById("toDateParameter").value = today3;
</script>