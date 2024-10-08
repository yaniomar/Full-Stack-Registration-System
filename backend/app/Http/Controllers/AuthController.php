<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import the User model
use Illuminate\Support\Facades\Hash; // Import Hash for password hashing

class AuthController extends Controller
{
    // Show the signup form
    public function showSignupForm()
    {
        return view('auth.signup'); // Ensure this view exists
    }

    // Handle the signup process
    public function signup(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'birth_date' => 'required|date',
            'phone' => 'required|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user
        User::create([
            'first_name' => $request->input('first_name'),
            'second_name' => $request->input('second_name'),
            'email' => $request->input('email'),
            'birth_date' => $request->input('birth_date'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')), // Hash the password
            'admin' => false, // Set admin to false by default
        ]);

        // Redirect to the signup page with a success message
        return redirect()->route('signup.form')->with('success', 'User registered successfully!');
    }
}
