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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'destination_id' => 'required|exists:destinations,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'required|numeric|min:0',
            'categories' => 'required|array', // Validate danh sách categories
            'categories.*.name' => 'required|string|max:255',
            'categories.*.budget' => 'required|numeric|min:0',
            'categories.*.schedules' => 'required|array', // Validate danh sách schedules trong mỗi category
            'categories.*.schedules.*.title' => 'nullable|string|max:255',
            'categories.*.schedules.*.day' => 'nullable|integer|min:1|max:31',
            'categories.*.schedules.*.hour' => 'nullable|integer|min:0|max:23',
            'categories.*.schedules.*.amount' => 'nullable|numeric|min:0',
            'categories.*.schedules.*.expense_date' => 'nullable|date',
            'categories.*.schedules.*.note' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        $trip = new Trip();
        $trip->user_id = $request->user_id;
        $trip->destination_id = $request->destination_id;
        $trip->name = $request->name;
        $trip->start_date = $request->start_date;
        $trip->end_date = $request->end_date;
        $trip->budget = $request->budget;
        $trip->is_completed = false;
        $trip->is_public = false;
        $trip->save();
    
        foreach ($request->categories as $categoryData) {
            $category = new Category();
            $category->trip_id = $trip->id;
            $category->name = $categoryData['name'];
            $category->budget = $categoryData['budget'];
            $category->save();
    
            foreach ($categoryData['schedules'] as $scheduleData) {
                $schedule = new Schedule();
                $schedule->category_id = $category->id;
                $schedule->title = $scheduleData['title'];
                $schedule->day = $scheduleData['day'];
                $schedule->time = $scheduleData['time'];
                $schedule->amount = $scheduleData['amount'];
                $schedule->expense_date = $scheduleData['expense_date'];
                $schedule->note = $scheduleData['note'];
                $schedule->save();
            }
        }
    
        return response()->json(['message' => 'Trip created successfully'], 201);
    }
    
    

    public function show($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        return response()->json($trip, 200);
    }

    public function update(Request $request)
    {
        $trip_id = $request->trip_id;
        $trip = Trip::find($trip_id);

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        $request->validate([
            'destination_id' => 'exists:destinations,id',
            'package_id' => 'exists:trip_packages,id',
            'name' => 'string|max:255',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
            'budget' => 'numeric|min:0',
        ]);

        $trip->update($request->all());

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
    
}
