<?php

namespace App\Http\Controllers\Farmer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    
    public function create(): View
    {
        return view('farmer.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',           
            'birth_date' => 'required|date',
            'gender' => 'required|string',
            'street_address' => 'required|string|max:255',
            'street_address2' => 'nullable|string|max:255',
            'country' => 'required|string',
            'province' => 'required|string', 
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'postal_code' => 'required|numeric',
            'farmers_activity' => 'nullable|string|max:255',
            'id_type' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:50048',
            'crop_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:50048',  
            'captcha' => 'required|captcha',  ], [
                'captcha.required' => 'Please solve the CAPTCHA to proceed.',
                'captcha.captcha' => 'The CAPTCHA is incorrect. Please try again.',
        ]);

        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = time() . '.' . $request->file('profile_picture')->extension();
            $request->file('profile_picture')->move(public_path('images/profile_pictures'), $profilePicturePath);
        } else {
            $profilePicturePath = $farmer->profile_picture; // Retain the existing profile picture
        }

        $cropPicturePath = null;
        if ($request->hasFile('crop_picture')) {
            $cropPicturePath = $request->file('crop_picture')->store('crop_pictures', 'public');  // Store the crop picture
        }

        // Create the farmer record
        $farmer = Farmer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial,
            'password' => Hash::make($request->password),
            'phone' => $request->phone ?? null, // Ensure phone is null if not provided
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'street_address' => $request->street_address,
            'street_address2' => $request->street_address2,
            'country' => $request->country,
            'province' => $request->province, // Save province
            'city' => $request->city,
            'region' => $request->region,
            'postal_code' => $request->postal_code,
            'farmers_activity' => $request->farmers_activity,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            'profile_picture' => $profilePicturePath,
            'crop_picture' => $cropPicturePath,  // Save crop picture path in database
        ]);

        // Fire the Registered event (optional)
        event(new Registered($farmer));

        // Redirect to the login page with a success message
        return redirect()->route('farmer.login')->with('success', 'Registration successful! Please login.');
    }

    /**
     * Reload CAPTCHA image (AJAX request).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reloadCaptcha(){

        return response () ->json(['captcha'=>captcha_img('math')]);
        }
}
