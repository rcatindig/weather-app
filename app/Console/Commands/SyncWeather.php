<?php

namespace App\Console\Commands;

use App\Models\Weather;
use Illuminate\Console\Command;

class SyncWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'syncing weather from the api';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $cities = ["auckland","sydney","manila","hamilton"];

        foreach($cities as $city) {
            $client = new \GuzzleHttp\Client();
            $request = $client->get('https://goweather.herokuapp.com/weather/'. $city);
            $response = $request->getBody()->getContents();

            $result = json_decode($response);

            $weather = new Weather();
            $weather->city = $city;
            $weather->temperature = $result->temperature;
            $weather->wind = $result->wind;
            $weather->description = $result->description;

            $weather->save();
        }
    }
}
