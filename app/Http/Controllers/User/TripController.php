<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Schedule;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::all();
        return response()->json($trips, 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'destination_id' => 'nullable|exists:destinations,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'required|numeric|min:0',
            'categories' => 'required|array', // Validate danh sách categories
            'categories.*.id' => 'nullable|exists:categories,id', // Kiểm tra nếu có ID của category thì phải tồn tại
            'categories.*.name' => 'required|string|max:255',
            'categories.*.budget' => 'required|numeric|min:0',
            'categories.*.schedules' => 'required|array', // Validate danh sách schedules trong mỗi category
            'categories.*.schedules.*.id' => 'nullable|exists:schedules,id', // Kiểm tra nếu có ID của schedule thì phải tồn tại
            'categories.*.schedules.*.title' => 'nullable|string|max:255',
            'categories.*.schedules.*.day' => 'nullable|integer|min:1|max:31',
            'categories.*.schedules.*.hour' => 'nullable',
            'categories.*.schedules.*.amount' => 'nullable|numeric|min:0',
            'categories.*.schedules.*.expense_date' => 'nullable|date',
            'categories.*.schedules.*.note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $trip = Trip::find($id);
        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        $trip->user_id = $request->user_id;
        $trip->destination_id = $request->destination_id;
        $trip->name = $request->name;
        $trip->start_date = $request->start_date;
        $trip->end_date = $request->end_date;
        $trip->budget = $request->budget;
        $trip->save();

        $categoryIds = [];
        foreach ($request->categories as $categoryData) {
            if (isset($categoryData['id'])) {
                $category = Category::find($categoryData['id']);
                if ($category) {
                    $category->name = $categoryData['name'];
                    $category->budget = $categoryData['budget'];
                    $category->save();
                }
            } else {
                $category = new Category();
                $category->trip_id = $trip->id;
                $category->name = $categoryData['name'];
                $category->budget = $categoryData['budget'];
                $category->save();
            }
            $categoryIds[] = $category->id;

            $scheduleIds = [];
            foreach ($categoryData['schedules'] as $scheduleData) {
                if (isset($scheduleData['id'])) {
                    $schedule = Schedule::find($scheduleData['id']);
                    if ($schedule) {
                        $schedule->title = $scheduleData['title'];
                        $schedule->day = $scheduleData['day'];
                        $schedule->time = $scheduleData['hour'];
                        $schedule->save();
                    }
                } else {
                    $schedule = new Schedule();
                    $schedule->category_id = $category->id;
                    $schedule->title = $scheduleData['title'];
                    $schedule->day = $scheduleData['day'];
                    $schedule->time = $scheduleData['hour'];
                    $schedule->save();
                }
                $scheduleIds[] = $schedule->id;
            }

            Schedule::where('category_id', $category->id)
                ->whereNotIn('id', $scheduleIds)
                ->delete();
        }

        Category::where('trip_id', $trip->id)
            ->whereNotIn('id', $categoryIds)
            ->delete();

        return response()->json(['message' => 'Trip updated successfully'], 200);
    }

    public function show($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        return response()->json($trip, 200);
    }

    public function destroy($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        $trip->delete();

        return response()->json(['message' => 'Trip deleted successfully'], 200);
    }

    public function getTripByUser(Request $request)
    {
        $user_id = $request->userId;

        $trips = Trip::where('user_id', $user_id)
            ->where('is_completed', false)
            ->with(['destination', 'categories.schedules'])
            ->get();

        $tripData = $trips->map(function ($trip) {
            $totalBudget = $trip->budget;

            $totalAmount = $trip->categories->flatMap(function ($category) {
                return $category->schedules;
            })->sum('amount');

            return [
                'id' => $trip->id,
                'trip_name' => $trip->name,
                'destination_name' => $trip->destination ? $trip->destination->name : null,
                'start_date' => $trip->start_date,
                'end_date' => $trip->end_date,
                'categories' => $trip->categories->pluck('name'),
                'schedules' => $trip->categories->flatMap(function ($category) {
                    return $category->schedules;
                })->map(function ($schedule) {
                    return [
                        'name' => $schedule->category->name,
                        'category_id' => $schedule->category_id,
                        'title' => $schedule->title,
                        'day' => $schedule->day,
                        'time' => $schedule->time,
                        'amount' => $schedule->amount,
                        'expense_date' => $schedule->expense_date,
                        'note' => $schedule->note,
                    ];
                }),
                'total_budget' => number_format($totalBudget),
                'total_amount' => number_format($totalAmount),
            ];
        });

        return response()->json($tripData, 200);
    }

    public function tripDetail($id)
    {
        $trip = Trip::where('id', $id)->with(['destination', 'categories.schedules'])->first();

        return response()->json($trip, 200);
    }
}
