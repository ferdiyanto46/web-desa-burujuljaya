<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\DatabaseManager;

final class UserService
{
    public function __construct(
        private readonly DatabaseManager $db,
        private readonly Hasher $hash
    ) {}

    /**
     * Create a new user and assign a role.
     *
     * @param  array<string, mixed>  $data
     */
    public function createUser(array $data, string $role): User
    {
        return $this->db->transaction(function () use ($data, $role) {
            $user = clone new User;
            $user->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $this->hash->make((string) $data['password']),
                'phone' => $data['phone'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);
            $user->save();

            $user->assignRole($role);

            return $user;
        });
    }

    /**
     * Update user profile information.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->fill([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'phone' => $data['phone'] ?? $user->phone,
        ]);

        if (isset($data['password']) && is_string($data['password'])) {
            $user->password = $this->hash->make($data['password']);
        }

        $user->save();

        return $user;
    }

    /**
     * Ban or deactivate a user.
     */
    public function banUser(User $user): User
    {
        $user->update(['is_active' => false]);

        return $user;
    }

    /**
     * Unban or activate a user.
     */
    public function unbanUser(User $user): User
    {
        $user->update(['is_active' => true]);

        return $user;
    }

    /**
     * Assign a new role, replacing old ones.
     */
    public function syncRole(User $user, string $role): User
    {
        $user->syncRoles([$role]);

        return $user;
    }
}
