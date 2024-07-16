<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\PlaceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SectorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'hourly_price' => $this->hourly_price,
            'places' => PlaceResource::collection($this->places)
        ];
    }
}
