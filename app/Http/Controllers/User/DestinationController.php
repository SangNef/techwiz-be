<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestinationController extends Controller
{
    public function getTopDestinations()
    {
        $destinations = Destination::with(['trips' => function ($query) {
            $query->where('is_completed', true);
        }])->get();

        $result = [];

        foreach ($destinations as $destination) {
            $trips = $destination->trips;

            if ($trips->isNotEmpty()) {
                $total_amount_per_day = 0;
                $trip_count = $trips->count();

                foreach ($trips as $trip) {
                    $days = (strtotime($trip->end_date) - strtotime($trip->start_date)) / (60 * 60 * 24) + 1;

                    $schedules = DB::table('schedules')
                        ->join('categories', 'schedules.category_id', '=', 'categories.id')
                        ->where('categories.trip_id', $trip->id)
                        ->select('schedules.amount')
                        ->get();

                    $trip_total_amount = 0;

                    foreach ($schedules as $schedule) {
                        $trip_total_amount += $schedule->amount;
                    }

                    $avg_amount_per_day = $days > 0 ? $trip_total_amount / $days : 0;

                    $total_amount_per_day += $avg_amount_per_day;
                }

                $avg_amount_per_day_for_destination = $trip_count > 0 ? $total_amount_per_day / $trip_count : 0;

                $result[] = [
                    'destination_id' => $destination->id,
                    'destination_name' => $destination->name,
                    'avg_amount_per_day' => "Số tiền trung bình ngày: " . $avg_amount_per_day_for_destination, 
                ];
            } else {
                $result[] = [
                    'destination_id' => $destination->id,
                    'destination_name' => $destination->name,
                    'avg_amount_per_day' => 'NA',
                    'trip_count' => 0,
                ];
            }
        }

        return response()->json($result);
    }
<<<<<<< HEAD
=======
<<<<<<< HEAD

    public function destinationDetail($id)
    {
        $destination = Destination::with('images')->find($id);

        return response()->json($destination);
    }
=======
>>>>>>> master
>>>>>>> master
}
