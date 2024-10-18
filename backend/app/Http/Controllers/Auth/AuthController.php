<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TemporaryUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use App\Mail\EmailVerification;

class AuthController extends Controller
{
    public function showSignupForm()
    {
        return view('auth.signup'); // Ensure you have this view created
    }

    public function signup(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:temporary_users',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the temporary user
        $temporaryUser = TemporaryUser::create([
            'first_name' => $request->first_name,
            'second_name' => $request->second_name,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'email_verification_token' => bin2hex(random_bytes(32)), // Generate a token
        ]);

        // Send the email verification notification
        $temporaryUser->sendEmailVerificationNotification();

        // Redirect to the verification notice page
        return redirect()->route('verification.notice')
            ->with('success', 'Please check your email to verify your account.');
    }
}
