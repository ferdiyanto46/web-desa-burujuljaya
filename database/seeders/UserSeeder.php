<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds the default Super Administrator account.
 *
 * Credentials are read from environment variables so they are never
 * hardcoded in source control. Defaults are intentionally weak to force
 * the deployer to set proper values in .env before first deployment.
 *
 * Required .env variables:
 *   SUPER_ADMIN_NAME     — Display name           (default: "Super Administrator")
 *   SUPER_ADMIN_EMAIL    — Login email             (default: "superadmin@burujuljaya.desa.id")
 *   SUPER_ADMIN_PASSWORD — Initial password        (default: "ChangeThisPassword!123")
 *
 * WARNING: Change SUPER_ADMIN_PASSWORD before deploying to production.
 * The default password exists solely to allow fresh installations to boot.
 *
 * Idempotent: uses firstOrCreate keyed on email, then only assigns role
 * if not already present.
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('  Creating Super Administrator account...');

        $email = env('SUPER_ADMIN_EMAIL', 'superadmin@burujuljaya.desa.id');
        $name = env('SUPER_ADMIN_NAME', 'Super Administrator');
        $password = env('SUPER_ADMIN_PASSWORD', 'ChangeThisPassword!123');

        $superAdmin = User::withTrashed()->firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Restore if soft-deleted (edge case: someone accidentally deleted the account)
        if ($superAdmin->trashed()) {
            $superAdmin->restore();
        }

        // Ensure is_active is true in case account was manually deactivated
        if (! $superAdmin->is_active) {
            $superAdmin->forceFill(['is_active' => true])->saveQuietly();
        }

        // Assign role only if not already assigned (avoids duplicate pivot rows)
        if (! $superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole('super-admin');
        }

        $this->command->info("  Super Administrator created: {$email}");

        if ($password === 'ChangeThisPassword!123') {
            $this->command->warn('  [WARNING] Default password detected. Set SUPER_ADMIN_PASSWORD in .env before production deployment!');
        }
    }
}
