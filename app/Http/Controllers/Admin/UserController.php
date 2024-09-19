<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = User::query()->withTrashed();

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
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $pageSize = $request->get('page_size', 10);

        $users = $query->paginate($pageSize);

        return view('pages.users.index', compact('users'));
    }

    public function update($id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        if ($user->trashed()) {
            $user->restore();
            return redirect()->back()->with('success', 'User restored successfully');
        } else {
            $user->delete();
            return redirect()->back()->with('success', 'User deleted successfully');
        }
    }
}
