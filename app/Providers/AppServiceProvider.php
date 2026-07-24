<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureSuperAdminGate();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * Grant super-admin users access to every gate/policy check.
     *
     * Returning `true` from Gate::before() short-circuits all downstream
     * permission checks. Returning `null` defers to the normal check chain,
     * which is what all other roles do.
     *
     * This is the recommended Spatie approach: the super-admin role itself
     * has NO explicit permissions; the gate bypass covers everything.
     * This avoids the need to assign every new permission to the role on creation.
     */
    protected function configureSuperAdminGate(): void
    {
        Gate::before(function (User $user, string $ability): ?bool {
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}
