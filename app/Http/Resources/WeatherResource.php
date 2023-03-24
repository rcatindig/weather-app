<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'city' => $this->city,
            'updated' => date('d/m/Y h:i A', strtotime($this->created_at)),
            'temperature' => $this->temperature,
            'wind' => $this->wind,
            'description' => $this->description
        ];
    }
}
