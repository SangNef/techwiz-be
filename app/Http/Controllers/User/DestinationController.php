<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestinationController extends Controller
{
    public function getDestination()
    {
        $destinations = Destination::with(['trips' => function ($query) {
            $query->where('is_completed', true);
        }])->with('images')->get();

        $result = [];

        foreach ($destinations as $destination) {
            $trip_count = $destination->trips->count();
            $total_days = 0;
            $total_amount = 0;

            foreach ($destination->trips as $trip) {
                $days = (strtotime($trip->end_date) - strtotime($trip->start_date)) / (60 * 60 * 24) + 1;
                $total_days += $days;

                $schedules = DB::table('schedules')
                    ->join('categories', 'schedules.category_id', '=', 'categories.id')
                    ->where('categories.trip_id', $trip->id)
                    ->select('schedules.amount')
                    ->get();

                foreach ($schedules as $schedule) {
                    $total_amount += $schedule->amount;
                }

                $avg_amount_per_day = $days > 0 ? $total_amount / $days : 0;

                $result[] = [
                    'id' => $destination->id,
                    'name' => $destination->name,
                    'description' => $destination->description,
                    'days' => $days,
                    'avg_amount_per_day' => $avg_amount_per_day,
                    'image' => env('APP_URL') . '/images/destinations/' . $destination->images[0]->image,
                ];
            }
        }

        return response()->json($result);
    }

    public function destinationDetail($id)
    {
        $destination = Destination::with('images')->find($id);

        return response()->json($destination);
    }
}

