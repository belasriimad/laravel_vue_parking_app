<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaceResource extends JsonResource
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
            'availlable' => $this->availlable,
            'sector_id' => $this->sector_id,
            'user_id' => $this->user_id,
            'start_time' => Carbon::parse($this->start_time)->format('Y-m-d h:i:s'),
            'end_time' => $this->end_time,
            'total_price' => $this->total_price,
        ];
    }
}
