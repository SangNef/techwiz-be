<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    //
    public function index()
    {
        $trips = Trip::all();
        return view('pages.trips.index', compact('trips'));
    }
}
