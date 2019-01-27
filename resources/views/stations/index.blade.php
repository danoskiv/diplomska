@extends('layouts.app')
@section('title', 'Stations')
@section('content')
	<div class="container">
	
	<table class="table">
		<thead class="thead-dark">
			<tr>
				<th>SENSOR ID</th>
				<th>NAME</th>
				<th>STATUS</th>
			</tr>
		</thead>
		@foreach($stations as $station)
			@if($station->status == 'ACTIVE')
				<tr class="table-success">
			@elseif($station->status == 'NOT_CLAIMED')
				<tr class="table-warning">
			@else
				<tr class="table-danger">
			@endif
				<td>{{ $station->sensorId }}</td>
				<td>
					<strong>
							<a class="btn-default" href="/stations/{{ $station->recId }}">{{ $station->description }}</a>
					</strong>
				</td>
				<td>{{ $station->status }}</td>
			</tr>
		@endforeach
	</table>
	</div>
@endsection