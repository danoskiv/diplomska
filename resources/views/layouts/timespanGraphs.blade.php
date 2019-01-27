<div class="container">
	<form method="POST" action="/weatherData/3">
		<input name="_token" type="hidden" value="{{ csrf_token() }}">
		<div class="form-group row justify-content-center">
		    <label for="stationLabel" class="col-sm-2 col-form-label col-form-label-lg text-right">Station</label>
		    <div class="col-sm-3">
		    	<select id="stationLabel" class="form-control form-control-lg disabled" name="stationInput" required>
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
			<h3 class="col-sm-12 text-center">Select timespan or season</h3>
		</div>
	    <div class="form-group row justify-content-center">
	    	<label for="fromDateTimespan" class="col-sm-2 col-form-label col-form-label-lg text-right">From date</label>
			<input type="date" id="fromDateTimespan" class="col-sm-2" name="fromDateInput" onchange="checkDateValue('fromDateTimespan')">
			<label for="toDateTimespan" class="col-sm-2 col-form-label col-form-label-lg text-right">To date</label>
			<input type="date" id="toDateTimespan" class="col-sm-2" name="toDateInput" onchange="checkDateValue('toDateTimespan')">
		</div>
		<div class="form-group row justify-content-center">
			<label for="seasonTimespan" class="col-sm-2 col-form-label col-form-label-lg text-right">Season</label>
			<div class="col-sm-3">
		    	<select id="seasonTimespan" class="form-control form-control-lg" name="periodInput" onchange="disableTimespan()">
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
	    	<button id="button" type="submit" class="btn btn-primary btn-lg">Submit</button>
	    </div>
	</form>
</div>
<script>
	var period2 = document.getElementById("seasonTimespan");
	function disableTimespan() {
		if(period2 && period2.options[period2.selectedIndex].value != 5)
		{
			document.getElementById("fromDateTimespan").disabled = true;
			document.getElementById("toDateTimespan").disabled = true;
		}
		else
		{
			document.getElementById("fromDateTimespan").disabled = false;
			document.getElementById("toDateTimespan").disabled = false;
		}
	}

	function checkDateValue(dateId) {
		var fromDateTimespan = document.getElementById(dateId).value;
		if(Date.parse(fromDateTimespan))
			document.getElementById("seasonTimespan").disabled = true;
		else
			document.getElementById("seasonTimespan").disabled = false;
	}

	const today1 = (function() {
	    const now = new Date();
	    const month = (now.getMonth() + 1).toString().padStart(2, '0');
	    const day = now.getDate().toString().padStart(2, '0');
	    return `${now.getFullYear()}-${month}-${day}`;
	})();

	const weekBefore1 = (function() {
	    const now = new Date();
	    const month = (now.getMonth() + 1).toString().padStart(2, '0');
	    const day = (now.getDate() - 7).toString().padStart(2, '0');
	    return `${now.getFullYear()}-${month}-${day}`;
	})();

	document.getElementById("fromDateTimespan").value = weekBefore1;
	document.getElementById("toDateTimespan").value = today1;
</script>