<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    //
    public function getAllCurrency()
    {
        $currencies = Currency::all();

        return response()->json($currencies);
    }
}
