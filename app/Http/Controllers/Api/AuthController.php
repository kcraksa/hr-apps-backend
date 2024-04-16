<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;

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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
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
}
