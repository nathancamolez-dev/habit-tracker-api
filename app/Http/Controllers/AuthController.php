<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json(
            ['token' => $user->createToken('auth_token')->plainTextToken, ]
        );
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user || Hash::check($request->password, $user->password)) {
            return [
                'token' => $user->createToken('auth_token')->plainTextToken,
            ];
        }

        throw ValidationException::withMessages([
            'email'    => ['The provided credentials are incorrect.'],
            'password' => ['The provided credentials are incorrect.'],
        ]);
    }
}
