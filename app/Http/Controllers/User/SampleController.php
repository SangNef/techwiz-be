<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Sample;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    //
    public function index()
    {
        $samples = Sample::with(['categories.schedules'])->get();

        return response()->json($samples);
    }
}
