<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Currency;
use App\Models\Destination;
use App\Models\Sample;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('pages.account.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return redirect()->back()->with('error', 'Invalid credentials')->withInput();
        }

        session()->put('admin', $admin);

        return redirect()->route('dashboard');
    }

    public function index()
    {
        if (!session()->has('admin')) {
            return redirect()->route('showLoginForm');
        }
        $totalTrips = Trip::count();
        $totalDestinations = Destination::count();
        $totalUsers = User::count();
        $totalSamples = Sample::count();
        $currencies = Currency::withCount('users')->get();
        $totalUsers = $currencies->sum('users_count');
        $currencyData = $currencies->map(function ($currency) use ($totalUsers) {
            return [
                'name' => $currency->name,
                'percentage' => $totalUsers > 0 ? ($currency->users_count / $totalUsers) * 100 : 0,
            ];
        });

        $months = [];
        $monthlytotalTrips = [];
        $monthlyDestinations = [];
        $monthlyUsers = [];
        $monthlySamples = [];

        for ($i = 0; $i < 6; $i++) {
            $startOfMonth = Carbon::now()->subMonths($i)->startOfMonth();
            $endOfMonth = Carbon::now()->subMonths($i)->endOfMonth();
            $months[] = $startOfMonth->format('M Y');

            $monthlytotalTrips[] = Trip::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $monthlyDestinations[] = Destination::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $monthlyUsers[] = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $monthlySamples[] = Sample::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

            $months = array_reverse($months);
            $monthlytotalTrips = array_reverse($monthlytotalTrips);
            $monthlyDestinations = array_reverse($monthlyDestinations);
            $monthlyUsers = array_reverse($monthlyUsers);
            $monthlySamples = array_reverse($monthlySamples);
        }
        return view('pages.dashboard.index', compact('totalTrips','currencyData', 'totalDestinations', 'totalUsers', 'totalSamples', 'months', 'monthlytotalTrips', 'monthlyDestinations', 'monthlyUsers', 'monthlySamples'));
    }
}
