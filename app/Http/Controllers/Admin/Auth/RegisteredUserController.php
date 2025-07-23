<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('admin.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Debugging: Log request data
        \Log::info($request->all());

        // Validate the request
        $request->validate([
            'email' => ['required', 'string', 'email', 'lowercase', 'max:255', 'unique:admins'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'contact_number' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'between:0,120'],
            'birthday' => ['nullable', 'date'],
            'office_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:50048', 
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:50048',
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'middle_initial' => ['nullable', 'string', 'max:1'],
            'position' => ['required', 'string', 'max:100'],
            'id_type' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'in:male,female,other'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'region' => ['required', 'string', 'max:100'],
        ]);

        // Handle the profile picture upload
        $profilePicturePath = 'default-profile.png';
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $profilePicturePath = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/profile_pictures'), $profilePicturePath);
        }

        // Handle the office picture upload
        $officePicturePath = 'default-office.png';
        if ($request->hasFile('office_picture')) {
            $file = $request->file('office_picture');
            $officePicturePath = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/office_pictures'), $officePicturePath);
        }

        // Create the admin record
        $admin = Admin::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'age' => $request->age,
            'birthday' => $request->birthday,
            'office_picture' => $officePicturePath,  
            'profile_picture' => $profilePicturePath,  
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial,
            'position' => $request->position,
            'id_type' => $request->id_type,
            'gender' => $request->gender,
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'province' => $request->province,
            'country' => $request->country,
            'region' => $request->region,
        ]);

        // Ensure email verification works
        if (method_exists($admin, 'sendEmailVerificationNotification')) {
            $admin->sendEmailVerificationNotification();
        }

        event(new Registered($admin));

        // Automatically log in the user
        Auth::login($admin);

        return redirect()->route('admin.verification.notice');
    }
}
