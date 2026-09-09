<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    // Verified email
    public function verifyEmailVerification()
    {
        // Update
        User::where('id', Auth::user()->id)->update([
            'email_verified_at' => now()
        ]);

        // Page redirect 
        return redirect()->route('dashboard.user.overview');
    }

    // Resend Email Verification
    public function resendEmailVerification()
    {
        // Queries
        $user = User::where('id', Auth::user()->id)->where('status', 1)->first();

        // Send Email
        try {            
            $user->newEmail($user->email);
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->route('dashboard.user.overview')->with('error', 'Email service not available.');
        }

        // Page redirect 
        return redirect()->route('dashboard.user.overview')->with('success', 'Mail Sent.');
    }
}