<?php

namespace App\Models;

use App\Observers\UserObserver;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Passkeys\Passkey;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * Represents an authenticated user of the Desa Burujuljaya system.
 *
 * @property int $id
 * @property string $ulid 26-char sortable ULID (external identifier, prevents IDOR)
 * @property string $name
 * @property string $email
 * @property string|null $avatar Relative path in storage/app/public/avatars/
 * @property string|null $phone
 * @property bool $is_active Administrative status flag (distinct from soft delete)
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property int $login_attempts Brute-force counter; reset on successful login
 * @property Carbon|null $locked_until NULL = not locked; future timestamp = account locked
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Passkey> $passkeys
 * @property-read int|null $passkeys_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, Permission> $teams
 * @property-read int|null $teams_count
 *
 * @method static Builder<static>|User active()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User newModelQuery()
 * @method static Builder<static>|User newQuery()
 * @method static Builder<static>|User onlyTrashed()
 * @method static Builder<static>|User permission($permissions, bool $without = false)
 * @method static Builder<static>|User query()
 * @method static Builder<static>|User role($roles, ?string $guard = null, bool $without = false)
 * @method static Builder<static>|User team($teams, bool $without = false)
 * @method static Builder<static>|User whereAvatar($value)
 * @method static Builder<static>|User whereCreatedAt($value)
 * @method static Builder<static>|User whereDeletedAt($value)
 * @method static Builder<static>|User whereEmail($value)
 * @method static Builder<static>|User whereEmailVerifiedAt($value)
 * @method static Builder<static>|User whereId($value)
 * @method static Builder<static>|User whereIsActive($value)
 * @method static Builder<static>|User whereLockedUntil($value)
 * @method static Builder<static>|User whereLoginAttempts($value)
 * @method static Builder<static>|User whereName($value)
 * @method static Builder<static>|User wherePassword($value)
 * @method static Builder<static>|User wherePhone($value)
 * @method static Builder<static>|User whereRememberToken($value)
 * @method static Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static Builder<static>|User whereTwoFactorSecret($value)
 * @method static Builder<static>|User whereUlid($value)
 * @method static Builder<static>|User whereUpdatedAt($value)
 * @method static Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|User withoutPermission($permissions)
 * @method static Builder<static>|User withoutRole($roles, ?string $guard = null)
 * @method static Builder<static>|User withoutTeam($teams)
 * @method static Builder<static>|User withoutTrashed()
 *
 * @mixin \Eloquent
 */
#[Fillable(['name', 'email', 'password', 'avatar', 'phone', 'is_active'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasRoles;
    use Notifiable;
    use PasskeyAuthenticatable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * Boot the model.
     *
     * Automatically generates a ULID on creation if one is not already set.
     * ULIDs are monotonically sortable and safe for public-facing URLs.
     */
    protected static function booted(): void
    {
        static::creating(static function (User $user): void {
            if (empty($user->ulid)) {
                $user->ulid = (string) Str::ulid();
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'is_active' => 'boolean',
            'login_attempts' => 'integer',
            'locked_until' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Account Lockout Helpers
    // Business logic (throttle decision, email notification) lives in AuthService.
    // These methods are intentionally simple state-mutation helpers.
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Determine whether the account is currently locked out.
     *
     * Returns true only if `locked_until` is set to a future point in time.
     */
    public function isLocked(): bool
    {
        return $this->locked_until !== null && $this->locked_until->isFuture();
    }

    /**
     * Increment the failed login attempt counter by one.
     *
     * Uses Eloquent's atomic `increment()` to avoid race conditions.
     */
    public function incrementLoginAttempts(): void
    {
        $this->increment('login_attempts');
    }

    /**
     * Reset failed login counter and remove any active lockout.
     *
     * Called upon successful authentication.
     * Uses `forceFill()` because these fields are not mass-assignable.
     */
    public function resetLoginAttempts(): void
    {
        $this->forceFill([
            'login_attempts' => 0,
            'locked_until' => null,
        ])->saveQuietly();
    }

    /**
     * Lock the account for the specified number of minutes.
     *
     * Uses `forceFill()` because `locked_until` is not mass-assignable.
     */
    public function lockAccount(int $minutes = 15): void
    {
        $this->forceFill([
            'locked_until' => now()->addMinutes($minutes),
        ])->saveQuietly();
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Return the fully-qualified public URL for the user's avatar.
     *
     * Falls back to null when no avatar is set, letting the frontend
     * render initials instead.
     */
    public function avatarUrl(): ?string
    {
        return $this->avatar
            ? Storage::disk('public')->url($this->avatar)
            : null;
    }

    /**
     * Return a two-letter initials string derived from the user's name.
     * Used as fallback avatar in the UI.
     */
    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->take(2)
            ->map(fn (string $word) => Str::upper(Str::substr($word, 0, 1)))
            ->implode('');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Query Scopes
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Scope a query to only include active users.
     *
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
