<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json(
            ['token' => $user->createToken('auth_token')->plainTextToken, ]
        );
    }
}
