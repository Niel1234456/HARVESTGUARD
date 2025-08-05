<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farmer;
use Illuminate\Support\Facades\Hash;

class FarmerAuthController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('farmer.auth.forgot-password');
    }

    public function verifyFullName(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
        ]);

        $farmer = Farmer::where('first_name', $request->first_name)
                        ->where('birth_date', $request->birth_date)
                        ->first();

        if ($farmer) {
            return redirect()->route('farmer.farmer.reset-password', ['id' => $farmer->id]);
        }

        return back()->withErrors(['first_name' => 'Hindi natagpuan ang Pangalan o Mali ang Petsa ng Kapanganakan.']);
    }

    public function showResetPasswordForm($id)
    {
        return view('farmer.auth.reset-password', compact('id'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $farmer = Farmer::findOrFail($id);

        if (Hash::check($request->password, $farmer->password)) {
            return back()->with('error', 'Ang bagong Password na iyong nilagay ay dapat hindi pareho sa luma.');
        }

        $farmer->password = Hash::make($request->password);
        $farmer->save();

        return redirect()->route('farmer.farmer.reset-password', ['id' => $id])
                         ->with('success', 'Ang iyong Password ay napalitan na, Maaari ka nang mag-log in.');
    }
}
