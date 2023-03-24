<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WeatherResource;
use App\Models\Weather;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($city)
    {
        $cities = ["auckland","sydney","manila","hamilton"];
        if(!in_array($city, $cities)) {
            return "City not found";
        }

        $weather = Weather::where('city', $city)->latest('created_at')->first(); 

        if(!$weather) // since the cron job run every 15 mins to get the data. so initially we are syncing the weather.
        {
            return $this->sync_weather($city);
        }


        return $weather ? new WeatherResource($weather) : null;
    }

    private function sync_weather($city) {
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

        return new WeatherResource($weather);
    }
}
