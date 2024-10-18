<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\URL;

class TemporaryUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'second_name',
        'email',
        'birth_date',
        'phone',
        'password',
        'email_verification_token',
    ];

    // Method to send email verification notification

public function sendEmailVerificationNotification()
{
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify', // The name of the verification route
        now()->addMinutes(60), // Expiry time (e.g., 60 minutes)
        ['id' => $this->id, 'hash' => $this->email_verification_token] // Parameters
    );

    // Send the email
    Mail::to($this->email)->send(new EmailVerification($verificationUrl));
}
}
