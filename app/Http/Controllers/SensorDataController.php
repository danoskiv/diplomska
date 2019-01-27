<?php

namespace WSCAERD\Http\Controllers;

use WSCAERD\Models\SensorData;
use WSCAERD\Models\Stations;
use Illuminate\Http\Request;

class SensorDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stations = Stations::whereIn('type', [1,2])->get();
        return view('rawdata.index', ['stations' => $stations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \WSCAERD\Models\SensorData  $sensorData
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $date = request()->input('dateInput');
        $station = request()->input('stationInput');
        $name = Stations::find($station);
        $stations = Stations::whereIn('type', [1,2])->get();
        $noise = SensorData::where([
            ['date_stamp', 'like', $date . "%"],
            ['stationId', $station],
            ['parameterId', '=', 3]
            ])->get();
        $pm10 = SensorData::where([
            ['date_stamp', 'like', $date . "%"],
            ['stationId', $station],
            ['parameterId', 1]
            ])->get();
        $pm25 = SensorData::where([
            ['date_stamp', 'like', $date . "%"],
            ['stationId', $station],
            ['parameterId', 2]
            ])->get();
        $temperature = SensorData::where([
            ['date_stamp', 'like', $date . "%"],
            ['stationId', $station],
            ['parameterId', 4]
            ])->get();
        $humidity = SensorData::where([
            ['date_stamp', 'like', $date . "%"],
            ['stationId', $station],
            ['parameterId', 9]
            ])->get();
        return view('rawdata.show', [
            'stations'=>$stations,
            'noise'=>$noise,
            'temperature'=>$temperature,
            'humidity'=>$humidity,
            'pm10'=>$pm10,
            'pm25'=>$pm25,
            'date'=>$date,
            'name'=>$name
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \WSCAERD\Models\SensorData  $sensorData
     * @return \Illuminate\Http\Response
     */
    public function edit(SensorData $sensorData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \WSCAERD\Models\SensorData  $sensorData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SensorData $sensorData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \WSCAERD\Models\SensorData  $sensorData
     * @return \Illuminate\Http\Response
     */
    public function destroy(SensorData $sensorData)
    {
        //
    }
}
