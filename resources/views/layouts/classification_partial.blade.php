<div class="alert alert-primary" role="alert">
		<h3 class="text-center">
			Please select desired station and enter your data for the algorithm.
		</h3>
	</div>
	<div class="container text-right">
		<a href="#">What's this?</a>
	</div><hr>
	<div>
		<form method="POST" action="/predictions/3">
			<input name="_token" type="hidden" value="{{ csrf_token() }}">
			<div class="form-group row justify-content-center">
			    <label for="stationLabel" class="col-sm-1 col-form-label col-form-label-lg text-right">Station</label>
			    <div class="col-sm-2">
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
			    <label for="parameterLabel" class="col-sm-1 col-form-label col-form-label-lg text-right">Parameter</label>
		    	<div class="col-sm-2">
		    		<select id="parameterLabel" class="form-control form-control-lg" name="parameterInput">
			    		@foreach($parameters as $parameter)
			    				<option value="{{ $parameter->parameterID }}">{{ $parameter->parameterName }}</option>
			    		@endforeach
			    	</select>
		    	</div>
		    	<label for="dateLabel" class="col-sm-1 col-form-label col-form-label-lg text-right">Date</label>
		    	<div class="col-sm-2">
					<input id="dateLabel" class="form-control form-control-lg" type="date" name="dateInput" required>
				</div>
				<label for="timeLabel" class="col-sm-1 col-form-label col-form-label-lg text-right">Hour</label>
		    	<div class="col-sm-2">
		    		<select id="timeLabel" class="form-control form-control-lg" name="timeInput">
		    			@for($i = 0; $i < 23; $i++)
		    				@if($i >= 0 && $i <= 9)
		    					<option value="{{ 0 . $i }}">
		    						{{ "0" . $i }}
		    					</option>
		    				@else
		    					<option value="{{ strVal($i) }}">
		    						{{ $i }}
		    					</option>
		    					@endif
		    				
		    			@endfor
		    		</select>
				</div>
			</div>
			<div class="form-group row justify-content-center">
		    	<button type="submit" class="btn btn-primary btn-lg">Submit</button>
		    </div>
		</form>
	</div>