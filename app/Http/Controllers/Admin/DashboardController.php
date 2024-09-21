<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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
        // Kiểm tra xem admin đã đăng nhập hay chưa
        if (!session()->has('admin')) {
            return redirect()->route('showLoginForm');
        }

        return view('pages.dashboard.index');
    }
}
