<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     *
     * Cached as a static property so all factory-created users share the same
     * hashed password within a single test run — avoids repeated bcrypt calls.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * Note: `ulid` is intentionally omitted here; it is auto-generated
     * by the User::boot() creating listener (Str::ulid()).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'avatar' => null,
            'phone' => fake()->optional(0.6)->numerify('08##########'),
            'is_active' => true,
            'email_verified_at' => now(),
            'password' => static::$password ??= bcrypt('password'),
            'login_attempts' => 0,
            'locked_until' => null,
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user account is inactive (disabled by admin).
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the account is currently locked out.
     *
     * Useful for testing login lockout behavior without having to
     * simulate multiple failed login attempts.
     */
    public function locked(): static
    {
        return $this->state(fn (array $attributes) => [
            'login_attempts' => 5,
            'locked_until' => now()->addMinutes(15),
        ]);
    }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at' => now(),
        ]);
    }
}
