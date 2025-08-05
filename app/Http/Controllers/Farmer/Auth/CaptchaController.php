<?php

namespace App\Http\Controllers\Farmer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
class CaptchaController extends Controller
{
    public function create(){
        return view('farmer.auth.register');
}
public function reloadCaptcha(){

return response () ->json(['captcha'=>captcha_img('math')]);
}
public function post(Request $request){

    $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:farmers,email',
        'phone' => 'required|numeric',
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
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:20048',
        'crop_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:20048', 
        'captcha' => 'required|captcha',
    ]);
    return "registered succesful";
    }
}
