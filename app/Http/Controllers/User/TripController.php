<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Note;
use App\Models\Schedule;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'required|numeric|min:0',
            'categories' => 'required|array',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.budget' => 'required|numeric|min:0',
            'categories.*.schedules' => 'required|array',
            'categories.*.schedules.*.title' => 'nullable|string|max:255',
            'categories.*.schedules.*.day' => 'nullable|integer|min:1|max:31',
            'categories.*.schedules.*.hour' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Bắt đầu transaction
        DB::beginTransaction();
        try {
            // Lấy tỷ giá của người dùng
            $user = User::find($request->user_id);
            $exchangeRate = Currency::find($user->currency_id)->exchange_rate;

            // Tạo trip mới
            $trip = new Trip();
            $trip->user_id = $request->user_id;
            $trip->destination_id = $request->destination_id;
            $trip->name = $request->name;
            $trip->start_date = $request->start_date;
            $trip->end_date = $request->end_date;
            $trip->budget = $request->budget / $exchangeRate; // Quy đổi sang USD
            $trip->is_completed = false;
            $trip->is_public = false;
            $trip->save();

            foreach ($request->categories as $categoryData) {
                $category = new Category();
                $category->trip_id = $trip->id;
                $category->name = $categoryData['name'];
                $category->budget = $categoryData['budget'] / $exchangeRate; // Quy đổi sang USD
                $category->save();

                foreach ($categoryData['schedules'] as $scheduleData) {
                    $schedule = new Schedule();
                    $schedule->category_id = $category->id;
                    $schedule->title = $scheduleData['title'];
                    $schedule->day = $scheduleData['day'];
                    $schedule->time = $scheduleData['hour'];
                    $schedule->save();
                }
            }

            $note = new Note();
            $note->user_id = $request->user_id;
            $note->text = 'Created a new trip: ' . $trip->name;
            $note->save();

            DB::commit();
            return response()->json(['message' => 'Trip created successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create trip', 'details' => $e->getMessage()], 500);
        }
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
            'categories' => 'required|array',
            'categories.*.id' => 'nullable|exists:categories,id',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.budget' => 'required|numeric|min:0',
            'categories.*.schedules' => 'required|array',
            'categories.*.schedules.*.id' => 'nullable|exists:schedules,id',
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

        // Lấy tỷ giá của người dùng
        $user = User::find($request->user_id);
        $exchangeRate = Currency::find($user->currency_id)->exchange_rate;

        $trip->user_id = $request->user_id;
        $trip->destination_id = $request->destination_id;
        $trip->name = $request->name;
        $trip->start_date = $request->start_date;
        $trip->end_date = $request->end_date;
        $trip->budget = $request->budget / $exchangeRate; // Quy đổi sang USD
        $trip->save();

        $categoryIds = [];
        foreach ($request->categories as $categoryData) {
            if (isset($categoryData['id'])) {
                $category = Category::find($categoryData['id']);
                if ($category) {
                    $category->name = $categoryData['name'];
                    $category->budget = $categoryData['budget'] / $exchangeRate; // Quy đổi sang USD
                    $category->save();
                }
            } else {
                $category = new Category();
                $category->trip_id = $trip->id;
                $category->name = $categoryData['name'];
                $category->budget = $categoryData['budget'] / $exchangeRate; // Quy đổi sang USD
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
                        $schedule->amount = $scheduleData['amount'] / $exchangeRate; // Quy đổi sang USD
                        $schedule->expense_date = $scheduleData['expense_date'];
                        $schedule->note = $scheduleData['note'];
                        $schedule->save();
                    }
                } else {
                    $schedule = new Schedule();
                    $schedule->category_id = $category->id;
                    $schedule->title = $scheduleData['title'];
                    $schedule->day = $scheduleData['day'];
                    $schedule->time = $scheduleData['hour'];
                    $schedule->amount = $scheduleData['amount'] / $exchangeRate; // Quy đổi sang USD
                    $schedule->expense_date = $scheduleData['expense_date'];
                    $schedule->note = $scheduleData['note'];
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

        $trip->deleted_at = now();

        $trip->save();

        return response()->json(['message' => 'Trip deleted successfully'], 200);
    }

    public function getTripByUser(Request $request)
    {
        $user_id = $request->userId;
        $searchTerm = $request->searchTerm; // Nhận tham số tìm kiếm
        $startDate = $request->startDate; // Nhận startDate
        $endDate = $request->endDate; // Nhận endDate

        $user = User::find($user_id);
        $exchangeRate = Currency::find($user->currency_id)->exchange_rate;

        $query = Trip::where('user_id', $user_id)
            ->where('deleted_at', null)
            ->with(['destination', 'categories.schedules']);

        // Nếu có từ khóa tìm kiếm, lọc theo tên chuyến đi hoặc điểm đến
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('destination', function ($q2) use ($searchTerm) {
                        $q2->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Nếu có startDate và endDate, lọc theo khoảng ngày
        if ($startDate && $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate])
                ->whereBetween('end_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            // Nếu chỉ có startDate, lọc theo ngày bắt đầu
            $query->where('start_date', '>=', $startDate);
        } elseif ($endDate) {
            // Nếu chỉ có endDate, lọc theo ngày kết thúc
            $query->where('end_date', '<=', $endDate);
        }

        $trips = $query->get();

        $tripData = $trips->map(function ($trip) use ($exchangeRate) {
            $totalBudget = $trip->budget * $exchangeRate; // Chuyển đổi ngân sách
            $totalAmount = $trip->categories->flatMap(function ($category) {
                return $category->schedules;
            })->sum('amount') * $exchangeRate;

            return [
                'id' => $trip->id,
                'trip_name' => $trip->name,
                'destination_name' => $trip->destination ? $trip->destination->name : null,
                'start_date' => $trip->start_date,
                'end_date' => $trip->end_date,
                'is_completed' => $trip->is_completed,
                'categories' => $trip->categories->pluck('name'),
                'schedules' => $trip->categories->flatMap(function ($category) use ($exchangeRate) {
                    return $category->schedules;
                })->map(function ($schedule) use ($exchangeRate) {
                    return [
                        'name' => $schedule->category->name,
                        'category_id' => $schedule->category_id,
                        'title' => $schedule->title,
                        'day' => $schedule->day,
                        'time' => $schedule->time,
                        'amount' => number_format($schedule->amount * $exchangeRate),
                        'expense_date' => $schedule->expense_date,
                        'note' => $schedule->note,
                    ];
                }),
                'total_budget' => number_format($totalBudget * $exchangeRate),
                'total_amount' => number_format($totalAmount * $exchangeRate),
            ];
        });

        return response()->json($tripData, 200);
    }

    public function tripDetail($id)
    {
        $trip = Trip::where('id', $id)->with(['destination', 'categories.schedules'])->first();

        return response()->json($trip, 200);
    }

    public function completeTrip($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        $trip->is_completed = true;
        $trip->save();

        return response()->json(['message' => 'Trip marked as completed'], 200);
    }
}
