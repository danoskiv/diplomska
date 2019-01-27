@extends('layouts.app')
@section('title', 'Raw Data')
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
	</div>
@endsection