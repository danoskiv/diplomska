@extends('layouts.app')
@section('title')
	{{ 'Raw Data for ' . $name->description }}
@endsection
@section('content')
		<div class="container">
		<p class="h3">Please select date and station</p>
		<form method="POST" action="/rawdata">
			<input name="_token" type="hidden" value="{{ csrf_token() }}">
			<div class="form-group row">
			    <label for="dateLabel" class="col-sm-2 col-form-label col-form-label-lg text-right">Date</label>
			    <div class="col-sm-3">
			    	<input type="date" class="form-control form-control-lg" id="dateLabel" name="dateInput" required>
			    </div>
		  	</div>
		  	<div class="form-group row">
			    <label for="stationLabel" class="col-sm-2 col-form-label col-form-label-lg text-right">Station</label>
			    <div class="col-sm-3">
			    	<select id="stationLabel" class="form-control form-control-lg" name="stationInput">
			    		@foreach($stations as $station)
			    			@if($station->status == 'ACTIVE')
			    				<option value="{{ $station->recId }}">{{ $station->description }}</option>
			    			@else
			    				<option value="{{ $station->recId }}" disabled>{{ $station->description }}</option>
			    			@endif
			    		@endforeach
			    	</select>
			    	<br>
					<button type="submit" class="btn btn-primary text-right">Submit</button>
			    </div>
		  	</div>
		</form>
		<div class="container">
			<h1 class="text-center">Data shown for {{ $name->description }} at {{ $date }}</h1>
		</div>
		<div class="table-responsive">
			<table class="table border border-dark table-sm table-bordered">
	  			<thead class="thead-dark">
	  				<tr>
	  					<th class="text-center">Temperature</th>
	  					<th class="text-center">Humidity</th>
	  					<th class="text-center">Noise</th>
	  					<th class="text-center">PM25</th>
	  					<th class="text-center">PM10</th>
	  					<th class="text-center">Date&Time</th>
	  				</tr>
	  			</thead>
	  			<tbody>
	  			@for($i = 0; $i < sizeof($pm25); $i++)
	  			@if($pm25[$i]->value > 50 || $pm10[$i]->value > 50)
					<tr class="table-danger">
				@else
					<tr class="table-success">
				@endif
						<td class="text-center">
						@if(isset($temperature[$i]))
							{{ $temperature[$i]->value }}
						@else
							{{ "No measurements" }}
						@endif
						</td>	
						<td class="text-center">
						@if(isset($humidity[$i]))
							{{ $humidity[$i]->value }}
						@else
							{{ "No measurements" }}
						@endif
						</td>
						<td class="text-center">
						@if(isset($noise[$i]))
							{{ $noise[$i]->value }}
						@else
							{{ "No measurements" }}
						@endif
						</td>
						<td class="text-center">
						@if(isset($pm25[$i]))
							{{ $pm25[$i]->value }}
						@else
							{{ "No measurements" }}
						@endif
						</td>
						<td class="text-center">
						@if(isset($pm10[$i]))
							{{ $pm10[$i]->value }}
						@else
							{{ "No measurements" }}
						@endif
						</td><td class="text-center">
							{{ $pm25[$i]->date_stamp }}
						</td>
					</tr>
				@endfor
				</tbody>
	  		</table>
		</div>
	</div>
@endsection