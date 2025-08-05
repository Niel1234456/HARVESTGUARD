<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage; 
use App\Models\Notification;


class AdminProfileController extends Controller
{
    public function show()
    {
        
        $notifications = Notification::orderBy('created_at', 'desc')
        ->take(20)
        ->get();


        $admin = Auth::guard('admin')->user();
        return view('admin.profile.show', compact('admin', 'notifications'));
    }

    public function edit()

    {
        $notifications = Notification::orderBy('created_at', 'desc')
        ->take(20)
        ->get();
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.edit', compact('admin', 'notifications'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'middle_initial' => ['nullable', 'string', 'max:1'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            'contact_number' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'between:0,120'],
            'birthday' => ['nullable', 'date'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'office_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:50048'], // Validate the image
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:50048',  // Validate image
        ]);

        if ($request->hasFile('office_picture')) {
            $officePicturePath = time() . '_office.' . $request->file('office_picture')->extension();
            $request->file('office_picture')->move(public_path('images/office_pictures'), $officePicturePath);

            if ($admin->office_picture && file_exists(public_path('images/office_pictures/' . $admin->office_picture))) {
                unlink(public_path('images/office_pictures/' . $admin->office_picture));
            }

            $admin->office_picture = $officePicturePath;
        }

        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = time() . '.' . $request->file('profile_picture')->extension();
            $request->file('profile_picture')->move(public_path('images/profile_pictures'), $profilePicturePath);

            if ($admin->profile_picture && file_exists(public_path('images/profile_pictures/' . $admin->profile_picture))) {
                unlink(public_path('images/profile_pictures/' . $admin->profile_picture));
            }

            $admin->profile_picture = $profilePicturePath;
        }

        $admin->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'age' => $request->age,
            'birthday' => $request->birthday,
        ]);

        if ($request->filled('password')) {
            $admin->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.admin.profile.show')->with('status', 'Profile updated successfully!');
    }
}
