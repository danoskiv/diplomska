<?php

namespace WSCAERD\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use WSCAERD\Models\Stations;
use WSCAERD\Models\Parameter;

class PredictionsController extends Controller
{
    public function index()
    {
    	$stations = Stations::whereIn('type', [1,2])->get();
    	$parameters = Parameter::whereIn('parameterId', [1,2])->get();
    	return view('predictions.index',[
    		'stations' => $stations,
    		'parameters' => $parameters
    	]);
    }

    public function show($id)
    {
        $stationId = request()->input('stationInput');
        $parameterId = request()->input('parameterInput');

        switch ($id) {
            case '1':
                $noise = request()->input('noiseInput');
                $temperature = request()->input('temperatureInput');
                $humidity = request()->input('humidityInput');
                $values = array(
                    "temperature" => $temperature,
                    "humidity" => $humidity,
                    "station" => $stationId,
                    "parameter" => $parameterId,
                    "noise" => $noise
                );
                $values = json_encode($values);
                $process = new Process(array("regressionSkopjePulse.py", $values));
                $process->run();
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                $output = $process->getOutput();
                $output = json_decode($output, true);

                $stations = Stations::whereIn('type', [1,2])->get();
                $parameters = Parameter::whereIn('parameterId', [1,2])->get();
                return view('predictions.show', [
                    'output' => $output[0],
                    'stations' => $stations,
                    'parameters' => $parameters
                ]);
                
                break;

            case '2':
                $temperature = request()->input('temperatureInput');
                $humidity = request()->input('humidityInput');
                $windSpeed = request()->input('windSpeedInput');
                $windDir = request()->input('windDirInput');
                $pressure = request()->input('pressureInput');
                $clouds = request()->input('cloudsInput');
                $values = array(
                    "temperature" => $temperature,
                    "humidity" => $humidity,
                    "station" => $stationId,
                    "parameter" => $parameterId,
                    "windSpeed" => $windSpeed,
                    "windDir" => $windDir,
                    "pressure" => $pressure,
                    "clouds" => $clouds
                );
                $values = json_encode($values);
                $process = new Process(array("regressionWeather.py", $values));
                $process->run();
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                $output = $process->getOutput();
                $output = json_decode($output, true);

                $stations = Stations::whereIn('type', [1,2])->get();
                $parameters = Parameter::whereIn('parameterId', [1,2])->get();
                return view('predictions.show', [
                    'output' => $output,
                    'stations' => $stations,
                    'parameters' => $parameters
                ]);

                break;
            
            case '3':
                $values = array(
                "station" => $station,
                "parameter" => $parameter
                );
                $values = json_encode($values);
                $process = new Process(array("classification", $values));
                $process->run();
                if(!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                $output = $process->getOutput();
                $output = json_decode($output, true);

            default:
                throw new Exception("Error Processing Request");
                break;
        }
    }
}
