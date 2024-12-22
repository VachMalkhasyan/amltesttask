<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verification_token' => Str::random(32),
        ]);

        Mail::raw("Click the link to verify your email: " . url('/api/verify/' . $user->email_verification_token), function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Verify Email');
        });

        return response()->json(['message' => 'Registration successful. Please verify your email.'], 201);
    }

    public function verify($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        $user->update([
            'email_verification_token' => null,
            'is_verified' => true,
        ]);

        return response()->json(['message' => 'Email verified successfully']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Debug: Check if email and password are correct
        $email = $request->email;
        $password = $request->password;

        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return response()->json(['message' => 'Invalid credentials. Check your email or password'], 401);
        }

        $user = Auth::user();

        // Check if the email is verified
        if (!$user->is_verified) {
            return response()->json(['message' => 'Please verify your email before logging in.'], 403);
        }

        // Generate an API token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout successful']);
    }
}
