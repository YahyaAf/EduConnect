<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use App\Repositories\UserRepository;  // Import du Repository

class ProfileController extends Controller
{
    protected $userRepository;

    // Injection du Repository
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function show()
    {
        return response()->json([
            'user' => Auth::user()
        ]);
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $validatedData = $request->validated();

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::delete($user->photo);
            }

            $path = $request->file('photo')->store('profile_photos', 'public');
            $validatedData['photo'] = $path;
        }

        $user->update($validatedData);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function destroy()
    {
        $user = Auth::user();
        $this->userRepository->deleteUser($user);
        
        Auth::logout();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
