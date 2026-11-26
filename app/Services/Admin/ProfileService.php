<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class ProfileService
{
    /**
     * Update the admin profile.
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->fill([
            'name' => $data['name'] ?? $user->name,
            'username' => $data['username'] ?? $user->username,
            'email' => $data['email'] ?? $user->email,
            'phone' => $data['phone'] ?? $user->phone,
            'job_title' => $data['job_title'] ?? $user->job_title,
            'bio' => $data['bio'] ?? $user->bio,
        ]);

        if (array_key_exists('avatar', $data)) {
            $user->avatar = $data['avatar'];
        }

        $user->save();

        return $user;
    }

    /**
     * Update the admin password.
     *
     * @throws Exception if current password doesn't match
     */
    public function updatePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new Exception('Password saat ini tidak sesuai.', 400);
        }

        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
