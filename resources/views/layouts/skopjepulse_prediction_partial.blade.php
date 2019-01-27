<div class="alert alert-primary" role="alert">
		<h3 class="text-center">
			Please select desired station and enter your data for the algorithm.
		</h3>
	</div>
	<div class="container text-right">
		<a href="#jumbo1">What's this?</a>
	</div><hr>
	<div class="container">
		<form method="POST" action="/predictions/1">
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
		    	<div class="col-sm-3">
		    		<select id="parameterLabel" class="form-control form-control-lg" name="parameterInput">
			    		@foreach($parameters as $parameter)
			    				<option value="{{ $parameter->parameterID }}">{{ $parameter->parameterName }}</option>
			    		@endforeach
			    	</select>
		    	</div>
			</div>
		    <div class="form-group row">
		    	<label for="noiseLabel" class="col-sm-2 col-form-label col-form-label-lg text-right">Noise</label>
		    	<div class="col-sm-2">
		    		<input id="noiseLabel" class="form-control form-control-lg" type="number" name="noiseInput" required>
		    	</div>
		    	<label for="temperatureLabel" class="col-sm-2 col-form-label col-form-label-lg text-right">Temperature</label>
		    	<div class="col-sm-2">
		    		<input id="temperatureLabel" class="form-control form-control-lg" type="number" name="temperatureInput" required>
		    	</div>
		    	<label for="humidityLabel" class="col-sm-2 col-form-label col-form-label-lg text-right">Humidity</label>
		    	<div class="col-sm-2">
		    		<input id="humidityLabel" class="form-control form-control-lg" type="number" name="humidityInput" required>
		    	</div>
		    </div>
		    <div class="form-group row justify-content-center">
		    	<button type="submit" class="btn btn-primary btn-lg">Submit</button>
		    </div>
		</form>
	</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	        	<span aria-hidden="true">&times;</span>
	        </button>
  		</div>
      	<div class="modal-body">
        	<form>
          		<div class="form-group">
		            <label for="recipient-name" class="col-form-label">Recipient:</label>
		            <input type="text" class="form-control" id="recipient-name">
		        </div>
		          <div class="form-group">
		            <label for="message-text" class="col-form-label">Message:</label>
		            <textarea class="form-control" id="message-text"></textarea>
		          </div>
		        </form>
      	</div>
      	<div class="modal-footer">
        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        	<button type="button" class="btn btn-primary">Send message</button>
      	</div>
    </div>
  </div>
</div>