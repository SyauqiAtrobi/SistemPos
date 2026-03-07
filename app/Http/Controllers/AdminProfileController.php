<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('adminprofile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $data['name'];
        $user->phone = $data['phone'] ?? null;
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return redirect()->route('admin.profile.show')->with('success', 'Profil admin diperbarui.');
    }
}
