<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('dashboard.user.index', compact('users'));
    }
    public function create()
    {
        return view('dashboard.user.create');
    }
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'string',
            'username' => 'required|string|regex:/^[a-z0-9]+$/|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:0,1'
        ]);

        User::create($validated);
        Session::flash('success', 'Berhasil Menambahkan User');
        return redirect(route('users.index'));
    }

    public function edit(User $user)
    {
        return view('dashboard.user.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        $validated = $this->validate($request, [
            'name' => 'string',
            'username' => 'required|string|regex:/^[a-z0-9]+$/|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|nullable|min:6',
            'role' => 'required|in:0,1'
        ]);
        if (!$validated['password'] ?? '') {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        $user->update($validated);
        Session::flash('success', 'Berhasil Memperbaharui User');
        return redirect(route('users.index'));
    }
    public function destroy(User $user)
    {
        $user->delete();
        Session::flash('success', 'Berhasil Menghapus User');
        return route('users.index');
    }
}
