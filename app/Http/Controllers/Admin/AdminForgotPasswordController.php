<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
     $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        $token = Str::random(60);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        $resetLink = route('admin.admin.reset.password.form', ['token' => $token]);

        Mail::send('admin.auth.reset-email', ['resetLink' => $resetLink], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Admin Password Reset Link');
        });

        return back()->with('status', 'Password reset link sent to your email.');
    }
    public function showResetPasswordForm($token)
    {
        return view('admin.auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:password_resets,email',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $resetRequest = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();
    
        if (!$resetRequest) {
            return back()->withErrors(['token' => 'Invalid or expired token']);
        }
    
        $admin = Admin::where('email', $request->email)->first();
    
        if (!$admin) {
            return back()->withErrors(['email' => 'No admin account found with this email.']);
        }
    
        // **Check if the new password matches the current password**
        if (Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['password' => 'New password cannot be the same as the current password.']);
        }
    
        \Log::info('Before Password Update:', ['email' => $admin->email, 'password' => $admin->password]);
        
        $admin->password = Hash::make($request->password);
        $admin->save();
    
        \Log::info('After Password Update:', ['email' => $admin->email, 'password' => $admin->password]);
        DB::table('password_resets')->where('email', $request->email)->delete();
    
        return redirect()->route('admin.login')->with('status', 'Password has been reset successfully.');
    }
    
}

