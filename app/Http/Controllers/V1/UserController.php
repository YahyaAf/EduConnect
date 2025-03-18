<?php

namespace App\Http\Controllers\V1;

use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->userService->register($request->all());

        return response()->json([
            'message' => 'User has registered successfully',
            'user' => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->userService->login($request->validated());

        if (!$result) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        return response()->json([
            'message' => 'Login successfully',
            'user' => $result['user'],
            'token' => $result['token']
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout successfully']);
    }

    public function refreshToken(Request $request)
    {
        $user = Auth::user();
        $request->user()->currentAccessToken()->delete();

        $newToken = $user->createToken('auth_Token')->plainTextToken;

        return response()->json([
            'message' => 'Token refreshed successfully',
            'token' => $newToken
        ]);
    }
}
