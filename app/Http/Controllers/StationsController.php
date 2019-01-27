<?php

namespace WSCAERD\Http\Controllers;

use WSCAERD\Models\Stations;
use WSCAERD\Models\SensorData;
use Illuminate\Http\Request;

class StationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stations = Stations::whereIn('type', [1,2])->get();
        return view('stations.index', ['stations'=>$stations]);
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
     * @param  \WSCAERD\Models\Stations  $stations
     * @return \Illuminate\Http\Response
     */
    public function show($recId)
    {
        $station = Stations::find($recId);
        $data = SensorData::where('stationId', $recId)
                            ->orderBy('recId', 'desc')
                            ->take(100)
                            ->get();
        return view('stations.show', [
            'station' => $station,
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \WSCAERD\Models\Stations  $stations
     * @return \Illuminate\Http\Response
     */
    public function edit(Stations $stations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \WSCAERD\Models\Stations  $stations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stations $stations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \WSCAERD\Models\Stations  $stations
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stations $stations)
    {
        //
    }
}
