<?php

namespace App\Http\Controllers\Farmer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Farmer;
use App\Models\Notification;

class ProfilesController extends Controller
{
    /**
     * Show the profile page.
     */
    public function show()
    {
        $farmer = Auth::guard('farmer')->user();
        $notifications = Notification::where('farmer_id', $farmer->id)
            ->whereIn('type', ['supply', 'borrow', 'plantDiseasereport', 'approval', 'rejection', 'release', 'return'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('farmer.auth.profile', compact('farmer', 'notifications'));
    }

    /**
     * Show the form to edit the profile.
     */
    public function edit()
    {
        $farmer = Auth::guard('farmer')->user();
        $notifications = Notification::where('farmer_id', $farmer->id)
            ->whereIn('type', ['supply', 'borrow', 'plantDiseasereport', 'approval', 'rejection', 'release', 'return'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('farmer.auth.edit-profile', compact('farmer', 'notifications'));
    }

    /**
     * Update the farmer's profile.
     */
    public function update(Request $request)
    {
        $farmer = Auth::guard('farmer')->user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'phone' => 'required|numeric',
            'birth_date' => 'required|date',
            'gender' => 'required|string',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|numeric',
            'farmers_activity' => 'nullable|string|max:255',
            'id_type' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|confirmed|min:8',
        ]);

        // Update farmer's data
        $farmer->first_name = $request->first_name;
        $farmer-> last_name = $request->last_name;
        $farmer-> middle_initial = $request->middle_initial;
        $farmer->phone = $request->phone;
        $farmer->birth_date = $request->birth_date;
        $farmer->gender = $request->gender;
        $farmer->street_address = $request->street_address;
        $farmer->city = $request->city;
        $farmer->province = $request->province;
        $farmer->country = $request->country;
        $farmer->postal_code = $request->postal_code;
        $farmer->farmers_activity = $request->farmers_activity;
        $farmer->id_type = $request->id_type;
        $farmer->id_number = $request->id_number;

        // Handle profile picture upload if provided
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = time() . '.' . $request->file('profile_picture')->extension();
            $request->file('profile_picture')->move(public_path('images/profile_pictures'), $profilePicturePath);

            // Delete old profile picture if it exists
            if ($farmer->profile_picture && file_exists(public_path('images/profile_pictures/' . $farmer->profile_picture))) {
                unlink(public_path('images/profile_pictures/' . $farmer->profile_picture));
            }

            $farmer->profile_picture = $profilePicturePath;
        }

        // Handle password update if provided
        if ($request->filled('password')) {
            $farmer->password = Hash::make($request->password);
        }

        $farmer->save();

        return redirect()->route('farmer.farmer.profile.show')->with('success', 'Profile updated successfully.');
    }
}
