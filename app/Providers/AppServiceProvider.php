<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\Enums\ShortenerMethodEnum;
use App\interfaces\ShortenerMethod;
use App\Shorteners\MixedShortener;
use App\Shorteners\NumberShortener;
use App\Shorteners\StringShortener;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
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
        $this->app->bind(ShortenerMethod::class, function () {
            $method = request()->enum('method', ShortenerMethodEnum::class);

            return match ($method) {
                ShortenerMethodEnum::STRING => new StringShortener(),
                ShortenerMethodEnum::NUMBER => new NumberShortener(),
                ShortenerMethodEnum::MIXED => new MixedShortener(),
                default => new StringShortener(),
            };
        });
    }
}
