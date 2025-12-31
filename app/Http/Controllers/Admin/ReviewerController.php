<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ReviewerController extends Controller
{
    public function index()
    {
        $role = Role::firstOrCreate(['name' => 'reviewer']);
        $users = User::where('role_id', $role->id)->latest()->get();
        return view('dashboard.admin.reviewers.index', compact('users'));
    }

    public function create()
    {
        return view('dashboard.admin.reviewers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $role = Role::firstOrCreate(['name' => 'reviewer']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role->id,
        ]);

        return redirect()->route('admin.reviewers.index')->with('success', 'Reviewer account created.');
    }

    public function edit(User $reviewer)
    {
        return view('dashboard.admin.reviewers.edit', ['user' => $reviewer]);
    }

    public function update(Request $request, User $reviewer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $reviewer->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $reviewer->name = $data['name'];
        $reviewer->email = $data['email'];
        if (!empty($data['password'])) {
            $reviewer->password = Hash::make($data['password']);
        }
        $reviewer->save();

        return redirect()->route('admin.reviewers.index')->with('success', 'Reviewer updated.');
    }

    public function destroy(User $reviewer)
    {
        $reviewer->delete();
        return redirect()->route('admin.reviewers.index')->with('success', 'Reviewer deleted.');
    }
}
