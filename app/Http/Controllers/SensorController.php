<?php

namespace App\Http\Controllers;

use amantinetti\InfluxDB\Facades\InfluxDB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ixudra\Curl\CurlService;
use MathPHP\Statistics\Regression\Linear;

class SensorController extends Controller
{
    var $debug = true;

    public function index()
    {
        $query_api = InfluxDB::createQueryApi();

        $result_pm10 = $query_api->query('
            from(bucket:"fijnstof")
            |> range(start: 1970-01-01T00:00:00.000000001Z)
            |> filter(fn: (r) => r["_measurement"] == "pm10")
            |> filter(fn: (r) => r["_field"] == "value")
            |> filter(fn: (r) => r["sensor_id"] == "web")
            |> last()');

        $pm10_value = $result_pm10[0]->records[0]->values['_value'];

        $result_pm2 = $query_api->query('
            from(bucket:"fijnstof")
            |> range(start: 1970-01-01T00:00:00.000000001Z)
            |> filter(fn: (r) => r["_measurement"] == "pm2")
            |> filter(fn: (r) => r["_field"] == "value")
            |> filter(fn: (r) => r["sensor_id"] == "web")
            |> last()');

        $pm2_value = $result_pm2[0]->records[0]->values['_value'];

        $date = Carbon::now()->subHours(1)->toISOString();

        $result_pm10_trend = $query_api->query('
            from(bucket:"fijnstof")
            |> range(start: '.$date.')
            |> filter(fn: (r) => r["_measurement"] == "pm10")
            |> filter(fn: (r) => r["_field"] == "value")
            |> filter(fn: (r) => r["sensor_id"] == "web")
            |> movingAverage(n: 30)');

        $i = 1;
        $val_trend = [];
        foreach($result_pm10_trend[0]->records as $item) {
            $val_trend[] = [$i, $item->values["_value"]];
            $i++;
        }

        /*$trend = [];
        foreach($val_trend as $i => $item) {

            if($i != count($val_trend)-1) {
                if ($item > $val_trend[$i + 1]) {
                    $trend[] = -1;
                } else {
                    $trend[] = 1;
                }
            }

        }*/
        $regression = new Linear($val_trend);
        $parameters = $regression->getParameters();

        $trend_up = false;
        
        //if(array_sum($trend) > 0) {
        if($parameters['m'] > 0) {
            $trend_up = true;
        }


        return view('welcome')
            ->with('pm10', $pm10_value)
            ->with('pm2', $pm2_value)
            ->with('trend_up', $trend_up);
    }



}
