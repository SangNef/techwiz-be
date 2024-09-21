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
            // Đếm số chuyến đi đã hoàn thành cho destination
            $trip_count = $destination->trips->count();
            $total_days = 0;
            $total_amount = 0;

            foreach ($destination->trips as $trip) {
                // Tính tổng số ngày cho từng chuyến đi
                $days = (strtotime($trip->end_date) - strtotime($trip->start_date)) / (60 * 60 * 24) + 1;
                $total_days += $days;

                $schedules = DB::table('schedules')
                    ->join('categories', 'schedules.category_id', '=', 'categories.id')
                    ->where('categories.trip_id', $trip->id)
                    ->select('schedules.amount')
                    ->get();

                // Tính tổng số tiền cho chuyến đi này
                foreach ($schedules as $schedule) {
                    $total_amount += $schedule->amount;
                }

                // Tính số tiền trung bình mỗi ngày cho chuyến đi này
                $avg_amount_per_day = $days > 0 ? $total_amount / $days : 0;

                // Lưu thông tin vào mảng kết quả cho chuyến đi
                $result[] = [
                    'destination_id' => $destination->id,
                    'destination_name' => $destination->name,
                    'days' => $days,
                    'avg_amount_per_day' => $avg_amount_per_day, 
                ];
            }
        }

        return response()->json($result);
    }
<<<<<<< HEAD

    public function destinationDetail($id)
    {
        $destination = Destination::with('images')->find($id);

        return response()->json($destination);
    }
=======
>>>>>>> master
}

