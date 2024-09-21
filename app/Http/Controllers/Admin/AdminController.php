<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Admin::query()->withTrashed();

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

        $admins = $query->paginate($pageSize);

        return view('pages.admin.index', compact('admins'));
    }
    public function update($id)
    {
        $admin = Admin::withTrashed()->find($id);

        if (!$admin){
            return redirect()->back()->with('error', 'admin not found');
        }

        if ($admin->trashed()) {
            $admin->restore();
            return redirect()->back()->with('success', 'admin restored successfully');
        } else {
            $admin->delete();
            return redirect()->back()->with('success', 'admin ban successfully');
        }
    }
}
