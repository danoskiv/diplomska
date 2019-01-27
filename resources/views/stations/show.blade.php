@extends('layouts.app')
@section('title')
	{{ 'Station - ' . $station->description }}
@endsection
@section('content')
	<div class="container">
		<p class="h3 text-center"><strong>Information about sensor at {{ $station->description }}</strong></p>
		<br>
		<div class="row justify-content-center ">
			<dl class="row h5">
	  			<dt class="col-sm-5 text-right">Sensor ID:</dt>
	  			<dd class="col-sm-5 text-muted">{{ $station->sensorId }}</dd>
	  			<div class="w-100"></div>
	  			<dt class="col-sm-5 text-right">Position:</dt>
	  			<dd class="col-sm-5 text-muted">{{ $station->position }}</dd>
	  			<div class="w-100"></div>
	  			<dt class="col-sm-5 text-right">Description:</dt>
	  			<dd class="col-sm-5 text-muted">{{ $station->description }}</dd>
	  			<div class="w-100"></div>
	  			<dt class="col-sm-5 text-right">Comments:</dt>
	  			<dd class="col-sm-5 text-muted">{{ $station->comments }}</dd>
	  			<div class="w-100"></div>
	  			<dt class="col-sm-5 text-right">Status:</dt>
	  			@switch($station->status)
	  				@case('ACTIVE')
	  				<dd class="col-sm-5 text-success">{{ $station->status }}</dd>
	  				@break
	  				@case('INACTIVE')
	  				<dd class="col-sm-5 text-danger">{{ $station->status }}</dd>
	  				@break
	  				@case('NOT_CLAIMED')
	  				<dd class="col-sm-5 text-warning">{{ $station->status }}</dd>
	  				@break
	  			@endswitch
  			</dl>
		</div>
		<br>

		@if(!$data->isEmpty())
	  		<div class="row justify-content-center">
	    		<h1 class="h1">Last 50 measurements</h1>
	  		</div><br>
	  		<div class="table-responsive">
	  		<table class="table border border-dark table-sm table-bordered">
	  			<thead class="thead-dark">
	  				<tr>
	  					<th class="text-center">Parameter</th>
	  					<th class="text-center">Value</th>
	  					<th class="text-center">Date&Time</th>
	  				</tr>
	  			</thead>
	  			<tbody>
	  			@foreach($data as $d)
		  			@if($d->value > 50 && ($d->parameterId === 1 || $d->parameterId === 2))
						<tr class="table-danger">
					@else
						<tr class="table-success">
					@endif
						<td class="text-center">
							@switch($d->parameterId)
								@case(9)
									{{ "Humidity" }}
									@break
								@case(3)
									{{ "Noise" }}
									@break
								@case(2)
									{{ "PM25" }}
									@break
								@case(1)
									{{ "PM10" }}
									@break
								@case(4)
									{{ "Temperature" }}
									@break
							@endswitch
						</td>
						<td class="text-center">
							{{ $d->value }}
						</td>
						<td class="text-center">
							{{ $d->date_stamp }}
						</td>
					</tr>
				@endforeach
				</tbody>
	  		</table>
			</div>
		</div>
		@else
			<div class="row justify-content-center">
	    		<h1 class="h1">Sorry, there are no measurements for the selected sensor</h1>
	    		<img class="text-center" src="{{ URL::to('images/noun_Sad emoji_837791.png') }}" width="450" height="450">
	  		</div><br>
	  	@endif
@endsection
