<?php
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

class VerificationController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        $user = Admin::findOrFail($id);
    
        // Check if the hash matches
        if (Hash::check($user->getEmailForVerification(), $hash)) {
            $user->markEmailAsVerified(); // This will set the `email_verified_at` timestamp
    
            event(new Verified($user));
    
            return redirect()->route('admin.dashboard')->with('status', 'Your email has been verified!');
        }
    
        return redirect()->route('admin.login')->with('error', 'Invalid verification link!');
    }
    

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('admin.dashboard');
        }
    
        $request->user()->sendEmailVerificationNotification();
    
        return redirect()->route('admin.verification.notice')
                         ->with('status', 'Verification link sent!');
    }
}
