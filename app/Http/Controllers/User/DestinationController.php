<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestinationController extends Controller
{
    //
    public function getTopDestinations()
    {
        $destinations = Destination::paginate(10);

        return response()->json($destinations);
    }

}
