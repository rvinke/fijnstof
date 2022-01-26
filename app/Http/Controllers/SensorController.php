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
    var $query_api;

    public function index()
    {
        $this->query_api = InfluxDB::createQueryApi();

        $pm10['value'] = $this->getInfluxData('pm10');
        $pm10['max'] = $this->getInfluxData('pm10_max');
        $pm10['min'] = $this->getInfluxData('pm10_min');

        $pm2['value'] = $this->getInfluxData('pm2');
        $pm2['max'] = $this->getInfluxData('pm2_max');
        $pm2['min'] = $this->getInfluxData('pm2_min');



        $date = Carbon::now()->subHours(1)->toISOString();

        $result_pm10_trend = $this->query_api->query('
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

        $parameters['m'] = 0;//default
        if(count($val_trend) > 20) {
            $regression = new Linear($val_trend);
            $parameters = $regression->getParameters();
        }

        $trend_up = false;

        //if(array_sum($trend) > 0) {
        if($parameters['m'] > 0) {
            $trend_up = true;
        }


        return view('welcome')
            ->with('pm10', $pm10)
            ->with('pm2', $pm2)
            ->with('trend_up', $trend_up);
    }


    private function getInfluxData($field)
    {
        $result = $this->query_api->query('
            from(bucket:"fijnstof")
            |> range(start: 2022-01-01T00:00:00.000000001Z)
            |> filter(fn: (r) => r["_measurement"] == "'.$field.'")
            |> filter(fn: (r) => r["_field"] == "value")
            |> filter(fn: (r) => r["sensor_id"] == "web")
            |> last()');

        return $result[0]->records[0]->values['_value'];

    }


}
