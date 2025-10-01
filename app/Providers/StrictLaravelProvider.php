<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Sleep;
use Illuminate\Validation\Rules\Password;

final class StrictLaravelProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Model::unguard(! app()->isProduction());
        Model::shouldBeStrict(! app()->isProduction());
        DB::prohibitDestructiveCommands(app()->isProduction());
        Password::defaults(fn (): ?Password => app()->isProduction() ? Password::min(12)->max(255)->uncompromised() : null);
        Date::use(CarbonImmutable::class);
        Vite::useAggressivePrefetching();

        RateLimiter::for('api', fn () => Limit::perMinute(maxAttempts: 60));
        RateLimiter::for('web', fn () => Limit::perMinute(100));

        if (app()->runningUnitTests()) {
            Http::preventStrayRequests();
            Sleep::fake();
        }

        if (app()->isProduction()) {
            URL::forceHttps();
        }
    }
}
