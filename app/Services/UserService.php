<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        if (isset($data['photo'])) {
            $data['photo'] = $data['photo']->store('photos', 'public');
        }

        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }

    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user();
        $token = $user->createToken('auth_Token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }
}
