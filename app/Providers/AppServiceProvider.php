<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Laravel merges default auth password brokers into published config; this API intentionally has none.
        config([
            'auth.defaults.passwords' => null,
            'auth.passwords' => [],
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureModels();
        $this->configureCommands();
        $this->configureRateLimiting();
        $this->configureGates();
    }

    private function configureGates(): void
    {
        Gate::define('view', static fn (?User $authenticated, User $user): bool => $authenticated?->id === $user->id);
    }

    private function configureModels(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request): Limit {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request): Limit {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('reviews', function (Request $request): Limit {
            return Limit::perMinute(3)->by($request->ip());
        });
    }
}
