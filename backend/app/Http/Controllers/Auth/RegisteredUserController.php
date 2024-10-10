<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'second_name' => $request->second_name,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect()->route('welcome')->with('success', 'Signup successful! Please verify your email.');
    }
}
