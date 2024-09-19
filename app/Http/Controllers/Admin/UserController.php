<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::all();
        return view('pages.users.index', compact('users'));
    }
    
    public function update($id)
    {
        $user = User::find($id);
        if($user->deleted_at == null){
            $user->deleted_at = now();
        }else{
            $user->deleted_at = null;
        }
        $user->save();
        return redirect()->back()->with('success', 'User status updated successfully');
    }
}
