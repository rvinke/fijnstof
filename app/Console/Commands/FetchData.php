<?php

namespace App\Console\Commands;

use amantinetti\InfluxDB\Facades\InfluxDB;
use Illuminate\Console\Command;
use Ixudra\Curl\CurlService;

class FetchData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fijnstof:fetchdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //52.046420,5.519122
        //51.996561,5.600886

        //"https://public.opendatasoft.com/api/records/1.0/search/?dataset=api-luftdateninfo&rows=50&q=&facet=timestamp&facet=land&facet=value_type&facet=is_indoor&facet=sensor_manufacturer&facet=sensor_name&facet=sensor_id&geofilter.distance=52.024734586878665,5.554336323751223,2500&timezone=Europe%2FAmsterdam"
        $result = $this->doRequest(
            "https://data.sensor.community/airrohr/v1/filter/box=52.046420,5.519122,51.996561,5.600886"
        );

        $o = json_decode($result);

        $sensor_done = [];

        foreach($o as $record) {

            if(!in_array($record->sensor->id, $sensor_done)) { //voorkomen dat sensoren dubbel opgenomen worden

                foreach ($record->sensordatavalues as $val) {
                    if ($val->value_type == 'P1') {
                        $pm10[] = (double)$val->value;
                    }

                    if ($val->value_type == 'P2') {
                        $pm2[] = (double)$val->value;
                    }
                }
            }

            $sensor_done[] = $record->sensor->id;


        }

        $pm10 = $this->remove_outliers($pm10, 2);
        //var_dump($pm10);
        $pm10_sensor_count = count($pm10);
        $pm10_value = array_sum($pm10)/count($pm10);
        $this->info('PM10: '.$pm10_value);
        $pm2 = $this->remove_outliers($pm2, 2);
        //var_dump($pm2);
        $pm2_sensor_count = count($pm2);
        $pm2_value = array_sum($pm2)/count($pm2);
        $this->info('PM2.5: '.$pm2_value);

        $write_api = InfluxDB::createWriteApi();
        $write_api->write('pm2,sensor_id=web value='.$pm2_value);
        $write_api->write('pm2_sensor_count,sensor_id=web value='.$pm2_sensor_count);
        $write_api->write('pm10,sensor_id=web value='.$pm10_value);
        $write_api->write('pm10_sensor_count,sensor_id=web value='.$pm10_sensor_count);
        $this->info('Data opgeslagen');

        @file_get_contents('https://ping.ohdear.app/d604fab7-3129-4f9c-820c-a049dbd93b29');
    }

    private function doRequest($url)
    {
        $curlService = new CurlService();

        $request  = $curlService->to($url);

        if (!empty($post_vars)) {
            //$request->withData($post_vars);
            $response = $request->returnResponseObject()->post();
        } else if (!empty($headers)) {
            //$request->withHeaders($headers);
            $response = $request->returnResponseObject()->get();
        } else {
            $response = $request->returnResponseObject()->get();
        }


        if($response->status != 200) {
            $error = $response->status;
            return 'Error code: '.$error."\n";
        }else{
            return $response->content;
        }
    }

    function remove_outliers($dataset, $magnitude = 1) {

        $count = count($dataset);
        $mean = array_sum($dataset) / $count; // Calculate the mean
        $deviation = sqrt(array_sum(array_map([$this, "sd_square"], $dataset, array_fill(0, $count, $mean))) / $count) * $magnitude; // Calculate standard deviation and times by magnitude

        return array_filter($dataset, function($x) use ($mean, $deviation) { return ($x <= $mean + $deviation && $x >= $mean - $deviation); }); // Return filtered array of values that lie within $mean +- $deviation.
    }

    function sd_square($x, $mean) {
        return pow($x - $mean, 2);
    }
}
