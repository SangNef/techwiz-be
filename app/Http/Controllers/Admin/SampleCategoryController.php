<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sample;
use App\Models\SampleCategory;
use App\Models\SampleSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SampleCategoryController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = SampleCategory::query()->with('schedules');

        if ($request->has('status')) {
            if ($request->status == 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status == 'banned') {
                $query->whereNotNull('deleted_at');
            }
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $pageSize = $request->get('page_size', 10);

        $sampleCategories = $query->paginate($pageSize);

        return view('pages.sampleCategories.index', compact('sampleCategories'));
    }

    public function create()
    {
        return view('pages.sampleCategories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:samples,name',
            'category_name.*' => 'required|string|max:255',
            'category_budget.*' => 'required|numeric|min:0',
            'schedule_title.*.*' => 'nullable|string|max:255',
            'schedule_day.*.*' => 'nullable|integer|min:1',
            'schedule_time.*.*' => 'nullable|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        \DB::beginTransaction();
        try {
            $sample = new Sample();
            $sample->name = $request->input('name');
            $sample->save();

            $categoryNames = $request->input('category_name');
            $categoryBudgets = $request->input('category_budget');
            $scheduleTitles = $request->input('schedule_title');
            $scheduleDays = $request->input('schedule_day');
            $scheduleTimes = $request->input('schedule_time');

            foreach ($categoryNames as $index => $categoryName) {
                $category = new SampleCategory();
                $category->name = $categoryName;
                $category->budget = $categoryBudgets[$index];
                $category->sample_id = $sample->id;
                $category->save();

                $titles = $scheduleTitles[$index] ?? [];
                $days = $scheduleDays[$index] ?? [];
                $times = $scheduleTimes[$index] ?? [];

                foreach ($titles as $i => $title) {
                    if ($title) { 
                        $schedule = new SampleSchedule();
                        $schedule->sample_category_id = $category->id;
                        $schedule->title = $title;
                        $schedule->day = $days[$i];
                        $schedule->time = $times[$i];
                        $schedule->save();
                    }
                }
            }

            \DB::commit();

            return redirect()->route('sample.index')->with('success', 'Sample and schedules created successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withErrors(['error' => 'An error occurred while saving the data.']);
        }
    }

}
