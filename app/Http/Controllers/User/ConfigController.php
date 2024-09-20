<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    //
    public function getHomeScreen()
    {
        $configs = Config::where('description', 'home')->get();

        return response()->json($configs);
    }
}
