<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestinationController extends Controller
{
    //
    public function getDestination()
    {
        $destinations = Destination::with('images')->paginate(10);

        return response()->json($destinations);
    }

    public function destinationDetail($id)
    {
        $destination = Destination::with('images')->find($id);

        return response()->json($destination);
    }

}
