<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->adminProfile ?: new AdminProfile();

        return view('admin.profile.index', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedUser = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $validatedProfile = $request->validate([
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
        ]);

        $user->update($validatedUser);

        $user->adminProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $validatedProfile
        );

        return redirect()->route('admin.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
