<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileRepository
{
    /**
     * Delete a user and their profile photo if exists.
     *
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user)
    {
        if ($user->photo) {
            Storage::delete($user->photo);
        }

        return $user->delete();
    }

    /**
     * Update the user's profile.
     *
     * @param User $user
     * @param array $validatedData
     * @return bool
     */
    public function updateUser(User $user, array $validatedData)
    {
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        return $user->update($validatedData);
    }
}
