<?php

namespace WSCAERD\Http\Controllers;

use Illuminate\Http\Request;
use WSCAERD\Models\Weather;
use WSCAERD\Models\Stations;
use WSCAERD\Models\Parameter;
use WSCAERD\Models\SensorData;
use DB;
use App\Quotation;

class WeatherDataController extends Controller
{
    public function index()
    {
    	$weather = Weather::orderBy('recId', 'desc')->first();
    	$time = $weather->date_stamp;
        $weatherToday = Weather::where('date_stamp', 'like', date("Y-m-d") . '%')->get();
    	$hour = explode("T", $time);
        $stations = Stations::whereIn('type', [1,2])->get();
        $name = Stations::find(2);
        $paramName = Parameter::find(1);
        $parameters = Parameter::whereIn('parameterID', [1,2])->get();

        $allParms = Parameter::whereIn('parameterID', [3, 4, 9])->get();

        
    	return view('weatherdata.index', [
    		'weather' => $weather,
    		'hour' => $hour[1],
            'weatherToday' => $weatherToday,
            'stations' => $stations,
            'parameters' => $parameters,
            'name' => $name,
            'paramName' => $paramName,
            'allParms' => $allParms
    	]);
    }

    public function show($id)
    {
        \Debugbar::enable();

        $weather = Weather::orderBy('recId', 'desc')->first();
        $time = $weather->date_stamp;
        $weatherToday = Weather::where('date_stamp', 'like', date("Y-m-d") . '%')->get();
        $hour = explode("T", $time);
        switch ($id) {
            case 1:
                $stationId = request()->input('stationInput');
                $parameterId = request()->input('parameterInput');
                $name = Stations::find($stationId);
                $paramName = Parameter::find($parameterId);
                $independentParm = request()->input('parmInput');
                $indParmName = Parameter::find($independentParm);
                $fromDate = request()->input('fromDateInput');
                $toDate = request()->input('toDateInput');
                $pm10counter = 0;
                $pm25counter = 0;
                $decision = "1";
                $indParmName = ucfirst($indParmName["parameterName"]);
                $hours = ""; 
                $paramName = strtoupper($paramName["parameterName"]);

                $stations = Stations::whereIn('type', [1,2])->get();
                $parameters = Parameter::whereIn('parameterID', [1,2])->get();
                $allParms = Parameter::whereIn('parameterID', [3, 4, 9])->get();

                $period = request()->input('periodInput');
                $year = request()->input('yearInput');
                if($period != '5')
                {
                    switch($period)
                    {
                        case '1': //Winter period
                            $fromDate = $year . "-12-21";
                            $toDate = $year + 1 . "-03-20";
                            break;
                        case '2': //Spring period
                            $fromDate = $year . "-03-20";
                            $toDate = $year . "-06-21";
                            break;
                        case '3': //Summer period
                            $fromDate = $year . "-06-21";
                            $toDate = $year . "-09-23";
                            break;
                        case '4': //Autumn period
                            $fromDate = $year . "-09-23";
                            $toDate = $year . "-12-21";
                            break;
                    }
                }

                if($parameterId == '3')
                {
                    $tempTable = DB::unprepared(DB::raw(
                        "CREATE TEMPORARY TABLE table_temp 
                            AS (
                            select value,date_stamp 
                            from SensorData 
                            where parameterId = " . $independentParm . " and stationId = " . $stationId . " and date_stamp between '" . $fromDate . "' and '" . $toDate . "' and value in 
                                (select distinct(sd.value) 
                                from SensorData as sd 
                                where sd.stationId = " . $stationId . " and sd.parameterId = " . $independentParm . " and sd.date_stamp between '" . $fromDate . "' and '" . $toDate . "' order by sd.value) order by value);"
                    ));

                    if($tempTable)
                    {
                        $tempValues = DB::select(DB::raw(
                            "SELECT * FROM table_temp;"
                        ));

                        foreach ($tempValues as $value) {
                            \Debugbar::info("DATE: " . ($value->date_stamp));
                            //\Debugbar::info("VALUE: " . get_object_vars($value["value"]));
                        }

                    }
                    // $paramName = "AQI";

                    // $allTemps = SensorData::select('value')->whereBetween('date_stamp', [$fromDate, $toDate])->where([
                    //     ['parameterId', '=', $independentParm],
                    //     ['stationId', '=', $stationId]
                    // ])->orderBy('value');
                    // $allTemps = $allTemps->distinct('value')->get();

                    // $average = array();
                    // $averagePM10 = array();
                    // $i = 0;
                    // $j = 0;
                    // $temperatures = array();

                    // foreach ($allTemps as $temp) {
                    //     $tempDates = SensorData::select('date_stamp')->where([
                    //         ['parameterId', '=', $independentParm],
                    //         ['stationId', '=', $stationId]
                    //     ])->whereIn('value', $temp)->get();

                    //     $avg = 0;
                    //     $counter = 0;
                    //     $avg2 = 0;
                    //     $counter2 = 0;

                    //     $avgValue = SensorData::select('value')->where([
                    //         ['parameterId', '=', '1'],
                    //         ['stationId', '=', $stationId]
                    //     ])->whereIn('date_stamp', $tempDates)->chunk(200, function($values) use (&$avg, &$counter) {
                    //         foreach ($values as $value) {
                    //             $avg += $value->value;
                    //             $counter++;
                    //         }
                    //     });

                    //     $avgValue2 = SensorData::select('value')->where([
                    //         ['parameterId', '=', '2'],
                    //         ['stationId', '=', $stationId]
                    //     ])->whereIn('date_stamp', $tempDates)->chunk(200, function($values) use (&$avg2, &$counter2) {
                    //         foreach ($values as $value) {
                    //             $avg2 += $value->value;
                    //             $counter2++;
                    //         }
                    //     });
                    //     $avg = $avg/$counter;
                    //     $avg = round($avg, 2);
                    //     $avg2 = $avg2/$counter2;
                    //     $avg2 = round($avg2, 2);

                    //     $avg = $this->calculateAQI($avg, '1');
                    //     $avg2 = $this->calculateAQI($avg2, '2');

                    //     $average[$i] = $avg;
                    //     $average2[$i] = $avg2;

                    //     if($avg > $avg2)
                    //         $pm25counter++;
                    //     else if($avg2 > $avg)
                    //         $pm10counter++;

                    //     $temperatures[$i] = $temp->value;
                    //     $i++;

                    // }

                    // if($pm10counter >= $pm25counter)
                    //     $average = $average2;
                }
                else
                {
                    $allTemps = SensorData::select('value')->whereBetween('date_stamp', [$fromDate, $toDate])->where([
                        ['parameterId', '=', $independentParm],
                        ['stationId', '=', $stationId]
                    ])->orderBy('value');
                    $allTemps = $allTemps->distinct('value')->get();

                    $average = array();
                    $i = 0;
                    $j = 0;
                    $temperatures = array();

                    foreach ($allTemps as $temp) {
                        $tempDates = SensorData::select('date_stamp')->where([
                            ['parameterId', '=', $independentParm],
                            ['stationId', '=', $stationId]
                        ])->whereIn('value', $temp)->get();

                        $avg = 0;
                        $counter = 0;  

                        $avgValue = SensorData::select('value')->where([
                            ['parameterId', '=', $parameterId],
                            ['stationId', '=', $stationId]
                        ])->whereIn('date_stamp', $tempDates)->chunk(200, function($values) use (&$avg, &$counter) {
                            foreach ($values as $value) {
                                $avg += $value->value;
                                $counter++;
                            }
                        });
                        $avg = $avg/$counter;
                        $avg = round($avg, 2);
                        $average[$i] = $avg;
                        $temperatures[$i] = $temp->value;
                        $i++;
                    }
                }

                // if($i > 45)
                // {
                //     if($i < 100)
                //     {
                //         $temperatures = $this->groupAverageIndexes($temperatures, 3);
                //         $average = $this->groupAverage($average, 3);
                //     }
                //     else
                //     {
                //         $temperatures = $this->groupAverageIndexes($temperatures, 6);
                //         $average = $this->groupAverage($average, 6);
                //     }
                // }

                $temperatures = '';
                $average = '';
                return view('weatherdata.show', [
                    'stationId' => $stationId,
                    'parameterId' => $parameterId,
                    'weather' => $weather,
                    'hour' => $hour[1],
                    'weatherToday' => $weatherToday,
                    'stations' => $stations,
                    'parameters' => $parameters,
                    'name' => $name,
                    'paramName' => $paramName,
                    'temperatures' => $temperatures,
                    'average' => $average,
                    'allParms' => $allParms,
                    'indParmName' => $indParmName,
                    'decision' => $decision,
                    'hours' => $hours,
                    'fromDate' => $fromDate,
                    'toDate' => $toDate,
                    'tempValues' => $tempValues
                ]);
                break;
            
            case 2:
                $stationId = request()->input('stationInput');
                $parameterId = request()->input('parameterInput');
                $name = Stations::find($stationId);
                $paramName = Parameter::find($parameterId);
                $independentParm = request()->input('parmInput');
                $indParmName = Parameter::find($independentParm);
                $fromDate = request()->input('fromDateInput');
                $toDate = request()->input('toDateInput');
                $decision = "2";
                $hours = "";
                $paramName = strtoupper($paramName["parameterName"]);
                $indParmName = ucfirst($indParmName["parameterName"]);
                $pm10counter = 0;
                $pm25counter = 0;

                $stations = Stations::whereIn('type', [1,2])->get();
                $parameters = Parameter::whereIn('parameterID', [1,2])->get();
                $allParms = Parameter::whereIn('parameterID', [3, 4, 9])->get();

                $period = request()->input('periodInput');
                $year = request()->input('yearInput');
                if($period != '5')
                {
                    switch($period)
                    {
                        case '1': //Winter period
                            $fromDate = $year . "-12-21";
                            $toDate = $year + 1 . "-03-20";
                            break;
                        case '2': //Spring period
                            $fromDate = $year . "-03-20";
                            $toDate = $year . "-06-21";
                            break;
                        case '3': //Summer period
                            $fromDate = $year . "-06-21";
                            $toDate = $year . "-09-23";
                            break;
                        case '4': //Autumn period
                            $fromDate = $year . "-09-23";
                            $toDate = $year . "-12-21";
                            break;
                    }
                }

                if($parameterId == '3')
                {
                    $allTemps = SensorData::select('value')->where([
                        ['parameterId', '=', $independentParm],
                        ['stationId', '=', $stationId]
                    ])->orderBy('value');
                    $allTemps = $allTemps->distinct('value')->whereBetween('date_stamp', [$fromDate, $toDate])->get();

                    $average = array();
                    $average2 = array();
                    $i = 0;
                    $j = 0;
                    $temperatures = array();

                    foreach ($allTemps as $temp) {
                        $tempDates = SensorData::select('date_stamp')->where([
                            ['parameterId', '=', $independentParm],
                            ['stationId', '=', $stationId]
                        ])->whereIn('value', $temp)->get();

                        $nad50 = 0;
                        $nad502 = 0;
                        $vkupno2 = 0;
                        $vkupno = 0;  

                        $avgValue = SensorData::select('value')->where([
                            ['parameterId', '=', '1'],
                            ['stationId', '=', $stationId]
                        ])->whereIn('date_stamp', $tempDates)->chunk(200, function($values) use (&$nad50, &$vkupno) {
                            foreach ($values as $value) {
                                if($value->value >= 50)
                                {
                                    $nad50++;
                                    $vkupno++;
                                }
                                else
                                {
                                    $vkupno++;
                                }
                            }
                        });

                        $avgValue2 = SensorData::select('value')->where([
                            ['parameterId', '=', '2'],
                            ['stationId', '=', $stationId]
                        ])->whereIn('date_stamp', $tempDates)->chunk(200, function($values) use (&$nad502, &$vkupno2) {
                            foreach ($values as $value) {
                                if($value->value >= 50)
                                {
                                    $nad502++;
                                    $vkupno2++;
                                }
                                else
                                {
                                    $vkupno2++;
                                }
                            }
                        });

                        $rezultat = $nad50/$vkupno;
                        $rezultat = round($rezultat, 2);
                        $rezultat2 = $nad502/$vkupno2;
                        $rezultat2 = round($rezultat2, 2);
                        $average[$i] = $rezultat;
                        $average2[$i] = $rezultat2;

                        if($rezultat > $rezultat2)
                            $pm25counter++;
                        else if($rezultat2 > $rezultat)
                            $pm10counter++;

                        $temperatures[$i] = $temp->value;
                        $i++;
                    }
                    if($pm10counter >= $pm25counter)
                        $average = $average2;
                }
                else
                {
                    $allTemps = SensorData::select('value')->where([
                        ['parameterId', '=', $independentParm],
                        ['stationId', '=', $stationId]
                    ])->orderBy('value');
                    $allTemps = $allTemps->distinct('value')->whereBetween('date_stamp', [$fromDate, $toDate])->get();

                    $average = array();
                    $i = 0;
                    $j = 0;
                    $temperatures = array();

                    foreach ($allTemps as $temp) {
                        $tempDates = SensorData::select('date_stamp')->where([
                            ['parameterId', '=', $independentParm],
                            ['stationId', '=', $stationId]
                        ])->whereIn('value', $temp)->get();

                        $nad50 = 0;
                        $vkupno = 0;  

                        $avgValue = SensorData::select('value')->where([
                            ['parameterId', '=', $parameterId],
                            ['stationId', '=', $stationId]
                        ])->whereIn('date_stamp', $tempDates)->chunk(200, function($values) use (&$nad50, &$vkupno) {
                            foreach ($values as $value) {
                                if($value->value >= 50)
                                {
                                    $nad50++;
                                    $vkupno++;
                                }
                                else
                                {
                                    $vkupno++;
                                }
                            }
                        });
                        $rezultat = $nad50/$vkupno;
                        $rezultat = round($rezultat, 2);
                        $average[$i] = $rezultat;
                        $temperatures[$i] = $temp->value;
                        $i++;
                    }
                }
                if($i > 30)
                {
                    if($i < 70)
                    {
                        $temperatures = $this->groupAverageIndexes($temperatures, 3);
                        $average = $this->groupAverage($average, 3);
                    }
                    else
                    {
                        $temperatures = $this->groupAverageIndexes($temperatures, 6);
                        $average = $this->groupAverage($average, 6);
                    }
                }

                return view('weatherdata.show', [
                    'stationId' => $stationId,
                    'parameterId' => $parameterId,
                    'weather' => $weather,
                    'hour' => $hour[1],
                    'weatherToday' => $weatherToday,
                    'stations' => $stations,
                    'parameters' => $parameters,
                    'name' => $name,
                    'paramName' => $paramName,
                    'temperatures' => $temperatures,
                    'average' => $average,
                    'allParms' => $allParms,
                    'indParmName' => $indParmName,
                    'decision' => $decision,
                    'hours' => $hours,
                    'fromDate' => $fromDate,
                    'toDate' => $toDate
                ]);
                break;
            case 3:
                $stationId = request()->input('stationInput');
                $parameterId = request()->input('parameterInput');
                $name = Stations::find($stationId);
                $fromDate = request()->input('fromDateInput');
                $toDate = request()->input('toDateInput');
                $counter = 0;
                $average = array();
                $average2 = array();
                $averageFinal = array();
                $decision = "3";
                $temperatures = "";
                $indParmName = "";
                $param1counter = 0;
                $param2counter = 0;
                

                $stations = Stations::whereIn('type', [1,2])->get();
                $parameters = Parameter::whereIn('parameterID', [1,2])->get();
                $allParms = Parameter::whereIn('parameterID', [3, 4, 9])->get();

                $period = request()->input('periodInput');
                $year = request()->input('yearInput');
                if($period != '5')
                {
                    switch($period)
                    {
                        case '1': //Winter period
                            $fromDate = $year . "-12-21";
                            $toDate = $year + 1 . "-03-20";
                            break;
                        case '2': //Spring period
                            $fromDate = $year . "-03-20";
                            $toDate = $year . "-06-21";
                            break;
                        case '3': //Summer period
                            $fromDate = $year . "-06-21";
                            $toDate = $year . "-09-23";
                            break;
                        case '4': //Autumn period
                            $fromDate = $year . "-09-23";
                            $toDate = $year . "-12-21";
                            break;
                    }
                }

                $hours = ["00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"];


                if($parameterId == '3')
                {
                    $paramName = "AQI";
                    $tempTable = DB::unprepared(DB::raw(
                        "CREATE TEMPORARY TABLE table_temp 
                            AS (
                            SELECT DATE(date_stamp) AS date_field, TIME(date_stamp) as time_field, value as value 
                            FROM SensorData 
                            WHERE stationId = " . $stationId . " AND parameterId = 1 AND date_stamp BETWEEN '" . $fromDate . "' AND '" . $toDate . "');"
                    ));

                    $tempTable2 = DB::unprepared(DB::raw(
                        "CREATE TEMPORARY TABLE table_temp2 
                            AS (
                            SELECT DATE(date_stamp) AS date_field, TIME(date_stamp) as time_field, value as value 
                            FROM SensorData 
                            WHERE stationId = " . $stationId . " AND parameterId = 2 AND date_stamp BETWEEN '" . $fromDate . "' AND '" . $toDate . "');"
                    ));

                    
                    if($tempTable && $tempTable2)
                    {
                        foreach ($hours as $h) {
                            $avgData = DB::select(DB::raw(
                                "SELECT AVG(value) FROM table_temp AS temp WHERE temp.time_field BETWEEN '" . $h . ":00:00.000000' AND '" . $h . ":59:59.999999';"
                            ));
                            $avgData2 = DB::select(DB::raw(
                                "SELECT AVG(value) FROM table_temp AS temp WHERE temp.time_field BETWEEN '" . $h . ":00:00.000000' AND '" . $h . ":59:59.999999';"
                            ));
                            $test1 = $this->calculateAQI(round(get_object_vars($avgData[0])["AVG(value)"]), '1');
                            $test2 = $this->calculateAQI(round(get_object_vars($avgData2[0])["AVG(value)"]), '2');
                            $average[$counter] = $test1;
                            $average2[$counter] = $test2;
                            $counter++;
                            if($test1 > $test2)
                                $param1counter++;
                            else if($test1 < $test2)
                                $param2counter++;
                        }

                        if($param1counter > $param2counter)
                            $averageFinal = $average;
                        else if($param1counter < $param2counter)
                            $averageFinal = $average2;
                        else
                            $averageFinal = $average2;
                    }

                    $dropTemp = DB::unprepared(DB::raw(
                        "DROP TEMPORARY TABLE table_temp;
                         DROP TEMPORARY TABLE table_temp2;"
                    ));
                }
                else
                {
                    $paramName = Parameter::find($parameterId);
                    $paramName = strtoupper($paramName["parameterName"]);

                    $tempTable = DB::unprepared(DB::raw(
                        "CREATE TEMPORARY TABLE table_temp 
                            AS (
                            SELECT DATE(date_stamp) AS date_field, TIME(date_stamp) as time_field, value as value 
                            FROM SensorData 
                            WHERE stationId = " . $stationId . " AND parameterId = " . $parameterId . " AND date_stamp BETWEEN '" . $fromDate . "' AND '" . $toDate . "');"
                    ));

                    
                    if($tempTable)
                    {
                        foreach ($hours as $h) {
                            $avgData = DB::select(DB::raw(
                                "SELECT AVG(value) FROM table_temp AS temp WHERE temp.time_field BETWEEN '" . $h . ":00:00.000000' AND '" . $h . ":59:59.999999';"
                            ));
                            $average[$counter++] = get_object_vars($avgData[0])["AVG(value)"];
                        }
                    }

                    $dropTemp = DB::unprepared(DB::raw(
                        "DROP TEMPORARY TABLE table_temp;"
                    ));
                    $averageFinal = $average;
                }

                
                return view('weatherdata.show', [
                    'stationId' => $stationId,
                    'parameterId' => $parameterId,
                    'weather' => $weather,
                    'hour' => $hour[1],
                    'weatherToday' => $weatherToday,
                    'stations' => $stations,
                    'parameters' => $parameters,
                    'name' => $name,
                    'paramName' => $paramName,
                    'average' => $averageFinal,
                    'allParms' => $allParms,
                    'decision' => $decision,
                    'hours' => $hours,
                    'temperatures' => $temperatures,
                    'indParmName' => $indParmName,
                    'fromDate' => $fromDate,
                    'toDate' => $toDate
                ]);
                break;
        }
        
    }

    public function groupAverage($arr, $n) {
        $result = [];
        $divide = floor(sizeof($arr)/$n);
        if(sizeof($arr) % $n == 0)
        {
            for ($i = 0; $i < sizeof($arr);) 
            {
                $sum = 0;
                for($j = 0; $j< $n; $j++)
                {
                    if(isset($arr[$i]))
                    {
                        $sum += $arr[$i++];
                    }
                    else
                        $sum += 0;
                }
                array_push($result, round(($sum/$n), 2));
            }
        }
        else
        {
            for($i = 0; $i < sizeof($arr);)
            {
                if($i <= $divide * $n)
                {
                    $k = $i;
                    if(isset($arr[$k]) && isset($arr[$k+=1]) && isset($arr[$k+=2]))
                    {
                        $sum = 0;
                        for($j = 0; $j< $n; $j++)
                        {
                            if($i <= $divide * $n)
                            {
                                if(isset($arr[$i]))
                                {
                                    $sum += $arr[$i++];
                                }
                                else
                                    $sum += 0;
                            }
                            else
                            {
                                break;
                            }
                        }
                    }
                    else
                    {
                        array_push($result, $arr[$i++]);
                    }
                    array_push($result, round(($sum/$n), 2));
                }
                else
                {
                    array_push($result, $arr[$i++]);
                }
            }

        }
        return $result;
    }

    public function groupAverageIndexes($arr, $n) {
        $result = [];
        $divide = floor(sizeof($arr)/$n);
        if(sizeof($arr) % $n == 0)
        {
            for ($i = 0; $i < sizeof($arr);) 
            {
                $sum = 0;
                for($j = 0; $j< $n; $j++)
                {
                    if(isset($arr[$i]))
                    {
                        $sum += $arr[$i++];
                    }
                    else
                        $sum += 0;
                }
                array_push($result, ceil($sum/$n));
            }
        }
        else
        {
            for($i = 0; $i < sizeof($arr);)
            {
                if($i <= $divide * $n)
                {
                    $k = $i;
                    if(isset($arr[$k]) && isset($arr[$k+=1]) && isset($arr[$k+=2]))
                    {
                        $sum = 0;
                        for($j = 0; $j< $n; $j++)
                        {
                            if($i <= $divide * $n)
                            {
                                if(isset($arr[$i]))
                                {
                                    $sum += $arr[$i++];
                                }
                                else
                                    $sum += 0;
                            }
                            else
                            {
                                break;
                            }
                        }
                    }
                    else
                    {
                        array_push($result, $arr[$i++]);
                    }
                    array_push($result, ceil($sum/$n));
                }
                else
                {
                    array_push($result, $arr[$i++]);
                }
            }

        }
        return $result;
    }

    public function calculateAQI($C, $parameterId)
    {
        //This function only calculates values up to 500/600 units, so if concentration is > 500/600, return 0

        // I = ((Ihigh - Ilow)/(Chigh - Clow))*(C - Clow) + Ilow
        if($parameterId == '2')
        {
            if($C > 500.4)
                return 0;
            if($C <= 12.0)
            {
                $Clow = 0.0;
                $Chigh = 12.0;
                $Ilow = 0;
                $Ihigh = 50;
            }
            else if($C >= 12.0 && $C <= 35.4)
            {
                $Clow = 12.1;
                $Chigh = 35.4;
                $Ilow = 51;
                $Ihigh = 100;
            }
            else if($C >= 35.4 && $C <= 55.4)
            {
                $Clow = 35.5;
                $Chigh = 55.4;
                $Ilow = 101;
                $Ihigh = 150;
            }
            else if($C >= 55.4 && $C <= 150.4)
            {
                $Clow = 55.5;
                $Chigh = 150.4;
                $Ilow = 151;
                $Ihigh = 200;
            }
            else if($C >= 150.4 && $C <= 250.4)
            {
                $Clow = 150.5;
                $Chigh = 250.4;
                $Ilow = 201;
                $Ihigh = 300;
            }
            else if($C >= 250.4 && $C <= 350.4)
            {
                $Clow = 250.5;
                $Chigh = 350.4;
                $Ilow = 301;
                $Ihigh = 400;
            }
            else if($C >= 350.4 && $C <= 500.4)
            {
                $Clow = 350.5;
                $Chigh = 500.4;
                $Ilow = 401;
                $Ihigh = 500;
            }

            $I = (($Ihigh - $Ilow)/($Chigh - $Clow))*($C - $Clow) + $Ilow;
        }
        else if($parameterId == '1')
        {
            if($C > 604)
                return 0;
            if($C <= 54)
            {
                $Clow = 0.0;
                $Chigh = 54.0;
                $Ilow = 0;
                $Ihigh = 50;
            }
            else if($C >= 54 && $C <= 154)
            {
                $Clow = 55;
                $Chigh = 154;
                $Ilow = 51;
                $Ihigh = 100;
            }
            else if($C >= 154 && $C <= 254)
            {
                $Clow = 155;
                $Chigh = 254;
                $Ilow = 101;
                $Ihigh = 150;
            }
            else if($C >= 254 && $C <= 354)
            {
                $Clow = 255;
                $Chigh = 354;
                $Ilow = 151;
                $Ihigh = 200;
            }
            else if($C >= 354 && $C <= 424)
            {
                $Clow = 355;
                $Chigh = 424;
                $Ilow = 201;
                $Ihigh = 300;
            }
            else if($C >= 424 && $C <= 504)
            {
                $Clow = 425;
                $Chigh = 504;
                $Ilow = 301;
                $Ihigh = 400;
            }
            else if($C >= 504 && $C <= 604)
            {
                $Clow = 350.5;
                $Chigh = 500.4;
                $Ilow = 401;
                $Ihigh = 500;
            }
            $I = (($Ihigh - $Ilow)/($Chigh - $Clow))*($C - $Clow) + $Ilow;
        }
        
        return round($I, 2);
    }
}
