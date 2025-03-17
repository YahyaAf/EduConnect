<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }
   
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $photoPath, 
        ]);

        return response()->json([
            'message' => 'User has registered successfully',
            'user' => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        if (!Auth::attempt($validatedData)) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successfully',
            'user' => $user,
            'token' => $token
        ], 200);
    }



}
