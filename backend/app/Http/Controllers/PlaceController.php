<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Place;
use App\Models\Sector;
use Illuminate\Http\Request;
use App\Http\Resources\PlaceResource;

class PlaceController extends Controller
{
    //
    public function startParking(Request $request, Place $place)
    {
        $request->validate([
            'user_id' => ['required', 'integer']
        ]);

        if($place->where('user_id', $request->user_id)->whereNull('end_time')->exists()) {
            return response()->json([
                'error' => 'Car already parked!'
            ]);
        }

        $place->update([
            'user_id' => $request->user_id,
            'start_time' => now(),
            'availlable' => 0,
            'end_time' => NULL,
            'total_price' => NULL
        ]);

        return PlaceResource::make($place);
    }

    public function endParking(Place $place)
    {
        $place->update([
            'availlable' => 1,
            'end_time' => now(),
            'total_price' => $this->calculatePrice($place->sector_id, $place->start_time)
        ]);

        return PlaceResource::make($place);
    }
    
    public function calculatePrice($sector_id, $start_time)
    {
        $start = Carbon::createMidnightDate($start_time);
        $end = Carbon::createMidnightDate(now());
        $totalDuration = $start->diffInHours($end);
        $sector_hourly_price = Sector::find($sector_id)->hourly_price;

        if($totalDuration > 1) {
            return ceil($sector_hourly_price * $totalDuration);
        }

        return $sector_hourly_price;

    }
}
