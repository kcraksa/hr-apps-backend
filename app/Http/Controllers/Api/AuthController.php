<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\DataNotFoundException;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifiedEmail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!$validated) {
            return ApiResponse::error("User has been registered", 400);
        }

        $checkUser = User::where("email", $request->email)->first();
        if ($checkUser) {
            return ApiResponse::error("User has been registered", 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate token for the registered user
        $token = $user->createToken('authToken')->plainTextToken;

        return ApiResponse::success([
            'user' => $user,
            'token' => $token,
        ], "User has been registered", 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('nip', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return ApiResponse::success([
              'user' => $user,
              'token' => $token,
            ], "Login successfull");
        } else {
            return ApiResponse::error("Invalid Credential", 401);
        }
    }

    public function emailVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'new_password' => 'required',
            'confirm_new_password' => 'required|same:new_password',
        ]);

        $user = User::where("email", $request->email)->first();
        if (!$user) {
            throw new DataNotFoundException();
        }

        Mail::to($request->email)->send(new VerifiedEmail("123456"));
    }
}
