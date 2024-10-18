<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TemporaryUser;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected $redirectTo = '/'; // Redirect to the welcome page after verification

    public function __construct()
    {
        $this->middleware('signed')->only('verify'); // Ensure that the verification route is signed
        $this->middleware('throttle:6,1')->only('verify', 'resend'); // Limit verification requests
    }

    public function show()
    {
        return view('auth.verification'); // Ensure this view exists
    }

    /**
     * Handle the email verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  string  $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, $id, $hash)
    {
        // Use a database transaction to ensure both actions are atomic
        DB::transaction(function () use ($id, $hash) {
            // Find the temporary user by ID and verify the token
            $temporaryUser = TemporaryUser::where('id', $id)
                ->where('email_verification_token', $hash)
                ->first();

            // Log the ID and hash for debugging
            Log::info("Verification attempt for ID: {$id}, Hash: {$hash}");

            if (!$temporaryUser) {
                // Handle invalid token case
                Log::error("Verification failed for user ID: {$id}, Hash: {$hash}");
                return redirect('/')->with('error', 'Invalid verification link.');
            }

            // Move user data from the temporary_users table to the users table
            User::create([
                'first_name' => $temporaryUser->first_name,
                'second_name' => $temporaryUser->second_name,
                'email' => $temporaryUser->email,
                'birth_date' => $temporaryUser->birth_date,
                'phone' => $temporaryUser->phone,
                'password' => $temporaryUser->password, // Password already hashed during registration
                'email_verified_at' => now(), // Mark email as verified
            ]);

            Log::info("Temporary user {$temporaryUser->email} data moved, deleting temporary entry.");

            // Delete the temporary user after moving to the users table
            $temporaryUser->delete();
            Log::info("Temporary user {$temporaryUser->email} deleted successfully.");
        });

        // Redirect the user after verification
        return redirect($this->redirectTo)->with('success', 'Your email has been verified successfully.');
    }

    /**
     * Handle the action after email verification is successful.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function verified(Request $request)
    {
        return redirect($this->redirectTo)->with('success', 'Your email has been verified successfully.');
    }
}
