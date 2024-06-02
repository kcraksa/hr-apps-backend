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
use Ichtrojan\Otp\Otp;
use App\Models\Module;
use App\Models\FunctionModel;
use App\Models\Role;

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
            $roles = [];

            $user->last_login = now();
            $user->save();

            if ($user->email_verified_at === null) {
                $token = $user->createToken('emailVerificationToken', ['email-verification'])->plainTextToken;
            } else {
                $token = $user->createToken('authToken', ['apps'])->plainTextToken;
            }

            if ($user->is_document_complete == 1) {
                $roles = $this->getUserRoles($user->id);
            }

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
        $otp = (new Otp)->generate($request->email, 'numeric', 6, 5);

        Mail::to($request->email)->send(new VerifiedEmail($otp->token));

        return ApiResponse::success(null, "Email has been sent");
    }

    public function emailOtpVerification(Request $request)
    {
        try {
            $userInformation = $request->user();
            $checkOtp = (new Otp)->validate($userInformation->email, $request->otp);

            if ($checkOtp) {
                $user = User::find($userInformation->id);
                $token = $userInformation->createToken('authToken', ['apps'])->plainTextToken;

                $userInformation->currentAccessToken()->delete();
                return ApiResponse::success([
                    'user' => $user,
                    'role' => $this->getUserRoles($user->id),
                    'token' => $token,
                ], "Email has been verified");
            }
            return ApiResponse::error("Invalid Credential", 401);
        } catch (\Throwable $th) {
            return ApiResponse::error("Invalid Credential", 401);
        }
    }

    public function getUserRoles($user_id)
    {
        return Module::whereHas('functions', function ($query) use ($user_id) {
            $query->whereHas('role', function ($roleQuery) use ($user_id) {
                $roleQuery->where('user_id', $user_id);
            });
        })->with(['functions' => function ($query) use ($user_id) {
            $query->whereHas('role', function ($roleQuery) use ($user_id) {
                $roleQuery->where('user_id', $user_id);
            })->with(['role' => function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            }]);
        }])->get();
    }
}
