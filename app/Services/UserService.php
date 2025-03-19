<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
    
        $user = $this->userRepository->create($data);
    
        $user->assignRole('student');
    
        return $user;
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
